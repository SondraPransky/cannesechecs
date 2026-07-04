/**
 * build-wp-import.js — Génère wp-theme/wp-import-articles.php depuis index.html.
 *
 * Extrait ARTICLES + ARCHIVE_ORDER du site statique et produit un script PHP
 * d'import unique : posts CPT "actualite" + champs ACF + images téléchargées
 * depuis cannes-echecs.fr vers la médiathèque WordPress.
 *
 * Usage : node build-wp-import.js
 */
const fs = require('fs');
const path = require('path');

const html = fs.readFileSync(path.join(__dirname, 'index.html'), 'utf8');

function extractConst(name, terminator) {
  const start = html.indexOf('const ' + name + ' = ');
  if (start === -1) throw new Error(name + ' introuvable');
  const end = html.indexOf('\n' + terminator, start);
  if (end === -1) throw new Error('Fin de ' + name + ' introuvable');
  return html.slice(start, end + 1 + terminator.length);
}

const articlesSrc = extractConst('ARTICLES', '};');
const orderSrc = extractConst('ARCHIVE_ORDER', '];');
const ARTICLES = new Function(articlesSrc + '; return ARTICLES;')();
const ARCHIVE_ORDER = new Function(orderSrc + '; return ARCHIVE_ORDER;')();

// Date française → ISO (ex : "1er mars 2026" → "2026-03-01")
const MOIS = { janvier: 1, février: 2, fevrier: 2, mars: 3, avril: 4, mai: 5, juin: 6,
               juillet: 7, août: 8, aout: 8, septembre: 9, octobre: 10, novembre: 11, décembre: 12, decembre: 12 };
function frToIso(d) {
  const m = d.match(/(\d+)(?:er)?\s+([a-zéûôè]+)\s+(\d{4})/i);
  if (!m || !MOIS[m[2].toLowerCase()]) throw new Error('Date non reconnue : ' + d);
  return m[3] + '-' + String(MOIS[m[2].toLowerCase()]).padStart(2, '0') + '-' + String(m[1]).padStart(2, '0');
}

// Import du plus ancien au plus récent : les IDs WP croissants préservent
// l'ordre d'ARCHIVE_ORDER en cas de dates identiques.
const payload = [...ARCHIVE_ORDER].reverse()
  .filter(k => ARTICLES[k])
  .map((k, i) => {
    const a = ARTICLES[k];
    return {
      slug: k,
      title: a.title,
      date_iso: frToIso(a.date),
      cat: a.cat || 'Vie du club',
      badge: a.badge || 'Actualité',
      emoji: a.emoji || '♟',
      bg: a.bg || 'linear-gradient(135deg,#1e3a5f,#0a1f38)',
      img: a.img || '',
      gallery: a.gallery || [],
      body: a.body.trim(),
      order: i,
    };
  });

const json = JSON.stringify(payload, null, 0);
if (json.includes('CEJSON')) throw new Error('Le contenu contient le délimiteur heredoc CEJSON');

const php = `<?php
/**
 * wp-import-articles.php — import UNIQUE des ${payload.length} articles du site statique.
 * Généré par build-wp-import.js — ne pas éditer à la main.
 *
 * MODE D'EMPLOI
 *   1. Le thème Cannes Échecs et ACF Pro doivent être actifs.
 *   2. Copier ce fichier à la racine de WordPress (à côté de wp-load.php).
 *   3. Visiter : https://VOTRE-SITE/wp-import-articles.php?key=cannes-import-2027
 *      (patienter : ~${payload.length} articles + téléchargement des photos depuis cannes-echecs.fr)
 *   4. ⚠️ SUPPRIMER CE FICHIER du serveur une fois l'import terminé.
 *
 * Relançable sans risque : les articles déjà importés (même slug) sont ignorés,
 * les images déjà en médiathèque (même nom de fichier) sont réutilisées.
 */

define('CE_IMPORT_KEY', 'cannes-import-2027');
define('CE_IMG_BASE', 'https://cannes-echecs.fr/');

require __DIR__ . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

if (php_sapi_name() !== 'cli' && (($_GET['key'] ?? '') !== CE_IMPORT_KEY)) {
    http_response_code(403);
    exit('Accès refusé — ajouter ?key=... à l\\'URL (voir l\\'en-tête du fichier).');
}
if (!function_exists('update_field')) exit('ACF Pro doit être installé et actif.');
if (!post_type_exists('actualite'))   exit('Le thème Cannes Échecs doit être actif (CPT actualite).');

set_time_limit(0);
header('Content-Type: text/html; charset=utf-8');
echo '<pre style="font:13px/1.6 monospace">Import des articles Cannes Échecs…' . "\\n\\n";

function ce_import_image(string $rel, int $post_id): int {
    $name = pathinfo(basename($rel), PATHINFO_FILENAME);
    $existing = get_posts([
        'post_type' => 'attachment', 'name' => sanitize_title($name),
        'numberposts' => 1, 'fields' => 'ids', 'post_status' => 'inherit',
    ]);
    if ($existing) return (int) $existing[0];
    $url = CE_IMG_BASE . implode('/', array_map('rawurlencode', explode('/', $rel)));
    $id = media_sideload_image($url, $post_id, null, 'id');
    return is_wp_error($id) ? 0 : (int) $id;
}

$articles = json_decode(<<<'CEJSON'
${json}
CEJSON, true);

$done = 0; $skipped = 0; $img_fail = [];

foreach ($articles as $a) {
    if (get_page_by_path($a['slug'], OBJECT, 'actualite')) {
        $skipped++;
        echo "= {$a['slug']} : existe déjà, ignoré\\n"; flush();
        continue;
    }
    $post_id = wp_insert_post([
        'post_type'    => 'actualite',
        'post_status'  => 'publish',
        'post_name'    => $a['slug'],
        'post_title'   => $a['title'],
        'post_content' => $a['body'],
        'post_date'    => $a['date_iso'] . ' 12:00:00',
        'menu_order'   => $a['order'],
    ], true);
    if (is_wp_error($post_id)) {
        echo "✗ {$a['slug']} : " . $post_id->get_error_message() . "\\n"; flush();
        continue;
    }
    update_field('categorie',   $a['cat'],   $post_id);
    update_field('badge',       $a['badge'], $post_id);
    update_field('emoji',       $a['emoji'], $post_id);
    update_field('bg_gradient', $a['bg'],    $post_id);

    if (!empty($a['img'])) {
        $att = ce_import_image($a['img'], $post_id);
        if ($att) { update_field('image_principale', $att, $post_id); set_post_thumbnail($post_id, $att); }
        else $img_fail[] = $a['img'];
    }
    if (!empty($a['gallery'])) {
        $ids = [];
        foreach ($a['gallery'] as $g) {
            $att = ce_import_image($g, $post_id);
            if ($att) $ids[] = $att; else $img_fail[] = $g;
        }
        if ($ids) update_field('gallery', $ids, $post_id);
    }
    $done++;
    echo "✓ {$a['slug']} : importé (" . (!empty($ids) ? count($ids) . ' photos' : (!empty($a['img']) ? '1 photo' : 'sans photo')) . ")\\n"; flush();
    $ids = [];
}

echo "\\n──────────────────────────────\\n";
echo "$done importés · $skipped déjà présents\\n";
if ($img_fail) {
    echo "⚠ Images en échec (à importer à la main dans la médiathèque) :\\n - " . implode("\\n - ", array_unique($img_fail)) . "\\n";
}
echo "\\n⚠️  SUPPRIMEZ CE FICHIER DU SERVEUR MAINTENANT.\\n</pre>";
`;

const out = path.join(__dirname, 'wp-theme', 'wp-import-articles.php');
fs.writeFileSync(out, php);

const totalPhotos = payload.reduce((n, a) => n + (a.img ? 1 : 0) + a.gallery.length, 0);
console.log('✓ ' + payload.length + ' articles extraits (du ' + payload[0].date_iso + ' au ' + payload[payload.length - 1].date_iso + ')');
console.log('✓ ' + totalPhotos + ' références photos');
console.log('✓ Écrit : wp-theme/wp-import-articles.php (' + Math.round(php.length / 1024) + ' Ko)');
