<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'article - Dashboard Auteur</title>
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
                    <a href="<?= Router\Router::route("author") ?>" class="back-link" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-blue); text-decoration: none; margin-bottom: 0.5rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        Retour au tableau de bord
                    </a>
                    <h1>Détails de l'article</h1>
                    <p>Informations complètes sur votre soumission</p>
                </div>
                <div class="header-actions">
                    <?php 
                    $statut = strtolower($article['statut'] ?? '');
                    // Permettre la modification si statut = soumis OU revision_requise
                    if ($statut === 'soumis' || strpos($statut, 'revision') !== false): 
                    ?>
                        <a href="<?= Router\Router::route("author") ?>/article/<?= $article['id'] ?>/edit" class="btn btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            <?= strpos($statut, 'revision') !== false ? 'Modifier et resoumettre' : 'Modifier' ?>
                        </a>
                        <?php if ($statut === 'soumis'): ?>
                            <button type="button" onclick="deleteArticle(<?= $article['id'] ?>)" class="btn btn-outline" style="color: var(--color-red); border-color: var(--color-red);">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                                Supprimer
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (isset($article) && $article): ?>
                <!-- Article Details -->
                <div class="content-card fade-up">
                    <div class="card-header">
                        <h2><?= htmlspecialchars($article['titre']) ?></h2>
                        <span class="status-badge <?= 
                            strpos(strtolower($article['statut']), 'publ') !== false ? 'published' : 
                            (strpos(strtolower($article['statut']), 'accept') !== false || strpos(strtolower($article['statut']), 'valide') !== false ? 'accepted' : 
                            (strpos(strtolower($article['statut']), 'rej') !== false ? 'rejected' : 
                            (strpos(strtolower($article['statut']), 'évaluation') !== false || strpos(strtolower($article['statut']), 'evaluation') !== false || strpos(strtolower($article['statut']), 'revision') !== false ? 'in-review' : 'pending')))
                        ?>">
                            <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $article['statut']))) ?>
                        </span>
                    </div>

                    <div class="article-details-content">
                        <!-- Informations générales -->
                        <div class="detail-section">
                            <h3>Informations générales</h3>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <label>Date de soumission</label>
                                    <p><?= date('d M Y à H:i', strtotime($article['date_soumission'] ?? $article['created_at'])) ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Dernière modification</label>
                                    <p><?= date('d M Y à H:i', strtotime($article['updated_at'])) ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Auteur</label>
                                    <p><?= htmlspecialchars(($article['auteur_prenom'] ?? '') . ' ' . ($article['auteur_nom'] ?? '')) ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Email</label>
                                    <p><?= htmlspecialchars($article['auteur_email'] ?? '') ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Résumé -->
                        <?php if (!empty($article['contenu'])): ?>
                            <div class="detail-section">
                                <h3>Résumé</h3>
                                <p class="article-content"><?= nl2br(htmlspecialchars($article['contenu'])) ?></p>
                            </div>
                        <?php endif; ?>

                        <!-- Fichier -->
                        <?php if (!empty($article['fichier_path'])): ?>
                            <div class="detail-section">
                                <h3>Fichier joint</h3>
                                <div class="file-info">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    <div>
                                        <p><strong><?= htmlspecialchars(basename($article['fichier_path'])) ?></strong></p>
                                        <a href="<?= Router\Router::$defaultUri . htmlspecialchars($article['fichier_path']) ?>" class="btn btn-outline" target="_blank" style="margin-top: 0.5rem;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                <line x1="12" y1="15" x2="12" y2="3"></line>
                                            </svg>
                                            Télécharger le fichier
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Évaluations et commentaires -->
                        <?php if (!empty($evaluations)): ?>
                            <div class="detail-section">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                    <h3>Commentaires des évaluateurs</h3>
                                    <?php
                                    // Vérifier si l'article peut être modifié (statut revision_requise ou si des évaluations demandent des révisions)
                                    $canModify = false;
                                    $hasRevisionRequest = false;
                                    $statut = strtolower($article['statut'] ?? '');
                                    
                                    if ($statut === 'soumis' || strpos($statut, 'revision') !== false) {
                                        $canModify = true;
                                    }
                                    
                                    // Vérifier si des évaluations demandent des révisions
                                    foreach ($evaluations as $eval) {
                                        $rec = strtolower($eval['recommendation'] ?? '');
                                        if (in_array($rec, ['revision_majeure', 'accepte_avec_modifications'])) {
                                            $hasRevisionRequest = true;
                                            $canModify = true;
                                            break;
                                        }
                                    }
                                    ?>
                                    <?php if ($canModify): ?>
                                        <a href="<?= Router\Router::route("author") ?>/article/<?= $article['id'] ?>/edit" class="btn btn-primary">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            <?= $hasRevisionRequest || strpos($statut, 'revision') !== false ? 'Modifier et resoumettre' : 'Modifier l\'article' ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($hasRevisionRequest || strpos($statut, 'revision') !== false): ?>
                                    <div class="alert alert-warning" style="margin-bottom: 1.5rem;">
                                        <strong>⚠️ Action requise</strong>
                                        <p style="margin: 0.5rem 0 0 0;">Des révisions sont nécessaires suite aux commentaires des évaluateurs. Veuillez consulter les commentaires ci-dessous et modifier votre article en conséquence.</p>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="evaluations-list">
                                    <?php foreach ($evaluations as $evaluation): ?>
                                        <div class="evaluation-card">
                                            <div class="evaluation-header">
                                                <div>
                                                    <strong><?= htmlspecialchars(($evaluation['evaluateur_prenom'] ?? '') . ' ' . ($evaluation['evaluateur_nom'] ?? 'Évaluateur')) ?></strong>
                                                    <span class="evaluation-date"><?= !empty($evaluation['date_soumission']) ? date('d M Y', strtotime($evaluation['date_soumission'])) : '—' ?></span>
                                                </div>
                                                <span class="status-badge <?= 
                                                    $evaluation['recommendation'] === 'accepte' ? 'accepted' : 
                                                    ($evaluation['recommendation'] === 'rejete' ? 'rejected' : 
                                                    (in_array($evaluation['recommendation'], ['revision_majeure', 'accepte_avec_modifications']) ? 'pending' : 'accepted'))
                                                ?>">
                                                    <?php
                                                    $recLabels = [
                                                        'accepte' => 'Accepté',
                                                        'accepte_avec_modifications' => 'Accepté avec modifications',
                                                        'revision_majeure' => 'Révisions majeures requises',
                                                        'rejete' => 'Rejeté'
                                                    ];
                                                    echo htmlspecialchars($recLabels[$evaluation['recommendation']] ?? ucfirst($evaluation['recommendation'] ?? 'N/A'));
                                                    ?>
                                                </span>
                                            </div>
                                            
                                            <?php if (!empty($evaluation['commentaires_public'])): ?>
                                                <div class="evaluation-comments">
                                                    <h4>Commentaires :</h4>
                                                    <p><?= nl2br(htmlspecialchars($evaluation['commentaires_public'])) ?></p>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($evaluation['suggestions'])): ?>
                                                <div class="evaluation-suggestions">
                                                    <h4>Suggestions d'amélioration :</h4>
                                                    <p><?= nl2br(htmlspecialchars($evaluation['suggestions'])) ?></p>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($evaluation['note_finale'])): ?>
                                                <div class="evaluation-scores">
                                                    <h4>Notes :</h4>
                                                    <div class="scores-grid">
                                                        <div><strong>Qualité scientifique :</strong> <?= $evaluation['qualite_scientifique'] ?? 'N/A' ?>/10</div>
                                                        <div><strong>Originalité :</strong> <?= $evaluation['originalite'] ?? 'N/A' ?>/10</div>
                                                        <div><strong>Pertinence :</strong> <?= $evaluation['pertinence'] ?? 'N/A' ?>/10</div>
                                                        <div><strong>Clarté :</strong> <?= $evaluation['clarte'] ?? 'N/A' ?>/10</div>
                                                        <div class="score-final"><strong>Note finale :</strong> <?= $evaluation['note_finale'] ?>/10</div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (in_array($evaluation['recommendation'], ['revision_majeure', 'accepte_avec_modifications'])): ?>
                                                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--color-gray-200);">
                                                    <a href="<?= Router\Router::route("author") ?>/article/<?= $article['id'] ?>/edit" class="btn btn-outline btn-sm">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                        </svg>
                                                        Modifier selon ces commentaires
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Révisions -->
                        <?php if (strpos(strtolower($article['statut']), 'revision') !== false): ?>
                            <div class="detail-section">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                    <h3>Action requise</h3>
                                    <a href="<?= Router\Router::route("author") ?>/article/<?= $article['id'] ?>/revisions" class="btn btn-outline btn-sm">
                                        Voir l'historique complet
                                    </a>
                                </div>
                                <div class="alert alert-warning">
                                    <strong>⚠️ Révisions requises</strong>
                                    <p style="margin: 0.5rem 0 0 0;">Votre article nécessite des révisions. Veuillez consulter les commentaires des évaluateurs ci-dessus et modifier votre article en conséquence. Une fois modifié, votre article sera automatiquement resoumis pour une nouvelle évaluation.</p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Workflow -->
                        <div class="detail-section">
                            <h3>État du workflow</h3>
                            <div class="workflow-steps">
                                <?php
                                // Utiliser le statut réel de la base de données
                                $statut = strtolower(trim($article['statut'] ?? 'soumis'));
                                
                                // Déterminer les étapes complétées basées sur le statut réel
                                $step1_reçu = true; // Toujours complété une fois soumis
                                
                                // Vérifier s'il y a des évaluations en cours ou terminées
                                $hasEvaluations = !empty($evaluationsEnCours ?? []);
                                
                                // Étape 2: En évaluation - complété si statut = en_evaluation, revision_requise, accepte, valide, publie OU s'il y a des évaluations
                                $step2_evaluation = $hasEvaluations || in_array($statut, [
                                    'en_evaluation', 
                                    'en évaluation', 
                                    'en evaluation',
                                    'revision_requise',
                                    'revision requise',
                                    'accepte',
                                    'accepted',
                                    'accepté',
                                    'valide',
                                    'publie',
                                    'publié',
                                    'published'
                                ]);
                                
                                // Étape 3: Révisions - complété si statut = revision_requise (déjà passé par là) ou accepte/valide/publie
                                $step3_revisions = in_array($statut, [
                                    'revision_requise',
                                    'revision requise',
                                    'accepte',
                                    'accepted',
                                    'accepté',
                                    'valide',
                                    'publie',
                                    'publié',
                                    'published'
                                ]);
                                
                                // Étape 4: Accepté - complété si statut = accepte ou valide/publie
                                $step4_accepte = in_array($statut, [
                                    'accepte',
                                    'accepted',
                                    'accepté',
                                    'valide',
                                    'publie',
                                    'publié',
                                    'published'
                                ]);
                                
                                // Étape 5: Publié - complété si statut = publie uniquement
                                $step5_publie = in_array($statut, [
                                    'publie',
                                    'publié',
                                    'published'
                                ]);
                                
                                $steps = [
                                    ['name' => 'Reçu', 'completed' => $step1_reçu],
                                    ['name' => 'En évaluation', 'completed' => $step2_evaluation],
                                    ['name' => 'Révisions', 'completed' => $step3_revisions],
                                    ['name' => 'Accepté', 'completed' => $step4_accepte],
                                    ['name' => 'Publié', 'completed' => $step5_publie]
                                ];
                                
                                // Déterminer l'étape courante (la première non complétée)
                                $currentStep = 0;
                                foreach ($steps as $index => $step) {
                                    if ($step['completed']) {
                                        $currentStep = $index + 1; // L'étape suivante devient courante
                                    } else {
                                        break; // Arrêter à la première non complétée
                                    }
                                }
                                // Si toutes sont complétées, currentStep reste sur la dernière
                                if ($currentStep >= count($steps)) {
                                    $currentStep = count($steps) - 1;
                                }
                                ?>
                                <?php foreach ($steps as $index => $step): ?>
                                    <div class="workflow-step <?= $step['completed'] ? 'completed' : '' ?> <?= $index === $currentStep && !$step['completed'] ? 'current' : '' ?>">
                                        <div class="step-icon">
                                            <?php if ($step['completed']): ?>
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            <?php elseif ($index === $currentStep && !$step['completed']): ?>
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                </svg>
                                            <?php else: ?>
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                </svg>
                                            <?php endif; ?>
                                        </div>
                                        <span><?= $step['name'] ?></span>
                                    </div>
                                    <?php if ($index < count($steps) - 1): ?>
                                        <div class="workflow-arrow <?= $step['completed'] ? 'completed' : '' ?>">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="9 18 15 12 9 6"></polyline>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="content-card fade-up">
                    <div class="empty-state">
                        <h3>Article introuvable</h3>
                        <p>L'article que vous recherchez n'existe pas ou vous n'avez pas les droits pour le consulter.</p>
                        <a href="<?= Router\Router::route("author") ?>" class="btn btn-primary">Retour au tableau de bord</a>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <style>
        .evaluations-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-top: 1rem;
        }
        .evaluation-card {
            padding: 1.5rem;
            border: 1px solid var(--color-gray-200);
            border-radius: 8px;
            background: var(--color-white);
        }
        .evaluation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--color-gray-200);
        }
        .evaluation-date {
            color: var(--color-gray-500);
            font-size: 0.875rem;
            margin-left: 0.5rem;
        }
        .evaluation-comments,
        .evaluation-suggestions {
            margin-top: 1rem;
        }
        .evaluation-comments h4,
        .evaluation-suggestions h4 {
            margin: 0 0 0.5rem 0;
            font-size: 0.875rem;
            color: var(--color-gray-700);
            font-weight: 600;
        }
        .evaluation-comments p,
        .evaluation-suggestions p {
            margin: 0;
            color: var(--color-gray-600);
            line-height: 1.6;
        }
        .evaluation-scores {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--color-gray-200);
        }
        .evaluation-scores h4 {
            margin: 0 0 0.75rem 0;
            font-size: 0.875rem;
            color: var(--color-gray-700);
            font-weight: 600;
        }
        .scores-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
        .score-final {
            grid-column: 1 / -1;
            padding-top: 0.75rem;
            border-top: 1px solid var(--color-gray-200);
            font-weight: 600;
            color: var(--color-blue);
        }
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .alert-warning {
            background: rgba(251, 191, 36, 0.1);
            border: 1px solid rgba(251, 191, 36, 0.3);
        }
        .alert-warning strong {
            color: #d97706;
        }
        .alert-warning p {
            margin: 0.5rem 0 0 0;
            color: var(--color-gray-700);
        }
    </style>
    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/author-notify.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
    <script>
        async function deleteArticle(id) {
            const ok = await showConfirm({
                title: 'Supprimer l\'article',
                message: 'Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.',
                confirmText: 'Supprimer',
                cancelText: 'Annuler'
            });
            if (!ok) return;
            
            fetch('<?= Router\Router::route("author") ?>/article/' + id + '/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message) {
                    showToast(data.message || 'Article supprimé avec succès', 'success');
                    window.location.href = '<?= Router\Router::route("author") ?>';
                } else {
                    showToast(data.error || 'Une erreur est survenue lors de la suppression', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue lors de la suppression de l\'article', 'error');
            });
        }
    </script>
</body>
</html>

