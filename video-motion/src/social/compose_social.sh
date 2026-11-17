#!/bin/bash
# Pubs vidéo réseaux sociaux : fond flou + vidéo 16:9 au centre + calque PNG (logo, accroche, contact).
# Usage : compose_social.sh ffmpeg pub-15s-16x9.mp4 dossier_calques dossier_sortie
set -e
FF="$1"; IN="$2"; CAL="$3"; OUT="$4"
mk() { # nom W H calque
  "$FF" -y -loglevel error -i "$IN" -i "$CAL/$4" -filter_complex "\
[0:v]split[b][f];\
[b]scale=-2:$3,crop=$2:$3,boxblur=28:2,eq=brightness=-0.12:saturation=0.8[bg];\
[f]scale=$2:-2[fg];\
[bg][fg]overlay=0:(H-h)/2[x];[x][1:v]overlay=0:0,format=yuv420p[v]" \
  -map "[v]" -map 0:a -c:v libx264 -crf 19 -preset medium -c:a aac -b:a 192k -movflags +faststart "$OUT/$1"
}
mk pub-15s-9x16-reels-stories.mp4 1080 1920 calque-9x16.png
mk pub-15s-4x5-fil-instagram-facebook.mp4 1080 1350 calque-4x5.png
mk pub-15s-1x1-carre.mp4 1080 1080 calque-1x1.png
