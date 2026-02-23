<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volume <?= htmlspecialchars($volume['annee']) ?> - Admin</title>
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
                    <h1><?= htmlspecialchars($volume['numero_volume'] ?? 'Volume ' . $volume['annee']) ?> (<?= htmlspecialchars($volume['annee']) ?>)</h1>
                    <?php if (!empty($volume['description'])): ?>
                        <p><?= htmlspecialchars($volume['description']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="header-actions">
                    <button class="btn btn-outline" onclick="editVolume(<?= $volume['id'] ?>)">Modifier</button>
                    <button class="btn btn-primary" onclick="showCreateIssueModal(<?= $volume['id'] ?>)">+ Ajouter un Numéro</button>
                </div>
            </div>

            <div class="content-card fade-up">
                <h2 style="margin-top: 0;">Numéros de ce volume</h2>
                
                <?php if (!empty($issues)): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Numéro</th>
                                <th>Titre</th>
                                <th>Date Publication</th>
                                <th>Articles</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($issues as $issue): ?>
                                <tr>
                                    <td><?= htmlspecialchars($issue['numero'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($issue['titre'] ?? '—') ?></td>
                                    <td><?= !empty($issue['date_publication']) ? htmlspecialchars($issue['date_publication']) : '—' ?></td>
                                    <td><?= htmlspecialchars($issue['article_count'] ?? 0) ?> article(s)</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn view" title="Voir" onclick="window.location.href='<?= Router\Router::route('admin') ?>/numero/<?= $issue['id'] ?>'">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button class="action-btn edit" title="Modifier" onclick="editIssue(<?= $issue['id'] ?>)">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; padding: 2rem; color: var(--color-gray-600);">
                        Aucun numéro dans ce volume.
                    </p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Modal Créer Numéro -->
    <div id="createIssueModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; overflow-y: auto; padding: 1rem;">
        <div style="background: white; padding: 1.25rem; border-radius: 8px; max-width: 450px; width: 100%; max-height: 90vh; overflow-y: auto; margin: auto;">
            <h3 style="margin-top: 0; margin-bottom: 1rem; font-size: 1.1rem;">Créer un Numéro</h3>
            <form id="createIssueForm">
                <input type="hidden" name="volume_id" id="createIssueVolumeId" value="<?= $volume['id'] ?>">
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Numéro *</label>
                    <input type="text" name="numero" required placeholder="Ex: Numéro 1" style="padding: 0.5rem; font-size: 0.875rem;">
                </div>
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Titre *</label>
                    <input type="text" name="titre" required style="padding: 0.5rem; font-size: 0.875rem;">
                </div>
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Description</label>
                    <textarea name="description" rows="2" style="padding: 0.5rem; font-size: 0.875rem;"></textarea>
                </div>
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Date de Publication</label>
                    <input type="date" name="date_publication" style="padding: 0.5rem; font-size: 0.875rem;">
                </div>
                <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1rem;">
                    <button type="button" class="btn btn-outline" onclick="closeCreateIssueModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Annuler</button>
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Créer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Modifier Volume -->
    <div id="editVolumeModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; overflow-y: auto; padding: 1rem;">
        <div style="background: white; padding: 1.25rem; border-radius: 8px; max-width: 450px; width: 100%; max-height: 90vh; overflow-y: auto; margin: auto;">
            <h3 style="margin-top: 0; margin-bottom: 1rem; font-size: 1.1rem;">Modifier le Volume</h3>
            <form id="editVolumeForm">
                <input type="hidden" name="volume_id" id="editVolumeId" value="<?= $volume['id'] ?>">
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Numéro du Volume</label>
                    <input type="text" name="numero_volume" id="editVolumeNumero" value="<?= htmlspecialchars($volume['numero_volume'] ?? '') ?>" placeholder="Ex: Volume 28" style="padding: 0.5rem; font-size: 0.875rem;">
                </div>
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Description</label>
                    <textarea name="description" id="editVolumeDescription" rows="2" style="padding: 0.5rem; font-size: 0.875rem;"><?= htmlspecialchars($volume['description'] ?? '') ?></textarea>
                </div>
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Rédacteur en chef (<?= htmlspecialchars($volume['annee']) ?>)</label>
                    <input type="text" name="redacteur_chef" id="editVolumeRedacteurChef" value="<?= htmlspecialchars($volume['redacteur_chef'] ?? '') ?>" placeholder="Ex: Prof. Nom Prénom" style="padding: 0.5rem; font-size: 0.875rem;">
                </div>
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Comité éditorial (<?= htmlspecialchars($volume['annee']) ?>)</label>
                    <textarea name="comite_editorial" id="editVolumeComiteEditorial" rows="6" placeholder="Membres du comité (un par ligne ou texte libre)..." style="padding: 0.5rem; font-size: 0.875rem;"><?= htmlspecialchars($volume['comite_editorial'] ?? '') ?></textarea>
                </div>
                <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1rem;">
                    <button type="button" class="btn btn-outline" onclick="closeEditVolumeModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Annuler</button>
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Modifier Numéro -->
    <div id="editIssueModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; overflow-y: auto; padding: 1rem;">
        <div style="background: white; padding: 1.25rem; border-radius: 8px; max-width: 450px; width: 100%; max-height: 90vh; overflow-y: auto; margin: auto;">
            <h3 style="margin-top: 0; margin-bottom: 1rem; font-size: 1.1rem;">Modifier le Numéro</h3>
            <form id="editIssueForm">
                <input type="hidden" name="issue_id" id="editIssueId">
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Numéro *</label>
                    <input type="text" name="numero" id="editIssueNumero" required style="padding: 0.5rem; font-size: 0.875rem;">
                </div>
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Titre *</label>
                    <input type="text" name="titre" id="editIssueTitre" required style="padding: 0.5rem; font-size: 0.875rem;">
                </div>
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Description</label>
                    <textarea name="description" id="editIssueDescription" rows="2" style="padding: 0.5rem; font-size: 0.875rem;"></textarea>
                </div>
                <div class="form-field" style="margin-bottom: 0.875rem;">
                    <label style="font-size: 0.875rem; margin-bottom: 0.375rem;">Date de Publication</label>
                    <input type="date" name="date_publication" id="editIssueDatePublication" style="padding: 0.5rem; font-size: 0.875rem;">
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
        .content-card .card-header { padding: 0.9rem 1rem; flex-wrap: wrap; gap: 0.75rem; }
        .content-card .card-header h2 { font-size: 1.1rem; }
        .data-table th, .data-table td { padding: 0.6rem 0.75rem; font-size: 0.92rem; }
        .action-buttons { gap: 0.4rem; }
        .action-btn { width: 34px; height: 34px; }
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.9rem; }
    </style>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/author-notify.js"></script>
    <script>
        function showCreateIssueModal(volumeId) {
            document.getElementById('createIssueVolumeId').value = volumeId;
            document.getElementById('createIssueModal').style.display = 'flex';
        }

        function closeCreateIssueModal() {
            document.getElementById('createIssueModal').style.display = 'none';
            document.getElementById('createIssueForm').reset();
        }

        function editVolume(id) {
            document.getElementById('editVolumeModal').style.display = 'flex';
        }

        function closeEditVolumeModal() {
            document.getElementById('editVolumeModal').style.display = 'none';
        }

        function editIssue(id) {
            const row = event.target.closest('tr');
            const numero = row.cells[0].textContent.trim();
            const titre = row.cells[1].textContent.trim();
            
            document.getElementById('editIssueId').value = id;
            document.getElementById('editIssueNumero').value = numero;
            document.getElementById('editIssueTitre').value = titre;
            document.getElementById('editIssueDescription').value = '';
            document.getElementById('editIssueDatePublication').value = '';
            document.getElementById('editIssueModal').style.display = 'flex';
        }

        function closeEditIssueModal() {
            document.getElementById('editIssueModal').style.display = 'none';
            document.getElementById('editIssueForm').reset();
        }

        // Formulaire créer numéro
        document.getElementById('createIssueForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Création...';
            
            fetch('<?= Router\Router::route("admin") ?>/issues/create', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return response.text().then(text => {
                        console.error('Réponse non-JSON:', text);
                        throw new Error('Le serveur a retourné une erreur HTML. Vérifiez les logs du serveur.');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast('Numéro créé avec succès !', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('Erreur : ' + (data.error || 'Une erreur est survenue'), 'error');
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Erreur : ' + error.message, 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });

        // Formulaire modifier volume
        document.getElementById('editVolumeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('<?= Router\Router::route("admin") ?>/volumes/update', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Volume mis à jour avec succès !', 'success');
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
</body>
</html>

