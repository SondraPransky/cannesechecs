# Plugin WordPress « Suivi tournoi »

Portage PHP de l'app Node `suivi-tournoi` (`C:\Users\mathi\Desktop\suivi tournoi`)
en **plugin WordPress**, pour suivre les joueurs de Cannes Échecs sur les tournois
FFE, **gérable depuis l'admin WordPress** (zéro terminal pour Romuald/Marlies).

But final : la liste des joueurs / le club suivi se règlent dans l'admin WP, le
rafraîchissement tourne en WP-cron, et un bloc/shortcode affiche le tableau de
bord sur la page Tournois.

## État — plugin complet (moteur, admin, affichage), vérifié dans un WordPress local

### Phase 1 : FFE, Elo, moteur et chess-results portés et validés

| Brique | Source JS | Statut |
|--------|-----------|--------|
| HTTP + décodage (utf-8 / windows-1252) | `ffe.js` | ✅ `includes/class-suivi-ffe.php` |
| Utilitaires parsing (`text`/`cells`/`num`/`norm_name`) | `ffe.js` | ✅ |
| Annuaire joueurs, licenciés d'un club, ID FIDE | `ffe.js` | ✅ |
| Liste des tournois du mois, fiche tournoi | `ffe.js` | ✅ |
| Participants, classement, grille américaine, grille Berger, ronde | `ffe.js` | ✅ |
| Pagination ASP.NET (`__doPostBack`) | `ffe.js` | ✅ **validée en direct** (193 licenciés de Cannes, 362 tournois en octobre 2026) |
| Estimation Elo en direct (K FIDE, nature du tournoi) | `elo.js` | ✅ `includes/class-suivi-elo.php` |
| Moteur de suivi (fenêtre, croisement, détail, parcours, partie du moment) | `tracker.js` | ✅ `includes/class-suivi-tracker.php` |
| Modèle d'engagement (statut, performance), rapprochement des noms | `engagement.js`, `appariement.js` | ✅ |
| Joueurs suivis (ajout, ajout d'un club, retrait, Elo quotidien) | `joueurs.js` | ✅ `includes/class-suivi-joueurs.php` |
| Fil des nouveaux résultats, archive, bilan de saison | `journal.js`, `archive.js` | ✅ `includes/class-suivi-journal.php` |
| Persistance | `store.js` | ✅ interface `includes/class-suivi-store.php` (mémoire, fichiers JSON ; WordPress en Phase 2) |
| Tournois internationaux (chess-results) : recherche par FIDE-ID, fiche joueur/tournoi | `chessresults.js` | ✅ `includes/class-suivi-chessresults.php` |
| Complément chess-results du moteur, ID FIDE lus sur les fiches FFE | `tracker.js`, `joueurs.js` | ✅ |
| **Plugin WP** : admin, WP-cron, stockage en base, affichage | `server.js` + `public/` | ✅ Phases 2-3 (ci-dessous) |

**Validation par parité** : le code JS d'origine et le portage PHP ont été exécutés
sur les mêmes pages ; sorties identiques champ par champ (club, liste, fiches,
participants, classement, grille, ronde, Berger, toute la batterie Elo), et
mêmes 193 licenciés en direct. Le **moteur complet** aussi : même scénario (4
joueurs suivis, horloge figée, réseau simulé) → même état (engagements, parcours,
partie du moment, classement en direct, mémoire du fil).

### Moteur reprenable (différence de structure avec l'original)

L'app Node fait tout en un passage (~1 000 requêtes au premier scan, en
parallèle). Un cron WordPress mutualisé est souvent coupé au bout de 30-60 s :
le moteur PHP est donc **reprenable** —

- budget de temps par passage (`budgetSecondes`, 25 s par défaut) ; au-delà,
  plus de requête, le passage finit proprement et l'état est reconstruit avec le cache ;
- tout ce qui est lu est mis en cache (listes des mois 1 h, fiches 6 h,
  participants 12 h, détails à chaque passage tant que le tournoi n'est pas fini) ;
- **le direct d'abord** : les tournois en cours des joueurs suivis sont relus en
  premier, plafonnés à 60 % du budget pour que la découverte avance toujours ;
- **cache négatif** : une page en échec n'est retentée qu'au bout d'une heure
  (sinon une page cassée retentée à chaque passage peut consommer tout le budget
  et le scan ne finit jamais — bug trouvé par les tests).

**Part réservée à chess-results** : quand il est actif, la FFE s'arrête à 75 %
du budget et chess-results reprend le reste. Sans cette réserve la FFE prenait
tout et la recherche chess-results (3 requêtes indivisibles) n'aboutissait
jamais. Côté chess-results aussi, le direct d'abord (fiches des tournois non
finis, plafonnées à 60 % de sa part), puis les recherches (cache 6 h), puis les
autres fiches (cache 24 h une fois le tournoi fini).

Contrainte : le budget doit couvrir le plus gros détail d'un tournoi (~3 + nombre
de rondes requêtes) ; 25 s est très large.

**Écarts volontaires avec l'original** (hors grille Berger, ci-dessous) : la fiche
d'un tournoi fait toujours foi sur la liste du mois (l'original rebasculait sur le
nom de la liste une fois le tournoi fini) ; un joueur sans code fédéral n'est pas
recherché par nom pour son Elo (l'original cherchait le nom littéral `undefined`).

**Seul écart volontaire avec l'original** — noms des titrés en grille Berger :
Papi préfixe le titre (`g Eric PRIE`), et `ffe.js` produisait `Eric PRIE g`, nom
alors introuvable par le suivi. Le portage rend `PRIE Eric` + `titre: "g"`, et gère
les prénoms composés (`Jean-Marie MARTINE` → `MARTINE Jean-Marie`). L'app Node
d'origine a toujours ce bug.

## Phases 2-3 : le plugin WordPress

| Fichier | Rôle |
|---------|------|
| `suivi-tournoi.php` | En-tête du plugin, chargement, crochets (cron, AJAX, admin, shortcode) |
| `includes/class-suivi-wp.php` | Stockage en options (sans autoload), transport `wp_remote_request` (délai 30 s), WP-cron à l'intervalle de la config, **verrou** atomique (`add_option`), priorité aux actions de l'admin, action AJAX du scan complet |
| `includes/class-suivi-admin.php` | Menu « Suivi tournoi », 4 onglets : Suivi (état, scan complet, masquer), Joueurs, Tournois masqués, Réglages. Actions par `admin-post` (jeton + `manage_options`) sous le verrou |
| `includes/class-suivi-public.php` | Shortcode `[suivi_tournoi]` + `GET /wp-json/suivi-tournoi/v1/etat` (lecture seule, données publiques seulement) |
| `assets/suivi-public.{js,css}` | Affichage : vues Tournois / En direct / Résultats, rafraîchi toutes les 2 min, aux couleurs du site (DESIGN.md) |
| `uninstall.php` | Efface les données à la suppression du plugin |

**Côté thème** : onglet « En direct » de la page Tournois dans `index.html` (classe
`tab-wp-only`, masqué sur le site statique) ; `build-wp-theme.js` retire la classe et
remplace `<!--SUIVI_TOURNOI-->` par le shortcode.

**Installation** : `python wp-plugin-suivi/bin/construire-zip.py` → `suivi-tournoi.zip`
(sans `bin/` ni `tests/`) → Extensions › Ajouter › Téléverser. Puis onglet Joueurs
(ajouter le club 311), onglet Suivi › *Lancer le scan complet*. Guide bénévoles :
`GUIDE-ADMIN.md` § 5.

**Hébergement** : WP-cron ne tourne qu'aux visites du site. Site peu visité → demander à
l'hébergeur une tâche planifiée qui appelle `wp-cron.php` toutes les 5 minutes (et mettre
`DISABLE_WP_CRON` à `true`). Le passage demande ~60 s de `max_execution_time` (budget 25 s
+ une requête FFE lente jusqu'à 30 s).

### Ce que le vrai réseau a appris (vérifié le 2026-11-03 dans Local)

La FFE met parfois **10 à 20 s par page**. Tout ce qui lisait plusieurs pages d'un bloc
dépassait un passage et recommençait sans fin :
- **liste du mois** (5-15 pages) et **liste d'un club** (~10 pages) : reprenables page par
  page (jeton de pagination ASP.NET en cache), une page en échec retentée 3 fois ;
- **listes de mois relues selon le mois** : en cours 2 h, précédent 12 h, suivant 6 h ;
- **Elo quotidien** : un joueur absent de la liste de son club (`horsClubLe`) passe
  directement à la recherche individuelle, une tentative par jour (`eloEssaiLe`) — sinon
  tout le club était relu à chaque passage ;
- **requêtes groupées** (comme l'app d'origine) : 4 à la fois vers la FFE, 2 vers chess-results (`transport_multiple`, bibliothèque Requests de WordPress) ; ~4× plus rapide en réel (8-14 éléments par passage au lieu de 2-3), même résultat vérifié par test ;
- **rattrapage chess-results** : tant que des joueurs n'y ont jamais été cherchés, chess-results a 60 % du budget (sinon une compétition internationale en cours restait invisible des heures) ;
- **ordre de lecture** : participants avant fiches (ce sont eux qui révèlent nos joueurs ; la FFE peut mettre 16 s par lot de fiches), région du club d'abord (06, puis PACA), tournois commencés avant ceux à venir, le plus proche d'aujourd'hui d'abord ; sans limite de budget, ordre de l'original (parité conservée) ;
- **direct relu au plus toutes les 4 min** (FFE et chess-results), et relu en tout début de passage pour chess-results ; délai d'attente d'une requête borné par le temps restant au passage ; scan complet de l'admin en passages de 50 s ;
- **verrou** : relu en base à chaque vérification (WordPress garde une option lue pendant toute la requête ; une attente du verrou ne voyait jamais sa libération) ; les actions de l'admin passent avant les passages ;
- **chess-results** : part de budget réservée (FFE ≤ 75 %), tour de rôle direct / recherche ;
- **fiches de test d'organisateurs** écartées (homme dans un tournoi féminin, adulte dans un
  tournoi jeunes, d'après la catégorie FFE) ; les autres cas se masquent depuis l'admin.

Premier scan : ~1 000 éléments (fiches, participants, recherches chess-results) ; avec le
bouton « scan complet », environ 1 à 2 h selon la vitesse de la FFE.

## Contrainte d'environnement

La PHP CLI locale (8.4) **n'a ni `curl` ni `openssl` ni `mbstring`** → elle ne
peut pas faire de HTTPS. Impact : **en local on teste le parsing contre des
fixtures** (pages FFE réelles sauvegardées via `curl`). En production WordPress,
l'HTTP passe par `wp_remote_get` (natif WP) ; le décodage utilise `iconv`
(présent). Le transport HTTP est **injecté** dans `Suivi_FFE` (découplé du parsing).

### chess-results

Le site répartit les visiteurs sur des nœuds (`S1`, `S2`, `S3`…) par
redirection : `Suivi_ChessResults` suit les redirections **à la main** et garde
les cookies, sinon le `__VIEWSTATE` de la recherche est rejeté (erreur 500).
Il demande donc au transport de ne pas suivre (`redirection => 0`) et attend
`status` + `headers` (`location`, `set-cookie`). Politesse : 350 ms entre deux
requêtes (le site est tenu par une seule personne). Coût : ~3 requêtes par
recherche, 2 par fiche.

Validé par parité avec l'app Node : les 6 dispositions de fiches + la recherche
(champ par champ), et le moteur complet sur un scénario FFE + chess-results
(mêmes 8 engagements, mêmes 23 requêtes chess-results, mêmes ID FIDE).

Écarts volontaires : une réponse HTTP ≥ 400 lève une erreur (l'original lisait la
page d'erreur comme « aucun tournoi » et le gardait 6 h) ; une fiche en panne
garde sa dernière version lue (l'original retirait le tournoi pour ce passage).

Limite connue : un événement multi-groupes **en cours** dont le nom n'a pas deux
mots communs entre chapeau et groupe (ex. noms grecs) laisse passer la page-chapeau
(« à venir », zéro ronde) — comme l'original.

## Tester (sans WordPress)

```bash
php bin/test-ffe.php      # couche FFE + Elo : 45 assertions sur fixtures, sans réseau
php bin/test-tracker.php  # moteur : 37 assertions (passage complet, fil, archivage,
                          # budget/reprise, gestion des joueurs), horloge figée
php bin/test-chessresults.php  # chess-results : 33 assertions (pages, moteur, cache,
                               # panne, budget, retrait), faux réseau avec redirections
php bin/live-ffe.php      # en direct contre la FFE (pagination réelle) — ponctuel
php bin/live-chessresults.php [FIDE-ID]  # en direct contre chess-results — ponctuel
```

`test-ffe.php` charge les pages réelles sauvegardées dans `tests/fixtures/`.
`live-ffe.php` passe par l'exécutable `curl` du système (`bin/transport-curl-cli.php`,
outil de dev non livré dans le plugin) : à lancer ponctuellement, pas en boucle,
par politesse envers le serveur de la FFE.

Rafraîchir une fixture :

```bash
curl -s -A "suivi-tournoi/1.0" "https://www.echecs.asso.fr/ListeJoueurs.aspx?Action=JOUEURCLUBREF&JrTri=Elo&ClubRef=311" -o tests/fixtures/club-311.html
```

`ClubRef=311` = Cannes Échecs.
