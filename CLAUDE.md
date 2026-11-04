# SiteACE — cannes-echecs.fr

## Règle absolue
Ne JAMAIS modifier `C:\Users\mathi\Desktop\Site ACE\cannes-echecs\` (avec espace dans "Site ACE").
C'est un projet React séparé — hors périmètre de ce répertoire.

## Règle A — Valider avant de pousser (posée le 2026-07-14)
1. **Ne JAMAIS `git push` tant que Mathilde n'a pas validé le rendu.** Commit local OK ; le push attend son feu vert explicite après qu'elle a vu le résultat (localhost ou capture).
2. **Tout arbitrage de mise en page** (onglet vs section, 1 vs 2 colonnes, emplacement d'un bloc…) → la faire choisir **avant** de coder, ne pas trancher seul.
3. Si le screenshot de l'aperçu est en panne, le **dire** au lieu d'annoncer « vérifié » sur la seule base des mesures (une mesure ne voit pas une erreur de disposition).

## Fichier unique
Tout le site est dans un seul fichier : `index.html` (~4800 lignes).
Pas de build step. On édite directement ce fichier.

## Déploiement
```
git push origin master:main
```
→ GitHub Pages (`SondraPransky/cannesechecs`).

⚠️ **Le domaine `cannes-echecs.fr` n'est PAS (encore) branché sur ce dépôt.** `cannes-echecs.fr`/`www` sert encore l'ANCIEN site (serveur nginx, sans rapport). Le nouveau site (ce dépôt) est en ligne à :
**`https://sondrapransky.github.io/cannesechecs/`**
C'est cette URL qui reflète les `git push`. Le jour où le club a un domaine, il faudra le pointer sur ce GitHub Pages (ajouter un `CNAME`).

## Prévisualisation locale
```
npx serve .
```
→ port 5173 → `http://localhost:5173`

## Panneau admin (widget héro)
Ajouter `?admin` à l'URL (ex. `https://sondrapransky.github.io/cannesechecs/?admin`).
Visible uniquement avec `?admin` — à bookmarker par Romuald et Marlies.

## Outils — Pico Elo (classement des jeunes)
- **Outil** (calcul du classement Pico Elo à partir des grilles FFE) : `outils/pico-elo.html`, en ligne à `https://sondrapransky.github.io/cannesechecs/outils/pico-elo.html`. Source dev : dépôt séparé `C:\Users\mathi\Desktop\PicoElo` (re-copier `PicoElo_Cannes.html` → `outils/pico-elo.html` à chaque MAJ). Diagnostic : `outils/test-fichier.html`.
- **Classement affiché** : bloc dans `index.html` onglet Tournois → PICO, `<iframe src="classement.html">` (auto-hauteur). `classement.html` (racine du dépôt) = page du classement, remplacée à chaque tournoi via le bouton « Publier sur le site » de l'outil (placeholder clair en attendant la 1re vraie publication).
- **Publication mensuelle** = Romuald/Marlies (non-tech). Guide : `PicoElo\Guide_Publier_Classement.html`. Cible WordPress = plugin *Enable Media Replace*.

## Plugin WordPress « Suivi tournoi » (`wp-plugin-suivi/`)
- **But** : suivre en direct les joueurs de Cannes Échecs sur les tournois FFE, géré depuis l'admin WordPress (zéro terminal pour les bénévoles). Portage PHP de l'app Node `C:\Users\mathi\Desktop\suivi tournoi` (ne pas la modifier sans demande).
- **État** : plugin complet (moteur validé par parité avec l'app Node, admin à onglets, affichage `[suivi_tournoi]` dans l'onglet Tournois › En direct), vérifié dans le WordPress local (Local : site `cannes-echecs-test`, http://cannes-echecs-test.local, plugin = jonction vers ce dossier). Reste : l'installer sur le vrai WordPress du club. Détail : `wp-plugin-suivi/README.md`. Zip : `python wp-plugin-suivi/bin/construire-zip.py`.
- **Tests** (PHP CLI : `C:\Users\mathi\AppData\Local\Programs\php84\php.exe`, sans curl/openssl/mbstring) : `php bin/test-ffe.php`, `php bin/test-tracker.php`, `php bin/test-chessresults.php` (fixtures, sans réseau) ; `php bin/live-ffe.php`, `php bin/live-chessresults.php` (en direct via curl système, ponctuel).
- Le dossier est servi en clair par GitHub Pages : **ne jamais y mettre de secret**.

### État au 2026-11-04 (pause, tout est poussé)
Scan complet terminé dans le site Local : 114 joueurs (importés de l'app Node), 69 participations suivies (7 en cours, 5 à venir, 57 terminées), noms russes/arabes traduits, tournois de test écartés. Le site Local doit être démarré (Local › Start site) pour toute vérification ; wp-cli et commandes de diag : voir la mémoire `project-wp-plugin-suivi`.

### À faire plus tard — Suivi tournoi (noté le 2026-11-03)
1. **Installer sur le vrai WordPress du club** quand il existera : `python wp-plugin-suivi/bin/construire-zip.py` → Extensions › Téléverser ; installer aussi le thème régénéré (`node build-wp-theme.js` + zip Python). Puis admin › Suivi tournoi › Joueurs : ajouter le club 311 ; onglet Suivi : *Lancer le scan complet* (page ouverte ~1-2 h).
2. **Chez l'hébergeur** : si le site a peu de visites, tâche planifiée sur `wp-cron.php` toutes les 5 min + `DISABLE_WP_CRON` ; `max_execution_time` ≥ 60 s.
3. **Montrer l'admin à Romuald et Marlies** (GUIDE-ADMIN.md § 5).
4. **Ensuite seulement** : archiver en zip le dossier `C:\Users\mathi\Desktop\suivi tournoi` (app Node d'origine). Ne pas le supprimer avant : référence de parité + source des 114 joueurs.
5. Nettoyer le site Local : effacer `app/public/apercu-suivi/` (dossier d'aperçu).
6. ~~Photo `photos/vichy_chptfrancejeunes.jpg`~~ : réglé — vieille photo (Vichy 2025, championnat de France jeunes), à laisser dans le dossier sans l'utiliser en article ni la commiter (Mathilde, 2026-11-04).

## Mainteneurs non-techniques
Romuald de Labaca et Marlies Bensdorp De Labaca mettront à jour le site.
Aucune compétence technique. Toute procédure doit rester simple.

## ⚠️ RÈGLE ABSOLUE — Site WordPress
**Le site FINAL sera sous WordPress. Le site statique actuel est PROVISOIRE.**

- Avant toute proposition de feature admin ou de gestion de contenu, se demander :
  "Est-ce que Romuald ou Marlies peut faire ça depuis l'interface WordPress admin ?"
- Si la réponse est non → trouver une autre approche ou signaler la limitation.
- NE JAMAIS proposer "Mathilde n'a qu'à modifier 3 lignes de code" comme solution permanente.
- Les limitations actuelles (localStorage, JS à éditer) sont acceptables en **transition seulement**.
- Sur WordPress : penser ACF (Advanced Custom Fields) pour tout contenu variable
  (URLs FIJ, compteurs, dates, textes widgets, articles, horaires, équipe, etc.).
