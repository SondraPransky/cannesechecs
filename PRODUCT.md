# Product

## Register

brand

## Platform

web

## Users

Trois publics principaux, tous francophones, sur la Côte d'Azur :

- **Familles et parents** cherchant une activité pour leurs enfants (initiation, cours, tournois jeunes). Souvent peu experts en échecs, ils évaluent le sérieux et l'accueil du club.
- **Joueurs et compétiteurs** (ados et adultes) intéressés par les cours, les compétitions FFE et le niveau du club.
- **Membres actuels et grand public local** venant chercher une info pratique : horaires, adresse, actualités, palmarès, contact.

Contexte d'usage : majoritairement mobile, souvent des personnes âgées ou des enfants dans le lectorat. La lisibilité prime.

## Product Purpose

Site vitrine du club Cannes Échecs (cannes-echecs.fr), club d'échecs du centre-ville de Cannes depuis 1985 : plus de 200 membres, 10× Champion de France Jeunes. Le site présente le club, ses cours, ses compétitions, son palmarès, son équipe et ses actualités, et convertit les visiteurs en adhérents ou participants (adhésion HelloAsso, inscription aux cours et tournois).

Succès = un site qui inspire confiance au premier coup d'œil, donne envie de pousser la porte du club, et reste simple à maintenir par des bénévoles non-techniciens (transition prévue vers WordPress + ACF).

## Brand Personality

**Prestige, tradition, héritage.** Le ton est élégant et institutionnel sans être froid : on assume 40 ans d'histoire, un palmarès de champions et le cadre Côte d'Azur haut de gamme. Voix posée, sérieuse, fière de son excellence compétitive — mais toujours accueillante, jamais hautaine. L'élégance doit rester au service de l'accueil : un club où le prestige rassure plutôt qu'il n'intimide.

## Anti-references

- **Le site associatif daté** : c'est l'anti-référence numéro un. Pas de template années 2000, pas de tableaux de mise en page, pas de couleurs criardes, pas de Comic Sans, aucun rendu amateur ou bricolé. Le club a un vrai palmarès ; le site doit être à la hauteur.
- Par extension : rien qui fasse « bénévole qui a fait ça vite fait ». La finition doit être irréprochable même si l'outil derrière est simple.

## Design Principles

1. **La lisibilité avant l'esthétique.** Le lectorat inclut des seniors et des enfants. Gros textes, contrastes francs, cibles tactiles généreuses — jamais du gris pâle « pour faire chic ».
2. **Le prestige se montre, il ne se crie pas.** L'or et le bleu marine, la typo serif, le palmarès : l'élégance vient de la retenue et de la cohérence, pas de la surcharge.
3. **Preuve par le palmarès.** Montrer les titres, les champions, l'ancrage depuis 1985 plutôt que de se décrire avec des adjectifs. Les faits portent la crédibilité.
4. **Simple à maintenir par des non-techniciens.** Toute évolution de contenu doit rester faisable par Romuald et Marlies (aujourd'hui panneau `?admin`, demain WordPress/ACF). Ne jamais concevoir une feature qui exige d'éditer du code pour du contenu courant.
5. **Mobile d'abord, Côte d'Azur en fond.** Pensé pour l'écran de téléphone d'un parent qui cherche les horaires, avec l'identité visuelle premium qui rassure sur le sérieux du club.

## Accessibility & Inclusion

Cible **WCAG 2.1 AA**, avec une attention renforcée pour les **seniors et les enfants** :

- Contraste du texte courant ≥ 4.5:1 (viser mieux sur les fonds teintés).
- Corps de texte confortable, pas de gris clair décoratif.
- Cibles tactiles ≥ 44px, navigation clavier complète, focus visibles.
- Respect de `prefers-reduced-motion` sur toute animation.
- Alternatives textuelles sur les images signifiantes (photos équipe, logos partenaires).
