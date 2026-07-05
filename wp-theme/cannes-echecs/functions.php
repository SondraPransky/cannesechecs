<?php
/**
 * Cannes Échecs — functions.php
 * Thème multi-pages : CPT Actualité public, options ACF, données JS injectées,
 * metas SEO/OG par page, création des pages à l'activation.
 */

// ══════════════════════════════════════════════════════════
// THEME SETUP
// ══════════════════════════════════════════════════════════
function ce_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['script', 'style', 'gallery', 'caption', 'search-form', 'comment-list', 'comment-form']);
}
add_action('after_setup_theme', 'ce_setup');

// ══════════════════════════════════════════════════════════
// ENQUEUE CSS + JS (+ données ACF injectées avant main.js)
// ══════════════════════════════════════════════════════════
function ce_enqueue() {
    $dir = get_template_directory();
    $uri = get_template_directory_uri();
    wp_enqueue_style('ce-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Inter:wght@300;400;500;600&family=Montserrat:wght@500;600;700;800&display=swap', [], null);
    wp_enqueue_style('ce-main', $uri . '/assets/css/main.css', ['ce-fonts'], '2.0.' . filemtime($dir . '/assets/css/main.css'));
    wp_enqueue_script('ce-main', $uri . '/assets/js/main.js', [], '2.0.' . filemtime($dir . '/assets/js/main.js'), true);
    wp_add_inline_script('ce-main', ce_site_data_js(), 'before');
}
add_action('wp_enqueue_scripts', 'ce_enqueue');

// Désactiver les styles WordPress inutiles (optimisation)
function ce_dequeue_bloat() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'ce_dequeue_bloat', 100);

// ══════════════════════════════════════════════════════════
// CUSTOM POST TYPE : ACTUALITÉ — public, archive /actualites/
// ══════════════════════════════════════════════════════════
function ce_register_cpts() {
    register_post_type('actualite', [
        'labels' => [
            'name'               => 'Actualités',
            'singular_name'      => 'Actualité',
            'add_new'            => 'Ajouter',
            'add_new_item'       => 'Ajouter une actualité',
            'edit_item'          => 'Modifier l\'actualité',
            'new_item'           => 'Nouvelle actualité',
            'view_item'          => 'Voir l\'actualité',
            'search_items'       => 'Rechercher',
            'not_found'          => 'Aucune actualité trouvée',
            'not_found_in_trash' => 'Aucune actualité dans la corbeille',
            'all_items'          => 'Toutes les actualités',
            'menu_name'          => 'Actualités',
        ],
        'public'        => true,
        'show_in_rest'  => true,
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon'     => 'dashicons-megaphone',
        'menu_position' => 5,
        'has_archive'   => 'actualites',
        'rewrite'       => ['slug' => 'actualites', 'with_front' => false],
    ]);
}
add_action('init', 'ce_register_cpts');

// Archive actualités : 12 articles par page
function ce_actualite_archive_query($q) {
    if (!is_admin() && $q->is_main_query() && $q->is_post_type_archive('actualite')) {
        $q->set('posts_per_page', 12);
    }
}
add_action('pre_get_posts', 'ce_actualite_archive_query');

// ══════════════════════════════════════════════════════════
// ACTIVATION — création des pages statiques + permaliens
// ══════════════════════════════════════════════════════════
function ce_after_switch_theme() {
    $pages = [
        'club'         => 'Le Club',
        'activites'    => 'Nos Activités',
        'horaires'     => 'Horaires & Tarifs',
        'adhesion'     => 'Adhésion',
        'contact'      => 'Contact',
        'tournois'     => 'Tournois',
        'fij'          => 'FIJ',
        'partenaires'  => 'Partenaires',
        'agenda'       => 'Agenda',
        'organigramme' => 'Organigramme',
    ];
    foreach ($pages as $slug => $title) {
        if (!get_page_by_path($slug)) {
            wp_insert_post([
                'post_type'    => 'page',
                'post_status'  => 'publish',
                'post_name'    => $slug,
                'post_title'   => $title,
                'post_content' => '',
            ]);
        }
    }
    // Permaliens « Nom de l'article » si non configurés
    if (!get_option('permalink_structure')) {
        update_option('permalink_structure', '/%postname%/');
    }
    ce_register_cpts();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'ce_after_switch_theme');

// ══════════════════════════════════════════════════════════
// ACF OPTIONS PAGES (nécessite ACF Pro)
// ══════════════════════════════════════════════════════════
// Avertissement si ACF Pro manque — hooké sur admin_notices directement,
// car acf/init ne se déclenche jamais quand ACF est absent.
add_action('admin_notices', function() {
    if (!function_exists('acf_add_options_page')) {
        echo '<div class="notice notice-warning"><p><strong>Cannes Échecs :</strong> ACF Pro doit être installé et activé pour accéder aux réglages du site (FIJ, HelloAsso, Infos club) et aux champs des actualités.</p></div>';
    }
});

function ce_register_options_pages() {
    if (!function_exists('acf_add_options_page')) return;
    acf_add_options_page([
        'page_title' => 'Réglages du site',
        'menu_title' => 'Réglages CE',
        'menu_slug'  => 'reglages-ce',
        'capability' => 'edit_posts',
        'icon_url'   => 'dashicons-admin-settings',
        'position'   => 3,
        'redirect'   => true,
    ]);
    acf_add_options_sub_page(['page_title' => 'Réglages FIJ 2027', 'menu_title' => '♟ FIJ 2027',   'menu_slug' => 'reglages-fij',    'parent_slug' => 'reglages-ce', 'capability' => 'edit_posts']);
    acf_add_options_sub_page(['page_title' => 'Liens HelloAsso',   'menu_title' => '🔗 HelloAsso',  'menu_slug' => 'liens-helloasso', 'parent_slug' => 'reglages-ce', 'capability' => 'edit_posts']);
    acf_add_options_sub_page(['page_title' => 'Infos du Club',     'menu_title' => '🏛 Infos Club', 'menu_slug' => 'infos-club',      'parent_slug' => 'reglages-ce', 'capability' => 'edit_posts']);
}
add_action('acf/init', 'ce_register_options_pages');

// ACF JSON — synchronisation automatique des champs
add_filter('acf/settings/save_json', function() {
    return get_template_directory() . '/acf-json';
});
add_filter('acf/settings/load_json', function($paths) {
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
});

// ══════════════════════════════════════════════════════════
// HELPERS ACF
// ══════════════════════════════════════════════════════════
function ce_opt(string $key, $default = '') {
    if (!function_exists('get_field')) return $default;
    $val = get_field($key, 'option');
    return ($val !== null && $val !== false && $val !== '') ? $val : $default;
}
function ce_opt_int(string $key, int $default = 0): int {
    return intval(ce_opt($key, $default));
}
function ce_opt_date(string $key, string $default): string {
    // ACF date_time_picker retourne 'Y-m-d H:i:s' → ISO pour new Date()
    return str_replace(' ', 'T', ce_opt($key, $default));
}

// URL de l'image principale d'une actualité (ACF, sinon vignette WP)
function ce_actu_image_url(int $post_id, string $size = 'large'): string {
    $img = function_exists('get_field') ? get_field('image_principale', $post_id) : null;
    if (is_array($img)) return $img['sizes'][$size] ?? $img['url'];
    if (has_post_thumbnail($post_id)) return (string) get_the_post_thumbnail_url($post_id, $size);
    return '';
}

// Catégorie affichée → clé de filtre (identique au JS du site statique)
function ce_cat_to_filter(string $cat): string {
    if (stripos($cat, 'tournoi') !== false)  return 'tournois';
    if (stripos($cat, 'scolaire') !== false) return 'scolaire';
    if (stripos($cat, 'vie du club') !== false || stripos($cat, 'animation') !== false) return 'club';
    return 'resultats';
}

// ══════════════════════════════════════════════════════════
// CARTE ACTUALITÉ — même markup que le site statique (buildCard)
// mais en vrai lien <a> crawlable.
// ══════════════════════════════════════════════════════════
function ce_actu_card(WP_Post $post, bool $show_extrait = false): string {
    $cat     = (function_exists('get_field') ? get_field('categorie', $post->ID) : '') ?: 'Actualité';
    $bg      = (function_exists('get_field') ? get_field('bg_gradient', $post->ID) : '') ?: 'linear-gradient(135deg,#1e3a5f,#0a1f38)';
    $emoji   = (function_exists('get_field') ? get_field('emoji', $post->ID) : '') ?: '♟';
    $badge   = (function_exists('get_field') ? get_field('badge', $post->ID) : '') ?: 'Actualité';
    $img_url = ce_actu_image_url($post->ID);

    $img_html = $img_url
        ? '<div class="actu-img" style="background:#111;background-image:url(\'' . esc_url($img_url) . '\');background-size:cover;background-position:center"></div>'
        : '<div class="actu-img" style="background:' . esc_attr($bg) . '" role="img" aria-label="' . esc_attr($badge) . '"><span aria-hidden="true">' . esc_html($emoji) . '</span></div>';

    $extrait_html = '';
    if ($show_extrait) {
        $extrait      = wp_trim_words(wp_strip_all_tags($post->post_content), 22, '…');
        $extrait_html = '<p class="actu-extrait">' . esc_html($extrait) . '</p>';
    }

    return '<a class="actu-card" data-cat="' . esc_attr(ce_cat_to_filter($cat)) . '" href="' . esc_url(get_permalink($post)) . '">'
        . $img_html
        . '<div class="actu-body">'
        . '<div class="actu-cat">' . esc_html($cat) . '</div>'
        . '<h3 class="actu-title">' . esc_html($post->post_title) . '</h3>'
        . $extrait_html
        . '<div class="actu-footer"><span class="actu-date">' . esc_html(get_the_date('j F Y', $post)) . '</span><span class="actu-lire">Lire →</span></div>'
        . '</div></a>';
}

// ══════════════════════════════════════════════════════════
// DONNÉES JS — injectées avant main.js (remplace les const du site statique)
// ══════════════════════════════════════════════════════════
function ce_site_data_js(): string {
    $json = fn($v) => wp_json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    // URLs des pages (utilisées par le shim de navigation)
    $urls = ['home' => home_url('/'), 'actualites' => home_url('/actualites/'), 'article' => home_url('/actualites/')];
    foreach (['club','activites','horaires','adhesion','contact','tournois','fij','partenaires','agenda','organigramme'] as $s) {
        $urls[$s] = home_url('/' . $s . '/');
    }

    // HelloAsso — le garde-fou haOpen() s'applique tant que les URLs sont vides ('#')
    $helloasso = [
        'adhesion' => ce_opt('ha_adhesion', '#'),
        'fij'      => ce_opt('ha_fij', '#'),
        'paques'   => ce_opt('ha_paques', '#'),
        'pico'     => [],
    ];
    foreach (['sep','oct','nov','dec','jan','mar','avr','mai','jun'] as $m) {
        $helloasso['pico'][$m] = ce_opt('ha_pico_' . $m, '#');
    }

    // FIJ
    $fij_inscrits = [];
    foreach (['a' => 'openA', 'b' => 'openB', 'c' => 'openC'] as $k => $key) {
        $fij_inscrits[$key] = [
            'url'      => ce_opt('fij_inscrits_' . $k . '_url', ''),
            'count'    => ce_opt_int('fij_inscrits_' . $k . '_count', 0),
            'pairings' => ce_opt('fij_inscrits_' . $k . '_pairings', ''),
        ];
    }
    $rondes_defaults = [
        [1, '2027-02-22T16:30:00', 'Ronde 1 · Lun. 22 fév. à 16h30'],
        [2, '2027-02-23T09:00:00', 'Ronde 2 · Mar. 23 fév. à 9h00'],
        [3, '2027-02-23T16:00:00', 'Ronde 3 · Mar. 23 fév. à 16h00'],
        [4, '2027-02-24T15:00:00', 'Ronde 4 · Mer. 24 fév. à 15h00'],
        [5, '2027-02-25T09:00:00', 'Ronde 5 · Jeu. 25 fév. à 9h00'],
        [6, '2027-02-25T16:00:00', 'Ronde 6 · Jeu. 25 fév. à 16h00'],
        [7, '2027-02-26T15:00:00', 'Ronde 7 · Ven. 26 fév. à 15h00'],
        [8, '2027-02-27T15:00:00', 'Ronde 8 · Sam. 27 fév. à 15h00'],
        [9, '2027-02-28T10:00:00', 'Ronde 9 · Dim. 28 fév. à 10h00'],
        [10, '2027-02-28T16:30:00', 'Remise des prix · Dim. 28 fév. à 16h30'],
    ];
    $rondes = [];
    foreach ($rondes_defaults as [$n, $date, $label]) {
        $field = $n === 10 ? 'fij_remise' : 'fij_r' . $n;
        $rondes[] = [
            'n'     => $n,
            'date'  => ce_opt_date($field . '_date', $date),
            'label' => ce_opt($field . '_label', $label),
        ];
    }

    // Horaires — repeater ACF, sinon valeurs par défaut du site statique
    $horaires = [];
    if (function_exists('have_rows') && have_rows('horaires', 'option')) {
        while (have_rows('horaires', 'option')) {
            the_row();
            $horaires[] = [
                'jour'      => (string) get_sub_field('jour'),
                'h'         => (string) get_sub_field('horaires'),
                'detail'    => (string) get_sub_field('detail'),
                'ferme'     => (bool) get_sub_field('ferme'),
                'highlight' => (bool) get_sub_field('highlight'),
            ];
        }
    }
    if (!$horaires) {
        $horaires = [
            ['jour' => 'Lundi',    'h' => '13h30–18h30', 'detail' => 'Parties libres · Accès club', 'ferme' => false, 'highlight' => false],
            ['jour' => 'Mardi',    'h' => '17h00–19h00', 'detail' => 'Cours jeunes uniquement', 'ferme' => false, 'highlight' => true],
            ['jour' => 'Mercredi', 'h' => '13h30–20h00', 'detail' => 'Pitchounets 13h30 · Jeunes 13h30–17h30 · Adultes débutants 17h30 · Adultes confirmés 18h30', 'ferme' => false, 'highlight' => false],
            ['jour' => 'Jeudi',    'h' => '13h30–18h30', 'detail' => 'Parties libres · Accès club', 'ferme' => false, 'highlight' => false],
            ['jour' => 'Vendredi', 'h' => '13h30–18h30', 'detail' => 'Parties libres · Rapide du vendredi (soirée)', 'ferme' => false, 'highlight' => false],
            ['jour' => 'Samedi',   'h' => '', 'detail' => 'Fermé (sauf tournois PICO)', 'ferme' => true, 'highlight' => false],
            ['jour' => 'Dimanche', 'h' => '', 'detail' => 'Fermé (sauf tournois)', 'ferme' => true, 'highlight' => false],
        ];
    }

    // Équipe — repeater ACF (photo → URL), sinon valeurs par défaut
    $equipe = [];
    if (function_exists('have_rows') && have_rows('equipe', 'option')) {
        while (have_rows('equipe', 'option')) {
            the_row();
            $photo = get_sub_field('photo');
            $equipe[] = [
                'nom'    => (string) get_sub_field('nom'),
                'role'   => (string) get_sub_field('role'),
                'avatar' => is_array($photo) ? ($photo['sizes']['medium'] ?? $photo['url']) : '👤',
            ];
        }
    }
    if (!$equipe) {
        $equipe = [
            ['nom' => 'Joffrey', 'role' => 'Animateur',         'avatar' => '👤'],
            ['nom' => 'Nicolas', 'role' => 'Animateur',         'avatar' => '👤'],
            ['nom' => 'Romu',    'role' => 'Directeur sportif', 'avatar' => '👤'],
            ['nom' => 'Marlies', 'role' => 'Coordinatrice',     'avatar' => '👤'],
        ];
    }

    // Widget héro — choisi dans WP admin (vide = auto selon la saison)
    $hero_mode = (string) ce_opt('hero_widget', '');
    $hero_data = null;
    if ($hero_mode === 'actu') {
        $hero_data = [
            'label' => ce_opt('hero_actu_label', 'À la une'),
            'titre' => ce_opt('hero_actu_titre', ''),
            'desc'  => ce_opt('hero_actu_desc', ''),
            'date'  => ce_opt('hero_actu_date', ''),
            'cta'   => ce_opt('hero_actu_cta', 'Lire l\'article →'),
            'lien'  => 'actualites',
        ];
    }

    return 'var CE_URLS = ' . $json($urls) . ';'
        . 'var CE_HERO_MODE = ' . $json($hero_mode ?: null) . ';'
        . 'var CE_HERO_DATA = ' . $json($hero_data) . ';'
        . 'const SAISON = ' . $json((string) ce_opt('saison', '2026–2027')) . ';'
        . 'const HORAIRES = ' . $json($horaires) . ';'
        . 'const EQUIPE = ' . $json($equipe) . ';'
        . 'const HELLOASSO = ' . $json($helloasso) . ';'
        . 'const FIJ_INSCRITS = ' . $json($fij_inscrits) . ';'
        . 'const FIJ_DATE = new Date(' . $json(ce_opt_date('fij_r1_date', '2027-02-22T16:30:00')) . ');'
        . 'const FIJ_OPEN = new Date(' . $json(ce_opt_date('fij_open_inscriptions', '2026-11-01T00:00:00')) . ');'
        . 'const FIJ_FIN = new Date(' . $json(ce_opt_date('fij_fin', '2027-02-28T19:00:00')) . ');'
        . 'const FIJ_RONDES = ' . $json($rondes) . '.map(function(r){return {n:r.n, date:new Date(r.date), label:r.label};});';
}

// ══════════════════════════════════════════════════════════
// SEO — metas description/OG/Twitter + JSON-LD par page
// ══════════════════════════════════════════════════════════
function ce_meta_tags() {
    $logo    = 'https://cannes-echecs.fr/logo-cannes-echecs.png';
    $desc    = 'Cannes Échecs, club d\'échecs en centre-ville de Cannes depuis 1985. Cours tous niveaux, compétitions FFE, tournois FIJ. Plus de 200 membres, 10× Champion de France Jeunes.';
    $og_type = 'website';
    $img     = $logo;
    $url     = home_url('/');

    if (is_singular('actualite')) {
        $p       = get_queried_object();
        $desc    = wp_trim_words(wp_strip_all_tags($p->post_content), 30, '…');
        $og_type = 'article';
        $img     = ce_actu_image_url($p->ID) ?: $logo;
        $url     = get_permalink($p);
    } elseif (is_post_type_archive('actualite')) {
        $desc = 'Résultats, événements, vie du club — toutes les actualités de Cannes Échecs.';
        $url  = get_post_type_archive_link('actualite');
    } elseif (is_page()) {
        $url = get_permalink();
    }

    $title = wp_get_document_title();

    echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
    echo '<link rel="canonical" href="' . esc_url($url) . '">' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($desc) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($img) . '">' . "\n";
    echo '<meta property="og:locale" content="fr_FR">' . "\n";
    echo '<meta property="og:site_name" content="Cannes Échecs">' . "\n";
    echo '<meta name="twitter:card" content="' . ($img !== $logo ? 'summary_large_image' : 'summary') . '">' . "\n";
    echo '<meta name="twitter:site" content="@CannesChessClub">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($desc) . '">' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($img) . '">' . "\n";

    if (is_singular('actualite')) {
        echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '">' . "\n";
    }

    if (is_front_page()) {
        $ld = [
            '@context'    => 'https://schema.org',
            '@type'       => 'SportsClub',
            'name'        => 'Cannes Échecs',
            'description' => 'Club d\'échecs en centre-ville de Cannes depuis 1985. Cours tous niveaux, compétitions FFE, tournois FIJ.',
            'url'         => home_url('/'),
            'logo'        => $logo,
            'telephone'   => '+33493394139',
            'email'       => 'info@cannes-echecs.fr',
            'address'     => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => '3 Avenue du Petit Juas',
                'addressLocality' => 'Cannes',
                'postalCode'      => '06400',
                'addressCountry'  => 'FR',
            ],
            'openingHoursSpecification' => [
                ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => ['Monday', 'Thursday', 'Friday'], 'opens' => '13:30', 'closes' => '18:30'],
                ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => 'Tuesday',   'opens' => '17:00', 'closes' => '19:00'],
                ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => 'Wednesday', 'opens' => '13:30', 'closes' => '20:00'],
            ],
            'sameAs' => [
                'https://www.facebook.com/canneschessclub',
                'https://x.com/CannesChessClub',
                'https://www.instagram.com/cannes_echecs',
            ],
        ];
        echo '<script type="application/ld+json">' . wp_json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }
}
add_action('wp_head', 'ce_meta_tags', 5);
