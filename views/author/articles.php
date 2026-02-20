<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles Publiés - Dashboard Auteur</title>
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/styles.css">
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/dashboard-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <?php include __DIR__ . DIRECTORY_SEPARATOR . '_sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="dashboard-main">
            <!-- Header -->
            <div class="dashboard-header fade-up">
                <div class="header-title">
                    <h1>Mes articles publiés</h1>
                    <p>Consultez vos articles qui ont été publiés dans la revue</p>
                </div>
            </div>

            <!-- Articles Published -->
            <div class="content-card fade-up">
                <div class="card-header">
                    <h2>Articles publiés (<?= isset($publishedArticles) ? count($publishedArticles) : 0 ?>)</h2>
                </div>
                
                <?php if (isset($publishedArticles) && !empty($publishedArticles)): ?>
                    <div class="articles-grid">
                        <?php foreach ($publishedArticles as $article): ?>
                            <div class="article-card">
                                <div class="article-header">
                                    <h3><?= htmlspecialchars($article['titre']) ?></h3>
                                    <span class="status-badge published">Publié</span>
                                </div>
                                <div class="article-meta">
                                    <div class="meta-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        <span>Publié le <?= date('d M Y', strtotime($article['date_publication'] ?? $article['updated_at'] ?? $article['created_at'])) ?></span>
                                    </div>
                                    <?php if (!empty($article['doi'])): ?>
                                        <div class="meta-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                            </svg>
                                            <span>DOI: <?= htmlspecialchars($article['doi']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($article['contenu'])): ?>
                                    <p class="article-excerpt"><?= htmlspecialchars(substr($article['contenu'], 0, 150)) ?>...</p>
                                <?php endif; ?>
                                <div class="article-actions">
                                    <?php if (!empty($article['fichier_path'])): ?>
                                        <a href="<?= Router\Router::$defaultUri . htmlspecialchars($article['fichier_path']) ?>" class="btn btn-outline" target="_blank">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                <line x1="12" y1="15" x2="12" y2="3"></line>
                                            </svg>
                                            Télécharger PDF
                                        </a>
                                    <?php endif; ?>
                                    <!-- Rediriger vers la page de détails auteur au lieu de l'API JSON -->
                                    <a href="<?= Router\Router::route('author') ?>/article/<?= $article['id'] ?>" class="btn btn-primary">
                                        Voir l'article
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        <h3>Aucun article publié</h3>
                        <p>Vous n'avez pas encore d'articles publiés dans la revue.</p>
                        <a href="<?= Router\Router::route("author") ?>#submit-form" class="btn btn-primary">Soumettre un article</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
</body>
</html>

