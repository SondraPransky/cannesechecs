# Génère les pages HTML des visuels réseaux sociaux (posts, stories, bannières, photo de profil)
# et les calques PNG transparents des pubs vidéo. Rendu ensuite par render.js.
import os, json
HERE = os.path.dirname(os.path.abspath(__file__))
OUT = os.path.join(HERE, 'pages'); os.makedirs(OUT, exist_ok=True)
LOGO = '../../img/logo-cannes-echecs.png'
PH = lambda n: n if '/' in n else f'../../../../photos/{n}.jpg'
ADR = '3 avenue du Petit Juas · Cannes'
TEL = '04 93 39 41 39'
URL = 'cannes-echecs.fr'

CSS = '''<link href="../../fonts.css" rel="stylesheet"><style>
:root{--navy:#141F43;--navy2:#1C2A58;--navy3:#0D1530;--gold:#83713F;--goldL:#C9B06A;--goldXL:#E3CF94}
*{margin:0;padding:0;box-sizing:border-box}
html,body{width:%(W)spx;height:%(H)spx;overflow:hidden;background:%(BG)s}
body{position:relative;font-family:Montserrat,sans-serif;color:#fff}
.abs{position:absolute}
.g{background:linear-gradient(180deg,var(--goldXL) 0%%,var(--goldL) 45%%,#A88F4E 100%%);-webkit-background-clip:text;background-clip:text;color:transparent}
.h{font-weight:900;text-transform:uppercase;line-height:.98;letter-spacing:-.005em;text-shadow:0 6px 30px rgba(13,21,48,.55)}
.k{font-weight:800;text-transform:uppercase;letter-spacing:.32em;color:var(--goldXL)}
.pill{display:inline-block;background:linear-gradient(180deg,var(--goldXL),var(--goldL) 55%%,#A88F4E);color:var(--navy);font-weight:900;text-transform:uppercase;letter-spacing:.08em;border-radius:999px;box-shadow:0 10px 30px rgba(0,0,0,.35)}
.ph{position:absolute;background-size:cover;background-position:center}
.fr{position:absolute;border:2px solid rgba(201,176,106,.6)}
.foot{font-weight:700;color:rgba(255,255,255,.88);letter-spacing:.04em}
.foot b{color:var(--goldXL)}
.logo{position:absolute;background:url(%(LOGO)s) center/contain no-repeat}
</style>'''


def page(name, W, H, body, bg='#141F43'):
    css = CSS % dict(W=W, H=H, BG=bg, LOGO=LOGO)
    open(os.path.join(OUT, name + '.html'), 'w').write(f'<!doctype html><html lang="fr"><head><meta charset="utf-8">{css}</head><body>{body}</body></html>')
    return dict(name=name, W=W, H=H, transparent=(bg == 'transparent'))


def foot(size, sep=' · '):
    return f'<div class="foot" style="font-size:{size}px">{ADR}{sep}{TEL}{sep}<b>{URL}</b></div>'


J = []

# ---------- POSTS & STORIES ----------
# 1. Post 4:5 — Rejoins Cannes Échecs
J.append(page('post-4x5-rejoins', 1080, 1350, f'''
<div class="ph" style="left:0;top:0;width:1080px;height:860px;background-image:url({PH('picomars3')})"></div>
<div class="abs" style="left:0;top:0;width:1080px;height:860px;background:linear-gradient(180deg,rgba(20,31,67,.55) 0%,rgba(20,31,67,0) 30%,rgba(20,31,67,.15) 60%,#141F43 100%)"></div>
<div class="logo" style="left:60px;top:52px;width:150px;height:150px"></div>
<div class="abs" style="left:70px;right:70px;top:620px">
  <div class="k" style="font-size:24px;margin-bottom:22px">Club d'échecs · Cannes</div>
  <div class="h" style="font-size:112px">Rejoins<br><span class="g">Cannes Échecs</span></div>
  <div style="font-size:34px;font-weight:700;margin-top:26px;color:rgba(255,255,255,.92)">Le club recordman de France chez les jeunes</div>
  <div class="pill" style="font-size:28px;padding:20px 40px;margin-top:40px">Cours d'essai sur demande</div>
</div>
<div class="abs" style="left:70px;right:70px;bottom:52px">{foot(22)}</div>
<div class="fr" style="inset:24px"></div>'''))

# 2. Post 4:5 — Et si le prochain champion / la prochaine championne, c'était toi ?
def champ_post(name, photo, titre):
    J.append(page(name, 1080, 1350, f'''
<div class="ph" style="inset:0;background-image:url({PH(photo)});background-position:50% 30%"></div>
<div class="abs" style="inset:0;background:linear-gradient(180deg,rgba(20,31,67,.35) 0%,rgba(20,31,67,0) 25%,rgba(20,31,67,.25) 48%,rgba(20,31,67,.92) 70%,#141F43 100%)"></div>
<div class="logo" style="right:60px;top:52px;width:150px;height:150px"></div>
<div class="abs" style="left:70px;right:70px;top:740px">
  <div class="h" style="font-size:92px">{titre}</div>
  <div class="pill" style="font-size:30px;padding:20px 44px;margin-top:40px">Rejoins le club !</div>
</div>
<div class="abs" style="left:70px;right:70px;bottom:52px">{foot(22)}</div>
<div class="fr" style="inset:24px"></div>'''))


T_GARCON = 'Et si le prochain<br><span class="g">champion,</span><br>c\'était toi ?'
T_FILLE = 'Et si la prochaine<br><span class="g">championne,</span><br>c\'était toi ?'
champ_post('post-4x5-prochain-champion', 'topjeunes_15', T_GARCON)
champ_post('post-4x5-prochaine-championne', 'topjeunes_10', T_FILLE)
champ_post('post-4x5-prochaine-championne-2', 'mondejeunes2', T_FILLE)
champ_post('post-4x5-prochaine-championne-3', 'mondejeunes', T_FILLE)
champ_post('post-4x5-prochaine-championne-4', '../../img/Cyrielle_Duchatel.jpg', T_FILLE)
for k, ph in enumerate(['topjeunes_11', 'topjeunes_13', 'topjeunes_14', 'topjeunes_16', 'topjeunes_17'], 2):
    champ_post(f'post-4x5-prochain-champion-{k}', ph, T_GARCON)

# 3. Post carré — Des cours pour tous
J.append(page('post-carre-cours-pour-tous', 1080, 1080, f'''
<div class="ph" style="inset:0;background-image:url({PH('topjeunes_8')})"></div>
<div class="abs" style="inset:0;background:linear-gradient(180deg,rgba(20,31,67,.5) 0%,rgba(20,31,67,.15) 30%,rgba(20,31,67,.85) 62%,#141F43 100%)"></div>
<div class="logo" style="left:60px;top:52px;width:140px;height:140px"></div>
<div class="abs" style="left:70px;right:70px;top:610px">
  <div class="h" style="font-size:104px">Des cours<br><span class="g">pour tous</span></div>
  <div style="font-size:32px;font-weight:800;margin-top:22px;text-transform:uppercase;letter-spacing:.12em;color:var(--goldXL)">Enfants · Ados · Adultes</div>
  <div style="font-size:28px;font-weight:600;margin-top:10px;color:rgba(255,255,255,.9)">Du premier coup au haut niveau</div>
</div>
<div class="abs" style="left:70px;right:70px;bottom:52px;display:flex;align-items:center;justify-content:space-between">
  <div class="pill" style="font-size:24px;padding:16px 32px">Cours d'essai sur demande</div>
  <div class="foot" style="font-size:22px;text-align:right">{TEL}<br><b>{URL}</b></div>
</div>
<div class="fr" style="inset:24px"></div>'''))

# 4. Post carré — Les chiffres
num = lambda n, u, l: f'''<div style="flex:1;text-align:center"><div style="font-family:'Cormorant Garamond',serif;font-weight:700;font-size:150px;line-height:1"><span class="g">{n}</span><span class="g" style="font-size:80px">{u}</span></div>
<div style="font-size:21px;font-weight:800;text-transform:uppercase;letter-spacing:.14em;margin-top:14px;line-height:1.35">{l}</div></div>'''
J.append(page('post-carre-chiffres', 1080, 1080, f'''
<div class="ph" style="inset:0;background-image:url({PH('chptdep')});opacity:.22"></div>
<div class="abs" style="inset:0;background:radial-gradient(ellipse at 50% 45%,rgba(28,42,88,.55),#0D1530 80%)"></div>
<div class="logo" style="left:465px;top:70px;width:150px;height:150px"></div>
<div class="abs" style="left:0;right:0;top:260px;text-align:center"><div class="k" style="font-size:24px">Cannes Échecs en chiffres</div></div>
<div class="abs" style="left:50px;right:50px;top:350px;display:flex;gap:10px">
  {num('200','+','membres<br>au club')}{num('10','×','titres Top Jeunes<br>record de France')}{num('30','','ans dans<br>les écoles')}
</div>
<div class="abs" style="left:0;right:0;top:740px;text-align:center">
  <div class="h" style="font-size:58px">Rejoins un club <span class="g">qui gagne</span></div>
  <div class="pill" style="font-size:26px;padding:18px 40px;margin-top:30px">Cours d'essai sur demande</div>
</div>
<div class="abs" style="left:0;right:0;bottom:52px;text-align:center">{foot(21)}</div>
<div class="fr" style="inset:24px"></div>'''))

# 5. Story 9:16 — Rejoins le club (zones sûres : 250 px en haut, 340 px en bas)
J.append(page('story-9x16-rejoins', 1080, 1920, f'''
<div class="ph" style="left:0;top:0;width:1080px;height:1150px;background-image:url({PH('chptdep')})"></div>
<div class="abs" style="left:0;top:0;width:1080px;height:1150px;background:linear-gradient(180deg,rgba(20,31,67,.7) 0%,rgba(20,31,67,.1) 28%,rgba(20,31,67,.2) 60%,#141F43 100%)"></div>
<div class="logo" style="left:440px;top:250px;width:200px;height:200px"></div>
<div class="abs" style="left:70px;right:70px;top:1000px;text-align:center">
  <div class="h" style="font-size:120px">Rejoins<br><span class="g">Cannes Échecs</span></div>
  <div style="font-size:36px;font-weight:700;margin-top:28px;color:rgba(255,255,255,.92)">Le club recordman de France<br>chez les jeunes</div>
  <div class="pill" style="font-size:32px;padding:24px 50px;margin-top:44px">Cours d'essai sur demande</div>
  <div class="foot" style="font-size:28px;margin-top:40px;line-height:1.6">{ADR}<br>{TEL} · <b>{URL}</b></div>
</div>'''))

# 6. Story 9:16 — Prochain champion / prochaine championne
def champ_story(name, photo, titre, y=230):
    J.append(page(name, 1080, 1920, f'''
<div class="ph" style="left:0;right:0;top:{y}px;bottom:0;background-image:url({PH(photo)});background-position:50% 0"></div>
<div class="abs" style="inset:0;background:linear-gradient(180deg,#141F43 0%,#141F43 18%,rgba(20,31,67,.35) 28%,rgba(20,31,67,.1) 34%,rgba(20,31,67,.15) 48%,rgba(20,31,67,.9) 66%,#141F43 100%)"></div>
<div class="logo" style="left:450px;top:250px;width:180px;height:180px"></div>
<div class="abs" style="left:60px;right:60px;top:1180px;text-align:center">
  <div class="h" style="font-size:100px">{titre}</div>
  <div class="pill" style="font-size:32px;padding:24px 50px;margin-top:44px">Rejoins le club !</div>
  <div class="foot" style="font-size:28px;margin-top:34px"><b>{URL}</b> · {TEL}</div>
</div>'''))


champ_story('story-9x16-prochain-champion', 'topjeunes_12', T_GARCON)
champ_story('story-9x16-prochaine-championne', 'topjeunes_10', T_FILLE)
champ_story('story-9x16-prochaine-championne-2', 'mondejeunes2', T_FILLE, 340)
champ_story('story-9x16-prochaine-championne-3', 'mondejeunes', T_FILLE, 380)
champ_story('story-9x16-prochaine-championne-4', '../../img/Cyrielle_Duchatel.jpg', T_FILLE, 260)
for k, ph in enumerate(['topjeunes_11', 'topjeunes_13', 'topjeunes_14', 'topjeunes_16', 'topjeunes_17'], 2):
    champ_story(f'story-9x16-prochain-champion-{k}', ph, T_GARCON)

# ---------- BANNIÈRES & PROFIL ----------
def banner(name, W, H, logo, title, sub, photo, pos='50% 40%', cta=True):
    return page(name, W, H, f'''
<div class="ph" style="inset:0;background-image:url({PH(photo)});background-position:{pos}"></div>
<div class="abs" style="inset:0;background:linear-gradient(90deg,rgba(13,21,48,.25) 0%,rgba(20,31,67,.88) 32%,rgba(20,31,67,.88) 68%,rgba(13,21,48,.25) 100%)"></div>
<div class="abs" style="left:0;right:0;top:50%;transform:translateY(-50%);display:flex;align-items:center;justify-content:center;gap:{logo*.22:.0f}px">
  <div class="logo" style="position:relative;width:{logo}px;height:{logo}px;flex:none"></div>
  <div><div class="h" style="font-size:{title}px">Cannes <span class="g">Échecs</span></div>
  <div class="k" style="font-size:{sub}px;margin-top:{sub*.7:.0f}px;letter-spacing:.22em">Jeune · Dynamique · Ambitieux</div>
  {f'<div class="foot" style="font-size:{sub*.9:.0f}px;margin-top:{sub*.6:.0f}px;letter-spacing:.08em">Cours pour tous · <b>{URL}</b></div>' if cta else ''}</div>
</div>''')

J.append(banner('banniere-facebook-1640x624', 1640, 624, 220, 92, 24, 'topjeunes_8'))        # contenu dans les 1100 px centraux
J.append(banner('banniere-youtube-2560x1440', 2560, 1440, 300, 120, 30, 'picomars3'))        # zone sûre 1546x423
J.append(banner('banniere-linkedin-1584x396', 1584, 396, 170, 76, 20, 'topjeunes_8'))
J.append(banner('banniere-x-1500x500', 1500, 500, 190, 80, 21, 'picomars3'))
J.append(banner('image-lien-1200x630', 1200, 630, 220, 84, 22, 'chptdep'))                 # aperçu de lien / événement Facebook

J.append(page('photo-profil-1080x1080', 1080, 1080, '<div class="logo" style="left:110px;top:110px;width:860px;height:860px"></div>'))

# ---------- ENCARTS PUBLICITAIRES ----------
# Web : formats standards des régies (IAB), au pixel près.
DARK = 'linear-gradient(180deg,rgba(20,31,67,.25),rgba(20,31,67,.92))'
def ph_bg(photo, pos='50% 35%', grad=DARK, box='inset:0'):
    return (f'<div class="ph" style="{box};background-image:url({PH(photo)});background-position:{pos}"></div>'
            f'<div class="abs" style="{box};background:{grad}"></div>')

E = 'encart-'
# Pavé 300x250 et grand pavé 336x280
for W, H, s in [(300, 250, 1), (336, 280, 1.12)]:
    J.append(page(f'{E}pave-{W}x{H}', W, H, ph_bg('picomars3', grad='linear-gradient(180deg,rgba(20,31,67,.35) 0%,rgba(20,31,67,.55) 40%,#141F43 78%)') + f'''
<div class="logo" style="left:{12*s:.0f}px;top:{12*s:.0f}px;width:{54*s:.0f}px;height:{54*s:.0f}px"></div>
<div class="abs" style="left:{16*s:.0f}px;right:{16*s:.0f}px;top:{100*s:.0f}px">
  <div class="h" style="font-size:{30*s:.0f}px">Rejoins<br><span class="g">Cannes Échecs</span></div>
  <div style="font-size:{12*s:.0f}px;font-weight:700;margin-top:{6*s:.0f}px">Cours pour tous · Enfants · Ados · Adultes</div>
  <div style="display:flex;align-items:center;justify-content:space-between;margin-top:{12*s:.0f}px">
    <div class="pill" style="font-size:{11*s:.0f}px;padding:{7*s:.0f}px {13*s:.0f}px">Cours d'essai</div>
    <div class="foot" style="font-size:{11*s:.0f}px"><b>{URL}</b></div></div>
</div><div class="fr" style="inset:4px;border-width:1px"></div>'''))

# Bandeau 728x90
J.append(page(f'{E}bandeau-728x90', 728, 90, ph_bg('topjeunes_8', grad='linear-gradient(90deg,#141F43 20%,rgba(20,31,67,.82) 60%,rgba(20,31,67,.6))') + f'''
<div class="abs" style="inset:0;display:flex;align-items:center;gap:16px;padding:0 18px 0 12px">
  <div class="logo" style="position:relative;width:70px;height:70px;flex:none"></div>
  <div style="flex:1"><div class="h" style="font-size:27px">Rejoins <span class="g">Cannes Échecs</span></div>
  <div style="font-size:12px;font-weight:700;margin-top:4px;color:rgba(255,255,255,.9)">Le club recordman de France chez les jeunes · <b style="color:var(--goldXL)">{URL}</b></div></div>
  <div class="pill" style="font-size:13px;padding:11px 18px;flex:none">Cours d'essai</div>
</div>'''))

# Grand bandeau 970x250
J.append(page(f'{E}billboard-970x250', 970, 250, ph_bg('topjeunes_10', '50% 25%', 'linear-gradient(90deg,#141F43 0%,rgba(20,31,67,.6) 30%,rgba(20,31,67,0) 60%)', 'left:600px;top:0;right:0;bottom:0') + f'''
<div class="logo" style="left:28px;top:65px;width:120px;height:120px"></div>
<div class="abs" style="left:172px;top:36px;width:470px">
  <div class="h" style="font-size:34px">Et si la prochaine <span class="g">championne,</span><br>c'était toi ?</div>
  <div style="font-size:15px;font-weight:700;margin-top:10px;color:rgba(255,255,255,.9)">Cours pour tous · Enfants · Ados · Adultes</div>
  <div style="display:flex;align-items:center;gap:16px;margin-top:16px">
    <div class="pill" style="font-size:14px;padding:11px 22px">Cours d'essai sur demande</div>
    <div class="foot" style="font-size:14px"><b>{URL}</b></div></div>
</div><div class="fr" style="inset:6px;border-width:1px"></div>'''))

# Gratte-ciel 160x600
J.append(page(f'{E}gratte-ciel-160x600', 160, 600, f'''
<div class="logo" style="left:30px;top:16px;width:100px;height:100px"></div>
<div class="abs" style="left:0;right:0;top:128px;text-align:center"><div class="h" style="font-size:24px">Rejoins<br><span class="g">Cannes<br>Échecs</span></div></div>
{ph_bg('topjeunes_15', '50% 30%', 'linear-gradient(180deg,#141F43 0%,rgba(20,31,67,0) 18%,rgba(20,31,67,0) 70%,#141F43 100%)', 'left:0;right:0;top:232px;height:220px')}
<div class="abs" style="left:10px;right:10px;top:458px;text-align:center">
  <div style="font-size:12px;font-weight:700;line-height:1.35">Le club recordman<br>de France chez les jeunes</div>
  <div class="pill" style="font-size:11px;padding:9px 12px;margin-top:12px">Cours d'essai</div>
  <div class="foot" style="font-size:11px;margin-top:10px"><b>{URL}</b></div>
</div><div class="fr" style="inset:4px;border-width:1px"></div>'''))

# Grand angle 300x600
J.append(page(f'{E}grand-angle-300x600', 300, 600, ph_bg('mondejeunes2', '50% 10%', 'linear-gradient(180deg,rgba(20,31,67,.3) 0%,rgba(20,31,67,0) 20%,rgba(20,31,67,.2) 42%,#141F43 62%)') + f'''
<div class="logo" style="left:16px;top:16px;width:70px;height:70px"></div>
<div class="abs" style="left:20px;right:20px;top:300px">
  <div class="h" style="font-size:30px">Et si la prochaine<br><span class="g">championne,</span><br>c'était toi ?</div>
  <div style="font-size:13px;font-weight:700;margin-top:10px">Cours pour tous · Enfants · Ados · Adultes</div>
  <div class="pill" style="font-size:13px;padding:10px 20px;margin-top:16px">Rejoins le club !</div>
  <div class="foot" style="font-size:12px;margin-top:12px"><b>{URL}</b> · {TEL}</div>
</div><div class="fr" style="inset:5px;border-width:1px"></div>'''))

# Mobile 320x50 et 320x100
J.append(page(f'{E}mobile-320x50', 320, 50, f'''
<div class="abs" style="inset:0;display:flex;align-items:center;gap:8px;padding:0 8px 0 4px">
  <div class="logo" style="position:relative;width:42px;height:42px;flex:none"></div>
  <div style="flex:1"><div class="h" style="font-size:13px;white-space:nowrap">Rejoins <span class="g">Cannes Échecs</span></div>
  <div style="font-size:9px;font-weight:700;margin-top:2px;color:rgba(255,255,255,.85)">Cours pour tous · {URL}</div></div>
  <div class="pill" style="font-size:10px;padding:6px 10px;flex:none">Essai</div>
</div>'''))
J.append(page(f'{E}mobile-320x100', 320, 100, ph_bg('picomars3', grad='linear-gradient(90deg,#141F43 30%,rgba(20,31,67,.75))') + f'''
<div class="abs" style="inset:0;display:flex;align-items:center;gap:10px;padding:0 12px 0 8px">
  <div class="logo" style="position:relative;width:74px;height:74px;flex:none"></div>
  <div style="flex:1"><div class="h" style="font-size:20px">Rejoins<br><span class="g">Cannes Échecs</span></div>
  <div style="display:flex;align-items:center;gap:8px;margin-top:6px;white-space:nowrap"><div class="pill" style="font-size:9px;padding:6px 9px">Cours d'essai</div>
  <div class="foot" style="font-size:10px"><b>{URL}</b></div></div></div>
</div>'''))

# ---------- CALQUES DES PUBS VIDÉO (transparents) ----------
def overlay(name, W, H, vh, top_html, bot_html):
    y0 = (H - vh) // 2
    return page(name, W, H, f'''
<div class="abs" style="left:0;top:0;width:{W}px;height:{y0}px;background:linear-gradient(180deg,rgba(13,21,48,.96),rgba(20,31,67,.82))"></div>
<div class="abs" style="left:0;top:{y0+vh}px;width:{W}px;height:{H-y0-vh}px;background:linear-gradient(0deg,rgba(13,21,48,.96),rgba(20,31,67,.82))"></div>
<div class="abs" style="left:0;top:{y0-3}px;width:{W}px;height:3px;background:linear-gradient(90deg,transparent,var(--goldL),transparent)"></div>
<div class="abs" style="left:0;top:{y0+vh}px;width:{W}px;height:3px;background:linear-gradient(90deg,transparent,var(--goldL),transparent)"></div>
<div class="abs" style="left:0;top:0;width:{W}px;height:{y0}px">{top_html}</div>
<div class="abs" style="left:0;top:{y0+vh}px;width:{W}px;height:{H-y0-vh}px">{bot_html}</div>''', bg='transparent')

VH = 608  # 1920x1080 ramené à 1080 de large
J.append(overlay('calque-9x16', 1080, 1920, VH,
  '''<div class="logo" style="left:440px;top:260px;width:200px;height:200px"></div>
     <div class="abs" style="left:0;right:0;top:480px;text-align:center"><div class="h" style="font-size:64px">Cannes <span class="g">Échecs</span></div>
     <div class="k" style="font-size:22px;margin-top:14px;letter-spacing:.24em">Jeune · Dynamique · Ambitieux</div></div>''',
  f'''<div class="abs" style="left:0;right:0;top:70px;text-align:center"><div class="h" style="font-size:86px">Rejoins<br><span class="g">le club !</span></div>
     <div style="font-size:30px;font-weight:700;margin-top:22px;color:rgba(255,255,255,.92)">Cours pour tous · Enfants · Ados · Adultes</div>
     <div class="pill" style="font-size:28px;padding:20px 44px;margin-top:30px">Cours d'essai sur demande</div>
     <div class="foot" style="font-size:26px;margin-top:26px"><b>{URL}</b> · {TEL}</div></div>'''))
J.append(overlay('calque-4x5', 1080, 1350, VH,
  '''<div class="abs" style="left:0;right:0;top:0;bottom:0;display:flex;align-items:center;justify-content:center;gap:34px">
     <div class="logo" style="position:relative;width:170px;height:170px"></div>
     <div><div class="h" style="font-size:66px">Cannes <span class="g">Échecs</span></div><div class="k" style="font-size:20px;margin-top:14px;letter-spacing:.2em">Jeune · Dynamique · Ambitieux</div></div></div>''',
  f'''<div class="abs" style="left:0;right:0;top:0;bottom:0;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center">
     <div class="h" style="font-size:62px">Rejoins <span class="g">le club !</span></div>
     <div class="pill" style="font-size:26px;padding:18px 40px;margin-top:24px">Cours d'essai sur demande</div>
     <div class="foot" style="font-size:24px;margin-top:20px"><b>{URL}</b> · {TEL}</div></div>'''))
J.append(overlay('calque-1x1', 1080, 1080, VH,
  '''<div class="abs" style="left:0;right:0;top:0;bottom:0;display:flex;align-items:center;justify-content:center;gap:26px">
     <div class="logo" style="position:relative;width:150px;height:150px"></div>
     <div><div class="h" style="font-size:58px">Cannes <span class="g">Échecs</span></div><div class="k" style="font-size:18px;margin-top:12px;letter-spacing:.2em">Jeune · Dynamique · Ambitieux</div></div></div>''',
  f'''<div class="abs" style="left:0;right:0;top:0;bottom:0;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center">
     <div class="h" style="font-size:52px">Rejoins <span class="g">le club !</span></div>
     <div class="foot" style="font-size:24px;margin-top:18px">Cours d'essai sur demande · <b>{URL}</b></div></div>'''))

json.dump(J, open(os.path.join(OUT, 'jobs.json'), 'w'), indent=1)
print(len(J), 'pages')
