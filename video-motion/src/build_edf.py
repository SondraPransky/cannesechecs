# Génère edf.html : scène « Des joueurs et un entraîneur en équipe de France »
# 14 médaillons (photos validées par Mathilde) + les autres noms en doré.
import os
D = 190
IN = D - 14
# (image, prénom, nom, mode, données) — 'old' : médaillon recadré dans l'ancienne vidéo (330x330, cercle centré)
# 'new' : photo source (largeur, hauteur, centre visage x/y en fraction, largeur visage en fraction)
P = [
    ("img/old_Timothe.png", "Timothé", "Razafindratsima", "old", None),
    ("img/old_Louis.png", "Louis", "Auribault", "old", None),
    ("img/old_Rajat.png", "Rajat", "Makkar", "old", None),
    ("img/old_Anu.png", "Anu", "Bat", "old", None),
    ("img/Bilguun_Bat.jpg", "Bilguun", "Bat", "new", (510, 600, .49, .32, .22)),
    ("img/old_Gary.png", "Gary", "Giroyan", "old", None),
    ("img/old_Guillaume.png", "Guillaume", "Lamard", "old", None),
    ("img/Cyrielle_Duchatel.jpg", "Cyrielle", "Duchatel", "new", (1440, 1440, .545, .133, .146)),
    ("img/Emilie_Alfano.jpg", "Emilie", "Alfano", "new", (403, 510, .466, .194, .215)),
    ("img/Emilie_Negre.jpg", "Emilie", "Nègre", "new", (475, 575, .43, .45, .46)),
    ("img/Chjara_Sabiani.jpg", "Chjara", "Sabiani", "new", (861, 576, .746, .262, .052)),
    ("img/Marc_Llari.jpg", "Marc", "Llari", "new", (338, 600, .60, .286, .095)),
    ("img/Melissa_Levacic.jpg", "Melissa", "Levacic", "new", (200, 200, .36, .46, .39)),
    ("img/old_Romuald.png", "Romuald", "de Labaca", "old", None),
]
OTH = ["Mathieu Acher", "Mélanie Vérot", "Julia Laurens", "Pierre-Basile Coiffait",
       "Axel Bergougnoux", "Lucas Benassayag", "Robert Vuillermoz", "Cécile Haussernot",
       "Laura Neel", "Kevin Bordi", "Arnaud Rainfray", "Maxime Sorin", "Matthieu Cornette"]

med = ''
for k, (u, fn, ln, mode, d) in enumerate(P):
    cx = 960 + ((k % 7) - 3) * 255
    top = 180 if k < 7 else 500
    if mode == 'old':
        s = IN / 286
        bs = f"{330*s:.0f}px {330*s:.0f}px"
        bp = f"{-22*s:.0f}px {-22*s:.0f}px"
    else:
        W, H, fx, fy, fw = d
        sc = max(.42 * IN / (fw * W), IN / min(W, H))
        w, h = W * sc, H * sc
        px = min(0, max(IN - w, IN / 2 - fx * w))
        py = min(0, max(IN - h, IN * .45 - fy * h))
        bs = f"{w:.0f}px {h:.0f}px"
        bp = f"{px:.0f}px {py:.0f}px"
    med += f'''<div class="abs emed" style="left:{cx-D/2:.0f}px;top:{top}px;width:{D}px">
  <div class="ering" style="width:{D}px;height:{D}px;border-radius:50%;padding:7px;background:conic-gradient(from 0deg,var(--goldXL),var(--goldL),#8a7440,var(--goldXL));box-shadow:0 16px 50px rgba(0,0,0,.5)"><div style="width:100%;height:100%;border-radius:50%;background:url({u}) {bp}/{bs} no-repeat;background-color:var(--navy2)"></div></div>
  <div class="enm" style="text-align:center;margin:12px -50px 0;line-height:1.1"><div style="font-weight:800;font-size:19px;text-transform:uppercase;letter-spacing:.06em">{fn}</div><div class="g" style="font-weight:900;font-size:22px;text-transform:uppercase;white-space:nowrap">{ln}</div></div>
</div>
'''
oth = ' <span class="dot">·</span> '.join(f'<span class="on">{n}</span>' for n in OTH)

html = open(os.path.join(os.path.dirname(__file__), 'edf_template.html')).read()
html = html.replace('%%MED%%', med).replace('%%OTH%%', oth)
open(os.path.join(os.path.dirname(__file__), 'edf.html'), 'w').write(html)
print('edf.html écrit')
