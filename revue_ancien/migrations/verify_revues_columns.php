<?php
/**
 * Script pour vérifier les colonnes de la table revues
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Models\Database;

echo "Vérification des colonnes de la table revues...\n\n";

$db = new Database();
$db->connect();

$dbname = $db->fetchOne("SELECT DATABASE() as db")['db'] ?? 'revue';

// Liste des colonnes nécessaires
$requiredColumns = [
    'volume_id' => "INT NULL",
    'type' => "ENUM('issue', 'special') DEFAULT 'issue'"
];

foreach ($requiredColumns as $column => $definition) {
    $checkSql = "SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.COLUMNS
                 WHERE table_schema = :dbname AND table_name = 'revues' AND column_name = :column";
    $result = $db->fetchOne($checkSql, [':dbname' => $dbname, ':column' => $column]);
    
    if (($result['count'] ?? 0) == 0) {
        echo "📝 Ajout de la colonne $column dans revues...\n";
        try {
            if ($column === 'volume_id') {
                $db->execute("ALTER TABLE `revues` ADD COLUMN `volume_id` INT NULL, ADD INDEX `idx_volume_id` (`volume_id`)", []);
            } else if ($column === 'type') {
                $db->execute("ALTER TABLE `revues` ADD COLUMN `type` ENUM('issue', 'special') DEFAULT 'issue'", []);
            }
            echo "✅ Colonne $column ajoutée !\n";
        } catch (\Exception $e) {
            echo "❌ Erreur : " . $e->getMessage() . "\n";
        }
    } else {
        echo "✅ La colonne $column existe déjà dans revues.\n";
    }
}

// Afficher toutes les colonnes de la table revues
echo "\n📋 Colonnes actuelles de la table revues :\n";
$columns = $db->fetchAll("
    SELECT column_name, data_type, is_nullable, column_default
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE table_schema = :dbname AND table_name = 'revues'
    ORDER BY ordinal_position
", [':dbname' => $dbname]);

foreach ($columns as $col) {
    echo "  - {$col['column_name']} ({$col['data_type']}) " . 
         ($col['is_nullable'] === 'YES' ? 'NULL' : 'NOT NULL') . 
         ($col['column_default'] ? " DEFAULT {$col['column_default']}" : '') . "\n";
}

echo "\n✅ Vérification terminée !\n";

