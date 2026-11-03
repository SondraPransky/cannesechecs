# Plugin WordPress « Suivi tournoi »

Portage PHP de l'app Node `suivi-tournoi` (`C:\Users\mathi\Desktop\suivi tournoi`)
en **plugin WordPress**, pour suivre les joueurs de Cannes Échecs sur les tournois
FFE, **gérable depuis l'admin WordPress** (zéro terminal pour Romuald/Marlies).

But final : la liste des joueurs / le club suivi se règlent dans l'admin WP, le
rafraîchissement tourne en WP-cron, et un bloc/shortcode affiche le tableau de
bord sur la page Tournois.

## État — Phase 1 terminée : FFE, Elo, moteur et chess-results portés et validés

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
| **Plugin WP** : admin, WP-cron, stockage en base, bloc d'affichage | `server.js` + `public/` | ⏳ Phase 2-3 (nécessite un WordPress local) |

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
