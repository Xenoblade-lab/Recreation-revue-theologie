<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volume <?= htmlspecialchars($volume['annee'] ?? '') ?> - Revue de Théologie UPC</title>
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/styles.css">
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/numeros-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .issue-cover-small img { object-fit: cover; height: 160px; background: #f3f4f6; }
        .issues-grid { margin-top: 2rem; }
        .page-header .volume-redacteur { font-size: 1rem; opacity: 0.95; margin-top: 0.5rem; }
    </style>
</head>
<body>
    <?php include __DIR__ . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'header.php'; ?>

    <section class="page-header">
        <div class="container">
            <h1 class="fade-up"><?= htmlspecialchars($volume['numero_volume'] ?? 'Volume ' . ($volume['annee'] ?? '')) ?></h1>
            <p class="fade-up">Année <?= htmlspecialchars($volume['annee'] ?? '') ?></p>
            <?php if (!empty($volume['redacteur_chef'])): ?>
                <p class="fade-up volume-redacteur"><?= htmlspecialchars($volume['redacteur_chef']) ?></p>
            <?php endif; ?>
            <?php if (!empty($volume['description'])): ?>
                <p class="fade-up" style="margin-top: 1rem; color: rgba(255,255,255,0.9);"><?= htmlspecialchars($volume['description']) ?></p>
            <?php endif; ?>
        </div>
    </section>

    <section class="archives-section">
        <div class="container">
            <h2 class="section-title-volume">Numéros de ce volume</h2>
            <?php if (!empty($issues)): ?>
                <div class="issues-grid">
                    <?php foreach ($issues as $issue): ?>
                        <div class="issue-card fade-up">
                            <div class="issue-cover-small">
                                <img src="<?= Router\Router::$defaultUri ?>logo_upc.png" alt="<?= htmlspecialchars($issue['titre'] ?? '') ?>"
                                     onerror="this.onerror=null; this.src='<?= Router\Router::$defaultUri ?>placeholder.svg';">
                            </div>
                            <div class="issue-info">
                                <div class="issue-meta-small">
                                    <span>Numéro <?= htmlspecialchars($issue['numero'] ?? '—') ?></span>
                                    <span><?= htmlspecialchars($volume['annee'] ?? '') ?></span>
                                </div>
                                <h3><?= htmlspecialchars($issue['titre'] ?? 'Sans titre') ?></h3>
                                <p><?= !empty($issue['description']) ? htmlspecialchars(substr($issue['description'], 0, 150)) . '...' : 'Aucune description disponible.' ?></p>
                                <div class="issue-stats-small">
                                    <span><?= htmlspecialchars($issue['article_count'] ?? 0) ?> article(s)</span>
                                    <?php if (!empty($issue['date_publication'])): ?>
                                        <span><?= date('d/m/Y', strtotime($issue['date_publication'])) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="issue-actions-small">
                                    <a href="<?= Router\Router::route('numero', $issue['id']) ?>" class="btn btn-primary">Voir les articles</a>
                                    <?php 
                                    $canDownload = Service\AuthService::canDownloadArticle();
                                    if (!empty($issue['fichier_path']) && $canDownload): ?>
                                        <a href="<?= Router\Router::route('download/issue', $issue['id']) ?>" class="btn btn-outline">Télécharger PDF</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; padding: 2rem; color: var(--color-gray-600);">
                    Aucun numéro disponible pour ce volume.
                </p>
            <?php endif; ?>
        </div>
    </section>

    <?php include __DIR__ . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'footer.php'; ?>
    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
</body>
</html>

