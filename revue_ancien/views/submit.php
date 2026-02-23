<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soumettre un Article - Revue de Théologie UPC</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/soumettre-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
     <?php include __DIR__ .  DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'header.php'; ?>
   
    <!-- Mobile Navigation -->
    <nav class="mobile-nav">
        <a href="index.html">Accueil</a>
        <a href="numeros.html">Numéros & Archives</a>
        <a href="soumettre.html" class="active">Soumettre</a>
        <a href="instructions.html">Instructions</a>
        <a href="comite.html">Comité</a>
        <a href="recherche.html">Recherche</a>
        <a href="soumettre.html" class="btn btn-primary btn-submit-mobile">Soumettre un article</a>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="fade-up">Soumettre un Article</h1>
            <p class="fade-up">Partagez votre recherche avec la communauté théologique internationale</p>
        </div>
    </section>

    <!-- Submission Process -->
    <section class="process-section">
        <div class="container">
            <h2 class="section-title fade-up">Processus de soumission</h2>
            <div class="process-steps">
                <div class="step fade-up">
                    <div class="step-number">1</div>
                    <h3>Choisir votre formule</h3>
                    <p>Sélectionnez le plan d'abonnement adapté à vos besoins</p>
                </div>
                <div class="step fade-up">
                    <div class="step-number">2</div>
                    <h3>Créer un compte</h3>
                    <p>Inscrivez-vous sur notre plateforme de soumission</p>
                </div>
                <div class="step fade-up">
                    <div class="step-number">3</div>
                    <h3>Téléverser votre manuscrit</h3>
                    <p>Soumettez votre article au format Word ou LaTeX</p>
                </div>
                <div class="step fade-up">
                    <div class="step-number">4</div>
                    <h3>Évaluation par les pairs</h3>
                    <p>Votre article sera évalué par des experts</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing-section">
        <div class="container">
            <div class="section-header fade-up">
                <h2>Formules d'abonnement</h2>
                <p>Choisissez la formule qui correspond à vos besoins de publication</p>
            </div>
            <div class="pricing-grid">
                <div class="pricing-card fade-up">
                    <div class="pricing-header">
                        <h3>Étudiant</h3>
                        <div class="price">
                            <span class="amount">50$</span>
                            <span class="period">/ article</span>
                        </div>
                    </div>
                    <ul class="features-list">
                        <li>✓ Évaluation par les pairs</li>
                        <li>✓ Publication en ligne</li>
                        <li>✓ DOI attribution</li>
                        <li>✓ Support éditorial basique</li>
                        <li>✗ Révision linguistique</li>
                        <li>✗ Publication accélérée</li>
                    </ul>
                    <button class="btn btn-outline">Choisir</button>
                </div>

                <div class="pricing-card featured fade-up">
                    <div class="badge-featured">Populaire</div>
                    <div class="pricing-header">
                        <h3>Standard</h3>
                        <div class="price">
                            <span class="amount">150$</span>
                            <span class="period">/ article</span>
                        </div>
                    </div>
                    <ul class="features-list">
                        <li>✓ Évaluation par les pairs</li>
                        <li>✓ Publication en ligne</li>
                        <li>✓ DOI attribution</li>
                        <li>✓ Support éditorial avancé</li>
                        <li>✓ Révision linguistique</li>
                        <li>✗ Publication accélérée</li>
                    </ul>
                    <button class="btn btn-primary">Choisir</button>
                </div>

                <div class="pricing-card fade-up">
                    <div class="pricing-header">
                        <h3>Premium</h3>
                        <div class="price">
                            <span class="amount">300$</span>
                            <span class="period">/ article</span>
                        </div>
                    </div>
                    <ul class="features-list">
                        <li>✓ Évaluation par les pairs</li>
                        <li>✓ Publication en ligne</li>
                        <li>✓ DOI attribution</li>
                        <li>✓ Support éditorial premium</li>
                        <li>✓ Révision linguistique complète</li>
                        <li>✓ Publication accélérée (2 mois)</li>
                    </ul>
                    <button class="btn btn-outline">Choisir</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Submission Form
    <section class="submission-form-section">
        <div class="container">
            <div class="form-container fade-up">
                <h2>Formulaire de soumission</h2>
                <form class="submission-form" id="submissionForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="firstName">Prénom *</label>
                            <input type="text" id="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Nom *</label>
                            <input type="text" id="lastName" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" required>
                    </div>

                    <div class="form-group">
                        <label for="affiliation">Affiliation institutionnelle *</label>
                        <input type="text" id="affiliation" required>
                    </div>

                    <div class="form-group">
                        <label for="title">Titre de l'article *</label>
                        <input type="text" id="title" required>
                    </div>

                    <div class="form-group">
                        <label for="abstract">Résumé (max 300 mots) *</label>
                        <textarea id="abstract" rows="6" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="keywords">Mots-clés (séparés par des virgules) *</label>
                        <input type="text" id="keywords" required>
                    </div>

                    <div class="form-group">
                        <label for="category">Catégorie *</label>
                        <select id="category" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <option value="systematique">Théologie Systématique</option>
                            <option value="biblique">Études Bibliques</option>
                            <option value="ethique">Éthique Chrétienne</option>
                            <option value="histoire">Histoire de l'Église</option>
                            <option value="pratique">Théologie Pratique</option>
                            <option value="missionnologie">Missionnologie</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="manuscript">Manuscrit (Word ou PDF) *</label>
                        <div class="file-upload">
                            <input type="file" id="manuscript" accept=".doc,.docx,.pdf" required>
                            <label for="manuscript" class="file-upload-label">
                                <span>Choisir un fichier</span>
                            </label>
                            <span class="file-name">Aucun fichier sélectionné</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" required>
                            <span>J'accepte les <a href="#">conditions de publication</a> et confirme que ce travail est original</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-large">Soumettre l'article</button>
                </form>
            </div>
        </div>
    </section> -->

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3>Revue de Théologie UPC</h3>
                    <p>
                        Université Protestante au Congo<br>
                        Faculté de Théologie<br>
                        Kinshasa, RD Congo
                    </p>
                </div>
                <div class="footer-col">
                    <h4>Navigation</h4>
                    <ul>
                        <li><a href="index.html">Accueil</a></li>
                        <li><a href="numeros.html">Numéros & Archives</a></li>
                        <li><a href="soumettre.html">Soumettre un article</a></li>
                        <li><a href="instructions.html">Instructions aux auteurs</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Ressources</h4>
                    <ul>
                        <li><a href="comite.html">Comité éditorial</a></li>
                        <li><a href="recherche.html">Recherche avancée</a></li>
                        <li><a href="#">Politique éditoriale</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Suivez-nous</h4>
                    <div class="social-links">
                        <a href="#">Facebook</a>
                        <a href="#">Twitter</a>
                        <a href="#">LinkedIn</a>
                        <a href="#">ResearchGate</a>
                    </div>
                    <p class="footer-issn">ISSN: 1234-5678</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Revue de la Faculté de Théologie - UPC. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="./js/script.js"></script>
    <!-- <script src="./js/soumettre-script.js"></script> -->
</body>
</html>
