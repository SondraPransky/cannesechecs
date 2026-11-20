# Supports de communication imprimés : plaquette partenaires (A4, 4 pages), affiches A3 (agrandissables en A2,
# même proportion), cartes de visite (85x55 mm). Réutilise la charte et les outils de build_print.py.
import os, json
import build_print as bp
from build_print import PH, LOGO, SAISON, ADR, CP, TEL, MAIL, URL, stat, contact, HORAIRES

OUT = bp.OUT
CSS_A4 = '''<style>.pg.a4{width:210mm;height:297mm}.pg.a3{width:297mm;height:420mm}.pg.cv{width:85mm;height:55mm}
.big p{font-size:11pt}.tier{border-radius:2.5mm;padding:5mm;color:var(--ink);background:#fff;border:.4mm solid rgba(131,113,63,.45)}
.tier .n{font-weight:900;font-size:15pt;letter-spacing:.06em;text-transform:uppercase}
.tier li{font-size:10.5pt;list-style:none;margin-top:1.3mm;line-height:1.4}.tier li:before{content:"✓ ";color:#8a7440;font-weight:900}</style>'''


def docx(name, pages, W, H):
    j = bp.doc(name, pages, W, H)
    p = os.path.join(OUT, name + '.html')
    s = open(p).read().replace('</head>', CSS_A4 + '</head>')
    open(p, 'w').write(s)
    return j


# ======================= PLAQUETTE PARTENAIRES (A4) =======================
s4 = lambda n, l: f'''<div style="flex:1;text-align:center;padding:4mm 2mm;border-right:.3mm solid rgba(227,207,148,.25)"><div class="serif g" style="font-size:30pt;line-height:1;white-space:nowrap">{n}</div>
<div style="font-size:7pt;font-weight:800;text-transform:uppercase;letter-spacing:.12em;margin-top:2mm;line-height:1.4">{l}</div></div>'''

pa1 = f'''<div class="pg a4">
<div class="ph" style="inset:0;background-image:url({PH('FIJ14')});background-position:50% 50%"></div>
<div class="abs" style="inset:0;background:linear-gradient(180deg,rgba(13,21,48,.94) 0%,rgba(20,31,67,.6) 40%,rgba(20,31,67,.55) 60%,rgba(13,21,48,.96) 88%)"></div>
<div class="logo" style="left:75mm;top:32mm;width:60mm;height:60mm"></div>
<div class="abs" style="left:0;right:0;top:104mm;text-align:center">
  <div class="k" style="font-size:9pt">Dossier partenaires · Saison {SAISON}</div>
  <div class="h" style="font-size:44pt;margin-top:6mm">Devenez<br><span class="g">partenaire</span></div>
  <div style="font-size:13pt;font-weight:700;margin-top:6mm">de Cannes Échecs, club d’excellence depuis 1985</div>
</div>
<div class="abs" style="left:20mm;right:20mm;bottom:26mm;display:flex">{s4('200+', 'membres actifs')}{s4('10×', 'champion de France Jeunes')}{s4('100 000+', 'visiteurs au FIJ')}<div style="flex:1;text-align:center;padding:4mm 2mm"><div class="serif g" style="font-size:34pt;line-height:1">30+</div><div style="font-size:7pt;font-weight:800;text-transform:uppercase;letter-spacing:.12em;margin-top:2mm;line-height:1.4">ans dans les écoles</div></div></div>
<div class="fr" style="inset:7mm"></div></div>'''

pa2 = f'''<div class="pg a4 light big">
<div class="abs" style="left:20mm;right:20mm;top:20mm">
  <div class="k" style="font-size:8pt">Le club</div>
  <div class="h" style="font-size:28pt;margin-top:3mm">Un club formateur<br><span class="gd">reconnu</span></div><div class="rule"></div>
  <p>Fondé en 1985, Cannes Échecs est l’un des clubs de référence du sud de la France. Installé en plein cœur de Cannes, il accueille plus de 200 membres, du débutant de 5 ans au joueur classé, et intervient dans les écoles du bassin cannois depuis plus de 30 ans.</p>
  <p style="margin-top:3mm">Le club détient le <b>record national de titres de Champion de France Jeunes</b> par équipes (10 titres, dont 6 consécutifs de 2007 à 2012), aligne <b>trois équipes dans l’élite nationale</b> (Top 16, Top 16 Féminin, Top Jeunes) et compte <b>des joueurs et un entraîneur en équipe de France</b>. Il est labellisé <b>Club Formateur</b> par la Fédération Française des Échecs.</p>
  <div style="display:flex;gap:6mm;margin-top:8mm">
    <div style="flex:1;border-radius:2.5mm;overflow:hidden;height:72mm;background:url({PH('chptdep')}) 50% 35%/cover"></div>
    <div style="flex:1;border-radius:2.5mm;overflow:hidden;height:72mm;background:url({PH('topjeunes_8')}) 50% 50%/cover"></div></div>
  <div class="k" style="font-size:8pt;margin-top:9mm">Un rayonnement au-delà de Cannes</div><div class="rule"></div>
  <p><b>Festival International des Jeux (FIJ)</b> — chaque février au Palais des Festivals, Cannes Échecs organise les tournois d’échecs officiels, homologués FIDE, au cœur du plus grand festival de jeux de société du monde : <b>plus de 100 000 visiteurs</b>.</p>
  <p style="margin-top:3mm"><b>Tout au long de l’année</b> — tournois PICO mensuels, Open de Pâques homologué FIDE, championnats de France jeunes, interclubs nationaux, tournois scolaires inter-écoles et inter-collèges.</p>
  <p style="margin-top:3mm"><b>Sur les réseaux</b> — Instagram <b>@cannes_echecs</b> · Facebook <b>canneschessclub</b> · site <b>{URL}</b></p>
</div>
<div class="fr" style="inset:7mm"></div></div>'''

TIERS = [('Or', 'Partenaire Titre', ['Logo en pied de page du site', 'Bannière sur nos événements', 'Mention dans nos communiqués de presse', 'Invitation au Festival International des Jeux']),
         ('Argent', 'Partenaire Officiel', ['Logo sur le site et nos réseaux', 'Mention lors des tournois']),
         ('Bronze', 'Partenaire Associé', ['Logo sur le site', 'Remerciements dans notre lettre d’information'])]
COL = {'Or': 'linear-gradient(180deg,#E3CF94,#C9B06A 55%,#A88F4E)', 'Argent': 'linear-gradient(180deg,#EEF0F4,#C5CAD3)', 'Bronze': 'linear-gradient(180deg,#E7C3A1,#B9875E)'}
pa3 = f'''<div class="pg a4 big">
<div class="abs" style="left:20mm;right:20mm;top:20mm">
  <div class="k" style="font-size:8pt">Nos offres</div>
  <div class="h" style="font-size:28pt;margin-top:3mm">Associez votre image<br><span class="g">à l’excellence</span></div><div class="rule"></div>
  <p>Soutenir Cannes Échecs, c’est soutenir un club formateur reconnu, présent dans les médias locaux et organisateur d’événements à rayonnement national.</p>
  <div style="display:flex;flex-direction:column;gap:7mm;margin-top:10mm">
  {''.join(f"""<div class="tier" style="display:flex;gap:6mm;align-items:center"><div style="flex:none;width:38mm;height:38mm;border-radius:50%;background:{COL[n]};display:flex;align-items:center;justify-content:center"><div class="n" style="color:#141F43">{n}</div></div>
  <div><div style="font-weight:900;font-size:13pt;text-transform:uppercase">{t}</div><ul style="margin-top:1mm">{''.join(f'<li>{x}</li>' for x in li)}</ul></div></div>""" for n, t, li in TIERS)}
  </div>
  <div style="display:flex;gap:6mm;margin-top:8mm">
   <div style="flex:1"><div class="k">Mécénat</div><div class="rule"></div><p style="font-size:10.5pt">Dons et mécénat : déductibilité fiscale selon les dispositifs en vigueur. Nous établissons les reçus nécessaires.</p></div>
   <div style="flex:1"><div class="k">Sur mesure</div><div class="rule"></div><p style="font-size:10.5pt">Parrainage d’un tournoi, d’une équipe ou d’un jeune espoir, dotations, stand au FIJ : construisons ensemble votre partenariat.</p></div></div>
</div>
<div class="fr" style="inset:7mm"></div></div>'''

PARTS = [('Ville de Cannes', 'Partenaire institutionnel'), ('Fédération Française des Échecs', 'Partenaire fédéral'),
         ('Région Sud', 'Partenaire régional'), ('Département des Alpes-Maritimes', 'Partenaire départemental'),
         ('Cannes Université', 'Partenaire associé'), ('SEMEC', 'Partenaire associé')]
pa4 = f'''<div class="pg a4 light big">
<div class="abs" style="left:20mm;right:20mm;top:20mm">
  <div class="k" style="font-size:8pt">Ils nous font confiance</div>
  <div class="h" style="font-size:28pt;margin-top:3mm">Nos <span class="gd">partenaires</span></div><div class="rule"></div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:4mm;margin-top:6mm">
  {''.join(f'<div style="border:.3mm solid rgba(131,113,63,.45);border-radius:2mm;padding:5mm;background:#fff"><div style="font-weight:900;font-size:12pt">{n}</div><div style="font-size:9pt;color:#5b6378;margin-top:1mm">{r}</div></div>' for n, r in PARTS)}
  </div>
  <div style="display:flex;gap:6mm;margin-top:8mm">
    <div style="flex:1;border-radius:2.5mm;height:62mm;background:url({PH('stemariechamp7')}) 50% 40%/cover"></div>
    <div style="flex:1;border-radius:2.5mm;height:62mm;background:url({PH('picomars3')}) 50% 45%/cover"></div></div>
  <div style="margin-top:8mm;padding:8mm;border-radius:3mm;background:var(--navy);color:#fff">
    <div style="display:flex;gap:6mm;align-items:center"><div class="logo" style="position:relative;width:24mm;height:24mm;flex:none"></div><div class="h" style="font-size:20pt">Parlons de <span class="g">votre projet</span></div></div>
    <p style="margin-top:3mm;color:#fff">Contactez-nous pour recevoir une proposition adaptée à vos objectifs.</p>
    <div style="font-size:11pt;line-height:1.7;margin-top:4mm"><b>{TEL}</b> · {MAIL}<br>{ADR} · {CP}<br><b class="g">{URL}</b></div>
  </div>
</div>
<div class="fr" style="inset:7mm"></div></div>'''

# ======================= AFFICHES A3 =======================
def affiche(name, photo, pos, kicker, titre, sous, bloc, cta, s=2.0):
    return f'''<div class="pg a3">
<div class="ph" style="left:0;right:0;top:0;height:270mm;background-image:url({PH(photo)});background-position:{pos}"></div>
<div class="abs" style="left:0;right:0;top:0;height:272mm;background:linear-gradient(180deg,rgba(13,21,48,.6) 0%,rgba(20,31,67,0) 22%,rgba(20,31,67,.1) 55%,#141F43 100%)"></div>
<div class="logo" style="left:18mm;top:18mm;width:52mm;height:52mm"></div>
<div class="abs" style="left:24mm;right:24mm;top:222mm">
  <div class="k" style="font-size:{6.5*s:.0f}pt">{kicker}</div>
  <div class="h" style="font-size:{34*s:.0f}pt;margin-top:5mm">{titre}</div>
  <div style="font-size:{10.5*s:.0f}pt;font-weight:700;margin-top:6mm">{sous}</div>
  <div style="margin-top:10mm">{bloc}</div>
  <div class="pill" style="font-size:{9*s:.0f}pt;padding:6mm 12mm;margin-top:10mm">{cta}</div>
</div>
<div class="abs" style="left:24mm;right:24mm;bottom:20mm;font-size:{8*s:.0f}pt;font-weight:700">{ADR} · Cannes · {TEL} · <b class="g">{URL}</b></div>
<div class="fr" style="inset:9mm;border-width:.7mm"></div></div>'''

STATS3 = ''.join(stat(n, l) for n, l in [('200+', 'membres<br>actifs'), ('10×', 'champion de France<br>Jeunes · record'), ('30+', 'ans dans<br>les écoles')]).replace('25pt', '54pt').replace('5.8pt', '12pt')
aff_rentree = affiche('affiche-A3-inscriptions', 'picomars3', '50% 40%', f'Saison {SAISON} · Inscriptions ouvertes',
                      'Rejoins<br><span class="g">Cannes Échecs</span>', 'Le club recordman de France chez les jeunes',
                      f'<div style="display:flex;gap:10mm">{STATS3}</div><div style="font-size:17pt;font-weight:800;text-transform:uppercase;letter-spacing:.12em;margin-top:10mm;color:var(--goldXL)">Cours pour tous dès 4 ans · Enfants · Ados · Adultes</div>',
                      'Cours d’essai sur demande')
aff_fij = affiche('affiche-A3-FIJ-2027', 'FIJ14', '50% 50%', 'Festival International des Jeux · Cannes',
                  'Tournois d’échecs<br><span class="g">FIJ 2027</span>', 'Du 22 au 28 février 2027 · Palais des Festivals',
                  '<div style="font-size:18pt;line-height:1.6;font-weight:700">Tournois <b class="g">A</b> (2200+) · <b class="g">B</b> (1600–2200) · <b class="g">C</b> (≤ 1600)<br>9 rondes · homologués FIDE · tous niveaux</div>',
                  'Inscription sur HelloAsso')
aff_paques = affiche('affiche-A3-Open-de-Paques-2027', 'FIJ16', '50% 40%', 'Tournoi ouvert à tous les licenciés FFE',
                     'Open<br><span class="g">de Pâques</span>', 'Du samedi 27 au lundi 29 mars 2027 · au club',
                     '<div style="font-size:18pt;line-height:1.6;font-weight:700">6 rondes · 45 min + 15 s/coup<br>Homologation Elo FIDE · dotations par catégories</div>',
                     'Inscription sur HelloAsso')

# ======================= CARTES DE VISITE (85x55 mm) =======================
cv_recto = f'''<div class="pg cv"><div class="logo" style="left:6mm;top:9.5mm;width:36mm;height:36mm"></div>
<div class="abs" style="left:45mm;right:5mm;top:15mm"><div class="h" style="font-size:12pt">Cannes<br><span class="g">Échecs</span></div>
<div class="k" style="font-size:4.4pt;margin-top:2mm;letter-spacing:.18em">Club d’échecs · depuis 1985</div></div>
<div class="fr" style="inset:2.5mm;border-width:.25mm"></div></div>'''


def cv_verso(nom='', role=''):
    who = f'<div style="font-weight:900;font-size:9.5pt;text-transform:uppercase">{nom}</div><div class="gd" style="font-size:6.5pt;font-weight:800;text-transform:uppercase;letter-spacing:.12em;margin-top:.6mm">{role}</div>' if nom else \
          '<div class="h" style="font-size:9pt">Jeune · Dynamique · <span class="gd">Ambitieux</span></div><div style="font-size:6pt;font-weight:600;margin-top:.8mm;color:#5b6378">Cours pour tous dès 4 ans · Compétition · Scolaire</div>'
    return f'''<div class="pg cv light"><div class="abs" style="left:6mm;right:6mm;top:50%;transform:translateY(-50%)">{who}<div class="rule" style="margin:2.2mm 0;width:12mm"></div>
<div style="font-size:6.8pt;line-height:1.65">{ADR} · {CP}<br><b>{TEL}</b> · {MAIL}<br>Instagram @cannes_echecs · <b class="gd">{URL}</b></div></div>
<div class="fr" style="inset:2.5mm;border-width:.25mm"></div></div>'''


J = [docx('plaquette-partenaires-A4-4-pages', [pa1, pa2, pa3, pa4], '210mm', '297mm'),
     docx('affiche-A3-inscriptions', [aff_rentree], '297mm', '420mm'),
     docx('affiche-A3-FIJ-2027', [aff_fij], '297mm', '420mm'),
     docx('affiche-A3-Open-de-Paques-2027', [aff_paques], '297mm', '420mm'),
     docx('carte-de-visite-club-85x55', [cv_recto, cv_verso()], '85mm', '55mm'),
     docx('carte-de-visite-Romuald-de-Labaca-85x55', [cv_recto, cv_verso('Romuald de Labaca', 'Entraîneur')], '85mm', '55mm'),
     docx('carte-de-visite-Marlies-Bensdorp-De-Labaca-85x55', [cv_recto, cv_verso('Marlies Bensdorp De Labaca', 'Coordinatrice')], '85mm', '55mm')]
json.dump(J, open(os.path.join(OUT, 'jobs_comm.json'), 'w'), indent=1)
print('ok', len(J))
