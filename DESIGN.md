---
name: Cannes Échecs
description: Site vitrine premium d'un club d'échecs de la Côte d'Azur — or, bleu marine, ivoire, typographie éditoriale serif.
colors:
  gold: "#C9A84C"
  gold-hover: "#B8963C"
  gold-pale: "#F5EDD4"
  gold-text: "#7E6420"
  bleu: "#1E3A5F"
  noir: "#0F0E17"
  noir2: "#1A1925"
  ivoire: "#FAF8F4"
  ivoire2: "#F0EDE6"
  text: "#374151"
  muted: "#4A4858"
  border: "#E5E0D8"
typography:
  display:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "66px"
    fontWeight: 700
    lineHeight: 1.0
    letterSpacing: "normal"
  headline:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "42px"
    fontWeight: 700
    lineHeight: 1.15
    letterSpacing: "normal"
  title:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "26px"
    fontWeight: 700
    lineHeight: 1.2
  body:
    fontFamily: "Inter, sans-serif"
    fontSize: "15px"
    fontWeight: 400
    lineHeight: 1.6
  label:
    fontFamily: "Montserrat, sans-serif"
    fontSize: "11px"
    fontWeight: 700
    lineHeight: 1.4
    letterSpacing: "0.15em"
rounded:
  sm: "4px"
  md: "8px"
  lg: "12px"
  xl: "16px"
  pill: "50%"
spacing:
  xs: "8px"
  sm: "16px"
  md: "24px"
  lg: "48px"
components:
  button-primary:
    backgroundColor: "{colors.gold}"
    textColor: "{colors.noir}"
    rounded: "{rounded.md}"
    padding: "14px 28px"
  button-primary-hover:
    backgroundColor: "{colors.gold-hover}"
    textColor: "{colors.noir}"
    rounded: "{rounded.md}"
    padding: "14px 28px"
  button-outline-dark:
    backgroundColor: "transparent"
    textColor: "{colors.bleu}"
    rounded: "{rounded.md}"
    padding: "14px 28px"
---

# Design System: Cannes Échecs

## 1. Overview

**Creative North Star: "Le Palmarès en Vitrine"**

Le système visuel de Cannes Échecs est une **salle des trophées**. L'or (`#C9A84C`) célèbre les titres et les accents ; le bleu marine profond (`#1E3A5F`) et le noir d'encre (`#0F0E17`) forment l'écrin qui les met en valeur ; l'ivoire chaud (`#FAF8F4`) est la lumière du musée. Chaque élément existe pour faire briller quelque chose : un chiffre de palmarès, un nom de champion, une date depuis 1985. Le prestige est porté par la retenue et la cohérence, jamais par la surcharge.

L'écriture typographique est délibérément **éditoriale** : le serif Cormorant Garamond pour les titres apporte l'élégance intemporelle d'un beau livre d'échecs, l'Inter porte le corps de texte avec une lisibilité sans compromis, et le Montserrat en capitales espacées signe les surtitres et boutons comme des cartels de vitrine. Ce trio crée un contraste net (serif de caractère + sans neutre + sans structuré) plutôt qu'un empilement de fontes similaires.

Ce système **rejette explicitement le site associatif daté** : aucun template années 2000, aucune couleur criarde, aucun tableau de mise en page, aucun rendu bricolé. Le club a un vrai palmarès (10× Champion de France Jeunes) ; le site doit être à sa hauteur — mais l'élégance reste au service de l'accueil, pour un public qui inclut seniors et enfants.

**Key Characteristics:**
- Or + bleu marine + ivoire : trois rôles clairs, jamais dilués.
- Typographie de contraste (serif display / sans body / sans label capitalisé).
- Prestige par la preuve (chiffres, titres, dates) plutôt que par l'adjectif.
- Lisibilité renforcée : contrastes francs, pas de gris pâle décoratif.
- Micro-relèvements au survol (`translateY(-2px)`) — vivant mais sobre.

## 2. Colors

Une palette de cercle privé : l'or comme unique voix d'accent, le bleu marine et le noir comme écrins, l'ivoire comme lumière ambiante.

### Primary
- **Or Trophée** (`#C9A84C`) : la voix d'accent unique. Boutons principaux, chiffres de palmarès, italiques de titre (`<em>`), surlignages, filets décoratifs (`.gold-bar`). C'est la couleur qui célèbre. Variante survol **Or Bronze** (`#B8963C`).
- **Or Pâle** (`#F5EDD4`) : fonds de badges et pastilles sur surface claire. **Or Encre** (`#7E6420`) : le texte des surtitres, assez foncé pour rester lisible sur ivoire.

### Secondary
- **Bleu Marine de Nuit** (`#1E3A5F`) : la couleur institutionnelle. Titres de section sur fond clair, boutons contour, `theme-color` mobile, fonds de sections calmes. L'écrin sérieux qui fait ressortir l'or.

### Neutral
- **Noir d'Encre** (`#0F0E17`) et **Noir Ardoise** (`#1A1925`) : fonds des héros et sections sombres, texte corps sur ivoire. La profondeur du velours d'une vitrine.
- **Ivoire** (`#FAF8F4`) et **Ivoire Sable** (`#F0EDE6`) : fond principal du site et surfaces alternées. Une lumière chaude, pas un blanc froid.
- **Texte** (`#374151`) et **Muté** (`#4A4858`) : corps de texte et texte secondaire. **Bordure** (`#E5E0D8`) : filets et séparateurs discrets.

### Named Rules
**La Règle de la Voix Unique.** L'or est la seule couleur d'accent. Il ne cohabite avec aucun autre accent saturé (pas de vert, rouge, violet décoratifs). Sa rareté fait sa valeur : sur un écran donné, l'or doit rester minoritaire pour continuer à signifier « trophée ».

**La Règle de l'Écrin (contraste du TEXTE or — WCAG AA).** L'or comme *texte* change de teinte selon le fond, pour rester lisible :
- **Fond sombre** (noir, bleu marine solide, dégradé héros) → **Or Trophée `#C9A84C`** (vif). C'est là qu'il brille (8:1+).
- **Fond clair** (ivoire, blanc, or-pâle) → **Or Encre `#7E6420`** (bronze). L'or vif y échoue le contraste (2.1:1) ; le bronze passe (5:1+).
- **Fond or-tinté ou ardoise moyenne** (pastilles `rgba(201,168,76,.1–.25)`, cartes translucides sur héros, chips calendrier/tarifs) → **Or Pâle `#F5EDD4`** (crème). L'or vif sur or-tinté est illisible (3:1) ; le crème pop (6:1+).

L'or comme *fond* (boutons, `.gold-bar`, pastilles pleines) porte toujours un texte **noir d'encre** (8.4:1). C'est le contraste écrin/joyau qui fait le prestige — mais jamais au prix de la lisibilité.

## 3. Typography

**Display Font:** Cormorant Garamond (fallback : Georgia, serif)
**Body Font:** Inter (fallback : sans-serif)
**Label/Kicker Font:** Montserrat (capitales espacées)

**Character:** Un contraste éditorial assumé. Le Cormorant apporte le caractère d'un serif de collection (les italiques dorées sont sa signature), l'Inter garantit une lecture confortable sur tous supports, le Montserrat structure les micro-éléments comme des cartels de musée. Trois familles, trois rôles nets — jamais deux fontes qui se ressemblent.

### Hierarchy
- **Display** (700, 66px, line-height 1.0) : titre du héro d'accueil. Les mots-clés passent en `<em>` italique doré. Plafond ; ne pas monter au-dessus.
- **Headline** (700, 42px, line-height 1.15) : titres de section (`.section-header h2`), en bleu marine sur fond clair, blanc sur fond sombre. Le héro des sous-pages est à 54px.
- **Title** (700, 22–26px, line-height 1.2) : titres de cartes, sous-titres, noms de sections internes.
- **Body** (400, 15px, line-height 1.6–1.75) : corps de texte en Inter, couleur `#374151`. Longueur de ligne max 65–75ch (les blocs de héro sont bornés à ~460–580px).
- **Label / Surtitre** (700, 11px, letter-spacing 0.15em, MAJUSCULES) : `.surtitre`, boutons, cartels. En Or Encre (`#7E6420`) sur clair, en or sur sombre.

### Échelle typographique (tokens `:root` — source pour WordPress/ACF)
Tailles nommées par rôle, définies dans `:root` de `index.html`. À réutiliser au lieu de valeurs `px` ad-hoc :

| Token | Valeur | Usage |
|---|---|---|
| `--fs-micro` | 9px | dates, chips calendrier |
| `--fs-label` | 11px | surtitres, cartels Montserrat |
| `--fs-small` | 13px | légendes, petits textes |
| `--fs-body` | 15px | corps courant |
| `--fs-body-lg` | 17px | chapô, corps large |
| `--fs-lead` | 20px | sous-titres, titres de carte |
| `--fs-h4` | 24px | — |
| `--fs-h3` | 30px | — |
| `--fs-h2` | 42px | titres de section |
| `--fs-hero-sub` | 54px | h1 des sous-pages |
| `--fs-hero` | 66px | h1 accueil |

Familles : `--font-serif` (Cormorant), `--font-sans` (Inter), `--font-label` (Montserrat). Rayons : `--r-sm/md/lg/`**`card`**`/xl/pill/circle` — **`--r-card` (14px) est le rayon unique des cartes de contenu** ; `--r-xl` (16px) reste réservé aux grands panneaux « écrin » (héros, encarts sombres pleine largeur). Espacements : `--sp-1..8` (8→80px). Teintes or : `--gold-tint` (`rgba(201,168,76,.2)`, fond de pastille), `--gold-tint-border` (`.4`, filet), `--card-glass` (verre translucide). Navy de dégradé : `--bleu-deep` (`#0A1F38`, fond profond des héros/cartes, partenaire de `--bleu`), `--bleu-mid` (`#2D5A8E`, tuiles avatar).

### Named Rules
**La Règle de l'Italique Dorée.** L'emphase dans un titre se fait par `<em>` en italique Cormorant couleur or — jamais par du gras coloré ni du soulignement. C'est la signature typographique du club.

**La Règle du Token.** Tout nouvel élément réutilise les tokens `:root` (taille, rayon, espacement, couleur) plutôt qu'une valeur `px`/hex en dur. Le futur thème WordPress/ACF s'appuie sur cette échelle. Les titres de section utilisent `--fs-h2` (jamais une taille en dur).

**Composants partagés (Lot B).** Onglets = `.tab-bar` / `.tab-bar-inner` (composant unique, pages Activités **et** Tournois). Compte à rebours = `.cd` décliné en `.cd-sm` / `.cd-md` / `.cd-lg` (un seul balisage, IDs `*-cd-*` conservés pour le JS du décompte). Cartes de contenu = `.card` (+ `.card.card-lg`), panneau marine = `.panel`, événements agenda = `.agenda-event`. Le hero de la page FIJ utilise `.hero-shared` comme les 11 autres sous-pages (pas de hero « maison »).

**Factorisation des cartes — état & feuille de route.** Sont déjà en classes : cartes de contenu simples (`.card`), panneaux marine (`.panel`), événements agenda (`.agenda-event`). **Restent volontairement en inline** les cartes à structure propre : cartes de stages (en-tête média bord-à-bord + corps), chips de spécifications (Format/Rondes/Tarif, étiquettes de données), cartes de dotation FIJ (en-tête dégradé à lettre géante). Chacune demanderait sa **propre** classe (`.card-media`, `.spec-chip`, `.prize-card`…) et la réécriture des surcharges responsive `[style*="…"]` qui les ciblent — pour **zéro gain visuel** et un vrai risque de régression mobile. Décision : cette componentisation fine est **réservée à la reconstruction WordPress** (blocs ACF / template parts), où elle a du sens et ne sera pas jetée. Ne pas la forcer sur le site statique provisoire.

**La Règle des Icônes.** L'iconographie d'**interface** (mobilier de page : cartes forces, blocs contact, onglets, méta, footer, dotations) utilise le jeu SVG au trait — sprite `<symbol id="ic-…">` en tête de `<body>`, appelé via `<svg class="ic"><use href="#ic-…"/></svg>`, hérite de `currentColor`. **Jamais d'emoji dans l'interface.** Les emoji restent réservés au **contenu éditorial** (corps des articles `ARTICLES[]`, champ `emoji:` des vignettes) que Romuald/Marlies rédigent. Podiums/dotations : icône `#ic-medal` colorée (or / `#9AA0AE` / `#C08457`).

## 4. Elevation

Système **majoritairement plat avec relief discret par ombres douces**. Les surfaces reposent à plat ; le relief apparaît surtout en réponse à un état (survol) ou pour détacher une carte de son fond. Les ombres sont teintées noir d'encre (`rgba(15,14,23,…)`), jamais du gris neutre — la profondeur reste dans la famille chromatique de la marque.

### Shadow Vocabulary
- **Ombre Légère** (`box-shadow: 0 2px 8px rgba(15,14,23,.10)`) : cartes au repos, éléments flottants discrets.
- **Ombre Moyenne** (`box-shadow: 0 4px 16px rgba(15,14,23,.13)`) : cartes actives, panneaux détachés.
- **Ombre Ample** (`box-shadow: 0 8px 32px rgba(15,14,23,.18)`) : modales, éléments de premier plan.
- **Halo Or** (`box-shadow: 0 0 0 3px rgba(201,168,76,.25)`) : anneau de focus / mise en avant dorée, réservé aux états actifs et accents à célébrer.

### Named Rules
**La Règle de l'Ombre Encre.** Toute ombre est teintée noir d'encre (`rgba(15,14,23,…)`), jamais `rgba(0,0,0,…)` pur ni gris. La profondeur appartient à la palette.

## 5. Components

### Buttons
- **Shape:** coins doucement arrondis (`border-radius: 8px`). Tailles : `.btn-sm` (10/20px), défaut (14/28px), `.btn-lg` (18/40px). Typographie Montserrat 12px, 700, MAJUSCULES, letter-spacing 0.08em.
- **Primary (`.btn-gold`):** fond Or Trophée, texte noir d'encre, ombre dorée `0 4px 16px rgba(201,168,76,.3)`.
- **Hover / Focus:** l'or fonce vers le bronze (`#B8963C`), le bouton se soulève (`translateY(-2px)`), l'ombre s'intensifie. Transition `all .2s`.
- **Outline:** `.btn-outline-dark` (contour bleu marine, se remplit de marine au survol), `.btn-outline-white` et `.btn-outline-gold` pour les fonds sombres.

### Cards / Containers
- **Corner Style:** 8px à 16px selon la densité (12px le plus courant).
- **Background:** ivoire ou blanc sur fond clair ; noir ardoise (`#1A1925`) sur les sections sombres.
- **Shadow Strategy:** Ombre Légère au repos, Ombre Moyenne au survol (voir Elevation).
- **Border:** filet discret `1px solid #E5E0D8` quand une séparation est nécessaire.
- **Internal Padding:** échelle 16–24px.

### Navigation
- Barre supérieure, liens en Montserrat/Inter, état actif souligné ou coloré en or. Router CSS `#app > .page.active` (SPA mono-fichier). Traitement mobile : menu condensé, cibles tactiles généreuses (≥44px).

### Signature — Le Fond Échiquier
`.chess-bg` : motif d'échiquier très subtil en `repeating-conic-gradient` bleu marine à 5% d'opacité, maille 48px. Texture de fond signature qui évoque l'échiquier sans jamais distraire. À réserver aux fonds de section, jamais derrière du texte dense.

### Signature — Le Filet Or
`.gold-bar` : trait or de 48×3px, `border-radius: 2px`, qui souligne un surtitre ou ouvre une section. Marqueur rythmique de la marque.

## 6. Do's and Don'ts

### Do:
- **Do** garder l'or comme voix d'accent unique et minoritaire (Règle de la Voix Unique).
- **Do** poser l'or sur fond sombre ou ivoire uniquement (Règle de l'Écrin).
- **Do** marquer l'emphase des titres par `<em>` italique doré (Règle de l'Italique Dorée).
- **Do** teinter toutes les ombres en noir d'encre `rgba(15,14,23,…)`.
- **Do** viser un contraste ≥ 4.5:1 sur le texte courant — public seniors + enfants. Corps en `#374151` ou plus foncé, jamais du gris pâle.
- **Do** garder des cibles tactiles ≥ 44px et respecter `prefers-reduced-motion` sur les survols/relèvements.

### Don't:
- **Don't** faire un **site associatif daté** : pas de template années 2000, pas de tableaux de mise en page, pas de couleurs criardes, pas de Comic Sans, aucun rendu amateur. (Anti-référence n°1 du PRODUCT.md.)
- **Don't** introduire un second accent saturé (vert, rouge, violet) à côté de l'or.
- **Don't** utiliser du gris clair « pour faire chic » sur du texte — c'est illisible pour le lectorat.
- **Don't** poser du texte de couleur en dégradé (`background-clip: text`) — l'emphase se fait par l'italique dorée, le poids ou la taille.
- **Don't** utiliser des bordures latérales colorées (`border-left` > 1px) comme accent sur les cartes ou encadrés.
- **Don't** empiler des cartes identiques à l'infini (icône + titre + texte) ni imbriquer des cartes.
- **Don't** monter un titre display au-dessus de 66px ni utiliser une ombre `rgba(0,0,0,…)` grise.
