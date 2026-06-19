const sharp = require('sharp');
const fs = require('fs');
const path = require('path');

const PHOTOS_DIR = path.join(__dirname, 'photos');
const QUALITY = 82;

async function compress() {
  const files = fs.readdirSync(PHOTOS_DIR).filter(f => /\.(jpg|jpeg)$/i.test(f));
  let totalBefore = 0, totalAfter = 0, skipped = 0;

  for (const file of files) {
    const filePath = path.join(PHOTOS_DIR, file);
    const statBefore = fs.statSync(filePath).size;
    totalBefore += statBefore;

    const tmp = filePath + '.tmp';
    try {
      await sharp(filePath)
        .jpeg({ quality: QUALITY, mozjpeg: true })
        .toFile(tmp);

      const statAfter = fs.statSync(tmp).size;

      if (statAfter < statBefore) {
        fs.renameSync(tmp, filePath);
        totalAfter += statAfter;
        const pct = Math.round((1 - statAfter / statBefore) * 100);
        console.log(`✓ ${file} : ${kb(statBefore)} → ${kb(statAfter)} KB (-${pct}%)`);
      } else {
        fs.unlinkSync(tmp);
        totalAfter += statBefore;
        skipped++;
        console.log(`= ${file} : déjà optimisé (${kb(statBefore)} KB)`);
      }
    } catch (e) {
      if (fs.existsSync(tmp)) fs.unlinkSync(tmp);
      totalAfter += statBefore;
      console.error(`✗ ${file} : ${e.message}`);
    }
  }

  const saved = totalBefore - totalAfter;
  console.log(`\n── Résultat ──`);
  console.log(`${files.length} fichiers · ${skipped} déjà optimisés`);
  console.log(`Avant : ${mb(totalBefore)} MB`);
  console.log(`Après : ${mb(totalAfter)} MB`);
  console.log(`Gain  : ${mb(saved)} MB (-${Math.round(saved/totalBefore*100)}%)`);
}

const kb = n => Math.round(n / 1024);
const mb = n => (n / 1024 / 1024).toFixed(1);

compress();
