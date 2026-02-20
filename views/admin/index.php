<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Revue de Théologie UPC</title>
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
                    <h1>Tableau de bord</h1>
                    <p>Vue d'ensemble de la revue</p>
                </div>
                <div class="header-actions">
                    <button class="notification-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="notification-badge"></span>
                    </button>
                    <button class="btn btn-primary" onclick="location.href='<?= Router\Router::route('admin') ?>/volumes'">+ Nouveau numéro</button>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid" id="stats">
                <div class="stat-card fade-up">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= $stats['articles_total'] ?? 0 ?></div>
                    <div class="stat-label">Articles soumis</div>
                </div>

                <div class="stat-card fade-up">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= $stats['articles_publies'] ?? 0 ?></div>
                    <div class="stat-label">Articles publiés</div>
                </div>

                <div class="stat-card fade-up">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= $stats['evaluateurs_actifs'] ?? 0 ?></div>
                    <div class="stat-label">Utilisateurs / Évaluateurs</div>
                </div>

                <div class="stat-card fade-up">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= number_format($stats['revenus_mois'] ?? 0, 2, ',', ' ') ?> $</div>
                    <div class="stat-label">Revenus ce mois</div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid" id="articles">
                <div class="content-card fade-up">
                    <div class="card-header">
                        <h2>Soumissions récentes</h2>
                        <a href="<?= Router\Router::route('admin') ?>/articles" class="view-all">Voir tout →</a>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Auteur</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentSubmissions)): ?>
                                <?php foreach ($recentSubmissions as $sub): ?>
                                    <?php
                                        $statut = strtolower($sub['statut'] ?? '');
                                        // Mapping selon les spécifications
                                        $badge = 'pending';
                                        if (strpos($statut, 'publ') !== false) {
                                            $badge = 'published';
                                        } elseif (strpos($statut, 'accept') !== false || strpos($statut, 'valide') !== false) {
                                            $badge = 'accepted';
                                        } elseif (strpos($statut, 'rej') !== false) {
                                            $badge = 'rejected';
                                        } elseif (strpos($statut, 'evaluation') !== false || strpos($statut, 'évaluation') !== false || strpos($statut, 'revision') !== false) {
                                            $badge = 'in-review';
                                        }
                                        
                                        $statutDisplay = ucfirst($sub['statut'] ?? 'Soumis');
                                        if ($statut === 'valide' || strpos($statut, 'accept') !== false) $statutDisplay = 'Accepté';
                                        elseif ($statut === 'rejete') $statutDisplay = 'Rejeté';
                                        elseif ($statut === 'soumis') $statutDisplay = 'Soumis';
                                        elseif (strpos($statut, 'evaluation') !== false || strpos($statut, 'évaluation') !== false) $statutDisplay = 'En évaluation';
                                        elseif (strpos($statut, 'revision') !== false) $statutDisplay = 'Révisions requises';
                                        elseif (strpos($statut, 'publ') !== false) $statutDisplay = 'Publié';
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($sub['titre']) ?></td>
                                        <td><?= htmlspecialchars(trim(($sub['prenom'] ?? '') . ' ' . ($sub['nom'] ?? ''))) ?></td>
                                        <td><?= !empty($sub['date_soumission']) ? date('d M Y', strtotime($sub['date_soumission'])) : '—' ?></td>
                                        <td><span class="status-badge <?= $badge ?>"><?= htmlspecialchars($statutDisplay) ?></span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn view" title="Voir les détails" onclick="window.location.href='<?= Router\Router::route('admin') ?>/article/<?= $sub['id'] ?? '' ?>'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                                <button class="action-btn edit" title="Modifier le statut" onclick="window.location.href='<?= Router\Router::route('admin') ?>/articles'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align:center; padding:1.5rem;">Aucune soumission récente.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="content-card fade-up">
                    <div class="card-header">
                        <h2>Activité récente</h2>
                    </div>
                    <div style="padding: var(--spacing-md); color: var(--color-gray-600);">
                        <?php if (!empty($recentSubmissions)): ?>
                            <div class="activity-feed">
                                <?php foreach (array_slice($recentSubmissions, 0, 3) as $sub): ?>
                                    <div class="activity-item">
                                        <div class="activity-icon submission">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="activity-content">
                                            <h4>Nouvelle soumission</h4>
                                            <p><?= htmlspecialchars(trim(($sub['prenom'] ?? '') . ' ' . ($sub['nom'] ?? ''))) ?> a soumis "<?= htmlspecialchars($sub['titre']) ?>"</p>
                                            <div class="activity-time">
                                                <?php
                                                if (!empty($sub['date_soumission'])) {
                                                    $date = new DateTime($sub['date_soumission']);
                                                    $now = new DateTime();
                                                    $diff = $now->diff($date);
                                                    
                                                    if ($diff->days > 0) {
                                                        echo 'Il y a ' . $diff->days . ' jour' . ($diff->days > 1 ? 's' : '');
                                                    } elseif ($diff->h > 0) {
                                                        echo 'Il y a ' . $diff->h . ' heure' . ($diff->h > 1 ? 's' : '');
                                                    } elseif ($diff->i > 0) {
                                                        echo 'Il y a ' . $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
                                                    } else {
                                                        echo 'À l\'instant';
                                                    }
                                                } else {
                                                    echo 'Date inconnue';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>Aucune activité récente.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <button class="mobile-menu-btn" id="mobile-menu-btn">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <style>
        /* Version plus compacte du dashboard admin */
        .dashboard-main {
            padding: 1.25rem;
        }

        .dashboard-header {
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .dashboard-header .header-title h1 {
            font-size: 1.5rem;
            line-height: 1.2;
            margin-bottom: 0.25rem;
        }

        .dashboard-header .header-title p {
            font-size: 0.95rem;
        }

        .dashboard-header .header-actions {
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .notification-btn {
            width: 40px;
            height: 40px;
        }

        /* Stats : cartes plus petites et grille plus dense */
        .stats-grid {
            margin-bottom: 1.25rem;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
        }

        .stat-value {
            font-size: 1.5rem;
            margin-top: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
        }

        /* Contenu : cartes plus compactes */
        .content-grid {
            gap: 1rem;
        }

        .content-card {
            padding: 0;
        }

        .content-card .card-header {
            padding: 0.9rem 1rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .content-card .card-header h2 {
            font-size: 1.1rem;
        }

        .data-table th,
        .data-table td {
            padding: 0.6rem 0.75rem;
            font-size: 0.92rem;
        }

        .action-buttons {
            gap: 0.4rem;
        }

        .action-btn {
            width: 34px;
            height: 34px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-main {
                padding: 1rem;
            }

            .dashboard-header .header-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
</body>
</html>
