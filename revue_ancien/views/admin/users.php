<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs - Admin</title>
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
                    <h1>Utilisateurs</h1>
                    <p>Gestion des comptes</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-outline" onclick="showAddUserModal()">+ Ajouter un utilisateur</button>
                    <button class="btn btn-primary" onclick="showAddEvaluatorModal()">+ Créer un évaluateur</button>
                </div>
            </div>

            <div class="content-card fade-up">
                <div class="card-header">
                    <h2>Liste des utilisateurs</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Créé le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $u): ?>
                                <tr data-user-id="<?= $u['id'] ?>" data-role="<?= htmlspecialchars($u['role'] ?? 'user') ?>" data-statut="<?= htmlspecialchars($u['statut'] ?? 'actif') ?>">
                                    <td><?= htmlspecialchars($u['id']) ?></td>
                                    <td><?= htmlspecialchars(trim(($u['prenom'] ?? '') . ' ' . ($u['nom'] ?? ''))) ?></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td><span class="status-badge" style="background: var(--color-blue); color: white; font-weight: 500;"><?= htmlspecialchars(ucfirst($u['role'] ?? 'user')) ?></span></td>
                                    <td><span class="status-badge <?= 
                                        ($u['statut'] ?? '') === 'actif' ? 'accepted' : 
                                        (($u['statut'] ?? '') === 'suspendu' ? 'rejected' : 'pending')
                                    ?>"><?= htmlspecialchars($u['statut'] ?? '') ?></span></td>
                                    <td><?= !empty($u['created_at']) ? date('d M Y', strtotime($u['created_at'])) : '—' ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn view" title="Voir les détails" onclick="window.location.href='<?= Router\Router::route('admin') ?>/user/<?= $u['id'] ?>'">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button class="action-btn edit" title="Modifier" onclick="editUser(<?= $u['id'] ?>)">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button class="action-btn delete" title="Supprimer" type="button" onclick="confirmDeleteUser(<?= $u['id'] ?>)">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align:center; padding:1.5rem;">Aucun utilisateur.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <button class="mobile-menu-btn" id="mobile-menu-btn">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <style>
        /* Mode compact (admin/users) */
        .dashboard-main { padding: 1.25rem; overflow-x: hidden; }

        .dashboard-header { margin-bottom: 1rem; flex-wrap: wrap; gap: 0.75rem; }
        .dashboard-header .header-title h1 { font-size: 1.5rem; line-height: 1.2; margin-bottom: 0.25rem; }
        .dashboard-header .header-title p { font-size: 0.95rem; }

        .dashboard-header .header-actions { gap: 0.75rem; flex-wrap: wrap; justify-content: flex-end; }
        .dashboard-header .header-actions .btn { padding: 0.5rem 0.85rem; font-size: 0.92rem; }

        .content-card .card-header { padding: 0.9rem 1rem; flex-wrap: wrap; gap: 0.75rem; }
        .content-card .card-header h2 { font-size: 1.1rem; }

        .data-table th, .data-table td { padding: 0.6rem 0.75rem; font-size: 0.92rem; }

        .action-buttons { gap: 0.4rem; }
        .action-btn { width: 34px; height: 34px; }

        /* Éviter que le tableau déborde sur petits écrans */
        .content-card { overflow-x: auto; }
        .data-table { min-width: 980px; }

        /* Modals : un peu moins “gros” */
        #addUserModal > div,
        #addEvaluatorModal > div,
        #editUserModal > div {
            padding: 1.5rem !important;
            max-width: 560px !important;
        }

        @media (max-width: 768px) {
            .dashboard-main { padding: 1rem; }
            .dashboard-header .header-actions { width: 100%; justify-content: flex-start; }
            .data-table { min-width: 900px; }
        }
    </style>

    <!-- Modal Ajouter Utilisateur -->
    <div id="addUserModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; overflow-y: auto;">
        <div style="background: white; padding: 2rem; border-radius: 8px; max-width: 600px; width: 90%; margin: 2rem auto;">
            <h3 style="margin-top: 0;">Ajouter un utilisateur</h3>
            <form id="addUserForm" class="auth-form">
                <div class="form-field">
                    <label>Nom *</label>
                    <input type="text" name="nom" required>
                </div>
                <div class="form-field">
                    <label>Prénom *</label>
                    <input type="text" name="prenom" required>
                </div>
                <div class="form-field">
                    <label>Email *</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-field">
                    <label>Rôle *</label>
                    <select name="role" required>
                        <option value="user">Utilisateur</option>
                        <option value="auteur">Auteur</option>
                        <option value="redacteur">Rédacteur</option>
                        <option value="redacteur en chef">Rédacteur en chef</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>
                <div class="form-field">
                    <label>Mot de passe *</label>
                    <input type="password" name="password" required minlength="8">
                </div>
                <div class="form-field">
                    <label>Confirmer le mot de passe *</label>
                    <input type="password" name="confirm_password" required minlength="8">
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" class="btn btn-outline" onclick="closeAddUserModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Créer Évaluateur -->
    <div id="addEvaluatorModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; overflow-y: auto;">
        <div style="background: white; padding: 2rem; border-radius: 8px; max-width: 600px; width: 90%; margin: 2rem auto;">
            <h3 style="margin-top: 0;">Créer un évaluateur</h3>
            <p style="color: var(--color-gray-600); margin-bottom: 1.5rem;">Les évaluateurs ne peuvent pas s'inscrire eux-mêmes. Créez leur compte ici.</p>
            <form id="addEvaluatorForm" class="auth-form">
                <div class="form-field">
                    <label>Nom *</label>
                    <input type="text" name="nom" required>
                </div>
                <div class="form-field">
                    <label>Prénom *</label>
                    <input type="text" name="prenom" required>
                </div>
                <div class="form-field">
                    <label>Email *</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-field">
                    <label>Mot de passe *</label>
                    <input type="password" name="password" required minlength="8">
                </div>
                <div class="form-field">
                    <label>Confirmer le mot de passe *</label>
                    <input type="password" name="confirm_password" required minlength="8">
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" class="btn btn-outline" onclick="closeAddEvaluatorModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer l'évaluateur</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Modifier Utilisateur -->
    <div id="editUserModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; overflow-y: auto;">
        <div style="background: white; padding: 2rem; border-radius: 8px; max-width: 600px; width: 90%; margin: 2rem auto;">
            <h3 style="margin-top: 0;">Modifier l'utilisateur</h3>
            <form id="editUserForm" class="auth-form">
                <input type="hidden" name="user_id" id="editUserId">
                <div class="form-field">
                    <label>Nom *</label>
                    <input type="text" name="nom" id="editNom" required>
                </div>
                <div class="form-field">
                    <label>Prénom *</label>
                    <input type="text" name="prenom" id="editPrenom" required>
                </div>
                <div class="form-field">
                    <label>Email *</label>
                    <input type="email" name="email" id="editEmail" required>
                </div>
                <div class="form-field">
                    <label>Rôle *</label>
                    <select name="role" id="editRole" required>
                        <option value="user">Utilisateur</option>
                        <option value="auteur">Auteur</option>
                        <option value="redacteur">Rédacteur</option>
                        <option value="redacteur en chef">Rédacteur en chef</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>
                <div class="form-field">
                    <label>Statut *</label>
                    <select name="statut" id="editStatut" required>
                        <option value="actif">Actif</option>
                        <option value="suspendu">Suspendu</option>
                    </select>
                </div>
                <div class="form-field">
                    <label>Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                    <input type="password" name="password" minlength="8">
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" class="btn btn-outline" onclick="closeEditUserModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
    <script>
        // Modals
        function showAddUserModal() {
            document.getElementById('addUserModal').style.display = 'flex';
        }

        function closeAddUserModal() {
            document.getElementById('addUserModal').style.display = 'none';
            document.getElementById('addUserForm').reset();
        }

        function showAddEvaluatorModal() {
            document.getElementById('addEvaluatorModal').style.display = 'flex';
        }

        function closeAddEvaluatorModal() {
            document.getElementById('addEvaluatorModal').style.display = 'none';
            document.getElementById('addEvaluatorForm').reset();
        }

        // Stocker les données utilisateurs pour l'édition
        const usersData = {
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $u): ?>
                    <?= $u['id'] ?>: {
                        nom: <?= json_encode($u['nom'] ?? '') ?>,
                        prenom: <?= json_encode($u['prenom'] ?? '') ?>,
                        email: <?= json_encode($u['email'] ?? '') ?>,
                        role: <?= json_encode($u['role'] ?? 'user') ?>,
                        statut: <?= json_encode($u['statut'] ?? 'actif') ?>
                    },
                <?php endforeach; ?>
            <?php endif; ?>
        };

        function editUser(id) {
            const userData = usersData[id];
            if (userData) {
                document.getElementById('editUserId').value = id;
                document.getElementById('editNom').value = userData.nom || '';
                document.getElementById('editPrenom').value = userData.prenom || '';
                document.getElementById('editEmail').value = userData.email || '';
                document.getElementById('editRole').value = userData.role || 'user';
                document.getElementById('editStatut').value = userData.statut || 'actif';
                document.getElementById('editUserModal').style.display = 'flex';
            } else {
                // Fallback: charger depuis l'API
                fetch('<?= Router\Router::route("admin") ?>/user/' + id + '/json')
                    .then(response => response.json())
                    .then(data => {
                        if (data.user) {
                            document.getElementById('editUserId').value = data.user.id;
                            document.getElementById('editNom').value = data.user.nom || '';
                            document.getElementById('editPrenom').value = data.user.prenom || '';
                            document.getElementById('editEmail').value = data.user.email || '';
                            document.getElementById('editRole').value = data.user.role || 'user';
                            document.getElementById('editStatut').value = data.user.statut || 'actif';
                            document.getElementById('editUserModal').style.display = 'flex';
                        } else {
                            showToast('Erreur lors du chargement des données', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        showToast('Erreur lors du chargement des données', 'error');
                    });
            }
        }

        function closeEditUserModal() {
            document.getElementById('editUserModal').style.display = 'none';
            document.getElementById('editUserForm').reset();
        }

        // Formulaires
        document.getElementById('addUserForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            if (formData.get('password') !== formData.get('confirm_password')) {
                showToast('Les mots de passe ne correspondent pas', 'error');
                return;
            }

            const data = {
                nom: formData.get('nom'),
                prenom: formData.get('prenom'),
                email: formData.get('email'),
                role: formData.get('role'),
                password: formData.get('password')
            };

            fetch('<?= Router\Router::route("admin") ?>/user/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message) {
                    showToast(data.message || 'Utilisateur créé avec succès', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.error || 'Une erreur est survenue', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue', 'error');
            });
        });

        document.getElementById('addEvaluatorForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            if (formData.get('password') !== formData.get('confirm_password')) {
                showToast('Les mots de passe ne correspondent pas', 'error');
                return;
            }

            const data = {
                nom: formData.get('nom'),
                prenom: formData.get('prenom'),
                email: formData.get('email'),
                role: 'redacteur', // Les évaluateurs sont des rédacteurs
                password: formData.get('password')
            };

            fetch('<?= Router\Router::route("admin") ?>/evaluator/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message) {
                    showToast(data.message || 'Évaluateur créé avec succès', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.error || 'Une erreur est survenue', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue', 'error');
            });
        });

        document.getElementById('editUserForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const userId = formData.get('user_id');
            
            const data = {
                nom: formData.get('nom'),
                prenom: formData.get('prenom'),
                email: formData.get('email'),
                role: formData.get('role'),
                statut: formData.get('statut')
            };

            if (formData.get('password')) {
                data.password = formData.get('password');
            }

            fetch('<?= Router\Router::route("admin") ?>/user/' + userId + '/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message) {
                    showToast(data.message || 'Utilisateur mis à jour avec succès', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.error || 'Une erreur est survenue', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue', 'error');
            });
        });

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
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.error || 'Une erreur est survenue', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue', 'error');
            });
        }

        // Fermer les modals en cliquant en dehors
        ['addUserModal', 'addEvaluatorModal', 'editUserModal'].forEach(modalId => {
            document.getElementById(modalId)?.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (modalId === 'addUserModal') closeAddUserModal();
                    else if (modalId === 'addEvaluatorModal') closeAddEvaluatorModal();
                    else if (modalId === 'editUserModal') closeEditUserModal();
                }
            });
        });
    </script>
    <script src="<?= Router\Router::$defaultUri ?>js/author-notify.js"></script>
</body>
</html>

