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
          <h3 class="footer-title">Navigation</h3>
          <ul>
            <li><a href="<?= $base ?>/">Accueil</a></li>
            <li><a href="<?= $base ?>/publications">Publications</a></li>
            <li><a href="<?= $base ?>/archives">Archives</a></li>
            <li><a href="<?= $base ?>/comite">Comité éditorial</a></li>
            <li><a href="<?= $base ?>/presentation">Présentation</a></li>
          </ul>
        </div>
        <div>
          <h3 class="footer-title">Pour les auteurs</h3>
          <ul>
            <li><a href="<?= $base ?>/soumettre">Soumettre un article</a></li>
            <li><a href="<?= $base ?>/instructions-auteurs">Instructions aux auteurs</a></li>
            <li><a href="<?= $base ?>/politique-editoriale">Processus d'évaluation</a></li>
            <li><a href="<?= $base ?>/login">Connexion</a></li>
            <li><a href="<?= $base ?>/faq">FAQ</a></li>
          </ul>
        </div>
        <div>
          <h3 class="footer-title">Contact</h3>
          <ul>
            <li class="contact-block">
              <span class="icon"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#map-pin"/></svg></span>
              <span>Université Protestante au Congo, Kinshasa, RD Congo</span>
            </li>
            <li class="contact-block">
              <span class="icon"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#mail"/></svg></span>
              <a href="mailto:revue.theologie@upc.ac.cd">revue.theologie@upc.ac.cd</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p class="mb-0">&copy; <span id="year"></span> Revue de la Faculté de Théologie - UPC. Tous droits réservés.</p>
        <div class="flex gap-4">
          <a href="<?= $base ?>/politique-editoriale">Politique éditoriale</a>
          <a href="<?= $base ?>/mentions-legales">Mentions légales</a>
        </div>
      </div>
    </div>
  </footer>
