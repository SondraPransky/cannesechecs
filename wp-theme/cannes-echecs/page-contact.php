<?php
/**
 * Template de la page « Contact » (slug WordPress : contact).
 * Généré depuis index.html par build-wp-theme.js — ne pas éditer le HTML ici
 * sans reporter la modification dans index.html tant que les deux coexistent.
 */
get_header(); ?>

<div id="page-contact" class="page active">
  <section class="hero-shared" style="background:linear-gradient(160deg,var(--bleu) 0%,var(--noir) 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>">Accueil</a><span>›</span><span>Contact</span></div>
      <h1>Contactez-<em>nous</em></h1>
      <p class="hero-desc">Une question sur les cours, les tarifs, les inscriptions ? Nous répondons sous 48h.</p>
    </div>
  </section>

  <!-- Bande contacts rapides -->
  <div style="background:var(--gold);padding:22px 0">
    <div class="container" style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px">
      <div style="display:flex;align-items:center;gap:14px;justify-content:center">
        <div style="font-size:24px">📞</div>
        <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(0,0,0,.5);margin-bottom:2px">Téléphone</div><a href="tel:+33493394139" style="font-size:15px;font-weight:600;color:var(--noir);text-decoration:none">04 93 39 41 39</a></div>
      </div>
      <div style="display:flex;align-items:center;gap:14px;justify-content:center">
        <div style="font-size:24px">✉</div>
        <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(0,0,0,.5);margin-bottom:2px">Email</div><a href="mailto:info@cannes-echecs.fr" style="font-size:15px;font-weight:600;color:var(--noir);text-decoration:none">info@cannes-echecs.fr</a></div>
      </div>
      <div style="display:flex;align-items:center;gap:14px;justify-content:center">
        <div style="font-size:24px">📍</div>
        <div><div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(0,0,0,.5);margin-bottom:2px">En personne</div><div style="font-size:15px;font-weight:600;color:var(--noir)">3 Av. du Petit Juas</div></div>
      </div>
    </div>
  </div>

  <!-- Formulaire + infos -->
  <section style="padding:80px 0;background:#fff">
    <div class="container">
      <div style="display:grid;grid-template-columns:60% 40%;gap:48px;align-items:start">
        <div>
          <span class="surtitre">Formulaire de contact</span>
          <h2 style="font-size:36px;color:var(--bleu);margin-bottom:28px">Envoyez-nous<br>un message</h2>
          <form id="contact-form" onsubmit="sendContact(event)" novalidate>
          <input type="text" id="cf-honey" name="_honey" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px;height:0;width:0;opacity:0">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div><label for="cf-nom" style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);display:block;margin-bottom:6px">Prénom & Nom *</label><input id="cf-nom" type="text" name="nom" required placeholder="Votre nom" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:14px;background:var(--ivoire);outline:none;transition:border-color .2s,box-shadow .2s"></div>
            <div><label for="cf-email" style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);display:block;margin-bottom:6px">Email *</label><input id="cf-email" type="email" name="email" required placeholder="votre@email.fr" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:14px;background:var(--ivoire);outline:none;transition:border-color .2s,box-shadow .2s"></div>
          </div>
          <div style="margin-bottom:16px"><label for="cf-sujet" style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);display:block;margin-bottom:6px">Objet</label>
            <select id="cf-sujet" name="sujet" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:14px;background:var(--ivoire);outline:none;color:var(--noir);transition:border-color .2s,box-shadow .2s">
              <option value="cours">Renseignements sur les cours</option>
              <option value="tarifs">Tarifs et inscriptions</option>
              <option value="partenariat">Partenariat / sponsoring</option>
              <option value="scolaire">Intervention scolaire</option>
              <option value="stage">Stages vacances</option>
              <option value="presse">Presse et médias</option>
              <option value="autre">Autre</option>
            </select>
          </div>
          <div style="margin-bottom:16px"><label for="cf-message" style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);display:block;margin-bottom:6px">Message *</label><textarea id="cf-message" name="message" rows="5" required placeholder="Votre message..." style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:14px;background:var(--ivoire);outline:none;resize:vertical;transition:border-color .2s,box-shadow .2s"></textarea></div>
          <div style="margin-bottom:20px;display:flex;align-items:flex-start;gap:10px"><input id="cf-rgpd" type="checkbox" name="rgpd" required style="margin-top:3px;accent-color:var(--gold);width:16px;height:16px;flex-shrink:0"><label for="cf-rgpd" style="font-size:13px;color:var(--muted)">J'accepte que mes données soient utilisées pour traiter ma demande.</label></div>
          <div id="cf-feedback" role="status" aria-live="polite" style="display:none;padding:14px 18px;border-radius:8px;margin-bottom:16px;font-size:14px;font-weight:600"></div>
          <button type="submit" class="btn btn-gold btn-full" style="font-size:13px;padding:16px">Envoyer mon message →</button>
          </form>
        </div>
        <div>
          <div style="background:var(--ivoire);border-radius:12px;padding:24px;margin-bottom:14px;border:1px solid rgba(201,168,76,.3)">
            <h4 style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--bleu);margin-bottom:14px">Nous trouver</h4>
            <div style="font-size:14px;color:var(--text);line-height:1.7;margin-bottom:12px">3 Av. du Petit Juas<br>06400 Cannes</div>
            <a href="https://www.google.com/maps/search/3+Avenue+du+Petit+Juas+06400+Cannes" target="_blank" rel="noopener" style="font-size:12px;color:var(--gold-text);cursor:pointer;font-family:'Montserrat',sans-serif;font-weight:600;letter-spacing:.06em;text-transform:uppercase">Itinéraire Google Maps →</a>
          </div>
          <div style="background:var(--ivoire);border-radius:12px;padding:24px;margin-bottom:14px;border:1px solid rgba(201,168,76,.3)">
            <h4 style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--bleu);margin-bottom:14px">Quand nous rendre visite ?</h4>
            <div style="font-size:14px;color:var(--text);line-height:1.8">Lun · Jeu · Ven : 13h30–18h30<br>Mardi : cours jeunes 17h–19h<br>Mercredi : 13h30–20h · cours dès 13h30</div>
          </div>
          <div style="background:var(--bleu);border-radius:12px;padding:24px;color:#fff">
            <h4 style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--gold);margin-bottom:14px">Contacts spécialisés</h4>
            <div style="font-size:13px;color:rgba(255,255,255,.7);margin-bottom:8px">📚 Formation : <a style="color:var(--gold)">formation@cannes-echecs.fr</a></div>
            <div style="font-size:13px;color:rgba(255,255,255,.7);margin-bottom:8px">🏆 Compétitions : <a style="color:var(--gold)">competition@cannes-echecs.fr</a></div>
            <div style="font-size:13px;color:rgba(255,255,255,.7)">🏫 Scolaire : <a style="color:var(--gold)">scolaire@cannes-echecs.fr</a></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Carte Google Maps -->
  <div style="line-height:0">
    <iframe src="https://maps.google.com/maps?q=3%20Avenue%20du%20Petit%20Juas%2006400%20Cannes&z=15&output=embed" width="100%" height="320" style="border:0;display:block" loading="lazy" title="Cannes Échecs — 3 Avenue du Petit Juas, 06400 Cannes"></iframe>
  </div>
</div><!-- fin page-contact -->

<?php get_footer(); ?>
