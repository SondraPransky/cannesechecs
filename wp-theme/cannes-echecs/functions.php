<?php
/**
 * Cannes Échecs — functions.php
 * Thème WordPress SPA — CPT + ACF Options + Enqueue
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
// ENQUEUE CSS + JS
// ══════════════════════════════════════════════════════════
function ce_enqueue() {
    $ver = '1.0.' . filemtime(get_template_directory() . '/assets/css/main.css');
    wp_enqueue_style('ce-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Inter:wght@300;400;500;600&family=Montserrat:wght@500;600;700;800&display=swap', [], null);
    wp_enqueue_style('ce-main', get_template_directory_uri() . '/assets/css/main.css', ['ce-fonts'], $ver);
    wp_enqueue_script('ce-main', get_template_directory_uri() . '/assets/js/main.js', [], $ver, true);
}
add_action('wp_enqueue_scripts', 'ce_enqueue');

// Désactiver les styles/scripts WordPress inutiles (optimisation)
function ce_dequeue_bloat() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'ce_dequeue_bloat', 100);

// ══════════════════════════════════════════════════════════
// CUSTOM POST TYPE : ACTUALITÉ
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
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'],
        'menu_icon'          => 'dashicons-megaphone',
        'menu_position'      => 5,
        'has_archive'        => false,
        'rewrite'            => false,
    ]);
}
add_action('init', 'ce_register_cpts');

// ══════════════════════════════════════════════════════════
// ACF OPTIONS PAGES (nécessite ACF Pro)
// ══════════════════════════════════════════════════════════
function ce_register_options_pages() {
    if (!function_exists('acf_add_options_page')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-warning"><p><strong>Cannes Échecs :</strong> ACF Pro doit être installé et activé pour accéder aux réglages du site (FIJ, HelloAsso, Infos club).</p></div>';
        });
        return;
    }

    // Page parente "Réglages du site"
    acf_add_options_page([
        'page_title'  => 'Réglages du site',
        'menu_title'  => 'Réglages CE',
        'menu_slug'   => 'reglages-ce',
        'capability'  => 'edit_posts',
        'icon_url'    => 'dashicons-admin-settings',
        'position'    => 3,
        'redirect'    => true,
    ]);

    // Sous-page : FIJ 2027
    acf_add_options_sub_page([
        'page_title'  => 'Réglages FIJ 2027',
        'menu_title'  => '♟ FIJ 2027',
        'menu_slug'   => 'reglages-fij',
        'parent_slug' => 'reglages-ce',
        'capability'  => 'edit_posts',
    ]);

    // Sous-page : Liens HelloAsso
    acf_add_options_sub_page([
        'page_title'  => 'Liens HelloAsso',
        'menu_title'  => '🔗 HelloAsso',
        'menu_slug'   => 'liens-helloasso',
        'parent_slug' => 'reglages-ce',
        'capability'  => 'edit_posts',
    ]);

    // Sous-page : Infos Club
    acf_add_options_sub_page([
        'page_title'  => 'Infos du Club',
        'menu_title'  => '🏛 Infos Club',
        'menu_slug'   => 'infos-club',
        'parent_slug' => 'reglages-ce',
        'capability'  => 'edit_posts',
    ]);
}
add_action('acf/init', 'ce_register_options_pages');

// ══════════════════════════════════════════════════════════
// ACF JSON — sauvegarde automatique des champs
// ══════════════════════════════════════════════════════════
add_filter('acf/settings/save_json', function() {
    return get_template_directory() . '/acf-json';
});
add_filter('acf/settings/load_json', function($paths) {
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
});

// ══════════════════════════════════════════════════════════
// HELPERS — récupérer les champs ACF avec valeurs par défaut
// ══════════════════════════════════════════════════════════

/**
 * Retourne un champ ACF options ou la valeur par défaut fournie.
 */
function ce_opt(string $key, $default = '') {
    if (!function_exists('get_field')) return $default;
    $val = get_field($key, 'option');
    return ($val !== null && $val !== false && $val !== '') ? $val : $default;
}

/**
 * Retourne un champ ACF options sous forme d'entier.
 */
function ce_opt_int(string $key, int $default = 0): int {
    return intval(ce_opt($key, $default));
}

/**
 * Retourne une date ACF options formatée pour JS (ISO 8601).
 * Le champ ACF date_time_picker retourne 'YYYY-MM-DD HH:MM:SS'.
 * On le convertit en 'YYYY-MM-DDTHH:MM:SS' pour new Date().
 */
function ce_opt_date(string $key, string $default): string {
    $val = ce_opt($key, $default);
    // Convertit "2027-02-22 16:30:00" → "2027-02-22T16:30:00"
    return str_replace(' ', 'T', $val);
}

/**
 * Échappe une chaîne pour usage dans un littéral JS entre guillemets simples.
 */
function ce_js(string $s): string {
    return addslashes($s);
}

/**
 * Génère le tableau ARTICLES JS depuis le CPT "actualite".
 */
function ce_build_articles_js(): string {
    $posts = get_posts([
        'post_type'      => 'actualite',
        'numberposts'    => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'post_status'    => 'publish',
    ]);

    if (empty($posts)) return '';

    $lines = [];
    foreach ($posts as $post) {
        $key     = $post->post_name;
        $gallery = get_field('gallery', $post->ID) ?: [];
        $gal_js  = implode(',', array_map(fn($img) => "'" . ce_js($img['url']) . "'", $gallery));
        $img     = get_field('image_principale', $post->ID);
        $img_url = $img ? $img['url'] : '';
        $body    = $post->post_content;
        // Backtick-safe: échapper les backticks et ${} dans le body
        $body    = str_replace(['`', '${'], ['\\`', '\\${'], $body);

        $lines[] = "  '" . $key . "': {"
            . "\n    cat:'" . ce_js(get_field('categorie', $post->ID) ?: '') . "',"
            . "\n    badge:'" . ce_js(get_field('badge', $post->ID) ?: '') . "',"
            . "\n    emoji:'" . ce_js(get_field('emoji', $post->ID) ?: '♟') . "',"
            . "\n    bg:'" . ce_js(get_field('bg_gradient', $post->ID) ?: 'linear-gradient(135deg,#1e3a5f,#0a1f38)') . "',"
            . "\n    title:'" . ce_js($post->post_title) . "',"
            . "\n    date:'" . ce_js(get_the_date('j F Y', $post)) . "',"
            . "\n    img:'" . ce_js($img_url) . "',"
            . "\n    gallery:[" . $gal_js . "],"
            . "\n    body:\`" . $body . "\`"
            . "\n  }";
    }

    return implode(",\n", $lines);
}

/**
 * Génère le tableau ARCHIVE_ORDER JS depuis l'ordre de menu_order du CPT.
 */
function ce_build_archive_order_js(): string {
    $posts = get_posts([
        'post_type'   => 'actualite',
        'numberposts' => -1,
        'orderby'     => 'menu_order',
        'order'       => 'ASC',
        'post_status' => 'publish',
    ]);
    return implode(',', array_map(fn($p) => "'" . $p->post_name . "'", $posts));
}

/**
 * Génère l'objet EXTRAITS JS (extraits courts des articles).
 */
function ce_build_extraits_js(): string {
    $posts = get_posts([
        'post_type'   => 'actualite',
        'numberposts' => -1,
        'post_status' => 'publish',
    ]);

    $lines = [];
    foreach ($posts as $post) {
        $excerpt = wp_trim_words(strip_tags($post->post_content), 25, '…');
        $excerpt = addslashes($excerpt);
        $lines[] = "  '" . $post->post_name . "':'" . $excerpt . "'";
    }
    return implode(",\n", $lines);
}
