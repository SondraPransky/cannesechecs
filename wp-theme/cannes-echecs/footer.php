<?php
/**
 * footer.php — Cannes Échecs
 * Footer + lightbox partagée (galeries d'articles).
 */
?>
</main>

<footer class="footer">
  <div class="container footer-main">
    <div class="footer-grid" style="max-width:900px;margin:0 auto">
      <div>
        <div class="footer-logo-row">
          <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/logo-cannes-echecs.png" alt="Cannes Échecs" class="footer-logo-img" loading="lazy" decoding="async"
               onerror="this.style.display='none';document.getElementById('footer-logo-fallback').style.display='flex'">
          <div id="footer-logo-fallback" style="display:none;width:88px;height:88px;background:var(--gold);border-radius:12px;align-items:center;justify-content:center;font-size:40px;color:var(--noir)">♟</div>
        </div>
        <ul class="footer-brand-info">
          <li><span class="ico">📍</span> 3 Av. du Petit Juas, 06400 Cannes</li>
          <li><span class="ico">🕐</span> Lun/Jeu/Ven 13h30–18h30 · Mar 17h–19h · Mer 13h30–20h</li>
          <li><span class="ico">✉</span> <a href="mailto:info@cannes-echecs.fr" style="color:inherit;text-decoration:none">info@cannes-echecs.fr</a></li>
          <li><span class="ico">📞</span> <a href="tel:+33493394139" style="color:inherit;text-decoration:none">04 93 39 41 39</a></li>
        </ul>
        <div style="display:flex;gap:10px;margin-top:18px">
          <a href="https://www.facebook.com/canneschessclub" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-family:'Montserrat',sans-serif;font-weight:600;letter-spacing:.06em;color:rgba(255,255,255,.5);text-decoration:none;padding:5px 10px;border:1px solid rgba(255,255,255,.12);border-radius:6px;transition:color .2s,border-color .2s" onmouseover="this.style.color='#C9A84C';this.style.borderColor='rgba(201,168,76,.4)'" onmouseout="this.style.color='rgba(255,255,255,.5)';this.style.borderColor='rgba(255,255,255,.12)'">Facebook</a>
          <a href="https://www.instagram.com/cannes_echecs" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-family:'Montserrat',sans-serif;font-weight:600;letter-spacing:.06em;color:rgba(255,255,255,.5);text-decoration:none;padding:5px 10px;border:1px solid rgba(255,255,255,.12);border-radius:6px;transition:color .2s,border-color .2s" onmouseover="this.style.color='#C9A84C';this.style.borderColor='rgba(201,168,76,.4)'" onmouseout="this.style.color='rgba(255,255,255,.5)';this.style.borderColor='rgba(255,255,255,.12)'">Instagram</a>
          <a href="https://x.com/CannesChessClub" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-family:'Montserrat',sans-serif;font-weight:600;letter-spacing:.06em;color:rgba(255,255,255,.5);text-decoration:none;padding:5px 10px;border:1px solid rgba(255,255,255,.12);border-radius:6px;transition:color .2s,border-color .2s" onmouseover="this.style.color='#C9A84C';this.style.borderColor='rgba(201,168,76,.4)'" onmouseout="this.style.color='rgba(255,255,255,.5)';this.style.borderColor='rgba(255,255,255,.12)'">X / Twitter</a>
        </div>
      </div>
      <div class="footer-col">
        <div class="footer-col-title">Le Club</div>
        <ul>
          <li><a href="<?php echo esc_url(home_url('/club/')); ?>">Présentation</a></li>
          <li><a href="<?php echo esc_url(home_url('/actualites/')); ?>">Actualités</a></li>
          <li><a href="<?php echo esc_url(home_url('/agenda/')); ?>">Agenda</a></li>
          <li><a href="<?php echo esc_url(home_url('/adhesion/')); ?>">Adhésion</a></li>
          <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <div class="footer-col-title">Activités</div>
        <ul>
          <li><a href="<?php echo esc_url(home_url('/activites/')); ?>#activites-cours">Cours & Formation</a></li>
          <li><a href="<?php echo esc_url(home_url('/activites/')); ?>#activites-stages">Stages</a></li>
          <li><a href="<?php echo esc_url(home_url('/activites/')); ?>#activites-scolaire">Scolaire</a></li>
          <li><a href="<?php echo esc_url(home_url('/tournois/')); ?>">Tournois</a></li>
          <li><a href="<?php echo esc_url(home_url('/fij/')); ?>">FIJ 2027</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="container footer-bottom">
    <div style="max-width:900px;margin:0 auto;width:100%;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
      <p class="footer-copy">© 2026 Cannes Échecs — Tous droits réservés</p>
      <div class="footer-links">
        <a onclick="showLegal('mentions')">Mentions légales</a>
        <a onclick="showLegal('confidentialite')">Confidentialité</a>
      </div>
    </div>
  </div>
</footer>

<div id="lightbox" class="lightbox">
  <button class="lb-close" onclick="lbClose()">×</button>
  <button class="lb-nav lb-prev" onclick="lbNav(-1)">‹</button>
  <img class="lb-img" id="lb-img" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="">
  <button class="lb-nav lb-next" onclick="lbNav(1)">›</button>
  <div class="lb-counter" id="lb-counter"></div>

<?php wp_footer(); ?>
</body>
</html>
