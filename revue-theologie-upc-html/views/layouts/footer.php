<?php
$base = $base ?? (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '');
?>
  <footer class="site-footer">
    <div class="container">
      <div class="grid">
        <div>
          <div class="logo-block">
            <img src="<?= $base ?>/images/logo_upc.png" alt="Logo UPC">
            <div>
              <p class="font-serif font-bold text-sm mb-0" style="color: var(--primary-foreground);">Revue de Théologie</p>
              <p class="text-xs mb-0" style="color: rgba(255,255,255,0.6);">UPC - Kinshasa</p>
            </div>
          </div>
          <p class="text-sm leading-relaxed mb-3" style="color: rgba(255,255,255,0.7);">Revue scientifique de la Faculté de Théologie de l'Université Protestante au Congo.</p>
          <p class="text-xs font-medium mt-3 italic" style="color: var(--upc-gold);">"Vérité, Foi, Liberté"</p>
        </div>
        <div>
          <h3 class="footer-title"><?= htmlspecialchars(function_exists('__') ? __('footer.nav') : 'Navigation') ?></h3>
          <ul>
            <li><a href="<?= $base ?>/"><?= htmlspecialchars(function_exists('__') ? __('nav.home') : 'Accueil') ?></a></li>
            <li><a href="<?= $base ?>/publications"><?= htmlspecialchars(function_exists('__') ? __('nav.publications') : 'Publications') ?></a></li>
            <li><a href="<?= $base ?>/archives"><?= htmlspecialchars(function_exists('__') ? __('nav.archives') : 'Archives') ?></a></li>
            <li><a href="<?= $base ?>/comite"><?= htmlspecialchars(function_exists('__') ? __('nav.comite') : 'Comité éditorial') ?></a></li>
            <li><a href="<?= $base ?>/presentation"><?= htmlspecialchars(function_exists('__') ? __('nav.presentation') : 'Présentation') ?></a></li>
          </ul>
        </div>
        <div>
          <h3 class="footer-title"><?= htmlspecialchars(function_exists('__') ? __('footer.authors') : 'Pour les auteurs') ?></h3>
          <ul>
            <li><a href="<?= $base ?>/soumettre"><?= htmlspecialchars(function_exists('__') ? __('footer.submit') : 'Soumettre un article') ?></a></li>
            <li><a href="<?= $base ?>/instructions-auteurs"><?= htmlspecialchars(function_exists('__') ? __('footer.instructions') : 'Instructions aux auteurs') ?></a></li>
            <li><a href="<?= $base ?>/politique-editoriale"><?= htmlspecialchars(function_exists('__') ? __('footer.evaluation') : 'Processus d\'évaluation') ?></a></li>
            <li><a href="<?= $base ?>/login"><?= htmlspecialchars(function_exists('__') ? __('nav.login') : 'Connexion') ?></a></li>
            <li><a href="<?= $base ?>/faq"><?= htmlspecialchars(function_exists('__') ? __('nav.faq') : 'FAQ') ?></a></li>
          </ul>
        </div>
        <div>
          <h3 class="footer-title"><?= htmlspecialchars(function_exists('__') ? __('footer.contact') : 'Contact') ?></h3>
          <ul>
            <li class="contact-block">
              <span class="icon"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#map-pin"/></svg></span>
              <span>Université Protestante au Congo, Kinshasa, RD Congo</span>
            </li>
            <li class="contact-block">
              <span class="icon"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#mail"/></svg></span>
              <a href="mailto:revue.theologie@upc.ac.cd">revue.theologie@upc.ac.cd</a>
            </li>
            <li class="contact-block">
              <span class="icon"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#phone"/></svg></span>
              <a href="tel:+243000000000">+243 000 000 000</a>
            </li>
            <li class="contact-block">
              <span class="icon"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#globe"/></svg></span>
              <a href="https://www.upc.ac.cd" target="_blank" rel="noopener">www.upc.ac.cd</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p class="mb-0">&copy; <span id="year"></span> Revue de la Faculté de Théologie - UPC. <?= htmlspecialchars(function_exists('__') ? __('footer.copyright') : 'Tous droits réservés.') ?></p>
        <div class="flex gap-4">
          <a href="<?= $base ?>/politique-editoriale"><?= htmlspecialchars(function_exists('__') ? __('footer.politique') : 'Politique éditoriale') ?></a>
          <a href="<?= $base ?>/mentions-legales"><?= htmlspecialchars(function_exists('__') ? __('footer.mentions') : 'Mentions légales') ?></a>
        </div>
      </div>
    </div>
  </footer>
