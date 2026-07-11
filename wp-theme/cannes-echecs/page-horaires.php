<?php
/**
 * Template de la page « Horaires & Tarifs » (slug WordPress : horaires).
 * Généré depuis index.html par build-wp-theme.js — ne pas éditer le HTML ici
 * sans reporter la modification dans index.html tant que les deux coexistent.
 */
get_header(); ?>

<div id="page-horaires" class="page active">
  <section class="hero-shared" style="background:linear-gradient(160deg,var(--bleu) 0%,var(--noir) 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>">Accueil</a><span>›</span><span>Horaires & Tarifs</span></div>
      <h1>Horaires & <em>Tarifs</em></h1>
      <p style="font-size:14px;color:rgba(255,255,255,.4);margin-top:8px;font-style:italic">Saison 2026–2027 · Mis à jour en septembre 2026</p>
    </div>
  </section>

  <!-- Horaires + infos -->
  <section style="padding:80px 0;background:#fff">
    <div class="container">
      <div style="display:grid;grid-template-columns:60% 40%;gap:48px;align-items:start">
        <div>
          <span class="surtitre">Planning hebdomadaire</span>
          <h2 style="font-size:36px;color:var(--bleu);margin-bottom:24px">Horaires d'ouverture</h2>
          <table style="width:100%;border-collapse:collapse;font-size:14px;font-family:'Inter',sans-serif">
            <thead><tr style="background:var(--bleu);color:#fff"><th scope="col" style="padding:12px 16px;text-align:left;font-family:'Montserrat',sans-serif;font-size:11px;letter-spacing:.08em;text-transform:uppercase">Jour</th><th scope="col" style="padding:12px 16px;text-align:left;font-family:'Montserrat',sans-serif;font-size:11px;letter-spacing:.08em;text-transform:uppercase">Horaires</th><th scope="col" style="padding:12px 16px;text-align:left;font-family:'Montserrat',sans-serif;font-size:11px;letter-spacing:.08em;text-transform:uppercase">Activité</th></tr></thead>
            <tbody id="horaires-tbody"></tbody>
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
            <a href="tel:+33493394139" style="font-size:15px;font-weight:500;color:inherit;text-decoration:none">04 93 39 41 39</a>
          </div>
          <div>
            <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:6px">✉ Email</div>
            <a href="mailto:info@cannes-echecs.fr" style="font-size:14px;color:inherit;text-decoration:none">info@cannes-echecs.fr</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Carte Google Maps -->
  <div style="line-height:0">
    <iframe src="https://maps.google.com/maps?q=3%20Avenue%20du%20Petit%20Juas%2006400%20Cannes&z=15&output=embed" width="100%" height="320" style="border:0;display:block" loading="lazy" title="Cannes Échecs — 3 Avenue du Petit Juas, 06400 Cannes"></iframe>
  </div>

  <!-- Tarifs -->
  <section style="padding:80px 0;background:var(--ivoire)">
    <div class="container">
      <div class="section-header center" style="margin-bottom:36px">
        <span class="surtitre">Saison 2026–2027</span>
        <h2 style="font-size:42px;color:var(--bleu)">Tarifs en un coup d'œil</h2>
        <p style="font-size:15px;color:var(--muted);margin-top:12px;max-width:560px;margin-left:auto;margin-right:auto">Licence FFE et accès illimité au club inclus dans chaque formule.</p>
      </div>
      <!-- Aperçu tarifs — 3 prix repères, la page Adhésion reste la source unique -->
      <div style="max-width:960px;margin:0 auto;background:var(--bleu);border-radius:16px;padding:28px 32px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:24px">
        <div style="max-width:240px">
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:#fff;margin-bottom:4px">6 formules d'adhésion</div>
          <div style="font-size:13px;color:rgba(255,255,255,.6);line-height:1.5">De l'accès libre à la formule Famille — un tarif pour chaque profil.</div>
        </div>
        <div style="display:flex;gap:28px;flex-wrap:wrap">
          <div style="text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:34px;font-weight:700;color:var(--gold);line-height:1;font-variant-numeric:tabular-nums">60€</div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-top:5px">Accès libre</div></div>
          <div style="text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:34px;font-weight:700;color:var(--gold);line-height:1;font-variant-numeric:tabular-nums">200€</div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-top:5px">Pitchounets</div></div>
          <div style="text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:34px;font-weight:700;color:var(--gold);line-height:1;font-variant-numeric:tabular-nums">290€</div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-top:5px">École Échecs</div></div>
        </div>
        <button class="btn btn-gold" style="flex-shrink:0" onclick="goTo('adhesion')">Voir les 6 formules →</button>
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

<?php get_footer(); ?>
