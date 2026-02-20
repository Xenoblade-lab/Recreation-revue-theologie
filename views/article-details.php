<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['titre'] ?? 'Article') ?> - Revue de Théologie UPC</title>
    <?php
    $baseUrl = Router\Router::$defaultUri;
    ?>
    <link rel="stylesheet" href="<?= $baseUrl ?>css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .page-header {
            background: linear-gradient(135deg, var(--color-blue) 0%, #0f2847 100%);
            color: var(--color-white);
            padding: var(--spacing-2xl) 0;
            text-align: center;
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: var(--spacing-sm);
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .page-header .article-meta-header {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            margin-top: var(--spacing-md);
        }
        
        .article-details-section {
            padding: var(--spacing-2xl) 0;
        }
        
        .article-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 var(--spacing-md);
        }
        
        .article-content-card {
            background: var(--color-white);
            padding: var(--spacing-2xl);
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: var(--spacing-xl);
        }
        
        .article-info {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-xl);
            padding-bottom: var(--spacing-lg);
            border-bottom: 2px solid var(--color-gray-200);
        }
        
        .article-info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--color-gray-600);
            font-size: 0.875rem;
        }
        
        .article-info-item strong {
            color: var(--color-blue);
            font-weight: 600;
        }
        
        .article-content {
            line-height: 1.8;
            color: var(--color-gray-700);
            font-size: 1.0625rem;
        }
        
        .article-content h2,
        .article-content h3,
        .article-content h4 {
            color: var(--color-blue);
            margin-top: var(--spacing-lg);
            margin-bottom: var(--spacing-sm);
        }
        
        .article-actions {
            display: flex;
            gap: var(--spacing-md);
            margin-top: var(--spacing-xl);
            padding-top: var(--spacing-lg);
            border-top: 2px solid var(--color-gray-200);
        }
        
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.75rem;
            }
            
            .article-content-card {
                padding: var(--spacing-lg);
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'header.php'; ?>
    
    <!-- Mobile Navigation -->
    <nav class="mobile-nav">
        <a href="<?= Router\Router::route('') ?>">Accueil</a>
        <a href="<?= Router\Router::route('archives') ?>">Numéros & Archives</a>
        <a href="<?= Router\Router::route('submit') ?>">Soumettre</a>
        <a href="<?= Router\Router::route('instructions') ?>">Instructions</a>
        <a href="<?= Router\Router::route('comite') ?>">Comité éditorial</a>
        <a href="<?= Router\Router::route('search') ?>">Recherche</a>
        <a href="<?= Router\Router::route('publications') ?>">Publications</a>
        <a href="<?= Router\Router::route('submit') ?>" class="btn btn-primary btn-submit-mobile">Soumettre un article</a>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="fade-up"><?= htmlspecialchars($article['titre'] ?? 'Article') ?></h1>
            <div class="article-meta-header fade-up">
                <?php
                    $authorName = trim(($article['auteur_prenom'] ?? '') . ' ' . ($article['auteur_nom'] ?? ''));
                    $publicationDate = !empty($article['date_publication']) 
                        ? date('d M Y', strtotime($article['date_publication'])) 
                        : (!empty($article['updated_at']) 
                            ? date('d M Y', strtotime($article['updated_at'])) 
                            : date('d M Y', strtotime($article['created_at'] ?? 'now')));
                ?>
                <p>Par <strong><?= htmlspecialchars($authorName ?: 'Auteur inconnu') ?></strong></p>
                <p>Publié le <?= htmlspecialchars($publicationDate) ?></p>
            </div>
        </div>
    </section>

    <!-- Article Details Section -->
    <section class="article-details-section">
        <div class="container">
            <div class="article-container">
                <article class="article-content-card fade-up">
                    <div class="article-info">
                        <?php if (!empty($article['volume_annee'])): ?>
                            <div class="article-info-item">
                                <strong>Volume:</strong>
                                <span><?= htmlspecialchars($article['volume_annee']) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($article['issue_numero'])): ?>
                            <div class="article-info-item">
                                <strong>Numéro:</strong>
                                <span><?= htmlspecialchars($article['issue_numero']) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($article['date_soumission'])): ?>
                            <div class="article-info-item">
                                <strong>Soumis le:</strong>
                                <span><?= date('d M Y', strtotime($article['date_soumission'])) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($article['mots_cles'])): ?>
                            <div class="article-info-item" style="flex-basis: 100%; margin-top: 0.5rem;">
                                <strong>Mots-clés:</strong>
                                <span><?= htmlspecialchars($article['mots_cles']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($article['contenu'])): ?>
                        <div class="article-content">
                            <?= nl2br(htmlspecialchars($article['contenu'])) ?>
                        </div>
                    <?php else: ?>
                        <p style="color: var(--color-gray-600); font-style: italic;">
                            Le contenu de cet article n'est pas disponible en ligne. 
                            Veuillez télécharger le PDF pour lire l'article complet.
                        </p>
                    <?php endif; ?>
                    
                    <div class="article-actions">
                        <?php 
                        $canDownload = Service\AuthService::canDownloadArticle();
                        if (!empty($article['fichier_path'])): 
                            if ($canDownload): ?>
                            <a href="<?= Router\Router::route('download/article', $article['id']) ?>" 
                               class="btn btn-primary">
                                Télécharger le PDF
                            </a>
                        <?php else: ?>
                            <p class="download-restricted" style="color: var(--color-gray-600); font-size: 0.9rem;">
                                <a href="<?= Router\Router::route('login') ?>">Connectez-vous</a> et <a href="<?= Router\Router::route('author') ?>/subscribe">abonnez-vous</a> pour télécharger les articles.
                            </p>
                        <?php endif; endif; ?>
                        <a href="<?= Router\Router::route('publications') ?>" class="btn btn-outline">
                            ← Retour aux publications
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include __DIR__ . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'footer.php'; ?>

    <script src="<?= $baseUrl ?>js/script.js"></script>
</body>
</html>

