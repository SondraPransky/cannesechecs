# Guide de mise à jour — Site Cannes Échecs

Pour Romuald de Labaca et Marlies Bensdorp De Labaca

---

## 1. Changer le widget de la page d'accueil

L'encart en haut à droite de la page d'accueil peut afficher différentes informations selon la période (FIJ, rentrée, tournoi PICO, etc.).

**Comment faire :**
1. Aller sur : `https://cannes-echecs.fr/?admin`
2. Un panneau apparaît en bas à droite de l'écran
3. Cliquer sur le widget à afficher
4. Le changement est immédiat et mémorisé

**Widgets disponibles :**
- 🔄 Auto — s'adapte automatiquement à la période (rentrée en juin-août, FIJ le reste de l'année)
- ★ FIJ 2027 — compte à rebours pour le Festival International des Jeux
- 🍂 Rentrée — annonce la reprise des cours en septembre
- ♟ PICO mensuel — annonce le prochain tournoi mensuel
- 🐣 Open Pâques — annonce le tournoi de Pâques
- 🏅 Qualification — annonce les championnats en cours
- 📰 Actualité — met en avant une actualité libre

---

## 2. Mettre à jour les liens HelloAsso

Les liens d'inscription aux événements (adhésion, PICO, FIJ, Pâques) sont regroupés dans le fichier `index.html`, ligne ~2530, dans la constante `HELLOASSO`.

**Comment faire :**
1. Créer l'événement sur HelloAsso et copier l'URL
2. Contacter Mathieu Choisy (`mchoisy@echecs.com`) avec l'URL
3. Mathieu ou Claude Code remplace le placeholder `LIEN-*` correspondant

Les placeholders actuels à remplacer :
- `LIEN-ADHESION-2026-2027` — lien adhésion
- `LIEN-FIJ-2027` — lien inscription FIJ
- `LIEN-OPEN-PAQUES-2027` — lien Open de Pâques
- `LIEN-PICO-SEP-2026` à `LIEN-PICO-JUN-2027` — 9 dates PICO

---

## 3. Ajouter une actualité / modifier le contenu

Pour toute modification du contenu du site (textes, dates, prix, articles) :

**Contacter Mathieu Choisy** (`mchoisy@echecs.com`) qui effectuera la modification via Claude Code, ou ouvrez une session Claude Code sur le projet `SiteACE`.

La migration vers WordPress (prévue prochainement) permettra de faire ces mises à jour directement depuis une interface simple, sans passer par le code.

---

## 4. Déployer les modifications sur le site en ligne

Après toute modification du fichier `index.html` :

```
git push origin master:main
```

Le site est mis à jour automatiquement en quelques minutes sur `cannes-echecs.fr`.

---

## 5. Suivi des joueurs en tournoi (site WordPress)

L'onglet **Tournois › En direct** du site montre, ronde après ronde, les résultats des joueurs du club dans les tournois FFE et à l'étranger. Il se met à jour **tout seul** toutes les quelques minutes : il n'y a rien à faire au quotidien.

Tout se règle dans l'admin WordPress, menu **Suivi tournoi** (colonne de gauche).

**Ajouter des joueurs** — onglet *Joueurs* :
- un joueur : taper son **code FFE** (sur sa fiche du site de la FFE, par exemple `S50192`), puis *Ajouter le joueur* ;
- tout le club d'un coup : *Ajouter tous les joueurs d'un club* (311 = Cannes Échecs), avec un Elo minimum si besoin. Les joueurs déjà suivis ne sont pas ajoutés deux fois.

**Retirer un joueur** — onglet *Joueurs*, bouton *Retirer* sur sa ligne.

**Un tournoi qui n'a rien à faire là ?** (fiche de test d'un organisateur, homonyme…) — onglet *Suivi*, bouton *Masquer* sur la ligne. Pour le faire revenir : onglet *Tournois masqués*, *Réafficher*.

**Après l'installation (ou une longue coupure)** — onglet *Suivi*, bouton *Lancer le scan complet*, et **laisser la page ouverte** jusqu'à la fin (environ une heure). Sans cela, le suivi met environ une journée à tout trouver.

**Un message d'erreur dans l'onglet Suivi ?** Le plus souvent, le site de la FFE était lent ou indisponible : le suivi réessaie tout seul aux passages suivants.

**« Une mise à jour du suivi est en cours » ?** Attendre une minute et recommencer.
