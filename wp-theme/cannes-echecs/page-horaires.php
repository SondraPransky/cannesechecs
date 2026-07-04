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

<?php get_footer(); ?>
