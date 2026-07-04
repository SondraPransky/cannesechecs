<?php
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

<nav class="navbar">
  <div class="container navbar-inner">
    <div class="nav-logo" onclick="goTo('home')">
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/logo-cannes-echecs.png" alt="Cannes Échecs" class="nav-logo-img"
           onerror="this.style.display='none';document.getElementById('nav-logo-fallback').style.display='flex'">
      <div id="nav-logo-fallback" style="display:none;width:44px;height:44px;background:var(--gold);border-radius:8px;align-items:center;justify-content:center;font-size:20px;font-weight:900;color:var(--noir);flex-shrink:0">♟</div>
      <div class="nav-logo-text">
        <span class="l1">Cannes</span>
        <span class="l2">Échecs</span>
      </div>
    </div>
    <div class="nav-menu">
      <div class="nav-item">
        <button class="nav-link" id="nav-club">Club <span class="chevron">▾</span></button>
        <div class="dropdown">
          <a href="<?php echo esc_url(home_url('/actualites/')); ?>">Actualités</a>
          <a href="<?php echo esc_url(home_url('/club/')); ?>">Présentation</a>
          <a href="<?php echo esc_url(home_url('/organigramme/')); ?>">Organigramme</a>
          <a href="<?php echo esc_url(home_url('/horaires/')); ?>">Horaires & Tarifs</a>
          <a href="<?php echo esc_url(home_url('/adhesion/')); ?>">Formules d'adhésion</a>
          <a href="<?php echo esc_url(home_url('/agenda/')); ?>">Agenda</a>
          <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a>
        </div>
      </div>
      <div class="nav-item">
        <a class="nav-link" id="nav-tournois" href="<?php echo esc_url(home_url('/tournois/')); ?>">Tournois</a>
      </div>
      <div class="nav-item">
        <a class="nav-link nav-link-fij" id="nav-fij" href="<?php echo esc_url(home_url('/fij/')); ?>">★ FIJ 2027</a>
      </div>
      <div class="nav-item">
        <button class="nav-link" id="nav-activites">Activités <span class="chevron">▾</span></button>
        <div class="dropdown">
          <a href="<?php echo esc_url(home_url('/activites/')); ?>#activites-cours">Cours & Formation</a>
          <a href="<?php echo esc_url(home_url('/activites/')); ?>#activites-stages">Stages</a>
          <a href="<?php echo esc_url(home_url('/activites/')); ?>#activites-scolaire">Interventions scolaires</a>
        </div>
      </div>
      <div class="nav-item">
        <a class="nav-link" id="nav-partenaires" href="<?php echo esc_url(home_url('/partenaires/')); ?>">Partenaires</a>
      </div>
      <div class="nav-item">
        <a class="nav-link" id="nav-agenda" href="<?php echo esc_url(home_url('/agenda/')); ?>">Agenda</a>
      </div>
    </div>
    <a class="nav-cta" href="<?php echo esc_url(home_url('/adhesion/')); ?>">S'inscrire</a>
    <!-- Hamburger (mobile uniquement) -->
    <button class="nav-hamburger" id="nav-hamburger" onclick="toggleMobileNav()" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>
  <!-- Menu mobile overlay -->
  <div class="nav-mobile" id="nav-mobile">
    <a class="nav-mobile-link" href="<?php echo esc_url(home_url('/actualites/')); ?>">Actualités</a>
    <a class="nav-mobile-link" href="<?php echo esc_url(home_url('/club/')); ?>">Le Club</a>
    <a class="nav-mobile-link" href="<?php echo esc_url(home_url('/organigramme/')); ?>">Organigramme</a>
    <a class="nav-mobile-link" href="<?php echo esc_url(home_url('/horaires/')); ?>">Horaires & Tarifs</a>
    <a class="nav-mobile-link" href="<?php echo esc_url(home_url('/adhesion/')); ?>">Adhésion</a>
    <div class="nav-mobile-sep"></div>
    <a class="nav-mobile-link" href="<?php echo esc_url(home_url('/activites/')); ?>#activites-cours">Cours & Formation</a>
    <a class="nav-mobile-link" href="<?php echo esc_url(home_url('/activites/')); ?>#activites-stages">Stages</a>
    <a class="nav-mobile-link" href="<?php echo esc_url(home_url('/activites/')); ?>#activites-scolaire">Interventions scolaires</a>
    <a class="nav-mobile-link" href="<?php echo esc_url(home_url('/tournois/')); ?>">Tournois</a>
    <div class="nav-mobile-sep"></div>
    <a class="nav-mobile-link" href="<?php echo esc_url(home_url('/agenda/')); ?>">Agenda</a>
    <a class="nav-mobile-link" href="<?php echo esc_url(home_url('/partenaires/')); ?>">Partenaires</a>
    <a class="nav-mobile-link" href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a>
    <div class="nav-mobile-sep"></div>
    <a class="nav-mobile-link gold" href="<?php echo esc_url(home_url('/fij/')); ?>">★ FIJ 2027</a>
    <a class="nav-mobile-cta" href="<?php echo esc_url(home_url('/adhesion/')); ?>">S'inscrire →</a>
  </div>
</nav>

<main id="app" tabindex="-1">
