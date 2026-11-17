// Rend une scène HTML (qui expose render(t)) en images JPEG 1920x1080.
// node render_scene.js page.html dossier_sortie nb_images      -> images à 30 i/s
// node render_scene.js page.html dossier_sortie 0 t1 t2 ...     -> captures aux instants donnés
const { chromium } = require(process.env.PW || 'playwright');
const path = require('path');
const fs = require('fs');
(async () => {
  const [page, out, n, ...stills] = process.argv.slice(2);
  const b = await chromium.launch({ args: ['--allow-file-access-from-files'] });
  const p = await b.newPage({ viewport: { width: 1920, height: 1080 } });
  await p.goto('file://' + path.resolve(page));
  await p.evaluate(() => document.fonts.ready);
  await p.waitForTimeout(1200);
  fs.mkdirSync(out, { recursive: true });
  const ts = stills.length ? stills.map(Number) : [...Array(Number(n)).keys()].map(i => i / 30);
  for (let i = 0; i < ts.length; i++) {
    await p.evaluate(t => render(t), ts[i]);
    await p.screenshot({ path: `${out}/${String(i).padStart(4, '0')}.jpg`, type: 'jpeg', quality: 92 });
  }
  await b.close();
})();
