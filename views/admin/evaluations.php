<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluations - Admin</title>
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
                    <h1>Évaluations</h1>
                    <p>Gestion de toutes les évaluations</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-outline" onclick="window.location.reload()">Actualiser</button>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="stats-grid" style="margin-bottom: var(--spacing-xl);">
                <div class="stat-card fade-up">
                    <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#2563eb" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="stat-value"><?= $stats['total'] ?? 0 ?></div>
                    <div class="stat-label">Total évaluations</div>
                </div>
                <div class="stat-card fade-up">
                    <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#2563eb" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-value"><?= $stats['en_cours'] ?? 0 ?></div>
                    <div class="stat-label">En cours</div>
                </div>
                <div class="stat-card fade-up">
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#059669" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-value"><?= $stats['terminees'] ?? 0 ?></div>
                    <div class="stat-label">Terminées</div>
                </div>
                <div class="stat-card fade-up">
                    <div class="stat-icon" style="background: rgba(251, 191, 36, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#d97706" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-value"><?= $stats['en_attente'] ?? 0 ?></div>
                    <div class="stat-label">En attente</div>
                </div>
            </div>

            <div class="content-card fade-up">
                <div class="card-header">
                    <h2>Toutes les évaluations</h2>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <select id="filter-status" style="padding: 0.5rem; border-radius: 4px; border: 1px solid var(--color-gray-300);">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente">En attente</option>
                            <option value="en_cours">En cours</option>
                            <option value="termine">Terminé</option>
                            <option value="annule">Annulé</option>
                        </select>
                        <select id="filter-recommendation" style="padding: 0.5rem; border-radius: 4px; border: 1px solid var(--color-gray-300);">
                            <option value="">Toutes les recommandations</option>
                            <option value="accepte">Accepté</option>
                            <option value="accepte_avec_modifications">Accepté avec modifications</option>
                            <option value="revision_majeure">Révisions majeures</option>
                            <option value="rejete">Rejeté</option>
                        </select>
                    </div>
                </div>
                <div style="overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch;">
                    <table class="data-table" style="min-width: 1200px; width: 100%;">
                        <thead>
                            <tr>
                                <th style="min-width: 50px; width: 60px;">ID</th>
                                <th style="min-width: 200px;">Article</th>
                                <th style="min-width: 150px;">Évaluateur</th>
                                <th style="min-width: 140px;">Date d'assignation</th>
                                <th style="min-width: 180px;">Date d'échéance</th>
                                <th style="min-width: 100px;">Statut</th>
                                <th style="min-width: 150px;">Recommandation</th>
                                <th style="min-width: 100px;">Note finale</th>
                                <th style="min-width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($evaluations) && !empty($evaluations)): ?>
                            <?php foreach ($evaluations as $eval): ?>
                                <?php
                                $statut = strtolower($eval['statut'] ?? '');
                                $statutClass = 'pending';
                                if ($statut === 'termine') $statutClass = 'accepted';
                                if ($statut === 'en_cours') $statutClass = 'in-review';
                                if ($statut === 'annule') $statutClass = 'rejected';
                                
                                $recommendation = strtolower($eval['recommendation'] ?? '');
                                $recommendationClass = 'pending';
                                $recommendationLabel = 'N/A';
                                switch ($recommendation) {
                                    case 'accepte':
                                        $recommendationLabel = 'Accepté';
                                        $recommendationClass = 'accepted';
                                        break;
                                    case 'accepte_avec_modifications':
                                        $recommendationLabel = 'Accepté avec modif.';
                                        $recommendationClass = 'in-review';
                                        break;
                                    case 'revision_majeure':
                                        $recommendationLabel = 'Révisions majeures';
                                        $recommendationClass = 'pending';
                                        break;
                                    case 'rejete':
                                        $recommendationLabel = 'Rejeté';
                                        $recommendationClass = 'rejected';
                                        break;
                                }
                                
                                $joursRestants = isset($eval['jours_restants']) ? (int)$eval['jours_restants'] : null;
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($eval['id']) ?></td>
                                    <td>
                                        <a href="<?= Router\Router::route("admin") ?>/article/<?= $eval['article_id'] ?>" style="color: var(--color-blue); text-decoration: none;">
                                            <?= htmlspecialchars($eval['article_titre'] ?? 'N/A') ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars(($eval['evaluateur_prenom'] ?? '') . ' ' . ($eval['evaluateur_nom'] ?? '')) ?></td>
                                    <td><?= !empty($eval['date_assignation']) ? date('d M Y', strtotime($eval['date_assignation'])) : '—' ?></td>
                                    <td style="white-space: normal;">
                                        <?php if (!empty($eval['date_echeance'])): ?>
                                            <div style="line-height: 1.4;">
                                                <div><?= date('d M Y', strtotime($eval['date_echeance'])) ?></div>
                                                <?php if ($joursRestants !== null): ?>
                                                    <?php if ($joursRestants < 0): ?>
                                                        <span style="color: var(--color-red); font-size: 0.75rem; display: block; margin-top: 0.25rem;">(<?= abs($joursRestants) ?> jours de retard)</span>
                                                    <?php elseif ($joursRestants <= 3): ?>
                                                        <span style="color: #d97706; font-size: 0.75rem; display: block; margin-top: 0.25rem;">(<?= $joursRestants ?> jours restants)</span>
                                                    <?php else: ?>
                                                        <span style="color: var(--color-gray-500); font-size: 0.75rem; display: block; margin-top: 0.25rem;">(<?= $joursRestants ?> jours restants)</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            —
                                        <?php endif; ?>
                                    </td>
                                    <td><span class="status-badge <?= $statutClass ?>"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $statut))) ?></span></td>
                                    <td><span class="status-badge <?= $recommendationClass ?>"><?= htmlspecialchars($recommendationLabel) ?></span></td>
                                    <td>
                                        <?php if (!empty($eval['note_finale'])): ?>
                                            <strong><?= $eval['note_finale'] ?>/10</strong>
                                        <?php else: ?>
                                            —
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <a href="<?= Router\Router::route("admin") ?>/evaluation/<?= $eval['id'] ?>" class="btn btn-outline btn-sm">Voir</a>
                                            <a href="<?= Router\Router::route("admin") ?>/article/<?= $eval['article_id'] ?>" class="btn btn-outline btn-sm">Article</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 2rem; color: var(--color-gray-500);">
                                    Aucune évaluation trouvée
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <style>
        /* Empêcher tout débordement horizontal global sur la page */
        .dashboard-main {
            overflow-x: hidden;
        }

        /* Header (titre + actions) : autoriser le retour à la ligne */
        .dashboard-header {
            flex-wrap: wrap;
            gap: 1rem;
        }

        .dashboard-header .header-title {
            min-width: 260px;
            flex: 1 1 320px;
        }

        .dashboard-header .header-actions {
            flex: 0 1 auto;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        /* Bloc stats : grille responsive qui wrap automatiquement */
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        /* Card header (Titre + filtres) : wrap + alignement */
        .content-card .card-header {
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-start;
        }

        /* Conteneur des filtres (le <div style="display:flex...">) : wrap propre */
        .content-card .card-header > div {
            flex-wrap: wrap;
            justify-content: flex-end;
            width: 100%;
        }

        .content-card .card-header select {
            min-width: 220px;
            max-width: 100%;
        }

        /* Styles pour le tableau des évaluations */
        .content-card {
            overflow: visible;
        }
        
        .data-table {
            border-collapse: collapse;
            width: 100%;
        }
        
        .data-table th,
        .data-table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid var(--color-gray-200);
            white-space: nowrap;
        }
        
        .data-table th {
            background: var(--color-gray-50);
            font-weight: 600;
            color: var(--color-gray-700);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .data-table tbody tr:hover {
            background: var(--color-gray-50);
        }
        
        .data-table td {
            white-space: normal;
            word-wrap: break-word;
        }
        
        /* Colonne Article peut avoir du texte long */
        .data-table td:nth-child(2) {
            white-space: normal;
            max-width: 250px;
            word-wrap: break-word;
        }
        
        /* Colonne Date d'échéance avec jours restants */
        .data-table td:nth-child(5) {
            white-space: normal;
        }
        
        /* Colonne Actions */
        .data-table td:last-child {
            white-space: nowrap;
        }
        
        /* Responsive pour petits écrans */
        @media (max-width: 1400px) {
            .data-table {
                font-size: 0.875rem;
            }
            
            .data-table th,
            .data-table td {
                padding: 0.5rem 0.75rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-header .header-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .content-card .card-header > div {
                justify-content: flex-start;
            }

            .content-card .card-header select {
                min-width: 180px;
                flex: 1 1 180px;
            }
        }
    </style>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
    <script>
        // Filtrage côté client (peut être amélioré avec un filtrage serveur)
        document.getElementById('filter-status')?.addEventListener('change', function() {
            filterTable();
        });
        
        document.getElementById('filter-recommendation')?.addEventListener('change', function() {
            filterTable();
        });
        
        function filterTable() {
            const statusFilter = document.getElementById('filter-status')?.value.toLowerCase() || '';
            const recommendationFilter = document.getElementById('filter-recommendation')?.value.toLowerCase() || '';
            const rows = document.querySelectorAll('.data-table tbody tr');
            
            rows.forEach(row => {
                if (row.cells.length < 9) return; // Skip empty rows
                
                const statusCell = row.cells[5];
                const recommendationCell = row.cells[6];
                
                const statusText = statusCell.textContent.toLowerCase();
                const recommendationText = recommendationCell.textContent.toLowerCase();
                
                const statusMatch = !statusFilter || statusText.includes(statusFilter);
                const recommendationMatch = !recommendationFilter || recommendationText.includes(recommendationFilter.replace('_', ' '));
                
                row.style.display = (statusMatch && recommendationMatch) ? '' : 'none';
            });
        }
    </script>
</body>
</html>

