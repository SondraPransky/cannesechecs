<?php
/**
 * index.php — Cannes Échecs SPA
 * Template principal WordPress. Données dynamiques injectées via
 * le bloc <script> ci-dessous (ACF Options + CPT actualite).
 */
$logo_uri = get_template_directory_uri() . '/assets/img/logo-cannes-echecs.png';
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- SEO — <title> géré par WordPress via wp_head() -->
<meta name="description" content="Cannes Échecs, club d'échecs en centre-ville de Cannes depuis 1985. Cours tous niveaux, compétitions FFE, tournois FIJ. Plus de 200 membres, 10× Champion de France Jeunes.">
<meta name="keywords" content="échecs Cannes, club échecs Côte d'Azur, cours échecs, FIJ, Festival International des Jeux, FFE, compétition échecs, échecs enfants Cannes">
<meta name="robots" content="index, follow">
<link rel="canonical" href="https://cannes-echecs.fr/">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:url" content="https://cannes-echecs.fr/">
<meta property="og:title" content="Cannes Échecs — Club d'échecs · Côte d'Azur">
<meta property="og:description" content="Club d'échecs de référence sur la Côte d'Azur depuis 1985. Cours, compétitions, FIJ. Plus de 200 membres, 10× Champion de France Jeunes.">
<meta property="og:image" content="https://cannes-echecs.fr/logo-cannes-echecs.png">
<meta property="og:locale" content="fr_FR">
<meta property="og:site_name" content="Cannes Échecs">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@CannesChessClub">
<meta name="twitter:title" content="Cannes Échecs — Club d'échecs · Côte d'Azur">
<meta name="twitter:description" content="Club d'échecs de référence sur la Côte d'Azur depuis 1985. Cours, compétitions, FIJ. Plus de 200 membres.">
<meta name="twitter:image" content="https://cannes-echecs.fr/logo-cannes-echecs.png">

<!-- Favicon -->
<link rel="icon" type="image/png" href="<?= esc_url($logo_uri) ?>">
<link rel="apple-touch-icon" href="<?= esc_url($logo_uri) ?>">

<!-- Données structurées -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SportsClub",
  "name": "Cannes Échecs",
  "description": "Club d'échecs en centre-ville de Cannes depuis 1985. Cours tous niveaux, compétitions FFE, tournois FIJ.",
  "url": "https://cannes-echecs.fr",
  "logo": "https://cannes-echecs.fr/logo-cannes-echecs.png",
  "telephone": "+33493394139",
  "email": "info@cannes-echecs.fr",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "3 Avenue du Petit Juas",
    "addressLocality": "Cannes",
    "postalCode": "06400",
    "addressCountry": "FR"
  },
  "openingHoursSpecification": [
    { "@type": "OpeningHoursSpecification", "dayOfWeek": ["Monday","Thursday","Friday"], "opens": "13:30", "closes": "18:30" },
    { "@type": "OpeningHoursSpecification", "dayOfWeek": "Tuesday", "opens": "17:00", "closes": "19:00" },
    { "@type": "OpeningHoursSpecification", "dayOfWeek": "Wednesday", "opens": "13:30", "closes": "20:00" }
  ],
  "sameAs": [
    "https://www.facebook.com/canneschessclub",
    "https://x.com/CannesChessClub",
    "https://www.instagram.com/cannes_echecs"
  ]
}
</script>

<?php wp_head(); ?>

<!-- ═══════════════════════════════════════════════════════
     DONNÉES DYNAMIQUES — ACF Options + CPT WordPress
     Romuald / Marlies modifient tout depuis l'admin WP.
     ═══════════════════════════════════════════════════════ -->
<script>
const HELLOASSO = {
  adhesion: '<?= esc_js(ce_opt('ha_adhesion','#')) ?>',
  fij:      '<?= esc_js(ce_opt('ha_fij','#')) ?>',
  paques:   '<?= esc_js(ce_opt('ha_paques','#')) ?>',
  pico: {
    sep: '<?= esc_js(ce_opt('ha_pico_sep','#')) ?>',
    oct: '<?= esc_js(ce_opt('ha_pico_oct','#')) ?>',
    nov: '<?= esc_js(ce_opt('ha_pico_nov','#')) ?>',
    dec: '<?= esc_js(ce_opt('ha_pico_dec','#')) ?>',
    jan: '<?= esc_js(ce_opt('ha_pico_jan','#')) ?>',
    mar: '<?= esc_js(ce_opt('ha_pico_mar','#')) ?>',
    avr: '<?= esc_js(ce_opt('ha_pico_avr','#')) ?>',
    mai: '<?= esc_js(ce_opt('ha_pico_mai','#')) ?>',
    jun: '<?= esc_js(ce_opt('ha_pico_jun','#')) ?>',
  }
};
const FIJ_INSCRITS = {
  openA: { url:'<?= esc_js(ce_opt('fij_inscrits_a_url','')) ?>', count:<?= ce_opt_int('fij_inscrits_a_count',0) ?>, pairings:'<?= esc_js(ce_opt('fij_inscrits_a_pairings','')) ?>' },
  openB: { url:'<?= esc_js(ce_opt('fij_inscrits_b_url','')) ?>', count:<?= ce_opt_int('fij_inscrits_b_count',0) ?>, pairings:'<?= esc_js(ce_opt('fij_inscrits_b_pairings','')) ?>' },
  openC: { url:'<?= esc_js(ce_opt('fij_inscrits_c_url','')) ?>', count:<?= ce_opt_int('fij_inscrits_c_count',0) ?>, pairings:'<?= esc_js(ce_opt('fij_inscrits_c_pairings','')) ?>' },
};
const FIJ_DATE = new Date('<?= ce_opt_date('fij_r1_date','2027-02-22T16:30:00') ?>');
const FIJ_OPEN = new Date('<?= ce_opt_date('fij_open_inscriptions','2026-11-01T00:00:00') ?>');
const FIJ_FIN  = new Date('<?= ce_opt_date('fij_fin','2027-02-28T19:00:00') ?>');
const FIJ_RONDES = [
  { n:1,  date:new Date('<?= ce_opt_date('fij_r1_date','2027-02-22T16:30:00') ?>'),  label:'<?= ce_js(ce_opt('fij_r1_label','Ronde 1 · Lun. 22 fév. à 16h30')) ?>' },
  { n:2,  date:new Date('<?= ce_opt_date('fij_r2_date','2027-02-23T09:00:00') ?>'),  label:'<?= ce_js(ce_opt('fij_r2_label','Ronde 2 · Mar. 23 fév. à 9h00')) ?>' },
  { n:3,  date:new Date('<?= ce_opt_date('fij_r3_date','2027-02-23T16:00:00') ?>'),  label:'<?= ce_js(ce_opt('fij_r3_label','Ronde 3 · Mar. 23 fév. à 16h00')) ?>' },
  { n:4,  date:new Date('<?= ce_opt_date('fij_r4_date','2027-02-24T15:00:00') ?>'),  label:'<?= ce_js(ce_opt('fij_r4_label','Ronde 4 · Mer. 24 fév. à 15h00')) ?>' },
  { n:5,  date:new Date('<?= ce_opt_date('fij_r5_date','2027-02-25T09:00:00') ?>'),  label:'<?= ce_js(ce_opt('fij_r5_label','Ronde 5 · Jeu. 25 fév. à 9h00')) ?>' },
  { n:6,  date:new Date('<?= ce_opt_date('fij_r6_date','2027-02-25T16:00:00') ?>'),  label:'<?= ce_js(ce_opt('fij_r6_label','Ronde 6 · Jeu. 25 fév. à 16h00')) ?>' },
  { n:7,  date:new Date('<?= ce_opt_date('fij_r7_date','2027-02-26T15:00:00') ?>'),  label:'<?= ce_js(ce_opt('fij_r7_label','Ronde 7 · Ven. 26 fév. à 15h00')) ?>' },
  { n:8,  date:new Date('<?= ce_opt_date('fij_r8_date','2027-02-27T15:00:00') ?>'),  label:'<?= ce_js(ce_opt('fij_r8_label','Ronde 8 · Sam. 27 fév. à 15h00')) ?>' },
  { n:9,  date:new Date('<?= ce_opt_date('fij_r9_date','2027-02-28T10:00:00') ?>'),  label:'<?= ce_js(ce_opt('fij_r9_label','Ronde 9 · Dim. 28 fév. à 10h00')) ?>' },
  { n:10, date:new Date('<?= ce_opt_date('fij_remise_date','2027-02-28T16:30:00') ?>'), label:'<?= ce_js(ce_opt('fij_remise_label','Remise des prix · Dim. 28 fév. à 16h30')) ?>' },
];
const ARTICLES = { <?= ce_build_articles_js() ?> };
const ARCHIVE_ORDER = [ <?= ce_build_archive_order_js() ?> ];
const EXTRAITS = { <?= ce_build_extraits_js() ?> };
</script>
</head>
<body>

<!-- =====================================================
     NAVBAR
     ===================================================== -->
<nav class="navbar">
  <div class="container navbar-inner">
    <div class="nav-logo" onclick="goTo('home')">
      <img src="<?= esc_url($logo_uri) ?>" alt="Cannes Échecs" class="nav-logo-img"
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
          <a onclick="goTo('actualites')">Actualités</a>
          <a onclick="goTo('club')">Présentation</a>
          <a onclick="goTo('organigramme')">Organigramme</a>
          <a onclick="goTo('horaires')">Horaires & Tarifs</a>
          <a onclick="goTo('adhesion')">Formules d'adhésion</a>
          <a onclick="goTo('agenda')">Agenda</a>
          <a onclick="goTo('contact')">Contact</a>
        </div>
      </div>
      <div class="nav-item">
        <button class="nav-link" id="nav-tournois" onclick="goTo('tournois')">Tournois</button>
      </div>
      <div class="nav-item">
        <button class="nav-link nav-link-fij" id="nav-fij" onclick="goTo('fij')">★ FIJ 2027</button>
      </div>
      <div class="nav-item">
        <button class="nav-link" id="nav-activites">Activités <span class="chevron">▾</span></button>
        <div class="dropdown">
          <a onclick="goToTab('activites','cours')">Cours & Formation</a>
          <a onclick="goToTab('activites','stages')">Stages</a>
          <a onclick="goToTab('activites','scolaire')">Interventions scolaires</a>
        </div>
      </div>
      <div class="nav-item">
        <button class="nav-link" id="nav-partenaires" onclick="goTo('partenaires')">Partenaires</button>
      </div>
      <div class="nav-item">
        <button class="nav-link" id="nav-agenda" onclick="goTo('agenda')">Agenda</button>
      </div>
    </div>
    <button class="nav-cta" onclick="goTo('adhesion')">S'inscrire</button>
    <button class="nav-hamburger" id="nav-hamburger" onclick="toggleMobileNav()" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>
  <div class="nav-mobile" id="nav-mobile">
    <button class="nav-mobile-link" onclick="mobileGoTo('actualites')">Actualités</button>
    <button class="nav-mobile-link" onclick="mobileGoTo('club')">Le Club</button>
    <button class="nav-mobile-link" onclick="mobileGoTo('organigramme')">Organigramme</button>
    <button class="nav-mobile-link" onclick="mobileGoTo('horaires')">Horaires & Tarifs</button>
    <button class="nav-mobile-link" onclick="mobileGoTo('adhesion')">Adhésion</button>
    <div class="nav-mobile-sep"></div>
    <button class="nav-mobile-link" onclick="mobileGoTo('activites','cours')">Cours & Formation</button>
    <button class="nav-mobile-link" onclick="mobileGoTo('activites','stages')">Stages</button>
    <button class="nav-mobile-link" onclick="mobileGoTo('activites','scolaire')">Interventions scolaires</button>
    <button class="nav-mobile-link" onclick="mobileGoTo('tournois')">Tournois</button>
    <div class="nav-mobile-sep"></div>
    <button class="nav-mobile-link" onclick="mobileGoTo('agenda')">Agenda</button>
    <button class="nav-mobile-link" onclick="mobileGoTo('partenaires')">Partenaires</button>
    <button class="nav-mobile-link" onclick="mobileGoTo('contact')">Contact</button>
    <div class="nav-mobile-sep"></div>
    <button class="nav-mobile-link gold" onclick="mobileGoTo('fij')">★ FIJ 2027</button>
    <button class="nav-mobile-cta" onclick="mobileGoTo('adhesion')">S'inscrire →</button>
  </div>
</nav>

<!-- =====================================================
     PAGES
     ===================================================== -->
<main id="app">

<!-- ═══════════════════════════════════════════════════
     PAGE ACCUEIL
     ═══════════════════════════════════════════════════ -->
<div id="page-home" class="page">
  <section class="home-hero">
    <div class="container">
      <div class="home-hero-grid">
        <div>
          <h1>Cannes<br><em>Échecs</em></h1>
          <p class="sub">Club d'excellence depuis 1985</p>
          <p class="desc">Plus de 200 membres, 10 titres de Champion de France Jeunes, 30 ans d'intervention scolaire. Le club de référence de la Côte d'Azur, en plein cœur de Cannes.</p>
          <div class="home-hero-btns">
            <button class="btn btn-gold" onclick="goTo('adhesion')">Rejoindre le club →</button>
            <button class="btn btn-outline-white" onclick="goTo('club')">Découvrir le club</button>
          </div>
        </div>
        <div>
          <div class="fij-hero-card" id="hero-widget">
            <div class="fij-hero-card-label">♟ Tournois d'Échecs · FIJ 2027</div>
            <h3>Festival International<br>des Jeux — Cannes</h3>
            <div class="cd-title">Ouverture des inscriptions dans</div>
            <div class="cd-row">
              <div class="cd-item"><span class="cd-num" id="h-cd-j">--</span><span class="cd-label">Jours</span></div>
              <div class="cd-sep">:</div>
              <div class="cd-item"><span class="cd-num" id="h-cd-h">--</span><span class="cd-label">Heures</span></div>
              <div class="cd-sep">:</div>
              <div class="cd-item"><span class="cd-num" id="h-cd-m">--</span><span class="cd-label">Min</span></div>
            </div>
            <button class="btn btn-gold btn-full btn-sm" onclick="goTo('fij')">Découvrir le FIJ 2027 →</button>
            <div class="fij-hero-stats">
              <div class="fij-hs"><div class="n">100 000+</div><div class="l">visiteurs FIJ</div></div>
              <div class="fij-hs"><div class="n">A · B · C</div><div class="l">tournois par Elo</div></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="stats-band">
    <div class="container stats-band-inner">
      <div class="sb-item"><div class="sb-num">200+</div><div class="sb-label">Membres actifs</div></div>
      <div class="sb-item"><div class="sb-num">10×</div><div class="sb-label">Champion de France Jeunes</div></div>
      <div class="sb-item"><div class="sb-num">30+</div><div class="sb-label">Ans d'intervention scolaire</div></div>
      <div class="sb-item"><div class="sb-num">FFE</div><div class="sb-label">Label Club Formateur</div></div>
    </div>
  </div>
  <section class="section-actus">
    <div class="container">
      <div class="section-actus-header">
        <div>
          <span class="surtitre">Vie du club</span>
          <h2 style="font-size:42px;color:var(--bleu)">Dernières actualités</h2>
        </div>
        <a class="link-all" onclick="goTo('actualites')">Toutes les actualités →</a>
      </div>
      <div class="actu-grid" id="home-actus-grid"></div>
    </div>
  </section>
  <section class="section-fij">
    <div class="container">
      <div class="fij-grid">
        <div class="fij-text">
          <div class="badge badge-event" style="margin-bottom:16px">Événement annuel · Chaque février à Cannes</div>
          <h2>Festival International<br>des <em>Jeux</em></h2>
          <div class="gold-bar"></div>
          <p class="desc">Le FIJ est le plus grand festival de jeux de société du monde, organisé chaque année au Palais des Festivals de Cannes. Cannes Échecs y organise les tournois d'échecs officiels, homologués FIDE.</p>
          <ul class="fij-list">
            <li>Tournois A (2200+), B (1600–2200) et C (≤ 1600) — tous niveaux</li>
            <li>9 rondes · 1h30/40 coups + 30 min + 30s/coup · Normes MI et GMI possibles</li>
            <li>Palais des Festivals (Salon des Ambassadeurs) — Chaque année en février</li>
          </ul>
          <div class="fij-btns">
            <button class="btn btn-gold" onclick="goTo('fij')">Découvrir le FIJ 2027 →</button>
            <button class="btn btn-outline-white" onclick="window.open(HELLOASSO.fij,'_blank','noopener,noreferrer')">S'inscrire sur HelloAsso</button>
          </div>
          <div class="fij-mini-stats">
            <div class="fij-ms"><div class="n">100 000+</div><div class="l">visiteurs du festival</div></div>
            <div class="fij-ms"><div class="n">3</div><div class="l">tournois A / B / C</div></div>
            <div class="fij-ms"><div class="n">9</div><div class="l">rondes</div></div>
          </div>
        </div>
        <div>
          <div class="fij-cd-box">
            <div class="cd-title">FIJ 2027 — Coup d'envoi dans</div>
            <div class="fij-cd-row">
              <div class="fij-cd-item"><span class="fij-cd-num" id="f-cd-j">--</span><span class="fij-cd-label">Jours</span></div>
              <div class="fij-cd-sep">:</div>
              <div class="fij-cd-item"><span class="fij-cd-num" id="f-cd-h">--</span><span class="fij-cd-label">Heures</span></div>
              <div class="fij-cd-sep">:</div>
              <div class="fij-cd-item"><span class="fij-cd-num" id="f-cd-m">--</span><span class="fij-cd-label">Min</span></div>
              <div class="fij-cd-sep">:</div>
              <div class="fij-cd-item"><span class="fij-cd-num" id="f-cd-s">--</span><span class="fij-cd-label">Sec</span></div>
            </div>
            <button class="btn btn-gold btn-full" style="margin-bottom:12px" onclick="goTo('fij')">Voir les tarifs & s'inscrire →</button>
            <p style="font-size:12px;color:rgba(255,255,255,.4);text-align:center;margin-top:12px;font-style:italic">Inscription via HelloAsso · MI / GMI : participation gratuite</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="section-forces">
    <div class="container" style="position:relative;z-index:1">
      <div class="section-header center light">
        <span class="surtitre">Pourquoi nous rejoindre</span>
        <h2 style="font-size:42px;color:#fff">Un club d'exception</h2>
      </div>
      <div class="forces-grid">
        <div class="force-card"><span class="force-icon">🏆</span><span class="force-num">10×</span><div class="force-label">Champion de France Jeunes</div><p class="force-desc">Un palmarès qui témoigne de l'excellence de notre formation</p></div>
        <div class="force-card"><span class="force-icon">📚</span><span class="force-num">30+</span><div class="force-label">Ans scolaire</div><p class="force-desc">Partenaire de l'Éducation Nationale depuis plus de trois décennies</p></div>
        <div class="force-card"><span class="force-icon">👥</span><span class="force-num">200+</span><div class="force-label">Membres actifs</div><p class="force-desc">Une communauté soudée du débutant de 5 ans au joueur classé</p></div>
        <div class="force-card"><span class="force-icon">🎖</span><span class="force-num">FFE</span><div class="force-label">Label Club Formateur</div><p class="force-desc">Reconnu par la Fédération Française des Échecs</p></div>
      </div>
    </div>
  </section>
  <section class="section-cta">
    <div class="cta-inner">
      <div class="badge badge-event" style="margin-bottom:20px">Saison 2026–2027 ouverte</div>
      <h2>Rejoignez<br><em>Cannes Échecs</em></h2>
      <p>Inscription en ligne sécurisée via HelloAsso. Dès 60€/an, licence FFE incluse. Tous niveaux, tous âges.</p>
      <div class="cta-btns">
        <button class="btn btn-gold btn-lg" onclick="goTo('adhesion')">S'inscrire maintenant →</button>
        <button class="btn btn-outline-white btn-lg" onclick="goTo('club')">En savoir plus</button>
      </div>
      <p class="cta-note">Paiement sécurisé · Confirmation immédiate · Annulation possible</p>
    </div>
  </section>
</div><!-- fin page-home -->


<!-- ═══════════════════════════════════════════════════
     PAGE LE CLUB
     ═══════════════════════════════════════════════════ -->
<div id="page-club" class="page">
  <section class="hero-shared" style="background:linear-gradient(160deg,var(--bleu) 0%,#0A1F38 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a onclick="goTo('home')">Accueil</a><span>›</span><span>Le Club</span></div>
      <h1 style="font-size:58px;color:#fff">Le <em>Club</em></h1>
      <p class="hero-desc">Plus de 30 ans d'excellence échiquéenne sur la Côte d'Azur</p>
    </div>
  </section>
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
        <div class="club-histoire-img">♟</div>
      </div>
    </div>
  </section>
  <section class="club-equipe" style="background:var(--ivoire)">
    <div class="container">
      <div class="section-header center" style="margin-bottom:40px">
        <span class="surtitre">L'équipe</span>
        <h2 style="font-size:40px;color:var(--bleu)">Ceux qui font vivre le club</h2>
      </div>
      <div class="equipe-grid">
        <div class="team-card"><div class="team-avatar">👤</div><div class="team-name">Joffrey</div><div class="team-role">Animateur</div></div>
        <div class="team-card"><div class="team-avatar">👤</div><div class="team-name">Nicolas</div><div class="team-role">Animateur</div></div>
        <div class="team-card"><div class="team-avatar">👤</div><div class="team-name">Romu</div><div class="team-role">Directeur sportif</div></div>
        <div class="team-card"><div class="team-avatar">👤</div><div class="team-name">Marlies</div><div class="team-role">Coordinatrice</div></div>
      </div>
    </div>
  </section>
  <section class="section-cta">
    <div class="cta-inner">
      <div class="badge badge-event" style="margin-bottom:20px">Rejoignez-nous</div>
      <h2>Faire partie de<br><em>l'aventure</em></h2>
      <p>Toutes les formules d'adhésion pour la saison 2026–2027 sont disponibles en ligne sur HelloAsso.</p>
      <div class="cta-btns"><button class="btn btn-gold btn-lg" onclick="goTo('adhesion')">Voir les formules d'adhésion →</button></div>
    </div>
  </section>
</div><!-- fin page-club -->


<!-- ═══════════════════════════════════════════════════
     PAGE ACTUALITÉS (ARCHIVE)
     ═══════════════════════════════════════════════════ -->
<div id="page-actualites" class="page">
  <section class="hero-shared" style="background:linear-gradient(135deg,var(--bleu) 0%,var(--noir) 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a onclick="goTo('home')">Accueil</a><span>›</span><span>Actualités</span></div>
      <h1 style="font-size:54px;color:#fff">Actualités</h1>
      <p class="hero-desc">Résultats, événements, vie du club — toute l'info Cannes Échecs.</p>
    </div>
  </section>
  <div class="archive-filtres">
    <div class="container archive-filtres-inner">
      <button class="filtre-btn active-gold">Toutes</button>
      <button class="filtre-btn">Résultats</button>
      <button class="filtre-btn" title="À venir">Formation</button>
      <button class="filtre-btn">Tournois</button>
      <button class="filtre-btn">Scolaire</button>
      <button class="filtre-btn">Club</button>
    </div>
  </div>
  <section class="archive-grid-section">
    <div class="container">
      <div class="archive-grid"></div>
      <div class="pagination" style="opacity:.5;pointer-events:none">
        <button class="pg-btn pg-active">1</button>
      </div>
    </div>
  </section>
</div><!-- fin page-actualites -->


<!-- ═══════════════════════════════════════════════════
     PAGE ARTICLE SINGLE
     ═══════════════════════════════════════════════════ -->
<div id="page-article" class="page">
  <section class="article-hero">
    <div class="container" style="max-width:800px">
      <div class="breadcrumb" style="color:rgba(255,255,255,.4);display:flex;align-items:center;gap:8px;font-size:12px;margin-bottom:20px">
        <a onclick="goTo('home')" style="color:rgba(255,255,255,.4)">Accueil</a><span>›</span>
        <a onclick="goTo('actualites')" style="color:rgba(255,255,255,.4)">Actualités</a><span>›</span>
        <span style="color:rgba(255,255,255,.6)">Article</span>
      </div>
      <div id="art-badge" class="badge badge-gold" style="margin-bottom:16px">Actualité</div>
      <h1 id="art-title" style="font-size:46px;color:#fff;line-height:1.1;margin-bottom:16px">Titre de l'article</h1>
      <div class="article-meta">
        <div id="art-date" class="meta-item"><span>📅</span> Date</div>
        <div id="art-cat" class="meta-item"><span>🏷</span> Catégorie</div>
      </div>
    </div>
  </section>
  <div class="article-content-wrap">
    <div class="container" style="max-width:800px">
      <div id="art-img" class="article-featured-img">♟</div>
      <div id="art-body" class="article-body">
        <p style="color:var(--muted);font-style:italic;text-align:center;padding:40px 0">Sélectionnez un article depuis les actualités.</p>
      </div>
      <div id="art-gallery" class="art-gallery" style="display:none"></div>
      <div class="article-share">
        <span class="share-label">Partager :</span>
        <button id="share-native" class="share-btn" style="background:var(--bleu);color:#fff;display:none" onclick="shareNative()">⬆ Partager</button>
        <button id="share-fb" class="share-btn share-fb" onclick="shareFb()">Facebook</button>
        <button id="share-tw" class="share-btn share-tw" onclick="shareTw()">X / Twitter</button>
      </div>
    </div>
  </div>
  <section class="article-related">
    <div class="container">
      <span class="surtitre">À lire aussi</span>
      <h2 style="font-size:36px;color:var(--bleu);margin-bottom:30px">Autres actualités</h2>
      <div id="art-related" class="actu-grid"></div>
    </div>
  </section>
</div><!-- fin page-article -->


<!-- ═══════════════════════════════════════════════════
     PAGE ACTIVITÉS
     ═══════════════════════════════════════════════════ -->
<div id="page-activites" class="page">
  <section class="hero-shared" style="background:linear-gradient(135deg,var(--bleu) 0%,#0A1F38 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a onclick="goTo('home')">Accueil</a><span>›</span><span>Activités</span></div>
      <h1 style="font-size:54px;color:#fff">Nos <em>activités</em></h1>
      <p class="hero-desc">Du cours débutant aux séances de compétition — trouvez votre niveau.</p>
    </div>
  </section>
  <div style="background:#fff;border-bottom:2px solid var(--border);position:sticky;top:64px;z-index:100">
    <div class="container" style="display:flex;gap:0">
      <button class="tab-btn tab-active" data-tab="cours">Cours & Formation</button>
      <button class="tab-btn" data-tab="competitions">Compétitions</button>
      <button class="tab-btn" data-tab="stages">Stages</button>
      <button class="tab-btn" data-tab="scolaire">Scolaire</button>
      <button class="tab-btn" data-tab="loisir">Loisir</button>
    </div>
  </div>
  <div class="tab-panel tab-panel-active" id="tab-cours" style="padding:60px 0;background:var(--ivoire)">
    <div class="container">
      <div class="section-header" style="margin-bottom:36px"><span class="surtitre">Tous niveaux · Tous âges</span><h2 style="font-size:38px;color:var(--bleu)">Cours & Formation</h2></div>
      <p style="font-size:15px;color:var(--text);line-height:1.8;max-width:680px;margin-bottom:32px">Cannes Échecs propose des cours pour tous les niveaux et tous les âges : Pitchounets, jeunes débutants, jeunes confirmés, adultes débutants et adultes confirmés. Les cours ont lieu le mardi soir (17h–19h) et le mercredi après-midi.</p>
      <div style="background:var(--bleu);border-radius:12px;padding:28px 32px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px">
        <div>
          <div style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--gold);margin-bottom:6px">Saison 2026–2027 · Inscriptions ouvertes</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;color:#fff;font-weight:700">Rejoignez un cours adapté à votre niveau</div>
          <div style="font-size:13px;color:rgba(255,255,255,.6);margin-top:6px">Contactez-nous pour connaître les horaires et les groupes disponibles</div>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap">
          <button class="btn btn-gold btn-sm" onclick="goTo('adhesion')">S'inscrire →</button>
          <button class="btn btn-outline-white btn-sm" onclick="goTo('contact')">Nous contacter →</button>
        </div>
      </div>
    </div>
  </div>
  <div class="tab-panel" id="tab-competitions" style="padding:60px 0;background:var(--ivoire);display:none">
    <div class="container">
      <h2 style="font-size:34px;color:var(--bleu);margin-bottom:8px">Compétitions</h2>
      <p style="font-size:15px;color:var(--text);line-height:1.8;max-width:640px;margin-bottom:32px">Cannes Échecs engage ses membres dans les compétitions officielles FFE (Championnat de France par équipes, championnats jeunes) et organise ses propres tournois internes tout au long de l'année.</p>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:32px">
        <div style="background:#fff;border-radius:12px;padding:24px;border:1px solid var(--border)">
          <div style="font-size:28px;margin-bottom:10px">🏆</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:6px">Compétitions officielles</div>
          <div style="font-size:13px;color:var(--muted);line-height:1.7">Championnat de France par équipes · Championnats jeunes FFE (U8→U18) · Interclubs régionaux</div>
        </div>
        <div style="background:#fff;border-radius:12px;padding:24px;border:1px solid var(--border)">
          <div style="font-size:28px;margin-bottom:10px">🎯</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:6px">Tournois du club</div>
          <div style="font-size:13px;color:var(--muted);line-height:1.7">PICO mensuel · Open de Pâques · Rapide du vendredi · Tournois scolaires · FIJ Cannes</div>
        </div>
      </div>
      <div style="background:var(--bleu);border-radius:12px;padding:28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px">
        <div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:24px;color:var(--gold);margin-bottom:8px">Dates, formats et inscriptions</div>
          <p style="font-size:14px;color:rgba(255,255,255,.7);line-height:1.7;max-width:480px">Consultez la page Tournois pour les calendriers détaillés, les formats de jeu et les modalités d'inscription à chaque compétition.</p>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;flex-shrink:0">
          <button class="btn btn-gold" onclick="goTo('tournois')">Voir les tournois →</button>
          <button class="btn btn-outline-white btn-sm" onclick="goTo('adhesion')">Prendre une licence FFE</button>
        </div>
      </div>
    </div>
  </div>
  <div class="tab-panel" id="tab-stages" style="padding:60px 0;background:var(--ivoire);display:none">
    <div class="container">
      <div class="section-header" style="margin-bottom:36px"><span class="surtitre">Vacances scolaires</span><h2 style="font-size:38px;color:var(--bleu)">Stages</h2></div>
      <p style="font-size:15px;color:var(--text);line-height:1.8;max-width:680px;margin-bottom:36px">Cannes Échecs organise des stages intensifs pendant les vacances scolaires. Tous niveaux, encadrés par nos formateurs agréés. Inscriptions en ligne via HelloAsso.</p>
      <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px">
        <div style="background:#fff;border-radius:12px;border:1px solid var(--border);overflow:hidden;box-shadow:var(--sh-sm)">
          <div style="background:var(--bleu);padding:18px 22px;display:flex;align-items:center;justify-content:space-between">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Toussaint 2026</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:#fff">Stage Toussaint</div></div>
            <span style="font-size:32px">🍂</span>
          </div>
          <div style="padding:20px 22px">
            <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:18px">
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0">📅</span><span>Dates à confirmer</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0">👥</span><span>Enfants · Tous niveaux</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0">⏱</span><span>5 jours · 9h–12h</span></div>
            </div>
            <button class="btn btn-outline-dark btn-sm btn-full" disabled style="opacity:.45;cursor:not-allowed">Inscriptions à venir</button>
          </div>
        </div>
        <div style="background:#fff;border-radius:12px;border:1px solid var(--border);overflow:hidden;box-shadow:var(--sh-sm)">
          <div style="background:var(--bleu);padding:18px 22px;display:flex;align-items:center;justify-content:space-between">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Hiver 2027</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:#fff">Stage Hiver</div></div>
            <span style="font-size:32px">❄️</span>
          </div>
          <div style="padding:20px 22px">
            <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:18px">
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0">📅</span><span>Dates à confirmer</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0">👥</span><span>Enfants · Tous niveaux</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0">⏱</span><span>5 jours · 9h–12h</span></div>
            </div>
            <button class="btn btn-outline-dark btn-sm btn-full" disabled style="opacity:.45;cursor:not-allowed">Inscriptions à venir</button>
          </div>
        </div>
        <div style="background:#fff;border-radius:12px;border:1px solid var(--border);overflow:hidden;box-shadow:var(--sh-sm)">
          <div style="background:var(--bleu);padding:18px 22px;display:flex;align-items:center;justify-content:space-between">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Pâques 2027</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:#fff">Stage Pâques</div></div>
            <span style="font-size:32px">🐣</span>
          </div>
          <div style="padding:20px 22px">
            <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:18px">
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0">📅</span><span>Dates à confirmer</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0">👥</span><span>Enfants · Tous niveaux</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0">⏱</span><span>5 jours · 9h–12h</span></div>
            </div>
            <button class="btn btn-outline-dark btn-sm btn-full" disabled style="opacity:.45;cursor:not-allowed">Inscriptions à venir</button>
          </div>
        </div>
        <div style="background:#fff;border-radius:12px;border:1px solid var(--border);overflow:hidden;box-shadow:var(--sh-sm)">
          <div style="background:var(--bleu);padding:18px 22px;display:flex;align-items:center;justify-content:space-between">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Été 2027</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:#fff">Stage Été</div></div>
            <span style="font-size:32px">☀️</span>
          </div>
          <div style="padding:20px 22px">
            <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:18px">
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0">📅</span><span>Dates à confirmer</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0">👥</span><span>Enfants · Tous niveaux</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0">⏱</span><span>5 jours · 9h–12h</span></div>
            </div>
            <button class="btn btn-outline-dark btn-sm btn-full" disabled style="opacity:.45;cursor:not-allowed">Inscriptions à venir</button>
          </div>
        </div>
      </div>
      <div style="margin-top:24px;text-align:center">
        <button class="btn btn-outline-dark btn-sm" onclick="goTo('contact')">Être informé en priorité →</button>
      </div>
    </div>
  </div>
  <div class="tab-panel" id="tab-scolaire" style="padding:60px 0;background:var(--ivoire);display:none">
    <div class="container">
      <div style="display:grid;grid-template-columns:55% 45%;gap:60px;align-items:center">
        <div>
          <span class="surtitre">Depuis 1994 · Saison 2026–2027</span>
          <h2 style="font-size:38px;color:var(--bleu);margin-bottom:16px">Les échecs<br>à l'école</h2>
          <div class="gold-bar"></div>
          <p style="font-size:15px;color:var(--text);line-height:1.8;margin-bottom:16px">Cannes Échecs intervient dans les établissements scolaires du bassin cannois depuis plus de 30 ans. Nos formateurs agréés par l'Éducation Nationale dispensent des séances hebdomadaires d'initiation aux échecs.</p>
          <div style="display:flex;gap:20px;margin:24px 0;flex-wrap:wrap">
            <div style="text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:36px;color:var(--gold);font-weight:700">12</div><div style="font-size:11px;color:var(--muted);text-transform:uppercase;font-family:'Montserrat',sans-serif;font-weight:600;letter-spacing:.08em">Écoles partenaires</div></div>
            <div style="text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:36px;color:var(--gold);font-weight:700">600+</div><div style="font-size:11px;color:var(--muted);text-transform:uppercase;font-family:'Montserrat',sans-serif;font-weight:600;letter-spacing:.08em">Élèves/an</div></div>
            <div style="text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:36px;color:var(--gold);font-weight:700">30+</div><div style="font-size:11px;color:var(--muted);text-transform:uppercase;font-family:'Montserrat',sans-serif;font-weight:600;letter-spacing:.08em">Ans de partenariat</div></div>
          </div>
          <div style="display:flex;gap:10px;flex-wrap:wrap">
            <button class="btn btn-outline-dark" onclick="goTo('contact')">Faire venir les échecs dans votre école →</button>
            <button class="btn btn-outline-dark" onclick="goToTab('tournois','t-scolaire')">Bilan 2025–2026 →</button>
          </div>
        </div>
        <div style="background:linear-gradient(135deg,var(--bleu),#0A1F38);border-radius:16px;padding:32px;box-shadow:var(--sh-lg);color:#fff;display:flex;flex-direction:column;gap:16px;justify-content:center">
          <div style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--gold);margin-bottom:4px">Bilan 2025–2026</div>
          <div style="display:flex;flex-direction:column;gap:10px">
            <div style="background:rgba(201,168,76,.15);border-radius:8px;padding:12px 16px"><div style="font-size:13px;font-weight:600;color:var(--gold)">🏆 Championnat Ville de Cannes — 130 enfants</div></div>
            <div style="background:rgba(255,255,255,.07);border-radius:8px;padding:12px 16px"><div style="font-size:13px;font-weight:600;color:#fff">🥇 Sainte-Marie — Championne d'Académie</div></div>
            <div style="background:rgba(255,255,255,.07);border-radius:8px;padding:12px 16px"><div style="font-size:13px;font-weight:600;color:#fff">🥇 Stanislas — Champion Académique collèges</div></div>
            <div style="background:rgba(255,255,255,.07);border-radius:8px;padding:12px 16px"><div style="font-size:13px;font-weight:600;color:#fff">🥇 Stanislas — Champion Départ. écoles & collèges</div></div>
          </div>
          <div style="font-size:12px;color:rgba(255,255,255,.4);margin-top:4px">Inscriptions 2026–2027 ouvertes en septembre</div>
        </div>
      </div>
    </div>
  </div>
  <div class="tab-panel" id="tab-loisir" style="padding:60px 0;background:var(--ivoire);display:none">
    <div class="container">
      <div class="section-header" style="margin-bottom:36px"><span class="surtitre">Détente & plaisir</span><h2 style="font-size:38px;color:var(--bleu)">Jouer sans compétition</h2></div>
      <p style="font-size:15px;color:var(--text);line-height:1.8;max-width:680px;margin-bottom:32px">Pas de compétition ? Aucun problème. Le club accueille les amateurs de tous âges qui souhaitent simplement jouer, progresser à leur rythme et partager un moment convivial autour d'un échiquier.</p>
      <p style="font-size:15px;color:var(--text);line-height:1.8;max-width:680px;margin-bottom:32px">Le club est ouvert lundi, jeudi et vendredi de 13h30 à 18h30, et le mercredi de 13h30 à 20h. Venez jouer librement, sans inscription préalable.</p>
      <div style="display:flex;gap:12px;flex-wrap:wrap">
        <button class="btn btn-gold" onclick="goTo('horaires')">Voir les horaires →</button>
        <button class="btn btn-outline-dark" onclick="goTo('adhesion')">Prendre une licence →</button>
      </div>
    </div>
  </div>
  <section class="section-cta">
    <div class="cta-inner">
      <div class="badge badge-event" style="margin-bottom:20px">Toutes activités disponibles</div>
      <h2>Choisissez votre<br><em>formule</em></h2>
      <p>Inscription en ligne via HelloAsso pour toutes nos activités.</p>
      <div class="cta-btns"><button class="btn btn-gold btn-lg" onclick="goTo('adhesion')">Voir les formules d'adhésion →</button></div>
    </div>
  </section>
</div><!-- fin page-activites -->


<!-- ═══════════════════════════════════════════════════
     PAGE HORAIRES & TARIFS
     ═══════════════════════════════════════════════════ -->
<div id="page-horaires" class="page">
  <section class="hero-shared" style="background:linear-gradient(160deg,var(--bleu) 0%,var(--noir) 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a onclick="goTo('home')">Accueil</a><span>›</span><span>Horaires & Tarifs</span></div>
      <h1 style="font-size:52px;color:#fff">Horaires & <em>Tarifs</em></h1>
      <p style="font-size:14px;color:rgba(255,255,255,.4);margin-top:8px;font-style:italic">Saison 2026–2027 · Mis à jour en septembre 2026</p>
    </div>
  </section>
  <section style="padding:80px 0;background:#fff">
    <div class="container">
      <div style="display:grid;grid-template-columns:60% 40%;gap:48px;align-items:start">
        <div>
          <span class="surtitre">Planning hebdomadaire</span>
          <h2 style="font-size:36px;color:var(--bleu);margin-bottom:24px">Horaires d'ouverture</h2>
          <table style="width:100%;border-collapse:collapse;font-size:14px;font-family:'Inter',sans-serif">
            <thead><tr style="background:var(--bleu);color:#fff"><th style="padding:12px 16px;text-align:left;font-family:'Montserrat',sans-serif;font-size:11px;letter-spacing:.08em;text-transform:uppercase">Jour</th><th style="padding:12px 16px;text-align:left;font-family:'Montserrat',sans-serif;font-size:11px;letter-spacing:.08em;text-transform:uppercase">Horaires</th><th style="padding:12px 16px;text-align:left;font-family:'Montserrat',sans-serif;font-size:11px;letter-spacing:.08em;text-transform:uppercase">Activité</th></tr></thead>
            <tbody>
              <tr style="border-bottom:1px solid var(--border)"><td style="padding:12px 16px;font-weight:600;color:var(--bleu)">Lundi</td><td style="padding:12px 16px">13h30–18h30</td><td style="padding:12px 16px;color:var(--muted)">Parties libres · Accès club</td></tr>
              <tr style="border-bottom:1px solid var(--border);background:var(--ivoire)"><td style="padding:12px 16px;font-weight:600;color:var(--bleu)">Mardi</td><td style="padding:12px 16px">17h00–19h00</td><td style="padding:12px 16px;color:var(--muted)"><strong style="color:var(--bleu)">Cours jeunes uniquement</strong></td></tr>
              <tr style="border-bottom:1px solid var(--border)"><td style="padding:12px 16px;font-weight:600;color:var(--bleu)">Mercredi</td><td style="padding:12px 16px">13h30–20h00</td><td style="padding:12px 16px;color:var(--muted)">Pitchounets 13h30 · Jeunes 13h30–17h30 · Adultes débutants 17h30 · Adultes confirmés 18h30</td></tr>
              <tr style="border-bottom:1px solid var(--border);background:var(--ivoire)"><td style="padding:12px 16px;font-weight:600;color:var(--bleu)">Jeudi</td><td style="padding:12px 16px">13h30–18h30</td><td style="padding:12px 16px;color:var(--muted)">Parties libres · Accès club</td></tr>
              <tr style="border-bottom:1px solid var(--border)"><td style="padding:12px 16px;font-weight:600;color:var(--bleu)">Vendredi</td><td style="padding:12px 16px">13h30–18h30</td><td style="padding:12px 16px;color:var(--muted)">Parties libres · Rapide du vendredi (soirée)</td></tr>
              <tr style="border-bottom:1px solid var(--border);background:var(--ivoire)"><td style="padding:12px 16px;font-weight:600;color:#9ca3af;font-style:italic">Samedi</td><td colspan="2" style="padding:12px 16px;color:#9ca3af;font-style:italic">Fermé (sauf tournois PICO)</td></tr>
              <tr><td style="padding:12px 16px;font-weight:600;color:#9ca3af;font-style:italic">Dimanche</td><td colspan="2" style="padding:12px 16px;color:#9ca3af;font-style:italic">Fermé (sauf tournois)</td></tr>
            </tbody>
          </table>
        </div>
        <div style="background:var(--bleu);border-radius:16px;padding:32px;color:rgba(255,255,255,.85)">
          <div style="margin-bottom:22px;padding-bottom:22px;border-bottom:1px solid rgba(255,255,255,.1)">
            <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:6px">📍 Adresse</div>
            <div style="font-size:15px;font-weight:500">3 Av. du Petit Juas<br>06400 Cannes</div>
            <a href="https://www.google.com/maps/search/3+Avenue+du+Petit+Juas+06400+Cannes" target="_blank" rel="noopener" style="font-size:12px;color:var(--gold);cursor:pointer;display:block;margin-top:6px">Voir sur Google Maps →</a>
          </div>
          <div style="margin-bottom:22px;padding-bottom:22px;border-bottom:1px solid rgba(255,255,255,.1)">
            <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:6px">📞 Téléphone</div>
            <div style="font-size:15px;font-weight:500">04 93 39 41 39</div>
          </div>
          <div>
            <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:6px">✉ Email</div>
            <div style="font-size:14px">info@cannes-echecs.fr</div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div style="line-height:0">
    <iframe src="https://maps.google.com/maps?q=3%20Avenue%20du%20Petit%20Juas%2006400%20Cannes&z=15&output=embed" width="100%" height="320" style="border:0;display:block" loading="lazy" title="Cannes Échecs — 3 Avenue du Petit Juas, 06400 Cannes"></iframe>
  </div>
  <section style="padding:80px 0;background:var(--ivoire)">
    <div class="container">
      <div class="section-header center" style="margin-bottom:48px">
        <span class="surtitre">Saison 2026–2027</span>
        <h2 style="font-size:42px;color:var(--bleu)">Formules d'adhésion</h2>
        <p style="font-size:15px;color:var(--muted);margin-top:12px;max-width:560px;margin-left:auto;margin-right:auto">Tous les tarifs incluent la licence FFE et l'accès illimité au club pendant les heures d'ouverture.</p>
      </div>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;max-width:960px;margin:0 auto">
        <div class="card-hover" style="background:#fff;border-radius:16px;padding:28px 20px;text-align:center;border:2px solid var(--border);box-shadow:var(--sh-sm)">
          <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:8px">École Échecs</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:16px">Enfants</div>
          <div style="font-size:44px;font-weight:800;color:var(--bleu);line-height:1">290<span style="font-size:18px;font-weight:400;color:var(--muted)">€</span></div>
          <div style="font-size:12px;color:var(--muted);margin:8px 0 16px">Cours + Licence FFE</div>
          <button class="btn btn-gold btn-sm btn-full" onclick="goTo('adhesion')">Choisir</button>
        </div>
        <div class="card-hover" style="background:#fff;border-radius:16px;padding:28px 20px;text-align:center;border:2px solid var(--border);box-shadow:var(--sh-sm)">
          <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:8px">Cours adultes</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:16px">Échecs Adultes</div>
          <div style="font-size:44px;font-weight:800;color:var(--bleu);line-height:1">290<span style="font-size:18px;font-weight:400;color:var(--muted)">€</span></div>
          <div style="font-size:12px;color:var(--muted);margin:8px 0 16px">Cours + Licence FFE</div>
          <button class="btn btn-gold btn-sm btn-full" onclick="goTo('adhesion')">Choisir</button>
        </div>
        <div class="card-hover" style="background:#fff;border-radius:16px;padding:28px 20px;text-align:center;border:2px solid var(--border);box-shadow:var(--sh-sm)">
          <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:8px">Licence A</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:16px">Accès libre</div>
          <div style="font-size:44px;font-weight:800;color:var(--bleu);line-height:1">60<span style="font-size:18px;font-weight:400;color:var(--muted)">€</span></div>
          <div style="font-size:12px;color:var(--muted);margin:8px 0 16px">Sans cours</div>
          <button class="btn btn-gold btn-sm btn-full" onclick="goTo('adhesion')">Choisir</button>
        </div>
      </div>
      <div style="text-align:center;margin-top:28px">
        <p style="font-size:14px;color:var(--muted);margin-bottom:16px">6 formules disponibles · Pitchounets (200€), Compétition (120€), École Famille (500€) et plus</p>
        <button class="btn btn-outline-gold" onclick="goTo('adhesion')">Voir toutes les formules →</button>
      </div>
    </div>
  </section>
  <section class="section-cta">
    <div class="cta-inner">
      <h2>Prêt à<br><em>nous rejoindre ?</em></h2>
      <p>Inscription en ligne sécurisée via HelloAsso. Paiement en 3 fois disponible.</p>
      <div class="cta-btns"><button class="btn btn-gold btn-lg" onclick="goTo('adhesion')">S'inscrire maintenant →</button></div>
    </div>
  </section>
</div><!-- fin page-horaires -->


<!-- ═══════════════════════════════════════════════════
     PAGE ADHÉSION
     ═══════════════════════════════════════════════════ -->
<div id="page-adhesion" class="page">
  <section class="hero-shared" style="background:linear-gradient(160deg,var(--bleu) 0%,var(--noir) 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a onclick="goTo('home')">Accueil</a><span>›</span><span>Adhésion</span></div>
      <div class="badge badge-event" style="margin-bottom:16px">Saison 2026–2027 ouverte</div>
      <h1 style="font-size:52px;color:#fff">Rejoindre le <em>club</em></h1>
      <p class="hero-desc">Inscription en ligne sécurisée via HelloAsso. Confirmation immédiate.</p>
    </div>
  </section>
  <section style="padding:80px 0;background:#fff">
    <div class="container">
      <div class="section-header center" style="margin-bottom:48px">
        <span class="surtitre">Simple & rapide</span>
        <h2 style="font-size:38px;color:var(--bleu)">S'inscrire en 3 étapes</h2>
      </div>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0;position:relative">
        <div style="position:absolute;top:32px;left:calc(16.66% + 16px);right:calc(16.66% + 16px);height:2px;background:linear-gradient(90deg,var(--gold),var(--gold-h));z-index:0"></div>
        <div style="text-align:center;padding:32px 24px;position:relative;z-index:1">
          <div style="width:64px;height:64px;border-radius:50%;background:var(--gold);color:var(--noir);font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;box-shadow:0 0 0 8px rgba(201,168,76,.15)">1</div>
          <h3 style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--bleu);margin-bottom:8px">Choisir votre formule</h3>
          <p style="font-size:13px;color:var(--muted);line-height:1.6">Enfant, ado, adulte loisir ou compétition — sélectionnez le tarif adapté à votre profil.</p>
        </div>
        <div style="text-align:center;padding:32px 24px;position:relative;z-index:1">
          <div style="width:64px;height:64px;border-radius:50%;background:var(--gold);color:var(--noir);font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;box-shadow:0 0 0 8px rgba(201,168,76,.15)">2</div>
          <h3 style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--bleu);margin-bottom:8px">Régler en ligne</h3>
          <p style="font-size:13px;color:var(--muted);line-height:1.6">Paiement sécurisé CB via HelloAsso. Paiement en 3 fois sans frais disponible.</p>
        </div>
        <div style="text-align:center;padding:32px 24px;position:relative;z-index:1">
          <div style="width:64px;height:64px;border-radius:50%;background:var(--gold);color:var(--noir);font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;box-shadow:0 0 0 8px rgba(201,168,76,.15)">3</div>
          <h3 style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--bleu);margin-bottom:8px">Venir au club !</h3>
          <p style="font-size:13px;color:var(--muted);line-height:1.6">Confirmation par email immédiate. Présentez-vous au club dès le prochain cours.</p>
        </div>
      </div>
    </div>
  </section>
  <section style="padding:80px 0;background:var(--ivoire)">
    <div class="container">
      <div class="section-header center" style="margin-bottom:40px">
        <span class="surtitre">Saison 2026–2027</span>
        <h2 style="font-size:38px;color:var(--bleu)">Choisissez votre formule</h2>
      </div>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;max-width:1100px;margin:0 auto">
        <div class="card-hover" style="background:#fff;border-radius:14px;padding:28px;border:2px solid var(--border);box-shadow:var(--sh-sm)">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Pitchounets</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu)">4–6 ans</div></div>
            <div style="font-size:42px;font-weight:800;color:var(--bleu);line-height:1">200€</div>
          </div>
          <ul style="list-style:none;margin-bottom:20px">
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ 1h atelier ludique / semaine</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Licence FFE incluse</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0">✓ Mercredi 13h30–14h30</li>
          </ul>
          <button class="btn btn-gold btn-full" onclick="window.open(HELLOASSO.adhesion,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        </div>
        <div class="card-hover" style="background:#fff;border-radius:14px;padding:28px;border:2px solid var(--border);box-shadow:var(--sh-sm)">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Compétition</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu)">Tous âges</div></div>
            <div style="font-size:42px;font-weight:800;color:var(--bleu);line-height:1">120€</div>
          </div>
          <ul style="list-style:none;margin-bottom:20px">
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Adhésion sans cours</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Interclubs FFE inclus</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0">✓ Licence FFE incluse</li>
          </ul>
          <button class="btn btn-gold btn-full" onclick="window.open(HELLOASSO.adhesion,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        </div>
        <div class="card-hover" style="background:#fff;border-radius:14px;padding:28px;border:2px solid var(--border);box-shadow:var(--sh-sm)">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">École Échecs</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu)">Enfants</div></div>
            <div style="font-size:42px;font-weight:800;color:var(--bleu);line-height:1">290€</div>
          </div>
          <ul style="list-style:none;margin-bottom:20px">
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ 1h cours collectif / semaine</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ 1h pratique encadrée</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0">✓ Licence FFE incluse</li>
          </ul>
          <button class="btn btn-gold btn-full" onclick="window.open(HELLOASSO.adhesion,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        </div>
        <div class="card-hover" style="background:#fff;border-radius:14px;padding:28px;border:2px solid var(--border);box-shadow:var(--sh-sm)">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Cours adultes</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu)">Échecs Adultes</div></div>
            <div style="font-size:42px;font-weight:800;color:var(--bleu);line-height:1">290€</div>
          </div>
          <ul style="list-style:none;margin-bottom:20px">
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Cours collectif du mercredi</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Débutants 17h30 · Confirmés 18h30</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0">✓ Licence FFE incluse</li>
          </ul>
          <button class="btn btn-gold btn-full" onclick="window.open(HELLOASSO.adhesion,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        </div>
        <div style="background:#fff;border-radius:14px;padding:28px;border:2px solid var(--gold);box-shadow:var(--sh-gold),var(--sh-sm);position:relative">
          <div style="position:absolute;top:-12px;left:24px"><div class="badge badge-gold">⭐ Famille</div></div>
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">École Famille</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu)">2 membres</div></div>
            <div style="font-size:42px;font-weight:800;color:var(--bleu);line-height:1">500€</div>
          </div>
          <ul style="list-style:none;margin-bottom:20px">
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ 2 membres du même foyer</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Licences FFE incluses</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0">✓ 3 membres : <strong>750€</strong></li>
          </ul>
          <button class="btn btn-gold btn-full" onclick="window.open(HELLOASSO.adhesion,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        </div>
        <div class="card-hover" style="background:#fff;border-radius:14px;padding:28px;border:2px solid var(--border);box-shadow:var(--sh-sm)">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Licence A</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu)">Accès libre</div></div>
            <div style="font-size:42px;font-weight:800;color:var(--bleu);line-height:1">60€</div>
          </div>
          <ul style="list-style:none;margin-bottom:20px">
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Adhésion sans cours ni interclubs</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Accès libre au club</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0">✓ Participation PICO gratuite</li>
          </ul>
          <button class="btn btn-gold btn-full" onclick="window.open(HELLOASSO.adhesion,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        </div>
      </div>
      <div style="max-width:1100px;margin:24px auto 0;background:var(--ivoire);border-radius:12px;padding:20px 28px;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border)">
        <div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Cours particuliers</div><div style="font-size:14px;color:var(--muted)">Sur rendez-vous avec l'un de nos entraîneurs</div></div>
        <div style="display:flex;gap:32px;text-align:center">
          <div><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--bleu)">40€</div><div style="font-size:11px;color:var(--muted)">/ heure · adhérents</div></div>
          <div><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--bleu)">50€</div><div style="font-size:11px;color:var(--muted)">/ heure · non-adhérents</div></div>
        </div>
      </div>
    </div>
  </section>
</div><!-- fin page-adhesion -->


<!-- ═══════════════════════════════════════════════════
     PAGE CONTACT
     ═══════════════════════════════════════════════════ -->
<div id="page-contact" class="page">
  <section class="hero-shared" style="background:linear-gradient(160deg,var(--bleu) 0%,var(--noir) 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a onclick="goTo('home')">Accueil</a><span>›</span><span>Contact</span></div>
      <h1 style="font-size:52px;color:#fff">Contactez-<em>nous</em></h1>
      <p class="hero-desc">Une question sur les cours, les tarifs, les inscriptions ? Nous répondons sous 48h.</p>
    </div>
  </section>
  <div style="background:var(--gold);padding:22px 0">
    <div class="container" style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px">
      <div style="display:flex;align-items:center;gap:14px;justify-content:center">
        <div style="font-size:24px">📞</div>
        <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(0,0,0,.5);margin-bottom:2px">Téléphone</div><div style="font-size:15px;font-weight:600;color:var(--noir)">04 93 39 41 39</div></div>
      </div>
      <div style="display:flex;align-items:center;gap:14px;justify-content:center">
        <div style="font-size:24px">✉</div>
        <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(0,0,0,.5);margin-bottom:2px">Email</div><div style="font-size:15px;font-weight:600;color:var(--noir)">info@cannes-echecs.fr</div></div>
      </div>
      <div style="display:flex;align-items:center;gap:14px;justify-content:center">
        <div style="font-size:24px">📍</div>
        <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(0,0,0,.5);margin-bottom:2px">En personne</div><div style="font-size:15px;font-weight:600;color:var(--noir)">3 Av. du Petit Juas</div></div>
      </div>
    </div>
  </div>
  <section style="padding:80px 0;background:#fff">
    <div class="container">
      <div style="display:grid;grid-template-columns:60% 40%;gap:48px;align-items:start">
        <div>
          <span class="surtitre">Formulaire de contact</span>
          <h2 style="font-size:36px;color:var(--bleu);margin-bottom:28px">Envoyez-nous<br>un message</h2>
          <form id="contact-form" onsubmit="sendContact(event)" novalidate>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div><label style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);display:block;margin-bottom:6px">Prénom & Nom *</label><input id="cf-nom" type="text" name="nom" required placeholder="Votre nom" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:14px;background:var(--ivoire);outline:none;transition:border-color .2s,box-shadow .2s"></div>
            <div><label style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);display:block;margin-bottom:6px">Email *</label><input id="cf-email" type="email" name="email" required placeholder="votre@email.fr" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:14px;background:var(--ivoire);outline:none;transition:border-color .2s,box-shadow .2s"></div>
          </div>
          <div style="margin-bottom:16px"><label style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);display:block;margin-bottom:6px">Objet</label>
            <select id="cf-sujet" name="sujet" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:14px;background:var(--ivoire);outline:none;color:var(--noir);transition:border-color .2s,box-shadow .2s">
              <option value="cours">Renseignements sur les cours</option>
              <option value="tarifs">Tarifs et inscriptions</option>
              <option value="partenariat">Partenariat / sponsoring</option>
              <option value="scolaire">Intervention scolaire</option>
              <option value="presse">Presse et médias</option>
              <option value="autre">Autre</option>
            </select>
          </div>
          <div style="margin-bottom:16px"><label style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);display:block;margin-bottom:6px">Message *</label><textarea id="cf-message" name="message" rows="5" required placeholder="Votre message..." style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:14px;background:var(--ivoire);outline:none;resize:vertical;transition:border-color .2s,box-shadow .2s"></textarea></div>
          <div style="margin-bottom:20px;display:flex;align-items:flex-start;gap:10px"><input id="cf-rgpd" type="checkbox" name="rgpd" required style="margin-top:3px;accent-color:var(--gold);width:16px;height:16px;flex-shrink:0"><label for="cf-rgpd" style="font-size:13px;color:var(--muted)">J'accepte que mes données soient utilisées pour traiter ma demande.</label></div>
          <div id="cf-feedback" style="display:none;padding:14px 18px;border-radius:8px;margin-bottom:16px;font-size:14px;font-weight:600"></div>
          <button type="submit" class="btn btn-gold btn-full" style="font-size:13px;padding:16px">Envoyer mon message →</button>
          </form>
        </div>
        <div>
          <div style="background:var(--ivoire);border-radius:12px;padding:24px;margin-bottom:14px;border-left:4px solid var(--gold)">
            <h4 style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--bleu);margin-bottom:14px">Nous trouver</h4>
            <div style="font-size:14px;color:var(--text);line-height:1.7;margin-bottom:12px">3 Av. du Petit Juas<br>06400 Cannes</div>
            <a href="https://www.google.com/maps/search/3+Avenue+du+Petit+Juas+06400+Cannes" target="_blank" rel="noopener" style="font-size:12px;color:var(--gold);cursor:pointer;font-family:'Montserrat',sans-serif;font-weight:600;letter-spacing:.06em;text-transform:uppercase">Itinéraire Google Maps →</a>
          </div>
          <div style="background:var(--ivoire);border-radius:12px;padding:24px;margin-bottom:14px;border-left:4px solid var(--gold)">
            <h4 style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--bleu);margin-bottom:14px">Quand nous rendre visite ?</h4>
            <div style="font-size:14px;color:var(--text);line-height:1.8">Lun · Jeu · Ven : 13h30–18h30<br>Mardi : cours jeunes 17h–19h<br>Mercredi : 13h30–20h · cours dès 13h30</div>
          </div>
          <div style="background:var(--bleu);border-radius:12px;padding:24px;color:#fff">
            <h4 style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--gold);margin-bottom:14px">Contacts spécialisés</h4>
            <div style="font-size:13px;color:rgba(255,255,255,.7);margin-bottom:8px">📚 Formation : <a href="mailto:formation@cannes-echecs.fr" style="color:var(--gold)">formation@cannes-echecs.fr</a></div>
            <div style="font-size:13px;color:rgba(255,255,255,.7);margin-bottom:8px">🏆 Compétitions : <a href="mailto:competition@cannes-echecs.fr" style="color:var(--gold)">competition@cannes-echecs.fr</a></div>
            <div style="font-size:13px;color:rgba(255,255,255,.7)">🏫 Scolaire : <a href="mailto:scolaire@cannes-echecs.fr" style="color:var(--gold)">scolaire@cannes-echecs.fr</a></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div style="line-height:0">
    <iframe src="https://maps.google.com/maps?q=3%20Avenue%20du%20Petit%20Juas%2006400%20Cannes&z=15&output=embed" width="100%" height="320" style="border:0;display:block" loading="lazy" title="Cannes Échecs — 3 Avenue du Petit Juas, 06400 Cannes"></iframe>
  </div>
</div><!-- fin page-contact -->


<!-- ═══════════════════════════════════════════════════
     PAGE TOURNOIS
     ═══════════════════════════════════════════════════ -->
<div id="page-tournois" class="page">
  <section class="hero-shared" style="background:linear-gradient(160deg,var(--bleu) 0%,var(--noir) 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a onclick="goTo('home')">Accueil</a><span>›</span><span>Tournois</span></div>
      <h1 style="font-size:52px;color:#fff">Nos <em>tournois</em></h1>
      <p class="hero-desc">PICO mensuel, Open de Pâques, Rapide du vendredi, championnats scolaires — la compétition toute l'année.</p>
    </div>
  </section>
  <div style="background:#fff;border-bottom:2px solid var(--border);position:sticky;top:64px;z-index:100">
    <div class="container" style="display:flex;gap:0;overflow-x:auto">
      <button class="tab-btn tab-active" data-tab="t-pico">PICO</button>
      <button class="tab-btn" data-tab="t-paques">Open Pâques</button>
      <button class="tab-btn" data-tab="t-rapide">Rapide</button>
      <button class="tab-btn" data-tab="t-scolaire">Scolaire</button>
    </div>
  </div>

  <!-- TAB PICO -->
  <div class="tab-panel tab-panel-active" id="tab-t-pico" style="padding:60px 0;background:var(--ivoire)">
    <div class="container">
      <div style="display:grid;grid-template-columns:55% 45%;gap:48px;align-items:start">
        <div>
          <div class="badge badge-event" style="margin-bottom:16px">Chaque mois · Saison 2026–2027</div>
          <h2 style="font-size:40px;color:var(--bleu);margin-bottom:12px">Tournoi PICO</h2>
          <div class="gold-bar"></div>
          <p style="font-size:15px;color:var(--text);line-height:1.8;margin-bottom:20px">Le PICO (Parties Individuelles Cannes Open) est le tournoi mensuel du club. Format parties rapides, homologué FFE, ouvert à tous les adhérents. Chaque manche compte pour le classement annuel PICO.</p>
          <div style="background:#fff;border-radius:12px;padding:22px 24px;border:1px solid var(--border);margin-bottom:20px">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
              <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Format</div><div style="font-size:14px;font-weight:600;color:var(--bleu)">Rapide homologué FFE</div></div>
              <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Cadence</div><div style="font-size:14px;font-weight:600;color:var(--bleu)">15 min + 10 sec/coup</div></div>
              <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Rondes</div><div style="font-size:14px;font-weight:600;color:var(--bleu)">5 rondes suisses</div></div>
              <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Participation</div><div style="font-size:14px;font-weight:600;color:var(--bleu)">Gratuit pour adhérents</div></div>
            </div>
          </div>
          <div style="display:flex;gap:10px;flex-wrap:wrap">
            <button class="btn btn-gold" onclick="window.open(HELLOASSO.pico?.sep ?? HELLOASSO.adhesion,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
            <button class="btn btn-outline-dark" onclick="goTo('adhesion')">Prendre une licence</button>
          </div>
        </div>
        <div>
          <h3 style="font-family:'Cormorant Garamond',serif;font-size:24px;color:var(--bleu);margin-bottom:20px">Calendrier PICO 2026–2027</h3>
          <div style="display:flex;flex-direction:column;gap:8px">
            <div class="pico-row" style="background:#fff;border-radius:10px;padding:14px 18px;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border)">
              <div><div style="font-weight:700;color:var(--bleu);font-size:14px">Septembre 2026</div><div style="font-size:12px;color:var(--muted)">Samedi</div></div>
              <button class="btn btn-gold btn-sm" onclick="window.open(HELLOASSO.pico.sep,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
            <div class="pico-row" style="background:#fff;border-radius:10px;padding:14px 18px;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border)">
              <div><div style="font-weight:700;color:var(--bleu);font-size:14px">Octobre 2026</div><div style="font-size:12px;color:var(--muted)">Samedi</div></div>
              <button class="btn btn-gold btn-sm" onclick="window.open(HELLOASSO.pico.oct,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
            <div class="pico-row" style="background:#fff;border-radius:10px;padding:14px 18px;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border)">
              <div><div style="font-weight:700;color:var(--bleu);font-size:14px">Novembre 2026</div><div style="font-size:12px;color:var(--muted)">Samedi</div></div>
              <button class="btn btn-gold btn-sm" onclick="window.open(HELLOASSO.pico.nov,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
            <div class="pico-row" style="background:#fff;border-radius:10px;padding:14px 18px;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border)">
              <div><div style="font-weight:700;color:var(--bleu);font-size:14px">Décembre 2026</div><div style="font-size:12px;color:var(--muted)">Samedi</div></div>
              <button class="btn btn-gold btn-sm" onclick="window.open(HELLOASSO.pico.dec,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
            <div class="pico-row" style="background:#fff;border-radius:10px;padding:14px 18px;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border)">
              <div><div style="font-weight:700;color:var(--bleu);font-size:14px">Janvier 2027</div><div style="font-size:12px;color:var(--muted)">Samedi</div></div>
              <button class="btn btn-gold btn-sm" onclick="window.open(HELLOASSO.pico.jan,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
            <div class="pico-row" style="background:#fff;border-radius:10px;padding:14px 18px;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border)">
              <div><div style="font-weight:700;color:var(--bleu);font-size:14px">Mars 2027</div><div style="font-size:12px;color:var(--muted)">Samedi</div></div>
              <button class="btn btn-gold btn-sm" onclick="window.open(HELLOASSO.pico.mar,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
            <div class="pico-row" style="background:#fff;border-radius:10px;padding:14px 18px;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border)">
              <div><div style="font-weight:700;color:var(--bleu);font-size:14px">Avril 2027</div><div style="font-size:12px;color:var(--muted)">Samedi</div></div>
              <button class="btn btn-gold btn-sm" onclick="window.open(HELLOASSO.pico.avr,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
            <div class="pico-row" style="background:#fff;border-radius:10px;padding:14px 18px;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border)">
              <div><div style="font-weight:700;color:var(--bleu);font-size:14px">Mai 2027</div><div style="font-size:12px;color:var(--muted)">Samedi</div></div>
              <button class="btn btn-gold btn-sm" onclick="window.open(HELLOASSO.pico.mai,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
            <div class="pico-row" style="background:#fff;border-radius:10px;padding:14px 18px;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border)">
              <div><div style="font-weight:700;color:var(--bleu);font-size:14px">Juin 2027</div><div style="font-size:12px;color:var(--muted)">Samedi · Clôture de saison</div></div>
              <button class="btn btn-gold btn-sm" onclick="window.open(HELLOASSO.pico.jun,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- TAB PÂQUES -->
  <div class="tab-panel" id="tab-t-paques" style="padding:60px 0;background:var(--ivoire);display:none">
    <div class="container">
      <div style="display:grid;grid-template-columns:55% 45%;gap:48px;align-items:start">
        <div>
          <div class="badge badge-event" style="margin-bottom:16px">Avril 2027 · Cannes</div>
          <h2 style="font-size:40px;color:var(--bleu);margin-bottom:12px">Open de Pâques 2027</h2>
          <div class="gold-bar"></div>
          <p style="font-size:15px;color:var(--text);line-height:1.8;margin-bottom:20px">Tournoi ouvert à tous les niveaux, classique et convivial. Se déroule sur le week-end de Pâques au club de Cannes Échecs. Homologué FFE, dotation.</p>
          <div style="background:#fff;border-radius:12px;padding:22px 24px;border:1px solid var(--border);margin-bottom:20px">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
              <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Format</div><div style="font-size:14px;font-weight:600;color:var(--bleu)">Classique homologué FFE</div></div>
              <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Rondes</div><div style="font-size:14px;font-weight:600;color:var(--bleu)">7 rondes</div></div>
              <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Dates</div><div style="font-size:14px;font-weight:600;color:var(--bleu)">Week-end de Pâques 2027</div></div>
              <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Dotation</div><div style="font-size:14px;font-weight:600;color:var(--bleu)">Trophées & prix</div></div>
            </div>
          </div>
          <button class="btn btn-gold" onclick="window.open(HELLOASSO.paques,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        </div>
        <div style="background:var(--bleu);border-radius:16px;padding:32px;color:#fff">
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;color:var(--gold);margin-bottom:20px">Informations pratiques</div>
          <div style="display:flex;flex-direction:column;gap:14px">
            <div style="display:flex;gap:12px;align-items:flex-start"><span style="color:var(--gold);font-size:18px;flex-shrink:0">📍</span><div><div style="font-weight:600;font-size:14px">Lieu</div><div style="font-size:13px;color:rgba(255,255,255,.7)">Cannes Échecs — 3 Av. du Petit Juas, 06400 Cannes</div></div></div>
            <div style="display:flex;gap:12px;align-items:flex-start"><span style="color:var(--gold);font-size:18px;flex-shrink:0">📅</span><div><div style="font-weight:600;font-size:14px">Dates</div><div style="font-size:13px;color:rgba(255,255,255,.7)">Week-end de Pâques 2027 — dates exactes à confirmer</div></div></div>
            <div style="display:flex;gap:12px;align-items:flex-start"><span style="color:var(--gold);font-size:18px;flex-shrink:0">💶</span><div><div style="font-weight:600;font-size:14px">Droits d'inscription</div><div style="font-size:13px;color:rgba(255,255,255,.7)">Tarifs à confirmer</div></div></div>
            <div style="display:flex;gap:12px;align-items:flex-start"><span style="color:var(--gold);font-size:18px;flex-shrink:0">✉</span><div><div style="font-weight:600;font-size:14px">Contact</div><div style="font-size:13px;color:rgba(255,255,255,.7)">info@cannes-echecs.fr</div></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- TAB RAPIDE -->
  <div class="tab-panel" id="tab-t-rapide" style="padding:60px 0;background:var(--ivoire);display:none">
    <div class="container">
      <div style="display:grid;grid-template-columns:55% 45%;gap:48px;align-items:start">
        <div>
          <div class="badge badge-event" style="margin-bottom:16px">Chaque vendredi soir</div>
          <h2 style="font-size:40px;color:var(--bleu);margin-bottom:12px">Rapide du vendredi</h2>
          <div class="gold-bar"></div>
          <p style="font-size:15px;color:var(--text);line-height:1.8;margin-bottom:20px">Soirée de parties rapides chaque vendredi au club, ouverte à tous les adhérents. Format convivial, non homologué. Idéal pour s'entraîner en conditions de jeu rapide.</p>
          <div style="background:#fff;border-radius:12px;padding:22px 24px;border:1px solid var(--border);margin-bottom:20px">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
              <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Format</div><div style="font-size:14px;font-weight:600;color:var(--bleu)">Rapide interne</div></div>
              <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Cadence</div><div style="font-size:14px;font-weight:600;color:var(--bleu)">15 min</div></div>
              <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Horaire</div><div style="font-size:14px;font-weight:600;color:var(--bleu)">Vendredi 18h30–21h</div></div>
              <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Participation</div><div style="font-size:14px;font-weight:600;color:var(--bleu)">Gratuit pour adhérents</div></div>
            </div>
          </div>
          <button class="btn btn-gold" onclick="goTo('adhesion')">Devenir adhérent →</button>
        </div>
        <div style="background:var(--bleu);border-radius:16px;padding:32px;color:#fff">
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;color:var(--gold);margin-bottom:16px">Chaque vendredi</div>
          <p style="font-size:14px;color:rgba(255,255,255,.7);line-height:1.8;margin-bottom:20px">Pas besoin d'inscription à l'avance. Présentez-vous au club à partir de 18h30 avec votre carte d'adhérent.</p>
          <div style="background:rgba(201,168,76,.15);border-radius:10px;padding:16px 20px">
            <div style="font-size:13px;color:var(--gold);font-weight:600;margin-bottom:6px">Ouvert uniquement aux adhérents</div>
            <div style="font-size:13px;color:rgba(255,255,255,.65)">Licence Cannes Échecs requise · Toutes catégories Elo</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- TAB SCOLAIRE -->
  <div class="tab-panel" id="tab-t-scolaire" style="padding:60px 0;background:var(--ivoire);display:none">
    <div class="container">
      <div class="section-header" style="margin-bottom:36px">
        <span class="surtitre">Bilan 2025–2026</span>
        <h2 style="font-size:38px;color:var(--bleu)">Championnats scolaires</h2>
      </div>
      <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:36px">
        <div style="background:linear-gradient(135deg,var(--gold),var(--gold-h));border-radius:12px;padding:24px;display:flex;align-items:center;gap:16px">
          <div style="font-size:36px;flex-shrink:0">🏆</div>
          <div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--noir);margin-bottom:4px">Championnat Ville de Cannes</div><div style="font-size:13px;color:rgba(0,0,0,.6)">130 enfants · 12 écoles · Février 2026</div></div>
        </div>
        <div style="background:#fff;border-radius:12px;padding:24px;display:flex;align-items:center;gap:16px;border:1px solid var(--border)">
          <div style="font-size:36px;flex-shrink:0">🥇</div>
          <div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Sainte-Marie — Championne d'Académie</div><div style="font-size:13px;color:var(--muted)">Catégorie primaire filles · Académie de Nice</div></div>
        </div>
        <div style="background:#fff;border-radius:12px;padding:24px;display:flex;align-items:center;gap:16px;border:1px solid var(--border)">
          <div style="font-size:36px;flex-shrink:0">🥇</div>
          <div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Stanislas — Champion Académique</div><div style="font-size:13px;color:var(--muted)">Catégorie collèges · Académie de Nice</div></div>
        </div>
        <div style="background:#fff;border-radius:12px;padding:24px;display:flex;align-items:center;gap:16px;border:1px solid var(--border)">
          <div style="font-size:36px;flex-shrink:0">🥇</div>
          <div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Stanislas — Champion Départemental</div><div style="font-size:13px;color:var(--muted)">Écoles & collèges · Alpes-Maritimes</div></div>
        </div>
      </div>
      <div style="background:var(--bleu);border-radius:12px;padding:28px 32px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px">
        <div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;color:var(--gold);margin-bottom:8px">Intervention scolaire 2026–2027</div>
          <div style="font-size:14px;color:rgba(255,255,255,.7)">Vous souhaitez accueillir les échecs dans votre école ? Contactez-nous.</div>
        </div>
        <button class="btn btn-gold" onclick="goTo('contact')">Nous contacter →</button>
      </div>
    </div>
  </div>
</div><!-- fin page-tournois -->


<!-- ═══════════════════════════════════════════════════
     PAGE FIJ
     ═══════════════════════════════════════════════════ -->
<div id="page-fij" class="page">
  <section class="fij-hero" style="background:linear-gradient(160deg,var(--bleu) 0%,#0A1F38 100%);padding:80px 0 60px">
    <div class="container">
      <div class="breadcrumb" style="color:rgba(255,255,255,.4);display:flex;align-items:center;gap:8px;font-size:12px;margin-bottom:20px"><a onclick="goTo('home')" style="color:rgba(255,255,255,.4)">Accueil</a><span>›</span><span>FIJ 2027</span></div>
      <div style="display:grid;grid-template-columns:60% 40%;gap:48px;align-items:center">
        <div>
          <div class="badge badge-event" style="margin-bottom:16px">Festival International des Jeux · Cannes · Février 2027</div>
          <h1 style="font-size:58px;color:#fff;line-height:1.05;margin-bottom:16px">FIJ 2027<br><em>Les Tournois<br>d'Échecs</em></h1>
          <div class="gold-bar"></div>
          <p style="font-size:15px;color:rgba(255,255,255,.75);line-height:1.8;margin-bottom:28px">Cannes Échecs organise les tournois officiels d'échecs au Festival International des Jeux, le plus grand festival de jeux de société du monde. Trois tournois par niveau Elo, 9 rondes, normes MI et GMI possibles en Open A.</p>
          <div style="display:flex;gap:12px;flex-wrap:wrap">
            <button class="btn btn-gold btn-lg" onclick="window.open(HELLOASSO.fij,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
            <button class="btn btn-outline-white" onclick="document.querySelector('#fij-programme').scrollIntoView({behavior:'smooth'})">Voir le programme ↓</button>
          </div>
        </div>
        <div>
          <div class="fij-cd-box">
            <div class="cd-title" style="font-size:14px;margin-bottom:20px;text-align:center">FIJ 2027 — Coup d'envoi dans</div>
            <div class="fij-cd-row">
              <div class="fij-cd-item"><span class="fij-cd-num" id="fij-cd-j">--</span><span class="fij-cd-label">Jours</span></div>
              <div class="fij-cd-sep">:</div>
              <div class="fij-cd-item"><span class="fij-cd-num" id="fij-cd-h">--</span><span class="fij-cd-label">Heures</span></div>
              <div class="fij-cd-sep">:</div>
              <div class="fij-cd-item"><span class="fij-cd-num" id="fij-cd-m">--</span><span class="fij-cd-label">Min</span></div>
              <div class="fij-cd-sep">:</div>
              <div class="fij-cd-item"><span class="fij-cd-num" id="fij-cd-s">--</span><span class="fij-cd-label">Sec</span></div>
            </div>
            <button class="btn btn-gold btn-full" style="margin-top:20px" onclick="window.open(HELLOASSO.fij,'_blank','noopener,noreferrer')">S'inscrire maintenant →</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats inscrits -->
  <div class="fij-stats-band">
    <div class="container">
      <div class="fij-stats-inner">
        <div class="fij-stat">
          <div class="fij-stat-num" id="stat-inscrits-a"><?= ce_opt_int('fij_inscrits_a_count',0) ?></div>
          <div class="fij-stat-label">Inscrits Open A</div>
        </div>
        <div class="fij-stat">
          <div class="fij-stat-num" id="stat-inscrits-b"><?= ce_opt_int('fij_inscrits_b_count',0) ?></div>
          <div class="fij-stat-label">Inscrits Open B</div>
        </div>
        <div class="fij-stat">
          <div class="fij-stat-num" id="stat-inscrits-c"><?= ce_opt_int('fij_inscrits_c_count',0) ?></div>
          <div class="fij-stat-label">Inscrits Open C</div>
        </div>
        <div class="fij-stat">
          <div class="fij-stat-num">100 000+</div>
          <div class="fij-stat-label">Visiteurs du festival</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Présentation + sidebar -->
  <section style="padding:80px 0;background:#fff">
    <div class="container">
      <div style="display:grid;grid-template-columns:60% 38%;gap:48px;align-items:start">
        <div>
          <span class="surtitre">Présentation</span>
          <h2 style="font-size:38px;color:var(--bleu);margin-bottom:16px">Trois tournois,<br>tous les niveaux</h2>
          <div class="gold-bar"></div>

          <!-- Cartes open A / B / C -->
          <div style="display:flex;flex-direction:column;gap:16px;margin:28px 0">
            <div class="open-card" style="background:linear-gradient(135deg,var(--bleu) 0%,#0A2952 100%);border-radius:14px;padding:24px 28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
              <div>
                <div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:var(--gold);margin-bottom:6px">Open A</div>
                <div style="font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:700;color:#fff;margin-bottom:4px">Élite · Elo 2200+</div>
                <div style="font-size:13px;color:rgba(255,255,255,.6)">Normes MI · GMI possibles · Gratuit pour MI/GMI</div>
              </div>
              <div id="fij-inscrits-a-wrap">
                <?php if(ce_opt('fij_inscrits_a_url')): ?>
                <a href="<?= esc_url(ce_opt('fij_inscrits_a_url')) ?>" target="_blank" rel="noopener" style="display:flex;flex-direction:column;align-items:center;background:rgba(201,168,76,.2);border:1px solid rgba(201,168,76,.4);border-radius:10px;padding:12px 20px;text-decoration:none">
                  <span style="font-family:'Cormorant Garamond',serif;font-size:30px;font-weight:700;color:var(--gold);line-height:1"><?= ce_opt_int('fij_inscrits_a_count',0) ?></span>
                  <span style="font-size:11px;color:rgba(255,255,255,.5);margin-top:2px">inscrits</span>
                </a>
                <?php endif; ?>
              </div>
            </div>
            <div class="open-card" style="background:linear-gradient(135deg,#1a3a5c 0%,#102436 100%);border-radius:14px;padding:24px 28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
              <div>
                <div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:var(--gold);margin-bottom:6px">Open B</div>
                <div style="font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:700;color:#fff;margin-bottom:4px">Confirmés · Elo 1600–2200</div>
                <div style="font-size:13px;color:rgba(255,255,255,.6)">Homologué FIDE · Tournoi suisse</div>
              </div>
              <div id="fij-inscrits-b-wrap">
                <?php if(ce_opt('fij_inscrits_b_url')): ?>
                <a href="<?= esc_url(ce_opt('fij_inscrits_b_url')) ?>" target="_blank" rel="noopener" style="display:flex;flex-direction:column;align-items:center;background:rgba(201,168,76,.2);border:1px solid rgba(201,168,76,.4);border-radius:10px;padding:12px 20px;text-decoration:none">
                  <span style="font-family:'Cormorant Garamond',serif;font-size:30px;font-weight:700;color:var(--gold);line-height:1"><?= ce_opt_int('fij_inscrits_b_count',0) ?></span>
                  <span style="font-size:11px;color:rgba(255,255,255,.5);margin-top:2px">inscrits</span>
                </a>
                <?php endif; ?>
              </div>
            </div>
            <div class="open-card" style="background:linear-gradient(135deg,#162d44 0%,#0d1e2c 100%);border-radius:14px;padding:24px 28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
              <div>
                <div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:var(--gold);margin-bottom:6px">Open C</div>
                <div style="font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:700;color:#fff;margin-bottom:4px">Débutants · Elo ≤ 1600</div>
                <div style="font-size:13px;color:rgba(255,255,255,.6)">Accueil tous niveaux · Parfait pour débuter la compétition</div>
              </div>
              <div id="fij-inscrits-c-wrap">
                <?php if(ce_opt('fij_inscrits_c_url')): ?>
                <a href="<?= esc_url(ce_opt('fij_inscrits_c_url')) ?>" target="_blank" rel="noopener" style="display:flex;flex-direction:column;align-items:center;background:rgba(201,168,76,.2);border:1px solid rgba(201,168,76,.4);border-radius:10px;padding:12px 20px;text-decoration:none">
                  <span style="font-family:'Cormorant Garamond',serif;font-size:30px;font-weight:700;color:var(--gold);line-height:1"><?= ce_opt_int('fij_inscrits_c_count',0) ?></span>
                  <span style="font-size:11px;color:rgba(255,255,255,.5);margin-top:2px">inscrits</span>
                </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- SIDEBAR 3 ÉTATS (géré par main.js > updateCountdowns) -->
        <div>
          <!-- ÉTAT 1 : avant le tournoi (inclut la période d'inscriptions) -->
          <div id="fij-sidebar-avant" style="display:none;background:linear-gradient(160deg,var(--bleu),#0A1F38);border-radius:16px;padding:28px;color:#fff">
            <div style="font-size:36px;margin-bottom:12px">🏆</div>
            <div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin-bottom:8px;border:1px solid rgba(201,168,76,.35);border-radius:20px;display:inline-block;padding:4px 12px">Inscriptions ouvertes</div>
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:700;color:#fff;margin:12px 0 8px;line-height:1.2">FIJ 2027</h3>
            <p style="font-size:13px;color:rgba(255,255,255,.65);line-height:1.7;margin-bottom:18px">Inscrivez-vous dès maintenant via HelloAsso. Maîtres internationaux et Grands Maîtres : inscription gratuite.</p>
            <button class="btn btn-gold btn-full" style="margin-bottom:12px" onclick="window.open(HELLOASSO.fij,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
            <button class="btn btn-outline-white btn-full btn-sm" onclick="document.querySelector('#fij-programme').scrollIntoView({behavior:'smooth'})">Voir le programme ↓</button>
            <div style="font-size:11px;color:rgba(255,255,255,.3);text-align:center;margin-top:10px">Paiement sécurisé · MI/GMI : participation gratuite</div>
          </div>
          <!-- ÉTAT 2 : tournoi en cours -->
          <div id="fij-sidebar-encours" style="display:none;background:linear-gradient(160deg,#0A1F38,var(--noir));border-radius:16px;padding:28px;color:#fff;border:1px solid rgba(201,168,76,.3)">
            <div style="font-size:36px;margin-bottom:12px">⚡</div>
            <div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin-bottom:8px;border:1px solid rgba(201,168,76,.35);background:rgba(251,191,36,.15);border-radius:20px;display:inline-block;padding:4px 12px">Tournoi en cours !</div>
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:700;color:#fff;margin:12px 0 16px;line-height:1.2">FIJ 2027 — Live</h3>
            <div style="background:rgba(201,168,76,.12);border-radius:10px;padding:14px 16px;margin-bottom:12px">
              <div id="fij-sb-ronde-header" style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Ronde en cours</div>
              <div id="fij-sb-ronde-label" style="font-size:14px;font-weight:600;color:#fff">...</div>
              <div id="fij-sb-countdown" style="display:none;margin-top:10px">
                <div style="font-size:11px;color:rgba(255,255,255,.4);margin-bottom:6px">Prochaine ronde dans</div>
                <div style="display:flex;gap:8px;align-items:center">
                  <span id="fij-sb-h" style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--gold)">--</span>
                  <span style="color:rgba(255,255,255,.3)">h</span>
                  <span id="fij-sb-m" style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--gold)">--</span>
                  <span style="color:rgba(255,255,255,.3)">min</span>
                  <span id="fij-sb-s" style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--gold)">--</span>
                  <span style="color:rgba(255,255,255,.3)">s</span>
                </div>
              </div>
            </div>
            <div id="fij-sb-inscrits" style="margin-bottom:14px"></div>
            <button class="btn btn-outline-white btn-full btn-sm" onclick="goTo('contact')">Contact organisateurs</button>
          </div>
          <!-- ÉTAT 3 : tournoi terminé -->
          <div id="fij-sidebar-fini" style="display:none;background:linear-gradient(160deg,var(--bleu),#0A1F38);border-radius:16px;padding:28px;color:#fff">
            <div style="font-size:36px;margin-bottom:12px">🎉</div>
            <div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin-bottom:8px;border:1px solid rgba(201,168,76,.35);border-radius:20px;display:inline-block;padding:4px 12px">Terminé</div>
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:700;color:#fff;margin:12px 0 8px;line-height:1.2">FIJ 2027<br>C'est fini !</h3>
            <p style="font-size:13px;color:rgba(255,255,255,.65);line-height:1.7;margin-bottom:18px">Merci à tous les participants ! Rendez-vous au FIJ 2028.</p>
            <button class="btn btn-gold btn-full" onclick="goTo('actualites')">Voir les résultats →</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Programme des rondes -->
  <section id="fij-programme" style="padding:80px 0;background:var(--ivoire)">
    <div class="container">
      <div class="section-header center" style="margin-bottom:48px">
        <span class="surtitre">FIJ 2027 · 22–28 février</span>
        <h2 style="font-size:42px;color:var(--bleu)">Programme des rondes</h2>
      </div>
      <div style="max-width:760px;margin:0 auto;display:flex;flex-direction:column;gap:12px">
        <?php
        $rondes = [
          ['r1_date','r1_label','2027-02-22T16:30:00','Ronde 1 · Lun. 22 fév. à 16h30','Lundi 22 fév.','16h30'],
          ['r2_date','r2_label','2027-02-23T09:00:00','Ronde 2 · Mar. 23 fév. à 9h00','Mardi 23 fév.','9h00'],
          ['r3_date','r3_label','2027-02-23T16:00:00','Ronde 3 · Mar. 23 fév. à 16h00','Mardi 23 fév.','16h00'],
          ['r4_date','r4_label','2027-02-24T15:00:00','Ronde 4 · Mer. 24 fév. à 15h00','Mercredi 24 fév.','15h00'],
          ['r5_date','r5_label','2027-02-25T09:00:00','Ronde 5 · Jeu. 25 fév. à 9h00','Jeudi 25 fév.','9h00'],
          ['r6_date','r6_label','2027-02-25T16:00:00','Ronde 6 · Jeu. 25 fév. à 16h00','Jeudi 25 fév.','16h00'],
          ['r7_date','r7_label','2027-02-26T15:00:00','Ronde 7 · Ven. 26 fév. à 15h00','Vendredi 26 fév.','15h00'],
          ['r8_date','r8_label','2027-02-27T15:00:00','Ronde 8 · Sam. 27 fév. à 15h00','Samedi 27 fév.','15h00'],
          ['r9_date','r9_label','2027-02-28T10:00:00','Ronde 9 · Dim. 28 fév. à 10h00','Dimanche 28 fév.','10h00'],
          ['remise_date','remise_label','2027-02-28T16:30:00','Remise des prix · Dim. 28 fév. à 16h30','Dimanche 28 fév.','16h30'],
        ];
        foreach($rondes as $i => [$dk,$lk,$dd,$dl,$jour,$heure]):
          $is_remise = ($i === 9);
          $num = $is_remise ? '★' : ($i+1);
          $label = ce_opt('fij_'.$lk, $dl);
        ?>
        <div style="background:#fff;border-radius:12px;padding:20px 24px;display:flex;align-items:center;gap:20px;border:1px solid var(--border);box-shadow:var(--sh-sm)<?= $is_remise ? ';border-color:var(--gold)' : '' ?>">
          <div style="width:44px;height:44px;border-radius:50%;background:<?= $is_remise ? 'var(--gold)' : 'var(--bleu)' ?>;color:<?= $is_remise ? 'var(--noir)' : '#fff' ?>;display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:<?= $is_remise ? '20px' : '18px' ?>;font-weight:700;flex-shrink:0"><?= $num ?></div>
          <div style="flex:1">
            <div style="font-weight:700;font-size:15px;color:var(--bleu);font-family:'Cormorant Garamond',serif"><?= esc_html($label) ?></div>
            <div style="font-size:12px;color:var(--muted);margin-top:2px"><?= esc_html($jour) ?></div>
          </div>
          <div style="font-family:'Montserrat',sans-serif;font-size:14px;font-weight:700;color:<?= $is_remise ? 'var(--gold)' : 'var(--bleu)' ?>"><?= esc_html($heure) ?></div>
        </div>
        <?php endforeach; ?>
      </div>
      <div style="text-align:center;margin-top:16px;font-size:13px;color:var(--muted)">
        Lieu : Palais des Festivals de Cannes — Salon des Ambassadeurs · Cadence : 1h30/40 coups + 30 min + 30s/coup
      </div>
    </div>
  </section>

  <!-- Dotation -->
  <section style="padding:80px 0;background:#fff">
    <div class="container">
      <div class="section-header center" style="margin-bottom:40px">
        <span class="surtitre">FIJ 2027</span>
        <h2 style="font-size:38px;color:var(--bleu)">Dotation</h2>
      </div>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:900px;margin:0 auto">
        <?php
        $opens = [
          ['a','Open A · Elo 2200+','var(--gold)','var(--noir)'],
          ['b','Open B · 1600–2200','var(--bleu)','#fff'],
          ['c','Open C · ≤ 1600','#1a3a5c','#fff'],
        ];
        foreach($opens as [$let,$titre,$bg,$fg]):
          $place1 = ce_opt("fij_dotation_{$let}_1",'TBD');
          $place2 = ce_opt("fij_dotation_{$let}_2",'TBD');
          $place3 = ce_opt("fij_dotation_{$let}_3",'TBD');
        ?>
        <div style="border-radius:14px;overflow:hidden;box-shadow:var(--sh-md)">
          <div style="background:<?= $bg ?>;padding:18px 22px"><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:<?= $fg == '#fff' ? 'rgba(255,255,255,.5)' : 'rgba(0,0,0,.4)' ?>;margin-bottom:4px">Dotation</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:<?= $fg ?>">Open <?= strtoupper($let) ?></div><div style="font-size:12px;color:<?= $fg == '#fff' ? 'rgba(255,255,255,.5)' : 'rgba(0,0,0,.4)' ?>;margin-top:2px"><?= $titre ?></div></div>
          <div style="background:var(--ivoire);padding:18px 22px;display:flex;flex-direction:column;gap:10px">
            <div style="display:flex;align-items:center;gap:10px"><div style="width:28px;height:28px;border-radius:50%;background:var(--gold);color:var(--noir);font-family:'Cormorant Garamond',serif;font-size:14px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">1</div><div style="font-size:14px;font-weight:600;color:var(--bleu)"><?= esc_html($place1) ?></div></div>
            <div style="display:flex;align-items:center;gap:10px"><div style="width:28px;height:28px;border-radius:50%;background:#9ca3af;color:#fff;font-family:'Cormorant Garamond',serif;font-size:14px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">2</div><div style="font-size:14px;font-weight:600;color:var(--bleu)"><?= esc_html($place2) ?></div></div>
            <div style="display:flex;align-items:center;gap:10px"><div style="width:28px;height:28px;border-radius:50%;background:#b45309;color:#fff;font-family:'Cormorant Garamond',serif;font-size:14px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">3</div><div style="font-size:14px;font-weight:600;color:var(--bleu)"><?= esc_html($place3) ?></div></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Section inscrits FIJ (peuplée dynamiquement par main.js > renderFijInscrits) -->
  <div id="fij-inscrits-section" style="padding:0 0 40px;background:var(--ivoire);display:none">
    <div class="container" style="max-width:700px"></div>
  </div>

  <!-- CTA FIJ -->
  <section class="section-cta">
    <div class="cta-inner">
      <div class="badge badge-event" style="margin-bottom:20px">FIJ 2027 · Inscriptions ouvertes</div>
      <h2>Rejoignez<br><em>l'aventure FIJ 2027</em></h2>
      <p>Inscription en ligne via HelloAsso. 3 niveaux de tournois pour jouer selon votre niveau. Maîtres internationaux et Grands Maîtres : inscription gratuite.</p>
      <div class="cta-btns">
        <button class="btn btn-gold btn-lg" onclick="window.open(HELLOASSO.fij,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        <button class="btn btn-outline-white btn-lg" onclick="goTo('contact')">Une question ?</button>
      </div>
      <p class="cta-note">Paiement sécurisé · Confirmation immédiate · MI/GMI : participation gratuite</p>
    </div>
  </section>
</div><!-- fin page-fij -->


<!-- ═══════════════════════════════════════════════════
     PAGE PARTENAIRES
     ═══════════════════════════════════════════════════ -->
<div id="page-partenaires" class="page">
  <section class="hero-shared" style="background:linear-gradient(135deg,var(--bleu) 0%,var(--noir) 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a onclick="goTo('home')">Accueil</a><span>›</span><span>Partenaires</span></div>
      <h1 style="font-size:52px;color:#fff">Nos <em>partenaires</em></h1>
      <p class="hero-desc">Cannes Échecs remercie ses partenaires pour leur soutien à nos activités et au FIJ.</p>
    </div>
  </section>
  <section style="padding:80px 0;background:#fff">
    <div class="container">
      <div class="section-header center" style="margin-bottom:48px">
        <span class="surtitre">Institutions & sponsors</span>
        <h2 style="font-size:38px;color:var(--bleu)">Partenaires officiels</h2>
      </div>
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:60px">
        <div class="card-hover" style="background:var(--ivoire);border-radius:12px;padding:32px 20px;text-align:center;border:1px solid var(--border)">
          <div style="font-size:48px;margin-bottom:12px">🏛</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:700;color:var(--bleu);margin-bottom:6px">Ville de Cannes</div>
          <div style="font-size:12px;color:var(--muted)">Partenaire institutionnel</div>
        </div>
        <div class="card-hover" style="background:var(--ivoire);border-radius:12px;padding:32px 20px;text-align:center;border:1px solid var(--border)">
          <div style="font-size:48px;margin-bottom:12px">♟</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:700;color:var(--bleu);margin-bottom:6px">FFE</div>
          <div style="font-size:12px;color:var(--muted)">Fédération Française des Échecs</div>
        </div>
        <div class="card-hover" style="background:var(--ivoire);border-radius:12px;padding:32px 20px;text-align:center;border:1px solid var(--border)">
          <div style="font-size:48px;margin-bottom:12px">🎮</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:700;color:var(--bleu);margin-bottom:6px">FIJ Cannes</div>
          <div style="font-size:12px;color:var(--muted)">Festival International des Jeux</div>
        </div>
        <div class="card-hover" style="background:var(--ivoire);border-radius:12px;padding:32px 20px;text-align:center;border:1px solid var(--border)">
          <div style="font-size:48px;margin-bottom:12px">📚</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:700;color:var(--bleu);margin-bottom:6px">Éducation Nationale</div>
          <div style="font-size:12px;color:var(--muted)">Partenaire scolaire</div>
        </div>
      </div>
      <div style="background:linear-gradient(160deg,var(--bleu) 0%,#0A1F38 100%);border-radius:16px;padding:48px;text-align:center;color:#fff">
        <div class="badge badge-event" style="margin-bottom:20px">Devenez partenaire</div>
        <h2 style="font-size:38px;color:#fff;margin-bottom:16px">Associez votre marque<br>à <em>l'excellence</em></h2>
        <p style="font-size:15px;color:rgba(255,255,255,.7);max-width:600px;margin:0 auto 28px;line-height:1.8">Cannes Échecs vous offre une visibilité unique lors du Festival International des Jeux (100 000 visiteurs), dans notre communication digitale et lors de nos événements. Plusieurs formules de sponsoring disponibles.</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
          <button class="btn btn-gold btn-lg" onclick="goTo('contact')">Nous contacter →</button>
        </div>
      </div>
    </div>
  </section>
</div><!-- fin page-partenaires -->


<!-- ═══════════════════════════════════════════════════
     PAGE AGENDA
     ═══════════════════════════════════════════════════ -->
<div id="page-agenda" class="page">
  <section class="hero-shared" style="background:linear-gradient(160deg,var(--bleu) 0%,var(--noir) 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a onclick="goTo('home')">Accueil</a><span>›</span><span>Agenda</span></div>
      <h1 style="font-size:52px;color:#fff">Agenda <em>2026–2027</em></h1>
      <p class="hero-desc">Tous les événements de la saison — tournois, stages, championnats, FIJ.</p>
    </div>
  </section>
  <section style="padding:80px 0;background:#fff">
    <div class="container">
      <div style="display:grid;grid-template-columns:65% 32%;gap:40px;align-items:start">
        <div>
          <h2 style="font-family:'Cormorant Garamond',serif;font-size:32px;color:var(--bleu);margin-bottom:28px">Calendrier saison 2026–2027</h2>
          <div style="display:flex;flex-direction:column;gap:0">

            <div class="agenda-month-header" style="background:var(--bleu);color:#fff;padding:10px 20px;border-radius:8px 8px 0 0;font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-top:20px">Septembre 2026</div>
            <div class="agenda-item" style="border:1px solid var(--border);border-top:none;padding:16px 20px;display:flex;gap:16px;align-items:center">
              <div style="min-width:48px;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--bleu);line-height:1">Sep.</div><div style="font-size:11px;color:var(--muted)">Samedi</div></div>
              <div><div style="font-weight:600;color:var(--bleu);font-size:15px;margin-bottom:2px">PICO Septembre</div><div style="font-size:13px;color:var(--muted)">Tournoi rapide mensuel · 5 rondes · 15+10s</div></div>
              <button class="btn btn-gold btn-sm" style="margin-left:auto;flex-shrink:0" onclick="window.open(HELLOASSO.pico.sep,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
            <div class="agenda-item" style="border:1px solid var(--border);border-top:none;padding:16px 20px;display:flex;gap:16px;align-items:center">
              <div style="min-width:48px;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--bleu);line-height:1">Sep.</div><div style="font-size:11px;color:var(--muted)">Rentrée</div></div>
              <div><div style="font-weight:600;color:var(--bleu);font-size:15px;margin-bottom:2px">Rentrée des cours 2026–2027</div><div style="font-size:13px;color:var(--muted)">Début des cours toutes catégories</div></div>
              <button class="btn btn-outline-dark btn-sm" style="margin-left:auto;flex-shrink:0" onclick="goTo('adhesion')">S'inscrire</button>
            </div>

            <div class="agenda-month-header" style="background:var(--bleu);color:#fff;padding:10px 20px;border-radius:8px 8px 0 0;font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-top:20px">Octobre 2026</div>
            <div class="agenda-item" style="border:1px solid var(--border);border-top:none;padding:16px 20px;display:flex;gap:16px;align-items:center">
              <div style="min-width:48px;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--bleu);line-height:1">Oct.</div><div style="font-size:11px;color:var(--muted)">Samedi</div></div>
              <div><div style="font-weight:600;color:var(--bleu);font-size:15px;margin-bottom:2px">PICO Octobre</div><div style="font-size:13px;color:var(--muted)">Tournoi rapide mensuel · 5 rondes</div></div>
              <button class="btn btn-gold btn-sm" style="margin-left:auto;flex-shrink:0" onclick="window.open(HELLOASSO.pico.oct,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
            <div class="agenda-item" style="border:1px solid var(--border);border-top:none;padding:16px 20px;display:flex;gap:16px;align-items:center">
              <div style="min-width:48px;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--bleu);line-height:1">Oct.</div><div style="font-size:11px;color:var(--muted)">Vacances</div></div>
              <div><div style="font-weight:600;color:var(--bleu);font-size:15px;margin-bottom:2px">Stage Toussaint</div><div style="font-size:13px;color:var(--muted)">Stage intensif enfants · 5 jours · 9h–12h</div></div>
              <button class="btn btn-outline-dark btn-sm" style="margin-left:auto;flex-shrink:0" onclick="goToTab('activites','stages')">En savoir plus</button>
            </div>

            <div class="agenda-month-header" style="background:var(--bleu);color:#fff;padding:10px 20px;border-radius:8px 8px 0 0;font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-top:20px">Novembre 2026</div>
            <div class="agenda-item" style="border:1px solid var(--border);border-top:none;padding:16px 20px;display:flex;gap:16px;align-items:center">
              <div style="min-width:48px;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--bleu);line-height:1">Nov.</div><div style="font-size:11px;color:var(--muted)">Samedi</div></div>
              <div><div style="font-weight:600;color:var(--bleu);font-size:15px;margin-bottom:2px">PICO Novembre</div><div style="font-size:13px;color:var(--muted)">Tournoi rapide mensuel · 5 rondes</div></div>
              <button class="btn btn-gold btn-sm" style="margin-left:auto;flex-shrink:0" onclick="window.open(HELLOASSO.pico.nov,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
            <div class="agenda-item" style="border:1px solid var(--border);border-top:none;padding:16px 20px;display:flex;gap:16px;align-items:center;background:rgba(201,168,76,.05);border-left:3px solid var(--gold)">
              <div style="min-width:48px;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--gold);line-height:1">1 Nov.</div><div style="font-size:11px;color:var(--muted)">Ouverture</div></div>
              <div><div style="font-weight:700;color:var(--gold);font-size:15px;margin-bottom:2px">★ Inscriptions FIJ 2027 ouvertes</div><div style="font-size:13px;color:var(--muted)">Inscription en ligne via HelloAsso</div></div>
              <button class="btn btn-gold btn-sm" style="margin-left:auto;flex-shrink:0" onclick="goTo('fij')">Voir le FIJ</button>
            </div>

            <div class="agenda-month-header" style="background:var(--bleu);color:#fff;padding:10px 20px;border-radius:8px 8px 0 0;font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-top:20px">Décembre 2026</div>
            <div class="agenda-item" style="border:1px solid var(--border);border-top:none;padding:16px 20px;display:flex;gap:16px;align-items:center">
              <div style="min-width:48px;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--bleu);line-height:1">Déc.</div><div style="font-size:11px;color:var(--muted)">Samedi</div></div>
              <div><div style="font-weight:600;color:var(--bleu);font-size:15px;margin-bottom:2px">PICO Décembre</div><div style="font-size:13px;color:var(--muted)">Tournoi rapide mensuel · 5 rondes</div></div>
              <button class="btn btn-gold btn-sm" style="margin-left:auto;flex-shrink:0" onclick="window.open(HELLOASSO.pico.dec,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>

            <div class="agenda-month-header" style="background:var(--bleu);color:#fff;padding:10px 20px;border-radius:8px 8px 0 0;font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-top:20px">Janvier 2027</div>
            <div class="agenda-item" style="border:1px solid var(--border);border-top:none;padding:16px 20px;display:flex;gap:16px;align-items:center">
              <div style="min-width:48px;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--bleu);line-height:1">Jan.</div><div style="font-size:11px;color:var(--muted)">Samedi</div></div>
              <div><div style="font-weight:600;color:var(--bleu);font-size:15px;margin-bottom:2px">PICO Janvier</div><div style="font-size:13px;color:var(--muted)">Tournoi rapide mensuel · 5 rondes</div></div>
              <button class="btn btn-gold btn-sm" style="margin-left:auto;flex-shrink:0" onclick="window.open(HELLOASSO.pico.jan,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>

            <div class="agenda-month-header" style="background:var(--gold);color:var(--noir);padding:10px 20px;border-radius:8px 8px 0 0;font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-top:20px">★ Février 2027 — FIJ</div>
            <div class="agenda-item" style="border:2px solid var(--gold);border-top:none;padding:16px 20px;display:flex;gap:16px;align-items:center;background:rgba(201,168,76,.05)">
              <div style="min-width:48px;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--gold);line-height:1">22–28</div><div style="font-size:11px;color:var(--muted)">Fév.</div></div>
              <div><div style="font-weight:700;color:var(--gold);font-size:16px;margin-bottom:4px">FIJ 2027 — Tournois d'Échecs</div><div style="font-size:13px;color:var(--muted)">Open A / B / C · 9 rondes · Palais des Festivals · Remise des prix dim. 28 fév. à 16h30</div></div>
              <button class="btn btn-gold btn-sm" style="margin-left:auto;flex-shrink:0" onclick="goTo('fij')">Découvrir →</button>
            </div>

            <div class="agenda-month-header" style="background:var(--bleu);color:#fff;padding:10px 20px;border-radius:8px 8px 0 0;font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-top:20px">Mars–Juin 2027</div>
            <div class="agenda-item" style="border:1px solid var(--border);border-top:none;padding:16px 20px;display:flex;gap:16px;align-items:center">
              <div style="min-width:48px;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--bleu);line-height:1">Mar.</div><div style="font-size:11px;color:var(--muted)">Samedi</div></div>
              <div><div style="font-weight:600;color:var(--bleu);font-size:15px;margin-bottom:2px">PICO Mars</div><div style="font-size:13px;color:var(--muted)">Tournoi rapide mensuel · 5 rondes</div></div>
              <button class="btn btn-gold btn-sm" style="margin-left:auto;flex-shrink:0" onclick="window.open(HELLOASSO.pico.mar,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
            <div class="agenda-item" style="border:1px solid var(--border);border-top:none;padding:16px 20px;display:flex;gap:16px;align-items:center">
              <div style="min-width:48px;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--bleu);line-height:1">Avr.</div><div style="font-size:11px;color:var(--muted)">Week-end Pâques</div></div>
              <div><div style="font-weight:600;color:var(--bleu);font-size:15px;margin-bottom:2px">Open de Pâques 2027</div><div style="font-size:13px;color:var(--muted)">Tournoi classique · 7 rondes · Dotation</div></div>
              <button class="btn btn-gold btn-sm" style="margin-left:auto;flex-shrink:0" onclick="window.open(HELLOASSO.paques,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
            <div class="agenda-item" style="border:1px solid var(--border);border-top:none;padding:16px 20px;display:flex;gap:16px;align-items:center">
              <div style="min-width:48px;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--bleu);line-height:1">Mai</div><div style="font-size:11px;color:var(--muted)">Samedi</div></div>
              <div><div style="font-weight:600;color:var(--bleu);font-size:15px;margin-bottom:2px">PICO Mai</div><div style="font-size:13px;color:var(--muted)">Tournoi rapide mensuel · 5 rondes</div></div>
              <button class="btn btn-gold btn-sm" style="margin-left:auto;flex-shrink:0" onclick="window.open(HELLOASSO.pico.mai,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
            <div class="agenda-item" style="border:1px solid var(--border);border-top:none;padding:16px 20px;display:flex;gap:16px;align-items:center">
              <div style="min-width:48px;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--bleu);line-height:1">Juin</div><div style="font-size:11px;color:var(--muted)">Clôture</div></div>
              <div><div style="font-weight:600;color:var(--bleu);font-size:15px;margin-bottom:2px">PICO Juin — Clôture de saison</div><div style="font-size:13px;color:var(--muted)">Tournoi final de la saison + remise des prix annuels</div></div>
              <button class="btn btn-gold btn-sm" style="margin-left:auto;flex-shrink:0" onclick="window.open(HELLOASSO.pico.jun,'_blank','noopener,noreferrer')">S'inscrire</button>
            </div>
          </div>
        </div>
        <div>
          <div style="background:var(--bleu);border-radius:14px;padding:28px;color:#fff;margin-bottom:20px;position:sticky;top:90px">
            <div style="font-family:'Cormorant Garamond',serif;font-size:22px;color:var(--gold);margin-bottom:16px">Accès rapide</div>
            <div style="display:flex;flex-direction:column;gap:8px">
              <button class="btn btn-outline-white btn-sm btn-full" onclick="goToTab('tournois','t-pico')">Tournoi PICO →</button>
              <button class="btn btn-outline-white btn-sm btn-full" onclick="goToTab('tournois','t-paques')">Open de Pâques →</button>
              <button class="btn btn-outline-white btn-sm btn-full" onclick="goTo('fij')">FIJ 2027 →</button>
              <button class="btn btn-outline-white btn-sm btn-full" onclick="goToTab('activites','stages')">Stages →</button>
              <div style="border-top:1px solid rgba(255,255,255,.1);margin:8px 0"></div>
              <button class="btn btn-gold btn-full" onclick="goTo('adhesion')">S'inscrire au club →</button>
            </div>
          </div>
          <div style="background:var(--ivoire);border-radius:14px;padding:24px;border:1px solid var(--border)">
            <div style="font-family:'Cormorant Garamond',serif;font-size:18px;color:var(--bleu);margin-bottom:12px">Restez informé</div>
            <p style="font-size:13px;color:var(--muted);line-height:1.7;margin-bottom:14px">Consultez nos actualités pour les mises à jour d'agenda en cours de saison.</p>
            <button class="btn btn-outline-dark btn-sm btn-full" onclick="goTo('actualites')">Voir les actualités →</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</div><!-- fin page-agenda -->


<!-- ═══════════════════════════════════════════════════
     PAGE ORGANIGRAMME
     ═══════════════════════════════════════════════════ -->
<div id="page-organigramme" class="page">
  <section class="hero-shared" style="background:linear-gradient(160deg,var(--bleu) 0%,var(--noir) 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a onclick="goTo('home')">Accueil</a><span>›</span><span>Organigramme</span></div>
      <h1 style="font-size:52px;color:#fff">L'<em>équipe</em></h1>
      <p class="hero-desc">Les bénévoles et animateurs qui font vivre Cannes Échecs.</p>
    </div>
  </section>
  <section style="padding:80px 0;background:var(--ivoire)">
    <div class="container">
      <div class="section-header center" style="margin-bottom:48px">
        <span class="surtitre">Saison 2026–2027</span>
        <h2 style="font-size:38px;color:var(--bleu)">Bureau & équipe</h2>
      </div>
      <div style="display:flex;flex-direction:column;gap:40px">
        <div>
          <div style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:20px;padding-left:4px">Bureau</div>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px">
            <div style="background:#fff;border-radius:12px;padding:24px;text-align:center;border:1px solid var(--border);box-shadow:var(--sh-sm)">
              <div style="width:64px;height:64px;border-radius:50%;background:var(--bleu);color:#fff;display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 14px">👤</div>
              <div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Romuald de Labaca</div>
              <div style="font-size:12px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;letter-spacing:.08em;text-transform:uppercase">Président</div>
            </div>
            <div style="background:#fff;border-radius:12px;padding:24px;text-align:center;border:1px solid var(--border);box-shadow:var(--sh-sm)">
              <div style="width:64px;height:64px;border-radius:50%;background:var(--bleu);color:#fff;display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 14px">👤</div>
              <div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Marlies Bensdorp De Labaca</div>
              <div style="font-size:12px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;letter-spacing:.08em;text-transform:uppercase">Secrétaire</div>
            </div>
            <div style="background:#fff;border-radius:12px;padding:24px;text-align:center;border:1px solid var(--border);box-shadow:var(--sh-sm)">
              <div style="width:64px;height:64px;border-radius:50%;background:var(--bleu);color:#fff;display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 14px">👤</div>
              <div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Mathilde Choisy</div>
              <div style="font-size:12px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;letter-spacing:.08em;text-transform:uppercase">Trésorière</div>
            </div>
          </div>
        </div>
        <div>
          <div style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:20px;padding-left:4px">Équipe pédagogique</div>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px">
            <div style="background:#fff;border-radius:12px;padding:24px;text-align:center;border:1px solid var(--border);box-shadow:var(--sh-sm)">
              <div style="width:64px;height:64px;border-radius:50%;background:var(--gold);color:var(--noir);display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 14px">👤</div>
              <div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Joffrey</div>
              <div style="font-size:12px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;letter-spacing:.08em;text-transform:uppercase">Animateur principal</div>
            </div>
            <div style="background:#fff;border-radius:12px;padding:24px;text-align:center;border:1px solid var(--border);box-shadow:var(--sh-sm)">
              <div style="width:64px;height:64px;border-radius:50%;background:var(--gold);color:var(--noir);display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 14px">👤</div>
              <div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Nicolas</div>
              <div style="font-size:12px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;letter-spacing:.08em;text-transform:uppercase">Animateur</div>
            </div>
            <div style="background:#fff;border-radius:12px;padding:24px;text-align:center;border:1px solid var(--border);box-shadow:var(--sh-sm)">
              <div style="width:64px;height:64px;border-radius:50%;background:var(--gold);color:var(--noir);display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 14px">👤</div>
              <div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Romuald</div>
              <div style="font-size:12px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;letter-spacing:.08em;text-transform:uppercase">Directeur sportif</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div><!-- fin page-organigramme -->

</main><!-- fin #app -->

<!-- =====================================================
     FOOTER
     ===================================================== -->
<footer class="footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
        <img src="<?= esc_url($logo_uri) ?>" alt="Cannes Échecs" style="width:40px;height:40px;object-fit:contain"
             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
        <div style="display:none;width:40px;height:40px;background:var(--gold);border-radius:6px;align-items:center;justify-content:center;font-size:18px;font-weight:900;color:var(--noir)">♟</div>
        <div>
          <div style="font-family:'Montserrat',sans-serif;font-size:14px;font-weight:800;letter-spacing:.05em;text-transform:uppercase;color:#fff">Cannes Échecs</div>
          <div style="font-size:11px;color:rgba(255,255,255,.4)">Club d'échecs de Cannes</div>
        </div>
      </div>
      <p style="font-size:13px;color:rgba(255,255,255,.5);line-height:1.7;margin-bottom:20px">Club d'excellence depuis 1985 sur la Côte d'Azur. Plus de 200 membres, FFE Label Club Formateur, 10× Champion de France Jeunes.</p>
      <div style="display:flex;gap:10px">
        <a href="https://www.facebook.com/canneschessclub" target="_blank" rel="noopener" class="social-btn" aria-label="Facebook">f</a>
        <a href="https://x.com/CannesChessClub" target="_blank" rel="noopener" class="social-btn" aria-label="X / Twitter">✕</a>
        <a href="https://www.instagram.com/cannes_echecs" target="_blank" rel="noopener" class="social-btn" aria-label="Instagram">◎</a>
      </div>
    </div>
    <div class="footer-col">
      <div class="footer-col-title">Le Club</div>
      <a onclick="goTo('club')">Présentation</a>
      <a onclick="goTo('organigramme')">Organigramme</a>
      <a onclick="goTo('actualites')">Actualités</a>
      <a onclick="goTo('horaires')">Horaires & Tarifs</a>
      <a onclick="goTo('adhesion')">Adhésion</a>
      <a onclick="goTo('contact')">Contact</a>
    </div>
    <div class="footer-col">
      <div class="footer-col-title">Activités</div>
      <a onclick="goToTab('activites','cours')">Cours & Formation</a>
      <a onclick="goToTab('activites','stages')">Stages</a>
      <a onclick="goToTab('activites','scolaire')">Scolaire</a>
      <a onclick="goTo('tournois')">Tournois</a>
      <a onclick="goTo('agenda')">Agenda</a>
    </div>
    <div class="footer-col">
      <div class="footer-col-title">Contact</div>
      <div style="font-size:13px;color:rgba(255,255,255,.5);margin-bottom:6px">3 Av. du Petit Juas<br>06400 Cannes</div>
      <a href="tel:+33493394139" style="font-size:13px;color:rgba(255,255,255,.5)">04 93 39 41 39</a>
      <a href="mailto:info@cannes-echecs.fr" style="font-size:13px;color:rgba(255,255,255,.5)">info@cannes-echecs.fr</a>
      <div style="margin-top:12px">
        <button class="btn btn-gold btn-sm" onclick="goTo('adhesion')" style="width:100%">S'inscrire →</button>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
      <div style="font-size:12px;color:rgba(255,255,255,.3)">© <?= date('Y') ?> Cannes Échecs — Tous droits réservés</div>
      <div style="display:flex;gap:16px">
        <a style="font-size:11px;color:rgba(255,255,255,.3);cursor:pointer" onclick="goTo('club')">Mentions légales</a>
        <a style="font-size:11px;color:rgba(255,255,255,.3);cursor:pointer" onclick="goTo('club')">CGU</a>
        <a style="font-size:11px;color:rgba(255,255,255,.3);cursor:pointer" onclick="goTo('club')">Politique de confidentialité</a>
      </div>
    </div>
  </div>
</footer>

<!-- =====================================================
     LIGHTBOX (IDs alignés avec main.js)
     ===================================================== -->
<div id="lightbox" class="lightbox">
  <button class="lb-close" onclick="lbClose()" aria-label="Fermer">✕</button>
  <img id="lb-img" class="lb-img" src="" alt="">
  <div id="lb-counter" style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);font-size:13px;color:rgba(255,255,255,.5);font-family:'Montserrat',sans-serif"></div>
  <button class="lb-nav lb-prev" onclick="lbNav(-1)" aria-label="Précédent">‹</button>
  <button class="lb-nav lb-next" onclick="lbNav(1)" aria-label="Suivant">›</button>
</div>
<!-- Le panneau admin est créé dynamiquement par main.js quand ?admin est dans l'URL -->

<?php wp_footer(); ?>
</body>
</html>
