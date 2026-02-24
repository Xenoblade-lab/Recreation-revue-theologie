<?php
/**
 * Header du site (barre utilitaire + logo, nav, actions).
 * Variable $base disponible depuis le layout.
 */
$base = $base ?? (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '');
$isLoggedIn = class_exists('Service\AuthService') && \Service\AuthService::isLoggedIn();
$currentUser = $isLoggedIn ? \Service\AuthService::getUser() : null;
$currentLang = function_exists('current_lang') ? current_lang() : 'fr';
?>
  <div class="site-header-sticky-wrap">
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
          $dashboardLabel = __('nav.my_space');
          if ($role === 'admin') { $dashboardUrl = $base . '/admin'; $dashboardLabel = __('nav.admin'); }
          elseif (in_array($role, ['redacteur', 'redacteur en chef'], true)) { $dashboardUrl = $base . '/reviewer'; $dashboardLabel = __('nav.reviewer'); }
          elseif (in_array($role, ['auteur'], true)) { $dashboardUrl = $base . '/author'; $dashboardLabel = __('nav.my_space'); }
          ?>
          <a href="<?= $dashboardUrl ?>"><?= htmlspecialchars($dashboardLabel) ?></a>
          <span class="text-sm"><?= htmlspecialchars(trim(($currentUser['prenom'] ?? '') . ' ' . ($currentUser['nom'] ?? ''))) ?></span>
          <a href="<?= $base ?>/logout"><?= htmlspecialchars(__('nav.logout')) ?></a>
        <?php else: ?>
          <a href="<?= $base ?>/login"><?= htmlspecialchars(__('nav.login')) ?></a>
          <a href="<?= $base ?>/register"><?= htmlspecialchars(__('nav.register')) ?></a>
        <?php endif; ?>
        <div class="dropdown lang-dropdown">
          <a href="#" class="lang-select" aria-haspopup="true" aria-expanded="false" id="lang-toggle">
            <span class="lang-current"><?= htmlspecialchars(__('lang.' . $currentLang)) ?></span><span aria-hidden="true">▼</span>
          </a>
          <div class="dropdown-menu" aria-labelledby="lang-toggle">
            <a href="<?= $base ?>/lang?l=fr"><?= htmlspecialchars(__('lang.fr')) ?></a>
            <a href="<?= $base ?>/lang?l=en"><?= htmlspecialchars(__('lang.en')) ?></a>
            <a href="<?= $base ?>/lang?l=ln"><?= htmlspecialchars(__('lang.ln')) ?></a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Header principal -->
  <header class="site-header">
    <div class="container inner flex justify-between">
      <a href="<?= $base ?>/" class="logo">
        <img src="<?= $base ?>/images/logo_upc.png" alt="Logo UPC">
        <div class="logo-text">
          <p class="title">Revue Congolaise de Théologie Protestante</p>
          <p class="sub">Université Protestante au Congo</p>
        </div>
      </a>
      <nav class="nav-desktop">
        <a href="<?= $base ?>/"><?= htmlspecialchars(__('nav.home')) ?></a>
        <div class="dropdown">
          <a href="<?= $base ?>/presentation"><?= htmlspecialchars(__('nav.about')) ?> <span aria-hidden="true">▼</span></a>
          <div class="dropdown-menu">
            <a href="<?= $base ?>/presentation"><?= htmlspecialchars(__('nav.presentation')) ?></a>
            <a href="<?= $base ?>/comite"><?= htmlspecialchars(__('nav.comite')) ?></a>
            <a href="<?= $base ?>/politique-editoriale"><?= htmlspecialchars(__('nav.politique')) ?></a>
          </div>
        </div>
        <div class="dropdown">
          <a href="<?= $base ?>/publications"><?= htmlspecialchars(__('nav.articles')) ?> <span aria-hidden="true">▼</span></a>
          <div class="dropdown-menu">
            <a href="<?= $base ?>/publications"><?= htmlspecialchars(__('nav.publications')) ?></a>
            <a href="<?= $base ?>/instructions-auteurs"><?= htmlspecialchars(__('nav.instructions')) ?></a>
          </div>
        </div>
        <div class="dropdown dropdown-badge">
          <a href="<?= $base ?>/archives"><?= htmlspecialchars(__('nav.archives')) ?> <span aria-hidden="true">▼</span></a>
          <span class="nav-badge">Nouveau</span>
          <div class="dropdown-menu">
            <a href="<?= $base ?>/archives"><?= htmlspecialchars(__('nav.volumes')) ?></a>
            <a href="<?= $base ?>/actualites"><?= htmlspecialchars(__('nav.actualites')) ?></a>
          </div>
        </div>
        <a href="<?= $base ?>/contact"><?= htmlspecialchars(__('nav.contact')) ?></a>
        <a href="<?= $base ?>/faq"><?= htmlspecialchars(__('nav.faq')) ?></a>
      </nav>
      <div class="header-actions">
        <span class="header-desk-actions flex items-center gap-2">
          <form action="<?= $base ?>/search" method="get" class="flex items-center gap-1" role="search">
            <input type="search" id="header-search-input" name="q" placeholder="<?= htmlspecialchars(function_exists('__') ? __('search.placeholder') : 'Mot-clé, auteur, titre...') ?>" class="input input-sm" style="width: 180px;" aria-label="<?= htmlspecialchars(__('nav.search')) ?>">
            <button type="submit" class="btn btn-icon btn-outline" aria-label="<?= htmlspecialchars(__('nav.search')) ?>"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#search"/></svg></button>
          </form>
          <?php
          $notificationCount = 0;
          if ($isLoggedIn && !empty($currentUser['id'])) {
              $notificationCount = \Models\NotificationModel::countUnreadByUserId((int) $currentUser['id']);
          }
          ?>
          <?php
          $role = $currentUser['role'] ?? '';
          $isReviewer = in_array($role, ['redacteur', 'redacteur en chef'], true);
          if ($role === 'admin') {
              $notifUrl = $base . '/admin';
          } elseif ($isReviewer) {
              $notifUrl = $base . '/reviewer/notifications';
          } else {
              $notifUrl = $base . '/author/notifications';
          }
          if ($isLoggedIn && ($notificationCount > 0 || in_array($role, ['auteur', 'admin'], true) || $isReviewer)): ?>
            <a href="<?= $notifUrl ?>" class="btn btn-icon btn-outline position-relative" aria-label="<?= htmlspecialchars(function_exists('__') ? __('nav.notifications') : 'Notifications') ?>">
              <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#mail"/></svg>
              <?php if ($notificationCount > 0): ?><span class="badge" style="position:absolute;top:-4px;right:-4px;min-width:1.1em;font-size:0.7rem;"><?= $notificationCount > 99 ? '99+' : $notificationCount ?></span><?php endif; ?>
            </a>
          <?php endif; ?>
          <a href="<?= $base ?>/soumettre" class="btn btn-sm btn-accent"><?= htmlspecialchars(__('nav.submit')) ?></a>
        </span>
        <button type="button" id="menu-toggle" class="menu-toggle" aria-label="Menu" aria-expanded="false"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#menu"/></svg></button>
      </div>
    </div>
    <div id="nav-mobile" class="nav-mobile" aria-hidden="true">
      <div class="container flex flex-col gap-1">
        <a href="<?= $base ?>/"><?= htmlspecialchars(__('nav.home')) ?></a>
        <a href="<?= $base ?>/presentation"><?= htmlspecialchars(__('nav.about')) ?></a>
        <div class="sub">
          <a href="<?= $base ?>/presentation"><?= htmlspecialchars(__('nav.presentation')) ?></a>
          <a href="<?= $base ?>/comite"><?= htmlspecialchars(__('nav.comite')) ?></a>
          <a href="<?= $base ?>/politique-editoriale"><?= htmlspecialchars(__('nav.politique')) ?></a>
        </div>
        <a href="<?= $base ?>/publications"><?= htmlspecialchars(__('nav.articles')) ?></a>
        <div class="sub">
          <a href="<?= $base ?>/publications"><?= htmlspecialchars(__('nav.publications')) ?></a>
          <a href="<?= $base ?>/instructions-auteurs"><?= htmlspecialchars(__('nav.instructions')) ?></a>
        </div>
        <a href="<?= $base ?>/archives"><?= htmlspecialchars(__('nav.archives')) ?></a>
        <div class="sub">
          <a href="<?= $base ?>/archives"><?= htmlspecialchars(__('nav.volumes')) ?></a>
          <a href="<?= $base ?>/actualites"><?= htmlspecialchars(__('nav.actualites')) ?></a>
        </div>
        <a href="<?= $base ?>/contact"><?= htmlspecialchars(__('nav.contact')) ?></a>
        <a href="<?= $base ?>/faq"><?= htmlspecialchars(__('nav.faq')) ?></a>
        <div class="lang-mobile flex items-center gap-2" style="padding: 0.5rem 0;">
          <span class="text-sm"><?= htmlspecialchars(__('lang.' . $currentLang)) ?></span>
          <a href="<?= $base ?>/lang?l=fr"><?= htmlspecialchars(__('lang.fr')) ?></a>
          <a href="<?= $base ?>/lang?l=en"><?= htmlspecialchars(__('lang.en')) ?></a>
          <a href="<?= $base ?>/lang?l=ln"><?= htmlspecialchars(__('lang.ln')) ?></a>
        </div>
        <div class="actions">
          <?php if ($isLoggedIn): ?>
            <?php
            $role = $currentUser['role'] ?? '';
            $dashboardUrl = $base . '/';
            $dashboardLabel = __('nav.my_space');
            if ($role === 'admin') { $dashboardUrl = $base . '/admin'; $dashboardLabel = __('nav.admin'); }
            elseif (in_array($role, ['redacteur', 'redacteur en chef'], true)) { $dashboardUrl = $base . '/reviewer'; $dashboardLabel = __('nav.reviewer'); }
            elseif (in_array($role, ['auteur'], true)) { $dashboardUrl = $base . '/author'; $dashboardLabel = __('nav.my_space'); }
            ?>
            <a href="<?= $dashboardUrl ?>" class="btn btn-outline-primary"><?= htmlspecialchars($dashboardLabel) ?></a>
            <a href="<?= $base ?>/logout" class="btn btn-outline"><?= htmlspecialchars(__('nav.logout')) ?></a>
          <?php else: ?>
            <a href="<?= $base ?>/login" class="btn btn-outline-primary"><?= htmlspecialchars(__('nav.login')) ?></a>
          <?php endif; ?>
          <a href="<?= $base ?>/soumettre" class="btn btn-accent"><?= htmlspecialchars(__('nav.submit')) ?></a>
        </div>
      </div>
    </div>
  </header>
  </div>
