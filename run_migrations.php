<?php
/**
 * Script pour exécuter les migrations SQL
 * Usage: php run_migrations.php
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Models\Database;

echo "Démarrage des migrations...\n\n";

$db = new Database();
$db->connect();

// Liste des migrations à exécuter
$migrations = [
    'migrations/add_article_statuses.sql',
    'migrations/transform_notifications_table.sql', // Transformer la table existante
    'migrations/create_article_revisions_table.sql',
    'migrations/add_notification_types.sql',
    'migrations/create_revue_structure.sql', // Structure Revue → Volumes → Numéros
    'migrations/migrate_existing_data.sql' // Migration des données existantes
];

foreach ($migrations as $migration) {
    $filePath = __DIR__ . DIRECTORY_SEPARATOR . $migration;
    
    if (!file_exists($filePath)) {
        echo "⚠️  Fichier de migration introuvable : $migration\n";
        continue;
    }
    
    echo "📝 Exécution de : $migration\n";
    
    $sql = file_get_contents($filePath);
    
    // Séparer les requêtes par point-virgule
    $queries = array_filter(
        array_map('trim', explode(';', $sql)),
        function($query) {
            return !empty($query) && !preg_match('/^--/', $query) && !preg_match('/^\/\*/', $query);
        }
    );
    
    try {
        foreach ($queries as $query) {
            if (!empty(trim($query))) {
                $db->execute($query, []);
            }
        }
        echo "✅ Migration réussie : $migration\n\n";
    } catch (\Exception $e) {
        echo "❌ Erreur lors de la migration $migration : " . $e->getMessage() . "\n\n";
    }
}

echo "✅ Toutes les migrations ont été exécutées !\n";

