<?php
/**
 * Layout espace évaluateur : topbar + sidebar + contenu.
 * Variables : $viewContent, $pageTitle, $base.
 */
$base = $base ?? (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '');
$pageTitle = $pageTitle ?? 'Espace évaluateur | Revue Congolaise de Théologie Protestante';
$notificationCount = 0;
if (!empty($currentUser['id']) && class_exists('Models\NotificationModel')) {
    $notificationCount = \Models\NotificationModel::countUnreadByUserId((int) $currentUser['id']);
}
?>
<!DOCTYPE html>
<html lang="<?= function_exists('current_lang') ? htmlspecialchars(current_lang()) : 'fr' ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link rel="icon" href="<?= $base ?>/images/logo_upc.png" type="image/png">
  <link rel="stylesheet" href="<?= $base ?>/css/styles.css">
</head>
<body class="dashboard-layout min-h-screen flex flex-col">
  <div class="dashboard-topbar">
    <button type="button" class="dashboard-mobile-menu" id="dashboard-menu-toggle" aria-label="<?= htmlspecialchars(function_exists('__') ? __('dash.open_menu') : 'Ouvrir le menu') ?>" aria-expanded="false">
      <svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#menu"/></svg>
    </button>
    <div class="flex items-center gap-4 flex-wrap">
      <a href="<?= $base ?>/" class="flex items-center gap-2">
        <img src="<?= $base ?>/images/logo_upc.png" alt="Logo UPC" width="36" height="36">
        <span class="dashboard-topbar-brand font-serif font-bold text-primary">Revue Congolaise de Théologie Protestante</span>
      </a>
      <span class="dashboard-topbar-sep text-muted">|</span>
      <span class="text-sm font-medium"><?= htmlspecialchars(function_exists('__') ? __('dash.reviewer_space') : 'Espace Évaluateur') ?></span>
    </div>
    <div class="breadcrumb">
      <a href="<?= $base ?>/"><?= htmlspecialchars(function_exists('__') ? __('dash.home') : 'Accueil') ?></a>
      <a href="<?= $base ?>/reviewer"><?= htmlspecialchars(function_exists('__') ? __('dash.dashboard') : 'Dashboard') ?></a>
      <a href="<?= $base ?>/reviewer/terminees" class="breadcrumb-optional text-primary"><?= htmlspecialchars(function_exists('__') ? __('reviewer.done') : 'Terminées') ?></a>
      <a href="<?= $base ?>/reviewer/historique" class="breadcrumb-optional text-primary"><?= htmlspecialchars(function_exists('__') ? __('reviewer.history_title') : 'Historique') ?></a>
      <a href="<?= $base ?>/logout" class="ml-4 text-accent"><?= htmlspecialchars(function_exists('__') ? __('dash.logout') : 'Déconnexion') ?></a>
    </div>
  </div>
  <div class="dashboard-body">
    <div class="dashboard-sidebar-backdrop" id="dashboard-sidebar-backdrop" aria-hidden="true"></div>
    <aside class="dashboard-sidebar" id="dashboard-sidebar">
      <div class="sidebar-inner">
        <p class="sidebar-title"><?= htmlspecialchars(function_exists('__') ? __('dash.reviewer_space') : 'Espace évaluateur') ?></p>
        <nav>
          <a href="<?= $base ?>/reviewer" class="<?= ($_SESSION['reviewer_page'] ?? '') === 'index' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#tag"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('dash.dashboard') : 'Tableau de bord') ?>
          </a>
          <a href="<?= $base ?>/reviewer/terminees" class="<?= ($_SESSION['reviewer_page'] ?? '') === 'terminees' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clipboard-check"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('reviewer.done_list') : 'Évaluations terminées') ?>
          </a>
          <a href="<?= $base ?>/reviewer/historique" class="<?= ($_SESSION['reviewer_page'] ?? '') === 'historique' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clock"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('reviewer.history_title') : 'Historique') ?>
          </a>
          <a href="<?= $base ?>/reviewer/notifications" class="<?= ($_SESSION['reviewer_page'] ?? '') === 'notifications' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#mail"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('author.notifications') : 'Notifications') ?>
          </a>
          <a href="<?= $base ?>/reviewer/profil" class="<?= ($_SESSION['reviewer_page'] ?? '') === 'profil' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('reviewer.profil_menu') : 'Mon profil') ?>
          </a>
          <a href="<?= $base ?>/publications">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#book"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('nav.publications') : 'Publications') ?>
          </a>
          <a href="<?= $base ?>/">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#globe"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('dash.view_site') : 'Voir le site') ?>
          </a>
        </nav>
      </div>
    </aside>
    <div class="dashboard-content-wrap">
      <main class="dashboard-main">
        <?= $viewContent ?? '' ?>
      </main>
      <footer class="site-footer" style="padding-top: 1.5rem; padding-bottom: 1.5rem;">
        <div class="container">
          <div class="footer-bottom">
            <p class="mb-0">&copy; <span id="year"></span> Revue Congolaise de Théologie Protestante - UPC. <?= htmlspecialchars(function_exists('__') ? __('dash.footer_reviewer') : 'Espace évaluateur') ?>.</p>
            <a href="<?= $base ?>/logout"><?= htmlspecialchars(function_exists('__') ? __('dash.logout') : 'Déconnexion') ?></a>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <script>if (document.getElementById('year')) document.getElementById('year').textContent = new Date().getFullYear();</script>
  <script src="<?= $base ?>/js/main.js"></script>
</body>
</html>
