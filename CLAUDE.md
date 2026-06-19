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

## ⚠️ RÈGLE ABSOLUE — Site WordPress
**Le site FINAL sera sous WordPress. Le site statique actuel est PROVISOIRE.**

- Avant toute proposition de feature admin ou de gestion de contenu, se demander :
  "Est-ce que Romuald ou Marlies peut faire ça depuis l'interface WordPress admin ?"
- Si la réponse est non → trouver une autre approche ou signaler la limitation.
- NE JAMAIS proposer "Mathilde n'a qu'à modifier 3 lignes de code" comme solution permanente.
- Les limitations actuelles (localStorage, JS à éditer) sont acceptables en **transition seulement**.
- Sur WordPress : penser ACF (Advanced Custom Fields) pour tout contenu variable
  (URLs FIJ, compteurs, dates, textes widgets, articles, horaires, équipe, etc.).
