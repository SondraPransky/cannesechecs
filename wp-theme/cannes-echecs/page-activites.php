<?php
/**
 * Template de la page « Nos Activités » (slug WordPress : activites).
 * Généré depuis index.html par build-wp-theme.js — ne pas éditer le HTML ici
 * sans reporter la modification dans index.html tant que les deux coexistent.
 */
get_header(); ?>

<div id="page-activites" class="page active">
  <section class="hero-shared" style="background:linear-gradient(135deg,var(--bleu) 0%,var(--bleu-deep) 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>">Accueil</a><span>›</span><span>Activités</span></div>
      <h1>Nos <em>activités</em></h1>
      <p class="hero-desc">Du cours débutant aux séances de compétition — trouvez votre niveau.</p>
    </div>
  </section>

  <!-- Onglets — composant partagé .tab-bar (mêmes que la page Tournois) -->
  <div class="tab-bar">
    <div class="tab-bar-inner">
      <button class="tab-btn tab-active" data-tab="cours">Cours & Formation</button>
      <button class="tab-btn" data-tab="stages">Stages</button>
      <button class="tab-btn" data-tab="scolaire">Scolaire</button>
    </div>
  </div>

  <!-- Onglet Cours -->
  <div class="tab-panel tab-panel-active" id="tab-cours" style="padding:var(--sp-8) 0;background:var(--ivoire)">
    <div class="container">
      <div class="section-header" style="margin-bottom:32px"><span class="surtitre">Tous niveaux · Tous âges</span><h2 style="font-size:var(--fs-h2);color:var(--bleu)">Cours & Formation</h2></div>
      <p style="font-size:15px;color:var(--text);line-height:1.8;max-width:680px;margin-bottom:28px">Cannes Échecs propose des cours encadrés pour tous les âges, du tout-petit à l'adulte confirmé. Trouvez le créneau adapté à votre niveau.</p>

      <!-- Grille des créneaux — Enfants & jeunes / Adultes -->
      <div class="cours-creneaux" style="margin-bottom:24px">
        <div class="card" style="padding:24px 26px">
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu)">Enfants &amp; jeunes</div>
          <div style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--gold-text);margin-bottom:14px">De 4 à 18 ans</div>
          <div style="display:flex;align-items:baseline;gap:12px;padding:12px 0;border-top:1px solid var(--border)"><span style="flex:1;font-weight:700;color:var(--bleu);font-size:14px">Pitchounets <span style="font-weight:400;color:var(--muted);font-size:12px">· 4–6 ans</span></span><span style="font-family:'Cormorant Garamond',serif;font-size:15px;font-weight:700;color:var(--gold-text);white-space:nowrap">Mer. 13h30</span></div>
          <div style="display:flex;align-items:baseline;gap:12px;padding:12px 0;border-top:1px solid var(--border)"><span style="flex:1;font-weight:700;color:var(--bleu);font-size:14px">Jeunes débutants <span style="font-weight:400;color:var(--muted);font-size:12px">· 6–12 ans</span></span><span style="font-family:'Cormorant Garamond',serif;font-size:15px;font-weight:700;color:var(--gold-text);white-space:nowrap">Mar. 17h–19h</span></div>
          <div style="display:flex;align-items:baseline;gap:12px;padding:12px 0;border-top:1px solid var(--border)"><span style="flex:1;font-weight:700;color:var(--bleu);font-size:14px">Jeunes confirmés <span style="font-weight:400;color:var(--muted);font-size:12px">· 8–18 ans</span></span><span style="font-family:'Cormorant Garamond',serif;font-size:15px;font-weight:700;color:var(--gold-text);white-space:nowrap">Mar. 17h–19h</span></div>
        </div>
        <div class="card" style="padding:24px 26px">
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu)">Adultes</div>
          <div style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--gold-text);margin-bottom:14px">Débutant à confirmé</div>
          <div style="display:flex;align-items:baseline;gap:12px;padding:12px 0;border-top:1px solid var(--border)"><span style="flex:1;font-weight:700;color:var(--bleu);font-size:14px">Adultes débutants</span><span style="font-family:'Cormorant Garamond',serif;font-size:15px;font-weight:700;color:var(--gold-text);white-space:nowrap">Mer. 17h30</span></div>
          <div style="display:flex;align-items:baseline;gap:12px;padding:12px 0;border-top:1px solid var(--border)"><span style="flex:1;font-weight:700;color:var(--bleu);font-size:14px">Adultes confirmés</span><span style="font-family:'Cormorant Garamond',serif;font-size:15px;font-weight:700;color:var(--gold-text);white-space:nowrap">Mer. 18h30</span></div>
          <div style="display:flex;align-items:baseline;gap:12px;padding:12px 0;border-top:1px solid var(--border)"><span style="flex:1;font-weight:700;color:var(--bleu);font-size:14px">Cours particuliers <span style="font-weight:400;color:var(--muted);font-size:12px">· sur RDV</span></span><span style="font-family:'Cormorant Garamond',serif;font-size:15px;font-weight:700;color:var(--gold-text);white-space:nowrap">40€ / h</span></div>
        </div>
      </div>

      <div style="background:var(--bleu);border-radius:14px;padding:28px 32px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px">
        <div>
          <div style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--gold);margin-bottom:6px">Saison 2026–2027 · Inscriptions ouvertes</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;color:#fff;font-weight:700">Un doute sur le groupe adapté ?</div>
          <div style="font-size:13px;color:rgba(255,255,255,.6);margin-top:6px">Contactez-nous pour un cours d'essai ou pour placer votre enfant au bon niveau.</div>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap">
          <button class="btn btn-gold btn-sm" onclick="goTo('adhesion')">S'inscrire →</button>
          <button class="btn btn-outline-white btn-sm" onclick="goTo('contact')">Nous contacter →</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Onglet Stages -->
  <div class="tab-panel" id="tab-stages" style="padding:var(--sp-8) 0;background:var(--ivoire);display:none">
    <div class="container">
      <div class="section-header" style="margin-bottom:36px"><span class="surtitre">Vacances scolaires</span><h2 style="font-size:var(--fs-h2);color:var(--bleu)">Stages</h2></div>
      <p style="font-size:15px;color:var(--text);line-height:1.8;max-width:680px;margin-bottom:20px">Cannes Échecs organise des stages intensifs pendant les vacances scolaires. Tous niveaux, encadrés par nos formateurs agréés. Inscriptions en ligne via HelloAsso à l'ouverture de chaque stage.</p>
      <button class="btn btn-gold" style="margin-bottom:36px" onclick="goToContactSujet('stage')">Être prévenu du prochain stage →</button>
      <div class="act-cards-2" style="gap:20px">

        <!-- Stage Toussaint -->
        <div style="background:#fff;border-radius:14px;border:1px solid var(--border);overflow:hidden;box-shadow:var(--sh-sm)">
          <div style="background:var(--bleu);padding:18px 22px;display:flex;align-items:center;justify-content:space-between">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Toussaint 2026</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:#fff">Stage Toussaint</div></div>
            <span style="font-size:32px;color:var(--gold)"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-leaf"/></svg></span>
          </div>
          <div style="padding:20px 22px">
            <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:18px">
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-calendar"/></svg></span><span>Dates à confirmer</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-users"/></svg></span><span>Enfants · Tous niveaux</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-clock"/></svg></span><span>5 jours · 9h–12h</span></div>
            </div>
            <button class="btn btn-outline-dark btn-sm btn-full" disabled style="opacity:.45;cursor:not-allowed">Inscriptions à venir</button>
          </div>
        </div>

        <!-- Stage Hiver -->
        <div style="background:#fff;border-radius:14px;border:1px solid var(--border);overflow:hidden;box-shadow:var(--sh-sm)">
          <div style="background:var(--bleu);padding:18px 22px;display:flex;align-items:center;justify-content:space-between">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Hiver 2027</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:#fff">Stage Hiver</div></div>
            <span style="font-size:32px;color:var(--gold)"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-snow"/></svg></span>
          </div>
          <div style="padding:20px 22px">
            <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:18px">
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-calendar"/></svg></span><span>Dates à confirmer</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-users"/></svg></span><span>Enfants · Tous niveaux</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-clock"/></svg></span><span>5 jours · 9h–12h</span></div>
            </div>
            <button class="btn btn-outline-dark btn-sm btn-full" disabled style="opacity:.45;cursor:not-allowed">Inscriptions à venir</button>
          </div>
        </div>

        <!-- Stage Pâques -->
        <div style="background:#fff;border-radius:14px;border:1px solid var(--border);overflow:hidden;box-shadow:var(--sh-sm)">
          <div style="background:var(--bleu);padding:18px 22px;display:flex;align-items:center;justify-content:space-between">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Pâques 2027</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:#fff">Stage Pâques</div></div>
            <span style="font-size:32px;color:var(--gold)"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-sprout"/></svg></span>
          </div>
          <div style="padding:20px 22px">
            <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:18px">
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-calendar"/></svg></span><span>Dates à confirmer</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-users"/></svg></span><span>Enfants · Tous niveaux</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-clock"/></svg></span><span>5 jours · 9h–12h</span></div>
            </div>
            <button class="btn btn-outline-dark btn-sm btn-full" disabled style="opacity:.45;cursor:not-allowed">Inscriptions à venir</button>
          </div>
        </div>

        <!-- Stage Été -->
        <div style="background:#fff;border-radius:14px;border:1px solid var(--border);overflow:hidden;box-shadow:var(--sh-sm)">
          <div style="background:var(--bleu);padding:18px 22px;display:flex;align-items:center;justify-content:space-between">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:4px">Été 2027</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:#fff">Stage Été</div></div>
            <span style="font-size:32px;color:var(--gold)"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-sun"/></svg></span>
          </div>
          <div style="padding:20px 22px">
            <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:18px">
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-calendar"/></svg></span><span>Dates à confirmer</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-users"/></svg></span><span>Enfants · Tous niveaux</span></div>
              <div style="font-size:13px;color:var(--muted);display:flex;gap:8px"><span style="color:var(--gold);flex-shrink:0"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-clock"/></svg></span><span>5 jours · 9h–12h</span></div>
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
  <div class="tab-panel" id="tab-scolaire" style="padding:var(--sp-8) 0;background:var(--ivoire);display:none">
    <div class="container">
      <div style="display:grid;grid-template-columns:55% 45%;gap:60px;align-items:start">
        <div>
          <span class="surtitre">Depuis 1994 · Saison 2026–2027</span>
          <h2 style="font-size:var(--fs-h2);color:var(--bleu);margin-bottom:16px">Les échecs<br>à l'école</h2>
          <div class="gold-bar"></div>
          <p style="font-size:15px;color:var(--text);line-height:1.8;margin-bottom:16px">Cannes Échecs intervient dans les établissements scolaires du bassin cannois depuis plus de 30 ans. Nos formateurs agréés par l'Éducation Nationale dispensent des séances hebdomadaires d'initiation aux échecs.</p>
          <div style="display:flex;gap:20px;margin:24px 0;flex-wrap:wrap">
            <div style="text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:36px;color:var(--gold-text);font-weight:700">12</div><div style="font-size:11px;color:var(--muted);text-transform:uppercase;font-family:'Montserrat',sans-serif;font-weight:600;letter-spacing:.08em">Écoles partenaires</div></div>
            <div style="text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:36px;color:var(--gold-text);font-weight:700">600+</div><div style="font-size:11px;color:var(--muted);text-transform:uppercase;font-family:'Montserrat',sans-serif;font-weight:600;letter-spacing:.08em">Élèves/an</div></div>
            <div style="text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:36px;color:var(--gold-text);font-weight:700">30+</div><div style="font-size:11px;color:var(--muted);text-transform:uppercase;font-family:'Montserrat',sans-serif;font-weight:600;letter-spacing:.08em">Ans de partenariat</div></div>
          </div>
          <div style="display:flex;gap:10px;flex-wrap:wrap">
            <button class="btn btn-outline-dark" onclick="goTo('contact')">Faire venir les échecs dans votre école →</button>
          </div>
        </div>
        <div style="background:linear-gradient(135deg,var(--bleu),var(--bleu-deep));border-radius:16px;padding:32px;box-shadow:var(--sh-lg);color:#fff">
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;color:var(--gold-pale);margin-bottom:2px">Bilan 2025–2026</div>
          <div style="font-size:12px;color:rgba(255,255,255,.65);margin-bottom:18px;letter-spacing:.04em">Saison terminée · 4 titres majeurs</div>
          <div style="display:flex;flex-direction:column;gap:10px">
            <div style="background:rgba(201,168,76,.18);border-radius:8px;padding:12px 16px"><div style="font-size:13px;font-weight:600;color:var(--gold-pale)"><svg class="ic" style="color:var(--gold-pale)" viewBox="0 0 24 24"><use href="#ic-trophy"/></svg> Ville de Cannes — 130 enfants · 5 catégories</div></div>
            <div style="background:rgba(201,168,76,.12);border-radius:8px;padding:12px 16px"><div style="font-size:13px;font-weight:600;color:var(--gold-pale)"><svg class="ic" style="color:var(--gold-pale)" viewBox="0 0 24 24"><use href="#ic-medal"/></svg> Sainte-Marie — Championne d'Académie · qualifiée nationale</div></div>
            <div style="background:rgba(255,255,255,.07);border-radius:8px;padding:12px 16px"><div style="font-size:13px;font-weight:600;color:#fff"><svg class="ic" style="color:var(--gold-pale)" viewBox="0 0 24 24"><use href="#ic-medal"/></svg> Stanislas — Champion Académique collèges</div></div>
            <div style="background:rgba(255,255,255,.07);border-radius:8px;padding:12px 16px"><div style="font-size:13px;font-weight:600;color:#fff"><svg class="ic" style="color:var(--gold-pale)" viewBox="0 0 24 24"><use href="#ic-medal"/></svg> Stanislas — Champion Départ. écoles & collèges</div></div>
          </div>
          <button class="btn btn-outline-white btn-sm btn-full" style="margin-top:18px" onclick="goTo('actualites')">Voir tous les résultats →</button>
        </div>
      </div>

      <!-- Les tournois scolaires (fusionné depuis l'ancien onglet Tournois › Scolaires) -->
      <div style="border-top:1px solid var(--border);margin-top:48px;padding-top:40px">
        <div class="section-header" style="margin-bottom:20px">
          <span class="surtitre">Compétitions</span>
          <h3 style="font-family:'Cormorant Garamond',serif;font-size:30px;color:var(--bleu)">Les tournois scolaires</h3>
        </div>
        <p style="font-size:15px;color:var(--text);line-height:1.8;max-width:680px;margin-bottom:24px">Cannes Échecs organise les tournois inter-établissements pour les élèves du primaire et du secondaire, du niveau départemental au niveau national.</p>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px">
          <div style="background:#fff;border-radius:10px;padding:16px 18px;border:1px solid var(--border);display:flex;gap:12px;align-items:center"><span style="font-size:20px;color:var(--gold)"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-school"/></svg></span><div><div style="font-weight:600;color:var(--bleu);font-size:14px">Tournoi inter-écoles</div><div style="font-size:12px;color:var(--muted)">Printemps · Primaires du bassin cannois</div></div></div>
          <div style="background:#fff;border-radius:10px;padding:16px 18px;border:1px solid var(--border);display:flex;gap:12px;align-items:center"><span style="font-size:20px;color:var(--gold)"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-cap"/></svg></span><div><div style="font-weight:600;color:var(--bleu);font-size:14px">Tournoi inter-collèges</div><div style="font-size:12px;color:var(--muted)">Décembre · Championnats départementaux</div></div></div>
          <div style="background:#fff;border-radius:10px;padding:16px 18px;border:1px solid var(--border);display:flex;gap:12px;align-items:center"><span style="font-size:20px;color:var(--gold)"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-trophy"/></svg></span><div><div style="font-weight:600;color:var(--bleu);font-size:14px">Sélection régionale &amp; nationale</div><div style="font-size:12px;color:var(--muted)">Février–avril · Championnats académiques et France</div></div></div>
        </div>
        <button class="btn btn-gold" onclick="goTo('contact')">Inscrire votre école →</button>
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
