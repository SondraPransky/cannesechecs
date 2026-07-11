<?php
/**
 * Template de la page « Adhésion » (slug WordPress : adhesion).
 * Généré depuis index.html par build-wp-theme.js — ne pas éditer le HTML ici
 * sans reporter la modification dans index.html tant que les deux coexistent.
 */
get_header(); ?>

<div id="page-adhesion" class="page active">
  <section class="hero-shared" style="background:linear-gradient(160deg,var(--bleu) 0%,var(--noir) 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>">Accueil</a><span>›</span><span>Adhésion</span></div>
      <div class="badge badge-event" style="margin-bottom:16px">Saison 2026–2027 ouverte</div>
      <h1>Rejoindre le <em>club</em></h1>
      <p class="hero-desc">Inscription en ligne sécurisée via HelloAsso. Confirmation immédiate.</p>
    </div>
  </section>

  <!-- Étapes -->
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

  <!-- Formules -->
  <section style="padding:80px 0;background:var(--ivoire)">
    <div class="container">
      <div class="section-header center" style="margin-bottom:40px">
        <span class="surtitre">Saison 2026–2027</span>
        <h2 style="font-size:38px;color:var(--bleu)">Choisissez votre formule</h2>
      </div>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;max-width:1100px;margin:0 auto">

        <!-- Pitchounets -->
        <div class="pcard">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-text);margin-bottom:4px">Pitchounets</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu)">4–6 ans</div></div>
            <div style="font-family:'Cormorant Garamond',serif;font-size:48px;font-weight:700;color:var(--bleu);line-height:.9;font-variant-numeric:tabular-nums">200€</div>
          </div>
          <ul style="list-style:none;margin-bottom:20px">
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ 1h atelier ludique / semaine</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Licence FFE incluse</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0">✓ Mercredi 13h30–14h30</li>
          </ul>
          <button class="btn btn-gold btn-full" onclick="haOpen(HELLOASSO.adhesion,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        </div>

        <!-- Compétition -->
        <div class="pcard">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-text);margin-bottom:4px">Compétition</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu)">Tous âges</div></div>
            <div style="font-family:'Cormorant Garamond',serif;font-size:48px;font-weight:700;color:var(--bleu);line-height:.9;font-variant-numeric:tabular-nums">120€</div>
          </div>
          <ul style="list-style:none;margin-bottom:20px">
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Adhésion sans cours</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Interclubs FFE inclus</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0">✓ Licence FFE incluse</li>
          </ul>
          <button class="btn btn-gold btn-full" onclick="haOpen(HELLOASSO.adhesion,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        </div>

        <!-- École Échecs Enfants -->
        <div class="pcard">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-text);margin-bottom:4px">École Échecs</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu)">Enfants</div></div>
            <div style="font-family:'Cormorant Garamond',serif;font-size:48px;font-weight:700;color:var(--bleu);line-height:.9;font-variant-numeric:tabular-nums">290€</div>
          </div>
          <ul style="list-style:none;margin-bottom:20px">
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ 1h cours collectif / semaine</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ 1h pratique encadrée</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0">✓ Licence FFE incluse</li>
          </ul>
          <button class="btn btn-gold btn-full" onclick="haOpen(HELLOASSO.adhesion,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        </div>

        <!-- Échecs Adultes -->
        <div class="pcard">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-text);margin-bottom:4px">Cours adultes</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu)">Échecs Adultes</div></div>
            <div style="font-family:'Cormorant Garamond',serif;font-size:48px;font-weight:700;color:var(--bleu);line-height:.9;font-variant-numeric:tabular-nums">290€</div>
          </div>
          <ul style="list-style:none;margin-bottom:20px">
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Cours collectif du mercredi</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Débutants 17h30 · Confirmés 18h30</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0">✓ Licence FFE incluse</li>
          </ul>
          <button class="btn btn-gold btn-full" onclick="haOpen(HELLOASSO.adhesion,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        </div>

        <!-- Famille -->
        <div class="pcard feature">
          <div style="position:absolute;top:-12px;left:24px"><div class="badge badge-gold">⭐ Famille</div></div>
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-text);margin-bottom:4px">École Famille</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu)">2 membres</div></div>
            <div style="font-family:'Cormorant Garamond',serif;font-size:48px;font-weight:700;color:var(--bleu);line-height:.9;font-variant-numeric:tabular-nums">500€</div>
          </div>
          <ul style="list-style:none;margin-bottom:20px">
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ 2 membres du même foyer</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Licences FFE incluses</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0">✓ 3 membres : <strong>750€</strong></li>
          </ul>
          <button class="btn btn-gold btn-full" onclick="haOpen(HELLOASSO.adhesion,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        </div>

        <!-- Licence A -->
        <div class="pcard">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
            <div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-text);margin-bottom:4px">Licence A</div><div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu)">Accès libre</div></div>
            <div style="font-family:'Cormorant Garamond',serif;font-size:48px;font-weight:700;color:var(--bleu);line-height:.9;font-variant-numeric:tabular-nums">60€</div>
          </div>
          <ul style="list-style:none;margin-bottom:20px">
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Adhésion sans cours ni interclubs</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0;border-bottom:1px solid var(--border)">✓ Accès libre au club</li>
            <li style="font-size:13px;color:var(--text);padding:5px 0">✓ Participation PICO gratuite</li>
          </ul>
          <button class="btn btn-gold btn-full" onclick="haOpen(HELLOASSO.adhesion,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        </div>

      </div>
      <!-- Cours particuliers -->
      <div style="max-width:1100px;margin:24px auto 0;background:var(--ivoire);border-radius:12px;padding:20px 28px;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border)">
        <div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-text);margin-bottom:4px">Cours particuliers</div><div style="font-size:14px;color:var(--muted)">Sur rendez-vous avec l'un de nos entraîneurs</div></div>
        <div style="display:flex;gap:32px;text-align:center">
          <div><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--bleu)">40€</div><div style="font-size:11px;color:var(--muted)">/ heure · adhérents</div></div>
          <div><div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:var(--bleu)">50€</div><div style="font-size:11px;color:var(--muted)">/ heure · non-adhérents</div></div>
        </div>
      </div>
    </div>
  </section>
</div><!-- fin page-adhesion -->

<?php get_footer(); ?>
