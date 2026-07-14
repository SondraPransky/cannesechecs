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
