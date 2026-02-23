<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Présentation - Revue de Théologie UPC</title>
    <?php $baseUrl = Router\Router::$defaultUri; ?>
    <link rel="stylesheet" href="<?= $baseUrl ?>css/styles.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>css/numeros-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .presentation-section { padding: var(--spacing-2xl) 0; }
        .presentation-block { margin-bottom: 2.5rem; }
        .presentation-block h2 { font-size: 1.35rem; color: var(--color-blue); margin-bottom: 0.75rem; }
        .presentation-block p, .presentation-block div { line-height: 1.7; color: var(--color-gray-700); }
        .presentation-empty { color: var(--color-gray-500); font-style: italic; }
    </style>
</head>
<body>
    <?php include __DIR__ . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'header.php'; ?>

    <section class="page-header">
        <div class="container">
            <h1 class="fade-up">Présentation de la revue</h1>
            <p class="fade-up"><?= htmlspecialchars($revueInfo['nom_officiel'] ?? 'Revue de la Faculté de Théologie - UPC') ?></p>
        </div>
    </section>

    <section class="archives-section presentation-section">
        <div class="container" style="max-width: 800px;">
            <?php
            $info = $revueInfo ?? [];
            $description = trim($info['description'] ?? '');
            $ligneEditoriale = trim($info['ligne_editoriale'] ?? '');
            $objectifs = trim($info['objectifs'] ?? '');
            $domainesCouverts = trim($info['domaines_couverts'] ?? '');
            ?>

            <?php if ($description !== ''): ?>
            <div class="presentation-block fade-up">
                <h2>À propos</h2>
                <div><?= nl2br(htmlspecialchars($description)) ?></div>
            </div>
            <?php endif; ?>

            <?php if ($ligneEditoriale !== ''): ?>
            <div class="presentation-block fade-up">
                <h2>Ligne éditoriale</h2>
                <div><?= nl2br(htmlspecialchars($ligneEditoriale)) ?></div>
            </div>
            <?php endif; ?>

            <?php if ($objectifs !== ''): ?>
            <div class="presentation-block fade-up">
                <h2>Objectifs</h2>
                <div><?= nl2br(htmlspecialchars($objectifs)) ?></div>
            </div>
            <?php endif; ?>

            <?php if ($domainesCouverts !== ''): ?>
            <div class="presentation-block fade-up">
                <h2>Domaines couverts</h2>
                <div><?= nl2br(htmlspecialchars($domainesCouverts)) ?></div>
            </div>
            <?php endif; ?>

            <?php if ($description === '' && $ligneEditoriale === '' && $objectifs === '' && $domainesCouverts === ''): ?>
            <div class="presentation-block fade-up">
                <p class="presentation-empty">La présentation de la revue n'est pas encore renseignée. Elle sera disponible dans les paramètres de la revue (administration).</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include __DIR__ . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'footer.php'; ?>
    <script src="<?= $baseUrl ?>js/script.js"></script>
</body>
</html>
