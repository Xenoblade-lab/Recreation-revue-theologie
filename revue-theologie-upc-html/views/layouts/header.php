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
          <?php
          $role = $currentUser['role'] ?? '';
          $dashboardUrl = $base . '/';
          $dashboardLabel = 'Mon espace';
          if ($role === 'admin') { $dashboardUrl = $base . '/admin'; $dashboardLabel = 'Administration'; }
          elseif (in_array($role, ['redacteur', 'redacteur en chef'], true)) { $dashboardUrl = $base . '/reviewer'; $dashboardLabel = 'Espace évaluateur'; }
          elseif (in_array($role, ['auteur'], true)) { $dashboardUrl = $base . '/author'; $dashboardLabel = 'Mon espace'; }
          ?>
          <a href="<?= $dashboardUrl ?>"><?= htmlspecialchars($dashboardLabel) ?></a>
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
        <span class="header-desk-actions flex items-center gap-2">
          <form action="<?= $base ?>/search" method="get" class="flex items-center gap-1" role="search">
            <input type="search" id="header-search-input" name="q" placeholder="" class="input input-sm" style="width: 140px;" aria-label="Rechercher">
            <button type="submit" class="btn btn-icon btn-outline" aria-label="Rechercher"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#search"/></svg></button>
          </form>
          <?php
          $notificationCount = 0;
          if ($isLoggedIn && !empty($currentUser['id'])) {
              $notificationCount = \Models\NotificationModel::countUnreadByUserId((int) $currentUser['id']);
          }
          ?>
          <?php
          $isReviewer = in_array($currentUser['role'] ?? '', ['redacteur', 'redacteur en chef'], true);
          $notifUrl = $isReviewer ? $base . '/reviewer/notifications' : $base . '/author/notifications';
          if ($isLoggedIn && ($notificationCount > 0 || in_array($currentUser['role'] ?? '', ['auteur', 'admin'], true) || $isReviewer)): ?>
            <a href="<?= $notifUrl ?>" class="btn btn-icon btn-outline position-relative" aria-label="Notifications">
              <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#mail"/></svg>
              <?php if ($notificationCount > 0): ?><span class="badge" style="position:absolute;top:-4px;right:-4px;min-width:1.1em;font-size:0.7rem;"><?= $notificationCount > 99 ? '99+' : $notificationCount ?></span><?php endif; ?>
            </a>
          <?php endif; ?>
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
            <?php
            $role = $currentUser['role'] ?? '';
            $dashboardUrl = $base . '/';
            $dashboardLabel = 'Mon espace';
            if ($role === 'admin') { $dashboardUrl = $base . '/admin'; $dashboardLabel = 'Administration'; }
            elseif (in_array($role, ['redacteur', 'redacteur en chef'], true)) { $dashboardUrl = $base . '/reviewer'; $dashboardLabel = 'Espace évaluateur'; }
            elseif (in_array($role, ['auteur'], true)) { $dashboardUrl = $base . '/author'; $dashboardLabel = 'Mon espace'; }
            ?>
            <a href="<?= $dashboardUrl ?>" class="btn btn-outline-primary"><?= htmlspecialchars($dashboardLabel) ?></a>
            <a href="<?= $base ?>/logout" class="btn btn-outline">Déconnexion</a>
          <?php else: ?>
            <a href="<?= $base ?>/login" class="btn btn-outline-primary">Connexion</a>
          <?php endif; ?>
          <a href="<?= $base ?>/soumettre" class="btn btn-accent">Soumettre un article</a>
        </div>
      </div>
    </div>
  </header>
