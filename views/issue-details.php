<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($issue['titre'] ?? 'Numéro') ?> - Revue de Théologie UPC</title>
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'header.php'; ?>

    <section class="page-header">
        <div class="container">
            <?php if (!empty($issue['annee'])): ?>
                <p class="fade-up" style="margin-bottom: 0.5rem;">
                    <a href="<?= Router\Router::route('volume', $issue['annee']) ?>" style="color: rgba(255,255,255,0.9); text-decoration: none;">← Retour au volume <?= htmlspecialchars($issue['annee']) ?></a>
                </p>
            <?php endif; ?>
            <h1 class="fade-up"><?= htmlspecialchars($issue['titre'] ?? 'Numéro') ?></h1>
            <p class="fade-up">
                <?= htmlspecialchars($issue['numero'] ?? '—') ?> 
                <?php if (!empty($issue['annee'])): ?>
                    - <?= htmlspecialchars($issue['annee']) ?>
                <?php endif; ?>
            </p>
            <?php if (!empty($issue['description'])): ?>
                <p class="fade-up" style="margin-top: 1rem; color: var(--color-gray-600); max-width: 800px; margin-left: auto; margin-right: auto;">
                    <?= nl2br(htmlspecialchars($issue['description'])) ?>
                </p>
            <?php endif; ?>
            <?php 
            $canDownload = Service\AuthService::canDownloadArticle();
            if (!empty($issue['fichier_path'])): 
                if ($canDownload): ?>
                <div class="fade-up" style="margin-top: 1.5rem;">
                    <a href="<?= Router\Router::route('download/issue', $issue['id']) ?>" class="btn btn-primary">
                        Télécharger le PDF complet
                    </a>
                </div>
            <?php else: ?>
                <p class="fade-up" style="margin-top: 1rem; color: rgba(255,255,255,0.9); font-size: 0.95rem;">
                    <a href="<?= Router\Router::route('login') ?>" style="color: #fff; text-decoration: underline;">Connectez-vous</a> et <a href="<?= Router\Router::route('author') ?>/subscribe" style="color: #fff; text-decoration: underline;">abonnez-vous</a> pour télécharger ce numéro.
                </p>
            <?php endif; endif; ?>
        </div>
    </section>

    <section class="archives-section">
        <div class="container">
            <h2 style="margin-bottom: 2rem; text-align: center;">Articles de ce numéro</h2>
            
            <?php if (!empty($articles)): ?>
                <div class="articles-list" style="max-width: 900px; margin: 0 auto;">
                    <?php foreach ($articles as $article): ?>
                        <div class="article-card" style="background: white; padding: 1.5rem; margin-bottom: 1.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            <h3 style="margin-top: 0; color: var(--color-primary);">
                                <a href="<?= Router\Router::route('article', $article['id']) ?>" 
                                   style="color: inherit; text-decoration: none;">
                                    <?= htmlspecialchars($article['titre'] ?? 'Sans titre') ?>
                                </a>
                            </h3>
                            <p style="color: var(--color-gray-600); margin: 0.5rem 0;">
                                <strong>Auteur :</strong> 
                                <?= htmlspecialchars(trim(($article['auteur_prenom'] ?? '') . ' ' . ($article['auteur_nom'] ?? ''))) ?>
                            </p>
                            <?php if (!empty($article['date_soumission'])): ?>
                                <p style="color: var(--color-gray-600); margin: 0.5rem 0; font-size: 0.9rem;">
                                    Publié le <?= date('d M Y', strtotime($article['date_soumission'])) ?>
                                </p>
                            <?php endif; ?>
                            <?php if (!empty($article['fichier_path']) && $canDownload): ?>
                                <div style="margin-top: 1rem;">
                                    <a href="<?= Router\Router::route('download/article', $article['id']) ?>" 
                                       class="btn btn-outline btn-sm">
                                        Télécharger l'article
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; padding: 2rem; color: var(--color-gray-600);">
                    Aucun article disponible dans ce numéro.
                </p>
            <?php endif; ?>
        </div>
    </section>

    <?php include __DIR__ . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'footer.php'; ?>
    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
</body>
</html>

