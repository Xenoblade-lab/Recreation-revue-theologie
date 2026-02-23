<?php
/**
 * Configuration et connexion à la base de données
 * Revue de la Faculté de Théologie - UPC
 */

// Configuration de la base de données
// Base de données simplifiée : revue_theologie
define('DB_HOST', 'localhost');
define('DB_NAME', 'revue_theologie');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Mode debug (true = développement, false = production)
// À mettre à false en production pour ne pas exposer les erreurs
if (!defined('DEBUG')) {
    define('DEBUG', true);
}

/**
 * Connexion à la base de données avec PDO
 * @return PDO|null Retourne l'instance PDO ou null en cas d'erreur
 */
function getDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Erreur de connexion à la base de données : " . $e->getMessage());
            // En production, ne pas afficher l'erreur directement
            if (defined('DEBUG') && DEBUG) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            } else {
                die("Erreur de connexion à la base de données. Veuillez contacter l'administrateur.");
            }
        }
    }
    
    return $pdo;
}

// Initialiser la connexion (seulement si pas déjà initialisé)
if (!isset($pdo)) {
    $pdo = getDBConnection();
}

