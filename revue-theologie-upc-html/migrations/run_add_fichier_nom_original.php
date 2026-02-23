<?php
/**
 * Exécuter une fois pour ajouter la colonne fichier_nom_original.
 * En ligne de commande : php migrations/run_add_fichier_nom_original.php
 * Ou ouvrir dans le navigateur : .../migrations/run_add_fichier_nom_original.php (si PHP exécute ce dossier)
 */
$base = dirname(__DIR__);
require_once $base . '/config/config.php';
require_once $base . '/includes/db.php';

$sql = "ALTER TABLE articles ADD COLUMN fichier_nom_original VARCHAR(255) NULL DEFAULT NULL AFTER fichier_path";

try {
    $db = getDB();
    $db->exec($sql);
    echo "Migration OK : colonne fichier_nom_original ajoutée à la table articles.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "La colonne fichier_nom_original existe déjà. Rien à faire.\n";
        exit(0);
    }
    echo "Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
