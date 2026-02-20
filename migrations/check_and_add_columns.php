<?php
/**
 * Script pour vérifier et ajouter les colonnes manquantes
 * Usage: php migrations/check_and_add_columns.php
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Models\Database;

echo "Vérification des colonnes...\n\n";

$db = new Database();
$db->connect();

$dbname = $db->fetchOne("SELECT DATABASE() as db")['db'] ?? 'revue';

// Vérifier et ajouter volume_id dans revues
$checkSql = "SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.COLUMNS
             WHERE table_schema = :dbname AND table_name = 'revues' AND column_name = 'volume_id'";
$result = $db->fetchOne($checkSql, [':dbname' => $dbname]);

if (($result['count'] ?? 0) == 0) {
    echo "📝 Ajout de la colonne volume_id dans revues...\n";
    try {
        $db->execute("ALTER TABLE `revues` ADD COLUMN `volume_id` INT NULL, ADD INDEX `idx_volume_id` (`volume_id`)", []);
        echo "✅ Colonne volume_id ajoutée !\n";
    } catch (\Exception $e) {
        echo "❌ Erreur : " . $e->getMessage() . "\n";
    }
} else {
    echo "✅ La colonne volume_id existe déjà dans revues.\n";
}

// Vérifier et ajouter type dans revues
$checkSql = "SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.COLUMNS
             WHERE table_schema = :dbname AND table_name = 'revues' AND column_name = 'type'";
$result = $db->fetchOne($checkSql, [':dbname' => $dbname]);

if (($result['count'] ?? 0) == 0) {
    echo "📝 Ajout de la colonne type dans revues...\n";
    try {
        $db->execute("ALTER TABLE `revues` ADD COLUMN `type` ENUM('issue', 'special') DEFAULT 'issue'", []);
        echo "✅ Colonne type ajoutée !\n";
    } catch (\Exception $e) {
        echo "❌ Erreur : " . $e->getMessage() . "\n";
    }
} else {
    echo "✅ La colonne type existe déjà dans revues.\n";
}

// Vérifier et ajouter issue_id dans articles
$checkSql = "SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.COLUMNS
             WHERE table_schema = :dbname AND table_name = 'articles' AND column_name = 'issue_id'";
$result = $db->fetchOne($checkSql, [':dbname' => $dbname]);

if (($result['count'] ?? 0) == 0) {
    echo "📝 Ajout de la colonne issue_id dans articles...\n";
    try {
        $db->execute("ALTER TABLE `articles` ADD COLUMN `issue_id` INT NULL, ADD INDEX `idx_issue_id` (`issue_id`)", []);
        echo "✅ Colonne issue_id ajoutée !\n";
    } catch (\Exception $e) {
        echo "❌ Erreur : " . $e->getMessage() . "\n";
    }
} else {
    echo "✅ La colonne issue_id existe déjà dans articles.\n";
}

echo "\n✅ Vérification terminée !\n";

