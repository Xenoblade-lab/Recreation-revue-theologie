<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluation - Dashboard Évaluateur</title>
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
                    <a href="<?= Router\Router::route("reviewer") ?>" class="back-link" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-blue); text-decoration: none; margin-bottom: 0.5rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        Retour au tableau de bord
                    </a>
                    <h1>Évaluation de l'article</h1>
                    <p>Remplissez votre évaluation détaillée</p>
                </div>
            </div>

            <?php if (isset($evaluation) && $evaluation): ?>
                <!-- Informations de l'article -->
                <div class="content-card fade-up">
                    <div class="card-header">
                        <h2><?= htmlspecialchars($evaluation['article_titre'] ?? 'Titre indisponible') ?></h2>
                        <span class="status-badge <?= 
                            strtolower($evaluation['statut'] ?? '') === 'termine' ? 'accepted' : 
                            (strtolower($evaluation['statut'] ?? '') === 'en_cours' ? 'in-review' : 'pending')
                        ?>">
                            <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $evaluation['statut'] ?? 'en_attente'))) ?>
                        </span>
                    </div>

                    <div class="article-details-content">
                        <div class="detail-section">
                            <h3>Informations de l'évaluation</h3>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <label>Date d'assignation</label>
                                    <p><?= !empty($evaluation['date_assignation']) ? date('d M Y à H:i', strtotime($evaluation['date_assignation'])) : '—' ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Date d'échéance</label>
                                    <p><?= !empty($evaluation['date_echeance']) ? date('d M Y', strtotime($evaluation['date_echeance'])) : '—' ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Jours restants</label>
                                    <p><?= isset($evaluation['jours_restants']) ? ($evaluation['jours_restants'] >= 0 ? $evaluation['jours_restants'] . ' jours' : abs($evaluation['jours_restants']) . ' jours de retard') : '—' ?></p>
                                </div>
                                <div class="detail-item">
                                    <label>Auteur</label>
                                    <p><?= htmlspecialchars(trim(($evaluation['auteur_prenom'] ?? '') . ' ' . ($evaluation['auteur_nom'] ?? ''))) ?></p>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($evaluation['article_contenu'])): ?>
                            <div class="detail-section">
                                <h3>Résumé de l'article</h3>
                                <p class="article-content"><?= nl2br(htmlspecialchars($evaluation['article_contenu'] ?? '')) ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($evaluation['article_fichier_path'])): ?>
                            <div class="detail-section">
                                <h3>Fichier de l'article</h3>
                                <a href="<?= Router\Router::$defaultUri . htmlspecialchars($evaluation['article_fichier_path']) ?>" target="_blank" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    Télécharger le fichier
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Formulaire d'évaluation -->
                <div class="content-card fade-up">
                    <div class="card-header">
                        <h2>Formulaire d'évaluation</h2>
                    </div>

                    <form id="evaluationForm" class="evaluation-form">
                        <input type="hidden" name="evaluation_id" value="<?= $evaluation['id'] ?>">

                        <!-- Notes sur 10 -->
                        <div class="form-section">
                            <h3>Notes sur 10</h3>
                            <div class="form-grid">
                                <div class="form-field">
                                    <label>Qualité scientifique *</label>
                                    <input type="number" name="qualite_scientifique" id="qualite_scientifique" 
                                           min="0" max="10" step="0.5" 
                                           value="<?= htmlspecialchars($evaluation['qualite_scientifique'] ?? '') ?>"
                                           required>
                                    <small>Évaluez la rigueur méthodologique et la qualité scientifique</small>
                                </div>
                                <div class="form-field">
                                    <label>Originalité *</label>
                                    <input type="number" name="originalite" id="originalite" 
                                           min="0" max="10" step="0.5" 
                                           value="<?= htmlspecialchars($evaluation['originalite'] ?? '') ?>"
                                           required>
                                    <small>Évaluez l'originalité et l'innovation de la recherche</small>
                                </div>
                                <div class="form-field">
                                    <label>Pertinence *</label>
                                    <input type="number" name="pertinence" id="pertinence" 
                                           min="0" max="10" step="0.5" 
                                           value="<?= htmlspecialchars($evaluation['pertinence'] ?? '') ?>"
                                           required>
                                    <small>Évaluez la pertinence pour le domaine de recherche</small>
                                </div>
                                <div class="form-field">
                                    <label>Clarté *</label>
                                    <input type="number" name="clarte" id="clarte" 
                                           min="0" max="10" step="0.5" 
                                           value="<?= htmlspecialchars($evaluation['clarte'] ?? '') ?>"
                                           required>
                                    <small>Évaluez la clarté de la rédaction et de la présentation</small>
                                </div>
                            </div>
                            <div class="form-field" style="margin-top: 1rem;">
                                <label>Note finale (calculée automatiquement)</label>
                                <input type="text" id="note_finale" readonly value="<?= htmlspecialchars($evaluation['note_finale'] ?? 'Non calculée') ?>">
                            </div>
                        </div>

                        <!-- Commentaires -->
                        <div class="form-section">
                            <h3>Commentaires</h3>
                            <div class="form-field">
                                <label>Commentaires publics (visibles par l'auteur) *</label>
                                <textarea name="commentaires_public" id="commentaires_public" rows="6" required 
                                          placeholder="Rédigez vos commentaires constructifs pour l'auteur..."><?= htmlspecialchars($evaluation['commentaires_public'] ?? '') ?></textarea>
                                <small>Ces commentaires seront visibles par l'auteur de l'article</small>
                            </div>
                            <div class="form-field">
                                <label>Commentaires privés (pour le comité éditorial)</label>
                                <textarea name="commentaires_prives" id="commentaires_prives" rows="6" 
                                          placeholder="Commentaires confidentiels pour le comité..."><?= htmlspecialchars($evaluation['commentaires_prives'] ?? '') ?></textarea>
                                <small>Ces commentaires ne seront visibles que par le comité éditorial</small>
                            </div>
                            <div class="form-field">
                                <label>Suggestions d'amélioration</label>
                                <textarea name="suggestions" id="suggestions" rows="4" 
                                          placeholder="Proposez des améliorations spécifiques..."><?= htmlspecialchars($evaluation['suggestions'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <!-- Recommandation -->
                        <div class="form-section">
                            <h3>Recommandation finale *</h3>
                            <div class="form-field">
                                <label>Votre recommandation</label>
                                <select name="recommendation" id="recommendation" required>
                                    <option value="">Sélectionnez une recommandation</option>
                                    <option value="accepte" <?= ($evaluation['recommendation'] ?? '') === 'accepte' ? 'selected' : '' ?>>Accepter</option>
                                    <option value="accepte_avec_modifications" <?= ($evaluation['recommendation'] ?? '') === 'accepte_avec_modifications' ? 'selected' : '' ?>>Accepter avec modifications mineures</option>
                                    <option value="revision_majeure" <?= ($evaluation['recommendation'] ?? '') === 'revision_majeure' ? 'selected' : '' ?>>Révision majeure requise</option>
                                    <option value="rejete" <?= ($evaluation['recommendation'] ?? '') === 'rejete' ? 'selected' : '' ?>>Rejeter</option>
                                </select>
                                <small>Cette recommandation déterminera la suite du processus éditorial</small>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--color-gray-200);">
                            <button type="button" class="btn btn-outline" onclick="window.location.href='<?= Router\Router::route("reviewer") ?>'">Annuler</button>
                            <button type="button" class="btn btn-outline" id="saveDraftBtn">Sauvegarder le brouillon</button>
                            <button type="submit" class="btn btn-primary">Soumettre l'évaluation</button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="content-card fade-up">
                    <div class="empty-state">
                        <h3>Évaluation introuvable</h3>
                        <p>L'évaluation que vous recherchez n'existe pas ou vous n'avez pas les droits pour y accéder.</p>
                        <a href="<?= Router\Router::route("reviewer") ?>" class="btn btn-primary">Retour au tableau de bord</a>
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
    <script>
        const evaluationId = <?= $evaluation['id'] ?? 0 ?>;

        // Calculer la note finale automatiquement
        function calculateFinalScore() {
            const scores = [
                parseFloat(document.getElementById('qualite_scientifique').value) || 0,
                parseFloat(document.getElementById('originalite').value) || 0,
                parseFloat(document.getElementById('pertinence').value) || 0,
                parseFloat(document.getElementById('clarte').value) || 0
            ];

            const validScores = scores.filter(s => s > 0);
            if (validScores.length > 0) {
                const average = validScores.reduce((a, b) => a + b, 0) / validScores.length;
                document.getElementById('note_finale').value = average.toFixed(1) + ' / 10';
            } else {
                document.getElementById('note_finale').value = 'Non calculée';
            }
        }

        // Écouter les changements de notes
        ['qualite_scientifique', 'originalite', 'pertinence', 'clarte'].forEach(id => {
            document.getElementById(id)?.addEventListener('input', calculateFinalScore);
        });

        // Sauvegarder le brouillon
        document.getElementById('saveDraftBtn')?.addEventListener('click', function() {
            const formData = new FormData(document.getElementById('evaluationForm'));
            const data = Object.fromEntries(formData);
            
            // Sauvegarder sans changer le statut
            fetch('<?= Router\Router::route("reviewer") ?>/evaluation/' + evaluationId + '/save-draft', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message) {
                    showToast('Brouillon sauvegardé avec succès', 'success');
                } else {
                    showToast(data.error || 'Erreur lors de la sauvegarde', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Erreur lors de la sauvegarde du brouillon', 'error');
            });
        });

        // Soumettre l'évaluation
        document.getElementById('evaluationForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const ok = typeof showConfirm === 'function'
                ? await showConfirm({
                    title: 'Confirmation',
                    message: 'Êtes-vous sûr de vouloir soumettre cette évaluation ? Cette action est définitive.',
                    confirmText: 'Soumettre',
                    cancelText: 'Annuler'
                })
                : confirm('Êtes-vous sûr de vouloir soumettre cette évaluation ? Cette action est définitive.');
            if (!ok) return;

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Convertir les notes en nombres
            data.qualite_scientifique = parseFloat(data.qualite_scientifique) || null;
            data.originalite = parseFloat(data.originalite) || null;
            data.pertinence = parseFloat(data.pertinence) || null;
            data.clarte = parseFloat(data.clarte) || null;

            fetch('<?= Router\Router::route("reviewer") ?>/evaluation/' + evaluationId + '/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(async response => {
                // Vérifier si la réponse est du JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const jsonData = await response.json();
                    if (!response.ok) {
                        return Promise.reject(jsonData);
                    }
                    return jsonData;
                } else {
                    // Si ce n'est pas du JSON, c'est probablement une erreur HTML
                    const text = await response.text();
                    console.error('Réponse non-JSON reçue:', text.substring(0, 200));
                    return Promise.reject({
                        error: 'Erreur serveur',
                        message: 'Une erreur est survenue. Veuillez réessayer.'
                    });
                }
            })
            .then(data => {
                if (data.success || data.message) {
                    showToast(data.message || 'Évaluation soumise avec succès', 'success');
                    window.location.href = '<?= Router\Router::route("reviewer") ?>';
                } else {
                    showToast(data.error || 'Une erreur est survenue', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                const errorMsg = error.error || error.message || 'Une erreur est survenue lors de la soumission';
                showToast(errorMsg, 'error');
            });
        });

        // Calculer la note initiale
        calculateFinalScore();
    </script>
</body>
</html>

