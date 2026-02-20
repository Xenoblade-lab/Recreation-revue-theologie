<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revue de la Faculté de Théologie – UPC</title>
    <?php
    $baseUrl = Router\Router::$defaultUri;
    ?>
    <link rel="stylesheet" href="<?= $baseUrl ?>css/styles.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>css/recherche-styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ .  DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'header.php'; ?>

    <!-- Mobile Navigation -->
    <nav class="mobile-nav">
        <a href="<?= Router\Router::route('') ?>">Accueil</a>
        <a href="<?= Router\Router::route('archives') ?>">Numéros & Archives</a>
        <a href="<?= Router\Router::route('submit') ?>">Soumettre</a>
        <a href="<?= Router\Router::route('instructions') ?>">Instructions</a>
        <a href="<?= Router\Router::route('comite') ?>">Comité éditorial</a>
        <a href="<?= Router\Router::route('search') ?>" class="active">Recherche</a>
        <a href="<?= Router\Router::route('submit') ?>" class="btn btn-primary btn-submit-mobile">Soumettre un article</a>
    </nav>
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="fade-up">Recherche avancée</h1>
            <p class="fade-up">Affinez votre recherche par auteur, mot-clé, année et type de publication</p>
        </div>
    </section>

    <!-- Search Section --> 
    <section class="search-section">
        <div class="container">
            <div class="advanced-search-layout">
                <form class="search-panel fade-up" id="advanced-search-form">
                    <div class="form-group">
                        <label for="search-author">Auteur</label>
                        <input type="text" id="search-author" name="author"
                               placeholder="Nom, prénom de l'auteur" 
                               value="<?= htmlspecialchars($searchParams['author'] ?? '') ?>" />
                    </div>

                    <div class="form-group">
                        <label for="search-keyword">Mot-clé</label>
                        <input type="text" id="search-keyword" name="keyword"
                               placeholder="Mot-clé, concept, thème…" 
                               value="<?= htmlspecialchars($searchParams['keyword'] ?? '') ?>" />
                    </div>

                    <div class="form-group form-group-inline">
                        <div>
                            <label for="year-from">Année (de)</label>
                            <input type="number" id="year-from" name="year_from" min="1900" max="2100" placeholder="1997" 
                                   value="<?= htmlspecialchars($searchParams['year_from'] ?? '') ?>" />
                        </div>
                        <div>
                            <label for="year-to">Année (à)</label>
                            <input type="number" id="year-to" name="year_to" min="1900" max="2100" placeholder="2025" 
                                   value="<?= htmlspecialchars($searchParams['year_to'] ?? '') ?>" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Type de publication</label>
                        <div class="filters-chips">
                            <button type="button" class="chip is-selected" data-filter="all">
                                Tous les types
                            </button>
                            <button type="button" class="chip" data-filter="article">
                                Articles
                            </button>
                            <button type="button" class="chip" data-filter="note">
                                Notes de recherche
                            </button>
                            <button type="button" class="chip" data-filter="recension">
                                Recensions
                            </button>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Lancer la recherche
                        </button>
                        <button type="reset" class="btn btn-ghost">
                            Réinitialiser
                        </button>
                    </div>
                </form>

                <div class="results-panel fade-up">
                    <h2>Résultats</h2>
                    <p class="results-summary">
                        <?php if (!empty($results)): ?>
                            <?= count($results) ?> résultat<?= count($results) > 1 ? 's' : '' ?> trouvé<?= count($results) > 1 ? 's' : '' ?>
                        <?php elseif (!empty($searchParams) && (isset($searchParams['author']) || isset($searchParams['keyword']))): ?>
                            <div class="empty-results">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3>Aucun résultat trouvé</h3>
                                <p>Essayez de modifier vos critères de recherche</p>
                            </div>
                        <?php else: ?>
                            Saisissez vos critères puis lancez la recherche pour afficher les résultats.
                        <?php endif; ?>
                    </p>
                    <ul class="results-list" id="search-results">
                        <?php if (!empty($results)): ?>
                            <?php foreach ($results as $article): ?>
                                <?php
                                    $authorName = trim(($article['auteur_prenom'] ?? '') . ' ' . ($article['auteur_nom'] ?? ''));
                                    $excerpt = !empty($article['contenu']) ? strip_tags($article['contenu']) : '';
                                    $excerpt = mb_strimwidth($excerpt, 0, 200, '…');
                                    $year = !empty($article['volume_annee']) ? $article['volume_annee'] : (!empty($article['date_soumission']) ? date('Y', strtotime($article['date_soumission'])) : '');
                                ?>
                                <li class="result-item">
                                    <span class="result-category">Article publié</span>
                                    <h3 class="result-title">
                                        <a href="<?= Router\Router::route('publications') ?>"><?= htmlspecialchars($article['titre'] ?? 'Sans titre') ?></a>
                                    </h3>
                                    <p class="result-authors">
                                        Par <?= htmlspecialchars($authorName ?: 'Auteur inconnu') ?>
                                    </p>
                                    <?php if (!empty($excerpt)): ?>
                                        <p class="result-excerpt">
                                            <?= htmlspecialchars($excerpt) ?>
                                        </p>
                                    <?php endif; ?>
                                    <div class="result-meta">
                                        <?php if (!empty($article['issue_numero'])): ?>
                                            <span><?= htmlspecialchars($article['issue_numero']) ?></span>
                                        <?php endif; ?>
                                        <?php if ($year): ?>
                                            <span><?= htmlspecialchars($year) ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($article['fichier_path']) && Service\AuthService::canDownloadArticle()): ?>
                                            <a href="<?= Router\Router::route('download/article', $article['id']) ?>" class="btn btn-outline btn-sm">PDF</a>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include __DIR__ . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'footer.php'; ?>

    <script src="<?= $baseUrl ?>js/script.js"></script>
    <script src="<?= $baseUrl ?>js/recherche-script.js"></script>
</body>
</html>
   