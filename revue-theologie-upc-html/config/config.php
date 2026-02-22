<?php
/**
 * Configuration — Revue de Théologie UPC
 * Projet : revue-theologie-upc-html (refactorisation)
 *
 * Base de données : utiliser la base dans laquelle vous avez importé
 * revue-theologie-upc-html/frontend/revue_theologie_2.sql (pas revue.sql).
 */
if (!defined('REVUE_CONFIG_LOADED')) {
    define('REVUE_CONFIG_LOADED', true);

    // Racine du projet (dossier revue-theologie-upc-html)
    define('BASE_PATH', dirname(__DIR__));

    // URL de base du site (sans slash final pour les liens)
    // Ex. http://localhost/Recreation-revu-theologie/revue-theologie-upc-html/public
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $script = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    if (!defined('BASE_URL')) {
        define('BASE_URL', $protocol . '://' . $host . $script);
    }

    // Base de données : nom de la base où vous avez importé revue_theologie_2.sql (pas revue.sql)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'revue'); // Modifier si votre base a un autre nom
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_CHARSET', 'utf8mb4');

    define('DEBUG', true);
}
