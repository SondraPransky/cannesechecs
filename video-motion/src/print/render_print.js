// PDF vectoriel prêt à imprimer + aperçus JPEG 300 dpi de chaque feuille.
// node render_print.js dossier_sortie
const { chromium } = require(process.env.PW || 'playwright');
const path = require('path'), fs = require('fs');
const mm = v => parseFloat(v) * 96 / 25.4;
(async () => {
  const out = process.argv[2]; fs.mkdirSync(out, { recursive: true });
  const jobs = JSON.parse(fs.readFileSync(path.join(__dirname, 'pages/jobs.json')));
  const b = await chromium.launch({ args: ['--allow-file-access-from-files'] });
  for (const j of jobs) {
    const W = Math.round(mm(j.W)), H = Math.round(mm(j.H));
    const p = await b.newPage({ viewport: { width: W, height: H }, deviceScaleFactor: 300 / 96 });
    await p.goto('file://' + path.join(__dirname, 'pages', j.name + '.html'));
    await p.evaluate(() => document.fonts.ready); await p.waitForTimeout(800);
    await p.pdf({ path: path.join(out, j.name + '.pdf'), width: j.W, height: j.H, printBackground: true, preferCSSPageSize: true });
    const sheets = await p.$$('.sheet');
    for (let i = 0; i < sheets.length; i++)
      await sheets[i].screenshot({ path: path.join(out, `${j.name}-page${i + 1}.jpg`), type: 'jpeg', quality: 90 });
    await p.close(); console.log(j.name);
  }
  await b.close();
})();
