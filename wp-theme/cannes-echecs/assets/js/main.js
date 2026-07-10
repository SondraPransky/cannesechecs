
// ── HELLOASSO — LIENS D'INSCRIPTION ─────────────────
// ⚠️ Remplacer chaque URL provisoire par le vrai lien HelloAsso
// ── INSCRIPTIONS FIJ — à mettre à jour quand les inscriptions ouvrent ───────
// url     : lien echecs.asso.fr liste des inscrits ('' = pas encore disponible)
// count   : nombre d'inscrits actuels (0 = ne pas afficher)
// pairings: lien echecs.asso.fr appariements ('' = pas encore disponible)
// La section disparaît automatiquement si url='' ET count=0 ET pairings='' pour tous les opens.
// Pour masquer manuellement après le FIJ → panneau admin (?admin dans l'URL)

// ══════════════════════════════════════════════════════════════════════════════
// DONNÉES DU SITE — MODIFIER ICI POUR METTRE À JOUR LE CONTENU
// ══════════════════════════════════════════════════════════════════════════════

// 1. SAISON EN COURS ── changer cette seule ligne met à jour tout le site

// 2. HORAIRES DU LOCAL (planning hebdomadaire)
//    ferme:true     → ligne grisée "Fermé"
//    highlight:true → activité affichée en bleu gras

// 3. ÉQUIPE DU CLUB
//    avatar : emoji 👤 ou chemin vers photo (ex: 'photos/joffrey.jpg')


// ── HELLOASSO — garde-fou liens non renseignés ──────
// Tant que les URLs réelles ne remplacent pas les LIEN-*, on affiche un
// message au lieu d'envoyer le visiteur vers une page HelloAsso inexistante.
function haOpen(url) {
  if (!url || url.indexOf('LIEN-') > -1) { haNotice(); return; }
  window.open(url, '_blank', 'noopener,noreferrer');
}
function haNotice() {
  var old = document.getElementById('ha-notice');
  if (old) old.remove();
  var n = document.createElement('div');
  n.id = 'ha-notice';
  n.setAttribute('role', 'status');
  n.style.cssText = 'position:fixed;left:50%;bottom:24px;transform:translateX(-50%);z-index:9999;background:var(--bleu);color:#fff;border:1px solid rgba(201,168,76,.5);border-radius:12px;padding:18px 40px 18px 22px;max-width:440px;width:calc(100% - 32px);box-shadow:0 8px 32px rgba(0,0,0,.35);font-size:14px;line-height:1.6';
  n.innerHTML = '<strong style="color:var(--gold)">Inscriptions en ligne bientôt disponibles</strong><br>'
    + 'En attendant, contactez le club : <a href="tel:+33493394139" style="color:var(--gold)">04 93 39 41 39</a> · '
    + '<a href="mailto:info@cannes-echecs.fr" style="color:var(--gold)">info@cannes-echecs.fr</a>'
    + '<button class="ha-notice-close" onclick="this.parentElement.remove()" aria-label="Fermer">×</button>';
  document.body.appendChild(n);
  setTimeout(function() { if (n.parentElement) n.remove(); }, 8000);
}

// ── MENU MOBILE ─────────────────────────────────────
function toggleMobileNav() {
  document.querySelector('.navbar').classList.toggle('nav-open');
  document.body.style.overflow = document.querySelector('.navbar').classList.contains('nav-open') ? 'hidden' : '';
}
function mobileGoTo(page, tab) {
  document.querySelector('.navbar').classList.remove('nav-open');
  document.body.style.overflow = '';
  goToTab(page, tab);
}

// Naviguer vers une page ET activer un onglet spécifique

// Naviguer vers Contact avec l'objet du message pré-rempli

// Accueil → page Club, défile jusqu'au bloc palmarès
// (noScroll évite que le scroll-to-top de goTo écrase le scrollIntoView)
function goToPalmares() {
  goTo('club', { noScroll: true });
  setTimeout(function() {
    var el = document.querySelector('.club-palmares');
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }, 50);
}

// ── ROUTER ──────────────────────────────────────────


// ── COMPTE À REBOURS ────────────────────────────────

function pad(n) { return String(n).padStart(2,'0'); }

var _fijState = null;
function renderFijSbInscrits() {
  var el = document.getElementById('fij-sb-inscrits');
  if (!el) return;
  var opens = [{k:'openA',l:'Open A'},{k:'openB',l:'Open B'},{k:'openC',l:'Open C'}];
  el.innerHTML = '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">'
    + opens.map(function(o) {
        var c = FIJ_INSCRITS[o.k].count;
        return '<div style="background:rgba(255,255,255,.07);border-radius:8px;padding:10px;text-align:center">'
          + '<div style="font-family:\'Cormorant Garamond\',serif;font-size:26px;font-weight:700;color:var(--gold);line-height:1">' + (c || '—') + '</div>'
          + '<div style="font-size:9px;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.06em;margin-top:3px">' + o.l + '</div>'
          + '</div>';
      }).join('') + '</div>';
}
function updateCountdowns() {
  var now = new Date();
  // ── Home widget (vers ouverture inscriptions) ──
  var diff1 = FIJ_OPEN - now;
  if (diff1 > 0) {
    var d1=Math.floor(diff1/86400000), h1=Math.floor((diff1%86400000)/3600000), m1=Math.floor((diff1%3600000)/60000);
    ['h-cd-j','h-cd-h','h-cd-m'].forEach(function(id,i){var el=document.getElementById(id);if(el)el.textContent=pad([d1,h1,m1][i]);});
  }
  // ── Hero widget (vers ronde 1) ──
  var diff2 = FIJ_DATE - now;
  if (diff2 > 0) {
    var d2=Math.floor(diff2/86400000),h2=Math.floor((diff2%86400000)/3600000),m2=Math.floor((diff2%3600000)/60000),s2=Math.floor((diff2%60000)/1000);
    ['f-cd-j','f-cd-h','f-cd-m','f-cd-s'].forEach(function(id,i){var el=document.getElementById(id);if(el)el.textContent=pad([d2,h2,m2,s2][i]);});
  }
  // ── Sidebar FIJ — 3 états ──
  var state = now < FIJ_RONDES[0].date ? 'avant' : now < FIJ_FIN ? 'encours' : 'fini';
  if (state !== _fijState) {
    _fijState = state;
    var avant=document.getElementById('fij-sidebar-avant');
    var encours=document.getElementById('fij-sidebar-encours');
    var fini=document.getElementById('fij-sidebar-fini');
    if(avant)   avant.style.display   = state==='avant'   ? '' : 'none';
    if(encours) encours.style.display = state==='encours' ? '' : 'none';
    if(fini)    fini.style.display    = state==='fini'    ? '' : 'none';
    if(state==='encours') renderFijSbInscrits();
  }
  if (state === 'avant') {
    // Countdown page FIJ (même valeurs que hero widget)
    if (diff2 > 0) {
      var d2b=Math.floor(diff2/86400000),h2b=Math.floor((diff2%86400000)/3600000),m2b=Math.floor((diff2%3600000)/60000),s2b=Math.floor((diff2%60000)/1000);
      ['fij-cd-j','fij-cd-h','fij-cd-m','fij-cd-s'].forEach(function(id,i){var el=document.getElementById(id);if(el)el.textContent=pad([d2b,h2b,m2b,s2b][i]);});
    }
  } else if (state === 'encours') {
    // Ronde en cours si démarrée depuis moins de 2h, sinon prochaine ronde
    var TWO_H = 7200000;
    var currentRonde = null, nextRonde = null;
    for(var i=0;i<FIJ_RONDES.length;i++){
      var r=FIJ_RONDES[i];
      if(r.date<=now && now-r.date<TWO_H){ currentRonde=r; break; }
      if(r.date>now){ nextRonde=r; break; }
    }
    var lbl=document.getElementById('fij-sb-ronde-label');
    var hdr=document.getElementById('fij-sb-ronde-header');
    var cdDiv=document.getElementById('fij-sb-countdown');
    if(currentRonde){
      if(hdr) hdr.textContent='Ronde en cours';
      if(lbl) lbl.textContent=currentRonde.label;
      if(cdDiv) cdDiv.style.display='none';
    } else if(nextRonde){
      if(hdr) hdr.textContent='Prochaine ronde';
      if(lbl) lbl.textContent=nextRonde.label;
      if(cdDiv) cdDiv.style.display='';
      var diffR=nextRonde.date-now;
      var hr=Math.floor(diffR/3600000),mr=Math.floor((diffR%3600000)/60000),sr=Math.floor((diffR%60000)/1000);
      ['fij-sb-h','fij-sb-m','fij-sb-s'].forEach(function(id,i){var el=document.getElementById(id);if(el)el.textContent=pad([hr,mr,sr][i]);});
    }
  }
}


// ── HELPER : catégorie article → filtre CSS ──────────
function catToFilter(cat) {
  if (cat.includes('Tournoi'))                                    return 'tournois';
  if (cat.includes('Scolaire'))                                   return 'scolaire';
  if (cat.includes('Vie du club') || cat.includes('Animation'))  return 'club';
  return 'resultats';
}

// ── HELPER : construit le HTML d'une carte article ───
// showExtrait=true → affiche le résumé (archive, home)
// showExtrait=false → carte compacte (section "À lire aussi")

// Normalisation pour la recherche : minuscules, sans accents
function ceNormalize(s) {
  return (s || '').toString().toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');
}


// ── RENDU : grille archive (page Actualités) ─────────

// ── RENDU : 4 dernières actus (page Accueil) ─────────

// ── RENDU : inscrits FIJ (page FIJ) ─────────────────
function renderFijInscrits() {
  var sec = document.getElementById('fij-inscrits-section');
  if (!sec) return;
  var off = false;
  try { off = localStorage.getItem('ace_fij_inscrits') === 'off'; } catch(e) {}
  if (off) { sec.style.display = 'none'; return; }
  var OPENS = [
    { key:'openA', label:'Open A', border:'var(--gold)' },
    { key:'openB', label:'Open B', border:'var(--bleu)' },
    { key:'openC', label:'Open C', border:'var(--muted)' },
  ];
  var rows = OPENS.filter(function(o) {
    var d = FIJ_INSCRITS[o.key];
    return d.url || d.pairings || d.count > 0;
  }).map(function(o) {
    var d = FIJ_INSCRITS[o.key];
    var letter = o.label.slice(-1);
    var fijStarted = new Date() >= FIJ_RONDES[0].date;
    var linkHtml;
    if (fijStarted) {
      linkHtml = d.pairings
        ? '<a href="' + d.pairings + '" target="_blank" rel="noopener noreferrer" style="font-size:13px;color:var(--gold)">Appariements →</a>'
        : '<span style="font-size:12px;color:var(--muted);font-style:italic">Appariements à venir</span>';
    } else {
      linkHtml = d.url
        ? '<a href="' + d.url + '" target="_blank" rel="noopener noreferrer" style="font-size:13px;color:var(--gold)">Liste des inscrits →</a>'
        : '<span style="font-size:12px;color:var(--muted);font-style:italic">Inscriptions à venir</span>';
    }
    var countHtml = d.count > 0
      ? '<div style="text-align:center;min-width:48px"><div style="font-family:\'Cormorant Garamond\',serif;font-size:30px;font-weight:700;color:var(--bleu);line-height:1">' + d.count + '</div><div style="font-size:10px;color:var(--muted)">' + (d.count > 1 ? 'inscrits' : 'inscrit') + '</div></div>'
      : '';
    return '<div style="display:flex;align-items:center;gap:14px;padding:12px 16px;background:var(--ivoire);border-radius:10px;border:1px solid ' + o.border + '">'
      + '<div style="font-family:\'Cormorant Garamond\',serif;font-size:28px;font-weight:700;color:var(--bleu);min-width:24px;text-align:center">' + letter + '</div>'
      + '<div style="flex:1"><div style="font-family:\'Montserrat\',sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--bleu);margin-bottom:3px">' + o.label + '</div>' + linkHtml + '</div>'
      + countHtml
      + '</div>';
  });
  if (rows.length === 0) { sec.style.display = 'none'; return; }
  sec.style.display = '';
  sec.innerHTML = '<h3 style="font-family:\'Cormorant Garamond\',serif;font-size:22px;color:var(--bleu);margin-bottom:14px;margin-top:28px">Inscrits FIJ 2027</h3>'
    + '<div style="display:flex;flex-direction:column;gap:10px">' + rows.join('') + '</div>';
}

// ── PAGE ARTICLE DYNAMIQUE ────────────────────────────

// ── AGENDA — TÉLÉCHARGEMENT ICS ─────────────────────
function downloadICS() {
  const CRLF = '\r\n';
  const loc   = '3 Avenue du Petit Juas\\, 06400 Cannes';
  const events = [
    { start:'20260909T073000Z', end:'20260909T173000Z', summary:'Reprise des cours — Cannes Échecs', desc:'Rentrée saison 2026-2027 · Accueil des nouveaux membres' },
    { start:'20260918T180000Z', end:'20260918T210000Z', summary:'Rapide du vendredi — Cannes Échecs', desc:'Parties rapides 10+5 · Membres · Gratuit · Veille du PICO' },
    { start:'20260919T113000Z', end:'20260919T163000Z', summary:'PICO Septembre — Cannes Échecs', desc:'Tournoi mensuel · 5 rondes · Membres · 13h30' },
    { start:'20261009T180000Z', end:'20261009T210000Z', summary:'Rapide du vendredi — Cannes Échecs', desc:'Parties rapides 10+5 · Membres · Gratuit · Veille du PICO' },
    { start:'20261010T113000Z', end:'20261010T163000Z', summary:'PICO Octobre — Cannes Échecs', desc:'Tournoi mensuel · 5 rondes · Membres · 13h30' },
    { start:'20261113T190000Z', end:'20261113T220000Z', summary:'Rapide du vendredi — Cannes Échecs', desc:'Parties rapides 10+5 · Membres · Gratuit · Veille du PICO' },
    { start:'20261114T123000Z', end:'20261114T173000Z', summary:'PICO Novembre — Cannes Échecs', desc:'Tournoi mensuel · 5 rondes · Membres · 13h30' },
    { start:'20261211T190000Z', end:'20261211T220000Z', summary:'Rapide du vendredi — Cannes Échecs', desc:'Parties rapides 10+5 · Membres · Gratuit · Veille du PICO' },
    { start:'20261212T123000Z', end:'20261212T173000Z', summary:'PICO Décembre — Cannes Échecs', desc:'Tournoi mensuel · 5 rondes · Membres · 13h30' },
    { start:'20270108T190000Z', end:'20270108T220000Z', summary:'Rapide du vendredi — Cannes Échecs', desc:'Parties rapides 10+5 · Membres · Gratuit · Veille du PICO' },
    { start:'20270109T123000Z', end:'20270109T173000Z', summary:'PICO Janvier — Cannes Échecs', desc:'Tournoi mensuel · 5 rondes · Membres · 13h30' },
    { start:'20270201T090000Z', end:'20270205T180000Z', summary:'FIJ 2027 — Tournois Cannes Échecs', desc:'Festival International des Jeux · Tournois A/B/C · Palais des Festivals' },
    { start:'20270312T190000Z', end:'20270312T220000Z', summary:'Rapide du vendredi — Cannes Échecs', desc:'Parties rapides 10+5 · Membres · Gratuit · Veille du PICO' },
    { start:'20270313T123000Z', end:'20270313T173000Z', summary:'PICO Mars — Cannes Échecs', desc:'Tournoi mensuel · 5 rondes · Membres · 13h30' },
    { start:'20270409T180000Z', end:'20270409T210000Z', summary:'Rapide du vendredi — Cannes Échecs', desc:'Parties rapides 10+5 · Membres · Gratuit · Veille du PICO' },
    { start:'20270410T113000Z', end:'20270410T163000Z', summary:'PICO Avril — Cannes Échecs', desc:'Tournoi mensuel · 5 rondes · Membres · 13h30' },
    { start:'20270507T180000Z', end:'20270507T210000Z', summary:'Rapide du vendredi — Cannes Échecs', desc:'Parties rapides 10+5 · Membres · Gratuit · Veille du PICO' },
    { start:'20270508T113000Z', end:'20270508T163000Z', summary:'PICO Mai — Cannes Échecs', desc:'Tournoi mensuel · 5 rondes · Membres · 13h30' },
    { start:'20270611T180000Z', end:'20270611T210000Z', summary:'Rapide du vendredi — Cannes Échecs', desc:'Parties rapides 10+5 · Membres · Gratuit · Veille du PICO' },
    { start:'20270612T113000Z', end:'20270612T163000Z', summary:'PICO Juin — Cannes Échecs', desc:'Tournoi mensuel · 5 rondes · Membres · 13h30' }
  ];
  let ics = 'BEGIN:VCALENDAR' + CRLF
    + 'VERSION:2.0' + CRLF
    + 'PRODID:-//Cannes Echecs//FR' + CRLF
    + 'X-WR-CALNAME:Agenda Cannes Échecs' + CRLF
    + 'X-WR-TIMEZONE:Europe/Paris' + CRLF;
  events.forEach(function(e) {
    ics += 'BEGIN:VEVENT' + CRLF
      + 'DTSTART:' + e.start + CRLF
      + 'DTEND:' + e.end + CRLF
      + 'SUMMARY:' + e.summary + CRLF
      + 'DESCRIPTION:' + e.desc + CRLF
      + 'LOCATION:' + loc + CRLF
      + 'END:VEVENT' + CRLF;
  });
  ics += 'END:VCALENDAR';
  const blob = new Blob([ics], { type: 'text/calendar;charset=utf-8' });
  const url  = URL.createObjectURL(blob);
  const a    = document.createElement('a');
  a.href = url; a.download = 'agenda-cannes-echecs-2026.ics';
  document.body.appendChild(a); a.click();
  document.body.removeChild(a); URL.revokeObjectURL(url);
}

function openGoogleCalendar() {
  // Ouvre le premier événement (reprise des cours) sur Google Calendar
  const url = 'https://calendar.google.com/calendar/r/eventedit'
    + '?text=Reprise+des+cours+%E2%80%94+Cann%C3%A8s+%C3%89checs'
    + '&dates=20260909T073000Z/20260909T173000Z'
    + '&location=3+Avenue+du+Petit+Juas%2C+06400+Cannes'
    + '&details=Rentr%C3%A9e+saison+2026-2027';
  window.open(url, '_blank', 'noopener');
}

// ── PARTAGE ARTICLE ─────────────────────────────────
function shareFb() {
  const url = 'https://www.facebook.com/sharer/sharer.php?u='
    + encodeURIComponent(window._shareUrl || 'https://cannes-echecs.fr/')
    + '&quote=' + encodeURIComponent(window._shareTitle || '');
  window.open(url, '_blank', 'noopener,noreferrer');
}
function shareTw() {
  const url = 'https://twitter.com/intent/tweet?url='
    + encodeURIComponent(window._shareUrl || 'https://cannes-echecs.fr/')
    + '&text=' + encodeURIComponent((window._shareTitle || '') + ' — Cannes Échecs ♟️');
  window.open(url, '_blank', 'noopener,noreferrer');
}
function shareNative() {
  if (!navigator.share) return;
  navigator.share({
    title: window._shareTitle || 'Cannes Échecs',
    text:  window._shareText  || '',
    url:   window._shareUrl   || 'https://cannes-echecs.fr/'
  }).catch(function() {}); // annulation utilisateur silencieuse
}

// ── FORMULAIRE CONTACT ──────────────────────────────
function sendContact(e) {
  e.preventDefault();
  const nom     = document.getElementById('cf-nom');
  const email   = document.getElementById('cf-email');
  const message = document.getElementById('cf-message');
  const rgpd    = document.getElementById('cf-rgpd');
  const fb      = document.getElementById('cf-feedback');
  const honey   = document.getElementById('cf-honey');
  if (honey && honey.value) { e.target.reset(); return; } // champ piège rempli → bot, on ignore
  // Validation
  const errors = [];
  if (!nom.value.trim())     errors.push('Veuillez indiquer votre nom.');
  if (!email.value.trim() || !/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/.test(email.value))
    errors.push('Veuillez indiquer une adresse email valide.');
  if (!message.value.trim()) errors.push('Veuillez écrire un message.');
  if (!rgpd.checked)         errors.push('Veuillez accepter la politique de données.');
  if (errors.length) {
    fb.style.display = 'block';
    fb.style.background = '#fef2f2';
    fb.style.border = '1px solid #fca5a5';
    fb.style.color = '#b91c1c';
    fb.innerHTML = errors.map(function(e){ return '• ' + e; }).join('<br>');
    return;
  }
  const btn = e.target.querySelector('[type="submit"]');
  btn.textContent = 'Envoi en cours…';
  btn.disabled = true;
  fetch('https://formsubmit.co/ajax/info@cannes-echecs.fr', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({
      name: nom.value.trim(),
      email: email.value.trim(),
      message: message.value.trim(),
      _subject: 'Message depuis cannes-echecs.fr'
    })
  }).then(function(response) {
    if (response.ok) {
      fb.style.display = 'block';
      fb.style.background = '#f0fdf4';
      fb.style.border = '1px solid #86efac';
      fb.style.color = '#15803d';
      fb.textContent = '✓ Message envoyé ! Nous vous répondrons sous 48h à ' + email.value;
      e.target.reset();
    } else {
      throw new Error('Erreur serveur');
    }
  }).catch(function() {
    fb.style.display = 'block';
    fb.style.background = '#fef2f2';
    fb.style.border = '1px solid #fca5a5';
    fb.style.color = '#b91c1c';
    fb.innerHTML = 'L\'envoi a échoué. Contactez-nous directement : <a href="mailto:info@cannes-echecs.fr" style="color:var(--bleu)">info@cannes-echecs.fr</a>';
  }).finally(function() {
    btn.textContent = 'Envoyer mon message →';
    btn.disabled = false;
  });
}

// ── MENTIONS LÉGALES / CONFIDENTIALITÉ ──────────────
function showLegal(type) {
  const content = {
    mentions: '<h2 style="font-size:24px;color:var(--bleu);margin-bottom:16px">Mentions légales</h2>'
      + '<p><strong>Éditeur :</strong> Association Cannes Échecs<br>3 Avenue du Petit Juas — 06400 Cannes<br>Email : <a href="mailto:info@cannes-echecs.fr" style="color:inherit">info@cannes-echecs.fr</a> · Tél : <a href="tel:+33493394139" style="color:inherit">04 93 39 41 39</a></p>'
      + '<p style="margin-top:12px"><strong>Hébergeur :</strong> GitHub Pages (Microsoft)</p>'
      + '<p style="margin-top:12px"><strong>Directeur de publication :</strong> La Présidente de l\'association</p>'
      ,
    confidentialite: '<h2 style="font-size:24px;color:var(--bleu);margin-bottom:16px">Confidentialité</h2>'
      + '<p>Ce site ne collecte aucune donnée personnelle sans votre consentement. Les données saisies dans le formulaire de contact sont utilisées uniquement pour répondre à votre demande.</p>'
      + '<p style="margin-top:12px">Conformément au RGPD, vous disposez d\'un droit d\'accès, de rectification et de suppression de vos données en contactant : <a href="mailto:info@cannes-echecs.fr" style="color:var(--bleu)">info@cannes-echecs.fr</a></p>'
  };
  const overlay = document.createElement('div');
  overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9998;display:flex;align-items:center;justify-content:center;padding:20px';
  overlay.innerHTML = '<div style="background:#fff;border-radius:16px;padding:40px;max-width:560px;width:100%;position:relative;max-height:80vh;overflow-y:auto">'
    + '<button onclick="this.closest(\'[style]\').remove()" style="position:absolute;top:16px;right:16px;background:none;border:none;font-size:24px;cursor:pointer;color:var(--muted)">×</button>'
    + content[type]
    + '</div>';
  overlay.addEventListener('click', function(e){ if(e.target===this) this.remove(); });
  document.body.appendChild(overlay);
}

// ── LIGHTBOX ────────────────────────────────────────
let currentGallery = [];
let lbIndex = 0;

function openLightbox(i) {
  lbIndex = i;
  lbUpdate();
  document.getElementById('lightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
  // Empile un état pour que le bouton "retour" (mobile/navigateur) ferme la photo
  history.pushState({lightbox:true}, '');
}
function lbClose() {
  // Si l'état lightbox est dans l'historique, revenir en arrière ferme via popstate
  if (history.state && history.state.lightbox) {
    history.back();
  } else {
    lbForceClose();
  }
}
function lbForceClose() {
  document.getElementById('lightbox').classList.remove('open');
  document.body.style.overflow = '';
}
// Bouton retour navigateur / téléphone → ferme la photo
window.addEventListener('popstate', function() {
  if (document.getElementById('lightbox').classList.contains('open')) lbForceClose();
});
function lbNav(dir) {
  lbIndex = (lbIndex + dir + currentGallery.length) % currentGallery.length;
  lbUpdate();
}
function lbUpdate() {
  document.getElementById('lb-img').src = currentGallery[lbIndex];
  document.getElementById('lb-counter').textContent = (lbIndex + 1) + ' / ' + currentGallery.length;
  const showNav = currentGallery.length > 1;
  document.querySelector('.lb-prev').style.display = showNav ? '' : 'none';
  document.querySelector('.lb-next').style.display = showNav ? '' : 'none';
}
// Fermer en cliquant sur le fond
document.getElementById('lightbox').addEventListener('click', function(e) {
  if (e.target === this) lbClose();
});
// Navigation clavier
document.addEventListener('keydown', function(e) {
  if (!document.getElementById('lightbox').classList.contains('open')) return;
  if (e.key === 'ArrowRight') lbNav(1);
  if (e.key === 'ArrowLeft')  lbNav(-1);
  if (e.key === 'Escape')     lbClose();
});

// Swipe tactile sur le lightbox (mobile)
let lbTouchX = null;
document.getElementById('lightbox').addEventListener('touchstart', function(e) {
  lbTouchX = e.touches[0].clientX;
}, {passive: true});
document.getElementById('lightbox').addEventListener('touchend', function(e) {
  if (lbTouchX === null) return;
  const dx = e.changedTouches[0].clientX - lbTouchX;
  if (Math.abs(dx) > 50) lbNav(dx < 0 ? 1 : -1); // swipe gauche = suivant
  lbTouchX = null;
}, {passive: true});

// ── AGENDA — masquage automatique des événements passés ──
// Lit le jour + le mois affichés dans la vignette date de chaque événement ;
// l'année est déduite de la SAISON (juil–déc → 1re année, jan–juin → 2e année).
function hideOldAgendaEvents(now) {
  now = now || new Date();
  var MOIS = {jan:0,fev:1,'fév':1,mar:2,avr:3,mai:4,juin:5,juil:6,aou:7,'aoû':7,sep:8,oct:9,nov:10,dec:11,'déc':11};
  var yrs = SAISON.match(/\d{4}/g) || [];
  var y1 = parseInt(yrs[0], 10) || now.getFullYear();
  var y2 = parseInt(yrs[1], 10) || y1 + 1;
  var visibles = 0;
  document.querySelectorAll('.agenda-event').forEach(function(ev) {
    var spans = ev.querySelectorAll('span');
    var day = parseInt(spans[0] && spans[0].textContent, 10);
    var key = (spans[1] ? spans[1].textContent : '').trim().toLowerCase().replace('.', '');
    var mo = MOIS[key] !== undefined ? MOIS[key] : MOIS[key.slice(0, 3)];
    if (isNaN(day) || mo === undefined) { visibles++; return; }
    var year = mo >= 6 ? y1 : y2;
    if (new Date(year, mo, day, 23, 59, 59) < now) {
      ev.dataset.past = '1';
      ev.style.display = 'none';
    } else { visibles++; }
  });
  var empty = document.getElementById('agenda-empty');
  if (empty && visibles === 0) empty.style.display = 'block';
}
hideOldAgendaEvents();

// ── FILTRES (Agenda + Archive Actualités) ────────────
document.querySelectorAll('.filtre-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    const label = this.textContent.trim().toLowerCase();
    // Agenda
    if (this.closest('#page-agenda')) {
      document.querySelectorAll('#page-agenda .filtre-btn').forEach(b => b.classList.remove('active-gold'));
      this.classList.add('active-gold');
      const agendaMap = { 'tous':null, 'tournois':'tournois', 'cours & formation':'formation', 'stages':'stages', 'soirées':'soirees', 'compétitions ffe':'competitions' };
      const cat = agendaMap[label] !== undefined ? agendaMap[label] : null;
      let visible = 0;
      document.querySelectorAll('.agenda-event').forEach(ev => {
        const show = (!cat || ev.dataset.cat === cat) && ev.dataset.past !== '1';
        ev.style.display = show ? '' : 'none';
        if (show) visible++;
      });
      const empty = document.getElementById('agenda-empty');
      if (empty) empty.style.display = visible === 0 ? 'block' : 'none';
      return;
    }
    // Actualités : catégorie active → recalcul du filtre combiné (catégorie + recherche)
    const container = this.closest('[class*="filtres"]');
    if (container) container.querySelectorAll('.filtre-btn').forEach(b => b.classList.remove('active','active-gold'));
    this.classList.add('active-gold');
    if (this.closest('#page-actualites')) applyArchiveFilters();
  });
});

// ── FILTRE COMBINÉ ACTUALITÉS (catégorie + recherche plein texte) ──
function applyArchiveFilters() {
  const grid = document.querySelector('#page-actualites .archive-grid');
  if (!grid) return;
  const activeBtn = document.querySelector('#page-actualites .filtre-btn.active-gold');
  const label = activeBtn ? activeBtn.textContent.trim().toLowerCase() : 'toutes';
  const archiveMap = { 'résultats':'resultats', 'formation':'formation', 'tournois':'tournois', 'scolaire':'scolaire', 'club':'club' };
  const cat = archiveMap[label] || null;
  const input = document.getElementById('actu-search');
  const q = input ? ceNormalize(input.value.trim()) : '';
  const terms = q.split(/\s+/).filter(Boolean);   // recherche ET : tous les mots présents
  let visible = 0;
  grid.querySelectorAll('.actu-card').forEach(card => {
    const okCat = !cat || card.dataset.cat === cat;
    const haystack = card.dataset.search || '';
    const okSearch = terms.every(t => haystack.indexOf(t) > -1);
    const show = okCat && okSearch;
    card.style.display = show ? '' : 'none';
    if (show) visible++;
  });
  const oldMsg = grid.parentElement.querySelector('.filtre-empty');
  if (oldMsg) oldMsg.remove();
  if (visible === 0) {
    const msg = document.createElement('p');
    msg.className = 'filtre-empty';
    msg.style.cssText = 'text-align:center;color:var(--muted);font-style:italic;padding:48px 0;font-size:15px';
    msg.textContent = q
      ? 'Aucun article ne correspond à votre recherche.'
      : 'Aucun article dans cette catégorie pour le moment.';
    grid.parentElement.insertBefore(msg, grid.nextSibling);
  }
}

// Recherche en direct
(function() {
  const input = document.getElementById('actu-search');
  if (input) input.addEventListener('input', applyArchiveFilters);
})();


// ── TABS (Activités + Tournois + toute page) ─────────
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    const container = this.closest('.page');
    if (!container) return;
    // Désactiver tous les boutons
    container.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('tab-active'));
    // Masquer tous les panels (retire style inline + classe)
    container.querySelectorAll('.tab-panel').forEach(p => {
      p.classList.remove('tab-panel-active');
      p.style.display = 'none';
    });
    // Activer le bouton cliqué
    this.classList.add('tab-active');
    // Afficher le panel correspondant
    const target = container.querySelector('#tab-' + this.dataset.tab);
    if (target) {
      target.style.display = '';
      target.classList.add('tab-panel-active');
    }
    // Mettre à jour le hash pour permettre le partage de lien direct
    try {
      const pid = container.id.replace('page-', '');
      history.replaceState(null, '', location.pathname + location.search + '#' + pid + '-' + this.dataset.tab);
    } catch(e) {}
  });
});

// ── DROPDOWN — SUPPORT TACTILE / CLICK ──────────────
(function() {
  function closeAll() {
    document.querySelectorAll('.nav-item').forEach(function(i) {
      var d = i.querySelector('.dropdown');
      if (d) { d.style.opacity = ''; d.style.visibility = ''; d.style.transform = ''; }
    });
  }
  document.querySelectorAll('.nav-item').forEach(function(item) {
    var dd = item.querySelector('.dropdown');
    if (!dd) return;
    var btn = item.querySelector('.nav-link');
    if (!btn) return;
    btn.addEventListener('click', function(e) {
      var isOpen = dd.style.visibility === 'visible';
      closeAll();
      if (!isOpen) {
        dd.style.opacity = '1';
        dd.style.visibility = 'visible';
        dd.style.transform = 'translateY(0)';
        e.stopPropagation();
      }
    });
    dd.querySelectorAll('a').forEach(function(a) {
      a.addEventListener('click', function() { closeAll(); });
    });
  });
  document.addEventListener('click', function(e) {
    if (!e.target || typeof e.target.closest !== 'function' || !e.target.closest('.nav-item')) closeAll();
  });
})();

// ── HERO WIDGET ─────────────────────────────────────
const HERO_WIDGETS = {
  fij: () => `
    <div class="fij-hero-card-label">♟ Tournois d'Échecs · FIJ 2027</div>
    <h3>Festival International <br>des Jeux — Cannes</h3>
    <div class="cd-title">Ouverture des inscriptions dans</div>
    <div class="cd-row">
      <div class="cd-item"><span class="cd-num" id="h-cd-j">--</span><span class="cd-label">Jours</span></div>
      <div class="cd-sep">:</div>
      <div class="cd-item"><span class="cd-num" id="h-cd-h">--</span><span class="cd-label">Heures</span></div>
      <div class="cd-sep">:</div>
      <div class="cd-item"><span class="cd-num" id="h-cd-m">--</span><span class="cd-label">Min</span></div>
    </div>
    <button class="btn btn-gold btn-full btn-sm" onclick="goTo('fij')">Découvrir le FIJ 2027 →</button>
    <div class="fij-hero-stats">
      <div class="fij-hs"><div class="n">100 000+</div><div class="l">visiteurs FIJ</div></div>
      <div class="fij-hs"><div class="n">A · B · C</div><div class="l">tournois par Elo</div></div>
    </div>`,
  rentree: () => `
    <div class="fij-hero-card-label">🍂 Saison ${SAISON} · Inscriptions ouvertes</div>
    <h3 style="font-size:22px">C'est la rentrée à Cannes Échecs !</h3>
    <div class="cd-title" style="margin-bottom:10px">Reprise des cours</div>
    <div style="font-size:17px;font-weight:700;color:var(--gold-pale);margin-bottom:6px">Mercredi 9 septembre 2026</div>
    <div style="font-size:12px;color:rgba(255,255,255,.55);margin-bottom:18px;line-height:1.7">Pitchounets · Jeunes · Adultes débutants & confirmés<br>Cours le mardi soir et le mercredi</div>
    <button class="btn btn-gold btn-full btn-sm" onclick="goTo('adhesion')">S'inscrire maintenant →</button>
    <div class="fij-hero-stats">
      <div class="fij-hs"><div class="n">200+</div><div class="l">membres</div></div>
      <div class="fij-hs"><div class="n">Dès 60€</div><div class="l">/saison</div></div>
    </div>`,
  pico: () => `
    <div class="fij-hero-card-label">♟ PICO · Tournoi mensuel homologué</div>
    <h3 style="font-size:22px">Prochain PICO<br>Cannes Échecs</h3>
    <div class="cd-title" style="margin-bottom:10px">Prochaine date</div>
    <div style="font-size:17px;font-weight:700;color:var(--gold-pale);margin-bottom:6px;cursor:pointer" onclick="goTo('agenda')">Voir l'agenda →</div>
    <div style="font-size:12px;color:rgba(255,255,255,.55);margin-bottom:18px;line-height:1.7">Tournoi homologué FFE · Tous niveaux · Dès 13h30<br>Inscription via HelloAsso</div>
    <button class="btn btn-gold btn-full btn-sm" onclick="goTo('tournois')">Voir les PICO →</button>
    <div class="fij-hero-stats">
      <div class="fij-hs"><div class="n">9</div><div class="l">dates / saison</div></div>
      <div class="fij-hs"><div class="n">13h30</div><div class="l">début</div></div>
    </div>`,
  paques: () => `
    <div class="fij-hero-card-label">🐣 Tournoi Open · Pâques 2027</div>
    <h3 style="font-size:22px">Open de Pâques<br>Cannes Échecs</h3>
    <div class="cd-title" style="margin-bottom:10px">Date du tournoi</div>
    <div style="font-size:17px;font-weight:700;color:var(--gold-pale);margin-bottom:6px;cursor:pointer" onclick="goTo('agenda')">Voir l'agenda →</div>
    <div style="font-size:12px;color:rgba(255,255,255,.55);margin-bottom:18px;line-height:1.7">Tournoi open homologué FFE · Tous niveaux<br>Prix garantis · Inscription HelloAsso</div>
    <button class="btn btn-gold btn-full btn-sm" onclick="haOpen(HELLOASSO.paques,'_blank','noopener,noreferrer')">S'inscrire sur HelloAsso →</button>
    <div class="fij-hero-stats">
      <div class="fij-hs"><div class="n">Open</div><div class="l">homologué FFE</div></div>
      <div class="fij-hs"><div class="n">Pâques</div><div class="l">2027</div></div>
    </div>`,
  qualification: () => `
    <div class="fij-hero-card-label">🏅 Championnats · Qualification</div>
    <h3 style="font-size:22px">Qualifications<br>en cours !</h3>
    <div class="cd-title" style="margin-bottom:10px">Prochaine échéance</div>
    <div style="font-size:17px;font-weight:700;color:var(--gold-pale);margin-bottom:6px;cursor:pointer" onclick="goTo('agenda')">Voir l'agenda →</div>
    <div style="font-size:12px;color:rgba(255,255,255,.55);margin-bottom:18px;line-height:1.7">Championnats départementaux & régionaux<br>Sélection équipes — Top 16 · Top 12 Féminin</div>
    <button class="btn btn-gold btn-full btn-sm" onclick="goTo('tournois')">Voir les tournois →</button>
    <div class="fij-hero-stats">
      <div class="fij-hs"><div class="n">FFE</div><div class="l">homologué</div></div>
      <div class="fij-hs"><div class="n">Top 16</div><div class="l">Top 12 Féminin</div></div>
    </div>`,
  actu: (d = {}) => `
    <div class="fij-hero-card-label">📰 ${d.label || 'À la une'}</div>
    <h3 style="font-size:20px;line-height:1.35">${d.titre || 'Titre de l\'actualité à mettre en avant'}</h3>
    <div style="font-size:13px;color:rgba(255,255,255,.6);margin:12px 0 8px;line-height:1.65">${d.desc || 'Courte description de l\'actualité sur deux ou trois lignes.'}</div>
    <div style="font-size:11px;font-family:\'Montserrat\',sans-serif;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--gold);margin-bottom:16px">${d.date || 'Janvier 2026'}</div>
    <button class="btn btn-gold btn-full btn-sm" onclick="goTo('${d.lien || 'actualites'}')">${d.cta || 'Lire l\'article →'}</button>`
};

function setHeroWidget(mode, data) {
  const w = document.getElementById('hero-widget');
  if (!w || !HERO_WIDGETS[mode]) return;
  w.innerHTML = HERO_WIDGETS[mode](data);
  updateCountdowns();
  try { localStorage.setItem('ace_widget', mode); } catch(e) {}
}

// ── PANNEAU ADMIN WIDGET (visible seulement avec ?admin dans l'URL) ──

// ── HORAIRES ──────────────────────────────────────────────────────────────────
function renderHoraires() {
  var tbody = document.getElementById('horaires-tbody');
  if (!tbody) return;
  tbody.innerHTML = HORAIRES.map(function(h, i) {
    var bg = i % 2 === 1 ? ';background:var(--ivoire)' : '';
    if (h.ferme) return '<tr style="border-bottom:1px solid var(--border)' + bg + '">'
      + '<th scope="row" style="padding:12px 16px;font-weight:600;color:#9ca3af;font-style:italic;text-align:left">' + h.jour + '</th>'
      + '<td colspan="2" style="padding:12px 16px;color:#9ca3af;font-style:italic">' + h.detail + '</td></tr>';
    var det = h.highlight
      ? '<strong style="color:var(--bleu)">' + h.detail + '</strong>'
      : h.detail;
    return '<tr style="border-bottom:1px solid var(--border)' + bg + '">'
      + '<th scope="row" style="padding:12px 16px;font-weight:600;color:var(--bleu);text-align:left">' + h.jour + '</th>'
      + '<td style="padding:12px 16px">' + h.h + '</td>'
      + '<td style="padding:12px 16px;color:var(--muted)">' + det + '</td></tr>';
  }).join('');
}

// ── ÉQUIPE ────────────────────────────────────────────────────────────────────
function renderEquipe() {
  var grid = document.getElementById('equipe-grid');
  if (!grid) return;
  grid.innerHTML = EQUIPE.map(function(m) {
    var av = (m.avatar && /^(https?:|photos\/)/.test(m.avatar))
      ? '<img src="' + m.avatar + '" class="team-photo" loading="lazy" decoding="async" alt="' + m.nom + '">'
      : '<div class="team-avatar">' + m.avatar + '</div>';
    return '<div class="team-card">' + av
      + '<div class="team-name">' + m.nom + '</div>'
      + '<div class="team-role">' + m.role + '</div></div>';
  }).join('');
}

// ── SAISON — injection automatique dans tous les nœuds texte HTML ─────────────
function renderSaison() {
  var re = /2026[–—-]2027/g;
  var walker = document.createTreeWalker(
    document.body,
    NodeFilter.SHOW_TEXT,
    { acceptNode: function(n) {
        var tag = n.parentElement && n.parentElement.tagName;
        return (tag === 'SCRIPT' || tag === 'STYLE') ? NodeFilter.FILTER_SKIP : NodeFilter.FILTER_ACCEPT;
      }
    },
    false
  );
  var node, nodes = [];
  while ((node = walker.nextNode())) nodes.push(node);
  nodes.forEach(function(n) {
    if (re.test(n.textContent)) { re.lastIndex = 0; n.textContent = n.textContent.replace(/2026[–—-]2027/g, SAISON); }
    re.lastIndex = 0;
  });
}

// ── INIT ────────────────────────────────────────────
renderFijInscrits();
renderHoraires();
renderEquipe();
renderSaison();
updateCountdowns();
setInterval(updateCountdowns, 1000);

// ── ACCESSIBILITÉ — liens onclick sans href ──────────
document.querySelectorAll('a[onclick]:not([href])').forEach(function(a) {
  a.setAttribute('tabindex', '0');
  a.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); a.click(); }
  });
});


// ═══════════════════════════════════════════════════════════
// WORDPRESS — NAVIGATION MULTI-PAGES (remplace le routeur SPA)
// CE_URLS, CE_HERO_MODE et les données ACF sont injectés par
// functions.php (wp_add_inline_script, avant ce fichier).
// ═══════════════════════════════════════════════════════════
function goTo(id) {
  if (window.CE_URLS && CE_URLS[id]) window.location.href = CE_URLS[id];
}
function goToTab(page, tab) {
  if (window.CE_URLS && CE_URLS[page]) window.location.href = CE_URLS[page] + (tab ? '#' + page + '-' + tab : '');
}
function goToArticle(slug) {
  if (window.CE_URLS && CE_URLS.actualites) window.location.href = CE_URLS.actualites + slug + '/';
}
function goToContactSujet(sujet) {
  if (window.CE_URLS && CE_URLS.contact) window.location.href = CE_URLS.contact + '#sujet-' + sujet;
}
function renderHomeActus() {}   // rendu côté PHP — front-page.php
function renderArchiveGrid() {} // rendu côté PHP — archive-actualite.php

// Lien de nav actif selon l'URL courante
(function() {
  if (!window.CE_URLS) return;
  var path = location.pathname;
  var map = { club:'nav-club', actualites:'nav-club', organigramme:'nav-club', horaires:'nav-club',
              adhesion:'nav-club', contact:'nav-club', activites:'nav-activites', tournois:'nav-tournois',
              fij:'nav-fij', partenaires:'nav-partenaires', agenda:'nav-agenda' };
  Object.keys(map).forEach(function(slug) {
    if (path.indexOf('/' + slug) === 0) {
      var el = document.getElementById(map[slug]);
      if (el) el.classList.add('active');
    }
  });
})();

// Hash à l'arrivée : #page-onglet (onglets) ou #sujet-xxx (objet contact)
(function() {
  var hash = location.hash.replace('#', '');
  if (!hash) return;
  if (hash.indexOf('sujet-') === 0) {
    var s = document.getElementById('cf-sujet');
    if (s) s.value = hash.slice(6);
    return;
  }
  var i = hash.indexOf('-');
  if (i > 0) {
    var btn = document.querySelector('[data-tab="' + hash.slice(i + 1) + '"]');
    if (btn) btn.click();
  }
})();

// Widget héro — mode choisi dans WP admin (Réglages CE → Infos Club)
(function() {
  if (!document.getElementById('hero-widget')) return;
  var mode = window.CE_HERO_MODE;
  if (mode && typeof HERO_WIDGETS !== 'undefined' && HERO_WIDGETS[mode]) { setHeroWidget(mode, window.CE_HERO_DATA); return; }
  var m = new Date().getMonth() + 1;
  setHeroWidget(m >= 6 && m <= 8 ? 'rentree' : 'fij');
})();

// Galerie d'article (single-actualite.php) + bouton de partage natif
(function() {
  if (window.CE_GALLERY && window.CE_GALLERY.length) currentGallery = window.CE_GALLERY;
  var bn = document.getElementById('share-native');
  if (bn && navigator.share) {
    bn.style.display = 'inline-flex';
    var bf = document.getElementById('share-fb'), bt = document.getElementById('share-tw');
    if (bf) bf.style.display = 'none';
    if (bt) bt.style.display = 'none';
  }
})();
