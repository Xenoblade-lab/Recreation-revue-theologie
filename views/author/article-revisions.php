<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des révisions - <?= htmlspecialchars($article['titre']) ?></title>
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
                    <a href="<?= Router\Router::route("author") ?>/article/<?= $article['id'] ?>" class="back-link" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-blue); text-decoration: none; margin-bottom: 0.5rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        Retour aux détails
                    </a>
                    <h1>Historique des révisions</h1>
                    <p><?= htmlspecialchars($article['titre']) ?></p>
                </div>
            </div>

            <div class="content-card fade-up">
                <div class="card-header">
                    <h2>Révisions (<?= $revisionCount ?>)</h2>
                </div>

                <?php if (!empty($revisions)): ?>
                    <div class="revisions-timeline">
                        <?php foreach ($revisions as $index => $revision): ?>
                            <div class="revision-item">
                                <div class="revision-number">
                                    <span>Révision #<?= $revision['revision_number'] ?></span>
                                </div>
                                <div class="revision-content">
                                    <div class="revision-header">
                                        <div>
                                            <h3>Changement de statut</h3>
                                            <div class="status-change">
                                                <span class="status-badge <?= 
                                                    strpos(strtolower($revision['previous_status']), 'publ') !== false ? 'published' : 
                                                    (strpos(strtolower($revision['previous_status']), 'accept') !== false ? 'accepted' : 
                                                    (strpos(strtolower($revision['previous_status']), 'rej') !== false ? 'rejected' : 
                                                    (strpos(strtolower($revision['previous_status']), 'evaluation') !== false ? 'in-review' : 'pending')))
                                                ?>">
                                                    <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $revision['previous_status'] ?? 'N/A'))) ?>
                                                </span>
                                                <span>→</span>
                                                <span class="status-badge <?= 
                                                    strpos(strtolower($revision['new_status']), 'publ') !== false ? 'published' : 
                                                    (strpos(strtolower($revision['new_status']), 'accept') !== false ? 'accepted' : 
                                                    (strpos(strtolower($revision['new_status']), 'rej') !== false ? 'rejected' : 
                                                    (strpos(strtolower($revision['new_status']), 'evaluation') !== false ? 'in-review' : 'pending')))
                                                ?>">
                                                    <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $revision['new_status']))) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <small><?= date('d M Y à H:i', strtotime($revision['submitted_at'])) ?></small>
                                    </div>
                                    <?php if (!empty($revision['revision_reason'])): ?>
                                        <div class="revision-reason">
                                            <strong>Raison :</strong>
                                            <p><?= nl2br(htmlspecialchars($revision['revision_reason'])) ?></p>
                                        </div>
                                    <?php endif; ?>
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
                        </svg>
                        <h3>Aucune révision</h3>
                        <p>Cet article n'a pas encore été révisé.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <style>
        .revisions-timeline {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .revision-item {
            display: flex;
            gap: 1rem;
            padding: 1.5rem;
            border-left: 3px solid var(--color-blue);
            background: var(--color-gray-50);
            border-radius: 8px;
        }
        .revision-number {
            flex-shrink: 0;
            width: 80px;
            text-align: center;
        }
        .revision-number span {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: var(--color-blue);
            color: white;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .revision-content {
            flex: 1;
        }
        .revision-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        .revision-header h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1rem;
        }
        .status-change {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .revision-reason {
            margin-top: 1rem;
            padding: 1rem;
            background: var(--color-white);
            border-radius: 6px;
            border: 1px solid var(--color-gray-200);
        }
        .revision-reason strong {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--color-gray-900);
        }
        .revision-reason p {
            margin: 0;
            color: var(--color-gray-600);
            line-height: 1.6;
        }
    </style>
</body>
</html>

