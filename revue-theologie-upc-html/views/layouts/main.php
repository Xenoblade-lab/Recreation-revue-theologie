<?php
/**
 * Layout principal — inclut header, contenu variable, footer.
 * Variables attendues : $viewContent (contenu de la page), $pageTitle (optionnel).
 */
$base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
$pageTitle = $pageTitle ?? 'Revue Congolaise de Théologie Protestante | UPC';
?>
<!DOCTYPE html>
<html lang="<?= function_exists('current_lang') ? htmlspecialchars(current_lang()) : 'fr' ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDescription ?? 'Revue Congolaise de Théologie Protestante — Publication de l\'UPC. Articles en théologie, études bibliques, éthique chrétienne et histoire de l\'Église, avec un accent sur les contextes africains.') ?>">
  <meta name="robots" content="index, follow">
  <link rel="icon" href="<?= $base ?>/images/logo_upc.png" type="image/png">
  <link rel="stylesheet" href="<?= $base ?>/css/styles.css">
</head>
<body class="min-h-screen flex flex-col page-template-2">
<?php require __DIR__ . '/header.php'; ?>
  <main class="flex-1">
    <?= $viewContent ?? '' ?>
  </main>
<?php require __DIR__ . '/footer.php'; ?>
  <script>if (document.getElementById('year')) document.getElementById('year').textContent = new Date().getFullYear();</script>
  <script src="<?= $base ?>/js/main.js"></script>
</body>
</html>
