<?php
// Déterminer le chemin de base selon l'emplacement du fichier
$basePath = '';
if (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) {
    $basePath = '../';
} elseif (strpos($_SERVER['PHP_SELF'], '/gestion/') !== false) {
    $basePath = '../';
}
?>
    <!-- Header -->
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="<?php echo $basePath; ?>pages/index.php">
                        <img src="<?php echo $basePath; ?>logo_upc.png" alt="UPC Logo">
                        <div class="logo-text">
                            <h1>Revue de la Faculté de Théologie</h1>
                            <p>Université Protestante au Congo</p>
                        </div>
                    </a>
                </div>
                <nav class="main-nav">
                    <a href="<?php echo $basePath; ?>pages/index.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Accueil</a>
                    <a href="<?php echo $basePath; ?>pages/numeros.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'numeros.php') ? 'active' : ''; ?>">Numéros & Archives</a>
                    <a href="<?php echo $basePath; ?>pages/soumettre-article.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'soumettre-article.php') ? 'active' : ''; ?>">Soumettre</a>
                    <a href="<?php echo $basePath; ?>pages/consignes-auteurs.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'consignes-auteurs.php') ? 'active' : ''; ?>">Instructions</a>
                    <a href="<?php echo $basePath; ?>pages/comite-editorial.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'comite-editorial.php') ? 'active' : ''; ?>">Comité</a>
                    <a href="<?php echo $basePath; ?>pages/recherche.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'recherche.php') ? 'active' : ''; ?>">Recherche</a>
                </nav>
                <a href="<?php echo $basePath; ?>pages/soumettre-article.php" class="btn-submit">Soumettre un article</a>
                <button class="mobile-menu-toggle" aria-label="Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation -->
    <nav class="mobile-nav">
        <a href="<?php echo $basePath; ?>pages/index.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Accueil</a>
        <a href="<?php echo $basePath; ?>pages/numeros.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'numeros.php') ? 'active' : ''; ?>">Numéros & Archives</a>
        <a href="<?php echo $basePath; ?>pages/soumettre-article.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'soumettre-article.php') ? 'active' : ''; ?>">Soumettre</a>
        <a href="<?php echo $basePath; ?>pages/consignes-auteurs.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'consignes-auteurs.php') ? 'active' : ''; ?>">Instructions</a>
        <a href="<?php echo $basePath; ?>pages/comite-editorial.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'comite-editorial.php') ? 'active' : ''; ?>">Comité</a>
        <a href="<?php echo $basePath; ?>pages/recherche.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'recherche.php') ? 'active' : ''; ?>">Recherche</a>
        <a href="<?php echo $basePath; ?>pages/soumettre-article.php" class="btn btn-primary btn-submit-mobile">Soumettre un article</a>
    </nav>

