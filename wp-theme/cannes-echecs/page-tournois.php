<?php
/**
 * Template de la page « Tournois » (slug WordPress : tournois).
 * Généré depuis index.html par build-wp-theme.js — ne pas éditer le HTML ici
 * sans reporter la modification dans index.html tant que les deux coexistent.
 */
get_header(); ?>

<div id="page-tournois" class="page active">
  <section class="hero-shared" style="background:linear-gradient(135deg,var(--bleu) 0%,var(--noir) 100%);min-height:270px">
    <div class="container">
      <div class="breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>">Accueil</a><span>›</span><span>Tournois</span></div>
      <h1>Nos <em>tournois</em></h1>
      <p class="hero-desc">Compétitions régulières, accessibles à tous les niveaux.</p>
    </div>
  </section>

  <!-- Onglets tournois -->
  <div class="tab-bar">
    <div class="tab-bar-inner">
      <button class="tab-btn tab-active" data-tab="t-pico">🎯 PICO</button>
      <button class="tab-btn" data-tab="t-rapide">⚡ Rapide</button>
      <button class="tab-btn" data-tab="t-scolaire">🏫 Scolaires</button>
      <button class="tab-btn" data-tab="t-paques">🐣 Open Pâques</button>
    </div>
  </div>

  <!-- Tab PICO -->
  <div class="tab-panel tab-panel-active" id="tab-t-pico" style="padding:60px 0;background:var(--ivoire)">
    <div class="container">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start">
        <div>
          <div class="badge badge-gold" style="margin-bottom:14px">Mensuel · Membres</div>
          <h2 style="font-size:34px;color:var(--bleu);margin-bottom:12px">Tournois PICO</h2>
          <p style="font-size:15px;color:var(--text);line-height:1.8;margin-bottom:20px">Le rendez-vous compétitif mensuel du club. 5 rondes rapides dans une ambiance conviviale, ouvert à tous les membres, toutes catégories.</p>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px">
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Format</div><div style="font-size:14px;color:var(--bleu);font-weight:600">Parties rapides 15+10</div></div>
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Rondes</div><div style="font-size:14px;color:var(--bleu);font-weight:600">5 rondes · après-midi</div></div>
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Accès</div><div style="font-size:14px;color:var(--bleu);font-weight:600">Membres uniquement</div></div>
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Tarif</div><div style="font-size:14px;color:var(--bleu);font-weight:600">Gratuit · HelloAsso</div></div>
          </div>
          <div style="background:var(--gold-pale);border:1px solid rgba(201,168,76,.3);border-radius:8px;padding:14px 18px">
            <div style="font-size:11px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.1em;margin-bottom:5px">📅 Prochaine édition</div>
            <div style="font-size:16px;font-weight:600;color:var(--bleu)">Samedi 19 septembre 2026 · 13h30</div>
            <div style="font-size:13px;color:var(--muted);margin-top:6px;display:flex;align-items:center;gap:10px"><span>Inscription en ligne</span><button class="btn btn-gold btn-sm" style="font-size:10px;padding:5px 12px" onclick="haOpen(HELLOASSO.pico.sep,'_blank','noopener,noreferrer')">S'inscrire →</button></div>
          </div>
        </div>
        <div style="background:var(--bleu);border-radius:14px;padding:24px">
          <div style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--gold);margin-bottom:16px">Calendrier PICO</div>
          <div style="display:flex;flex-direction:column;gap:0">
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">19</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">SEP</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85);flex:1">PICO Septembre · <strong style="color:var(--gold)">13h30</strong></div><button class="btn btn-gold btn-sm" style="font-size:10px;padding:5px 10px" onclick="haOpen(HELLOASSO.pico.sep,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">10</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">OCT</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85);flex:1">PICO Octobre · <strong style="color:var(--gold)">13h30</strong></div><button class="btn btn-gold btn-sm" style="font-size:10px;padding:5px 10px" onclick="haOpen(HELLOASSO.pico.oct,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">14</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">NOV</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85);flex:1">PICO Novembre · <strong style="color:var(--gold)">13h30</strong></div><button class="btn btn-gold btn-sm" style="font-size:10px;padding:5px 10px" onclick="haOpen(HELLOASSO.pico.nov,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">12</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">DÉC</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85);flex:1">PICO Décembre · <strong style="color:var(--gold)">13h30</strong></div><button class="btn btn-gold btn-sm" style="font-size:10px;padding:5px 10px" onclick="haOpen(HELLOASSO.pico.dec,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">09</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">JAN</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85);flex:1">PICO Janvier · <strong style="color:var(--gold)">13h30</strong></div><button class="btn btn-gold btn-sm" style="font-size:10px;padding:5px 10px" onclick="haOpen(HELLOASSO.pico.jan,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1);opacity:.5;font-style:italic">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:10px;line-height:1.2">FÉV.</span><span style="font-size:10px;line-height:1.2">FIJ</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.7)">Pas de PICO · <span style="color:var(--gold)">FIJ 2027</span></div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">13</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">MAR</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85);flex:1">PICO Mars · <strong style="color:var(--gold)">13h30</strong></div><button class="btn btn-gold btn-sm" style="font-size:10px;padding:5px 10px" onclick="haOpen(HELLOASSO.pico.mar,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">10</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">AVR</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85);flex:1">PICO Avril · <strong style="color:var(--gold)">13h30</strong></div><button class="btn btn-gold btn-sm" style="font-size:10px;padding:5px 10px" onclick="haOpen(HELLOASSO.pico.avr,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">08</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">MAI</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85);flex:1">PICO Mai · <strong style="color:var(--gold)">13h30</strong></div><button class="btn btn-gold btn-sm" style="font-size:10px;padding:5px 10px" onclick="haOpen(HELLOASSO.pico.mai,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">12</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">JUN</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85);flex:1">PICO Juin · <strong style="color:var(--gold)">13h30</strong></div><button class="btn btn-gold btn-sm" style="font-size:10px;padding:5px 10px" onclick="haOpen(HELLOASSO.pico.jun,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
          </div>
          <div style="margin-top:20px;padding-top:16px;border-top:1px solid rgba(255,255,255,.1);font-size:12px;color:rgba(255,255,255,.45);font-style:italic">Un samedi par mois · Inscription en ligne via HelloAsso</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tab Open de Pâques -->
  <div class="tab-panel" id="tab-t-paques" style="padding:60px 0;background:var(--ivoire);display:none">
    <div class="container">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start">
        <div>
          <div class="badge badge-gold" style="margin-bottom:14px">Annuel · Ouvert à tous</div>
          <h2 style="font-size:34px;color:var(--bleu);margin-bottom:12px">Open de Pâques</h2>
          <p style="font-size:15px;color:var(--text);line-height:1.8;margin-bottom:20px">Tournoi ouvert à tous les joueurs licenciés FFE. Cadence classique, homologation Elo FIDE. Idéal pour accumuler des parties homologuées pendant les vacances.</p>
          <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px">
            <div style="background:#fff;border-radius:8px;padding:10px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em">Format</div><div style="font-size:13px;color:var(--bleu);font-weight:600">45 min + 15 sec/coup</div></div>
            <div style="background:#fff;border-radius:8px;padding:10px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em">Rondes</div><div style="font-size:13px;color:var(--bleu);font-weight:600">6 rondes · 3 jours</div></div>
            <div style="background:#fff;border-radius:8px;padding:10px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em">Homologation</div><div style="font-size:13px;color:var(--bleu);font-weight:600">Elo FIDE</div></div>
          </div>
          <div style="background:var(--gold-pale);border-left:4px solid var(--gold);border-radius:0 8px 8px 0;padding:14px 18px;margin-bottom:20px">
            <div style="font-size:14px;font-weight:600;color:var(--bleu)">📅 Samedi 27 au lundi 29 mars 2027</div>
            <div style="font-size:13px;color:var(--muted);margin-top:2px">Au club · 3 Av. du Petit Juas, Cannes</div>
          </div>
          <button class="btn btn-gold" onclick="haOpen(HELLOASSO.paques,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        </div>
        <div style="background:#fff;border-radius:14px;padding:28px;border:1px solid var(--border)">
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;color:var(--bleu);margin-bottom:20px">Infos pratiques</div>
          <div style="display:flex;flex-direction:column;gap:14px">
            <div style="display:flex;gap:12px;align-items:flex-start"><span style="font-size:20px;flex-shrink:0">🏆</span><div><div style="font-weight:600;color:var(--bleu);font-size:14px">Prix</div><div style="font-size:13px;color:var(--muted)">Dotations par catégories d'âge et de classement</div></div></div>
            <div style="display:flex;gap:12px;align-items:flex-start"><span style="font-size:20px;flex-shrink:0">👤</span><div><div style="font-weight:600;color:var(--bleu);font-size:14px">Public</div><div style="font-size:13px;color:var(--muted)">Tous joueurs licenciés FFE · Toutes catégories</div></div></div>
            <div style="display:flex;gap:12px;align-items:flex-start"><span style="font-size:20px;flex-shrink:0">💳</span><div><div style="font-weight:600;color:var(--bleu);font-size:14px">Inscription</div><div style="font-size:13px;color:var(--muted)">En ligne via HelloAsso · Confirmation immédiate</div></div></div>
            <div style="display:flex;gap:12px;align-items:flex-start"><span style="font-size:20px;flex-shrink:0">📍</span><div><div style="font-weight:600;color:var(--bleu);font-size:14px">Lieu</div><div style="font-size:13px;color:var(--muted)">3 Av. du Petit Juas · 06400 Cannes</div></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tab Rapide du vendredi -->
  <div class="tab-panel" id="tab-t-rapide" style="padding:60px 0;background:var(--ivoire);display:none">
    <div class="container">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start">
        <div>
          <div class="badge badge-gold" style="margin-bottom:14px">Mensuel · Sans inscription</div>
          <h2 style="font-size:34px;color:var(--bleu);margin-bottom:12px">Rapide du vendredi</h2>
          <p style="font-size:15px;color:var(--text);line-height:1.8;margin-bottom:20px">Un vendredi par mois, la veille du PICO, soirée parties rapides ouverte à tous les membres. Présentez-vous directement au club à 20h — aucune inscription nécessaire.</p>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px">
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Format</div><div style="font-size:14px;color:var(--bleu);font-weight:600">10 min + 5 sec/coup</div></div>
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Rondes</div><div style="font-size:14px;color:var(--bleu);font-weight:600">4–5 selon présents</div></div>
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Horaire</div><div style="font-size:14px;color:var(--bleu);font-weight:600">20h00 · sur place</div></div>
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Tarif</div><div style="font-size:14px;color:var(--bleu);font-weight:600">Gratuit membres</div></div>
          </div>
          <div style="background:var(--ivoire2);border:1px solid var(--border);border-radius:8px;padding:14px 18px">
            <div style="font-size:14px;color:var(--bleu);font-weight:600">Aucune inscription préalable.</div>
            <div style="font-size:13px;color:var(--muted);margin-top:2px">Venez directement le vendredi soir à 20h.</div>
          </div>
        </div>
        <div style="background:var(--bleu);border-radius:14px;padding:24px">
          <div style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--gold);margin-bottom:16px">Prochains vendredis</div>
          <div style="display:flex;flex-direction:column;gap:0">
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">18</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">SEP</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Sept.</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">09</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">OCT</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Oct.</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">13</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">NOV</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Nov.</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">11</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">DÉC</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Déc.</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">08</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">JAN</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Jan.</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">12</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">MAR</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Mars</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">09</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">AVR</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Avr.</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">07</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">MAI</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Mai</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0">
              <div style="background:rgba(201,168,76,.2);color:var(--gold);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">11</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">JUN</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Juin</div>
            </div>
          </div>
          <div style="margin-top:16px;padding-top:14px;border-top:1px solid rgba(255,255,255,.1);font-size:12px;color:rgba(255,255,255,.45);font-style:italic">Toujours la veille du PICO du mois</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tab Scolaires -->
  <div class="tab-panel" id="tab-t-scolaire" style="padding:60px 0;background:var(--ivoire);display:none">
    <div class="container">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start">
        <div>
          <div class="badge badge-gold" style="margin-bottom:14px">Partenariat Éducation Nationale</div>
          <h2 style="font-size:34px;color:var(--bleu);margin-bottom:12px">Tournois scolaires</h2>
          <p style="font-size:15px;color:var(--text);line-height:1.8;margin-bottom:20px">Cannes Échecs organise les tournois inter-établissements pour les élèves du primaire et du secondaire du bassin cannois, du département au niveau national.</p>
          <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:24px">
            <div style="background:#fff;border-radius:10px;padding:14px 18px;border:1px solid var(--border);display:flex;gap:12px;align-items:center"><span style="font-size:20px">🏫</span><div><div style="font-weight:600;color:var(--bleu);font-size:14px">Tournoi inter-écoles</div><div style="font-size:12px;color:var(--muted)">Printemps · Primaires du bassin cannois</div></div></div>
            <div style="background:#fff;border-radius:10px;padding:14px 18px;border:1px solid var(--border);display:flex;gap:12px;align-items:center"><span style="font-size:20px">🎓</span><div><div style="font-weight:600;color:var(--bleu);font-size:14px">Tournoi inter-collèges</div><div style="font-size:12px;color:var(--muted)">Décembre · Championnats départementaux</div></div></div>
            <div style="background:#fff;border-radius:10px;padding:14px 18px;border:1px solid var(--border);display:flex;gap:12px;align-items:center"><span style="font-size:20px">🏆</span><div><div style="font-weight:600;color:var(--bleu);font-size:14px">Sélection régionale & nationale</div><div style="font-size:12px;color:var(--muted)">Février–avril · Championnats académiques et France</div></div></div>
          </div>
          <div style="display:flex;flex-direction:column;gap:10px">
            <button class="btn btn-gold" onclick="goToTab('activites','scolaire')">Nos interventions scolaires →</button>
            <button class="btn btn-outline-dark" onclick="goTo('contact')">Inscrire votre école →</button>
          </div>
        </div>
        <div style="background:var(--bleu);border-radius:14px;padding:28px;color:#fff">
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;color:var(--gold);margin-bottom:4px">Bilan 2025–2026</div>
          <div style="font-size:12px;color:rgba(255,255,255,.5);margin-bottom:20px;letter-spacing:.04em">Saison terminée · 4 titres majeurs</div>
          <div style="display:flex;flex-direction:column;gap:8px">
            <div style="background:rgba(201,168,76,.18);border-radius:8px;padding:12px 16px"><div style="font-size:13px;font-weight:600;color:var(--gold)">🏆 Ville de Cannes — 130 enfants · 5 catégories</div></div>
            <div style="background:rgba(201,168,76,.12);border-radius:8px;padding:12px 16px"><div style="font-size:13px;font-weight:600;color:var(--gold)">🥇 Sainte-Marie — Championne d'Académie · qualifiée nationale</div></div>
            <div style="background:rgba(255,255,255,.07);border-radius:8px;padding:12px 16px"><div style="font-size:13px;font-weight:600;color:#fff">🥇 Stanislas — Champion Académique collèges</div></div>
            <div style="background:rgba(255,255,255,.07);border-radius:8px;padding:12px 16px"><div style="font-size:13px;font-weight:600;color:#fff">🥇 Stanislas — Champion Départ. écoles & collèges</div></div>
          </div>
          <button class="btn btn-outline-white btn-sm btn-full" style="margin-top:20px" onclick="goTo('actualites')">Voir tous les résultats →</button>
        </div>
      </div>
    </div>
  </div>

</div><!-- fin page-tournois -->

<?php get_footer(); ?>
