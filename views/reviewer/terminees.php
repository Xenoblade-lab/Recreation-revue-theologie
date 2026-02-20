<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluations terminées - Reviewer</title>
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/styles.css">
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/dashboard-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <?php include __DIR__ . DIRECTORY_SEPARATOR . '_sidebar.php'; ?>

        <main class="dashboard-main">
            <div class="dashboard-header fade-up">
                <div class="header-title">
                    <h1>Évaluations terminées</h1>
                    <p>Historique des articles que vous avez évalués</p>
                </div>
            </div>

            <div class="content-card fade-up" style="margin-bottom: var(--spacing-xl);">
                <div class="card-header">
                    <h2>Articles évalués</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Soumis le</th>
                            <th>Votre recommandation</th>
                            <th>Statut de l'article</th>
                            <th>Date de soumission</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($evaluations)): ?>
                            <?php foreach ($evaluations as $evaluation): ?>
                                <?php
                                // Mapping de la recommandation
                                $recommendation = strtolower($evaluation['recommendation'] ?? '');
                                $recLabel = 'N/A';
                                $recBadge = 'pending';
                                
                                if ($recommendation === 'accepte') {
                                    $recLabel = 'Accepté';
                                    $recBadge = 'accepted';
                                } elseif ($recommendation === 'accepte_avec_modifications') {
                                    $recLabel = 'Accepté avec modifications';
                                    $recBadge = 'accepted';
                                } elseif ($recommendation === 'revision_majeure') {
                                    $recLabel = 'Révisions majeures requises';
                                    $recBadge = 'rejected';
                                } elseif ($recommendation === 'rejete') {
                                    $recLabel = 'Rejeté';
                                    $recBadge = 'rejected';
                                }
                                
                                // Mapping du statut de l'article
                                $articleStatut = strtolower($evaluation['article_statut'] ?? '');
                                $articleLabel = ucfirst(str_replace('_', ' ', $articleStatut));
                                $articleBadge = 'pending';
                                
                                if (strpos($articleStatut, 'publ') !== false) {
                                    $articleBadge = 'published';
                                } elseif (strpos($articleStatut, 'accept') !== false || strpos($articleStatut, 'valide') !== false) {
                                    $articleBadge = 'accepted';
                                } elseif (strpos($articleStatut, 'rej') !== false) {
                                    $articleBadge = 'rejected';
                                } elseif (strpos($articleStatut, 'evaluation') !== false || strpos($articleStatut, 'évaluation') !== false) {
                                    $articleBadge = 'in-review';
                                } elseif (strpos($articleStatut, 'revision') !== false) {
                                    $articleBadge = 'in-review';
                                }
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($evaluation['article_titre'] ?? 'Titre indisponible') ?></td>
                                    <td><?= !empty($evaluation['article_date']) ? date('d M Y', strtotime($evaluation['article_date'])) : '—' ?></td>
                                    <td><span class="status-badge <?= $recBadge ?>"><?= htmlspecialchars($recLabel) ?></span></td>
                                    <td><span class="status-badge <?= $articleBadge ?>"><?= htmlspecialchars($articleLabel) ?></span></td>
                                    <td><?= !empty($evaluation['date_soumission']) ? date('d M Y H:i', strtotime($evaluation['date_soumission'])) : '—' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center; padding:2rem;">Aucune évaluation terminée pour le moment.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <button class="mobile-menu-btn" id="mobile-menu-btn">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
</body>
</html>

