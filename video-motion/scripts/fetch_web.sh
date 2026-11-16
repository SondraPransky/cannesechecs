#!/bin/bash
# Retélécharge les photos web utilisées dans la vidéo (sites du club, FFE, presse).
cd "$(dirname "$0")/../assets/web" || exit 1
g(){ [ -s "$1" ] || curl -sL -o "$1" "$2"; printf '%-14s %s\n' "$1" "$(file -b "$1" | cut -c1-30)"; }
P=https://prod-csm.fr/wp-content/uploads
C=https://www.cannes-echecs.fr/photo/art/grande
g louis1.jpg  $P/2023/08/Louis_Auribault_1.jpg
g louis2.jpg  $P/2023/08/Louis_Auribault_2.jpg
g rajat1.jpg  $P/2023/11/405853979_757963239693126_890808994175288336_n.jpg
g eu1.jpg     $P/2023/09/Anu_Bat_ecu.jpg
g agen1.jpg   $P/2023/05/72758743-50610451.jpg
g fj1.jpg     $C/60030856-43997827.jpg
g fj6.jpg     $C/60030856-43997840.jpg
g fj2.jpg     $C/60030856-43997854.jpg
g tj2012.jpg  https://pariscotedazur.fr/static/pictures/article_3783/cannes-echecs.jpg
g tj2019.jpg  "$C/34674691-31647426.jpg?v=1560251333"
g tjchamp.jpg $C/65203462-46576086.jpg
g tj2e.jpg    https://www.echecs.asso.fr/Actus/14930/cannes.jpg
g anu1.jpg    $C/79764760-57708703.jpg
g anu3.jpg    $C/79764760-57708716.jpg
g anu4.jpg    $C/79764760-57708718.jpg
