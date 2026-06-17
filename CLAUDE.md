# SiteACE — cannes-echecs.fr

## Règle absolue
Ne JAMAIS modifier `C:\Users\mathi\Desktop\Site ACE\cannes-echecs\` (avec espace dans "Site ACE").
C'est un projet React séparé — hors périmètre de ce répertoire.

## Fichier unique
Tout le site est dans un seul fichier : `index.html` (~3800 lignes).
Pas de build step. On édite directement ce fichier.

## Déploiement
```
git push origin master:main
```
→ GitHub Pages (`SondraPransky/cannesechecs`) → `cannes-echecs.fr`

## Prévisualisation locale
```
npx serve .
```
→ port 5173 → `http://localhost:5173`

## Panneau admin (widget héro)
URL : `cannes-echecs.fr/?admin`  
Visible uniquement avec `?admin` — à bookmarker par Romuald et Marlies.

## Mainteneurs non-techniques
Romuald de Labaca et Marlies Bensdorp De Labaca mettront à jour le site.
Aucune compétence technique. Toute procédure doit rester simple.
