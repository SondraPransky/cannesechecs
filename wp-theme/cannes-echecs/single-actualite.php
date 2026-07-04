<?php
/**
 * single-actualite.php — page d'un article
 * Chaque article a sa propre URL, ses metas OG (ce_meta_tags) et sa galerie.
 */
get_header();

while (have_posts()) : the_post();
    $pid     = get_the_ID();
    $badge   = get_field('badge') ?: 'Actualité';
    $cat     = get_field('categorie') ?: 'Vie du club';
    $bg      = get_field('bg_gradient') ?: 'linear-gradient(135deg,#1e3a5f,#0a1f38)';
    $emoji   = get_field('emoji') ?: '♟';
    $img_url = ce_actu_image_url($pid);
    $gallery = get_field('gallery') ?: [];
    $extrait = wp_trim_words(wp_strip_all_tags(get_the_content()), 25, '…');
?>

<div id="page-article" class="page active">

  <section class="article-hero">
    <div class="container" style="max-width:800px">
      <div class="breadcrumb" style="color:rgba(255,255,255,.4);display:flex;align-items:center;gap:8px;font-size:12px;margin-bottom:20px">
        <a href="<?php echo esc_url(home_url('/')); ?>" style="color:rgba(255,255,255,.4)">Accueil</a><span>›</span>
        <a href="<?php echo esc_url(home_url('/actualites/')); ?>" style="color:rgba(255,255,255,.4)">Actualités</a><span>›</span>
        <span style="color:rgba(255,255,255,.6)">Article</span>
      </div>
      <div class="badge badge-gold" style="margin-bottom:16px"><?php echo esc_html($badge); ?></div>
      <h1 style="font-size:46px;color:#fff;line-height:1.1;margin-bottom:16px"><?php the_title(); ?></h1>
      <div class="article-meta">
        <div class="meta-item"><span>📅</span> <?php echo esc_html(get_the_date('j F Y')); ?></div>
        <div class="meta-item"><span>🏷</span> <?php echo esc_html($cat); ?></div>
      </div>
    </div>
  </section>

  <div class="article-content-wrap">
    <div class="container" style="max-width:800px">
      <?php if ($img_url) : ?>
        <div class="article-featured-img" style="background:#111;background-image:url('<?php echo esc_url($img_url); ?>');background-size:cover;background-position:center" role="img" aria-label="<?php the_title_attribute(); ?>"></div>
      <?php else : ?>
        <div class="article-featured-img" style="background:<?php echo esc_attr($bg); ?>"><?php echo esc_html($emoji); ?></div>
      <?php endif; ?>

      <div class="article-body"><?php the_content(); ?></div>

      <?php if ($gallery) : ?>
        <div class="art-gallery">
          <p class="art-gallery-label">Photos</p>
          <div class="art-gallery-grid">
            <?php foreach ($gallery as $i => $g) : ?>
              <div class="art-gallery-item" onclick="openLightbox(<?php echo (int) $i; ?>)">
                <img src="<?php echo esc_url($g['sizes']['large'] ?? $g['url']); ?>" alt="<?php echo esc_attr(get_the_title() . ' — photo ' . ($i + 1)); ?>" loading="lazy" decoding="async">
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <div class="article-share">
        <span class="share-label">Partager :</span>
        <button id="share-native" class="share-btn" style="background:var(--bleu);color:#fff;display:none" onclick="shareNative()">⬆ Partager</button>
        <button id="share-fb" class="share-btn share-fb" onclick="shareFb()">Facebook</button>
        <button id="share-tw" class="share-btn share-tw" onclick="shareTw()">X / Twitter</button>
      </div>

      <?php
      $prev = get_previous_post();
      $next = get_next_post();
      if ($prev || $next) : ?>
        <nav class="article-nav" aria-label="Autres articles">
          <?php if ($prev) : ?>
            <a href="<?php echo esc_url(get_permalink($prev)); ?>">← <?php echo esc_html(wp_trim_words($prev->post_title, 8, '…')); ?></a>
          <?php else : ?><span></span><?php endif; ?>
          <?php if ($next) : ?>
            <a href="<?php echo esc_url(get_permalink($next)); ?>" style="text-align:right"><?php echo esc_html(wp_trim_words($next->post_title, 8, '…')); ?> →</a>
          <?php endif; ?>
        </nav>
      <?php endif; ?>
    </div>
  </div>

  <section class="article-related">
    <div class="container">
      <span class="surtitre">À lire aussi</span>
      <h2 style="font-size:36px;color:var(--bleu);margin-bottom:30px">Autres actualités</h2>
      <div class="actu-grid">
        <?php
        $related = get_posts([
            'post_type'    => 'actualite',
            'numberposts'  => 3,
            'post_status'  => 'publish',
            'post__not_in' => [$pid],
        ]);
        foreach ($related as $r) {
            echo ce_actu_card($r, false);
        }
        ?>
      </div>
    </div>
  </section>
</div><!-- fin page-article -->

<script>
window._shareUrl   = <?php echo wp_json_encode(get_permalink()); ?>;
window._shareTitle = <?php echo wp_json_encode(get_the_title()); ?>;
window._shareText  = <?php echo wp_json_encode($extrait . ' — Cannes Échecs'); ?>;
window.CE_GALLERY  = <?php echo wp_json_encode(array_values(array_map(fn($g) => $g['url'], $gallery))); ?>;
</script>

<?php
endwhile;
get_footer(); ?>
