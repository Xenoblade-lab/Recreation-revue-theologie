<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles - Admin</title>
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
                    <h1>Articles / Soumissions</h1>
                    <p>Gestion de toutes les soumissions</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-outline" onclick="window.location.reload()">Actualiser</button>
                </div>
            </div>

            <div class="content-card fade-up">
                <div class="card-header">
                    <h2>Toutes les soumissions</h2>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <select id="filter-status" style="padding: 0.5rem; border-radius: 4px; border: 1px solid var(--color-gray-300);">
                            <option value="">Tous les statuts</option>
                            <option value="soumis">Soumis</option>
                            <option value="valide">Validé</option>
                            <option value="rejete">Rejeté</option>
                        </select>
                    </div>
                </div>
                <div style="overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch;">
                    <table class="data-table" style="min-width: 1050px; width: 100%;">
                        <thead>
                            <tr>
                                <th style="min-width: 50px; width: 70px;">ID</th>
                                <th style="min-width: 260px;">Titre</th>
                                <th style="min-width: 160px;">Auteur</th>
                                <th style="min-width: 220px;">Email</th>
                                <th style="min-width: 180px;">Date de soumission</th>
                                <th style="min-width: 120px;">Statut</th>
                                <th style="min-width: 200px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($articles)): ?>
                            <?php foreach ($articles as $article): ?>
                                <?php
                                    $statut = strtolower($article['statut'] ?? '');
                                    // Mapping selon les spécifications : soumis → pending, en évaluation → in-review, accepté/valide → accepted, publié → published, rejeté → rejected
                                    $badge = 'pending'; // Par défaut
                                    if (strpos($statut, 'publ') !== false) {
                                        $badge = 'published';
                                    } elseif (strpos($statut, 'accept') !== false || strpos($statut, 'valide') !== false) {
                                        $badge = 'accepted';
                                    } elseif (strpos($statut, 'rej') !== false) {
                                        $badge = 'rejected';
                                    } elseif (strpos($statut, 'evaluation') !== false || strpos($statut, 'évaluation') !== false || strpos($statut, 'revision') !== false) {
                                        $badge = 'in-review';
                                    }
                                    
                                    $statutDisplay = ucfirst($article['statut'] ?? 'Soumis');
                                    if ($statut === 'valide' || strpos($statut, 'accept') !== false) $statutDisplay = 'Accepté';
                                    elseif ($statut === 'rejete') $statutDisplay = 'Rejeté';
                                    elseif ($statut === 'soumis') $statutDisplay = 'Soumis';
                                    elseif (strpos($statut, 'evaluation') !== false || strpos($statut, 'évaluation') !== false) $statutDisplay = 'En évaluation';
                                    elseif (strpos($statut, 'revision') !== false) $statutDisplay = 'Révisions requises';
                                    elseif (strpos($statut, 'publ') !== false) $statutDisplay = 'Publié';
                                ?>
                                <tr data-status="<?= htmlspecialchars($statut) ?>">
                                    <td><?= htmlspecialchars($article['id']) ?></td>
                                    <td><?= htmlspecialchars($article['titre']) ?></td>
                                    <td><?= htmlspecialchars(trim(($article['prenom'] ?? '') . ' ' . ($article['nom'] ?? ''))) ?></td>
                                    <td><?= htmlspecialchars($article['email'] ?? '') ?></td>
                                    <td><?= !empty($article['date_soumission']) ? date('d M Y H:i', strtotime($article['date_soumission'])) : '—' ?></td>
                                    <td><span class="status-badge <?= $badge ?>"><?= htmlspecialchars($statutDisplay) ?></span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn view" title="Voir les détails" onclick="window.location.href='<?= Router\Router::route('admin') ?>/article/<?= $article['id'] ?>'">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button class="action-btn edit" title="Attribuer à un évaluateur" onclick="showAssignModal(<?= $article['id'] ?>, '<?= htmlspecialchars($article['titre']) ?>')" style="background: var(--color-blue); color: white;">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </button>
                                            <?php 
                                                $isPublished = in_array(strtolower($article['statut'] ?? ''), ['publie', 'publié', 'valide', 'validé', 'accepte', 'accepté', 'accepted']);
                                            ?>
                                            <?php if ($isPublished): ?>
                                                <button class="action-btn edit" title="Assigner à un numéro" onclick="showAssignIssueModal(<?= $article['id'] ?>, '<?= htmlspecialchars($article['titre']) ?>')" style="background: #10b981 !important; color: white !important; border: none;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#ffffff" width="16" height="16" style="width: 16px; height: 16px; display: block;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" style="stroke: #ffffff !important;" />
                                                    </svg>
                                                </button>
                                            <?php endif; ?>
                                            <button class="action-btn edit" title="Modifier le statut" onclick="showStatusModal(<?= $article['id'] ?>, '<?= htmlspecialchars($statut) ?>', '<?= htmlspecialchars($article['titre']) ?>')">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button class="action-btn delete" title="Supprimer" type="button" onclick="confirmDeleteArticle(<?= $article['id'] ?>)">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align:center; padding:1.5rem;">Aucun article trouvé.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <style>
        /* Mode compact (admin/articles) */
        .dashboard-main {
            padding: 1.25rem;
        }

        .dashboard-header {
            margin-bottom: 1rem;
        }

        .dashboard-header .header-title h1 {
            font-size: 1.5rem;
            line-height: 1.2;
            margin-bottom: 0.25rem;
        }

        .dashboard-header .header-title p {
            font-size: 0.95rem;
        }

        .dashboard-header .header-actions .btn {
            padding: 0.5rem 0.85rem;
            font-size: 0.92rem;
        }

        .content-card .card-header {
            padding: 0.9rem 1rem;
        }

        .content-card .card-header h2 {
            font-size: 1.1rem;
        }

        .content-card .card-header select {
            padding: 0.45rem 0.65rem;
            font-size: 0.92rem;
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

        /* Modals un peu moins volumineux */
        #assignModal > div {
            padding: 1.5rem !important;
            max-width: 560px !important;
        }

        #statusModal > div {
            padding: 1.5rem !important;
            max-width: 520px !important;
        }

        #assignModal h3,
        #statusModal h3 {
            font-size: 1.1rem;
        }

        #assignModal p,
        #statusModal p {
            margin-bottom: 1rem !important;
        }

        /* Empêcher les débordements horizontaux */
        .dashboard-main {
            overflow-x: hidden;
        }

        /* Header (titre + actions) : wrap */
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

        /* Card header (Titre + filtres) : wrap */
        .content-card .card-header {
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-start;
        }

        .content-card .card-header > div {
            flex-wrap: wrap;
            justify-content: flex-end;
            width: 100%;
        }

        .content-card .card-header select {
            min-width: 220px;
            max-width: 100%;
        }

        /* Tableau: rendre stable sur petits écrans */
        .data-table th {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Colonne Titre (texte long) */
        .data-table td:nth-child(2) {
            white-space: normal;
            word-wrap: break-word;
            max-width: 360px;
        }

        /* Colonne Email */
        .data-table td:nth-child(4) {
            white-space: normal;
            word-wrap: break-word;
            max-width: 280px;
        }

        /* Actions: ne pas casser les boutons */
        .data-table td:last-child {
            white-space: nowrap;
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

            .dashboard-main {
                padding: 1rem;
            }
        }
    </style>

    <!-- Modal pour attribuer à un évaluateur -->
    <div id="assignModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; overflow-y: auto; padding: 1rem;">
        <div style="background: white; padding: 1.25rem; border-radius: 8px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; margin: auto;">
            <h3 style="margin-top: 0; margin-bottom: 1rem; font-size: 1.1rem;">Attribuer à un évaluateur</h3>
            <p id="assignModalArticleTitle" style="color: var(--color-gray-600); margin-bottom: 1rem; font-size: 0.875rem;"></p>
            <form id="assignForm">
                <input type="hidden" id="assignModalArticleId" name="article_id">
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Évaluateur *</label>
                    <select name="reviewer_id" id="reviewerSelect" required style="padding: 0.5rem; font-size: 0.875rem; width: 100%;">
                        <option value="">Chargement des évaluateurs...</option>
                    </select>
                </div>
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Délai d'évaluation (en jours) *</label>
                    <input type="number" name="deadline_days" id="deadlineDays" value="14" min="1" max="90" required style="padding: 0.5rem; font-size: 0.875rem; width: 100%;">
                    <small style="color: var(--color-gray-600); font-size: 0.8rem;">Nombre de jours pour compléter l'évaluation</small>
                </div>
                <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1rem;">
                    <button type="button" class="btn btn-outline" onclick="closeAssignModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Annuler</button>
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Attribuer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal pour assigner à un numéro -->
    <div id="assignIssueModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; overflow-y: auto; padding: 1rem;">
        <div style="background: white; padding: 1.25rem; border-radius: 8px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; margin: auto;">
            <h3 style="margin-top: 0; margin-bottom: 1rem; font-size: 1.1rem;">Assigner l'article à un numéro</h3>
            <p id="assignIssueModalArticleTitle" style="color: var(--color-gray-600); margin-bottom: 1rem; font-size: 0.875rem;"></p>
            <form id="assignIssueForm">
                <input type="hidden" id="assignIssueModalArticleId" name="article_id">
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Sélectionner un numéro *</label>
                    <select name="issue_id" id="assignIssueSelect" required style="padding: 0.5rem; font-size: 0.875rem; width: 100%;">
                        <option value="">-- Choisir un numéro --</option>
                        <?php if (!empty($issues)): ?>
                            <?php foreach ($issues as $issue): ?>
                                <option value="<?= $issue['id'] ?>">
                                    <?= htmlspecialchars($issue['numero'] ?? 'Numéro') ?> - <?= htmlspecialchars($issue['titre']) ?>
                                    <?php if (!empty($issue['annee'])): ?>
                                        (<?= htmlspecialchars($issue['annee']) ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (empty($issues)): ?>
                        <p style="font-size: 0.875rem; color: var(--color-gray-600); margin-top: 0.5rem;">
                            Aucun numéro disponible. Créez d'abord un numéro dans la section Volumes.
                        </p>
                    <?php endif; ?>
                </div>
                <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1rem;">
                    <button type="button" class="btn btn-outline" onclick="closeAssignIssueModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Annuler</button>
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;" <?= empty($issues) ? 'disabled' : '' ?>>Assigner</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal pour modifier le statut -->
    <div id="statusModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; overflow-y: auto; padding: 1rem;">
        <div style="background: white; padding: 1.25rem; border-radius: 8px; max-width: 450px; width: 100%; max-height: 90vh; overflow-y: auto; margin: auto;">
            <h3 style="margin-top: 0; margin-bottom: 1rem; font-size: 1.1rem;">Modifier le statut</h3>
            <p id="modalArticleTitle" style="color: var(--color-gray-600); margin-bottom: 1rem; font-size: 0.875rem;"></p>
            <form id="statusForm">
                <input type="hidden" id="modalArticleId" name="article_id">
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Nouveau statut *</label>
                    <select name="statut" id="modalStatut" required style="padding: 0.5rem; font-size: 0.875rem; width: 100%;">
                        <option value="soumis">Soumis</option>
                        <option value="valide">Validé</option>
                        <option value="rejete">Rejeté</option>
                    </select>
                </div>
                <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1rem;">
                    <button type="button" class="btn btn-outline" onclick="closeStatusModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Annuler</button>
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <button class="mobile-menu-btn" id="mobile-menu-btn">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/author-notify.js"></script>
    <script>
        // Filtrer par statut
        document.getElementById('filter-status')?.addEventListener('change', function(e) {
            const filter = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr[data-status]');
            rows.forEach(row => {
                if (!filter || row.getAttribute('data-status') === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Modal d'attribution
        function showAssignModal(articleId, title) {
            document.getElementById('assignModalArticleId').value = articleId;
            document.getElementById('assignModalArticleTitle').textContent = title;
            loadAvailableReviewers(articleId);
            document.getElementById('assignModal').style.display = 'flex';
        }

        function closeAssignModal() {
            document.getElementById('assignModal').style.display = 'none';
        }

        function loadAvailableReviewers(articleId) {
            const select = document.getElementById('reviewerSelect');
            select.innerHTML = '<option value="">Chargement...</option>';
            
            fetch('<?= Router\Router::route("admin") ?>/article/' + articleId + '/reviewers')
                .then(response => response.json())
                .then(data => {
                    select.innerHTML = '<option value="">Sélectionnez un évaluateur</option>';
                    if (data.evaluateurs && data.evaluateurs.length > 0) {
                        data.evaluateurs.forEach(reviewer => {
                            const option = document.createElement('option');
                            option.value = reviewer.id;
                            const name = (reviewer.prenom || '') + ' ' + (reviewer.nom || '');
                            const reviewCount = reviewer.review_count || 0;
                            option.textContent = name.trim() + ' (' + reviewer.email + ') - ' + reviewCount + ' évaluation(s)';
                            select.appendChild(option);
                        });
                    } else {
                        select.innerHTML = '<option value="">Aucun évaluateur disponible</option>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    select.innerHTML = '<option value="">Erreur lors du chargement</option>';
                });
        }

        // Soumettre le formulaire d'attribution
        document.getElementById('assignForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const articleId = formData.get('article_id') || document.getElementById('assignModalArticleId')?.value;
            
            if (!articleId) {
                showToast('Erreur: ID d\'article manquant', 'error');
                return;
            }

            const reviewerId = formData.get('reviewer_id');
            const deadlineDays = parseInt(formData.get('deadline_days')) || 14;

            if (!reviewerId) {
                showToast('Veuillez sélectionner un évaluateur', 'error');
                return;
            }

            const data = {
                reviewer_id: reviewerId,
                deadline_days: deadlineDays
            };

            fetch('<?= Router\Router::route("admin") ?>/article/' + articleId + '/assign-reviewer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.success || data.message) {
                    showToast(data.message || 'Évaluateur assigné avec succès', 'success');
                    closeAssignModal();
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.error || 'Une erreur est survenue', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                const errorMsg = error.error || error.message || 'Une erreur est survenue lors de l\'attribution';
                showToast(errorMsg, 'error');
            });
        });

        // Modal de modification de statut
        function showStatusModal(id, currentStatus, title) {
            document.getElementById('modalArticleId').value = id;
            document.getElementById('modalArticleTitle').textContent = title;
            document.getElementById('modalStatut').value = currentStatus;
            document.getElementById('statusModal').style.display = 'flex';
        }

        function closeStatusModal() {
            document.getElementById('statusModal').style.display = 'none';
        }

        // Modal d'assignation à un numéro
        function showAssignIssueModal(articleId, title) {
            document.getElementById('assignIssueModalArticleId').value = articleId;
            document.getElementById('assignIssueModalArticleTitle').textContent = title;
            document.getElementById('assignIssueModal').style.display = 'flex';
        }

        function closeAssignIssueModal() {
            document.getElementById('assignIssueModal').style.display = 'none';
            document.getElementById('assignIssueForm').reset();
        }

        // Soumettre le formulaire d'assignation à un numéro
        document.getElementById('assignIssueForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const articleId = formData.get('article_id');
            const issueId = formData.get('issue_id');
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Assignation...';
            
            fetch('<?= Router\Router::route("admin") ?>/articles/' + articleId + '/assign-issue', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Article assigné au numéro avec succès !', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('Erreur : ' + (data.error || 'Une erreur est survenue'), 'error');
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });

        // Soumettre le formulaire de changement de statut
        document.getElementById('statusForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const articleId = formData.get('article_id');
            const statut = formData.get('statut');

            fetch('<?= Router\Router::route("admin") ?>/article/' + articleId + '/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ statut: statut })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message) {
                    showToast(data.message || 'Statut mis à jour avec succès', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.error || 'Une erreur est survenue', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue lors de la mise à jour', 'error');
            });
        });

        // Supprimer un article
        async function confirmDeleteArticle(id) {
            const ok = typeof showConfirm === 'function'
                ? await showConfirm({
                    title: 'Supprimer l\'article',
                    message: 'Êtes-vous sûr de vouloir supprimer cet article ?',
                    confirmText: 'Supprimer',
                    cancelText: 'Annuler'
                })
                : confirm('Êtes-vous sûr de vouloir supprimer cet article ?');
            if (!ok) return;
            deleteArticle(id);
        }

        function deleteArticle(id) {
            fetch('<?= Router\Router::route("admin") ?>/article/' + id + '/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message) {
                    showToast(data.message || 'Article supprimé avec succès', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.error || 'Une erreur est survenue lors de la suppression', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue lors de la suppression', 'error');
            });
        }

        // Fermer les modals en cliquant en dehors
        document.getElementById('statusModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeStatusModal();
            }
        });

        document.getElementById('assignModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAssignModal();
            }
        });
    </script>
</body>
</html>

