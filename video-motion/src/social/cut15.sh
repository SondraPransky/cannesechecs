#!/bin/bash
# Montage pub 15 s (8 mesures à 128 BPM) à partir de la vidéo principale, coupes calées sur les temps.
# Usage : cut15.sh ffmpeg video_principale.mp4 sortie_16x9.mp4
set -e
FF="$1"; SRC="$2"; OUT="$3"
# segments (s) : Jeune·Dynamique·Ambitieux | chiffres | recordman de France | équipe de France | prochain champion | fin
"$FF" -y -loglevel error -i "$SRC" -filter_complex "\
[0:v]trim=7.5:11.25,setpts=PTS-STARTPTS[a];\
[0:v]trim=13.125:15,setpts=PTS-STARTPTS[b];\
[0:v]trim=18.75:22.5,setpts=PTS-STARTPTS[c];\
[0:v]trim=25.3125:28.125,setpts=PTS-STARTPTS[d];\
[0:v]trim=48.75:50.625,setpts=PTS-STARTPTS[e];\
[0:v]trim=51.5625:52.5,setpts=PTS-STARTPTS[f];\
[a][b][c][d][e][f]concat=n=6:v=1:a=0,fps=30[v];\
[0:a]atrim=7.5:22.5,asetpts=PTS-STARTPTS,afade=t=out:st=14.2:d=0.8[aud]" \
-map "[v]" -map "[aud]" -t 15 -c:v libx264 -crf 18 -preset medium -pix_fmt yuv420p -c:a aac -b:a 192k -movflags +faststart "$OUT"
