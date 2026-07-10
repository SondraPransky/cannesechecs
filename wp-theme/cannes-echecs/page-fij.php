<?php
/**
 * Template de la page « FIJ » (slug WordPress : fij).
 * Généré depuis index.html par build-wp-theme.js — ne pas éditer le HTML ici
 * sans reporter la modification dans index.html tant que les deux coexistent.
 */
get_header(); ?>

<div id="page-fij" class="page active">
  <section style="background:linear-gradient(160deg,var(--noir) 0%,var(--bleu-deep) 50%,var(--bleu) 100%);min-height:500px;display:flex;align-items:center;padding:80px 0;position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;background-image:repeating-conic-gradient(rgba(201,168,76,.05) 0% 25%,transparent 0% 50%);background-size:56px 56px"></div>
    <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,transparent,var(--gold),transparent)"></div>
    <div class="container" style="position:relative;z-index:1">
      <div class="breadcrumb" style="color:rgba(255,255,255,.35);display:flex;gap:8px;font-size:12px;margin-bottom:20px"><a href="<?php echo esc_url(home_url('/')); ?>" style="color:rgba(255,255,255,.35)">Accueil</a><span>›</span><span>FIJ 2027</span></div>
      <h1 style="font-family:'Cormorant Garamond',serif;line-height:1"><span style="font-size:68px;color:#fff;display:block;margin-bottom:4px">Festival International </span><span style="font-size:68px;color:var(--gold);font-style:italic;display:block;margin-bottom:20px">des Jeux</span></h1>
      <p style="font-family:'Montserrat',sans-serif;font-size:15px;color:rgba(255,255,255,.55);letter-spacing:.06em">Palais des Festivals — Salon des Ambassadeurs · Du 22 au 28 février 2027</p>
      <div style="display:flex;gap:12px;margin-top:32px;flex-wrap:wrap">
        <button class="btn btn-gold btn-lg" onclick="haOpen(HELLOASSO.fij,'_blank','noopener,noreferrer')">S'inscrire sur HelloAsso →</button>
        <button class="btn btn-outline-white btn-lg" onclick="document.getElementById('fij-details').scrollIntoView({behavior:'smooth'})">Programme complet ↓</button>
      </div>
    </div>
  </section>

  <!-- Stats FIJ -->
  <div id="fij-details" style="background:var(--gold);padding:22px 0">
    <div class="container" style="display:grid;grid-template-columns:repeat(4,1fr);text-align:center">
      <div><div style="font-family:'Cormorant Garamond',serif;font-size:44px;font-weight:700;color:var(--noir);line-height:1">100 000+</div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:rgba(0,0,0,.55)">Visiteurs du festival</div></div>
      <div><div style="font-family:'Cormorant Garamond',serif;font-size:44px;font-weight:700;color:var(--noir);line-height:1">A · B · C</div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:rgba(0,0,0,.55)">Tournois par Elo</div></div>
      <div><div style="font-family:'Cormorant Garamond',serif;font-size:44px;font-weight:700;color:var(--noir);line-height:1">9</div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:rgba(0,0,0,.55)">Rondes</div></div>
      <div><div style="font-family:'Cormorant Garamond',serif;font-size:44px;font-weight:700;color:var(--noir);line-height:1">FIDE</div><div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:rgba(0,0,0,.55)">Homologué · Normes MI/GMI</div></div>
    </div>
  </div>

  <!-- Présentation + Inscription -->
  <section style="padding:80px 0;background:#fff">
    <div class="container">
      <div style="display:grid;grid-template-columns:55% 45%;gap:48px;align-items:start">
        <div>
          <span class="surtitre">À propos du FIJ</span>
          <h2 style="font-size:38px;color:var(--bleu);margin-bottom:16px">Le plus grand festival de jeux du monde — avec les échecs en vedette</h2>
          <div class="gold-bar"></div>
          <p style="font-size:15px;color:var(--text);line-height:1.8;margin-bottom:16px">Le <strong>Festival International des Jeux</strong> se tient chaque année en février au Palais des Festivals de Cannes. Avec plus de 100 000 visiteurs et 60 000 m² d'exposition, c'est l'un des plus grands rendez-vous de jeux au monde. Cannes Échecs y organise les tournois d'échecs officiels depuis de nombreuses années.</p>
          <p style="font-size:15px;color:var(--text);line-height:1.8;margin-bottom:28px">Les tournois sont homologués <strong>FIDE</strong>, avec des normes de Maître International (MI) et de Grand-Maître International (GMI) possibles dans le tournoi A. La cadence est de 1h30 pour 40 coups, puis 30 minutes avec 30 secondes par coup dès le 1er coup.</p>
          <div style="background:var(--gold-pale);border:1px solid rgba(201,168,76,.3);border-radius:8px;padding:16px 20px;font-style:italic;color:var(--bleu);font-size:16px;line-height:1.6;margin-bottom:28px">"Le tournoi du FIJ, c'est jouer aux échecs dans l'une des plus belles villes de France, au cœur d'un festival qui fait vibrer 100 000 passionnés de jeux."</div>
          <!-- Catégories + Dotation fusionnées -->
          <h3 style="font-family:'Cormorant Garamond',serif;font-size:26px;color:var(--bleu);margin-bottom:20px">Tournois — par classement Elo</h3>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px">

            <!-- Open A -->
            <div style="border-radius:14px;overflow:hidden;box-shadow:0 6px 24px rgba(0,0,0,.10)">
              <div style="background:linear-gradient(150deg,#1a0c00,#7a5212);padding:26px 18px 22px;text-align:center;position:relative;overflow:hidden">
                <div style="position:absolute;font-family:'Cormorant Garamond',serif;font-size:120px;font-weight:700;color:#fff;opacity:.06;line-height:1;top:50%;left:50%;transform:translate(-50%,-44%)">A</div>
                <div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.5);margin-bottom:4px">Open</div>
                <div style="font-family:'Cormorant Garamond',serif;font-size:58px;font-weight:700;color:#fff;line-height:1">A</div>
                <div style="display:inline-block;margin-top:10px;background:rgba(201,168,76,.25);border:1px solid rgba(201,168,76,.5);border-radius:20px;padding:3px 12px;font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;color:var(--gold-pale);letter-spacing:.04em">2 200 Elo et +</div>
              </div>
              <div style="background:var(--ivoire);padding:18px">
                <div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:8px">Inscription</div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px"><span style="color:var(--muted)">Senior</span><span style="font-weight:700;color:var(--bleu)">75 €</span></div>
                <div style="display:flex;justify-content:space-between;font-size:13px"><span style="color:var(--muted)">Junior</span><span style="font-weight:700;color:var(--bleu)">45 €</span></div>
                <div style="border-top:1px solid var(--border);margin:14px 0 10px"></div>
                <div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:8px">Dotation — 6 000 €</div>
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px"><span style="color:var(--muted)">🥇 1er</span><span style="font-weight:600;color:var(--bleu)">1 800 €</span></div>
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px"><span style="color:var(--muted)">🥈 2ème</span><span style="font-weight:600;color:var(--bleu)">1 200 €</span></div>
                <div style="display:flex;justify-content:space-between;font-size:12px"><span style="color:var(--muted)">🥉 3ème</span><span style="font-weight:600;color:var(--bleu)">700 €</span></div>
                <div style="border-top:1px solid var(--border);margin:12px 0 10px"></div>
                <div style="font-size:11px;color:#8B6914;font-weight:600">✦ Normes MI / GMI possibles</div>
              </div>
            </div>

            <!-- Open B -->
            <div style="border-radius:14px;overflow:hidden;box-shadow:0 6px 24px rgba(0,0,0,.10)">
              <div style="background:linear-gradient(150deg,#060f22,#1a2a5c);padding:26px 18px 22px;text-align:center;position:relative;overflow:hidden">
                <div style="position:absolute;font-family:'Cormorant Garamond',serif;font-size:120px;font-weight:700;color:#fff;opacity:.06;line-height:1;top:50%;left:50%;transform:translate(-50%,-44%)">B</div>
                <div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.5);margin-bottom:4px">Open</div>
                <div style="font-family:'Cormorant Garamond',serif;font-size:58px;font-weight:700;color:#fff;line-height:1">B</div>
                <div style="display:inline-block;margin-top:10px;background:var(--gold-tint);border:1px solid var(--gold-tint-border);border-radius:20px;padding:3px 12px;font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;color:var(--gold-pale);letter-spacing:.04em">1 600 – 2 200 Elo</div>
              </div>
              <div style="background:var(--ivoire);padding:18px">
                <div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:8px">Inscription</div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px"><span style="color:var(--muted)">Senior</span><span style="font-weight:700;color:var(--bleu)">75 €</span></div>
                <div style="display:flex;justify-content:space-between;font-size:13px"><span style="color:var(--muted)">Junior</span><span style="font-weight:700;color:var(--bleu)">45 €</span></div>
                <div style="border-top:1px solid var(--border);margin:14px 0 10px"></div>
                <div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:8px">Dotation — 3 000 €</div>
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px"><span style="color:var(--muted)">🥇 1er</span><span style="font-weight:600;color:var(--bleu)">600 €</span></div>
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px"><span style="color:var(--muted)">🥈 2ème</span><span style="font-weight:600;color:var(--bleu)">500 €</span></div>
                <div style="display:flex;justify-content:space-between;font-size:12px"><span style="color:var(--muted)">🥉 3ème</span><span style="font-weight:600;color:var(--bleu)">400 €</span></div>
              </div>
            </div>

            <!-- Open C -->
            <div style="border-radius:14px;overflow:hidden;box-shadow:0 6px 24px rgba(0,0,0,.10)">
              <div style="background:linear-gradient(150deg,#0f0f1e,#2e3350);padding:26px 18px 22px;text-align:center;position:relative;overflow:hidden">
                <div style="position:absolute;font-family:'Cormorant Garamond',serif;font-size:120px;font-weight:700;color:#fff;opacity:.06;line-height:1;top:50%;left:50%;transform:translate(-50%,-44%)">C</div>
                <div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.5);margin-bottom:4px">Open</div>
                <div style="font-family:'Cormorant Garamond',serif;font-size:58px;font-weight:700;color:#fff;line-height:1">C</div>
                <div style="display:inline-block;margin-top:10px;background:rgba(201,168,76,.15);border:1px solid rgba(201,168,76,.3);border-radius:20px;padding:3px 12px;font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;color:var(--gold-pale);letter-spacing:.04em">≤ 1 600 Elo</div>
              </div>
              <div style="background:var(--ivoire);padding:18px">
                <div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:8px">Inscription</div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px"><span style="color:var(--muted)">Senior</span><span style="font-weight:700;color:var(--bleu)">75 €</span></div>
                <div style="display:flex;justify-content:space-between;font-size:13px"><span style="color:var(--muted)">Junior</span><span style="font-weight:700;color:var(--bleu)">45 €</span></div>
                <div style="border-top:1px solid var(--border);margin:14px 0 10px"></div>
                <div style="font-family:'Montserrat',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:8px">Dotation — 1 000 €</div>
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px"><span style="color:var(--muted)">🥇 1er</span><span style="font-weight:600;color:var(--bleu)">300 €</span></div>
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px"><span style="color:var(--muted)">🥈 2ème</span><span style="font-weight:600;color:var(--bleu)">200 €</span></div>
                <div style="display:flex;justify-content:space-between;font-size:12px"><span style="color:var(--muted)">🥉 3ème</span><span style="font-weight:600;color:var(--bleu)">100 €</span></div>
                <div style="border-top:1px solid var(--border);margin:12px 0 10px"></div>
                <div style="font-size:11px;color:var(--muted)">Idéal pour progresser en compétition</div>
              </div>
            </div>

          </div>
          <!-- Inscrits FIJ 2027 — masquable depuis le panneau admin (?admin) -->
          <div id="fij-inscrits-section"></div>
        </div>
        <!-- Colonne latérale — 3 états automatiques selon la date -->
        <div id="fij-sidebar" style="background:linear-gradient(160deg,var(--noir) 0%,var(--bleu) 100%);border-radius:16px;padding:32px;position:sticky;top:80px">

          <!-- ÉTAT 1 : avant le FIJ -->
          <div id="fij-sidebar-avant">
            <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--gold-pale);margin-bottom:16px;text-align:center">FIJ 2027 — Coup d'envoi dans</div>
            <div style="display:flex;justify-content:center;gap:8px;margin-bottom:20px">
              <div style="text-align:center"><span id="fij-cd-j" style="font-family:'Cormorant Garamond',serif;font-size:56px;font-weight:700;color:var(--gold);line-height:1;display:block">--</span><span style="font-family:'Montserrat',sans-serif;font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.35)">Jours</span></div>
              <div style="font-size:44px;color:rgba(201,168,76,.3);padding:0 4px">:</div>
              <div style="text-align:center"><span id="fij-cd-h" style="font-family:'Cormorant Garamond',serif;font-size:56px;font-weight:700;color:var(--gold);line-height:1;display:block">--</span><span style="font-family:'Montserrat',sans-serif;font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.35)">Heures</span></div>
              <div style="font-size:44px;color:rgba(201,168,76,.3);padding:0 4px">:</div>
              <div style="text-align:center"><span id="fij-cd-m" style="font-family:'Cormorant Garamond',serif;font-size:56px;font-weight:700;color:var(--gold);line-height:1;display:block">--</span><span style="font-family:'Montserrat',sans-serif;font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.35)">Min</span></div>
              <div style="font-size:44px;color:rgba(201,168,76,.3);padding:0 4px">:</div>
              <div style="text-align:center"><span id="fij-cd-s" style="font-family:'Cormorant Garamond',serif;font-size:56px;font-weight:700;color:var(--gold);line-height:1;display:block">--</span><span style="font-family:'Montserrat',sans-serif;font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.35)">Sec</span></div>
            </div>
            <button class="btn btn-gold btn-full" style="margin-bottom:16px;font-size:13px;padding:16px" onclick="haOpen(HELLOASSO.fij,'_blank','noopener,noreferrer')">S'inscrire sur HelloAsso →</button>
            <div style="background:rgba(201,168,76,.1);border:1px solid rgba(201,168,76,.25);border-radius:10px;padding:18px;margin-bottom:12px">
              <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-pale);margin-bottom:10px;text-align:center">Tarifs d'inscription</div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:12px;color:rgba(255,255,255,.7)">
                <div style="background:rgba(255,255,255,.06);border-radius:6px;padding:10px;text-align:center"><div style="font-weight:700;color:#fff;margin-bottom:2px">Avant le 31/12/2026</div><div>Junior : 45 €</div><div>Senior : 75 €</div></div>
                <div style="background:rgba(255,255,255,.06);border-radius:6px;padding:10px;text-align:center"><div style="font-weight:700;color:#fff;margin-bottom:2px">Standard</div><div>Junior : 55 €</div><div>Senior : 85 €</div></div>
              </div>
              <div style="font-size:11px;color:rgba(255,255,255,.4);text-align:center;margin-top:8px">Sur place : Junior 60 € · Senior 95 €</div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
              <div style="background:rgba(255,255,255,.06);border-radius:8px;padding:12px;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:24px;color:var(--gold);font-weight:700">100 000+</div><div style="font-size:10px;color:rgba(255,255,255,.4)">visiteurs du festival</div></div>
              <div style="background:rgba(255,255,255,.06);border-radius:8px;padding:12px;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-size:24px;color:var(--gold);font-weight:700">40e éd.</div><div style="font-size:10px;color:rgba(255,255,255,.4)">FIJ 2027</div></div>
            </div>
            <p style="font-size:11px;color:rgba(255,255,255,.25);text-align:center;margin-top:12px;font-style:italic">MI / GMI : participation gratuite</p>
          </div>

          <!-- ÉTAT 2 : FIJ en cours -->
          <div id="fij-sidebar-encours" style="display:none">
            <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--gold-pale);margin-bottom:20px;text-align:center">♟ FIJ 2027 — En cours</div>
            <div id="fij-sb-inscrits" style="margin-bottom:16px"></div>
            <div style="background:rgba(201,168,76,.1);border:1px solid rgba(201,168,76,.25);border-radius:10px;padding:16px 18px">
              <div id="fij-sb-ronde-header" style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-pale);margin-bottom:10px;text-align:center">Prochaine ronde</div>
              <div id="fij-sb-ronde-label" style="font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:700;color:#fff;text-align:center;margin-bottom:12px;line-height:1.3"></div>
              <div id="fij-sb-countdown" style="display:flex;justify-content:center;align-items:flex-start;gap:6px">
                <div style="text-align:center"><span id="fij-sb-h" style="font-family:'Cormorant Garamond',serif;font-size:40px;font-weight:700;color:var(--gold);line-height:1;display:block">--</span><span style="font-family:'Montserrat',sans-serif;font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.35)">H</span></div>
                <div style="font-size:32px;color:rgba(201,168,76,.3);padding:4px 3px 0">:</div>
                <div style="text-align:center"><span id="fij-sb-m" style="font-family:'Cormorant Garamond',serif;font-size:40px;font-weight:700;color:var(--gold);line-height:1;display:block">--</span><span style="font-family:'Montserrat',sans-serif;font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.35)">Min</span></div>
                <div style="font-size:32px;color:rgba(201,168,76,.3);padding:4px 3px 0">:</div>
                <div style="text-align:center"><span id="fij-sb-s" style="font-family:'Cormorant Garamond',serif;font-size:40px;font-weight:700;color:var(--gold);line-height:1;display:block">--</span><span style="font-family:'Montserrat',sans-serif;font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.35)">Sec</span></div>
              </div>
            </div>
          </div>

          <!-- ÉTAT 3 : FIJ terminé -->
          <div id="fij-sidebar-fini" style="display:none">
            <div style="text-align:center;padding:28px 0 20px">
              <div style="font-size:52px;margin-bottom:14px">🏆</div>
              <div style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;color:#fff;margin-bottom:10px">FIJ 2027 terminé !</div>
              <div style="font-size:13px;color:rgba(255,255,255,.5);line-height:1.8">Merci à tous les participants.<br>Rendez-vous pour le FIJ 2028 !</div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- Programme -->
  <section style="padding:80px 0;background:var(--ivoire)">
    <div class="container">
      <div class="section-header center" style="margin-bottom:40px">
        <span class="surtitre">Calendrier</span>
        <h2 style="font-size:38px;color:var(--bleu)">Programme</h2>
      </div>
      <div style="max-width:640px;margin:0 auto;position:relative;padding-left:40px">
        <div style="position:absolute;left:10px;top:0;bottom:0;width:2px;background:linear-gradient(to bottom,var(--gold),rgba(201,168,76,.1))"></div>
        <div style="position:relative;margin-bottom:28px;padding-bottom:28px;border-bottom:1px solid var(--border)">
          <div style="position:absolute;left:-35px;top:4px;width:12px;height:12px;border-radius:50%;background:var(--gold);border:2px solid #fff;box-shadow:0 0 0 3px rgba(201,168,76,.25)"></div>
          <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-text);margin-bottom:4px">Lundi 22 février</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu);margin-bottom:6px">Pointage & Ronde 1</div>
          <div style="font-size:14px;color:var(--muted)">Pointage obligatoire de 12h00 à 14h30 · Ronde 1 à 16h30</div>
        </div>
        <div style="position:relative;margin-bottom:28px;padding-bottom:28px;border-bottom:1px solid var(--border)">
          <div style="position:absolute;left:-35px;top:4px;width:12px;height:12px;border-radius:50%;background:var(--gold);border:2px solid #fff;box-shadow:0 0 0 3px rgba(201,168,76,.25)"></div>
          <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-text);margin-bottom:4px">Mardi 23 février</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu);margin-bottom:6px">Rondes 2 & 3</div>
          <div style="font-size:14px;color:var(--muted)">Ronde 2 à 9h00 · Ronde 3 à 16h00</div>
        </div>
        <div style="position:relative;margin-bottom:28px;padding-bottom:28px;border-bottom:1px solid var(--border)">
          <div style="position:absolute;left:-35px;top:4px;width:12px;height:12px;border-radius:50%;background:var(--gold);border:2px solid #fff;box-shadow:0 0 0 3px rgba(201,168,76,.25)"></div>
          <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-text);margin-bottom:4px">Mercredi 24 février</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu);margin-bottom:6px">Ronde 4</div>
          <div style="font-size:14px;color:var(--muted)">Ronde 4 à 15h00</div>
        </div>
        <div style="position:relative;margin-bottom:28px;padding-bottom:28px;border-bottom:1px solid var(--border)">
          <div style="position:absolute;left:-35px;top:4px;width:12px;height:12px;border-radius:50%;background:var(--gold);border:2px solid #fff;box-shadow:0 0 0 3px rgba(201,168,76,.25)"></div>
          <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-text);margin-bottom:4px">Jeudi 25 février</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu);margin-bottom:6px">Rondes 5 & 6</div>
          <div style="font-size:14px;color:var(--muted)">Ronde 5 à 9h00 · Ronde 6 à 16h00</div>
        </div>
        <div style="position:relative;margin-bottom:28px;padding-bottom:28px;border-bottom:1px solid var(--border)">
          <div style="position:absolute;left:-35px;top:4px;width:12px;height:12px;border-radius:50%;background:var(--gold);border:2px solid #fff;box-shadow:0 0 0 3px rgba(201,168,76,.25)"></div>
          <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-text);margin-bottom:4px">Vendredi 26 février</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu);margin-bottom:6px">Ronde 7</div>
          <div style="font-size:14px;color:var(--muted)">Ronde 7 à 15h00</div>
        </div>
        <div style="position:relative;margin-bottom:28px;padding-bottom:28px;border-bottom:1px solid var(--border)">
          <div style="position:absolute;left:-35px;top:4px;width:12px;height:12px;border-radius:50%;background:var(--gold);border:2px solid #fff;box-shadow:0 0 0 3px rgba(201,168,76,.25)"></div>
          <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-text);margin-bottom:4px">Samedi 27 février</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu);margin-bottom:6px">Ronde 8</div>
          <div style="font-size:14px;color:var(--muted)">Ronde 8 à 15h00</div>
        </div>
        <div style="position:relative">
          <div style="position:absolute;left:-35px;top:4px;width:12px;height:12px;border-radius:50%;background:var(--gold);border:2px solid #fff;box-shadow:0 0 0 3px rgba(201,168,76,.25)"></div>
          <div style="font-family:'Montserrat',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-text);margin-bottom:4px">Dimanche 28 février (clôture)</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;color:var(--bleu);margin-bottom:6px">Ronde 9 + Remise des prix</div>
          <div style="font-size:14px;color:var(--muted)">Ronde 9 à 10h00 · Cérémonie de remise des prix & trophées à 16h30</div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA FIJ finale -->
  <section style="background:linear-gradient(135deg,var(--noir) 0%,var(--bleu) 100%);padding:80px 0;text-align:center;position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;font-size:300px;display:flex;align-items:center;justify-content:center;color:var(--gold);opacity:.03;pointer-events:none;font-family:'Inter',sans-serif">♟</div>
    <div class="container" style="position:relative;z-index:1;max-width:620px">
      <div class="badge badge-event" style="margin-bottom:20px">Inscriptions ouvertes</div>
      <h2 style="font-family:'Cormorant Garamond',serif;font-size:46px;color:#fff;margin-bottom:12px">Réservez votre place<br>au <em style="color:var(--gold)">FIJ 2027</em></h2>
      <p style="font-size:15px;color:rgba(255,255,255,.6);margin-bottom:32px">Places limitées · Inscription sécurisée via HelloAsso · Confirmation immédiate</p>
      <button class="btn btn-gold btn-lg" style="font-size:15px;padding:20px 48px;box-shadow:0 4px 24px var(--gold-tint-border)" onclick="haOpen(HELLOASSO.fij,'_blank','noopener,noreferrer')">S'inscrire maintenant →</button>
      <p style="font-size:13px;color:rgba(255,255,255,.4);margin-top:20px">Questions ? <a href="mailto:info@cannes-echecs.fr" style="color:var(--gold-pale);text-decoration:none">info@cannes-echecs.fr</a></p>
    </div>
  </section>
</div><!-- fin page-fij -->

<?php get_footer(); ?>
