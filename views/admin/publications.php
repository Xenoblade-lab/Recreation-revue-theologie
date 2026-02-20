<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publications - Admin</title>
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
                    <p>Gestion des articles publiés</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-outline" onclick="window.location.reload()">Actualiser</button>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="stats-grid" style="margin-bottom: var(--spacing-xl);">
                <div class="stat-card fade-up">
                    <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#7c3aed" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="stat-value"><?= $stats['total'] ?? 0 ?></div>
                    <div class="stat-label">Total publiés</div>
                </div>
                <div class="stat-card fade-up">
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#059669" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-value"><?= $stats['ce_mois'] ?? 0 ?></div>
                    <div class="stat-label">Ce mois</div>
                </div>
                <div class="stat-card fade-up">
                    <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#2563eb" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-value"><?= $stats['en_attente'] ?? 0 ?></div>
                    <div class="stat-label">Acceptés (non publiés)</div>
                </div>
            </div>

            <div class="content-card fade-up">
                <div class="card-header">
                    <h2>Articles publiés</h2>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <input type="text" id="search-input" placeholder="Rechercher..." style="padding: 0.5rem; border-radius: 4px; border: 1px solid var(--color-gray-300); width: 250px;">
                    </div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Date de publication</th>
                            <th>Date de soumission</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($publications) && !empty($publications)): ?>
                            <?php foreach ($publications as $pub): ?>
                                <?php
                                $statut = strtolower($pub['statut'] ?? '');
                                $statutClass = 'published';
                                if (strpos($statut, 'publ') === false) {
                                    $statutClass = 'accepted';
                                }
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($pub['id']) ?></td>
                                    <td><?= htmlspecialchars($pub['titre']) ?></td>
                                    <td><?= htmlspecialchars(($pub['auteur_prenom'] ?? '') . ' ' . ($pub['auteur_nom'] ?? '')) ?></td>
                                    <td>
                                        <?php if (!empty($pub['date_publication'])): ?>
                                            <?= date('d M Y', strtotime($pub['date_publication'])) ?>
                                        <?php else: ?>
                                            <?= date('d M Y', strtotime($pub['updated_at'])) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d M Y', strtotime($pub['date_soumission'] ?? $pub['created_at'])) ?></td>
                                    <td><span class="status-badge <?= $statutClass ?>"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $statut))) ?></span></td>
                                    <td>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <a href="<?= Router\Router::route("admin") ?>/article/<?= $pub['id'] ?>" class="btn btn-outline btn-sm">Voir</a>
                                            <?php if (strpos($statut, 'publ') === false && strpos($statut, 'accept') !== false): ?>
                                                <button onclick="publishArticle(<?= $pub['id'] ?>)" class="btn btn-primary btn-sm">Publier</button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem; color: var(--color-gray-500);">
                                    Aucune publication trouvée
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
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/author-notify.js"></script>
    <script>
        // Recherche
        document.getElementById('search-input')?.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.data-table tbody tr');
            
            rows.forEach(row => {
                if (row.cells.length < 7) return; // Skip empty rows
                
                const title = row.cells[1].textContent.toLowerCase();
                const author = row.cells[2].textContent.toLowerCase();
                
                row.style.display = (title.includes(searchTerm) || author.includes(searchTerm)) ? '' : 'none';
            });
        });
        
        async function publishArticle(id) {
            const ok = typeof showConfirm === 'function'
                ? await showConfirm({
                    title: 'Publier l\'article',
                    message: 'Êtes-vous sûr de vouloir publier cet article ?',
                    confirmText: 'Publier',
                    cancelText: 'Annuler'
                })
                : confirm('Êtes-vous sûr de vouloir publier cet article ?');
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
    </script>
</body>
</html>

