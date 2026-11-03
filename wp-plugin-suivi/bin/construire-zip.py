"""Construit suivi-tournoi.zip, prêt à téléverser dans WordPress
(Extensions > Ajouter > Téléverser une extension).

Usage : python wp-plugin-suivi/bin/construire-zip.py
Sortie : suivi-tournoi.zip à la racine du dépôt (ignoré par git).

Zip fait avec Python et des séparateurs « / » : PowerShell Compress-Archive
écrit des « \\ » qui cassent l'installation dans WordPress (même piège que le thème).
Les outils de dev (bin/, tests/) ne sont pas livrés.
"""
import os
import zipfile

ICI = os.path.dirname(os.path.abspath(__file__))
PLUGIN = os.path.dirname(ICI)
SORTIE = os.path.join(os.path.dirname(PLUGIN), 'suivi-tournoi.zip')
LIVRES = ['suivi-tournoi.php', 'uninstall.php', 'README.md', 'includes', 'assets']

n = 0
with zipfile.ZipFile(SORTIE, 'w', zipfile.ZIP_DEFLATED) as z:
    for entree in LIVRES:
        chemin = os.path.join(PLUGIN, entree)
        fichiers = [chemin] if os.path.isfile(chemin) else [
            os.path.join(d, f) for d, _, fs in os.walk(chemin) for f in sorted(fs)]
        for f in fichiers:
            arc = 'suivi-tournoi/' + os.path.relpath(f, PLUGIN).replace(os.sep, '/')
            z.write(f, arc)
            n += 1
print(f'{SORTIE} : {n} fichiers')
