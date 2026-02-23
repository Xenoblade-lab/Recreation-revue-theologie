<?php
/**
 * Layout espace auteur : topbar + sidebar + contenu.
 * Variables : $viewContent, $pageTitle, $base.
 */
$base = $base ?? (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '');
$pageTitle = $pageTitle ?? 'Espace auteur | Revue UPC';
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
    <div class="flex items-center gap-4">
      <a href="<?= $base ?>/" class="flex items-center gap-2">
        <img src="<?= $base ?>/images/logo_upc.png" alt="Logo UPC" width="36" height="36">
        <span class="font-serif font-bold text-primary">Revue de Théologie</span>
      </a>
      <span class="text-muted">|</span>
      <span class="text-sm font-medium"><?= htmlspecialchars(function_exists('__') ? __('dash.author_space') : 'Espace Auteur') ?></span>
    </div>
    <div class="breadcrumb">
      <a href="<?= $base ?>/"><?= htmlspecialchars(function_exists('__') ? __('dash.home') : 'Accueil') ?></a>
      <a href="<?= $base ?>/author"><?= htmlspecialchars(function_exists('__') ? __('dash.dashboard') : 'Dashboard') ?></a>
      <a href="<?= $base ?>/author/abonnement" class="text-primary"><?= htmlspecialchars(function_exists('__') ? __('author.subscription') : 'Abonnement') ?></a>
      <a href="<?= $base ?>/author/notifications" class="text-primary"><?= htmlspecialchars(function_exists('__') ? __('author.notifications') : 'Notifications') ?></a>
      <a href="<?= $base ?>/logout" class="ml-4 text-accent"><?= htmlspecialchars(function_exists('__') ? __('dash.logout') : 'Déconnexion') ?></a>
    </div>
  </div>
  <div class="dashboard-body">
    <aside class="dashboard-sidebar" id="dashboard-sidebar">
      <div class="sidebar-inner">
        <p class="sidebar-title"><?= htmlspecialchars(function_exists('__') ? __('dash.author_space') : 'Espace auteur') ?></p>
        <nav>
          <a href="<?= $base ?>/author" class="<?= ($_SESSION['author_page'] ?? '') === 'index' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#tag"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('dash.dashboard') : 'Tableau de bord') ?>
          </a>
          <a href="<?= $base ?>/author#articles">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('author.my_articles') : 'Mes articles') ?>
          </a>
          <a href="<?= $base ?>/author/abonnement" class="<?= ($_SESSION['author_page'] ?? '') === 'abonnement' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#award"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('author.subscription') : 'Abonnement') ?>
          </a>
          <a href="<?= $base ?>/author/notifications" class="<?= ($_SESSION['author_page'] ?? '') === 'notifications' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#mail"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('author.notifications') : 'Notifications') ?>
          </a>
          <a href="<?= $base ?>/author/soumettre" class="<?= ($_SESSION['author_page'] ?? '') === 'soumettre' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#upload"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('nav.submit') : 'Soumettre un article') ?>
          </a>
          <a href="<?= $base ?>/">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#globe"/></svg>
            <?= htmlspecialchars(function_exists('__') ? __('dash.view_site') : 'Voir le site') ?>
          </a>
        </nav>
      </div>
    </aside>
    <main class="dashboard-main">
      <?= $viewContent ?? '' ?>
    </main>
  </div>
  <footer class="site-footer" style="padding-top: 1.5rem; padding-bottom: 1.5rem;">
    <div class="container">
      <div class="footer-bottom">
        <p class="mb-0">&copy; <span id="year"></span> Revue de la Faculté de Théologie - UPC. <?= htmlspecialchars(function_exists('__') ? __('dash.footer_author') : 'Espace auteur') ?>.</p>
        <a href="<?= $base ?>/logout"><?= htmlspecialchars(function_exists('__') ? __('dash.logout') : 'Déconnexion') ?></a>
      </div>
    </div>
  </footer>
  <script>if (document.getElementById('year')) document.getElementById('year').textContent = new Date().getFullYear();</script>
  <script src="<?= $base ?>/js/main.js"></script>
</body>
</html>
