# Plugin WordPress « Suivi tournoi »

Portage PHP de l'app Node `suivi-tournoi` (`C:\Users\mathi\Desktop\suivi tournoi`)
en **plugin WordPress**, pour suivre les joueurs de Cannes Échecs sur les tournois
FFE, **gérable depuis l'admin WordPress** (zéro terminal pour Romuald/Marlies).

But final : la liste des joueurs / le club suivi se règlent dans l'admin WP, le
rafraîchissement tourne en WP-cron, et un bloc/shortcode affiche le tableau de
bord sur la page Tournois.

## État — Phase 1 en cours (cœur data, portage testable sans WordPress)

| Brique | Source JS | Statut |
|--------|-----------|--------|
| HTTP + décodage (utf-8 / windows-1252) | `ffe.js` | ✅ porté (`includes/class-suivi-ffe.php`) |
| Utilitaires parsing (`text`/`cells`/`num`/`norm_name`) | `ffe.js` | ✅ porté + testé |
| Annuaire joueurs + licenciés d'un club | `ffe.js` | ✅ porté + **testé contre la vraie FFE** (40 licenciés de Cannes) |
| Fiche tournoi, classement, grille américaine/Berger, ronde | `ffe.js` | ⏳ à porter |
| Estimation Elo en direct | `elo.js` | ⏳ à porter |
| Orchestration du suivi (tracker) | `tracker.js` | ⏳ à porter |
| **Plugin WP** : admin, WP-cron, stockage, bloc d'affichage | `server.js` + `public/` | ⏳ Phase 2-3 (nécessite un WordPress local) |

## Contrainte d'environnement

La PHP CLI locale (8.4) **n'a ni `curl` ni `openssl` ni `mbstring`** → elle ne
peut pas faire de HTTPS. Impact : **en local on teste le parsing contre des
fixtures** (pages FFE réelles sauvegardées via `curl`). En production WordPress,
l'HTTP passe par `wp_remote_get` (natif WP) ; le décodage utilise `iconv`
(présent). Le transport HTTP est **injecté** dans `Suivi_FFE` (découplé du parsing).

## Tester le parsing (sans WordPress)

```bash
php bin/test-ffe.php
```

Charge les fixtures de `tests/fixtures/` et vérifie l'extraction. Rafraîchir une
fixture :

```bash
curl -s -A "suivi-tournoi/1.0" "https://www.echecs.asso.fr/ListeJoueurs.aspx?Action=JOUEURCLUBREF&JrTri=Elo&ClubRef=311" -o tests/fixtures/club-311.html
```

`ClubRef=311` = Cannes Échecs.
