<?php
/**
 * Template de la page « Nos Activités » (slug WordPress : activites).
 * Généré depuis index.html par build-wp-theme.js — ne pas éditer le HTML ici
 * sans reporter la modification dans index.html tant que les deux coexistent.
 */
get_header(); ?>

<div id="page-activites" class="page active">
  <section class="hero-shared" style="background:linear-gradient(135deg,var(--bleu) 0%,#0A1F38 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>">Accueil</a><span>›</span><span>Activités</span></div>
      <h1>Nos <em>activités</em></h1>
      <p class="hero-desc">Du cours débutant aux séances de compétition — trouvez votre niveau.</p>
    </div>
  </section>

  <!-- Onglets -->
  <div style="background:#fff;border-bottom:2px solid var(--border);position:sticky;top:64px;z-index:100">
    <div class="container" style="display:flex;gap:0">
      <button class="tab-btn tab-active" data-tab="cours">Cours & Formation</button>
      <button class="tab-btn" data-tab="competitions">Compétitions</button>
      <button class="tab-btn" data-tab="stages">Stages</button>
      <button class="tab-btn" data-tab="scolaire">Scolaire</button>
      <button class="tab-btn" data-tab="loisir">Loisir</button>
    </div>
  </div>

  <!-- Onglet Cours -->
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

  <!-- Onglet Compétitions -->
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

  <!-- Onglet Stages -->
  <div class="tab-panel" id="tab-stages" style="padding:60px 0;background:var(--ivoire);display:none">
    <div class="container">
      <div class="section-header" style="margin-bottom:36px"><span class="surtitre">Vacances scolaires</span><h2 style="font-size:38px;color:var(--bleu)">Stages</h2></div>
      <p style="font-size:15px;color:var(--text);line-height:1.8;max-width:680px;margin-bottom:20px">Cannes Échecs organise des stages intensifs pendant les vacances scolaires. Tous niveaux, encadrés par nos formateurs agréés. Inscriptions en ligne via HelloAsso à l'ouverture de chaque stage.</p>
      <button class="btn btn-gold" style="margin-bottom:36px" onclick="goToContactSujet('stage')">Être prévenu du prochain stage →</button>
      <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px">

        <!-- Stage Toussaint -->
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

        <!-- Stage Hiver -->
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

        <!-- Stage Pâques -->
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

        <!-- Stage Été -->
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

  <!-- Onglet Scolaire -->
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

  <!-- Onglet Loisir -->
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

<?php get_footer(); ?>
