<?php $baseUrl = Router\Router::$defaultUri; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revue Congolaise de Théologie protestante – UPC</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header standard -->
    <?php include __DIR__ . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'header.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content fade-up">
                <span class="hero-badge">Revue Académique Internationale</span>
                <h1 class="hero-title">Revue Congolaise de Théologie protestante</h1>
                <p class="hero-subtitle">Université Protestante au Congo</p>
                <p class="hero-description">
                    Publication scientifique de référence en théologie et sciences humaines. 
                    Nous publions des recherches innovantes qui contribuent au dialogue théologique dans une perspective interdisciplinaire.
                </p>
                <div class="hero-actions">
                    <a href="<?= Router\Router::route("archives") ?>" class="btn btn-primary">Lire le dernier numéro</a>
                    <a href="<?= Router\Router::route("submit") ?>" class="btn btn-secondary">Soumettre un article</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Issue Section -->
    <section class="latest-issue">
        <div class="container">
            <div class="section-header fade-up">
                <h2>Dernier numéro publié</h2>
                <a href="<?= Router\Router::route('archives') ?>" class="view-all">Voir tous les numéros →</a>
            </div>
            <?php
            $volume = $latestVolume ?? null;
            $volumeYear = $volume ? ($volume['annee'] ?? date('Y')) : date('Y');
            $volumeLabel = $volume ? ($volume['numero_volume'] ?? ('Volume ' . $volumeYear)) : ('Volume ' . $volumeYear);
            $volumeDesc = ($volume && !empty($volume['description'])) ? $volume['description'] : 'Ce numéro explore les nouvelles perspectives de la théologie africaine face aux défis sociaux, politiques et environnementaux du XXIe siècle.';
            $volumeTitle = ($volume && !empty($volume['titre'])) ? $volume['titre'] : 'Théologie Contextuelle et Défis Contemporains en Afrique';
            $articleCount = $volume ? (int)($volume['article_count'] ?? 0) : 0;
            ?>
            <div class="issue-featured fade-up">
                <div class="issue-cover">
                    <img src="<?= Router\Router::$defaultUri ?>assets/logo_upc.png" alt="<?= htmlspecialchars($volumeLabel) ?>" onerror="this.src='<?= Router\Router::$defaultUri ?>placeholder.svg'">
                    <span class="issue-badge">Nouveau</span>
                </div>
                <div class="issue-details">
                    <div class="issue-meta">
                        <span class="volume"><?= htmlspecialchars($volumeLabel) ?></span>
                        <span class="year"><?= htmlspecialchars($volumeYear) ?></span>
                    </div>
                    <h3><?= htmlspecialchars($volumeTitle) ?></h3>
                    <p class="issue-description">
                        <?= htmlspecialchars(strlen($volumeDesc) > 220 ? substr($volumeDesc, 0, 220) . '…' : $volumeDesc) ?>
                    </p>
                    <div class="issue-stats">
                        <div class="stat">
                            <strong><?= $articleCount ?: '—' ?></strong>
                            <span>Articles</span>
                        </div>
                    </div>
                    <div class="issue-actions">
                        <a href="<?= Router\Router::route('volume', $volumeYear) ?>" class="btn btn-primary">Consulter tous les articles</a>
                        <a href="<?= Router\Router::route('archives') ?>" class="btn btn-outline">Voir les numéros</a>
                    </div>
                </div>
            </div>
            
            <!-- Featured Articles -->
            <div class="featured-articles">
                <h3 class="section-title fade-up">Articles en vedette</h3>
                <div class="articles-grid">
                    <article class="article-card fade-up">
                        <span class="article-category">Théologie Systématique</span>
                        <h4>La pneumatologie africaine : une réflexion trinitaire</h4>
                        <p class="article-authors">Dr. Jean-Baptiste Nkulu, Pr. Marie Kabongo</p>
                        <p class="article-excerpt">
                            Une exploration de la compréhension africaine de l'Esprit Saint dans le contexte 
                            de la théologie trinitaire contemporaine...
                        </p>
                        <div class="article-meta">
                            <span class="pages">pp. 15-42</span>
                            <span class="doi">DOI: 10.5281/zenodo.12345</span>
                        </div>
                        <a href="#" class="article-link">Lire l'article →</a>
                    </article>
                    
                    <article class="article-card fade-up">
                        <span class="article-category">Études Bibliques</span>
                        <h4>Herméneutique décoloniale et lecture africaine de l'Exode</h4>
                        <p class="article-authors">Pr. Samuel Mulamba</p>
                        <p class="article-excerpt">
                            Comment les communautés africaines lisent-elles le récit de l'Exode à travers 
                            leurs propres expériences historiques...
                        </p>
                        <div class="article-meta">
                            <span class="pages">pp. 43-68</span>
                            <span class="doi">DOI: 10.5281/zenodo.12346</span>
                        </div>
                        <a href="#" class="article-link">Lire l'article →</a>
                    </article>
                    
                    <article class="article-card fade-up">
                        <span class="article-category">Éthique Chrétienne</span>
                        <h4>Justice environnementale et responsabilité écologique chrétienne</h4>
                        <p class="article-authors">Dr. Grace Mwamba, Dr. Paul Ilunga</p>
                        <p class="article-excerpt">
                            L'urgence climatique interpelle la théologie africaine : quelle éthique 
                            environnementale pour les Églises congolaises...
                        </p>
                        <div class="article-meta">
                            <span class="pages">pp. 69-95</span>
                            <span class="doi">DOI: 10.5281/zenodo.12347</span>
                        </div>
                        <a href="#" class="article-link">Lire l'article →</a>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <!-- News & Events -->
    <section class="news-section">
        <div class="container">
            <div class="section-header fade-up">
                <h2>Actualités & Événements</h2>
                <a href="#" class="view-all">Voir tout →</a>
            </div>
            <div class="news-grid">
                <article class="news-card fade-up">
                    <span class="news-date">15 Janvier 2025</span>
                    <h4>Appel à contributions : Numéro spécial sur l'ecclésiologie africaine</h4>
                    <p>Nous sollicitons des articles pour notre numéro thématique de décembre 2025...</p>
                    <a href="#" class="news-link">Lire la suite →</a>
                </article>
                
                <article class="news-card fade-up">
                    <span class="news-date">10 Janvier 2025</span>
                    <h4>Colloque international : Théologie et développement durable</h4>
                    <p>La Faculté organise un colloque du 15-17 mars 2025 à Kinshasa...</p>
                    <a href="#" class="news-link">Lire la suite →</a>
                </article>
                
                <article class="news-card fade-up">
                    <span class="news-date">5 Janvier 2025</span>
                    <h4>Nouvelles procédures de soumission</h4>
                    <p>Découvrez nos nouvelles directives simplifiées pour la soumission d'articles...</p>
                    <a href="#" class="news-link">Lire la suite →</a>
                </article>
            </div>
        </div>
    </section>

    <!-- Submit Section -->
    <section class="submit-section">
        <div class="container">
            <div class="submit-content fade-up">
                <div class="submit-text">
                    <h2>Publiez votre recherche</h2>
                    <p>
                        Rejoignez une communauté de chercheurs internationaux et partagez vos contributions 
                        en théologie et sciences humaines. Notre processus d'évaluation par les pairs garantit 
                        l'excellence académique et contribue au dialogue théologique dans une perspective interdisciplinaire.
                    </p>
                    <ul class="submit-benefits">
                        <li>Évaluation rigoureuse par des pairs</li>
                        <li>Visibilité internationale</li>
                        <li>Indexation dans les principales bases de données</li>
                        <li>Accès libre (Open Access option)</li>
                    </ul>
                    <a href="soumettre.html" class="btn btn-primary">Soumettre un article</a>
                </div>
                <div class="submit-image">
                    <img src="<?= Router\Router::$defaultUri ?>assets/logo_upc.png" alt="Recherche académique" onerror="this.src='<?= Router\Router::$defaultUri ?>placeholder.svg'">
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-grid">
                <div class="about-text fade-up">
                    <h2>À propos de la revue</h2>
                    <p>
                        Fondée en 1997, la Revue de la Faculté de Théologie de l'Université Protestante 
                        au Congo est une publication semestrielle à comité de lecture qui promeut la recherche 
                        théologique en Afrique et dans le monde.
                    </p>
                    <p>
                        Notre mission est de fournir une plateforme académique de premier plan pour le dialogue 
                        théologique, l'innovation herméneutique et la réflexion critique sur les enjeux 
                        contemporains de la foi chrétienne.
                    </p>
                    <div class="about-stats">
                        <div class="stat-item">
                            <strong>28</strong>
                            <span>Années de publication</span>
                        </div>
                        <div class="stat-item">
                            <strong>500+</strong>
                            <span>Articles publiés</span>
                        </div>
                        <div class="stat-item">
                            <strong>45</strong>
                            <span>Pays représentés</span>
                        </div>
                    </div>
                </div>
                <div class="about-image fade-up">
                    <img src="<?= Router\Router::$defaultUri ?>assets/logo_upc.png" alt="Université Protestante au Congo" onerror="this.src='<?= Router\Router::$defaultUri ?>placeholder.svg'">
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-content fade-up">
                <div class="newsletter-text">
                    <h2>Restez informé</h2>
                    <p>Recevez les notifications de nouveaux numéros, appels à contributions et événements.</p>
                </div>
                <form class="newsletter-form">
                    <input type="email" placeholder="Votre adresse email" required>
                    <button type="submit" class="btn btn-primary">S'abonner</button>
                </form>
            </div>
        </div>
    </section>

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

    <script src="<?= $baseUrl ?>js/script.js"></script>
</body>
</html>
