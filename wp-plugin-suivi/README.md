# Plugin WordPress « Suivi tournoi »

Portage PHP de l'app Node `suivi-tournoi` (`C:\Users\mathi\Desktop\suivi tournoi`)
en **plugin WordPress**, pour suivre les joueurs de Cannes Échecs sur les tournois
FFE, **gérable depuis l'admin WordPress** (zéro terminal pour Romuald/Marlies).

But final : la liste des joueurs / le club suivi se règlent dans l'admin WP, le
rafraîchissement tourne en WP-cron, et un bloc/shortcode affiche le tableau de
bord sur la page Tournois.

## État — Phase 1 : couche FFE + Elo portées et validées

| Brique | Source JS | Statut |
|--------|-----------|--------|
| HTTP + décodage (utf-8 / windows-1252) | `ffe.js` | ✅ `includes/class-suivi-ffe.php` |
| Utilitaires parsing (`text`/`cells`/`num`/`norm_name`) | `ffe.js` | ✅ |
| Annuaire joueurs, licenciés d'un club, ID FIDE | `ffe.js` | ✅ |
| Liste des tournois du mois, fiche tournoi | `ffe.js` | ✅ |
| Participants, classement, grille américaine, grille Berger, ronde | `ffe.js` | ✅ |
| Pagination ASP.NET (`__doPostBack`) | `ffe.js` | ✅ **validée en direct** (193 licenciés de Cannes, 362 tournois en octobre 2026) |
| Estimation Elo en direct (K FIDE, nature du tournoi) | `elo.js` | ✅ `includes/class-suivi-elo.php` |
| Orchestration du suivi (fenêtre de dates, engagements, appariements, archive) | `tracker.js`, `engagement.js`, `appariement.js`, `joueurs.js`, `archive.js` | ⏳ prochaine étape |
| Tournois internationaux (chess-results) | `chessresults.js` | ⏳ |
| **Plugin WP** : admin, WP-cron, stockage, bloc d'affichage | `server.js` + `public/` | ⏳ Phase 2-3 (nécessite un WordPress local) |

**Validation par parité** : le code JS d'origine et le portage PHP ont été exécutés
sur les mêmes pages ; sorties identiques champ par champ (club, liste, fiches,
participants, classement, grille, ronde, Berger, toute la batterie Elo), et
mêmes 193 licenciés en direct.

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

## Tester (sans WordPress)

```bash
php bin/test-ffe.php    # 45 assertions sur fixtures — rapide, sans réseau
php bin/live-ffe.php    # en direct contre la FFE (pagination réelle) — ponctuel
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
