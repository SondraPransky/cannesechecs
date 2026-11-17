#!/bin/bash
# Remonte la vidéo à partir de la version 60 s renvoyée par Mathilde :
#  - garde 0 → 22,5 s (intro, chiffres, podiums)
#  - remplace la scène équipe de France (22,5 → 28,125 s) par edf.html rendue
#  - supprime la scène « Champions de France des collèges » (28,125 → 31,875 s)
#  - garde 31,875 s → fin
# Usage : compose.sh ffmpeg dossier_images_edf sortie.mp4
set -e
FF="$1"; EDF="$2"; OUT="$3"
SRC="$(dirname "$0")/../assets/uploads/cannes-echecs-motion-60s-v2.mp4"
"$FF" -y -loglevel error -i "$SRC" -framerate 30 -i "$EDF/%04d.jpg" -filter_complex "\
[0:v]trim=start_frame=0:end_frame=675,setpts=PTS-STARTPTS[a];\
[1:v]scale=1920:1080,format=yuv420p,setpts=PTS-STARTPTS[b];\
[0:v]trim=start_frame=956,setpts=PTS-STARTPTS[c];\
[a][b][c]concat=n=3:v=1:a=0[v];\
[0:a]atrim=0:28.125,asetpts=PTS-STARTPTS[x];\
[0:a]atrim=start=31.875,asetpts=PTS-STARTPTS[y];\
[x][y]concat=n=2:v=0:a=1[aud]" \
-map "[v]" -map "[aud]" -c:v libx264 -crf 22 -preset slow -pix_fmt yuv420p -c:a aac -b:a 192k -movflags +faststart "$OUT"
