/**
 * build-wp-theme.js — Génère le thème WordPress multi-pages depuis index.html.
 *
 * index.html reste la source de vérité du site statique (GitHub Pages).
 * Ce script en extrait CSS, JS et HTML pour produire wp-theme/cannes-echecs/ :
 *   - assets/css/main.css        (copie du <style>)
 *   - assets/js/main.js          (JS épuré du routeur SPA + shim navigation WP)
 *   - header.php / footer.php    (navbar et footer avec vrais liens <a href>)
 *   - page-{slug}.php × 10       (pages statiques)
 *   - _dev/fragment-*.html       (home, actualites, article — à convertir en PHP à la main)
 *
 * Usage : node build-wp-theme.js
 */
const fs = require('fs');
const path = require('path');

const SRC = path.join(__dirname, 'index.html');
const THEME = path.join(__dirname, 'wp-theme', 'cannes-echecs');
const DEV = path.join(THEME, '_dev');

const html = fs.readFileSync(SRC, 'utf8');
const lines = html.split('\n');

// ─────────────────────────────────────────────────────────────
// Helpers
// ─────────────────────────────────────────────────────────────
function extractBetween(startMarker, endMarker, from = 0) {
  const s = html.indexOf(startMarker, from);
  if (s === -1) throw new Error('Marqueur introuvable : ' + startMarker);
  const e = html.indexOf(endMarker, s + startMarker.length);
  if (e === -1) throw new Error('Fin introuvable : ' + endMarker);
  return html.slice(s, e + endMarker.length);
}

const PAGES = ['home','club','actualites','article','activites','horaires','adhesion','contact','tournois','fij','partenaires','agenda','organigramme'];
const STATIC_PAGES = ['club','activites','horaires','adhesion','contact','tournois','fij','partenaires','agenda','organigramme'];

function phpUrl(slug) {
  return slug === 'home'
    ? "<?php echo esc_url(home_url('/')); ?>"
    : "<?php echo esc_url(home_url('/" + slug + "/')); ?>";
}

// Transforme les onclick de navigation SPA en vrais liens crawlables.
function transformLinks(frag) {
  // <a onclick="goTo('x')" ...> → <a href="..." ...>
  frag = frag.replace(/<a\b([^>]*?)onclick="goTo\('(\w+)'\)"([^>]*)>/g,
    (m, pre, slug, post) => '<a' + pre + 'href="' + phpUrl(slug) + '"' + post + '>');
  // <a onclick="goToTab('x','y')" ...> → <a href="...#x-y" ...>
  frag = frag.replace(/<a\b([^>]*?)onclick="goToTab\('(\w+)','(\w+)'\)"([^>]*)>/g,
    (m, pre, page, tab, post) => '<a' + pre + 'href="' + phpUrl(page) + '#' + page + '-' + tab + '"' + post + '>');
  // Boutons navbar → liens
  frag = frag.replace(/<button class="(nav-link[^"]*)" id="(nav-\w+)" onclick="goTo\('(\w+)'\)">([\s\S]*?)<\/button>/g,
    (m, cls, id, slug, label) => '<a class="' + cls + '" id="' + id + '" href="' + phpUrl(slug) + '">' + label + '</a>');
  frag = frag.replace(/<button class="nav-cta" onclick="goTo\('(\w+)'\)">([\s\S]*?)<\/button>/g,
    (m, slug, label) => '<a class="nav-cta" href="' + phpUrl(slug) + '">' + label + '</a>');
  // Menu mobile → liens
  frag = frag.replace(/<button class="((?:nav-mobile-link|nav-mobile-cta)[^"]*)" onclick="mobileGoTo\('(\w+)'\)">([\s\S]*?)<\/button>/g,
    (m, cls, slug, label) => '<a class="' + cls + '" href="' + phpUrl(slug) + '">' + label + '</a>');
  frag = frag.replace(/<button class="(nav-mobile-link[^"]*)" onclick="mobileGoTo\('(\w+)','(\w+)'\)">([\s\S]*?)<\/button>/g,
    (m, cls, page, tab, label) => '<a class="' + cls + '" href="' + phpUrl(page) + '#' + page + '-' + tab + '">' + label + '</a>');
  // Logo du thème
  frag = frag.replace(/src="logo-cannes-echecs\.png"/g,
    'src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/logo-cannes-echecs.png"');
  return frag;
}

// ─────────────────────────────────────────────────────────────
// 1. CSS
// ─────────────────────────────────────────────────────────────
const cssStart = html.indexOf('<style>') + '<style>'.length;
const cssEnd = html.indexOf('</style>');
const css = html.slice(cssStart, cssEnd).trim() + `

/* =====================================================
   ADDITIONS WORDPRESS (multi-pages)
   ===================================================== */
.actu-card{display:block}
.archive-grid-section .navigation.pagination{margin-top:36px}
.archive-grid-section .nav-links{display:flex;gap:8px;justify-content:center;flex-wrap:wrap}
.archive-grid-section .page-numbers{display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;padding:0 12px;border:1.5px solid var(--border);border-radius:8px;background:#fff;color:var(--bleu);font-family:'Montserrat',sans-serif;font-size:13px;font-weight:600;text-decoration:none}
.archive-grid-section .page-numbers.current{background:var(--bleu);color:#fff;border-color:var(--bleu)}
.archive-grid-section .page-numbers:hover:not(.current){border-color:var(--gold)}
.article-nav{display:flex;justify-content:space-between;gap:16px;margin-top:28px;padding-top:24px;border-top:1px solid var(--border)}
.article-nav a{font-family:'Montserrat',sans-serif;font-size:12px;font-weight:600;color:var(--bleu);max-width:48%}
.article-nav a:hover{color:var(--gold-text)}
`;

// ─────────────────────────────────────────────────────────────
// 2. JS — extraction du dernier <script> + chirurgie
// ─────────────────────────────────────────────────────────────
const jsStart = html.lastIndexOf('<script>') + '<script>'.length;
const jsEnd = html.lastIndexOf('</script>');
let jsLines = html.slice(jsStart, jsEnd).split('\n');

// Blocs à retirer : données fournies par PHP (ACF) ou logique SPA obsolète.
// Chaque entrée : regex de la ligne de départ + terminateur exact en colonne 0
// (null = déclaration sur une seule ligne).
const REMOVALS = [
  { start: /^const FIJ_INSCRITS = \{/,   end: '};' },
  { start: /^const SAISON = /,           end: null },
  { start: /^const HORAIRES = \[/,       end: '];' },
  { start: /^const EQUIPE = \[/,         end: '];' },
  { start: /^const HELLOASSO = \{/,      end: '};' },
  { start: /^function goToTab\(/,        end: '}' },
  { start: /^function goToContactSujet\(/, end: '}' },
  { start: /^const pages = /,            end: null },
  { start: /^const PAGE_TITLES = \{/,    end: '};' },
  { start: /^function goTo\(/,           end: '}' },
  { start: /^function updateNavActive\(/, end: '}' },
  { start: /^const FIJ_DATE = /,         end: null },
  { start: /^const FIJ_OPEN = /,         end: null },
  { start: /^const FIJ_FIN {2}= /,       end: null },
  { start: /^const FIJ_RONDES = \[/,     end: '];' },
  { start: /^const ARTICLES = \{/,       end: '};' },
  { start: /^const ARCHIVE_ORDER = \[/,  end: '];' },
  { start: /^const EXTRAITS = \{/,       end: '};' },
  { start: /^function getCover\(/,       end: '}' },
  { start: /^function buildCard\(/,      end: '}' },
  { start: /^function renderArchiveGrid\(/, end: '}' },
  { start: /^function renderHomeActus\(/,   end: '}' },
  { start: /^function goToArticle\(/,    end: '}' },
];

function removeBlock(startRe, terminator, mustContain) {
  const i = jsLines.findIndex(l => startRe.test(l));
  if (i === -1) { console.warn('⚠ Bloc non trouvé : ' + startRe); return; }
  if (mustContain && !(jsLines[i + 1] || '').includes(mustContain)) {
    console.warn('⚠ IIFE ignorée (contenu inattendu) : ' + mustContain); return;
  }
  if (terminator === null) { jsLines.splice(i, 1); return; }
  const end = jsLines.findIndex((l, j) => j > i && l === terminator);
  if (end === -1) throw new Error('Terminateur "' + terminator + '" introuvable pour ' + startRe);
  jsLines.splice(i, end - i + 1);
}

REMOVALS.forEach(r => removeBlock(r.start, r.end));
// IIFE admin (?admin) et IIFE widget localStorage — remplacées par WP admin / ACF
removeBlock(/^\(function\(\)\{$/, '})();', 'admin');
removeBlock(/^\(function\(\)\{$/, '})();', 'ace_widget');
// Appels d'init devenus obsolètes
jsLines = jsLines.filter(l => !['renderArchiveGrid();', 'renderHomeActus();', "goTo('home');"].includes(l.trim()));
// Doc "comment ajouter un article" (obsolète sous WP : les articles sont des posts)
const docStart = jsLines.findIndex(l => l.includes('4. ARTICLES / ACTUALITÉS'));
if (docStart > 0) {
  const docEnd = jsLines.findIndex((l, j) => j > docStart && l.includes('Résumés affichés dans les cartes'));
  if (docEnd > docStart) jsLines.splice(docStart - 1, docEnd - docStart + 2);
}

const shim = `
// ═══════════════════════════════════════════════════════════
// WORDPRESS — NAVIGATION MULTI-PAGES (remplace le routeur SPA)
// CE_URLS, CE_HERO_MODE et les données ACF sont injectés par
// functions.php (wp_add_inline_script, avant ce fichier).
// ═══════════════════════════════════════════════════════════
function goTo(id) {
  if (window.CE_URLS && CE_URLS[id]) window.location.href = CE_URLS[id];
}
function goToTab(page, tab) {
  if (window.CE_URLS && CE_URLS[page]) window.location.href = CE_URLS[page] + (tab ? '#' + page + '-' + tab : '');
}
function goToArticle(slug) {
  if (window.CE_URLS && CE_URLS.actualites) window.location.href = CE_URLS.actualites + slug + '/';
}
function goToContactSujet(sujet) {
  if (window.CE_URLS && CE_URLS.contact) window.location.href = CE_URLS.contact + '#sujet-' + sujet;
}
function renderHomeActus() {}   // rendu côté PHP — front-page.php
function renderArchiveGrid() {} // rendu côté PHP — archive-actualite.php

// Lien de nav actif selon l'URL courante
(function() {
  if (!window.CE_URLS) return;
  var path = location.pathname;
  var map = { club:'nav-club', actualites:'nav-club', organigramme:'nav-club', horaires:'nav-club',
              adhesion:'nav-club', contact:'nav-club', activites:'nav-activites', tournois:'nav-tournois',
              fij:'nav-fij', partenaires:'nav-partenaires', agenda:'nav-agenda' };
  Object.keys(map).forEach(function(slug) {
    if (path.indexOf('/' + slug) === 0) {
      var el = document.getElementById(map[slug]);
      if (el) el.classList.add('active');
    }
  });
})();

// Hash à l'arrivée : #page-onglet (onglets) ou #sujet-xxx (objet contact)
(function() {
  var hash = location.hash.replace('#', '');
  if (!hash) return;
  if (hash.indexOf('sujet-') === 0) {
    var s = document.getElementById('cf-sujet');
    if (s) s.value = hash.slice(6);
    return;
  }
  var i = hash.indexOf('-');
  if (i > 0) {
    var btn = document.querySelector('[data-tab="' + hash.slice(i + 1) + '"]');
    if (btn) btn.click();
  }
})();

// Widget héro — mode choisi dans WP admin (Réglages CE → Infos Club)
(function() {
  if (!document.getElementById('hero-widget')) return;
  var mode = window.CE_HERO_MODE;
  if (mode && typeof HERO_WIDGETS !== 'undefined' && HERO_WIDGETS[mode]) { setHeroWidget(mode, window.CE_HERO_DATA); return; }
  var m = new Date().getMonth() + 1;
  setHeroWidget(m >= 6 && m <= 8 ? 'rentree' : 'fij');
})();

// Galerie d'article (single-actualite.php) + bouton de partage natif
(function() {
  if (window.CE_GALLERY && window.CE_GALLERY.length) currentGallery = window.CE_GALLERY;
  var bn = document.getElementById('share-native');
  if (bn && navigator.share) {
    bn.style.display = 'inline-flex';
    var bf = document.getElementById('share-fb'), bt = document.getElementById('share-tw');
    if (bf) bf.style.display = 'none';
    if (bt) bt.style.display = 'none';
  }
})();
`;

let js = jsLines.join('\n').replace(/\n{4,}/g, '\n\n\n') + '\n' + shim;
// Équipe : sous WP les photos sont des URLs absolues, pas des chemins photos/
js = js.replace("m.avatar.indexOf('photos/') === 0", "/^(https?:|photos\\/)/.test(m.avatar)");

// ─────────────────────────────────────────────────────────────
// 3. Fragments HTML : navbar, footer, lightbox, pages
// ─────────────────────────────────────────────────────────────
const navbar = transformLinks(extractBetween('<nav class="navbar">', '</nav>'));
const footer = transformLinks(extractBetween('<footer class="footer">', '</footer>'));
const lightbox = extractBetween('<div id="lightbox" class="lightbox">', '</div>');

const pageFragments = {};
for (const id of PAGES) {
  const startTag = '<div id="page-' + id + '" class="page">';
  const endTag = '</div><!-- fin page-' + id + ' -->';
  let frag = extractBetween(startTag.replace(' class="page">', ' class="page'), endTag);
  // class="page" ou class="page active" (home) → class="page active"
  frag = frag.replace(/^<div id="page-\w+" class="page( active)?">/,
    m => m.replace('class="page"', 'class="page active"'));
  pageFragments[id] = transformLinks(frag);
}

// ─────────────────────────────────────────────────────────────
// 4. Écriture des fichiers
// ─────────────────────────────────────────────────────────────
fs.mkdirSync(path.join(THEME, 'assets', 'css'), { recursive: true });
fs.mkdirSync(path.join(THEME, 'assets', 'js'), { recursive: true });
fs.mkdirSync(DEV, { recursive: true });

fs.writeFileSync(path.join(THEME, 'assets', 'css', 'main.css'), css);
fs.writeFileSync(path.join(THEME, 'assets', 'js', 'main.js'), js);

const headerPhp = `<?php
/**
 * header.php — Cannes Échecs
 * <head> + navbar. Metas SEO/OG : ce_meta_tags() dans functions.php (hook wp_head).
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/logo-cannes-echecs.png">
<link rel="apple-touch-icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/logo-cannes-echecs.png">
<meta name="theme-color" content="#1E3A5F">
<script defer data-domain="cannes-echecs.fr" src="https://plausible.io/js/script.js"></script>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<a class="skip-link" href="#app">Aller au contenu principal</a>

` + navbar + `

<main id="app" tabindex="-1">
`;

const footerPhp = `<?php
/**
 * footer.php — Cannes Échecs
 * Footer + lightbox partagée (galeries d'articles).
 */
?>
</main>

` + footer + `

` + lightbox + `

<?php wp_footer(); ?>
</body>
</html>
`;

fs.writeFileSync(path.join(THEME, 'header.php'), headerPhp);
fs.writeFileSync(path.join(THEME, 'footer.php'), footerPhp);

const PAGE_TITLES = {
  club: 'Le Club', activites: 'Nos Activités', horaires: 'Horaires & Tarifs',
  adhesion: 'Adhésion', contact: 'Contact', tournois: 'Tournois', fij: 'FIJ',
  partenaires: 'Partenaires', agenda: 'Agenda', organigramme: 'Organigramme',
};

for (const slug of STATIC_PAGES) {
  const tpl = `<?php
/**
 * Template de la page « ${PAGE_TITLES[slug]} » (slug WordPress : ${slug}).
 * Généré depuis index.html par build-wp-theme.js — ne pas éditer le HTML ici
 * sans reporter la modification dans index.html tant que les deux coexistent.
 */
get_header(); ?>

${pageFragments[slug]}

<?php get_footer(); ?>
`;
  fs.writeFileSync(path.join(THEME, 'page-' + slug + '.php'), tpl);
}

// Fragments à convertir manuellement en PHP (boucles WP)
fs.writeFileSync(path.join(DEV, 'fragment-home.html'), pageFragments.home);
fs.writeFileSync(path.join(DEV, 'fragment-actualites.html'), pageFragments.actualites);
fs.writeFileSync(path.join(DEV, 'fragment-article.html'), pageFragments.article);

console.log('✓ CSS       : ' + css.split('\n').length + ' lignes');
console.log('✓ JS        : ' + js.split('\n').length + ' lignes (routeur SPA retiré, shim WP ajouté)');
console.log('✓ header.php / footer.php');
console.log('✓ ' + STATIC_PAGES.length + ' templates page-{slug}.php');
console.log('✓ 3 fragments dans _dev/ (home, actualites, article)');
const residual = (js.match(/ARTICLES|ARCHIVE_ORDER|EXTRAITS\b/g) || []).length;
console.log(residual === 0 ? '✓ Aucune référence résiduelle à ARTICLES/EXTRAITS dans main.js'
                           : '⚠ ' + residual + ' références résiduelles ARTICLES/EXTRAITS à vérifier dans main.js');
