<?php
/**
 * Template de la page « Agenda » (slug WordPress : agenda).
 * Généré depuis index.html par build-wp-theme.js — ne pas éditer le HTML ici
 * sans reporter la modification dans index.html tant que les deux coexistent.
 */
get_header(); ?>

<div id="page-agenda" class="page active">
  <section class="hero-shared" style="background:linear-gradient(135deg,var(--bleu) 0%,var(--noir) 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>">Accueil</a><span>›</span><span>Agenda</span></div>
      <h1>Agenda</h1>
      <p class="hero-desc">Tous les événements du club — tournois, cours, stages et soirées.</p>
    </div>
  </section>

  <!-- Filtres catégories -->
  <div style="background:#fff;border-bottom:2px solid var(--border);position:sticky;top:64px;z-index:100;padding:14px 0">
    <div class="container" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
      <button class="filtre-btn active-gold">Tous</button>
      <button class="filtre-btn">Tournois</button>
      <button class="filtre-btn">Cours & Formation</button>
      <button class="filtre-btn">Stages</button>
      <button class="filtre-btn">Soirées</button>
      <button class="filtre-btn">Compétitions FFE</button>
    </div>
  </div>

  <section style="padding:60px 0;background:var(--ivoire)">
    <div class="container">
      <div style="display:grid;grid-template-columns:65% 35%;gap:40px;align-items:start">
        <!-- Liste événements -->
        <div>
          <h2 style="font-size:28px;color:var(--bleu);margin-bottom:20px">Prochains événements</h2>
          <div id="agenda-events" style="display:flex;flex-direction:column;gap:12px">

            <!-- Reprise cours -->
            <div class="card-hover agenda-event" data-cat="formation" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">09</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Sep</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Cours & Formation</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Reprise des cours — Rentrée 2026</div><div style="font-size:13px;color:var(--muted)">Accueil des nouveaux membres · Évaluation de niveau · Tous horaires</div></div>
              <div class="badge badge-bleu" style="font-size:9px;flex-shrink:0">Formation</div>
            </div>

            <!-- Rapide sept -->
            <div class="card-hover agenda-event" data-cat="soirees" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">18</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Sep</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Soirée · 20h00</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Rapide du vendredi</div><div style="font-size:13px;color:var(--muted)">Parties rapides 10+5 · Membres uniquement · Gratuit · Veille du PICO</div></div>
              <div class="badge badge-bleu" style="font-size:9px;flex-shrink:0">Soirées</div>
            </div>

            <!-- PICO sept -->
            <div class="card-hover agenda-event" data-cat="tournois" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">19</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Sep</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Tournoi · 13h30</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">PICO — Septembre 2026</div><div style="font-size:13px;color:var(--muted)">5 rondes · Membres uniquement · Entrée libre</div></div>
              <div class="badge badge-gold" style="font-size:9px;flex-shrink:0">Tournois</div>
            </div>

            <!-- Rapide oct -->
            <div class="card-hover agenda-event" data-cat="soirees" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">09</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Oct</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Soirée · 20h00</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Rapide du vendredi</div><div style="font-size:13px;color:var(--muted)">Parties rapides 10+5 · Membres uniquement · Gratuit · Veille du PICO</div></div>
              <div class="badge badge-bleu" style="font-size:9px;flex-shrink:0">Soirées</div>
            </div>

            <!-- PICO oct -->
            <div class="card-hover agenda-event" data-cat="tournois" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">10</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Oct</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Tournoi · 13h30</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">PICO — Octobre 2026</div><div style="font-size:13px;color:var(--muted)">5 rondes · Membres uniquement · Entrée libre</div></div>
              <div class="badge badge-gold" style="font-size:9px;flex-shrink:0">Tournois</div>
            </div>

            <!-- Rapide nov -->
            <div class="card-hover agenda-event" data-cat="soirees" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">13</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Nov</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Soirée · 20h00</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Rapide du vendredi</div><div style="font-size:13px;color:var(--muted)">Parties rapides 10+5 · Membres uniquement · Gratuit · Veille du PICO</div></div>
              <div class="badge badge-bleu" style="font-size:9px;flex-shrink:0">Soirées</div>
            </div>

            <!-- PICO nov -->
            <div class="card-hover agenda-event" data-cat="tournois" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">14</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Nov</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Tournoi · 13h30</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">PICO — Novembre 2026</div><div style="font-size:13px;color:var(--muted)">5 rondes · Membres uniquement · Entrée libre</div></div>
              <div class="badge badge-gold" style="font-size:9px;flex-shrink:0">Tournois</div>
            </div>

            <!-- Rapide déc -->
            <div class="card-hover agenda-event" data-cat="soirees" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">11</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Déc</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Soirée · 20h00</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Rapide du vendredi</div><div style="font-size:13px;color:var(--muted)">Parties rapides 10+5 · Membres uniquement · Gratuit · Veille du PICO</div></div>
              <div class="badge badge-bleu" style="font-size:9px;flex-shrink:0">Soirées</div>
            </div>

            <!-- PICO déc -->
            <div class="card-hover agenda-event" data-cat="tournois" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">12</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Déc</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Tournoi · 13h30</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">PICO — Décembre 2026</div><div style="font-size:13px;color:var(--muted)">5 rondes · Membres uniquement · Entrée libre</div></div>
              <div class="badge badge-gold" style="font-size:9px;flex-shrink:0">Tournois</div>
            </div>

            <!-- Rapide jan -->
            <div class="card-hover agenda-event" data-cat="soirees" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">08</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Jan</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Soirée · 20h00</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Rapide du vendredi</div><div style="font-size:13px;color:var(--muted)">Parties rapides 10+5 · Membres uniquement · Gratuit · Veille du PICO</div></div>
              <div class="badge badge-bleu" style="font-size:9px;flex-shrink:0">Soirées</div>
            </div>

            <!-- PICO jan -->
            <div class="card-hover agenda-event" data-cat="tournois" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">09</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Jan</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Tournoi · 13h30</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">PICO — Janvier 2027</div><div style="font-size:13px;color:var(--muted)">5 rondes · Membres uniquement · Entrée libre</div></div>
              <div class="badge badge-gold" style="font-size:9px;flex-shrink:0">Tournois</div>
            </div>

            <!-- Rapide mars -->
            <div class="card-hover agenda-event" data-cat="soirees" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">12</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Mar</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Soirée · 20h00</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Rapide du vendredi</div><div style="font-size:13px;color:var(--muted)">Parties rapides 10+5 · Membres uniquement · Gratuit · Veille du PICO</div></div>
              <div class="badge badge-bleu" style="font-size:9px;flex-shrink:0">Soirées</div>
            </div>

            <!-- PICO mars -->
            <div class="card-hover agenda-event" data-cat="tournois" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">13</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Mar</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Tournoi · 13h30</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">PICO — Mars 2027</div><div style="font-size:13px;color:var(--muted)">5 rondes · Membres uniquement · Entrée libre</div></div>
              <div class="badge badge-gold" style="font-size:9px;flex-shrink:0">Tournois</div>
            </div>

            <!-- Rapide avr -->
            <div class="card-hover agenda-event" data-cat="soirees" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">09</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Avr</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Soirée · 20h00</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Rapide du vendredi</div><div style="font-size:13px;color:var(--muted)">Parties rapides 10+5 · Membres uniquement · Gratuit · Veille du PICO</div></div>
              <div class="badge badge-bleu" style="font-size:9px;flex-shrink:0">Soirées</div>
            </div>

            <!-- PICO avr -->
            <div class="card-hover agenda-event" data-cat="tournois" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">10</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Avr</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Tournoi · 13h30</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">PICO — Avril 2027</div><div style="font-size:13px;color:var(--muted)">5 rondes · Membres uniquement · Entrée libre</div></div>
              <div class="badge badge-gold" style="font-size:9px;flex-shrink:0">Tournois</div>
            </div>

            <!-- Rapide mai -->
            <div class="card-hover agenda-event" data-cat="soirees" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">07</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Mai</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Soirée · 20h00</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Rapide du vendredi</div><div style="font-size:13px;color:var(--muted)">Parties rapides 10+5 · Membres uniquement · Gratuit · Veille du PICO</div></div>
              <div class="badge badge-bleu" style="font-size:9px;flex-shrink:0">Soirées</div>
            </div>

            <!-- PICO mai -->
            <div class="card-hover agenda-event" data-cat="tournois" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">08</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Mai</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Tournoi · 13h30</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">PICO — Mai 2027</div><div style="font-size:13px;color:var(--muted)">5 rondes · Membres uniquement · Entrée libre</div></div>
              <div class="badge badge-gold" style="font-size:9px;flex-shrink:0">Tournois</div>
            </div>

            <!-- Rapide juin -->
            <div class="card-hover agenda-event" data-cat="soirees" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">11</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Jun</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Soirée · 20h00</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Rapide du vendredi</div><div style="font-size:13px;color:var(--muted)">Parties rapides 10+5 · Membres uniquement · Gratuit · Veille du PICO</div></div>
              <div class="badge badge-bleu" style="font-size:9px;flex-shrink:0">Soirées</div>
            </div>

            <!-- PICO juin -->
            <div class="card-hover agenda-event" data-cat="tournois" style="background:#fff;border-radius:12px;padding:20px 24px;border:1px solid var(--border);display:flex;align-items:flex-start;gap:18px">
              <div style="background:var(--bleu);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:22px;line-height:1">12</span><span style="font-size:9px;opacity:.7;text-transform:uppercase;letter-spacing:.08em">Jun</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Tournoi · 13h30</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">PICO — Juin 2027</div><div style="font-size:13px;color:var(--muted)">5 rondes · Membres uniquement · Entrée libre</div></div>
              <div class="badge badge-gold" style="font-size:9px;flex-shrink:0">Tournois</div>
            </div>

            <!-- FIJ 2027 -->
            <div class="agenda-event" data-cat="tournois" style="background:#fff;border-radius:12px;padding:20px 24px;border:2px solid var(--gold);display:flex;align-items:flex-start;gap:18px;box-shadow:var(--sh-gold)">
              <div style="background:var(--gold);color:var(--noir);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;width:56px;height:56px;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:14px;line-height:1.1">FÉV.</span><span style="font-size:14px;line-height:1.1">2027</span></div>
              <div style="flex:1"><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">♟ Tournois officiels · 7 jours</div><div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:var(--bleu);margin-bottom:4px">Festival International des Jeux 2027</div><div style="font-size:13px;color:var(--muted)">Tournois A / B / C · 9 rondes · Homologué FIDE · Palais des Festivals</div></div>
              <button class="btn btn-gold btn-sm" style="flex-shrink:0" onclick="goTo('fij')">Détails →</button>
            </div>

            <p id="agenda-empty" style="display:none;text-align:center;color:var(--muted);font-style:italic;padding:32px 0">Aucun événement dans cette catégorie.</p>
          </div>
        </div>
        <!-- Sidebar -->
        <div>
          <div style="background:var(--bleu);border-radius:12px;padding:22px;margin-bottom:16px;color:#fff">
            <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin-bottom:6px">Prochain événement</div>
            <div style="font-family:'Cormorant Garamond',serif;font-size:22px;margin-bottom:4px">Reprise des cours</div>
            <div style="font-size:13px;color:rgba(255,255,255,.6);margin-bottom:4px">Mercredi 9 septembre 2026</div>
            <div style="font-size:13px;color:rgba(255,255,255,.5);margin-bottom:16px">Rentrée saison 2026–2027</div>
            <div style="border-top:1px solid rgba(255,255,255,.1);padding-top:14px;margin-bottom:8px">
              <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin-bottom:6px">Puis</div>
              <div style="font-size:13px;color:rgba(255,255,255,.75);margin-bottom:4px">Ven. 18 sept. — Rapide du vendredi · 20h</div>
              <div style="font-size:13px;color:rgba(255,255,255,.75)">Sam. 19 sept. — PICO Septembre · 13h30</div>
            </div>
          </div>
          <div style="background:var(--ivoire2);border-radius:12px;padding:22px;text-align:center">
            <div style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--bleu);margin-bottom:8px">Abonnez-vous à l'agenda</div>
            <p style="font-size:13px;color:var(--muted);margin-bottom:16px;line-height:1.6">Importez tous les événements directement dans votre calendrier.</p>
            <div style="display:flex;flex-direction:column;gap:8px">
              <button class="btn btn-gold btn-sm btn-full" onclick="downloadICS()">⬇ Télécharger le calendrier (.ics)</button>
              <button class="btn btn-outline-dark btn-sm btn-full" onclick="openGoogleCalendar()">Ajouter sur Google Calendar</button>
            </div>
            <p style="font-size:11px;color:var(--muted);margin-top:10px;font-style:italic">Compatible iPhone, Android, Outlook, Apple Calendar</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</div><!-- fin page-agenda -->

<?php get_footer(); ?>
