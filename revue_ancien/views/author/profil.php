<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Dashboard Auteur</title>
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
                    <h1>Mon Profil</h1>
                    <p>Gérez vos informations personnelles et vos préférences</p>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="content-card fade-up">
                <div class="card-header">
                    <h2>Informations personnelles</h2>
                </div>
                <form class="auth-form" method="post" action="<?= Router\Router::route('users') ?>/<?= isset($user) ? $user['id'] : '' ?>/update" id="profileForm">
                    <div class="form-section">
                        <h3>Identité</h3>
                        <div class="form-row">
                            <div class="form-field">
                                <label>Prénom *</label>
                                <input type="text" name="prenom" value="<?= isset($user) ? htmlspecialchars($user['prenom'] ?? '') : '' ?>" required>
                            </div>
                            <div class="form-field">
                                <label>Nom *</label>
                                <input type="text" name="nom" value="<?= isset($user) ? htmlspecialchars($user['nom'] ?? '') : '' ?>" required>
                            </div>
                        </div>
                        <div class="form-field">
                            <label>Email *</label>
                            <input type="email" name="email" value="<?= isset($user) ? htmlspecialchars($user['email'] ?? '') : '' ?>" required>
                        </div>
                        <div class="form-field">
                            <label>Téléphone</label>
                            <input type="tel" name="phone" value="<?= isset($user) ? htmlspecialchars($user['phone'] ?? '') : '' ?>">
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Informations professionnelles</h3>
                        <div class="form-field">
                            <label>Institution / Affiliation</label>
                            <input type="text" name="institution" value="<?= isset($user) ? htmlspecialchars($user['institution'] ?? '') : '' ?>" placeholder="Université, Institution...">
                        </div>
                        <div class="form-field">
                            <label>ORCID (optionnel)</label>
                            <input type="text" name="orcid" value="<?= isset($user) ? htmlspecialchars($user['orcid'] ?? '') : '' ?>" placeholder="0000-0000-0000-0000">
                        </div>
                        <div class="form-field">
                            <label>Biographie</label>
                            <textarea name="biographie" rows="4" placeholder="Parlez-nous de vous..."><?= isset($user) ? htmlspecialchars($user['biographie'] ?? '') : '' ?></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Sécurité</h3>
                        <div class="form-field">
                            <label>Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                            <input type="password" name="password" placeholder="Minimum 8 caractères">
                        </div>
                        <div class="form-field">
                            <label>Confirmer le nouveau mot de passe</label>
                            <input type="password" name="password_confirm" placeholder="Répétez le mot de passe">
                        </div>
                    </div>

                    <div style="display: flex; gap: var(--spacing-md); justify-content: flex-end; margin-top: var(--spacing-lg);">
                        <button type="button" class="btn btn-outline" onclick="window.location.href='<?= Router\Router::route("author") ?>'">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>

            <!-- Account Stats -->
            <?php if (isset($stats)): ?>
                <div class="content-card fade-up">
                    <div class="card-header">
                        <h2>Statistiques du compte</h2>
                    </div>
                    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                        <div class="stat-card">
                            <div class="stat-value"><?= $stats['total_articles'] ?? 0 ?></div>
                            <div class="stat-label">Articles soumis</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value"><?= $stats['publie'] ?? 0 ?></div>
                            <div class="stat-label">Articles publiés</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value"><?= isset($user) && isset($user['created_at']) ? date('Y', strtotime($user['created_at'])) : date('Y') ?></div>
                            <div class="stat-label">Membre depuis</div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/author-notify.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
    <script>
        // Gérer la soumission du formulaire
        document.getElementById('profileForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Vérifier que les mots de passe correspondent
            if (data.password && data.password !== data.password_confirm) {
                showToast('Les mots de passe ne correspondent pas', 'error');
                return;
            }
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    showToast(data.message, data.message.includes('mis à jour') ? 'success' : 'info');
                    if (data.message.includes('mis à jour')) {
                        window.location.reload();
                    }
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

