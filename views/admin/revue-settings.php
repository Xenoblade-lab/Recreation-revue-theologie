<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres de la Revue - Admin</title>
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
                    <h1>Paramètres de la Revue</h1>
                    <p>Gérer l'identité et les informations de la revue</p>
                </div>
            </div>

            <div class="content-card fade-up">
                <div class="card-header">
                    <h2>Identité de la Revue</h2>
                </div>
                
                <form id="revueSettingsForm" style="padding: var(--spacing-md);">
                    <div class="form-section" style="margin-bottom: 2rem;">
                        <h3 style="margin-bottom: 1rem; color: var(--color-primary);">Informations Générales</h3>
                        
                        <div class="form-field">
                            <label>Nom officiel de la revue *</label>
                            <input type="text" name="nom_officiel" id="nom_officiel" 
                                   value="<?= htmlspecialchars($revueInfo['nom_officiel'] ?? '') ?>" 
                                   required>
                        </div>

                        <div class="form-field">
                            <label>Description</label>
                            <textarea name="description" id="description" rows="4" 
                                      placeholder="Description générale de la revue..."><?= htmlspecialchars($revueInfo['description'] ?? '') ?></textarea>
                        </div>

                        <div class="form-field">
                            <label>ISSN</label>
                            <input type="text" name="issn" id="issn" 
                                   value="<?= htmlspecialchars($revueInfo['issn'] ?? '') ?>" 
                                   placeholder="Ex: 1234-5678">
                        </div>
                    </div>

                    <div class="form-section" style="margin-bottom: 2rem;">
                        <h3 style="margin-bottom: 1rem; color: var(--color-primary);">Ligne Éditoriale</h3>
                        
                        <div class="form-field">
                            <label>Ligne éditoriale</label>
                            <textarea name="ligne_editoriale" id="ligne_editoriale" rows="6" 
                                      placeholder="Décrivez la ligne éditoriale de la revue..."><?= htmlspecialchars($revueInfo['ligne_editoriale'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="form-section" style="margin-bottom: 2rem;">
                        <h3 style="margin-bottom: 1rem; color: var(--color-primary);">Objectifs</h3>
                        
                        <div class="form-field">
                            <label>Objectifs de la revue</label>
                            <textarea name="objectifs" id="objectifs" rows="6" 
                                      placeholder="Décrivez les objectifs de la revue..."><?= htmlspecialchars($revueInfo['objectifs'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="form-section" style="margin-bottom: 2rem;">
                        <h3 style="margin-bottom: 1rem; color: var(--color-primary);">Domaines Couverts</h3>
                        
                        <div class="form-field">
                            <label>Domaines de recherche couverts</label>
                            <textarea name="domaines_couverts" id="domaines_couverts" rows="4" 
                                      placeholder="Listez les domaines couverts par la revue..."><?= htmlspecialchars($revueInfo['domaines_couverts'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="form-section" style="margin-bottom: 2rem;">
                        <h3 style="margin-bottom: 1rem; color: var(--color-primary);">Comités</h3>
                        
                        <div class="form-field">
                            <label>Comité Scientifique</label>
                            <textarea name="comite_scientifique" id="comite_scientifique" rows="8" 
                                      placeholder="Listez les membres du comité scientifique (un par ligne ou format libre)..."><?= htmlspecialchars($revueInfo['comite_scientifique'] ?? '') ?></textarea>
                            <small style="color: var(--color-gray-600);">Vous pouvez formater librement (HTML accepté)</small>
                        </div>

                        <div class="form-field">
                            <label>Comité de Rédaction</label>
                            <textarea name="comite_redaction" id="comite_redaction" rows="8" 
                                      placeholder="Listez les membres du comité de rédaction (un par ligne ou format libre)..."><?= htmlspecialchars($revueInfo['comite_redaction'] ?? '') ?></textarea>
                            <small style="color: var(--color-gray-600);">Vous pouvez formater librement (HTML accepté)</small>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                        <button type="button" class="btn btn-outline" onclick="window.location.reload()">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
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
        document.getElementById('revueSettingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enregistrement...';
            
            fetch('<?= Router\Router::route("admin") ?>/revue/settings', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Paramètres enregistrés avec succès !', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('Erreur : ' + (data.error || 'Une erreur est survenue'), 'error');
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue lors de l\'enregistrement', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    </script>
</body>
</html>

