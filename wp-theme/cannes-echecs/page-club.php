<?php
/**
 * Template de la page « Le Club » (slug WordPress : club).
 * Généré depuis index.html par build-wp-theme.js — ne pas éditer le HTML ici
 * sans reporter la modification dans index.html tant que les deux coexistent.
 */
get_header(); ?>

<div id="page-club" class="page active">

  <section class="hero-shared" style="background:linear-gradient(160deg,var(--bleu) 0%,#0A1F38 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>">Accueil</a><span>›</span><span>Le Club</span></div>
      <h1>Le <em>Club</em></h1>
      <p class="hero-desc">Plus de 30 ans d'excellence échiquéenne sur la Côte d'Azur</p>
    </div>
  </section>

  <!-- Histoire -->
  <section class="club-histoire">
    <div class="container">
      <div class="club-histoire-grid">
        <div class="club-histoire-text">
          <span class="surtitre">Notre histoire</span>
          <h2 style="font-size:40px;color:var(--bleu);margin-bottom:20px">Un club fondé<br>sur la passion</h2>
          <div class="gold-bar"></div>
          <p>Fondé en 1985, Cannes Échecs s'est imposé comme l'un des clubs de référence du sud de la France. Installé en plein cœur de Cannes depuis 2006 dans des locaux entièrement rénovés, le club conjugue tradition et modernité.</p>
          <p>Notre philosophie : rendre les échecs accessibles à tous, du débutant de 5 ans au joueur classé, en passant par les scolaires que nous accompagnons depuis plus de trois décennies.</p>
          <p>Aujourd'hui, avec plus de 200 membres actifs et un palmarès national exceptionnel, Cannes Échecs reste un club vivant, ambitieux et profondément ancré dans le tissu culturel cannois.</p>
        </div>
        <div class="club-histoire-img" aria-hidden="true">♟</div>
      </div>
    </div>
  </section>

  <!-- Équipe -->
  <section class="club-equipe" style="background:var(--ivoire)">
    <div class="container">
      <div class="section-header center" style="margin-bottom:40px">
        <span class="surtitre">L'équipe</span>
        <h2 style="font-size:40px;color:var(--bleu)">Ceux qui font vivre le club</h2>
      </div>
      <div class="equipe-grid" id="equipe-grid"></div>
    </div>
  </section>

  <section class="section-cta">
    <div class="cta-inner">
      <div class="badge badge-event" style="margin-bottom:20px">Rejoignez-nous</div>
      <h2>Faire partie de<br><em>l'aventure</em></h2>
      <p>Toutes les formules d'adhésion pour la saison 2026–2027 sont disponibles en ligne sur HelloAsso.</p>
      <div class="cta-btns">
        <button class="btn btn-gold btn-lg" onclick="goTo('adhesion')">Voir les formules d'adhésion →</button>
      </div>
    </div>
  </section>
</div><!-- fin page-club -->

<?php get_footer(); ?>
