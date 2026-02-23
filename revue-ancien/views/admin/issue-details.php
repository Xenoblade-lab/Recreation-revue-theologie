<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($issue['titre'] ?? 'Numéro') ?> - Admin</title>
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
                    <a href="<?= Router\Router::route('admin') ?>/volumes" style="color: var(--color-gray-600); text-decoration: none; margin-bottom: 0.5rem; display: inline-block;">
                        ← Retour aux volumes
                    </a>
                    <h1><?= htmlspecialchars($issue['titre'] ?? 'Numéro') ?></h1>
                    <p>
                        <?= htmlspecialchars($issue['numero'] ?? '—') ?> 
                        <?php if (!empty($issue['annee'])): ?>
                            - <?= htmlspecialchars($issue['annee']) ?>
                        <?php endif; ?>
                    </p>
                    <?php if (!empty($issue['description'])): ?>
                        <p style="margin-top: 0.5rem; color: var(--color-gray-600);"><?= nl2br(htmlspecialchars($issue['description'])) ?></p>
                    <?php endif; ?>
                </div>
                <div class="header-actions">
                    <button class="btn btn-primary" onclick="showAssignArticleModal(<?= $issue['id'] ?>)">+ Ajouter un article</button>
                    <button class="btn btn-outline" onclick="editIssue(<?= $issue['id'] ?>)">Modifier</button>
                    <?php if (!empty($issue['fichier_path'])): ?>
                        <a href="<?= Router\Router::$defaultUri . htmlspecialchars($issue['fichier_path']) ?>" 
                           class="btn btn-outline" target="_blank">
                            Télécharger PDF
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="content-card fade-up">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h2 style="margin-top: 0;">Articles de ce numéro</h2>
                    <button class="btn btn-primary btn-sm" onclick="showAssignArticleModal(<?= $issue['id'] ?>)">+ Ajouter un article</button>
                </div>
                
                <?php if (!empty($articles)): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Auteur</th>
                                <th>Date Publication</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($articles as $article): ?>
                                <tr>
                                    <td><?= htmlspecialchars($article['id'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($article['titre'] ?? 'Sans titre') ?></td>
                                    <td>
                                        <?= htmlspecialchars(trim(($article['auteur_prenom'] ?? '') . ' ' . ($article['auteur_nom'] ?? ''))) ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($article['date_soumission'])): ?>
                                            <?= date('d M Y', strtotime($article['date_soumission'])) ?>
                                        <?php else: ?>
                                            —
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower($article['statut'] ?? '') ?>">
                                            <?= htmlspecialchars($article['statut'] ?? '—') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn view" title="Voir" onclick="window.location.href='<?= Router\Router::route('admin') ?>/article/<?= $article['id'] ?>'">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <?php if (!empty($article['fichier_path'])): ?>
                                                <a href="<?= Router\Router::$defaultUri . htmlspecialchars($article['fichier_path']) ?>" 
                                                   class="action-btn download" 
                                                   title="Télécharger" 
                                                   target="_blank">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; padding: 2rem; color: var(--color-gray-600);">
                        Aucun article disponible dans ce numéro.
                    </p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Modal Assigner Article -->
    <div id="assignArticleModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; overflow-y: auto; padding: 1rem;">
        <div style="background: white; padding: 1.25rem; border-radius: 8px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; margin: auto;">
            <h3 style="margin-top: 0; margin-bottom: 1rem; font-size: 1.1rem;">Assigner un article à ce numéro</h3>
            <form id="assignArticleForm">
                <input type="hidden" name="issue_id" id="assignArticleIssueId" value="<?= $issue['id'] ?>">
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Sélectionner un article *</label>
                    <select name="article_id" id="assignArticleArticleId" required style="padding: 0.5rem; font-size: 0.875rem; width: 100%;">
                        <option value="">-- Choisir un article --</option>
                        <?php if (!empty($unassignedArticles)): ?>
                            <?php foreach ($unassignedArticles as $article): ?>
                                <option value="<?= $article['id'] ?>">
                                    <?= htmlspecialchars($article['titre']) ?> 
                                    (<?= htmlspecialchars(trim(($article['auteur_prenom'] ?? '') . ' ' . ($article['auteur_nom'] ?? ''))) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (empty($unassignedArticles)): ?>
                        <p style="font-size: 0.875rem; color: var(--color-gray-600); margin-top: 0.5rem;">
                            Aucun article disponible (tous les articles publiés sont déjà assignés à un numéro).
                        </p>
                    <?php endif; ?>
                </div>
                <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1rem;">
                    <button type="button" class="btn btn-outline" onclick="closeAssignArticleModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Annuler</button>
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;" <?= empty($unassignedArticles) ? 'disabled' : '' ?>>Assigner</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Modifier Numéro -->
    <div id="editIssueModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; overflow-y: auto; padding: 1rem;">
        <div style="background: white; padding: 1.25rem; border-radius: 8px; max-width: 450px; width: 100%; max-height: 90vh; overflow-y: auto; margin: auto;">
            <h3 style="margin-top: 0; margin-bottom: 1rem; font-size: 1.1rem;">Modifier le Numéro</h3>
            <form id="editIssueForm">
                <input type="hidden" name="issue_id" id="editIssueId" value="<?= $issue['id'] ?>">
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Numéro *</label>
                    <input type="text" name="numero" id="editIssueNumero" value="<?= htmlspecialchars($issue['numero'] ?? '') ?>" required style="padding: 0.5rem; font-size: 0.875rem;">
                </div>
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Titre *</label>
                    <input type="text" name="titre" id="editIssueTitre" value="<?= htmlspecialchars($issue['titre'] ?? '') ?>" required style="padding: 0.5rem; font-size: 0.875rem;">
                </div>
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Description</label>
                    <textarea name="description" id="editIssueDescription" rows="2" style="padding: 0.5rem; font-size: 0.875rem;"><?= htmlspecialchars($issue['description'] ?? '') ?></textarea>
                </div>
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Date de Publication</label>
                    <input type="date" name="date_publication" id="editIssueDatePublication" value="<?= !empty($issue['date_publication']) ? date('Y-m-d', strtotime($issue['date_publication'])) : '' ?>" style="padding: 0.5rem; font-size: 0.875rem;">
                </div>
                <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1rem;">
                    <button type="button" class="btn btn-outline" onclick="closeEditIssueModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Annuler</button>
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

    <style>
        .dashboard-main { padding: 1.25rem; overflow-x: hidden; }
        .dashboard-header { margin-bottom: 1rem; flex-wrap: wrap; gap: 0.75rem; }
        .dashboard-header .header-title h1 { font-size: 1.5rem; line-height: 1.2; margin-bottom: 0.25rem; }
        .dashboard-header .header-title p { font-size: 0.95rem; }
        .content-card { padding: 1rem; }
        .content-card h2 { font-size: 1.1rem; margin-bottom: 1rem; }
        .data-table th, .data-table td { padding: 0.6rem 0.75rem; font-size: 0.92rem; }
        .action-buttons { gap: 0.4rem; }
        .action-btn { width: 34px; height: 34px; }
        .status-badge { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem; font-weight: 500; }
    </style>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/author-notify.js"></script>
    <script>
        function showAssignArticleModal(issueId) {
            document.getElementById('assignArticleIssueId').value = issueId;
            document.getElementById('assignArticleModal').style.display = 'flex';
        }

        function closeAssignArticleModal() {
            document.getElementById('assignArticleModal').style.display = 'none';
            document.getElementById('assignArticleForm').reset();
        }

        function editIssue(id) {
            document.getElementById('editIssueModal').style.display = 'flex';
        }

        function closeEditIssueModal() {
            document.getElementById('editIssueModal').style.display = 'none';
        }

        // Formulaire assigner article
        document.getElementById('assignArticleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Assignation...';
            
            const articleId = formData.get('article_id');
            const issueId = formData.get('issue_id');
            
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

        // Formulaire modifier numéro
        document.getElementById('editIssueForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('<?= Router\Router::route("admin") ?>/issues/update', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Numéro mis à jour avec succès !', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('Erreur : ' + (data.error || 'Une erreur est survenue'), 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue', 'error');
            });
        });
    </script>
</body>
</html>

