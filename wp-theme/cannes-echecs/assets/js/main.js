// Cannes Échecs — main.js
// Les constantes de données (HELLOASSO, FIJ_*, ARTICLES, ARCHIVE_ORDER, EXTRAITS)
// sont injectées par PHP dans un <script> inline placé avant ce fichier.

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

function goToTab(page, tab) {
  goTo(page);
  if (!tab) return;
  const container = document.getElementById('page-' + page);
  if (!container) return;
  const btn = container.querySelector('[data-tab="' + tab + '"]');
  if (btn) btn.click();
}

// ── ROUTER ──────────────────────────────────────────
const pages = ['home','club','actualites','article','activites','horaires','adhesion','contact','tournois','fij','partenaires','agenda','organigramme'];
const PAGE_TITLES = {
  home:"Cannes Échecs — Club d'échecs · Côte d'Azur",
  club:'Le Club — Cannes Échecs',
  actualites:'Actualités — Cannes Échecs',
  article:'Article — Cannes Échecs',
  activites:'Nos Activités — Cannes Échecs',
  horaires:'Horaires & Tarifs — Cannes Échecs',
  adhesion:'Adhésion — Cannes Échecs',
  contact:'Contact — Cannes Échecs',
  tournois:'Tournois — Cannes Échecs',
  fij:'FIJ 2027 — Cannes Échecs',
  partenaires:'Partenaires — Cannes Échecs',
  agenda:'Agenda — Cannes Échecs',
  organigramme:'Organigramme — Cannes Échecs'
};

function goTo(id) {
  pages.forEach(p => {
    const el = document.getElementById('page-' + p);
    if (el) el.classList.remove('active');
  });
  const target = document.getElementById('page-' + id);
  if (target) {
    target.classList.add('active');
  } else {
    document.getElementById('page-home').classList.add('active');
  }
  window.scrollTo({top: 0, behavior: 'smooth'});
  document.title = PAGE_TITLES[id] || 'Cannes Échecs';
  updateNavActive(id);
}

function updateNavActive(id) {
  document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
  const map = {club:'nav-club',actualites:'nav-club',article:'nav-club',organigramme:'nav-club',horaires:'nav-club',adhesion:'nav-club',contact:'nav-club',activites:'nav-activites',tournois:'nav-tournois',fij:'nav-fij',partenaires:'nav-partenaires',agenda:'nav-agenda'};
  if (map[id]) {
    const el = document.getElementById(map[id]);
    if (el) el.classList.add('active');
  }
}

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
  var diff1 = FIJ_OPEN - now;
  if (diff1 > 0) {
    var d1=Math.floor(diff1/86400000), h1=Math.floor((diff1%86400000)/3600000), m1=Math.floor((diff1%3600000)/60000);
    ['h-cd-j','h-cd-h','h-cd-m'].forEach(function(id,i){var el=document.getElementById(id);if(el)el.textContent=pad([d1,h1,m1][i]);});
  }
  var diff2 = FIJ_DATE - now;
  if (diff2 > 0) {
    var d2=Math.floor(diff2/86400000),h2=Math.floor((diff2%86400000)/3600000),m2=Math.floor((diff2%3600000)/60000),s2=Math.floor((diff2%60000)/1000);
    ['f-cd-j','f-cd-h','f-cd-m','f-cd-s'].forEach(function(id,i){var el=document.getElementById(id);if(el)el.textContent=pad([d2,h2,m2,s2][i]);});
  }
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
    if (diff2 > 0) {
      var d2b=Math.floor(diff2/86400000),h2b=Math.floor((diff2%86400000)/3600000),m2b=Math.floor((diff2%3600000)/60000),s2b=Math.floor((diff2%60000)/1000);
      ['fij-cd-j','fij-cd-h','fij-cd-m','fij-cd-s'].forEach(function(id,i){var el=document.getElementById(id);if(el)el.textContent=pad([d2b,h2b,m2b,s2b][i]);});
    }
  } else if (state === 'encours') {
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

// ── HELPERS ARTICLES ────────────────────────────────
function catToFilter(cat) {
  if (cat.includes('Tournoi'))                                    return 'tournois';
  if (cat.includes('Scolaire'))                                   return 'scolaire';
  if (cat.includes('Vie du club') || cat.includes('Animation'))  return 'club';
  return 'resultats';
}

function buildCard(key, a, showExtrait) {
  const imgHtml = a.img
    ? '<div class="actu-img" style="background:#111;background-image:url(\'' + a.img + '\');background-size:cover;background-position:center"></div>'
    : '<div class="actu-img" style="background:' + a.bg + '">' + a.emoji + '</div>';
  const extraitHtml = (showExtrait && EXTRAITS[key])
    ? '<p class="actu-extrait">' + EXTRAITS[key] + '</p>'
    : '';
  return '<div class="actu-card" data-cat="' + catToFilter(a.cat) + '" onclick="goToArticle(\'' + key + '\')">'
    + imgHtml
    + '<div class="actu-body">'
    + '<div class="actu-cat">' + a.cat + '</div>'
    + '<h3 class="actu-title">' + a.title + '</h3>'
    + extraitHtml
    + '<div class="actu-footer"><span class="actu-date">' + a.date + '</span><span class="actu-lire">Lire →</span></div>'
    + '</div></div>';
}

function renderArchiveGrid() {
  const grid = document.querySelector('#page-actualites .archive-grid');
  if (!grid) return;
  grid.innerHTML = ARCHIVE_ORDER
    .filter(function(k) { return !!ARTICLES[k]; })
    .map(function(k)    { return buildCard(k, ARTICLES[k], true); })
    .join('');
}

function renderHomeActus() {
  const grid = document.getElementById('home-actus-grid');
  if (!grid) return;
  grid.innerHTML = ARCHIVE_ORDER
    .filter(function(k) { return !!ARTICLES[k]; })
    .slice(0, 4)
    .map(function(k)    { return buildCard(k, ARTICLES[k], false); })
    .join('');
}

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
    return '<div style="display:flex;align-items:center;gap:14px;padding:12px 16px;background:var(--ivoire);border-radius:10px;border-left:4px solid ' + o.border + '">'
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

function goToArticle(id) {
  const a = ARTICLES[id];
  if (!a) { goTo('article'); return; }
  const badge = document.getElementById('art-badge');
  const title = document.getElementById('art-title');
  const date  = document.getElementById('art-date');
  const cat   = document.getElementById('art-cat');
  if (badge) badge.textContent = a.badge;
  if (title) title.textContent = a.title;
  if (date)  date.innerHTML = '<span>📅</span> ' + a.date;
  if (cat)   cat.innerHTML  = '<span>🏷</span> ' + a.cat;
  const img = document.getElementById('art-img');
  if (img) {
    if (a.img) {
      img.style.background = '#111';
      img.style.backgroundImage = "url('" + a.img + "')";
      img.style.backgroundSize = 'cover';
      img.style.backgroundPosition = 'center';
      img.textContent = '';
    } else {
      img.style.background = a.bg;
      img.style.backgroundImage = '';
      img.textContent = a.emoji;
    }
  }
  const body = document.getElementById('art-body');
  if (body) body.innerHTML = a.body;
  currentGallery = a.gallery || [];
  const galleryEl = document.getElementById('art-gallery');
  if (galleryEl) {
    if (currentGallery.length) {
      const items = currentGallery.map(function(src, i) {
        return '<div class="art-gallery-item" onclick="openLightbox(' + i + ')">'
          + '<img src="' + src + '" alt="' + a.title + ' — photo ' + (i + 1) + '" loading="lazy"></div>';
      }).join('');
      galleryEl.innerHTML = '<p class="art-gallery-label">Photos</p>'
        + '<div class="art-gallery-grid">' + items + '</div>';
      galleryEl.style.display = '';
    } else {
      galleryEl.innerHTML = '';
      galleryEl.style.display = 'none';
    }
  }
  window._shareUrl   = 'https://cannes-echecs.fr/';
  window._shareTitle = a.title;
  window._shareText  = (EXTRAITS[id] || a.title) + ' — Cannes Échecs';
  const btnNative = document.getElementById('share-native');
  if (btnNative) btnNative.style.display = navigator.share ? 'inline-flex' : 'none';
  const others = ARCHIVE_ORDER.filter(function(k) { return k !== id && ARTICLES[k]; }).slice(0, 3);
  const rel = document.getElementById('art-related');
  if (rel) {
    rel.innerHTML = others.map(function(k) { return buildCard(k, ARTICLES[k], false); }).join('');
  }
  goTo('article');
  document.title = a.title + ' — Cannes Échecs';
}

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
  let ics = 'BEGIN:VCALENDAR' + CRLF + 'VERSION:2.0' + CRLF + 'PRODID:-//Cannes Echecs//FR' + CRLF
    + 'X-WR-CALNAME:Agenda Cannes Échecs' + CRLF + 'X-WR-TIMEZONE:Europe/Paris' + CRLF;
  events.forEach(function(e) {
    ics += 'BEGIN:VEVENT' + CRLF + 'DTSTART:' + e.start + CRLF + 'DTEND:' + e.end + CRLF
      + 'SUMMARY:' + e.summary + CRLF + 'DESCRIPTION:' + e.desc + CRLF + 'LOCATION:' + loc + CRLF + 'END:VEVENT' + CRLF;
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
  window.open(url, 'fb-share', 'width=600,height=400,noopener');
}
function shareTw() {
  const url = 'https://twitter.com/intent/tweet?url='
    + encodeURIComponent(window._shareUrl || 'https://cannes-echecs.fr/')
    + '&text=' + encodeURIComponent((window._shareTitle || '') + ' — Cannes Échecs ♟️');
  window.open(url, 'tw-share', 'width=600,height=400,noopener');
}
function shareNative() {
  if (!navigator.share) return;
  navigator.share({
    title: window._shareTitle || 'Cannes Échecs',
    text:  window._shareText  || '',
    url:   window._shareUrl   || 'https://cannes-echecs.fr/'
  }).catch(function() {});
}

// ── FORMULAIRE CONTACT ──────────────────────────────
function sendContact(e) {
  e.preventDefault();
  const nom     = document.getElementById('cf-nom');
  const email   = document.getElementById('cf-email');
  const message = document.getElementById('cf-message');
  const rgpd    = document.getElementById('cf-rgpd');
  const fb      = document.getElementById('cf-feedback');
  const errors = [];
  if (!nom.value.trim())     errors.push('Veuillez indiquer votre nom.');
  if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value))
    errors.push('Veuillez indiquer une adresse email valide.');
  if (!message.value.trim()) errors.push('Veuillez écrire un message.');
  if (!rgpd.checked)         errors.push('Veuillez accepter la politique de données.');
  if (errors.length) {
    fb.style.display = 'block';
    fb.style.background = '#fef2f2'; fb.style.border = '1px solid #fca5a5'; fb.style.color = '#b91c1c';
    fb.innerHTML = errors.map(function(e){ return '• ' + e; }).join('<br>');
    return;
  }
  const btn = e.target.querySelector('[type="submit"]');
  btn.textContent = 'Envoi en cours…';
  btn.disabled = true;
  fetch('https://formsubmit.co/ajax/info@cannes-echecs.fr', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({ name: nom.value.trim(), email: email.value.trim(), message: message.value.trim(), _subject: 'Message depuis cannes-echecs.fr' })
  }).then(function(response) {
    if (response.ok) {
      fb.style.display = 'block'; fb.style.background = '#f0fdf4'; fb.style.border = '1px solid #86efac'; fb.style.color = '#15803d';
      fb.textContent = '✓ Message envoyé ! Nous vous répondrons sous 48h à ' + email.value;
      e.target.reset();
    } else { throw new Error('Erreur serveur'); }
  }).catch(function() {
    fb.style.display = 'block'; fb.style.background = '#fef2f2'; fb.style.border = '1px solid #fca5a5'; fb.style.color = '#b91c1c';
    fb.innerHTML = 'L\'envoi a échoué. Contactez-nous directement : <a href="mailto:info@cannes-echecs.fr" style="color:var(--bleu)">info@cannes-echecs.fr</a>';
  }).finally(function() { btn.textContent = 'Envoyer mon message →'; btn.disabled = false; });
}

// ── MENTIONS LÉGALES ────────────────────────────────
function showLegal(type) {
  const content = {
    mentions: '<h2 style="font-size:24px;color:var(--bleu);margin-bottom:16px">Mentions légales</h2>'
      + '<p><strong>Éditeur :</strong> Association Cannes Échecs<br>3 Avenue du Petit Juas — 06400 Cannes<br>Email : info@cannes-echecs.fr · Tél : 04 93 39 41 39</p>'
      + '<p style="margin-top:12px"><strong>Hébergeur :</strong> o2switch</p>'
      + '<p style="margin-top:12px"><strong>Directeur de publication :</strong> La Présidente de l\'association</p>',
    confidentialite: '<h2 style="font-size:24px;color:var(--bleu);margin-bottom:16px">Confidentialité</h2>'
      + '<p>Ce site ne collecte aucune donnée personnelle sans votre consentement. Les données saisies dans le formulaire de contact sont utilisées uniquement pour répondre à votre demande.</p>'
      + '<p style="margin-top:12px">Conformément au RGPD, vous disposez d\'un droit d\'accès, de rectification et de suppression de vos données en contactant : info@cannes-echecs.fr</p>'
  };
  const overlay = document.createElement('div');
  overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9998;display:flex;align-items:center;justify-content:center;padding:20px';
  overlay.innerHTML = '<div style="background:#fff;border-radius:16px;padding:40px;max-width:560px;width:100%;position:relative;max-height:80vh;overflow-y:auto">'
    + '<button onclick="this.closest(\'[style]\').remove()" style="position:absolute;top:16px;right:16px;background:none;border:none;font-size:24px;cursor:pointer;color:var(--muted)">×</button>'
    + content[type] + '</div>';
  overlay.addEventListener('click', function(e){ if(e.target===this) this.remove(); });
  document.body.appendChild(overlay);
}

// ── LIGHTBOX ────────────────────────────────────────
let currentGallery = [];
let lbIndex = 0;

function openLightbox(i) {
  lbIndex = i; lbUpdate();
  document.getElementById('lightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
  history.pushState({lightbox:true}, '');
}
function lbClose() {
  if (history.state && history.state.lightbox) { history.back(); } else { lbForceClose(); }
}
function lbForceClose() {
  document.getElementById('lightbox').classList.remove('open');
  document.body.style.overflow = '';
}
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
document.getElementById('lightbox').addEventListener('click', function(e) { if (e.target === this) lbClose(); });
document.addEventListener('keydown', function(e) {
  if (!document.getElementById('lightbox').classList.contains('open')) return;
  if (e.key === 'ArrowRight') lbNav(1);
  if (e.key === 'ArrowLeft')  lbNav(-1);
  if (e.key === 'Escape')     lbClose();
});
let lbTouchX = null;
document.getElementById('lightbox').addEventListener('touchstart', function(e) { lbTouchX = e.touches[0].clientX; }, {passive: true});
document.getElementById('lightbox').addEventListener('touchend', function(e) {
  if (lbTouchX === null) return;
  const dx = e.changedTouches[0].clientX - lbTouchX;
  if (Math.abs(dx) > 50) lbNav(dx < 0 ? 1 : -1);
  lbTouchX = null;
}, {passive: true});

// ── FILTRES ─────────────────────────────────────────
document.querySelectorAll('.filtre-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    const label = this.textContent.trim().toLowerCase();
    if (this.closest('#page-agenda')) {
      document.querySelectorAll('#page-agenda .filtre-btn').forEach(b => b.classList.remove('active-gold'));
      this.classList.add('active-gold');
      const agendaMap = { 'tous':null, 'tournois':'tournois', 'cours & formation':'formation', 'stages':'stages', 'soirées':'soirees', 'compétitions ffe':'competitions' };
      const cat = agendaMap[label] !== undefined ? agendaMap[label] : null;
      let visible = 0;
      document.querySelectorAll('.agenda-event').forEach(ev => {
        const show = !cat || ev.dataset.cat === cat;
        ev.style.display = show ? '' : 'none';
        if (show) visible++;
      });
      const empty = document.getElementById('agenda-empty');
      if (empty) empty.style.display = visible === 0 ? 'block' : 'none';
      return;
    }
    const container = this.closest('[class*="filtres"]');
    if (container) container.querySelectorAll('.filtre-btn').forEach(b => b.classList.remove('active','active-gold'));
    this.classList.add('active-gold');
    const archiveGrid = document.querySelector('#page-actualites .archive-grid');
    if (!archiveGrid || !this.closest('#page-actualites')) return;
    const archiveMap = { 'résultats':'resultats', 'formation':'formation', 'tournois':'tournois', 'scolaire':'scolaire', 'club':'club' };
    const cat = archiveMap[label] || null;
    archiveGrid.querySelectorAll('.actu-card').forEach(card => {
      card.style.display = (!cat || card.dataset.cat === cat) ? '' : 'none';
    });
    const oldMsg = archiveGrid.parentElement.querySelector('.filtre-empty');
    if (oldMsg) oldMsg.remove();
    if (cat) {
      const visible = archiveGrid.querySelectorAll('.actu-card:not([style*="none"])').length;
      if (visible === 0) {
        const msg = document.createElement('p');
        msg.className = 'filtre-empty';
        msg.style.cssText = 'text-align:center;color:var(--muted);font-style:italic;padding:48px 0;font-size:15px';
        msg.textContent = 'Aucun article dans cette catégorie pour le moment.';
        archiveGrid.parentElement.insertBefore(msg, archiveGrid.nextSibling);
      }
    }
  });
});

// ── TABS ────────────────────────────────────────────
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    const container = this.closest('.page');
    if (!container) return;
    container.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('tab-active'));
    container.querySelectorAll('.tab-panel').forEach(p => {
      p.classList.remove('tab-panel-active');
      p.style.display = 'none';
    });
    this.classList.add('tab-active');
    const target = container.querySelector('#tab-' + this.dataset.tab);
    if (target) { target.style.display = ''; target.classList.add('tab-panel-active'); }
  });
});

// ── HERO WIDGET ─────────────────────────────────────
const HERO_WIDGETS = {
  fij: () => `
    <div class="fij-hero-card-label">♟ Tournois d'Échecs · FIJ 2027</div>
    <h3>Festival International<br>des Jeux — Cannes</h3>
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
    <div class="fij-hero-card-label">🍂 Saison 2026–2027 · Inscriptions ouvertes</div>
    <h3 style="font-size:22px">C'est la rentrée à Cannes Échecs !</h3>
    <div class="cd-title" style="margin-bottom:10px">Reprise des cours</div>
    <div style="font-size:17px;font-weight:700;color:var(--gold);margin-bottom:6px">Mercredi 9 septembre 2026</div>
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
    <div style="font-size:17px;font-weight:700;color:var(--gold);margin-bottom:6px">DATE À RENSEIGNER</div>
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
    <div style="font-size:17px;font-weight:700;color:var(--gold);margin-bottom:6px">DATE À RENSEIGNER</div>
    <div style="font-size:12px;color:rgba(255,255,255,.55);margin-bottom:18px;line-height:1.7">Tournoi open homologué FFE · Tous niveaux<br>Prix garantis · Inscription HelloAsso</div>
    <button class="btn btn-gold btn-full btn-sm" onclick="window.open(HELLOASSO.paques,'_blank','noopener,noreferrer')">S'inscrire sur HelloAsso →</button>
    <div class="fij-hero-stats">
      <div class="fij-hs"><div class="n">Open</div><div class="l">homologué FFE</div></div>
      <div class="fij-hs"><div class="n">Pâques</div><div class="l">2027</div></div>
    </div>`,
  qualification: () => `
    <div class="fij-hero-card-label">🏅 Championnats · Qualification</div>
    <h3 style="font-size:22px">Qualifications<br>en cours !</h3>
    <div class="cd-title" style="margin-bottom:10px">Prochaine échéance</div>
    <div style="font-size:17px;font-weight:700;color:var(--gold);margin-bottom:6px">DATE À RENSEIGNER</div>
    <div style="font-size:12px;color:rgba(255,255,255,.55);margin-bottom:18px;line-height:1.7">Championnats départementaux & régionaux<br>Sélection équipes — Top 16 · Top 12 Féminin</div>
    <button class="btn btn-gold btn-full btn-sm" onclick="goTo('tournois')">Voir les tournois →</button>
    <div class="fij-hero-stats">
      <div class="fij-hs"><div class="n">FFE</div><div class="l">homologué</div></div>
      <div class="fij-hs"><div class="n">Top 16</div><div class="l">Top 12 Féminin</div></div>
    </div>`,
  actu: (d = {}) => `
    <div class="fij-hero-card-label">📰 ${d.label || 'À la une'}</div>
    <h3 style="font-size:20px;line-height:1.35">${d.titre || "Titre de l'actualité à mettre en avant"}</h3>
    <div style="font-size:13px;color:rgba(255,255,255,.6);margin:12px 0 8px;line-height:1.65">${d.desc || "Courte description de l'actualité."}</div>
    <div style="font-size:11px;font-family:'Montserrat',sans-serif;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--gold);margin-bottom:16px">${d.date || 'Janvier 2026'}</div>
    <button class="btn btn-gold btn-full btn-sm" onclick="goTo('${d.lien || 'actualites'}')">${d.cta || "Lire l'article →"}</button>`
};

function setHeroWidget(mode, data) {
  const w = document.getElementById('hero-widget');
  if (!w || !HERO_WIDGETS[mode]) return;
  w.innerHTML = HERO_WIDGETS[mode](data);
  updateCountdowns();
  try { localStorage.setItem('ace_widget', mode); } catch(e) {}
}

// ── PANNEAU ADMIN (visible avec ?admin dans l'URL) ──
(function(){
  if (!location.search.includes('admin')) return;
  const LABELS = {
    auto:'🔄 Auto (par date)', fij:'★ FIJ 2027', rentree:'🍂 Rentrée',
    pico:'♟ PICO mensuel', paques:'🐣 Open Pâques', qualification:'🏅 Qualification', actu:'📰 Actualité'
  };
  const p = document.createElement('div');
  p.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:9999;background:#0d2240;border:1px solid rgba(201,168,76,.45);border-radius:14px;padding:16px 18px;box-shadow:0 8px 40px rgba(0,0,0,.6);min-width:200px;font-family:Montserrat,sans-serif';
  p.innerHTML = '<div style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(201,168,76,.85);margin-bottom:12px">🔧 Widget accueil</div>'
    + Object.entries(LABELS).map(([k,v]) =>
        `<button id="sw-${k}" onclick="selectWidget('${k}')" style="display:block;width:100%;text-align:left;background:transparent;border:none;color:rgba(255,255,255,.8);font-size:12px;font-family:Montserrat,sans-serif;padding:7px 10px;border-radius:7px;cursor:pointer;margin-bottom:2px">${v}</button>`
      ).join('')
    + '<hr style="border:none;border-top:1px solid rgba(255,255,255,.12);margin:12px 0">'
    + '<div style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(201,168,76,.85);margin-bottom:8px">🎟️ Inscrits FIJ</div>'
    + '<button id="sw-inscrits" onclick="toggleFijInscrits()" style="display:block;width:100%;text-align:left;background:transparent;border:none;color:rgba(255,255,255,.8);font-size:12px;font-family:Montserrat,sans-serif;padding:7px 10px;border-radius:7px;cursor:pointer;margin-bottom:2px"></button>'
    + '<div style="font-size:10px;color:rgba(255,255,255,.3);margin-top:10px;text-align:center">Accès admin</div>';
  document.body.appendChild(p);
  function updateFijInscritBtn() {
    var b = document.getElementById('sw-inscrits');
    if (!b) return;
    var off = false; try { off = localStorage.getItem('ace_fij_inscrits') === 'off'; } catch(e) {}
    b.textContent = off ? '🚫 Inscrits masqués' : '✅ Inscrits visibles';
    b.style.background = off ? 'rgba(220,60,60,.18)' : 'rgba(201,168,76,.18)';
  }
  updateFijInscritBtn();
  window.toggleFijInscrits = function() {
    var off = false; try { off = localStorage.getItem('ace_fij_inscrits') === 'off'; } catch(e) {}
    try { localStorage.setItem('ace_fij_inscrits', off ? 'on' : 'off'); } catch(e) {}
    renderFijInscrits(); updateFijInscritBtn();
  };
  window.selectWidget = function(mode) {
    const m = new Date().getMonth()+1;
    if (mode === 'auto') { try{localStorage.removeItem('ace_widget')}catch(e){} setHeroWidget(m>=6&&m<=8?'rentree':'fij'); }
    else { setHeroWidget(mode); }
    Object.keys(LABELS).forEach(k => {
      const b = document.getElementById('sw-'+k);
      if (b) b.style.background = k===mode ? 'rgba(201,168,76,.18)' : 'transparent';
    });
  };
})();

// ── INIT ────────────────────────────────────────────
renderArchiveGrid();
renderHomeActus();
renderFijInscrits();
(function(){
  let saved; try { saved = localStorage.getItem('ace_widget'); } catch(e) {}
  if (saved && HERO_WIDGETS[saved]) { setHeroWidget(saved); }
  else { const m = new Date().getMonth()+1; setHeroWidget(m>=6&&m<=8?'rentree':'fij'); }
})();
updateCountdowns();
setInterval(updateCountdowns, 1000);
goTo('home');
document.querySelectorAll('a[onclick]:not([href])').forEach(function(a) {
  a.setAttribute('tabindex', '0');
  a.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); a.click(); }
  });
});
