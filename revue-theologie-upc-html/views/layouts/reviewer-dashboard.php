<?php
/**
 * Layout espace évaluateur : topbar + sidebar + contenu.
 * Variables : $viewContent, $pageTitle, $base.
 */
$base = $base ?? (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '');
$pageTitle = $pageTitle ?? 'Espace évaluateur | Revue UPC';
?>
<!DOCTYPE html>
<html lang="fr">
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
      <span class="text-sm font-medium">Espace Évaluateur</span>
    </div>
    <div class="breadcrumb">
      <a href="<?= $base ?>/">Accueil</a>
      <a href="<?= $base ?>/reviewer">Dashboard</a>
      <a href="<?= $base ?>/reviewer/terminees" class="text-primary">Terminées</a>
      <a href="<?= $base ?>/reviewer/historique" class="text-primary">Historique</a>
      <a href="<?= $base ?>/logout" class="ml-4 text-accent">Déconnexion</a>
    </div>
  </div>
  <div class="dashboard-body">
    <aside class="dashboard-sidebar" id="dashboard-sidebar">
      <div class="sidebar-inner">
        <p class="sidebar-title">Espace évaluateur</p>
        <nav>
          <a href="<?= $base ?>/reviewer" class="<?= ($_SESSION['reviewer_page'] ?? '') === 'index' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#tag"/></svg>
            Tableau de bord
          </a>
          <a href="<?= $base ?>/reviewer/terminees" class="<?= ($_SESSION['reviewer_page'] ?? '') === 'terminees' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clipboard-check"/></svg>
            Évaluations terminées
          </a>
          <a href="<?= $base ?>/reviewer/historique" class="<?= ($_SESSION['reviewer_page'] ?? '') === 'historique' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clock"/></svg>
            Historique
          </a>
          <a href="<?= $base ?>/reviewer/notifications" class="<?= ($_SESSION['reviewer_page'] ?? '') === 'notifications' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#mail"/></svg>
            Notifications
          </a>
          <a href="<?= $base ?>/publications">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#book"/></svg>
            Publications
          </a>
          <a href="<?= $base ?>/">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#globe"/></svg>
            Voir le site
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
        <p class="mb-0">&copy; <span id="year"></span> Revue de la Faculté de Théologie - UPC. Espace évaluateur.</p>
        <a href="<?= $base ?>/logout">Déconnexion</a>
      </div>
    </div>
  </footer>
  <script>if (document.getElementById('year')) document.getElementById('year').textContent = new Date().getFullYear();</script>
  <script src="<?= $base ?>/js/main.js"></script>
</body>
</html>
