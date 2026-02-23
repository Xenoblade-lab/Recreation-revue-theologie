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
                    <p style="margin-top: 0.75rem;">
                        <a href="https://upc.ac.cd/" target="_blank" rel="noopener noreferrer" class="footer-upc-link">Visiter le site de l'UPC →</a>
                    </p>
                </div>
                <div class="footer-col">
                    <h4>Navigation</h4>
                    <ul>
                        <li><a href="<?= Router\Router::route('') ?>">Accueil</a></li>
                        <li><a href="<?= Router\Router::route('presentation') ?>">Présentation</a></li>
                        <li><a href="<?= Router\Router::route('archives') ?>">Numéros & Archives</a></li>
                        <li><a href="<?= Router\Router::route('submit') ?>">Soumettre un article</a></li>
                        <li><a href="<?= Router\Router::route('instructions') ?>">Instructions aux auteurs</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Ressources</h4>
                    <ul>
                        <li><a href="<?= Router\Router::route('comite') ?>">Comité éditorial</a></li>
                        <li><a href="<?= Router\Router::route('search') ?>">Recherche avancée</a></li>
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
                <p>&copy; <?= date('Y') ?> Revue de la Faculté de Théologie - UPC. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

