<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Utilisateur - Admin</title>
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
                    <a href="<?= Router\Router::route("admin") ?>/users" class="back-link" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-blue); text-decoration: none; margin-bottom: 0.5rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        Retour aux utilisateurs
                    </a>
                    <h1>Détails de l'utilisateur</h1>
                    <p>Informations complètes sur le compte</p>
                </div>
                <div class="header-actions">
                    <button onclick="editUser(<?= $userDetail['id'] ?? '' ?>)" class="btn btn-primary">Modifier</button>
                    <button onclick="confirmDeleteUser(<?= $userDetail['id'] ?? '' ?>)" class="btn btn-outline" style="color: var(--color-red); border-color: var(--color-red);" type="button">
                        Supprimer
                    </button>
                </div>
            </div>

            <?php if (isset($userDetail) && $userDetail): ?>
                <div class="content-card fade-up">
                    <div class="card-header">
                        <h2><?= htmlspecialchars(trim(($userDetail['prenom'] ?? '') . ' ' . ($userDetail['nom'] ?? ''))) ?></h2>
                        <div style="display: flex; gap: 0.5rem;">
                            <span class="status-badge <?= 
                                ($userDetail['statut'] ?? '') === 'actif' ? 'accepted' : 
                                (($userDetail['statut'] ?? '') === 'suspendu' ? 'rejected' : 'pending')
                            ?>">
                                <?= htmlspecialchars(ucfirst($userDetail['statut'] ?? '')) ?>
                            </span>
                            <span class="status-badge" style="background: var(--color-blue);">
                                <?= htmlspecialchars(ucfirst($userDetail['role'] ?? 'user')) ?>
                            </span>
                        </div>
                    </div>

                    <div class="article-details-content">
                        <div class="detail-section">
                            <h3>Informations personnelles</h3>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <label>ID</label>
                                    <p><?= htmlspecialchars($userDetail['id']) ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Nom</label>
                                    <p><?= htmlspecialchars($userDetail['nom'] ?? '') ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Prénom</label>
                                    <p><?= htmlspecialchars($userDetail['prenom'] ?? '') ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Email</label>
                                    <p><?= htmlspecialchars($userDetail['email'] ?? '') ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Rôle</label>
                                    <p><?= htmlspecialchars(ucfirst($userDetail['role'] ?? 'user')) ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Statut</label>
                                    <p><?= htmlspecialchars(ucfirst($userDetail['statut'] ?? 'actif')) ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Date de création</label>
                                    <p><?= !empty($userDetail['created_at']) ? date('d M Y à H:i', strtotime($userDetail['created_at'])) : '—' ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Dernière modification</label>
                                    <p><?= !empty($userDetail['updated_at']) ? date('d M Y à H:i', strtotime($userDetail['updated_at'])) : '—' ?></p>
                                </div>
                            </div>
                        </div>

                        <?php if (isset($stats) && $stats): ?>
                            <div class="detail-section">
                                <h3>Statistiques</h3>
                                <div class="detail-grid">
                                    <div class="detail-item">
                                        <label>Articles soumis</label>
                                        <p><?= $stats['articles_count'] ?? 0 ?></p>
                                    </div>
                                    <div class="detail-item">
                                        <label>Articles validés</label>
                                        <p><?= $stats['validated_articles_count'] ?? 0 ?></p>
                                    </div>
                                    <div class="detail-item">
                                        <label>Commentaires</label>
                                        <p><?= $stats['comments_count'] ?? 0 ?></p>
                                    </div>
                                    <div class="detail-item">
                                        <label>Téléchargements</label>
                                        <p><?= $stats['downloads_count'] ?? 0 ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
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

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/author-notify.js"></script>
    <script>
        function editUser(id) {
            window.location.href = '<?= Router\Router::route("admin") ?>/users';
            setTimeout(() => {
                const event = new CustomEvent('editUser', { detail: { id: id } });
                window.dispatchEvent(event);
            }, 100);
        }

        function deleteUser(id) {
            fetch('<?= Router\Router::route("admin") ?>/user/' + id + '/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message) {
                    showToast(data.message || 'Utilisateur supprimé avec succès', 'success');
                    setTimeout(() => window.location.href = '<?= Router\Router::route("admin") ?>/users', 1500);
                } else {
                    showToast(data.error || 'Une erreur est survenue', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue', 'error');
            });
        }

        async function confirmDeleteUser(id) {
            const ok = typeof showConfirm === 'function'
                ? await showConfirm({
                    title: 'Supprimer l\'utilisateur',
                    message: 'Êtes-vous sûr de vouloir supprimer cet utilisateur ?',
                    confirmText: 'Supprimer',
                    cancelText: 'Annuler'
                })
                : confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');
            if (!ok) return;
            deleteUser(id);
        }
    </script>
</body>
</html>

