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
  function adv(a) {
    if (!a || !a.nom) return '<span class="sd-muted">exempt</span>';
    return esc(a.nom) + (a.elo ? ' <span class="sd-elo">' + Math.round(a.elo) + '</span>' : '');
  }

  function Suivi(racine) {
    var cfg = JSON.parse(racine.querySelector('.suivi-config').textContent);
    this.racine = racine;
    this.api = cfg.api;
    this.d = cfg.donnees;
    this.vue = 'tournois';
    this.ouverts = {};
    var self = this;
    racine.addEventListener('click', function (ev) {
      var b = ev.target.closest('[data-vue],[data-ouvrir]');
      if (!b) return;
      if (b.dataset.vue) { self.vue = b.dataset.vue; }
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
    h += '</div><p class="sd-maj">' + d.joueurs + ' joueurs suivis · mis à jour ' + esc(ilya(d.misAJour)) + '</p></div>';
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
    var t = eng[0].tournoi, ouvert = !!this.ouverts[ref];
    var ronde = Math.max.apply(null, eng.map(function (e) { return e.rondeJouee || 0; }));
    var nb = eng[0].nbRondes;
    var meta = [t.ville ? esc(t.ville.charAt(0) + t.ville.slice(1).toLowerCase()) : '', esc(periode(t)), ronde && nb ? 'ronde ' + ronde + '/' + nb : '']
      .filter(Boolean).join(' · ');
    var h = '<article class="sd-carte' + (eng.some(function (e) { return e.partie && e.partie.enCours; }) ? ' sd-live' : '') + '">';
    h += '<div class="sd-carte-h"><div><h4>' + esc(t.nom) + '</h4><p class="sd-meta">' + meta + (eng[0].source !== 'ffe' ? ' · <span class="sd-src">chess-results</span>' : '') + '</p></div></div>';
    h += '<ul class="sd-joueurs">';
    eng.forEach(function (e) {
      var score = e.points != null ? '<span class="sd-score">' + pts(e.points) + '<small>/' + (e.rondeJouee || '') + '</small></span>' : '';
      var info = [place(e.place, e.nbJoueurs), e.perf ? 'perf ' + Math.round(e.perf) : ''].filter(Boolean).join(' · ');
      var live = e.partie && e.partie.enCours ? '<span class="sd-joue">joue la ronde ' + e.partie.ronde + '</span>' : '';
      h += '<li><span class="sd-nom">' + esc(e.joueur) + '</span>' + live + '<span class="sd-info">' + esc(info) + '</span>' + score + '</li>';
    });
    h += '</ul>';
    h += '<button type="button" class="sd-ouvrir" data-ouvrir="' + esc(ref) + '" aria-expanded="' + ouvert + '">' + (ouvert ? 'Masquer les rondes' : 'Voir les rondes') + '</button>';
    if (ouvert) {
      eng.forEach(function (e) {
        h += '<div class="sd-detail">' + (eng.length > 1 ? '<p class="sd-detail-nom">' + esc(e.joueur) + '</p>' : '') + '<table class="sd-rondes"><tbody>';
        (e.rondes || []).forEach(function (r) {
          h += '<tr><td class="sd-r">R' + r.numero + '</td><td>' + couleur(r.couleur) + '</td><td class="sd-res sd-res-' + (r.resultat || 'x') + '">' + (RES[r.resultat] || '–') + '</td><td>' + adv(r.adversaire) + '</td></tr>';
        });
        if (e.partie && !e.partie.resultat) {
          h += '<tr class="sd-encours"><td class="sd-r">R' + e.partie.ronde + '</td><td>' + couleur(e.partie.couleur) + '</td><td class="sd-res">…</td><td>' + adv(e.partie.adversaire) + ' <span class="sd-joue">' + (e.partie.enCours ? 'en cours' : 'à jouer') + '</span></td></tr>';
        }
        h += '</tbody></table></div>';
      });
      var liens = (eng[0].liens || []).filter(function (l) { return LIENS[l.cle]; });
      if (liens.length) {
        h += '<p class="sd-liens">' + liens.map(function (l) {
          return '<a href="' + esc(l.href) + '" target="_blank" rel="noopener">' + LIENS[l.cle] + (l.ronde ? ' ' + l.ronde : '') + ' ' + FLECHE + '</a>';
        }).join('') + '</p>';
      }
    }
    return h + '</article>';
  };

  Suivi.prototype.vueDirect = function () {
    var jeu = this.enJeu();
    if (!jeu.length) return '<p class="sd-vide">Aucune partie en cours en ce moment. Les parties apparaissent ici dès que l’appariement est publié.</p>';
    var h = '<div class="sd-cartes">';
    jeu.forEach(function (e) {
      var p = e.partie;
      h += '<article class="sd-carte sd-live sd-partie"><p class="sd-meta">' + esc(e.tournoi.nom) + '</p>';
      h += '<p class="sd-duel"><span class="sd-nom">' + esc(e.joueur) + '</span> ' + couleur(p.couleur) + ' <span class="sd-contre">contre</span> ' + adv(p.adversaire) + '</p>';
      h += '<p class="sd-meta">Ronde ' + p.ronde + (p.echiquier ? ' · table ' + p.echiquier : '') + (e.points != null ? ' · ' + pts(e.points) + ' pt avant la ronde' : '') + (p.pasEncoreCommencee ? ' · pas encore commencée' : '') + '</p></article>';
    });
    return h + '</div>';
  };

  Suivi.prototype.vueFil = function () {
    var f = this.d.fil;
    if (!f.length) return '<p class="sd-vide">Les nouveaux résultats apparaîtront ici au fil des rondes.</p>';
    var h = '<ul class="sd-fil">';
    f.forEach(function (ev) {
      h += '<li><span class="sd-pastille sd-res-' + esc(ev.resultat) + '">' + (RES_LONG[ev.resultat] || '') + '</span>'
        + '<span class="sd-fil-txt"><strong>' + esc(ev.joueur) + '</strong> ' + couleur(ev.couleur) + ' contre ' + adv(ev.adversaire)
        + '<span class="sd-meta"> · ronde ' + ev.ronde + ' · ' + esc(ev.tournoi) + '</span></span>'
        + '<span class="sd-quand">' + esc(ilya(ev.a)) + '</span>'
        + (ev.lien ? '<a class="sd-fil-lien" href="' + esc(ev.lien) + '" target="_blank" rel="noopener" aria-label="Voir la ronde sur le site de la FFE">' + FLECHE + '</a>' : '') + '</li>';
    });
    return h + '</ul>';
  };

  function demarrer() {
    var els = document.querySelectorAll('[data-suivi]');
    for (var i = 0; i < els.length; i++) {
      if (!els[i].suivi) { els[i].suivi = new Suivi(els[i]); }
    }
  }
  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', demarrer); } else { demarrer(); }
})();
