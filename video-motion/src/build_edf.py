# Génère edf.html : scène « Des joueurs et un entraîneur en équipe de France »
# 19 médaillons sur une seule image, 3 rangées 7+6+6 (photos validées par Mathilde) + les autres noms en doré.
import os
D = 160
IN = D - 14
# (image, prénom, nom, données) — données : (largeur, hauteur, centre du visage x/y en fraction,
# largeur du cadre visage en fraction de la largeur — mesurés par détection de visage OpenCV). old_*.png = médaillons recadrés dans l'ancienne vidéo.
P = [
    ("img/old_Timothe.png", "Timothé", "Razafindratsima", (330, 330, 0.505, 0.465, 0.524)),
    ("img/old_Louis.png", "Louis", "Auribault", (330, 330, 0.441, 0.544, 0.39)),
    ("img/old_Rajat.png", "Rajat", "Makkar", (330, 330, 0.5, 0.5, 0.36)),
    ("img/old_Anu.png", "Anu", "Bat", (330, 330, 0.517, 0.232, 0.377)),
    ("img/Bilguun_Bat.jpg", "Bilguun", "Bat", (800, 900, 0.501, 0.501, 0.338)),
    ("img/old_Gary.png", "Gary", "Giroyan", (330, 330, 0.4, 0.4, 0.3)),
    ("img/old_Guillaume.png", "Guillaume", "Lamard", (330, 330, 0.558, 0.567, 0.485)),
    ("img/Cyrielle_Duchatel.jpg", "Cyrielle", "Duchatel", (1440, 1440, 0.554, 0.14, 0.204)),
    ("img/Emilie_Alfano.jpg", "Emilie", "Alfano", (403, 510, 0.475, 0.232, 0.34)),
    ("img/Emilie_Negre.jpg", "Emilie", "Nègre", (475, 575, 0.436, 0.454, 0.594)),
    ("img/Chjara_Sabiani.jpg", "Chjara", "Sabiani", (861, 576, 0.752, 0.266, 0.078)),
    ("img/Marc_Llari.jpg", "Marc", "Llari", (338, 600, 0.602, 0.318, 0.118)),
    ("img/Laura_Neel.jpg", "Laura", "Neel", (420, 500, 0.515, 0.417, 0.326)),
    ("img/Melissa_Levacic.jpg", "Melissa", "Levacic", (200, 200, 0.306, 0.39, 0.42)),
    ("img/Melanie_Verot.jpg", "Mélanie", "Vérot", (450, 540, 0.54, 0.38, 0.293)),
    ("img/Cecile_Haussernot.jpg", "Cécile", "Haussernot", (250, 250, 0.438, 0.364, 0.276)),
    ("img/Matthieu_Cornette.jpg", "Matthieu", "Cornette", (250, 250, 0.374, 0.39, 0.388)),
    ("img/Kevin_Bordi.png", "Kevin", "Bordi", (250, 250, 0.518, 0.598, 0.62)),
    ("img/old_Romuald.png", "Romuald", "de Labaca", (330, 330, 0.492, 0.441, 0.433)),
]


def crop(d, IN):
    """Visage au centre du médaillon, cadre visage (détection OpenCV) ~52 % du cercle.
    Zoom augmenté au besoin (max x1,4) pour que le cercle reste dans la photo — pour les anciens
    médaillons old_*.png, dans le disque photo de rayon ~138 ; au-delà, on décale le moins possible."""
    W, H, fx, fy, fw = d
    cx, cy = fx * W, fy * H
    sc0 = .52 * IN / (fw * W)
    old = (W, H) == (330, 330)
    def room(x, y):
        if old:
            return 138 - ((x - 165) ** 2 + (y - 165) ** 2) ** .5
        return min(x, W - x, y, H - y)
    sc = max(sc0, IN / (2 * max(room(cx, cy), 1)))
    if sc > 1.4 * sc0:
        sc = 1.4 * sc0
        R = IN / (2 * sc)
        tx, ty = (165, 165) if old else (W / 2, H / 2)
        for i in range(101):  # glisse le centre vers le milieu de la photo jusqu'à ce que ça rentre
            x, y = cx + (tx - cx) * i / 100, cy + (ty - cy) * i / 100
            if room(x, y) >= R:
                break
        cx, cy = x, y
        sc = max(sc, IN / (2 * max(room(cx, cy), 1)))
    return f"{-(cx * sc - IN / 2):.1f}px {-(cy * sc - IN / 2):.1f}px", f"{W * sc:.1f}px {H * sc:.1f}px"


OTH = ["Mathieu Acher", "Julia Laurens", "Pierre-Basile Coiffait", "Axel Bergougnoux",
       "Lucas Benassayag", "Robert Vuillermoz", "Arnaud Rainfray", "Maxime Sorin"]

med = ''
for k, (u, fn, ln, d) in enumerate(P):
    r, j = (0, k) if k < 7 else ((1, k - 7) if k < 13 else (2, k - 13))
    n = 7 if r == 0 else 6
    cx = 960 + (j - (n - 1) / 2) * 255
    top = (155, 395, 635)[r]
    bp, bs = crop(d, IN)
    med += f'''<div class="abs emed" style="left:{cx-D/2:.0f}px;top:{top}px;width:{D}px">
  <div class="ering" style="width:{D}px;height:{D}px;border-radius:50%;padding:7px;background:conic-gradient(from 0deg,var(--goldXL),var(--goldL),#8a7440,var(--goldXL));box-shadow:0 16px 50px rgba(0,0,0,.5)"><div style="width:100%;height:100%;border-radius:50%;background:url({u}) {bp}/{bs} no-repeat;background-color:var(--navy2)"></div></div>
  <div class="enm" style="text-align:center;margin:10px -60px 0;line-height:1.1"><div style="font-weight:800;font-size:17px;text-transform:uppercase;letter-spacing:.06em">{fn}</div><div class="g" style="font-weight:900;font-size:20px;text-transform:uppercase;white-space:nowrap">{ln}</div></div>
</div>
'''
oth = ' <span class="dot">·</span> '.join(f'<span class="on">{n}</span>' for n in OTH)

html = open(os.path.join(os.path.dirname(__file__), 'edf_template.html')).read()
html = html.replace('%%MED%%', med).replace('%%OTH%%', oth)
open(os.path.join(os.path.dirname(__file__), 'edf.html'), 'w').write(html)
print('edf.html écrit')
