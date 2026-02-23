<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Auteur - Revue de Théologie UPC</title>
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
                    <h1>Mes soumissions</h1>
                    <p>Suivez l'état de vos articles</p>
                </div>
                <div class="header-actions">
                    <button class="notification-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="notification-badge"></span>
                    </button>
                    <button class="btn btn-primary" onclick="window.location.href='#submit-form'">+ Soumettre un article</button>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card fade-up">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= isset($stats) ? $stats['total'] : 0 ?></div>
                    <div class="stat-label">Articles soumis</div>
                </div>

                <div class="stat-card fade-up">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= isset($stats) ? $stats['en_evaluation'] : 0 ?></div>
                    <div class="stat-label">En évaluation</div>
                </div>

                <div class="stat-card fade-up">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= isset($stats) ? $stats['publie'] : 0 ?></div>
                    <div class="stat-label">Publiés</div>
                </div>

                <div class="stat-card fade-up">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">$150</div>
                    <div class="stat-label">Paiements effectués</div>
                </div>
            </div>

            <!-- Submissions Table -->
            <div class="content-card fade-up" style="margin-bottom: var(--spacing-xl);" id="submissions-table">
                <div class="card-header">
                    <h2>Historique des soumissions</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Date de soumission</th>
                            <th>Statut</th>
                            <th>Workflow</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($articles) && !empty($articles)): ?>
                            <?php foreach ($articles as $article): ?>
                                <?php
                                $statut = strtolower($article['statut'] ?? 'soumis');
                                // Mapping selon les spécifications : soumis → pending, en évaluation → in-review, accepté/valide → accepted, publié → published, rejeté → rejected
                                $statutClass = 'pending';
                                if (strpos($statut, 'publ') !== false) {
                                    $statutClass = 'published';
                                } elseif (strpos($statut, 'accept') !== false || strpos($statut, 'valide') !== false) {
                                    $statutClass = 'accepted';
                                } elseif (strpos($statut, 'rej') !== false) {
                                    $statutClass = 'rejected';
                                } elseif (strpos($statut, 'évaluation') !== false || strpos($statut, 'evaluation') !== false || strpos($statut, 'revision') !== false) {
                                    $statutClass = 'in-review';
                                }
                                
                                $dateFormatted = date('d M Y', strtotime($article['date_soumission']));
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($article['titre']) ?></td>
                                    <td><?= $dateFormatted ?></td>
                                    <td><span class="status-badge <?= $statutClass ?>"><?= htmlspecialchars($article['statut_display']) ?></span></td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem;">
                                            <span style="color: #10b981;">✓ Reçu</span>
                                            <span>→</span>
                                            <?php if ($statutClass === 'in-review'): ?>
                                                <span style="color: #2563eb; font-weight: 600;">● En évaluation</span>
                                                <span>→</span>
                                                <span style="color: #9ca3af;">○ Révisions</span>
                                                <span>→</span>
                                                <span style="color: #9ca3af;">○ Accepté</span>
                                                <span>→</span>
                                                <span style="color: #9ca3af;">○ Publié</span>
                                            <?php elseif ($statutClass === 'accepted'): ?>
                                                <span style="color: #10b981;">✓ En évaluation</span>
                                                <span>→</span>
                                                <span style="color: #10b981;">✓ Révisions</span>
                                                <span>→</span>
                                                <span style="color: #059669; font-weight: 600;">● Accepté</span>
                                                <span>→</span>
                                                <span style="color: #9ca3af;">○ Publié</span>
                                            <?php elseif ($statutClass === 'published'): ?>
                                                <span style="color: #10b981;">✓ Reçu</span>
                                                <span>→</span>
                                                <span style="color: #10b981;">✓ En évaluation</span>
                                                <span>→</span>
                                                <span style="color: #10b981;">✓ Révisions</span>
                                                <span>→</span>
                                                <span style="color: #10b981;">✓ Accepté</span>
                                                <span>→</span>
                                                <span style="color: #7c3aed; font-weight: 600;">● Publié</span>
                                            <?php else: ?>
                                                <span style="color: #2563eb; font-weight: 600;">● <?= htmlspecialchars($article['statut_display']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn view" title="Voir les détails" onclick="window.location.href='<?= Router\Router::route('author') ?>/article/<?= $article['id'] ?>'">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <?php if ($statut === 'soumis'): ?>
                                                <button class="action-btn edit" title="Modifier" onclick="window.location.href='<?= Router\Router::route('author') ?>/article/<?= $article['id'] ?>/edit'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                <button type="button" class="action-btn delete" title="Supprimer" onclick="deleteArticle(<?= $article['id'] ?>)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem;">
                                    <p>Aucun article soumis pour le moment.</p>
                                    <a href="#submit-form" class="btn btn-primary" style="margin-top: 1rem;">Soumettre votre premier article</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Submission Form -->
            <div class="content-card fade-up" id="submit-form">
                <div class="card-header">
                    <h2>Soumettre un nouvel article</h2>
                </div>
                <form class="auth-form" method="post" action="<?= Router\Router::route('articles') ?>" enctype="multipart/form-data" id="article-submission-form">
                    <input type="hidden" name="auteur_id" value="<?= isset($user) ? $user['id'] : '' ?>">
                    <div class="form-section">
                        <h3>Informations de l'article</h3>
                        <div class="form-field">
                            <label>Titre de l'article *</label>
                            <input type="text" placeholder="Titre complet de votre article" required name="titre">
                        </div>
                        <div class="form-row">
                            <div class="form-field">
                                <label>Catégorie *</label>
                                <select name="categorie" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    <option value="systematic">Théologie Systématique</option>
                                    <option value="biblical">Études Bibliques</option>
                                    <option value="ethics">Éthique Chrétienne</option>
                                    <option value="history">Histoire de l'Église</option>
                                    <option value="practical">Théologie Pratique</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label>Type de publication *</label>
                                <select name="type_publication" required>
                                    <option value="">Sélectionnez un type</option>
                                    <option value="article">Article de recherche</option>
                                    <option value="note">Note de recherche</option>
                                    <option value="review">Recension</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-field">
                            <label>Résumé (250 mots max) *</label>
                            <textarea name="contenu" placeholder="Résumé de votre article en français" required></textarea>
                        </div>
                        <div class="form-field">
                            <label>Mots-clés (5-7 mots) *</label>
                            <input type="text" name="mots_cles" placeholder="Séparés par des virgules" required>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Fichiers</h3>
                        <div class="form-field">
                            <label>Manuscrit (PDF, Word ou LaTeX) *</label>
                            <div class="file-upload">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p id="file-name-display">Glissez-déposez votre fichier ici ou</p>
                                <span>Parcourir</span>
                                <input type="file" name="fichier" id="fichier-input" required accept=".pdf,.doc,.docx,.tex">
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: var(--spacing-md); justify-content: flex-end;">
                        <button type="button" class="btn btn-outline">Sauvegarder le brouillon</button>
                        <button type="submit" class="btn btn-primary">Soumettre l'article</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobile-menu-btn">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/author-notify.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script>
        // Gérer l'affichage du nom du fichier
        document.getElementById('fichier-input')?.addEventListener('change', function(e) {
            const fileNameDisplay = document.getElementById('file-name-display');
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
        document.getElementById('article-submission-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            // Désactiver le bouton pendant la soumission
            submitBtn.disabled = true;
            submitBtn.textContent = 'Soumission en cours...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message) {
                    showToast(data.message || 'Article soumis avec succès !', 'success');
                    this.reset();
                    document.getElementById('file-name-display').textContent = 'Glissez-déposez votre fichier ici ou';
                    document.getElementById('file-name-display').style.color = '';
                    document.getElementById('file-name-display').style.fontWeight = '';
                    // Recharger la page pour voir le nouvel article
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.error || 'Une erreur est survenue lors de la soumission', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue lors de la soumission de l\'article', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });

        // Fonction pour supprimer un article
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
                    window.location.reload();
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