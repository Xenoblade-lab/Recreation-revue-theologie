<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'article - Dashboard Auteur</title>
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
                    <a href="<?= Router\Router::route("author") ?>/article/<?= isset($article) ? $article['id'] : '' ?>" class="back-link" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-blue); text-decoration: none; margin-bottom: 0.5rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        Retour aux détails
                    </a>
                    <h1>Modifier l'article</h1>
                    <p>Modifiez les informations de votre soumission</p>
                </div>
            </div>

            <?php if (isset($article) && $article): ?>
                <!-- Edit Form -->
                <div class="content-card fade-up">
                    <form class="auth-form" method="post" action="<?= Router\Router::route("author") ?>/article/<?= $article['id'] ?>/update" enctype="multipart/form-data" id="article-edit-form">
                        <div class="form-section">
                            <h3>Informations de l'article</h3>
                            <div class="form-field">
                                <label>Titre de l'article *</label>
                                <input type="text" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required>
                            </div>
                            <div class="form-field">
                                <label>Résumé (250 mots max) *</label>
                                <textarea name="contenu" rows="6" required><?= htmlspecialchars($article['contenu']) ?></textarea>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Fichier</h3>
                            <?php if (!empty($article['fichier_path'])): ?>
                                <div class="form-field">
                                    <label>Fichier actuel</label>
                                    <div class="file-info" style="padding: 1rem; background: var(--color-gray-50); border-radius: 8px; display: flex; align-items: center; gap: 1rem;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                        </svg>
                                        <div style="flex: 1;">
                                            <p><strong><?= htmlspecialchars(basename($article['fichier_path'])) ?></strong></p>
                                            <a href="<?= Router\Router::$defaultUri . htmlspecialchars($article['fichier_path']) ?>" target="_blank" style="color: var(--color-blue); font-size: 0.875rem;">Télécharger</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="form-field">
                                <label><?= !empty($article['fichier_path']) ? 'Remplacer le fichier' : 'Nouveau fichier' ?> (PDF, Word ou LaTeX)</label>
                                <div class="file-upload">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p id="file-name-display-edit">Glissez-déposez votre fichier ici ou</p>
                                    <span>Parcourir</span>
                                    <input type="file" name="fichier" id="fichier-input-edit" accept=".pdf,.doc,.docx,.tex">
                                </div>
                                <small style="color: var(--color-gray-600); margin-top: 0.5rem; display: block;">Laisser vide pour conserver le fichier actuel</small>
                            </div>
                        </div>

                        <div style="display: flex; gap: var(--spacing-md); justify-content: flex-end; margin-top: var(--spacing-lg);">
                            <a href="<?= Router\Router::route("author") ?>/article/<?= $article['id'] ?>" class="btn btn-outline">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="content-card fade-up">
                    <div class="empty-state">
                        <h3>Article introuvable</h3>
                        <p>L'article que vous souhaitez modifier n'existe pas ou vous n'avez pas les droits pour le modifier.</p>
                        <a href="<?= Router\Router::route("author") ?>" class="btn btn-primary">Retour au tableau de bord</a>
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
        // Gérer l'affichage du nom du fichier
        document.getElementById('fichier-input-edit')?.addEventListener('change', function(e) {
            const fileNameDisplay = document.getElementById('file-name-display-edit');
            if (this.files.length > 0) {
                fileNameDisplay.textContent = 'Fichier sélectionné : ' + this.files[0].name;
                fileNameDisplay.style.color = 'var(--color-blue)';
                fileNameDisplay.style.fontWeight = '600';
            } else {
                fileNameDisplay.textContent = 'Glissez-déposez votre fichier ici ou';
                fileNameDisplay.style.color = '';
                fileNameDisplay.style.fontWeight = '';
            }
        });

        // Gérer la soumission du formulaire
        document.getElementById('article-edit-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validation côté client
            const titre = this.querySelector('input[name="titre"]').value.trim();
            const contenu = this.querySelector('textarea[name="contenu"]').value.trim();
            
            if (!titre) {
                showToast('Le titre est requis', 'error');
                return;
            }
            
            if (!contenu) {
                showToast('Le résumé est requis', 'error');
                return;
            }
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enregistrement...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // Vérifier si la réponse est du JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // Si ce n'est pas du JSON, lire le texte et essayer de le parser
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            throw new Error('Réponse invalide du serveur: ' + text.substring(0, 200));
                        }
                    });
                }
            })
            .then(data => {
                if (data.success || data.message) {
                    showToast(data.message || 'Article modifié avec succès !', 'success');
                    window.location.href = '<?= Router\Router::route("author") ?>/article/<?= isset($article) ? $article['id'] : '' ?>';
                } else {
                    const errorMsg = data.error || data.message || 'Une erreur est survenue lors de la modification';
                    showToast(errorMsg, 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                const errorMsg = error.message || 'Une erreur est survenue lors de la modification de l\'article';
                showToast('Erreur: ' + errorMsg, 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    </script>
</body>
</html>

