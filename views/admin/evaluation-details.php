<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'évaluation - Admin</title>
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
                    <a href="<?= Router\Router::route("admin") ?>/evaluations" class="back-link" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-blue); text-decoration: none; margin-bottom: 0.5rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        Retour aux évaluations
                    </a>
                    <h1>Détails de l'évaluation</h1>
                    <p>Informations complètes sur l'évaluation</p>
                </div>
            </div>

            <?php if (isset($evaluation) && $evaluation): ?>
                <!-- Informations de l'article -->
                <div class="content-card fade-up">
                    <div class="card-header">
                        <h2><?= htmlspecialchars($evaluation['article_titre'] ?? 'Titre indisponible') ?></h2>
                        <span class="status-badge <?= 
                            strtolower($evaluation['statut'] ?? '') === 'termine' ? 'accepted' : 
                            (strtolower($evaluation['statut'] ?? '') === 'en_cours' ? 'in-review' : 'pending')
                        ?>">
                            <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $evaluation['statut'] ?? 'en_attente'))) ?>
                        </span>
                    </div>

                    <div class="article-details-content">
                        <div class="detail-section">
                            <h3>Informations de l'évaluation</h3>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <label>Évaluateur</label>
                                    <p><?= htmlspecialchars(trim(($evaluation['evaluateur_prenom'] ?? '') . ' ' . ($evaluation['evaluateur_nom'] ?? ''))) ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Email évaluateur</label>
                                    <p><?= htmlspecialchars($evaluation['evaluateur_email'] ?? '—') ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Date d'assignation</label>
                                    <p><?= !empty($evaluation['date_assignation']) ? date('d M Y à H:i', strtotime($evaluation['date_assignation'])) : '—' ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Date d'échéance</label>
                                    <p><?= !empty($evaluation['date_echeance']) ? date('d M Y', strtotime($evaluation['date_echeance'])) : '—' ?></p>
                                </div>
                                <?php if (isset($evaluation['jours_restants'])): ?>
                                <div class="detail-item">
                                    <label>Jours restants</label>
                                    <p><?= $evaluation['jours_restants'] >= 0 ? $evaluation['jours_restants'] . ' jours' : abs($evaluation['jours_restants']) . ' jours de retard' ?></p>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($evaluation['date_soumission'])): ?>
                                <div class="detail-item">
                                    <label>Date de soumission</label>
                                    <p><?= date('d M Y à H:i', strtotime($evaluation['date_soumission'])) ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="detail-section">
                            <h3>Informations de l'article</h3>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <label>Auteur</label>
                                    <p><?= htmlspecialchars(trim(($evaluation['auteur_prenom'] ?? '') . ' ' . ($evaluation['auteur_nom'] ?? ''))) ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Email auteur</label>
                                    <p><?= htmlspecialchars($evaluation['auteur_email'] ?? '—') ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Date de soumission de l'article</label>
                                    <p><?= !empty($evaluation['article_date']) ? date('d M Y', strtotime($evaluation['article_date'])) : '—' ?></p>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($evaluation['article_contenu'])): ?>
                            <div class="detail-section">
                                <h3>Résumé de l'article</h3>
                                <p class="article-content"><?= nl2br(htmlspecialchars($evaluation['article_contenu'] ?? '')) ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($evaluation['article_fichier_path'])): ?>
                            <div class="detail-section">
                                <h3>Fichier de l'article</h3>
                                <a href="<?= Router\Router::$defaultUri . htmlspecialchars($evaluation['article_fichier_path']) ?>" target="_blank" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    Télécharger le fichier
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Détails de l'évaluation (si terminée) -->
                <?php if (strtolower($evaluation['statut'] ?? '') === 'termine'): ?>
                    <div class="content-card fade-up">
                        <div class="card-header">
                            <h2>Résultats de l'évaluation</h2>
                        </div>

                        <div class="article-details-content">
                            <!-- Notes -->
                            <div class="detail-section">
                                <h3>Notes sur 10</h3>
                                <div class="detail-grid">
                                    <div class="detail-item">
                                        <label>Qualité scientifique</label>
                                        <p><?= !empty($evaluation['qualite_scientifique']) ? number_format($evaluation['qualite_scientifique'], 1) . ' / 10' : '—' ?></p>
                                    </div>
                                    <div class="detail-item">
                                        <label>Originalité</label>
                                        <p><?= !empty($evaluation['originalite']) ? number_format($evaluation['originalite'], 1) . ' / 10' : '—' ?></p>
                                    </div>
                                    <div class="detail-item">
                                        <label>Pertinence</label>
                                        <p><?= !empty($evaluation['pertinence']) ? number_format($evaluation['pertinence'], 1) . ' / 10' : '—' ?></p>
                                    </div>
                                    <div class="detail-item">
                                        <label>Clarté</label>
                                        <p><?= !empty($evaluation['clarte']) ? number_format($evaluation['clarte'], 1) . ' / 10' : '—' ?></p>
                                    </div>
                                    <div class="detail-item">
                                        <label>Note finale</label>
                                        <p style="font-weight: 600; font-size: 1.125rem; color: var(--color-blue);">
                                            <?= !empty($evaluation['note_finale']) ? number_format($evaluation['note_finale'], 1) . ' / 10' : '—' ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Recommandation -->
                            <?php if (!empty($evaluation['recommendation'])): ?>
                                <div class="detail-section">
                                    <h3>Recommandation finale</h3>
                                    <div class="detail-grid" style="grid-template-columns: 1fr;">
                                        <div class="detail-item">
                                            <label>Recommandation</label>
                                            <p style="font-weight: 600; max-width: 100%; word-wrap: break-word;">
                                                <?php
                                                $recommendations = [
                                                    'accepte' => 'Accepter',
                                                    'accepte_avec_modifications' => 'Accepter avec modifications mineures',
                                                    'revision_majeure' => 'Révision majeure requise',
                                                    'rejete' => 'Rejeter'
                                                ];
                                                echo htmlspecialchars($recommendations[$evaluation['recommendation']] ?? ucfirst(str_replace('_', ' ', $evaluation['recommendation'])));
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Commentaires publics -->
                            <?php if (!empty($evaluation['commentaires_public'])): ?>
                                <div class="detail-section">
                                    <h3>Commentaires publics (visibles par l'auteur)</h3>
                                    <div class="article-content" style="background: rgba(59, 130, 246, 0.05); padding: 1rem; border-radius: 6px; border-left: 3px solid var(--color-blue);">
                                        <?= nl2br(htmlspecialchars($evaluation['commentaires_public'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Commentaires privés -->
                            <?php if (!empty($evaluation['commentaires_prives'])): ?>
                                <div class="detail-section">
                                    <h3>Commentaires privés (pour le comité éditorial)</h3>
                                    <div class="article-content" style="background: rgba(239, 68, 68, 0.05); padding: 1rem; border-radius: 6px; border-left: 3px solid var(--color-red);">
                                        <?= nl2br(htmlspecialchars($evaluation['commentaires_prives'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Suggestions -->
                            <?php if (!empty($evaluation['suggestions'])): ?>
                                <div class="detail-section">
                                    <h3>Suggestions d'amélioration</h3>
                                    <div class="article-content">
                                        <?= nl2br(htmlspecialchars($evaluation['suggestions'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Évaluation en cours ou en attente -->
                    <div class="content-card fade-up">
                        <div class="card-header">
                            <h2>État de l'évaluation</h2>
                        </div>
                        <div class="article-details-content">
                            <div class="detail-section">
                                <p style="color: var(--color-gray-600);">
                                    <?php if (strtolower($evaluation['statut'] ?? '') === 'en_cours'): ?>
                                        Cette évaluation est actuellement en cours. L'évaluateur travaille sur son évaluation.
                                    <?php else: ?>
                                        Cette évaluation est en attente. L'évaluateur n'a pas encore commencé son travail.
                                    <?php endif; ?>
                                </p>
                                <a href="<?= Router\Router::route("admin") ?>/article/<?= $evaluation['article_id'] ?? '' ?>" class="btn btn-outline" style="margin-top: 1rem;">
                                    Voir les détails de l'article
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="content-card fade-up">
                    <div class="empty-state">
                        <h3>Évaluation introuvable</h3>
                        <p>L'évaluation que vous recherchez n'existe pas ou vous n'avez pas les droits pour y accéder.</p>
                        <a href="<?= Router\Router::route("admin") ?>/evaluations" class="btn btn-primary">Retour aux évaluations</a>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <button class="mobile-menu-btn" id="mobile-menu-btn">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <style>
        /* Assurer que le contenu ne dépasse pas du cadre */
        .dashboard-main {
            overflow-x: hidden;
        }
        
        .content-card {
            max-width: 100%;
            overflow-x: auto;
        }
        
        .article-details-content {
            max-width: 100%;
            overflow-x: hidden;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            max-width: 100%;
        }
        
        .detail-section {
            max-width: 100%;
            overflow-x: hidden;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .detail-item {
            min-width: 0; /* Permet au contenu de se rétrécir */
            max-width: 100%;
        }
        
        .detail-item label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--color-gray-700);
            font-size: 0.875rem;
        }
        
        .detail-item p {
            margin: 0;
            color: var(--color-gray-900);
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
        }
        
        .article-content {
            max-width: 100%;
            word-wrap: break-word;
            overflow-wrap: break-word;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        
        /* S'assurer que les sections de commentaires sont bien contenues */
        .detail-section > div[style*="background"] {
            max-width: 100%;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: pre-wrap;
        }
        
        /* Responsive pour les petits écrans */
        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
</body>
</html>

