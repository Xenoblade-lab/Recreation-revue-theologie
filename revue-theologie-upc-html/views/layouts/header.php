<?php
/**
 * Header du site (barre utilitaire + logo, nav, actions).
 * Variable $base disponible depuis le layout.
 */
$base = $base ?? (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '');
$isLoggedIn = class_exists('Service\AuthService') && \Service\AuthService::isLoggedIn();
$currentUser = $isLoggedIn ? \Service\AuthService::getUser() : null;
?>
  <!-- Barre utilitaire -->
  <div class="topbar-utility">
    <div class="container flex justify-between items-center">
      <div class="topbar-social">
        <a href="#" aria-label="Facebook"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#globe"/></svg></a>
        <a href="#" aria-label="Twitter"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#globe"/></svg></a>
        <a href="#" aria-label="LinkedIn"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#globe"/></svg></a>
      </div>
      <div class="topbar-links flex items-center gap-4">
        <?php if ($isLoggedIn): ?>
          <span class="text-sm"><?= htmlspecialchars(trim(($currentUser['prenom'] ?? '') . ' ' . ($currentUser['nom'] ?? ''))) ?></span>
          <a href="<?= $base ?>/logout">Déconnexion</a>
        <?php else: ?>
          <a href="<?= $base ?>/login">Connexion</a>
          <a href="<?= $base ?>/register">Inscription</a>
        <?php endif; ?>
        <span class="lang-select"><span class="lang-current">Français</span><span aria-hidden="true">▼</span></span>
      </div>
    </div>
  </div>

  <!-- Header principal -->
  <header class="site-header">
    <div class="container inner flex justify-between">
      <a href="<?= $base ?>/" class="logo">
        <img src="<?= $base ?>/images/logo_upc.png" alt="Logo UPC">
        <div class="logo-text">
          <p class="title">Revue de Théologie</p>
          <p class="sub">Université Protestante au Congo</p>
        </div>
      </a>
      <nav class="nav-desktop">
        <a href="<?= $base ?>/">Accueil</a>
        <div class="dropdown">
          <a href="<?= $base ?>/presentation">À propos <span aria-hidden="true">▼</span></a>
          <div class="dropdown-menu">
            <a href="<?= $base ?>/presentation">Présentation</a>
            <a href="<?= $base ?>/comite">Comité éditorial</a>
            <a href="<?= $base ?>/politique-editoriale">Politique éditoriale</a>
          </div>
        </div>
        <div class="dropdown">
          <a href="<?= $base ?>/publications">Articles <span aria-hidden="true">▼</span></a>
          <div class="dropdown-menu">
            <a href="<?= $base ?>/publications">Publications</a>
            <a href="<?= $base ?>/instructions-auteurs">Instructions aux auteurs</a>
          </div>
        </div>
        <div class="dropdown dropdown-badge">
          <a href="<?= $base ?>/archives">Archives <span aria-hidden="true">▼</span></a>
          <span class="nav-badge">Nouveau</span>
          <div class="dropdown-menu">
            <a href="<?= $base ?>/archives">Volumes & Numéros</a>
            <a href="<?= $base ?>/actualites">Actualités</a>
          </div>
        </div>
        <a href="<?= $base ?>/contact">Contact</a>
        <a href="<?= $base ?>/faq">FAQ</a>
      </nav>
      <div class="header-actions">
        <span class="header-desk-actions">
          <button type="button" class="btn btn-icon btn-outline" id="header-search-btn" aria-label="Rechercher"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#search"/></svg></button>
          <a href="<?= $base ?>/soumettre" class="btn btn-sm btn-accent">Soumettre un article</a>
        </span>
        <button type="button" id="menu-toggle" class="menu-toggle" aria-label="Menu" aria-expanded="false"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#menu"/></svg></button>
      </div>
    </div>
    <div id="nav-mobile" class="nav-mobile" aria-hidden="true">
      <div class="container flex flex-col gap-1">
        <a href="<?= $base ?>/">Accueil</a>
        <a href="<?= $base ?>/presentation">À propos</a>
        <div class="sub">
          <a href="<?= $base ?>/presentation">Présentation</a>
          <a href="<?= $base ?>/comite">Comité éditorial</a>
          <a href="<?= $base ?>/politique-editoriale">Politique éditoriale</a>
        </div>
        <a href="<?= $base ?>/publications">Articles</a>
        <div class="sub">
          <a href="<?= $base ?>/publications">Publications</a>
          <a href="<?= $base ?>/instructions-auteurs">Instructions aux auteurs</a>
        </div>
        <a href="<?= $base ?>/archives">Archives</a>
        <div class="sub">
          <a href="<?= $base ?>/archives">Volumes & Numéros</a>
          <a href="<?= $base ?>/actualites">Actualités</a>
        </div>
        <a href="<?= $base ?>/contact">Contact</a>
        <a href="<?= $base ?>/faq">FAQ</a>
        <div class="actions">
          <?php if ($isLoggedIn): ?>
            <a href="<?= $base ?>/logout" class="btn btn-outline">Déconnexion</a>
          <?php else: ?>
            <a href="<?= $base ?>/login" class="btn btn-outline-primary">Connexion</a>
          <?php endif; ?>
          <a href="<?= $base ?>/soumettre" class="btn btn-accent">Soumettre un article</a>
        </div>
      </div>
    </div>
  </header>
