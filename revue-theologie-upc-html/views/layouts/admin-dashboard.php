<?php
/**
 * Layout espace administration : topbar + sidebar + contenu.
 * Variables : $viewContent, $pageTitle, $base.
 */
$base = $base ?? (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '');
$pageTitle = $pageTitle ?? 'Administration | Revue Congolaise de Théologie Protestante';
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
      <span class="text-sm font-medium"><?= htmlspecialchars(function_exists('__') ? __('dash.admin_space') : 'Administration') ?></span>
    </div>
    <div class="breadcrumb">
      <a href="<?= $base ?>/"><?= htmlspecialchars(function_exists('__') ? __('dash.home') : 'Accueil') ?></a>
      <a href="<?= $base ?>/admin"><?= htmlspecialchars(function_exists('__') ? __('dash.dashboard') : 'Dashboard') ?></a>
      <a href="<?= $base ?>/logout" class="ml-4 text-accent"><?= htmlspecialchars(function_exists('__') ? __('dash.logout') : 'Déconnexion') ?></a>
    </div>
  </div>
  <div class="dashboard-body">
    <div class="dashboard-sidebar-backdrop" id="dashboard-sidebar-backdrop" aria-hidden="true"></div>
    <aside class="dashboard-sidebar" id="dashboard-sidebar">
      <div class="sidebar-inner">
        <p class="sidebar-title"><?= htmlspecialchars(function_exists('__') ? __('dash.admin_space') : 'Administration') ?></p>
        <nav>
          <a href="<?= $base ?>/admin" class="<?= ($_SESSION['admin_page'] ?? '') === 'index' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#tag"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('dash.dashboard') : 'Tableau de bord') ?>
          </a>
          <a href="<?= $base ?>/admin/users" class="<?= ($_SESSION['admin_page'] ?? '') === 'users' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('admin.users') : 'Utilisateurs') ?>
          </a>
          <a href="<?= $base ?>/admin/articles" class="<?= ($_SESSION['admin_page'] ?? '') === 'articles' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('admin.articles') : 'Articles') ?>
          </a>
          <a href="<?= $base ?>/admin/evaluations" class="<?= ($_SESSION['admin_page'] ?? '') === 'evaluations' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clipboard-check"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('admin.evaluations_list') : 'Évaluations') ?>
          </a>
          <a href="<?= $base ?>/admin/volumes" class="<?= ($_SESSION['admin_page'] ?? '') === 'volumes' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#book"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('admin.volumes') : 'Volumes & Numéros') ?>
          </a>
          <a href="<?= $base ?>/admin/paiements" class="<?= ($_SESSION['admin_page'] ?? '') === 'paiements' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#award"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('admin.paiements') : 'Paiements') ?>
          </a>
          <a href="<?= $base ?>/admin/comite-editorial" class="<?= ($_SESSION['admin_page'] ?? '') === 'comite-editorial' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('admin.comite_editorial_menu') : 'Comité éditorial') ?>
          </a>
          <a href="<?= $base ?>/admin/notifications" class="<?= ($_SESSION['admin_page'] ?? '') === 'notifications' ? 'active' : '' ?>"<?php if ($notificationCount > 0): ?> aria-label="<?= htmlspecialchars((function_exists('__') ? __('nav.notifications') : 'Notifications') . ', ' . $notificationCount . ' non lues') ?>"<?php endif; ?>>
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#mail"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('nav.notifications') : 'Notifications') ?>
            <?php if ($notificationCount > 0): ?><span class="sidebar-notif-badge" aria-hidden="true"><?= $notificationCount > 99 ? '99+' : $notificationCount ?></span><?php endif; ?>
          </a>
          <a href="<?= $base ?>/admin/parametres" class="<?= ($_SESSION['admin_page'] ?? '') === 'parametres' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clipboard-check"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('admin.review_params') : 'Paramètres revue') ?>
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
            <p class="mb-0">&copy; <span id="year"></span> Revue Congolaise de Théologie Protestante - UPC. <?= htmlspecialchars(function_exists('__') ? __('dash.footer_admin') : 'Espace administrateur') ?>.</p>
            <a href="<?= $base ?>/logout"><?= htmlspecialchars(function_exists('__') ? __('dash.logout') : 'Déconnexion') ?></a>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <div id="confirm-modal" class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="confirm-modal-title" aria-hidden="true" hidden>
    <div class="confirm-modal-overlay"></div>
    <div class="confirm-modal-box">
      <p id="confirm-modal-message" class="confirm-modal-message"></p>
      <div class="confirm-modal-actions">
        <button type="button" class="btn btn-outline confirm-modal-cancel"><?= htmlspecialchars(function_exists('__') ? __('common.cancel') : 'Annuler') ?></button>
        <button type="button" class="btn btn-primary confirm-modal-confirm"><?= htmlspecialchars(function_exists('__') ? __('common.confirm') : 'Confirmer') ?></button>
      </div>
    </div>
  </div>
  <script>if (document.getElementById('year')) document.getElementById('year').textContent = new Date().getFullYear();</script>
  <script src="<?= $base ?>/js/main.js"></script>
</body>
</html>
