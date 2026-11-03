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
      <button class="tab-btn tab-active" data-tab="t-pico"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-target"/></svg> PICO</button>
      <button class="tab-btn" data-tab="t-rapide"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-bolt"/></svg> Rapide</button>
      <button class="tab-btn" data-tab="t-paques"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-sprout"/></svg> Open Pâques</button>
      <button class="tab-btn" data-tab="t-ffe"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-users"/></svg> Compét. FFE</button>
      <button class="tab-btn" data-tab="t-direct"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-clock"/></svg> En direct</button>
    </div>
  </div>

  <!-- Tab PICO -->
  <div class="tab-panel tab-panel-active" id="tab-t-pico" style="padding:var(--sp-8) 0;background:var(--ivoire)">
    <div class="container">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start">
        <div>
          <div class="badge badge-gold" style="margin-bottom:14px">Mensuel · Membres</div>
          <h2 style="font-size:var(--fs-h2);color:var(--bleu);margin-bottom:12px">Tournois PICO</h2>
          <p style="font-size:15px;color:var(--text);line-height:1.8;margin-bottom:20px">Le rendez-vous compétitif mensuel du club. 5 rondes rapides dans une ambiance conviviale, ouvert à tous les membres, toutes catégories.</p>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px">
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold-text);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Format</div><div style="font-size:14px;color:var(--bleu);font-weight:600">Parties rapides 15+10</div></div>
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold-text);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Rondes</div><div style="font-size:14px;color:var(--bleu);font-weight:600">5 rondes · après-midi</div></div>
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold-text);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Accès</div><div style="font-size:14px;color:var(--bleu);font-weight:600">Membres uniquement</div></div>
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold-text);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Tarif</div><div style="font-size:14px;color:var(--bleu);font-weight:600">Gratuit · HelloAsso</div></div>
          </div>
          <div class="pico-next" id="pico-focal">
            <div class="pn-cal"><span class="d">19</span><span class="m">Sep</span></div>
            <div class="pn-body">
              <div class="pn-lbl"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-calendar"/></svg> Prochaine édition</div>
              <div class="pn-date">Samedi 19 septembre 2026 · 13h30</div>
            </div>
            <button class="btn btn-gold btn-sm" style="font-size:10px;padding:8px 14px" onclick="var b=document.querySelector('#tab-t-pico .pico-row.is-next .pico-ins'); if(b) b.click();">S'inscrire →</button>
          </div>
        </div>
        <div class="pico-cal">
          <div class="pico-cal-h"><span class="t">Calendrier PICO</span><span class="s">2026–2027</span></div>
          <div class="pico-rows">
            <div class="pico-row" data-pico="2026-09-19">
              <div class="pico-chip"><span class="n">19</span><span class="mo">Sep</span></div>
              <div class="pico-lbl">PICO Septembre · <strong>13h30</strong></div>
              <button class="pico-ins" onclick="haOpen(HELLOASSO.pico.sep,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div class="pico-row" data-pico="2026-10-10">
              <div class="pico-chip"><span class="n">10</span><span class="mo">Oct</span></div>
              <div class="pico-lbl">PICO Octobre · <strong>13h30</strong></div>
              <button class="pico-ins" onclick="haOpen(HELLOASSO.pico.oct,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div class="pico-row" data-pico="2026-11-14">
              <div class="pico-chip"><span class="n">14</span><span class="mo">Nov</span></div>
              <div class="pico-lbl">PICO Novembre · <strong>13h30</strong></div>
              <button class="pico-ins" onclick="haOpen(HELLOASSO.pico.nov,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div class="pico-row" data-pico="2026-12-12">
              <div class="pico-chip"><span class="n">12</span><span class="mo">Déc</span></div>
              <div class="pico-lbl">PICO Décembre · <strong>13h30</strong></div>
              <button class="pico-ins" onclick="haOpen(HELLOASSO.pico.dec,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div class="pico-row" data-pico="2027-01-09">
              <div class="pico-chip"><span class="n">09</span><span class="mo">Jan</span></div>
              <div class="pico-lbl">PICO Janvier · <strong>13h30</strong></div>
              <button class="pico-ins" onclick="haOpen(HELLOASSO.pico.jan,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div class="pico-row off">
              <div class="pico-chip"><span class="mo">Fév</span><span class="mo">FIJ</span></div>
              <div class="pico-lbl">Pas de PICO · <strong>FIJ 2027</strong></div>
            </div>
            <div class="pico-row" data-pico="2027-03-13">
              <div class="pico-chip"><span class="n">13</span><span class="mo">Mar</span></div>
              <div class="pico-lbl">PICO Mars · <strong>13h30</strong></div>
              <button class="pico-ins" onclick="haOpen(HELLOASSO.pico.mar,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div class="pico-row" data-pico="2027-04-10">
              <div class="pico-chip"><span class="n">10</span><span class="mo">Avr</span></div>
              <div class="pico-lbl">PICO Avril · <strong>13h30</strong></div>
              <button class="pico-ins" onclick="haOpen(HELLOASSO.pico.avr,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div class="pico-row" data-pico="2027-05-08">
              <div class="pico-chip"><span class="n">08</span><span class="mo">Mai</span></div>
              <div class="pico-lbl">PICO Mai · <strong>13h30</strong></div>
              <button class="pico-ins" onclick="haOpen(HELLOASSO.pico.mai,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
            <div class="pico-row" data-pico="2027-06-12">
              <div class="pico-chip"><span class="n">12</span><span class="mo">Jun</span></div>
              <div class="pico-lbl">PICO Juin · <strong>13h30</strong></div>
              <button class="pico-ins" onclick="haOpen(HELLOASSO.pico.jun,'_blank','noopener,noreferrer')">S'inscrire →</button>
            </div>
          </div>
          <div class="pico-cal-foot">Un samedi par mois · Inscription en ligne via HelloAsso</div>
        </div>
      </div>

      <!-- Classement Pico Elo — alimenté par le fichier classement.html à la racine du site.
           Pour mettre à jour : depuis l'outil PicoElo, « Publier sur le site » → remplacer
           classement.html dans ce dossier → git push. L'iframe s'auto-dimensionne (même origine). -->
      <div class="pico-classement" style="margin-top:56px;padding-top:48px;border-top:1px solid var(--border)">
        <div style="text-align:center;max-width:640px;margin:0 auto 28px">
          <div class="badge badge-gold" style="margin-bottom:14px">Mis à jour chaque mois</div>
          <h2 style="font-size:var(--fs-h2);color:var(--bleu);margin-bottom:10px">Classement Pico Elo</h2>
          <p style="font-size:15px;color:var(--text);line-height:1.8">Le classement interne des jeunes du club, recalculé après chaque tournoi PICO. Chaque victoire fait progresser, chaque partie compte.</p>
        </div>
        <div style="border-radius:16px;overflow:hidden;border:1px solid var(--border);box-shadow:var(--sh-sm);background:var(--ivoire)">
          <iframe id="pico-classement-frame" src="classement.html" title="Classement Pico Elo — Cannes Échecs" loading="lazy" scrolling="no" onload="fitPicoFrame(this)" style="display:block;width:100%;height:900px;border:0"></iframe>
        </div>
        <p style="text-align:center;font-size:12px;color:var(--muted);margin-top:12px;font-style:italic">Classement à titre indicatif · Système Pico Elo interne au club</p>
      </div>
      <script>
        // Ajuste la hauteur de l'iframe au contenu (possible car classement.html est même origine).
        // Le classement est dans un onglet masqué au chargement : tant que la section n'est pas
        // affichée, l'iframe mesure 0. On (re)mesure donc à chaque fois qu'elle devient visible
        // (IntersectionObserver) et au chargement de l'iframe, avec quelques essais le temps que
        // les polices web se stabilisent. Cross-origin -> la hauteur par défaut est conservée.
        function fitPicoFrame(f){
          if(!f)return;
          var tries=0;
          (function apply(){
            var h=0;
            try{
              var d=f.contentDocument||f.contentWindow.document;
              // On mesure la hauteur du CONTENU (body). Pas documentElement : <html> remplit
              // le cadre et renverrait la hauteur actuelle de l'iframe, pas celle du contenu.
              h=d.body?d.body.scrollHeight:0;
            }catch(e){return;/* cross-origin : on garde la hauteur fixe */}
            if(h>200){f.style.height=h+'px';return;}
            if(tries++<25)setTimeout(apply,150);
          })();
        }
        (function(){
          var f=document.getElementById('pico-classement-frame');
          if(!f)return;
          if('IntersectionObserver' in window){
            var io=new IntersectionObserver(function(entries){
              entries.forEach(function(en){ if(en.isIntersecting) fitPicoFrame(f); });
            },{threshold:0.01});
            io.observe(f);
          }
          window.addEventListener('resize',function(){fitPicoFrame(f);});
        })();
      </script>
    </div>
  </div>

  <!-- Tab Open de Pâques -->
  <div class="tab-panel" id="tab-t-paques" style="padding:var(--sp-8) 0;background:var(--ivoire);display:none">
    <div class="container">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start">
        <div>
          <div class="badge badge-gold" style="margin-bottom:14px">Annuel · Ouvert à tous</div>
          <h2 style="font-size:var(--fs-h2);color:var(--bleu);margin-bottom:12px">Open de Pâques</h2>
          <p style="font-size:15px;color:var(--text);line-height:1.8;margin-bottom:20px">Tournoi ouvert à tous les joueurs licenciés FFE. Cadence classique, homologation Elo FIDE. Idéal pour accumuler des parties homologuées pendant les vacances.</p>
          <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px">
            <div style="background:#fff;border-radius:8px;padding:10px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold-text);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em">Format</div><div style="font-size:13px;color:var(--bleu);font-weight:600">45 min + 15 sec/coup</div></div>
            <div style="background:#fff;border-radius:8px;padding:10px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold-text);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em">Rondes</div><div style="font-size:13px;color:var(--bleu);font-weight:600">6 rondes · 3 jours</div></div>
            <div style="background:#fff;border-radius:8px;padding:10px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold-text);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em">Homologation</div><div style="font-size:13px;color:var(--bleu);font-weight:600">Elo FIDE</div></div>
          </div>
          <div style="background:var(--gold-pale);border:1px solid rgba(201,168,76,.35);border-radius:8px;padding:14px 18px;margin-bottom:20px">
            <div style="font-size:14px;font-weight:600;color:var(--bleu)"><svg class="ic" style="color:var(--gold)" viewBox="0 0 24 24"><use href="#ic-calendar"/></svg> Samedi 27 au lundi 29 mars 2027</div>
            <div style="font-size:13px;color:var(--muted);margin-top:2px">Au club · 3 Av. du Petit Juas, Cannes</div>
          </div>
          <button class="btn btn-gold" onclick="haOpen(HELLOASSO.paques,'_blank','noopener,noreferrer')">S'inscrire via HelloAsso →</button>
        </div>
        <div class="card card-lg">
          <div style="font-family:'Cormorant Garamond',serif;font-size:22px;color:var(--bleu);margin-bottom:20px">Infos pratiques</div>
          <div style="display:flex;flex-direction:column;gap:14px">
            <div style="display:flex;gap:12px;align-items:flex-start"><span style="font-size:20px;flex-shrink:0;color:var(--gold)"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-trophy"/></svg></span><div><div style="font-weight:600;color:var(--bleu);font-size:14px">Prix</div><div style="font-size:13px;color:var(--muted)">Dotations par catégories d'âge et de classement</div></div></div>
            <div style="display:flex;gap:12px;align-items:flex-start"><span style="font-size:20px;flex-shrink:0;color:var(--gold)"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-user"/></svg></span><div><div style="font-weight:600;color:var(--bleu);font-size:14px">Public</div><div style="font-size:13px;color:var(--muted)">Tous joueurs licenciés FFE · Toutes catégories</div></div></div>
            <div style="display:flex;gap:12px;align-items:flex-start"><span style="font-size:20px;flex-shrink:0;color:var(--gold)"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-card"/></svg></span><div><div style="font-weight:600;color:var(--bleu);font-size:14px">Inscription</div><div style="font-size:13px;color:var(--muted)">En ligne via HelloAsso · Confirmation immédiate</div></div></div>
            <div style="display:flex;gap:12px;align-items:flex-start"><span style="font-size:20px;flex-shrink:0;color:var(--gold)"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-pin"/></svg></span><div><div style="font-weight:600;color:var(--bleu);font-size:14px">Lieu</div><div style="font-size:13px;color:var(--muted)">3 Av. du Petit Juas · 06400 Cannes</div></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tab Rapide du vendredi -->
  <div class="tab-panel" id="tab-t-rapide" style="padding:var(--sp-8) 0;background:var(--ivoire);display:none">
    <div class="container">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start">
        <div>
          <div class="badge badge-gold" style="margin-bottom:14px">Mensuel · Sans inscription</div>
          <h2 style="font-size:var(--fs-h2);color:var(--bleu);margin-bottom:12px">Rapide du vendredi</h2>
          <p style="font-size:15px;color:var(--text);line-height:1.8;margin-bottom:20px">Un vendredi par mois, la veille du PICO, soirée parties rapides ouverte à tous les membres. Présentez-vous directement au club à 20h — aucune inscription nécessaire.</p>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px">
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold-text);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Format</div><div style="font-size:14px;color:var(--bleu);font-weight:600">10 min + 5 sec/coup</div></div>
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold-text);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Rondes</div><div style="font-size:14px;color:var(--bleu);font-weight:600">4–5 selon présents</div></div>
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold-text);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Horaire</div><div style="font-size:14px;color:var(--bleu);font-weight:600">20h00 · sur place</div></div>
            <div style="background:#fff;border-radius:8px;padding:12px 14px;border:1px solid var(--border)"><div style="font-size:10px;color:var(--gold-text);font-family:'Montserrat',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Tarif</div><div style="font-size:14px;color:var(--bleu);font-weight:600">Gratuit membres</div></div>
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
              <div style="background:var(--gold-tint);color:var(--gold-pale);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">18</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">SEP</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Sept.</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:var(--gold-tint);color:var(--gold-pale);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">09</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">OCT</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Oct.</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:var(--gold-tint);color:var(--gold-pale);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">13</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">NOV</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Nov.</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:var(--gold-tint);color:var(--gold-pale);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">11</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">DÉC</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Déc.</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:var(--gold-tint);color:var(--gold-pale);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">08</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">JAN</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Jan.</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:var(--gold-tint);color:var(--gold-pale);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">12</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">MAR</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Mars</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:var(--gold-tint);color:var(--gold-pale);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">09</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">AVR</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Avr.</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.1)">
              <div style="background:var(--gold-tint);color:var(--gold-pale);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">07</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">MAI</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Mai</div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0">
              <div style="background:var(--gold-tint);color:var(--gold-pale);font-family:'Montserrat',sans-serif;font-weight:700;text-align:center;min-width:44px;height:44px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0"><span style="font-size:15px;line-height:1">11</span><span style="font-size:8px;opacity:.8;text-transform:uppercase">JUN</span></div>
              <div style="font-size:13px;color:rgba(255,255,255,.85)">Rapide · <strong style="color:var(--gold)">20h00</strong> · Veille PICO Juin</div>
            </div>
          </div>
          <div style="margin-top:16px;padding-top:14px;border-top:1px solid rgba(255,255,255,.1);font-size:12px;color:rgba(255,255,255,.45);font-style:italic">Toujours la veille du PICO du mois</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tab Scolaires -->
  <!-- Onglet Compétitions officielles FFE (déplacé depuis l'ancien onglet Activités › Compétitions) -->
  <div class="tab-panel" id="tab-t-ffe" style="padding:var(--sp-8) 0;background:var(--ivoire);display:none">
    <div class="container">
      <div class="section-header center" style="margin-bottom:24px">
        <span class="surtitre">Compétitions fédérales</span>
        <h2 style="font-size:var(--fs-h2);color:var(--bleu)">Compétitions officielles FFE</h2>
      </div>
      <p style="font-size:15px;color:var(--text);line-height:1.8;max-width:640px;margin:0 auto 28px;text-align:center">Au-delà de ses tournois maison, Cannes Échecs engage ses membres dans les compétitions officielles de la Fédération Française des Échecs tout au long de la saison.</p>
      <div style="max-width:720px;margin:0 auto;background:#fff;border:1px solid var(--border);border-radius:14px;padding:26px 30px;display:flex;align-items:center;gap:22px;flex-wrap:wrap;justify-content:space-between">
        <div style="display:flex;align-items:center;gap:16px;min-width:0">
          <span style="font-size:30px;color:var(--gold);flex-shrink:0"><svg class="ic" viewBox="0 0 24 24"><use href="#ic-trophy"/></svg></span>
          <div style="font-size:14px;color:var(--text);line-height:1.7">Championnat de France par équipes · Championnats jeunes FFE (U8→U18) · Interclubs régionaux</div>
        </div>
        <button class="btn btn-outline-dark btn-sm" style="flex-shrink:0" onclick="goTo('adhesion')">Prendre une licence FFE →</button>
      </div>
    </div>
  </div>

  <!-- Tab En direct : suivi des joueurs du club en tournoi. Contenu fourni par le
       plugin WordPress « Suivi tournoi » (shortcode injecté par build-wp-theme.js) ;
       onglet masqué sur le site statique, qui n'a pas de serveur pour les données. -->
  <div class="tab-panel" id="tab-t-direct" style="padding:var(--sp-8) 0;background:var(--ivoire);display:none">
    <div class="container">
      <div class="badge badge-gold" style="margin-bottom:14px">Nos joueurs en tournoi</div>
      <h2 style="font-size:var(--fs-h2);color:var(--bleu);margin-bottom:12px">En direct</h2>
      <p style="font-size:15px;color:var(--text);line-height:1.8;margin-bottom:28px;max-width:640px">Les résultats des joueurs de Cannes Échecs dans les tournois homologués FFE et à l'étranger, ronde après ronde. Mis à jour automatiquement toutes les quelques minutes.</p>
      <?php echo shortcode_exists('suivi_tournoi') ? do_shortcode('[suivi_tournoi]') : '<p>Le suivi des joueurs sera bientôt disponible.</p>'; ?>
    </div>
  </div>

  <!-- Renvoi scolaire — regroupé dans Activités › Les échecs à l'école -->
  <div class="container" style="padding:8px 0 44px;text-align:center">
    <p style="font-size:13px;color:var(--muted)">Tournois et interventions scolaires sont désormais réunis dans <a href="<?php echo esc_url(home_url('/activites/')); ?>#activites-scolaire" style="color:var(--gold-text);font-weight:600;cursor:pointer">Activités › Les échecs à l'école →</a></p>
  </div>

</div><!-- fin page-tournois -->

<?php get_footer(); ?>
