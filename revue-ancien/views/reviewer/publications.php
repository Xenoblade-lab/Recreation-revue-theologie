<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publications - Dashboard Évaluateur</title>
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
                    <h1>Publications</h1>
                    <p>Articles publiés dans la revue</p>
                </div>
            </div>

            <div class="content-card fade-up">
                <div class="card-header">
                    <h2>Articles publiés</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Date de publication</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($publications)): ?>
                            <?php foreach ($publications as $pub): ?>
                                <?php
                                $statut = strtolower($pub['statut'] ?? '');
                                $badge = 'published';
                                if (strpos($statut, 'publ') === false) {
                                    $badge = 'accepted';
                                }
                                $datePub = $pub['date_publication'] ?? $pub['updated_at'] ?? $pub['created_at'];
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($pub['id']) ?></td>
                                    <td><?= htmlspecialchars($pub['titre']) ?></td>
                                    <td><?= htmlspecialchars(trim(($pub['auteur_prenom'] ?? '') . ' ' . ($pub['auteur_nom'] ?? ''))) ?></td>
                                    <td><?= !empty($datePub) ? date('d M Y', strtotime($datePub)) : '—' ?></td>
                                    <td><span class="status-badge <?= $badge ?>"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $statut))) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center; padding:2rem; color: var(--color-gray-500);">
                                    Aucun article publié pour le moment.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
</body>
</html>


