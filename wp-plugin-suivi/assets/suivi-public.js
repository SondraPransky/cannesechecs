/**
 * Suivi tournoi — affichage public (shortcode [suivi_tournoi]).
 * Trois vues : Tournois, En direct, Résultats. Rafraîchi toutes les 2 minutes.
 * Aucune dépendance ; tout le texte venant des données est échappé.
 */
(function () {
  'use strict';

  var STATUTS = [
    { cle: 'en_cours', titre: 'En cours' },
    { cle: 'a_venir', titre: 'À venir' },
    { cle: 'termine', titre: 'Terminés récemment' },
  ];
  // Flèche « lien externe » forcée en texte (sous Windows, ↗ seul s'affiche en emoji).
  var FLECHE = '↗︎';
  var RES = { gagne: '1', nulle: '½', perdu: '0' };
  var RES_LONG = { gagne: 'Victoire', nulle: 'Nulle', perdu: 'Défaite' };
  var LIENS = { classement: 'Classement', grille: 'Grille', ronde: 'Ronde', participants: 'Participants', fiche: 'Fiche du tournoi', joueur: 'Fiche du joueur' };

  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }
  function pts(p) {
    if (p == null) return '';
    var e = Math.floor(p), d = Math.round((p - e) * 2) === 1;
    return (e === 0 && d ? '' : String(e)) + (d ? '½' : '');
  }
  function jour(iso) {
    if (!iso) return '';
    var d = new Date(iso.length === 10 ? iso + 'T12:00:00Z' : iso);
    return d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
  }
  function periode(t) {
    if (t.dateDebut && t.dateFin && t.dateDebut !== t.dateFin) return jour(t.dateDebut) + ' – ' + jour(t.dateFin);
    if (t.dateFin) return (t.dateDebut ? '' : 'jusqu’au ') + jour(t.dateFin);
    return jour(t.dateDebut);
  }
  function ilya(iso) {
    if (!iso) return 'jamais';
    var m = Math.round((Date.now() - new Date(iso).getTime()) / 60000);
    if (m < 1) return 'à l’instant';
    if (m < 60) return 'il y a ' + m + ' min';
    var h = Math.round(m / 60);
    return h < 24 ? 'il y a ' + h + ' h' : 'le ' + jour(iso);
  }
  function place(p, n) {
    if (p == null) return '';
    return Math.round(p) + (Math.round(p) === 1 ? 'er' : 'e') + (n ? ' sur ' + n : '');
  }
  function couleur(c) {
    if (!c) return '';
    return '<span class="sd-coul sd-coul-' + (c === 'blancs' ? 'b' : 'n') + '" title="' + (c === 'blancs' ? 'Blancs' : 'Noirs') + '">' + (c === 'blancs' ? 'B' : 'N') + '</span>';
  }
  // --- Mise en forme des noms (affichage seulement, les données restent brutes) ---
  var PETITS = /^(de|du|des|la|le|les|sur|sous|en|et|aux|au|lès|lez)$/;
  function cap(m) { return m.charAt(0).toUpperCase() + m.slice(1); }

  // « SALON DE PROVENCE » → « Salon-de-Provence », « LE CANNET » → « Le Cannet ».
  // Communes françaises : traits d'union entre les mots, sauf après l'article de tête.
  function joliVille(v) {
    if (!v) return '';
    var mots = String(v).toLowerCase().trim().split(/[\s-]+/);
    var tete = '';
    if (mots.length > 1 && /^(le|la|les)$/.test(mots[0])) tete = cap(mots.shift()) + ' ';
    return tete + mots.map(function (m, i) {
      if (i > 0 && PETITS.test(m)) return m;
      return m.replace(/^(l|d)'(.)/, function (x, a, b) { return (i > 0 ? a : a.toUpperCase()) + "'" + b.toUpperCase(); })
        .replace(/^./, function (c) { return c.toUpperCase(); });
    }).join('-');
  }

  // Mot tout en majuscules → « Mot » (sauf chiffres romains et sigles courts).
  function adoucir(m, tout) {
    if (!/[A-ZÀ-Ý]{2}/.test(m) || /[a-zà-ÿ]/.test(m)) return m;
    if (/^[IVXLC]+\.?$/.test(m)) return m;
    if (!tout && m.replace(/[^A-ZÀ-Ý]/g, '').length < 5) return m;
    return m.toLowerCase().replace(/(^|[-'’.(])([a-zà-ÿ])/g, function (x, a, b) { return a + b.toUpperCase(); });
  }

  var MOIS_FR = 'janvier|février|fevrier|mars|avril|mai|juin|juillet|août|aout|septembre|octobre|novembre|décembre|decembre';
  function joliNom(n) {
    if (!n) return '';
    var s = String(n).trim();
    // Reste anglais accolé aux noms traduits : « … blitz Blitz_Women Champ. 2026 ».
    s = s.replace(/\s+\S*_\S*.*$/, '');
    // Date dans le nom (déjà affichée sous le titre) : « 11 novembre 26 ».
    s = s.replace(new RegExp('\\s+\\d{1,2}\\s+(' + MOIS_FR + ')\\s+\\d{2,4}\\b', 'i'), '');
    // Groupe en lettre isolée à la fin : « … A » → « … – Tournoi A ».
    s = s.replace(/\s+([A-F])$/, ' – Tournoi $1');
    // Segment répété : « Annemasse 2026 - MAITRES - Annemasse 2026 ».
    var seg = s.split(/\s+[-–]\s+/);
    if (seg.length > 2 && seg[0].toLowerCase() === seg[seg.length - 1].toLowerCase()) s = seg.slice(0, -1).join(' - ');
    // Majuscules : nom entier en capitales → tout adoucir ; sinon seulement les longs mots criés.
    var tout = !/[a-zà-ÿ]/.test(s);
    s = s.split(' ').map(function (m, i) {
      var r = adoucir(m, tout);
      return tout && i > 0 && PETITS.test(r.toLowerCase()) ? r.toLowerCase() : r;
    }).join(' ');
    return s;
  }

  function adv(a) {
    if (!a || !a.nom) return '<span class="sd-muted">exempt</span>';
    return esc(a.nom) + (a.elo ? ' <span class="sd-elo">' + Math.round(a.elo) + '</span>' : '');
  }

  // --- Résultats regroupés par jour ---
  function local(iso) { return new Date(iso); }
  function cleJour(iso) { var d = local(iso); return d.getFullYear() + '-' + d.getMonth() + '-' + d.getDate(); }
  function titreJour(iso) {
    var d = local(iso), auj = new Date(), hier = new Date(auj.getTime() - 864e5);
    if (cleJour(iso) === cleJour(auj.toISOString())) return 'Aujourd’hui';
    if (cleJour(iso) === cleJour(hier.toISOString())) return 'Hier';
    var o = { weekday: 'long', day: 'numeric', month: 'long' };
    if (d.getFullYear() !== auj.getFullYear()) o.year = 'numeric';
    return cap(d.toLocaleDateString('fr-FR', o));
  }
  function heure(iso) {
    var m = Math.round((Date.now() - local(iso).getTime()) / 60000);
    if (m >= 0 && m < 60) return m < 1 ? 'à l’instant' : 'il y a ' + m + ' min';
    var d = local(iso);
    return d.getHours() + ' h ' + String(d.getMinutes()).padStart(2, '0');
  }
  function bilan(evs) {
    var n = { gagne: 0, nulle: 0, perdu: 0 };
    evs.forEach(function (e) { if (n[e.resultat] != null) n[e.resultat]++; });
    return [[n.gagne, 'victoire'], [n.nulle, 'nulle'], [n.perdu, 'défaite']]
      .filter(function (x) { return x[0]; })
      .map(function (x) { return x[0] + ' ' + x[1] + (x[0] > 1 ? 's' : ''); }).join(', ');
  }

  function Suivi(racine) {
    var cfg = JSON.parse(racine.querySelector('.suivi-config').textContent);
    this.racine = racine;
    this.api = cfg.api;
    this.d = cfg.donnees;
    this.vue = 'tournois';
    this.ouverts = {};
    this.filMax = 30;
    var self = this;
    racine.addEventListener('click', function (ev) {
      var b = ev.target.closest('[data-vue],[data-ouvrir],[data-plus]');
      if (!b) return;
      if (b.dataset.vue) { self.vue = b.dataset.vue; self.filMax = 30; }
      if (b.dataset.plus) { self.filMax += 30; }
      if (b.dataset.ouvrir) { self.ouverts[b.dataset.ouvrir] = !self.ouverts[b.dataset.ouvrir]; }
      self.rendre();
    });
    this.rendre();
    setInterval(function () { self.recharger(); }, 120000);
  }

  Suivi.prototype.recharger = function () {
    var self = this;
    fetch(this.api, { credentials: 'omit' })
      .then(function (r) { return r.ok ? r.json() : null; })
      .then(function (d) { if (d) { self.d = d; self.rendre(); } })
      .catch(function () {});
  };

  Suivi.prototype.enJeu = function () {
    return this.d.suivi.filter(function (e) { return e.partie && e.partie.enCours; });
  };

  Suivi.prototype.rendre = function () {
    var d = this.d, n = this.enJeu().length;
    var onglets = [['tournois', 'Tournois'], ['direct', 'En direct' + (n ? ' <span class="sd-badge">' + n + '</span>' : '')], ['fil', 'Résultats']];
    var h = '<div class="sd-tete"><div class="sd-vues" role="group" aria-label="Affichage">';
    for (var i = 0; i < onglets.length; i++) {
      var o = onglets[i];
      h += '<button type="button" class="sd-vue" data-vue="' + o[0] + '" aria-pressed="' + (this.vue === o[0]) + '">' + o[1] + '</button>';
    }
    h += '</div><p class="sd-maj">' + d.joueurs + (d.joueurs > 1 ? ' joueurs suivis' : ' joueur suivi') + (d.misAJour ? ' · mis à jour ' + esc(ilya(d.misAJour)) : '') + '</p></div>';
    h += this.vue === 'direct' ? this.vueDirect() : this.vue === 'fil' ? this.vueFil() : this.vueTournois();
    this.racine.innerHTML = h;
  };

  Suivi.prototype.vueTournois = function () {
    var par = {}, ordre = [];
    this.d.suivi.forEach(function (e) {
      var r = e.tournoi.ref;
      if (!par[r]) { par[r] = []; ordre.push(r); }
      par[r].push(e);
    });
    if (!ordre.length) return '<p class="sd-vide">Aucun tournoi en ce moment pour nos joueurs.</p>';
    var self = this, h = '';
    STATUTS.forEach(function (s) {
      var refs = ordre.filter(function (r) { return par[r][0].statut === s.cle; });
      if (!refs.length) return;
      h += '<h3 class="sd-section">' + s.titre + ' <span>' + refs.length + '</span></h3><div class="sd-cartes">';
      refs.forEach(function (r) { h += self.carte(r, par[r]); });
      h += '</div>';
    });
    return h;
  };

  Suivi.prototype.carte = function (ref, eng) {
    var t = eng[0].tournoi, self = this;
    var ronde = Math.max.apply(null, eng.map(function (e) { return e.rondeJouee || 0; }));
    var nb = eng[0].nbRondes;
    var meta = [esc(joliVille(t.ville)), esc(periode(t)), ronde && nb ? 'ronde ' + ronde + '/' + nb : ''].filter(Boolean);
    if (eng[0].source !== 'ffe') meta.push('<span class="sd-src">chess-results</span>');
    var h = '<article class="sd-carte' + (eng.some(function (e) { return e.partie && e.partie.enCours; }) ? ' sd-live' : '') + '">';
    h += '<div class="sd-carte-h"><h4' + (t.nomOriginal ? ' title="' + esc(t.nomOriginal) + '"' : '') + '>' + esc(joliNom(t.nom)) + '</h4><p class="sd-meta">' + meta.join(' · ') + '</p></div>';

    var rienJoue = eng.every(function (e) { return !(e.rondes || []).length && !e.partie && e.points == null; });
    if (rienJoue) {
      h += '<p class="sd-cl-tete sd-cl-un"><span>Engagés</span></p><ul class="sd-cl">';
      eng.forEach(function (e) { h += '<li class="sd-cl-ligne sd-cl-un"><span class="sd-cl-nom"><span class="sd-nom">' + esc(e.joueur) + '</span></span></li>'; });
      return h + '</ul><p class="sd-cl-vide">Les rondes s’afficheront dès le premier appariement.</p></article>';
    }

    h += '<p class="sd-cl-tete" aria-hidden="true"><span>Joueur</span><span>Place</span><span>Points</span><span></span></p><ul class="sd-cl">';
    eng.forEach(function (e, k) {
      var id = ref + ':' + k, ouvert = !!self.ouverts[id];
      var detail = (e.rondes || []).length || (e.partie && !e.partie.resultat);
      var live = e.partie && e.partie.enCours ? '<span class="sd-joue">joue la ronde ' + esc(e.partie.ronde) + '</span>' : '';
      var place = e.place != null ? '<b>' + Math.round(e.place) + (Math.round(e.place) === 1 ? 'er' : 'e') + '</b>' + (e.nbJoueurs ? 'sur ' + e.nbJoueurs : '') : '<b>–</b>';
      var score = e.points != null ? pts(e.points) + '<small>/' + (e.rondeJouee || 0) + '</small>' : '–';
      var cellules = '<span class="sd-cl-nom"><span class="sd-nom">' + esc(e.joueur) + '</span>' + live + '</span>'
        + '<span class="sd-cl-place">' + place + '</span>'
        + '<span class="sd-cl-score">' + score + '</span>';
      h += '<li>' + (detail
        ? '<button type="button" class="sd-cl-ligne" data-ouvrir="' + esc(id) + '" aria-expanded="' + ouvert + '" aria-label="' + esc(e.joueur) + ' : ' + (ouvert ? 'masquer' : 'voir') + ' les rondes">' + cellules + '<span class="sd-cl-chev" aria-hidden="true">▾</span></button>'
        : '<div class="sd-cl-ligne">' + cellules + '<span></span></div>');
      if (detail && ouvert) {
        h += '<div class="sd-detail"><table class="sd-rondes"><tbody>';
        (e.rondes || []).forEach(function (r) {
          var bye = !r.adversaire || !r.adversaire.nom;
          h += '<tr><td class="sd-r">R' + esc(r.numero) + '</td><td>' + (bye ? '' : couleur(r.couleur)) + '</td><td class="sd-res sd-res-' + esc(r.resultat || 'x') + '">' + (RES[r.resultat] || '–') + '</td><td>' + adv(r.adversaire) + '</td></tr>';
        });
        if (e.partie && !e.partie.resultat) {
          var pa = e.partie, byeP = !pa.adversaire || !pa.adversaire.nom;
          h += '<tr class="sd-encours"><td class="sd-r">R' + esc(pa.ronde) + '</td><td>' + (byeP ? '' : couleur(pa.couleur)) + '</td><td class="sd-res">…</td><td>' + adv(pa.adversaire)
            + ' <span class="sd-meta">' + (pa.echiquier ? '· table ' + esc(pa.echiquier) + ' ' : '') + '· ' + (pa.enCours ? 'en cours' : 'à jouer') + '</span></td></tr>';
        }
        h += '</tbody></table>' + (e.perf ? '<p class="sd-meta sd-perf">Performance ' + Math.round(e.perf) + '</p>' : '') + '</div>';
      }
      h += '</li>';
    });
    h += '</ul>';
    var liens = (eng[0].liens || []).filter(function (l) { return LIENS[l.cle]; });
    if (liens.length) {
      h += '<p class="sd-liens">' + liens.map(function (l) {
        return '<a href="' + esc(l.href) + '" target="_blank" rel="noopener">' + LIENS[l.cle] + (l.ronde ? ' ' + esc(l.ronde) : '') + ' ' + FLECHE + '</a>';
      }).join('') + '</p>';
    }
    return h + '</article>';
  };

  Suivi.prototype.vueDirect = function () {
    var jeu = this.enJeu();
    if (!jeu.length) return '<p class="sd-vide">Aucune partie en cours en ce moment. Les parties apparaissent ici dès que l’appariement est publié.</p>';
    var h = '<div class="sd-cartes">';
    jeu.forEach(function (e) {
      var p = e.partie;
      h += '<article class="sd-carte sd-live sd-partie"><p class="sd-meta">' + esc(joliNom(e.tournoi.nom)) + '</p>';
      var bye = !p.adversaire || !p.adversaire.nom;
      h += '<p class="sd-duel"><span class="sd-nom">' + esc(e.joueur) + '</span> ' + (bye ? '<span class="sd-contre">exempt</span>' : couleur(p.couleur) + ' <span class="sd-adv"><span class="sd-contre">contre</span> ' + adv(p.adversaire) + '</span>') + '</p>';
      h += '<p class="sd-meta">Ronde ' + p.ronde + (p.echiquier ? ' · table ' + p.echiquier : '') + (e.points != null ? ' · ' + pts(e.points) + ' pt avant la ronde' : '') + (p.pasEncoreCommencee ? ' · pas encore commencée' : '') + '</p></article>';
    });
    return h + '</div>';
  };

  Suivi.prototype.vueFil = function () {
    var f = (this.d.fil || []).slice().sort(function (a, b) { return a.a < b.a ? 1 : a.a > b.a ? -1 : 0; });
    if (!f.length) return '<p class="sd-vide">Les nouveaux résultats apparaîtront ici au fil des rondes.</p>';
    var montres = f.slice(0, this.filMax), jours = [], par = {};
    montres.forEach(function (ev) {
      var k = cleJour(ev.a);
      if (!par[k]) { par[k] = []; jours.push(k); }
      par[k].push(ev);
    });
    var h = '';
    jours.forEach(function (k) {
      var evs = par[k];
      h += '<h3 class="sd-section sd-jour">' + esc(titreJour(evs[0].a)) + ' <span>' + esc(bilan(evs)) + '</span></h3><ul class="sd-fil">';
      evs.forEach(function (ev) {
        var r = ev.resultat, quand = heure(ev.a);
        var duel = ev.adversaire && ev.adversaire.nom
          ? couleur(ev.couleur) + ' <span class="sd-contre">contre</span> ' + adv(ev.adversaire)
          : ' <span class="sd-contre">exempt</span>';
        h += '<li><span class="sd-tuile sd-res-' + esc(r) + '" role="img" aria-label="' + (RES_LONG[r] || 'Résultat') + '">' + (RES[r] || '–') + '</span>'
          + '<div class="sd-fil-txt"><p class="sd-fil-duel"><span class="sd-nom">' + esc(ev.joueur) + '</span> ' + duel + '</p>'
          + '<p class="sd-meta sd-fil-meta"><span class="sd-fil-tournoi" title="' + esc(joliNom(ev.tournoi)) + '">' + esc(joliNom(ev.tournoi)) + '</span>'
          + '<span class="sd-fil-ronde">&nbsp;· ronde ' + esc(ev.ronde) + '<span class="sd-fil-heure-m"> · ' + esc(quand) + '</span></span></p></div>'
          + '<span class="sd-quand">' + esc(quand) + '</span>'
          + (ev.lien
            ? '<a class="sd-fil-lien" href="' + esc(ev.lien) + '" target="_blank" rel="noopener" aria-label="Voir la ronde ' + esc(ev.ronde) + ' sur le site du tournoi (nouvel onglet)">' + FLECHE + '</a>'
            : '<span class="sd-fil-lien sd-fil-lien-vide" aria-hidden="true"></span>')
          + '</li>';
      });
      h += '</ul>';
    });
    if (f.length > montres.length) {
      h += '<p class="sd-plus-wrap"><button type="button" class="sd-plus" data-plus="1">Voir les résultats plus anciens <span>(' + (f.length - montres.length) + ')</span></button></p>';
    }
    return h;
  };

  function demarrer() {
    var els = document.querySelectorAll('[data-suivi]');
    for (var i = 0; i < els.length; i++) {
      if (!els[i].suivi) { els[i].suivi = new Suivi(els[i]); }
    }
  }
  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', demarrer); } else { demarrer(); }
})();
