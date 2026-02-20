<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles publiés - Revue de Théologie UPC</title>
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
            font-size: 3rem;
            margin-bottom: var(--spacing-sm);
        }
        
        .page-header p {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .archives-section {
            padding: var(--spacing-2xl) 0;
        }
        
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
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
        <a href="<?= Router\Router::route('publications') ?>" class="active">Publications</a>
        <a href="<?= Router\Router::route('submit') ?>" class="btn btn-primary btn-submit-mobile">Soumettre un article</a>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="fade-up">Articles publiés</h1>
            <p class="fade-up">Découvrez les derniers articles publiés dans la revue.</p>
        </div>
    </section>

    <!-- Publications Section -->
    <section class="archives-section">
        <div class="container">
            <?php if (!empty($articles)): ?>
                <div class="section-header fade-up" style="margin-bottom: var(--spacing-xl);">
                    <h2 style="font-size: 2rem; color: var(--color-blue); margin-bottom: var(--spacing-sm);">
                        Tous les articles publiés
                    </h2>
                    <p style="color: var(--color-gray-600); font-size: 1.125rem;">
                        <?= count($articles) ?> article<?= count($articles) > 1 ? 's' : '' ?> disponible<?= count($articles) > 1 ? 's' : '' ?>
                    </p>
                </div>
                
                <div class="articles-grid fade-up">
                    <?php foreach ($articles as $article): ?>
                        <?php
                            $authorName = trim(($article['auteur_prenom'] ?? '') . ' ' . ($article['auteur_nom'] ?? ''));
                            $excerpt = !empty($article['contenu']) ? strip_tags($article['contenu']) : '';
                            $excerpt = mb_strimwidth($excerpt, 0, 200, '…');
                            $publicationDate = !empty($article['date_publication']) 
                                ? date('d M Y', strtotime($article['date_publication'])) 
                                : (!empty($article['updated_at']) 
                                    ? date('d M Y', strtotime($article['updated_at'])) 
                                    : date('d M Y', strtotime($article['created_at'] ?? 'now')));
                        ?>
                        <article class="article-card fade-up">
                            <span class="article-category">Article publié</span>
                            <h4><?= htmlspecialchars($article['titre'] ?? 'Sans titre') ?></h4>
                            <p class="article-authors">Par <?= htmlspecialchars($authorName ?: 'Auteur inconnu') ?></p>
                            <?php if (!empty($excerpt)): ?>
                                <p class="article-excerpt"><?= htmlspecialchars($excerpt) ?></p>
                            <?php endif; ?>
                            <div class="article-meta">
                                <span><?= htmlspecialchars($publicationDate) ?></span>
                                <?php if (!empty($article['issue_numero'])): ?>
                                    <span>·</span>
                                    <span><?= htmlspecialchars($article['issue_numero']) ?></span>
                                <?php endif; ?>
                            </div>
                            <div style="display: flex; gap: 1rem; align-items: center; margin-top: var(--spacing-sm); flex-wrap: wrap;">
                                <?php 
                                $canDownload = Service\AuthService::canDownloadArticle();
                                if (!empty($article['fichier_path']) && $canDownload): ?>
                                    <a href="<?= Router\Router::route('download/article', $article['id']) ?>" 
                                       class="btn btn-outline btn-sm" 
                                       style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                        Télécharger PDF
                                    </a>
                                <?php endif; ?>
                                <a href="<?= Router\Router::$defaultUri ?>article/<?= $article['id'] ?>" class="article-link">
                                    Voir les détails →
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="fade-up" style="text-align: center; padding: var(--spacing-2xl) 0;">
                    <div style="max-width: 500px; margin: 0 auto;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" 
                             style="width: 64px; height: 64px; color: var(--color-gray-300); margin: 0 auto var(--spacing-md);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 style="color: var(--color-gray-700); margin-bottom: var(--spacing-sm);">Aucun article publié</h3>
                        <p style="color: var(--color-gray-600);">Il n'y a pas encore d'articles publiés dans la revue.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <?php include __DIR__ . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'footer.php'; ?>

    <script src="<?= $baseUrl ?>js/script.js"></script>
</body>
</html>


