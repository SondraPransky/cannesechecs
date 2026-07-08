<?php
/**
 * archive-actualite.php — /actualites/
 * Liste paginée des actualités (12 par page, cartes crawlables).
 * Les filtres par catégorie agissent sur la page courante (JS).
 */
get_header(); ?>

<div id="page-actualites" class="page active">

  <section class="hero-shared" style="background:linear-gradient(135deg,var(--bleu) 0%,var(--noir) 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>">Accueil</a><span>›</span><span>Actualités</span></div>
      <h1>Actualités</h1>
      <p class="hero-desc">Résultats, événements, vie du club — toute l'info Cannes Échecs.</p>
    </div>
  </section>

  <div class="archive-filtres">
    <div class="container archive-filtres-inner">
      <button class="filtre-btn active-gold">Toutes</button>
      <button class="filtre-btn">Résultats</button>
      <button class="filtre-btn">Formation</button>
      <button class="filtre-btn">Tournois</button>
      <button class="filtre-btn">Scolaire</button>
      <button class="filtre-btn">Club</button>
      <div class="archive-search">
        <input type="search" id="actu-search" placeholder="Rechercher un joueur, un tournoi…" aria-label="Rechercher dans les actualités" autocomplete="off">
      </div>
    </div>
  </div>

  <section class="archive-grid-section">
    <div class="container">
      <div class="archive-grid">
        <?php if (have_posts()) : ?>
          <?php while (have_posts()) : the_post(); ?>
            <?php echo ce_actu_card(get_post(), true); ?>
          <?php endwhile; ?>
        <?php else : ?>
          <p style="color:var(--muted);font-style:italic;padding:48px 0">Aucune actualité pour le moment.</p>
        <?php endif; ?>
      </div>
      <?php the_posts_pagination([
          'mid_size'  => 2,
          'prev_text' => '← Précédent',
          'next_text' => 'Suivant →',
      ]); ?>
    </div>
  </section>
</div><!-- fin page-actualites -->

<?php get_footer(); ?>
