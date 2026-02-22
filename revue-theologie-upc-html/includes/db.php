<?php
/**
 * Connexion à la base de données
 * Utilise la base dans laquelle revue_theologie_2.sql a été importé.
 */

if (!defined('BASE_PATH')) {
    require_once dirname(__DIR__) . '/config/config.php';
}

function getDB(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                DB_HOST,
                DB_NAME,
                DB_CHARSET
            );
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            error_log('DB Error: ' . $e->getMessage());
            if (defined('DEBUG') && DEBUG) {
                die('Erreur de connexion à la base de données : ' . $e->getMessage());
            }
            die('Erreur de connexion. Veuillez contacter l\'administrateur.');
        }
    }

    return $pdo;
}
