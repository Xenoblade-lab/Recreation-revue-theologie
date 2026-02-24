<?php
/**
 * Point d'entrée — Revue Congolaise de Théologie Protestante
 * Toutes les requêtes sont redirigées ici via .htaccess.
 */

// Session (démarrage une seule fois)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuration
require_once dirname(__DIR__) . '/config/config.php';

// Connexion BDD (optionnel en Phase 1 ; nécessaire dès qu'on lit les données)
// getDB() est défini dans includes/db.php
require_once dirname(__DIR__) . '/includes/db.php';

// Autoload basique (controllers, models, service)
spl_autoload_register(function ($class) {
    $base = dirname(__DIR__);
    if (strpos($class, 'Controllers\\') === 0) {
        $file = $base . '/controllers/' . str_replace('Controllers\\', '', $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    if (strpos($class, 'Models\\') === 0) {
        $file = $base . '/models/' . str_replace('Models\\', '', $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    if (strpos($class, 'Service\\') === 0) {
        $file = $base . '/service/' . str_replace('Service\\', '', $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    return false;
});

// Helpers d'authentification (requireAuth, requireRole, etc.)
require_once dirname(__DIR__) . '/includes/auth.php';
// Internationalisation (current_lang, set_lang, __)
require_once dirname(__DIR__) . '/includes/i18n.php';

// Base path pour le routeur (sous-dossier si le site n'est pas à la racine du vhost)
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = dirname($scriptName);
if (strpos($basePath, '\\') !== false) {
    $basePath = str_replace('\\', '/', $basePath);
}
if ($basePath === '/' || $basePath === '.') {
    $basePath = '';
}
require_once dirname(__DIR__) . '/router/Router.php';

Router\Router::setBasePath($basePath);

// Charger les routes
require_once dirname(__DIR__) . '/routes/web.php';
require_once dirname(__DIR__) . '/routes/api.php';

// Démarrer le routeur
Router\Router::run();
