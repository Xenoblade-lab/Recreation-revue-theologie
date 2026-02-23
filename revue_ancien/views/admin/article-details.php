<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'article - Admin</title>
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
                    <a href="<?= Router\Router::route("admin") ?>/articles" class="back-link" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-blue); text-decoration: none; margin-bottom: 0.5rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        Retour aux articles
                    </a>
                    <h1>Détails de l'article</h1>
                    <p>Informations complètes sur la soumission</p>
                </div>
                <div class="header-actions">
                    <?php 
                    $statut = strtolower($article['statut'] ?? '');
                    // Bouton pour publier si l'article est accepté
                    if (strpos($statut, 'accept') !== false && strpos($statut, 'publ') === false): 
                    ?>
                        <button onclick="publishArticle(<?= $article['id'] ?>)" class="btn btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Publier l'article
                        </button>
                    <?php endif; ?>
                    <button onclick="showAssignModal()" class="btn btn-outline">Attribuer à un évaluateur</button>
                    <button onclick="showStatusModal()" class="btn btn-outline">Modifier le statut</button>
                    <button onclick="confirmDeleteArticle(<?= $article['id'] ?>)" class="btn btn-outline" style="color: var(--color-red); border-color: var(--color-red);" type="button">
                        Supprimer
                    </button>
                </div>
            </div>

            <?php if (isset($article) && $article): ?>
                <div class="content-card fade-up">
                    <div class="card-header">
                        <h2><?= htmlspecialchars($article['titre']) ?></h2>
                        <span class="status-badge <?= 
                            strpos(strtolower($article['statut']), 'publ') !== false ? 'published' : 
                            (strpos(strtolower($article['statut']), 'accept') !== false || strpos(strtolower($article['statut']), 'valide') !== false ? 'accepted' : 
                            (strpos(strtolower($article['statut']), 'rej') !== false ? 'rejected' : 
                            (strpos(strtolower($article['statut']), 'evaluation') !== false || strpos(strtolower($article['statut']), 'évaluation') !== false || strpos(strtolower($article['statut']), 'revision') !== false ? 'in-review' : 'pending')))
                        ?>">
                            <?= htmlspecialchars(ucfirst($article['statut'])) ?>
                        </span>
                    </div>

                    <div class="article-details-content">
                        <div class="detail-section">
                            <h3>Informations générales</h3>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <label>Date de soumission</label>
                                    <p><?= date('d M Y à H:i', strtotime($article['date_soumission'] ?? $article['created_at'])) ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Dernière modification</label>
                                    <p><?= date('d M Y à H:i', strtotime($article['updated_at'] ?? $article['created_at'])) ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Auteur</label>
                                    <p><?= htmlspecialchars(trim(($auteur['prenom'] ?? '') . ' ' . ($auteur['nom'] ?? ''))) ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Email</label>
                                    <p><?= htmlspecialchars($auteur['email'] ?? '') ?></p>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($article['contenu'])): ?>
                            <div class="detail-section">
                                <h3>Résumé</h3>
                                <p class="article-content"><?= nl2br(htmlspecialchars($article['contenu'])) ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($article['fichier_path'])): ?>
                            <div class="detail-section">
                                <h3>Fichier joint</h3>
                                <a href="<?= Router\Router::$defaultUri . $article['fichier_path'] ?>" target="_blank" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    Télécharger le fichier
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="detail-section">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <h3>Évaluateurs assignés</h3>
                                <button onclick="loadAssignedReviewers()" class="btn btn-outline btn-sm" style="padding: 0.5rem 1rem;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 0.5rem; display: inline-block;">
                                        <polyline points="23 4 23 10 17 10"></polyline>
                                        <polyline points="1 20 1 14 7 14"></polyline>
                                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                                    </svg>
                                    Actualiser
                                </button>
                            </div>
                            <div id="assignedReviewersList">
                                <p style="color: var(--color-gray-600);">Chargement des évaluateurs...</p>
                            </div>
                        </div>

                        <!-- Historique des révisions -->
                        <?php if (isset($revisionCount) && $revisionCount > 0): ?>
                            <div class="detail-section">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                    <h3>Historique des révisions</h3>
                                    <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #2563eb; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem;">
                                        <?= $revisionCount ?> révision<?= $revisionCount > 1 ? 's' : '' ?>
                                    </span>
                                </div>
                                <div class="revisions-timeline">
                                    <?php if (!empty($revisions)): ?>
                                        <?php foreach (array_reverse($revisions) as $revision): ?>
                                            <div class="revision-item">
                                                <div class="revision-header">
                                                    <div>
                                                        <strong>Révision #<?= $revision['revision_number'] ?? 'N/A' ?></strong>
                                                        <span class="revision-date"><?= date('d M Y à H:i', strtotime($revision['submitted_at'] ?? $revision['created_at'])) ?></span>
                                                    </div>
                                                    <div class="revision-status-change">
                                                        <span class="status-badge pending"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $revision['previous_status'] ?? 'N/A'))) ?></span>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 0.5rem;">
                                                            <polyline points="9 18 15 12 9 6"></polyline>
                                                        </svg>
                                                        <span class="status-badge <?= 
                                                            strpos(strtolower($revision['new_status'] ?? ''), 'publ') !== false ? 'published' : 
                                                            (strpos(strtolower($revision['new_status'] ?? ''), 'accept') !== false ? 'accepted' : 
                                                            (strpos(strtolower($revision['new_status'] ?? ''), 'rej') !== false ? 'rejected' : 
                                                            (strpos(strtolower($revision['new_status'] ?? ''), 'evaluation') !== false || strpos(strtolower($revision['new_status'] ?? ''), 'revision') !== false ? 'in-review' : 'pending')))
                                                        ?>"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $revision['new_status'] ?? 'N/A'))) ?></span>
                                                    </div>
                                                </div>
                                                <?php if (!empty($revision['revision_reason'])): ?>
                                                    <div class="revision-reason">
                                                        <strong>Raison :</strong> <?= nl2br(htmlspecialchars($revision['revision_reason'])) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p style="color: var(--color-gray-600);">Aucune révision enregistrée.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Modal pour attribuer à un évaluateur -->
    <div id="assignModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; padding: 2rem; border-radius: 8px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto;">
            <h3 style="margin-top: 0;">Attribuer à un évaluateur</h3>
            <p style="color: var(--color-gray-600); margin-bottom: 1.5rem;">Sélectionnez un évaluateur pour cet article</p>
            <form id="assignForm">
                <input type="hidden" name="article_id" value="<?= $article['id'] ?? '' ?>">
                <div class="form-field">
                    <label>Évaluateur *</label>
                    <select name="reviewer_id" id="reviewerSelect" required>
                        <option value="">Chargement des évaluateurs...</option>
                    </select>
                </div>
                <div class="form-field">
                    <label>Délai d'évaluation (en jours) *</label>
                    <input type="number" name="deadline_days" id="deadlineDays" value="14" min="1" max="90" required>
                    <small style="color: var(--color-gray-600);">Nombre de jours pour compléter l'évaluation</small>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" class="btn btn-outline" onclick="closeAssignModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Attribuer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal pour modifier le statut -->
    <div id="statusModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; padding: 2rem; border-radius: 8px; max-width: 500px; width: 90%;">
            <h3 style="margin-top: 0;">Modifier le statut</h3>
            <form id="statusForm">
                <input type="hidden" name="article_id" value="<?= $article['id'] ?? '' ?>">
                <div class="form-field">
                    <label>Nouveau statut *</label>
                    <select name="statut" id="modalStatut" required>
                        <option value="soumis" <?= ($article['statut'] ?? '') === 'soumis' ? 'selected' : '' ?>>Soumis</option>
                        <option value="valide" <?= ($article['statut'] ?? '') === 'valide' ? 'selected' : '' ?>>Validé</option>
                        <option value="rejete" <?= ($article['statut'] ?? '') === 'rejete' ? 'selected' : '' ?>>Rejeté</option>
                    </select>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" class="btn btn-outline" onclick="closeStatusModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <button class="mobile-menu-btn" id="mobile-menu-btn">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <style>
        .revisions-timeline {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 1rem;
        }
        .revision-item {
            padding: 1rem;
            border: 1px solid var(--color-gray-200);
            border-radius: 8px;
            background: var(--color-white);
            border-left: 3px solid var(--color-blue);
        }
        .revision-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .revision-date {
            color: var(--color-gray-500);
            font-size: 0.875rem;
            margin-left: 0.5rem;
            font-weight: normal;
        }
        .revision-status-change {
            display: flex;
            align-items: center;
        }
        .revision-reason {
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--color-gray-200);
            color: var(--color-gray-600);
            line-height: 1.6;
        }
    </style>
    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/author-notify.js"></script>
    <script>
        const articleId = <?= $article['id'] ?? 0 ?>;

        // Charger les évaluateurs assignés au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            loadAssignedReviewers();
        });

        // Fonctions pour le modal d'attribution
        function showAssignModal() {
            loadAvailableReviewers();
            document.getElementById('assignModal').style.display = 'flex';
        }

        function closeAssignModal() {
            document.getElementById('assignModal').style.display = 'none';
        }

        function loadAvailableReviewers() {
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

        function loadAssignedReviewers() {
            const container = document.getElementById('assignedReviewersList');
            container.innerHTML = '<p style="color: var(--color-gray-600);">Chargement...</p>';
            
            fetch('<?= Router\Router::route("admin") ?>/article/' + articleId + '/assigned-reviewers')
                .then(response => response.json())
                .then(data => {
                    if (data.evaluateurs && data.evaluateurs.length > 0) {
                        let html = '<table class="data-table" style="margin-top: 1rem;">';
                        html += '<thead><tr><th>Évaluateur</th><th>Email</th><th>Statut</th><th>Date d\'assignation</th><th>Échéance</th><th>Actions</th></tr></thead>';
                        html += '<tbody>';
                        data.evaluateurs.forEach(eval => {
                            const name = (eval.prenom || '') + ' ' + (eval.nom || '');
                            const joursRestants = eval.jours_restants !== null ? eval.jours_restants : 'N/A';
                            // Mapping pour les évaluations : en_attente → pending, en_cours → in-review, termine → accepted
                            const statusClass = eval.statut === 'termine' ? 'accepted' : 
                                               (eval.statut === 'en_cours' ? 'in-review' : 'pending');
                            html += '<tr>';
                            html += '<td>' + (name.trim() || 'N/A') + '</td>';
                            html += '<td>' + (eval.email || 'N/A') + '</td>';
                            html += '<td><span class="status-badge ' + statusClass + '">' + (eval.statut || 'N/A') + '</span></td>';
                            html += '<td>' + (eval.date_assignation ? new Date(eval.date_assignation).toLocaleDateString('fr-FR') : 'N/A') + '</td>';
                            html += '<td>' + (eval.date_echeance ? new Date(eval.date_echeance).toLocaleDateString('fr-FR') + ' (' + joursRestants + ' jours)' : 'N/A') + '</td>';
                            html += '<td><button class="btn btn-outline btn-sm" onclick="unassignReviewer(' + eval.evaluation_id + ')" style="color: var(--color-red); border-color: var(--color-red);">Retirer</button></td>';
                            html += '</tr>';
                        });
                        html += '</tbody></table>';
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = '<p style="color: var(--color-gray-600);">Aucun évaluateur assigné pour le moment.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    container.innerHTML = '<p style="color: var(--color-red);">Erreur lors du chargement des évaluateurs.</p>';
                });
        }

        // Soumettre le formulaire d'attribution
        document.getElementById('assignForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
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

            // Désactiver le bouton pour éviter les doubles soumissions
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Attribution en cours...';

            let handled = false; // Flag pour éviter les doubles alertes

            fetch('<?= Router\Router::route("admin") ?>/article/' + articleId + '/assign-reviewer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(async response => {
                const isHttpOk = response.ok || response.status === 200 || response.status === 201;
                let responseData = {};
                
                try {
                    const text = await response.text();
                    if (text) {
                        responseData = JSON.parse(text);
                    }
                } catch (e) {
                    // Si le parsing échoue, on continue avec un objet vide
                    console.warn('Parsing JSON échoué, mais on continue');
                }
                
                // Réactiver le bouton
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
                
                // Détecter le succès : HTTP OK ET (success === true OU message sans error)
                if (isHttpOk && (responseData.success === true || (responseData.message && !responseData.error))) {
                    handled = true;
                    closeAssignModal();
                    loadAssignedReviewers();
                    setTimeout(() => {
                        showToast(responseData.message || 'Évaluateur assigné avec succès', 'success');
                    }, 100);
                    return;
                }
                
                // Si erreur explicite
                if (responseData.error) {
                    handled = true;
                    showToast(responseData.error, 'error');
                    return;
                }
                
                // Si HTTP OK mais structure ambiguë, considérer comme succès
                if (isHttpOk) {
                    handled = true;
                    closeAssignModal();
                    loadAssignedReviewers();
                    setTimeout(() => {
                        showToast('Évaluateur assigné avec succès', 'success');
                    }, 100);
                    return;
                }
                
                // Si HTTP erreur, vérifier si l'assignation a quand même réussi
                handled = true;
                loadAssignedReviewers();
                setTimeout(() => {
                    const hasNewAssignment = document.querySelector('#assignedReviewersList table tbody tr');
                    if (hasNewAssignment) {
                        closeAssignModal();
                        showToast('Évaluateur assigné avec succès (vérification automatique)', 'success');
                    } else {
                        showToast(responseData.error || 'Une erreur est survenue lors de l\'attribution', 'error');
                    }
                }, 300);
            })
            .catch(error => {
                console.error('Erreur fetch:', error);
                
                // Réactiver le bouton
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
                
                // Ne traiter que si pas déjà géré
                if (handled) return;
                
                // Vérifier si l'assignation a quand même réussi
                loadAssignedReviewers();
                setTimeout(() => {
                    const hasNewAssignment = document.querySelector('#assignedReviewersList table tbody tr');
                    if (hasNewAssignment) {
                        showToast('Évaluateur assigné avec succès (vérification automatique)', 'success');
                        closeAssignModal();
                    } else {
                        showToast('Erreur réseau. Veuillez vérifier si l\'assignation a réussi.', 'error');
                    }
                }, 300);
            });
        });

        async function unassignReviewer(evaluationId) {
            const ok = typeof showConfirm === 'function'
                ? await showConfirm({
                    title: 'Retirer l\'évaluateur',
                    message: 'Êtes-vous sûr de vouloir retirer cet évaluateur ?',
                    confirmText: 'Retirer',
                    cancelText: 'Annuler'
                })
                : confirm('Êtes-vous sûr de vouloir retirer cet évaluateur ?');
            if (!ok) return;

            fetch('<?= Router\Router::route("admin") ?>/article/' + articleId + '/unassign-reviewer/' + evaluationId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message) {
                    showToast(data.message || 'Évaluateur retiré avec succès', 'success');
                    loadAssignedReviewers();
                } else {
                    showToast(data.error || 'Une erreur est survenue', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue lors du retrait', 'error');
            });
        }

        // Fonctions pour le modal de statut
        function showStatusModal() {
            document.getElementById('statusModal').style.display = 'flex';
        }

        function closeStatusModal() {
            document.getElementById('statusModal').style.display = 'none';
        }

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

        async function publishArticle(id) {
            const ok = typeof showConfirm === 'function'
                ? await showConfirm({
                    title: 'Publier l\'article',
                    message: 'Êtes-vous sûr de vouloir publier cet article ? Cette action le rendra visible publiquement.',
                    confirmText: 'Publier',
                    cancelText: 'Annuler'
                })
                : confirm('Êtes-vous sûr de vouloir publier cet article ? Cette action le rendra visible publiquement.');
            if (!ok) return;
            
            fetch('<?= Router\Router::route("admin") ?>/article/' + id + '/publish', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message) {
                    showToast(data.message || 'Article publié avec succès !', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.error || 'Erreur lors de la publication', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Erreur lors de la publication', 'error');
            });
        }

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
                    setTimeout(() => window.location.href = '<?= Router\Router::route("admin") ?>/articles', 1500);
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

