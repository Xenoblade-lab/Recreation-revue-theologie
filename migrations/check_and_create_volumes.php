<?php
/**
 * Script pour vérifier et créer la table volumes si elle n'existe pas
 * Usage: php migrations/check_and_create_volumes.php
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Models\Database;

echo "Vérification de la table volumes...\n\n";

$db = new Database();
$db->connect();

// Vérifier si la table volumes existe
$checkSql = "SELECT COUNT(*) as count FROM information_schema.tables 
             WHERE table_schema = DATABASE() AND table_name = 'volumes'";
$result = $db->fetchOne($checkSql);

if (($result['count'] ?? 0) == 0) {
    echo "📝 Création de la table volumes...\n";
    
    $createSql = "CREATE TABLE `volumes` (
      `id` INT PRIMARY KEY AUTO_INCREMENT,
      `annee` INT NOT NULL UNIQUE,
      `numero_volume` VARCHAR(50),
      `description` TEXT,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      INDEX `idx_annee` (`annee`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    try {
        $db->execute($createSql, []);
        echo "✅ Table volumes créée avec succès !\n";
    } catch (\Exception $e) {
        echo "❌ Erreur : " . $e->getMessage() . "\n";
    }
} else {
    echo "✅ La table volumes existe déjà.\n";
}

// Vérifier si la table revue_info existe
$checkSql = "SELECT COUNT(*) as count FROM information_schema.tables 
             WHERE table_schema = DATABASE() AND table_name = 'revue_info'";
$result = $db->fetchOne($checkSql);

if (($result['count'] ?? 0) == 0) {
    echo "📝 Création de la table revue_info...\n";
    
    $createSql = "CREATE TABLE `revue_info` (
      `id` INT PRIMARY KEY AUTO_INCREMENT,
      `nom_officiel` VARCHAR(255) NOT NULL DEFAULT 'Revue de Théologie de l\'UPC',
      `description` TEXT,
      `ligne_editoriale` TEXT,
      `objectifs` TEXT,
      `domaines_couverts` TEXT,
      `issn` VARCHAR(50),
      `comite_scientifique` TEXT,
      `comite_redaction` TEXT,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    try {
        $db->execute($createSql, []);
        
        // Insérer une entrée par défaut
        $insertSql = "INSERT INTO `revue_info` (`nom_officiel`, `description`, `created_at`, `updated_at`) 
                      VALUES ('Revue de Théologie de l\'UPC', 'Revue scientifique de la Faculté de Théologie de l\'Université Protestante au Congo', NOW(), NOW())";
        $db->execute($insertSql, []);
        
        echo "✅ Table revue_info créée avec succès !\n";
    } catch (\Exception $e) {
        echo "❌ Erreur : " . $e->getMessage() . "\n";
    }
} else {
    echo "✅ La table revue_info existe déjà.\n";
}

echo "\n✅ Vérification terminée !\n";

