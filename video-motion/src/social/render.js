// Rend chaque page de pages/jobs.json : JPEG pour les visuels, PNG transparent pour les calques vidéo.
// node render.js dossier_sortie
const { chromium } = require(process.env.PW || 'playwright');
const path = require('path'), fs = require('fs');
(async () => {
  const out = process.argv[2]; fs.mkdirSync(out, { recursive: true });
  const jobs = JSON.parse(fs.readFileSync(path.join(__dirname, 'pages/jobs.json')));
  const b = await chromium.launch({ args: ['--allow-file-access-from-files'] });
  for (const j of jobs) {
    const p = await b.newPage({ viewport: { width: j.W, height: j.H } });
    await p.goto('file://' + path.join(__dirname, 'pages', j.name + '.html'));
    await p.evaluate(() => document.fonts.ready); await p.waitForTimeout(500);
    const f = path.join(out, j.name + (j.transparent ? '.png' : '.jpg'));
    await p.screenshot(j.transparent ? { path: f, omitBackground: true } : { path: f, type: 'jpeg', quality: 92 });
    await p.close(); console.log(f);
  }
  await b.close();
})();
