<?php
/**
 * Layout des pages d'authentification (login, register, forgot-password).
 * Variables : $viewContent, $pageTitle, $base.
 */
$base = $base ?? (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '');
$pageTitle = $pageTitle ?? 'Connexion | Revue UPC';
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
<body class="auth-page">
  <div class="auth-side">
    <div>
      <a href="<?= $base ?>/" class="logo flex items-center gap-3">
        <img src="<?= $base ?>/images/logo_upc.png" alt="Logo UPC">
        <div>
          <p class="font-serif font-bold text-sm">Revue de Théologie</p>
          <p class="text-xs" style="color: rgba(255,255,255,0.6);">Université Protestante au Congo</p>
        </div>
      </a>
    </div>
    <div>
      <h2 class="font-serif text-3xl font-bold text-balance leading-tight">Contribuez au rayonnement de la recherche théologale en Afrique</h2>
      <p class="mt-4">Connectez-vous à votre espace auteur pour soumettre vos articles, suivre le processus d'évaluation et gérer vos publications.</p>
      <div class="divider mt-4" style="width: 4rem; height: 4px; background: var(--upc-gold);"></div>
    </div>
    <p class="text-xs italic" style="color: rgba(255,255,255,0.4);">"Vérité, Foi, Liberté"</p>
  </div>
  <div class="auth-main">
    <div class="box">
      <?= $viewContent ?? '' ?>
    </div>
  </div>
  <script src="<?= $base ?>/js/main.js"></script>
</body>
</html>
