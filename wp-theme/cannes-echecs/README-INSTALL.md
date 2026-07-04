# Thème WordPress Cannes Échecs — installation

Thème **multi-pages** : chaque page a sa propre URL, chaque actualité est un
article WordPress avec son permalien, ses metas de partage et sa galerie.

## Prérequis

- WordPress 6.4+, PHP 8.0+
- **ACF Pro** (obligatoire : pages d'options + repeaters)

## Installation

1. Zipper le dossier `cannes-echecs/` (sans le sous-dossier `_dev/`) et le
   téléverser via **Apparence → Thèmes → Ajouter → Téléverser**.
2. Activer le thème. À l'activation :
   - les 10 pages statiques sont créées automatiquement (club, activites,
     horaires, adhesion, contact, tournois, fij, partenaires, agenda,
     organigramme) ;
   - les permaliens passent en « Nom de l'article » si nécessaire.
3. Installer et activer **ACF Pro**. Les 4 groupes de champs se chargent
   automatiquement depuis `acf-json/` (vérifier dans ACF → Groupes de champs).
4. **Réglages → Lecture** : rien à faire — `front-page.php` sert l'accueil.

## Après installation (contenu)

| Où | Quoi |
|---|---|
| Réglages CE → 🔗 HelloAsso | Coller les 12 vraies URLs d'inscription |
| Réglages CE → ♟ FIJ 2027 | Vérifier dates des rondes + liens inscrits |
| Réglages CE → 🏛 Infos Club | Saison, widget d'accueil, horaires, équipe (photos !) |
| Actualités → Ajouter | Créer les articles (titre, contenu, catégorie, image, galerie) |

## Architecture

- `front-page.php` — accueil (4 dernières actus en boucle WP)
- `page-{slug}.php` × 10 — pages statiques (générées depuis `index.html`
  par `build-wp-theme.js` à la racine du dépôt)
- `archive-actualite.php` — `/actualites/` paginé (12/page)
- `single-actualite.php` — un article = une URL = ses propres og:title/image
- `functions.php` — CPT, options ACF, données JS (`wp_add_inline_script`),
  metas SEO/OG, création des pages à l'activation
- `assets/js/main.js` — interactions (compte à rebours, onglets, filtres,
  lightbox, formulaire) ; le routeur SPA est remplacé par un shim qui
  transforme tout `goTo()` résiduel en vraie navigation

## Régénérer les templates depuis index.html

Tant que le site statique (GitHub Pages) reste la référence :

```
node build-wp-theme.js
```

⚠️ Ne pas éditer à la main le HTML des `page-{slug}.php` : modifier
`index.html` puis régénérer. Les fichiers écrits à la main (`functions.php`,
`front-page.php`, `archive-actualite.php`, `single-actualite.php`,
`header.php` étant régénéré, y reporter toute retouche via le script) ne sont
pas écrasés, **sauf `header.php` et `footer.php`** qui sont régénérés.

## Import des articles du site statique

Le fichier `wp-theme/wp-import-articles.php` (généré par `node build-wp-import.js`
à la racine du dépôt) importe les 32 articles + toutes leurs photos :

1. Thème actif + ACF Pro actif.
2. Copier `wp-import-articles.php` à la racine de WordPress (à côté de `wp-load.php`).
3. Visiter `https://votre-site/wp-import-articles.php?key=cannes-import-2027`
   et patienter (les photos sont téléchargées depuis cannes-echecs.fr vers la
   médiathèque).
4. **Supprimer le fichier du serveur** une fois l'import terminé.

Relançable sans risque : slugs existants ignorés, images déjà présentes réutilisées.

## Reste à faire (hors thème)

- Formulaire de contact : remplacer formsubmit.co par un plugin WP + SMTP
- Renseigner les URLs HelloAsso + photos équipe (Réglages CE)
