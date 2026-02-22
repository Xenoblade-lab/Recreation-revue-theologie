<?php
/**
 * Layout espace administration : topbar + sidebar + contenu.
 * Variables : $viewContent, $pageTitle, $base.
 */
$base = $base ?? (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '');
$pageTitle = $pageTitle ?? 'Administration | Revue UPC';
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
      <span class="text-sm font-medium">Administration</span>
    </div>
    <div class="breadcrumb">
      <a href="<?= $base ?>/">Accueil</a>
      <a href="<?= $base ?>/admin">Dashboard</a>
      <a href="<?= $base ?>/logout" class="ml-4 text-accent">Déconnexion</a>
    </div>
  </div>
  <div class="dashboard-body">
    <aside class="dashboard-sidebar" id="dashboard-sidebar">
      <div class="sidebar-inner">
        <p class="sidebar-title">Administration</p>
        <nav>
          <a href="<?= $base ?>/admin" class="<?= ($_SESSION['admin_page'] ?? '') === 'index' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#tag"/></svg>
            Tableau de bord
          </a>
          <a href="<?= $base ?>/admin/users" class="<?= ($_SESSION['admin_page'] ?? '') === 'users' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg>
            Utilisateurs
          </a>
          <a href="<?= $base ?>/admin/articles" class="<?= ($_SESSION['admin_page'] ?? '') === 'articles' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg>
            Articles
          </a>
          <a href="<?= $base ?>/admin/volumes" class="<?= ($_SESSION['admin_page'] ?? '') === 'volumes' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#book"/></svg>
            Volumes & Numéros
          </a>
          <a href="<?= $base ?>/admin/paiements" class="<?= ($_SESSION['admin_page'] ?? '') === 'paiements' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#award"/></svg>
            Paiements
          </a>
          <a href="<?= $base ?>/admin/parametres" class="<?= ($_SESSION['admin_page'] ?? '') === 'parametres' ? 'active' : '' ?>">
            <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clipboard-check"/></svg>
            Paramètres revue
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
        <p class="mb-0">&copy; <span id="year"></span> Revue de la Faculté de Théologie - UPC. Espace administrateur.</p>
        <a href="<?= $base ?>/logout">Déconnexion</a>
      </div>
    </div>
  </footer>
  <script>if (document.getElementById('year')) document.getElementById('year').textContent = new Date().getFullYear();</script>
  <script src="<?= $base ?>/js/main.js"></script>
</body>
</html>
