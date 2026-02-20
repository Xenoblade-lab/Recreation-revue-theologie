<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Numéros & Archives - Revue de Théologie UPC</title>
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/styles.css">
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/numeros-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ .  DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'header.php'; ?>
    <!-- Mobile Navigation -->
    <nav class="mobile-nav">
        <a href="<?= Router\Router::route('') ?>">Accueil</a>
        <a href="<?= Router\Router::route('archives') ?>" class="active">Numéros & Archives</a>
        <a href="<?= Router\Router::route('submit') ?>">Soumettre</a>
        <a href="<?= Router\Router::route('instructions') ?>">Instructions</a>
        <a href="<?= Router\Router::route('comite') ?>">Comité éditorial</a>
        <a href="<?= Router\Router::route('research') ?>">Recherche</a>
        <a href="<?= Router\Router::route('submit') ?>" class="btn btn-primary btn-submit-mobile">Soumettre un article</a>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="fade-up">Numéros & Archives</h1>
            <p class="fade-up">Explorez nos publications scientifiques en théologie africaine</p>
        </div>
    </section>

    <!-- Archives Navigation -->
    <section class="archives-section">
        <div class="container">
            <?php if (!empty($years)): ?>
                <div class="archives-nav fade-up" style="display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                    <?php foreach ($years as $index => $year): ?>
                        <button type="button" class="year-btn <?= $index === 0 ? 'active' : '' ?>" data-year="<?= htmlspecialchars($year) ?>">
                            <?= htmlspecialchars($year) ?>
                        </button>
                    <?php endforeach; ?>
                    <div style="margin-left: auto;">
                        <a href="<?= Router\Router::route('volume', $years[0] ?? date('Y')) ?>" 
                           class="btn btn-outline btn-sm" 
                           style="text-decoration: none;">
                            Voir tous les volumes →
                        </a>
                    </div>
                </div>

                <?php foreach ($volumesByYear as $index => $yearData): ?>
                    <div class="year-content <?= $index === 0 ? 'active' : '' ?>" data-year="<?= htmlspecialchars($yearData['volume']['annee']) ?>">
                        <div style="text-align: center; margin-bottom: 1.5rem;">
                            <a href="<?= Router\Router::route('volume', $yearData['volume']['annee']) ?>" 
                               class="btn btn-primary" 
                               style="text-decoration: none;">
                                Consulter tous les articles
                            </a>
                        </div>
                        <div class="issues-grid">
                            <?php if (!empty($yearData['issues'])): ?>
                                <?php foreach ($yearData['issues'] as $issue): ?>
                                    <div class="issue-card fade-up">
                                        <div class="issue-cover-small">
                                            <img src="<?= Router\Router::$defaultUri ?>logo_upc.png" alt="<?= htmlspecialchars($issue['titre'] ?? '') ?>" 
                                                 onerror="this.onerror=null; this.src='<?= Router\Router::$defaultUri ?>placeholder.svg';">
                                            <?php if ($issue['date_publication'] && strtotime($issue['date_publication']) > strtotime('-6 months')): ?>
                                                <span class="issue-badge-small">Nouveau</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="issue-info">
                                            <div class="issue-meta-small">
                                                <span><?= htmlspecialchars($yearData['volume']['numero_volume'] ?? 'Vol. ' . $yearData['volume']['annee']) ?></span>
                                                <span><?= htmlspecialchars($issue['numero'] ?? '—') ?></span>
                                                <span><?= htmlspecialchars($yearData['volume']['annee']) ?></span>
                                            </div>
                                            <h3><?= htmlspecialchars($issue['titre'] ?? 'Sans titre') ?></h3>
                                            <p><?= !empty($issue['description']) ? htmlspecialchars(substr($issue['description'], 0, 150)) . '...' : 'Aucune description disponible.' ?></p>
                                            <div class="issue-stats-small">
                                                <span><?= htmlspecialchars($issue['article_count'] ?? 0) ?> article(s)</span>
                                                <?php if (!empty($issue['date_publication'])): ?>
                                                    <span><?= date('M Y', strtotime($issue['date_publication'])) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="issue-actions-small">
                                                <?php 
                                                $canDownloadArchives = Service\AuthService::canDownloadArticle();
                                                if (!empty($issue['fichier_path']) && $canDownloadArchives): ?>
                                                    <a href="<?= Router\Router::route('download/issue', $issue['id']) ?>" class="btn btn-outline">Télécharger PDF</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p style="text-align: center; padding: 2rem; color: var(--color-gray-600);">
                                    Aucun numéro disponible pour l'année <?= htmlspecialchars($yearData['volume']['annee']) ?>.
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="year-content active">
                    <p style="text-align: center; padding: 2rem; color: var(--color-gray-600);">
                        Aucun volume disponible dans les archives.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <style>
        /* Améliorations de stabilité et design */
        .archives-section {
            min-height: 60vh;
        }
        
        .year-content {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .issue-cover-small img {
            object-fit: cover;
            height: 160px;
            background: #f3f4f6;
        }
        
        .year-btn {
            cursor: pointer;
            user-select: none;
        }
        
        .year-btn:active {
            transform: scale(0.98);
        }
        
        /* Empêcher les rechargements */
        .year-btn[type="button"] {
            border: none;
        }
    </style>

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
                        <li><a href="<?= Router\Router::route('') ?>">Accueil</a></li>
                        <li><a href="<?= Router\Router::route('archives') ?>">Numéros & Archives</a></li>
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

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script>
        // Navigation par année - version stable
        (function() {
            'use strict';
            
            if (window.archivesScriptLoaded) {
                return; // Déjà chargé
            }
            window.archivesScriptLoaded = true;
            
            document.addEventListener('DOMContentLoaded', function() {
                const yearButtons = document.querySelectorAll(".year-btn");
                const yearContents = document.querySelectorAll(".year-content");
                
                if (yearButtons.length === 0 || yearContents.length === 0) {
                    return; // Pas d'éléments à gérer
                }
                
                yearButtons.forEach((button) => {
                    button.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const year = button.getAttribute("data-year");
                        if (!year) return;
                        
                        // Retirer active de tous les boutons et contenus
                        yearButtons.forEach((btn) => btn.classList.remove("active"));
                        yearContents.forEach((content) => content.classList.remove("active"));
                        
                        // Ajouter active au bouton cliqué
                        button.classList.add("active");
                        
                        // Afficher le contenu correspondant
                        const targetContent = document.querySelector(`.year-content[data-year="${year}"]`);
                        if (targetContent) {
                            targetContent.classList.add("active");
                            
                            // Scroll doux vers le contenu
                            targetContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                        
                        return false;
                    });
                });
            });
        })();
    </script>
</body>
</html>
