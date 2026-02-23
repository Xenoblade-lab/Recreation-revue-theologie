<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revue de la Faculté de Théologie – UPC</title>
    <?php
    $baseUrl = Router\Router::$defaultUri;
    ?>
    <link rel="stylesheet" href="<?= $baseUrl ?>css/styles.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>css/comite-styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
     <?php include __DIR__ .  DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'header.php'; ?>

    <!-- Page Header -->
    <section class="comite-page-header">
        <div class="container">
            <h1 class="comite-page-title">Comité éditorial</h1>
            <p class="comite-page-subtitle">L'équipe en charge de l'évaluation scientifique et de la direction éditoriale de la revue.</p>
        </div>
    </section>

    <!-- Comité de rédaction et Comité scientifique (depuis revue_info) -->
    <?php
    $revueInfo = $revueInfo ?? [];
    $comiteRedaction = trim($revueInfo['comite_redaction'] ?? '');
    $comiteScientifique = trim($revueInfo['comite_scientifique'] ?? '');
    $hasComitesGlobaux = $comiteRedaction !== '' || $comiteScientifique !== '';
    ?>
    <?php if ($hasComitesGlobaux): ?>
    <section class="comite-section comite-section--globaux" aria-label="Comités permanents">
        <div class="container container--comite">
            <div class="comite-globaux">
                <?php if ($comiteRedaction !== ''): ?>
                <article class="comite-global-card fade-up">
                    <h2 class="comite-global-card__title">Comité de rédaction</h2>
                    <div class="comite-global-card__content"><?= nl2br(htmlspecialchars($comiteRedaction)) ?></div>
                </article>
                <?php endif; ?>
                <?php if ($comiteScientifique !== ''): ?>
                <article class="comite-global-card fade-up">
                    <h2 class="comite-global-card__title">Comité scientifique</h2>
                    <p class="comite-global-card__subtitle">Composé de professeurs nationaux et internationaux</p>
                    <div class="comite-global-card__content"><?= nl2br(htmlspecialchars($comiteScientifique)) ?></div>
                </article>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Committee Section : un comité par année (volume) -->
    <section class="comite-section" aria-label="Comités éditoriaux par année">
        <div class="container container--comite">
            <?php
            $volumes = $volumes ?? [];
            if (empty($volumes)): ?>
                <div class="comite-empty fade-up">
                    <div class="comite-empty-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <p>Aucun volume (année) enregistré pour l’instant. Les comités seront affichés ici par année.</p>
                </div>
            <?php else: ?>
                <!-- Sommaire : navigation rapide par année -->
                <nav class="comite-nav" aria-label="Aller à l'année">
                    <span class="comite-nav__label">Aller à l'année :</span>
                    <ul class="comite-nav__list">
                        <?php foreach ($volumes as $vol): $a = (int)($vol['annee'] ?? 0); ?>
                            <li><a href="#comite-<?= $a ?>" class="comite-nav__link"><?= $a ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <div class="comite-list">
                <?php foreach ($volumes as $vol):
                    $annee = (int)($vol['annee'] ?? 0);
                    $redacteur = trim($vol['redacteur_chef'] ?? '');
                    $comite = trim($vol['comite_editorial'] ?? '');
                    $hasContent = $redacteur !== '' || $comite !== '';
                ?>
                    <article class="comite-year-card fade-up" id="comite-<?= $annee ?>" data-year="<?= $annee ?>">
                        <header class="comite-year-card__header">
                            <span class="comite-year-card__badge" aria-hidden="true">Volume <?= $annee ?></span>
                            <h2 class="comite-year-card__title">Comité éditorial <?= $annee ?></h2>
                        </header>
                        <div class="comite-year-card__body">
                            <?php if (!$hasContent): ?>
                                <p class="comite-year-card__empty">Comité non renseigné pour cette année.</p>
                            <?php else: ?>
                                <?php if ($redacteur !== ''): ?>
                                    <div class="comite-redacteur">
                                        <span class="comite-redacteur__label">Rédacteur en chef</span>
                                        <p class="comite-redacteur__name"><?= htmlspecialchars($redacteur) ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if ($comite !== ''): ?>
                                    <div class="comite-membres">
                                        <span class="comite-membres__label">Membres du comité</span>
                                        <div class="comite-membres__content"><?= nl2br(htmlspecialchars($comite)) ?></div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
                        <li><a href="<?= Router\Router::route("") ?>">Accueil</a></li>
                        <li><a href="<?= Router\Router::route("archives") ?>">Numéros & Archives</a></li>
                        <li><a href="<?= Router\Router::route("submit") ?>">Soumettre un article</a></li>
                        <li><a href="<?= Router\Router::route("instructions") ?>">Instructions aux auteurs</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Ressources</h4>
                    <ul>
                        <li><a href="<?= Router\Router::route("comite") ?>">Comité éditorial</a></li>
                        <li><a href="<?= Router\Router::route("search") ?>">Recherche avancée</a></li>
                        <li><a href="<?= Router\Router::route("publications") ?>">Publications</a></li>
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
    <script src="<?= $baseUrl ?>js/comite-script.js"></script>

</body></html>