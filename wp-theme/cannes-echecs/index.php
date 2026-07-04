<?php
/**
 * index.php — gabarit de repli WordPress (obligatoire).
 * Les vraies pages utilisent front-page.php, page-{slug}.php,
 * archive-actualite.php et single-actualite.php.
 */
get_header(); ?>

<div class="page active">
  <section class="hero-shared" style="background:linear-gradient(135deg,var(--bleu) 0%,var(--noir) 100%);min-height:270px">
    <div class="container">
      <h1><?php echo is_search() ? 'Recherche' : 'Cannes <em>Échecs</em>'; ?></h1>
    </div>
  </section>
  <section style="padding:60px 0;background:var(--ivoire)">
    <div class="container">
      <?php if (have_posts()) : ?>
        <div class="actu-grid">
          <?php while (have_posts()) : the_post(); ?>
            <?php echo get_post_type() === 'actualite'
                ? ce_actu_card(get_post(), true)
                : '<a class="actu-card" href="' . esc_url(get_permalink()) . '"><div class="actu-body"><h3 class="actu-title">' . esc_html(get_the_title()) . '</h3></div></a>'; ?>
          <?php endwhile; ?>
        </div>
        <?php the_posts_pagination(['mid_size' => 2, 'prev_text' => '← Précédent', 'next_text' => 'Suivant →']); ?>
      <?php else : ?>
        <p style="color:var(--muted);font-style:italic">Aucun contenu trouvé.</p>
        <p style="margin-top:16px"><a class="btn btn-gold" href="<?php echo esc_url(home_url('/')); ?>">Retour à l'accueil →</a></p>
      <?php endif; ?>
    </div>
  </section>
</div>

<?php get_footer(); ?>
