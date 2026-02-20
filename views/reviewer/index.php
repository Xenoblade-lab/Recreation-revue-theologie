<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Évaluateur - Revue de Théologie UPC</title>
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
                    <h1>Articles à évaluer</h1>
                    <p>Suivez vos affectations et vos délais</p>
                </div>
                <div class="header-actions">
                    <button class="notification-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="notification-badge"></span>
                    </button>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card fade-up">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= $stats['pending'] ?? 0 ?></div>
                    <div class="stat-label">En attente</div>
                </div>

                <div class="stat-card fade-up">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= $stats['in_progress'] ?? 0 ?></div>
                    <div class="stat-label">En cours</div>
                </div>

                <div class="stat-card fade-up">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= $stats['completed'] ?? 0 ?></div>
                    <div class="stat-label">Terminées</div>
                </div>

                <div class="stat-card fade-up">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= $stats['avg_response_days'] ?? 0 ?></div>
                    <div class="stat-label">Jours moyens / éval.</div>
                </div>
            </div>

            <div class="content-card fade-up" id="assigned" style="margin-bottom: var(--spacing-xl);">
                <div class="card-header">
                    <h2>Articles assignés</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Soumis le</th>
                            <th>Délai</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($evaluations)): ?>
                            <?php foreach ($evaluations as $evaluation): ?>
                                <?php
                                $statut = strtolower($evaluation['statut'] ?? '');
                                // Mapping pour les évaluations : en_attente → pending, en_cours → in-review, termine → accepted
                                $badge = 'pending';
                                if ($statut === 'en_cours') $badge = 'in-review';
                                if ($statut === 'termine') $badge = 'accepted';
                                $jours = $evaluation['jours_restants'] ?? null;
                                $delai = isset($jours) ? ($jours >= 0 ? $jours . " j restants" : abs($jours) . " j de retard") : '—';
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($evaluation['article_titre'] ?? 'Titre indisponible') ?></td>
                                    <td><?= !empty($evaluation['article_date']) ? date('d M Y', strtotime($evaluation['article_date'])) : '—' ?></td>
                                    <td><?= $delai ?></td>
                                    <td><span class="status-badge <?= $badge ?>"><?= htmlspecialchars($statut === 'termine' ? 'Terminé' : ($statut === 'en_cours' ? 'En cours' : 'En attente')) ?></span></td>
                                    <td>
                                <div class="action-buttons">
                                            <?php
                                                // Libellé du bouton :
                                                // - "Évaluer" pour une première assignation (en_attente, sans révisions)
                                                // - "Réévaluer" si l'article a déjà fait l'objet de révisions (revision_count > 0)
                                                // - "Continuer" si l'évaluation est en cours
                                                $actionLabel = 'Évaluer';
                                                if ($statut === 'en_cours') {
                                                    $actionLabel = 'Continuer';
                                                } elseif ($statut === 'en_attente') {
                                                    $hasRevisions = isset($evaluation['revision_count']) && (int)$evaluation['revision_count'] > 0;
                                                    $actionLabel = $hasRevisions ? 'Réévaluer' : 'Évaluer';
                                                }
                                            ?>
                                            <button class="btn btn-primary btn-sm" onclick="window.location.href='<?= Router\Router::route("reviewer") ?>/evaluation/<?= $evaluation['id'] ?? '' ?>'">
                                                <?= $actionLabel ?>
                                            </button>
                                            <?php if (!empty($evaluation['article_id'])): ?>
                                                <?php
                                                // Récupérer le chemin du fichier depuis l'article
                                                $articleFile = $evaluation['article_fichier_path'] ?? '';
                                                if (empty($articleFile)) {
                                                    // Essayer de récupérer depuis la base de données si disponible
                                                    $articleFile = '';
                                                }
                                                ?>
                                                <?php if (!empty($articleFile)): ?>
                                                    <a href="<?= Router\Router::$defaultUri . htmlspecialchars($articleFile) ?>" target="_blank" class="action-btn view" title="Télécharger">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center; padding:2rem;">Aucune évaluation assignée pour le moment.</td>
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

