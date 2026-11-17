# Flyer A5 recto-verso et plaquette 4 pages A5 (A4 plié) — mise en page en mm, rendue en PDF + JPEG 300 dpi.
# Contenus repris du site (saison 2026–2027). À mettre à jour chaque saison : tarifs, horaires.
import os, json
HERE = os.path.dirname(os.path.abspath(__file__))
OUT = os.path.join(HERE, 'pages'); os.makedirs(OUT, exist_ok=True)
PH = lambda n: f'../../../../photos/{n}.jpg'
LOGO = '../../img/logo-cannes-echecs.png'
SAISON = '2026–2027'
ADR, CP = '3 avenue du Petit Juas', '06400 Cannes'
TEL, MAIL, URL = '04 93 39 41 39', 'info@cannes-echecs.fr', 'cannes-echecs.fr'

CSS = '''<link href="../../fonts.css" rel="stylesheet"><style>
@page{size:%(PW)s %(PH)s;margin:0}
:root{--navy:#141F43;--navy2:#1C2A58;--navy3:#0D1530;--gold:#83713F;--goldL:#C9B06A;--goldXL:#E3CF94;--ink:#141F43;--paper:#FBF8F1}
*{margin:0;padding:0;box-sizing:border-box}
html,body{background:#888;-webkit-print-color-adjust:exact;print-color-adjust:exact}
body{font-family:Montserrat,sans-serif;color:#fff}
.sheet{width:%(PW)s;height:%(PH)s;display:flex;page-break-after:always;overflow:hidden}
.pg{position:relative;width:148mm;height:210mm;overflow:hidden;background:var(--navy);flex:none}
.pg.light{background:var(--paper);color:var(--ink)}
.abs{position:absolute}
.ph{position:absolute;background-size:cover;background-position:center}
.g{background:linear-gradient(180deg,var(--goldXL) 0%,var(--goldL) 45%,#A88F4E 100%);-webkit-background-clip:text;background-clip:text;color:transparent}
.gd{color:#8a7440}
.h{font-weight:900;text-transform:uppercase;line-height:.98}
.k{font-weight:800;text-transform:uppercase;letter-spacing:.28em;font-size:6.5pt;color:var(--goldXL)}
.light .k{color:#8a7440}
.serif{font-family:'Cormorant Garamond',serif;font-weight:700}
.pill{display:inline-block;background:linear-gradient(180deg,var(--goldXL),var(--goldL) 55%,#A88F4E);color:var(--navy);font-weight:900;text-transform:uppercase;letter-spacing:.08em;border-radius:99mm}
.logo{position:absolute;background:url(%(LOGO)s) center/contain no-repeat}
.fr{position:absolute;inset:5mm;border:.35mm solid rgba(201,176,106,.6)}
.light .fr{border-color:rgba(131,113,63,.45)}
.rule{height:.4mm;width:18mm;background:linear-gradient(90deg,var(--goldL),#A88F4E);margin:2.5mm 0 3.5mm}
p{font-size:8.6pt;line-height:1.5}
.card{border:.3mm solid rgba(131,113,63,.45);border-radius:2mm;padding:3mm 3.2mm;background:#fff}
.card .t{font-weight:900;text-transform:uppercase;font-size:7.6pt;letter-spacing:.04em}
.card .a{font-size:6.6pt;color:#5b6378;font-weight:600;margin-top:.6mm}
.card .p{font-family:'Cormorant Garamond',serif;font-weight:700;font-size:20pt;color:#8a7440;line-height:1;margin-top:1.4mm}
.card li{font-size:6.6pt;line-height:1.45;list-style:none;margin-top:.6mm}
.card li:before{content:"✓ ";color:#8a7440;font-weight:900}
.hr td{font-size:8pt;padding:1.3mm 0;border-bottom:.25mm solid rgba(131,113,63,.3)}
.hr td:first-child{font-weight:800;padding-right:4mm;white-space:nowrap}
</style>'''


def doc(name, pages, PW='148mm', PHt='210mm', per_sheet=1):
    css = CSS.replace('%(PW)s', PW).replace('%(PH)s', PHt).replace('%(LOGO)s', LOGO)
    sheets = ''.join(f'<div class="sheet">{"".join(pages[i:i+per_sheet])}</div>' for i in range(0, len(pages), per_sheet))
    open(os.path.join(OUT, name + '.html'), 'w').write(f'<!doctype html><html lang="fr"><head><meta charset="utf-8">{css}</head><body>{sheets}</body></html>')
    return dict(name=name, W=PW, H=PHt, n=len(pages) // per_sheet)


stat = lambda n, l, light=False: f'''<div style="flex:1"><div class="serif {'gd' if light else 'g'}" style="font-size:25pt;line-height:1">{n}</div>
<div style="font-size:5.8pt;font-weight:800;text-transform:uppercase;letter-spacing:.1em;margin-top:1.2mm;line-height:1.35">{l}</div></div>'''
STATS = [('200+', 'membres<br>actifs'), ('10×', 'champion de France<br>Jeunes · record'), ('30+', "ans dans<br>les écoles")]

contact = lambda light=True: f'''<div style="display:flex;gap:4mm;align-items:center">
<div class="logo" style="position:relative;width:19mm;height:19mm;flex:none"></div>
<div style="font-size:7.6pt;line-height:1.55"><b>{ADR} · {CP}</b><br>{TEL} · {MAIL}<br><b class="{'gd' if light else 'g'}" style="font-size:8.6pt">{URL}</b></div></div>'''

HORAIRES = f'''<table class="hr" style="border-collapse:collapse;width:100%">
<tr><td>Lun · Jeu · Ven</td><td>13h30 – 18h30</td></tr>
<tr><td>Mardi</td><td>Cours jeunes 17h – 19h</td></tr>
<tr><td>Mercredi</td><td>13h30 – 20h · cours dès 13h30</td></tr></table>'''

FORMULES = [
    ('Pitchounets', '4–6 ans', '200 €', ['1 h d’atelier ludique / semaine', 'Mercredi 13h30–14h30']),
    ('École Échecs', 'Enfants', '290 €', ['1 h de cours collectif / semaine', '1 h de pratique encadrée']),
    ('Cours adultes', 'Adultes', '290 €', ['Cours collectif du mercredi', 'Débutants 17h30 · Confirmés 18h30']),
    ('Famille', '2 membres du foyer', '500 €', ['Licences FFE incluses', '3 membres : 750 €']),
    ('Compétition', 'Tous âges', '120 €', ['Adhésion sans cours', 'Interclubs FFE inclus']),
    ('Accès libre', 'Licence A', '60 €', ['Accès libre au club', 'Tournois PICO gratuits']),
]
cards = lambda: ''.join(f'''<div class="card"><div class="t">{t}</div><div class="a">{a}</div><div class="p">{p}</div><ul style="margin-top:1mm">{''.join(f'<li>{x}</li>' for x in li)}</ul></div>''' for t, a, p, li in FORMULES)

# ================= FLYER =================
flyer_recto = f'''<div class="pg">
<div class="ph" style="left:0;right:0;top:0;height:128mm;background-image:url({PH('picomars3')});background-position:50% 40%"></div>
<div class="abs" style="left:0;right:0;top:0;height:130mm;background:linear-gradient(180deg,rgba(20,31,67,.55) 0%,rgba(20,31,67,0) 22%,rgba(20,31,67,.1) 55%,#141F43 100%)"></div>
<div class="logo" style="left:10mm;top:10mm;width:26mm;height:26mm"></div>
<div class="abs" style="left:12mm;right:12mm;top:96mm">
  <div class="k">Club d’échecs · Cannes · depuis 1985</div>
  <div class="h" style="font-size:38pt;margin-top:3mm">Rejoins<br><span class="g">Cannes Échecs</span></div>
  <div style="font-size:10.5pt;font-weight:700;margin-top:3mm">Le club recordman de France chez les jeunes</div>
  <div style="display:flex;gap:4mm;margin-top:6mm">{''.join(stat(n, l) for n, l in STATS)}</div>
  <div style="font-size:8.6pt;font-weight:800;text-transform:uppercase;letter-spacing:.12em;margin-top:6mm;color:var(--goldXL)">Cours pour tous dès 4 ans · Enfants · Ados · Adultes</div>
  <div class="pill" style="font-size:9pt;padding:3mm 6mm;margin-top:4mm">Cours d’essai sur demande</div>
</div>
<div class="abs" style="left:12mm;right:12mm;bottom:10mm;font-size:7.6pt;font-weight:700">{ADR} · Cannes · {TEL} · <b class="g">{URL}</b></div>
<div class="fr"></div></div>'''

flyer_verso = f'''<div class="pg light">
<div class="abs" style="left:12mm;right:12mm;top:12mm">
  <div class="k">Saison {SAISON}</div>
  <div class="h" style="font-size:22pt;margin-top:2mm">Nos <span class="gd">formules</span></div>
  <div style="font-size:7.6pt;font-weight:600;margin-top:1.5mm;color:#5b6378">Licence FFE et accès au club inclus dans chaque formule</div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:2.6mm;margin-top:4mm">{cards()}</div>
  <div style="font-size:7pt;margin-top:2.5mm;color:#5b6378"><b style="color:var(--ink)">Cours particuliers</b> sur rendez-vous : 40 €/h adhérents · 50 €/h non-adhérents</div>
  <div style="display:flex;gap:6mm;margin-top:5mm">
    <div style="flex:1.2"><div class="k">Horaires</div><div class="rule"></div>{HORAIRES}</div>
    <div style="flex:1"><div class="k">Inscription</div><div class="rule"></div>
      <p style="font-size:7.6pt">En ligne sur <b>HelloAsso</b>, confirmation immédiate. <b>Paiement en 3 fois</b> sans frais.</p>
      <p style="font-size:7.6pt;margin-top:1.5mm">Tournois <b>PICO</b> chaque mois, interclubs FFE, stages pendant les vacances.</p></div>
  </div>
</div>
<div class="abs" style="left:12mm;right:12mm;bottom:11mm">{contact()}</div>
<div class="fr"></div></div>'''

# ================= PLAQUETTE =================
p1 = f'''<div class="pg">
<div class="ph" style="inset:0;background-image:url({PH('topjeunes_8')});background-position:50% 50%"></div>
<div class="abs" style="inset:0;background:linear-gradient(180deg,rgba(13,21,48,.92) 0%,rgba(20,31,67,.55) 38%,rgba(20,31,67,.35) 55%,rgba(13,21,48,.95) 85%)"></div>
<div class="logo" style="left:49mm;top:22mm;width:50mm;height:50mm"></div>
<div class="abs" style="left:0;right:0;top:80mm;text-align:center">
  <div class="h" style="font-size:40pt">Cannes<br><span class="g">Échecs</span></div>
  <div class="k" style="margin-top:4mm;font-size:7.5pt">Club d’excellence depuis 1985</div>
</div>
<div class="abs" style="left:0;right:0;bottom:22mm;text-align:center">
  <div class="h" style="font-size:13pt;letter-spacing:.06em">Jeune · Dynamique · <span class="g">Ambitieux</span></div>
  <div style="font-size:8pt;font-weight:600;margin-top:3mm;opacity:.9">Cours · Compétition · Scolaire · Festival International des Jeux</div>
</div>
<div class="fr"></div></div>'''

YEARS = ['2007', '2008', '2009', '2010', '2011', '2012', '2015', '2019', '2020', '2022']
p2 = f'''<div class="pg light">
<div class="abs" style="left:12mm;right:12mm;top:12mm">
  <div class="k">Le club</div>
  <div class="h" style="font-size:20pt;margin-top:2mm">Un club fondé<br><span class="gd">sur la passion</span></div><div class="rule"></div>
  <p>Fondé en 1985, Cannes Échecs s’est imposé comme l’un des clubs de référence du sud de la France. Installé en plein cœur de Cannes, il conjugue tradition et modernité.</p>
  <p style="margin-top:2mm">Notre philosophie : rendre les échecs accessibles à tous, du débutant de 5 ans au joueur classé, en passant par les scolaires que nous accompagnons depuis plus de trois décennies.</p>
  <div style="display:flex;gap:3mm;margin-top:5mm;padding:4mm 0;border-top:.3mm solid rgba(131,113,63,.4);border-bottom:.3mm solid rgba(131,113,63,.4)">
    {''.join(stat(n, l, True) for n, l in STATS)}{stat('FFE', 'label club<br>formateur', True)}</div>
  <div class="k" style="margin-top:5mm">Palmarès</div><div class="rule"></div>
  <div style="font-size:9pt;font-weight:800">10× Champion de France Jeunes (Top Jeunes)</div>
  <div style="font-size:7.4pt;color:#5b6378;margin-top:.8mm">Le record national de titres — dont 6 titres consécutifs de 2007 à 2012</div>
  <div style="display:flex;flex-wrap:wrap;gap:1.5mm;margin-top:2.5mm">{''.join(f'<span style="font-size:7.2pt;font-weight:800;padding:1mm 2.2mm;border-radius:99mm;background:var(--navy);color:var(--goldXL)">{y}</span>' for y in YEARS)}</div>
  <div style="font-size:8pt;margin-top:3.5mm;line-height:1.5"><b>Trois équipes dans l’élite nationale</b> : Top 16 · Top 16 Féminin · Top Jeunes<br><b>Des joueurs et un entraîneur en équipe de France</b></div>
</div>
<div class="ph" style="left:0;right:0;bottom:0;height:46mm;background-image:url({PH('chptdep')});background-position:50% 35%"></div>
<div class="abs" style="left:0;right:0;bottom:0;height:46mm;background:linear-gradient(180deg,#FBF8F1 0%,rgba(251,248,241,0) 30%)"></div>
<div class="fr"></div></div>'''

act = lambda t, body: f'''<div style="margin-top:4.5mm"><div style="font-size:9.6pt;font-weight:900;text-transform:uppercase;letter-spacing:.03em">{t}</div><div class="rule" style="margin:1.6mm 0 2mm"></div><p style="font-size:8pt">{body}</p></div>'''
p3 = f'''<div class="pg light">
<div class="ph" style="left:0;right:0;top:0;height:52mm;background-image:url({PH('picomars3')});background-position:50% 45%"></div>
<div class="abs" style="left:0;right:0;top:0;height:53mm;background:linear-gradient(0deg,#FBF8F1 0%,rgba(251,248,241,0) 35%)"></div>
<div class="abs" style="left:12mm;right:12mm;top:46mm">
  <div class="k">Nos activités</div>
  <div class="h" style="font-size:20pt;margin-top:2mm">Des échecs <span class="gd">pour tous</span></div>
  {act('Cours & formation', 'Ateliers Pitchounets dès 4 ans, École Échecs pour les enfants, cours adultes débutants et confirmés, cours particuliers avec nos entraîneurs, stages pendant les vacances. Du premier coup au haut niveau.')}
  {act('Compétition', 'Tournois <b>PICO</b> un samedi par mois (5 rondes rapides, gratuits pour les membres), Rapide du vendredi, <b>Open de Pâques</b> homologué FIDE, interclubs FFE avec trois équipes dans l’élite nationale.')}
  {act('Festival International des Jeux', 'Chaque février au Palais des Festivals, Cannes Échecs organise les tournois d’échecs officiels du FIJ, homologués FIDE : tournois A, B et C pour tous les niveaux, au cœur d’un festival de plus de 100 000 visiteurs.')}
  {act('Scolaire', 'Partenaire de l’Éducation nationale depuis plus de 30 ans : interventions dans les écoles, tournois inter-écoles et inter-collèges, sélections académiques et championnats de France.')}
</div>
<div class="abs" style="left:12mm;right:12mm;bottom:12mm;height:36mm;display:flex;gap:2.5mm">
  {''.join(f'<div style="flex:1;border-radius:2mm;background:url({PH(n)}) {pos}/cover no-repeat;box-shadow:0 1mm 3mm rgba(0,0,0,.18)"></div>' for n, pos in [('FIJ14', '50% 50%'), ('topjeunes_10', '50% 22%'), ('stemariechamp7', '50% 40%')])}
</div>
<div class="fr"></div></div>'''

steps = ''.join(f'''<div style="display:flex;gap:2.5mm;align-items:flex-start;margin-top:2.2mm"><div class="serif" style="flex:none;width:6.5mm;height:6.5mm;border-radius:50%;background:linear-gradient(180deg,var(--goldXL),var(--goldL));color:var(--navy);font-size:11pt;display:flex;align-items:center;justify-content:center">{i}</div><div style="font-size:7.6pt;line-height:1.4"><b>{t}</b><br>{d}</div></div>'''
                for i, (t, d) in enumerate([('Choisir sa formule', 'Enfant, ado, adulte, loisir ou compétition'), ('Régler en ligne', 'Sur HelloAsso · paiement en 3 fois sans frais'), ('Venir au club !', 'Dès le prochain cours — cours d’essai sur demande')], 1))
p4 = f'''<div class="pg">
<div class="abs" style="left:12mm;right:12mm;top:12mm">
  <div class="k">Saison {SAISON}</div>
  <div class="h" style="font-size:22pt;margin-top:2mm">Rejoins <span class="g">le club</span></div>
  <div style="font-size:7.6pt;font-weight:600;margin-top:1.5mm;opacity:.85">Licence FFE et accès au club inclus dans chaque formule</div>
  <div style="margin-top:4mm;color:var(--ink);display:grid;grid-template-columns:1fr 1fr;gap:2.4mm">{cards()}</div>
  <div style="display:flex;gap:6mm;margin-top:5mm">
    <div style="flex:1"><div class="k">S’inscrire en 3 étapes</div>{steps}</div>
    <div style="flex:1"><div class="k">Horaires</div><div style="margin-top:2mm;color:#fff">{HORAIRES.replace('rgba(131,113,63,.3)', 'rgba(227,207,148,.3)')}</div></div>
  </div>
</div>
<div class="abs" style="left:12mm;right:12mm;bottom:11mm;color:#fff">{contact(False)}</div>
<div class="fr"></div></div>'''

J = [doc('flyer-A5-recto-verso', [flyer_recto, flyer_verso]),
     doc('plaquette-4-pages-A5', [p1, p2, p3, p4]),
     # A4 paysage à plier en deux : feuille extérieure (p4 | p1), feuille intérieure (p2 | p3)
     doc('plaquette-4-pages-A4-imposee-a-plier', [p4, p1, p2, p3], '296mm', '210mm', 2)]
json.dump(J, open(os.path.join(OUT, 'jobs.json'), 'w'), indent=1)
print('ok', [j['name'] for j in J])
