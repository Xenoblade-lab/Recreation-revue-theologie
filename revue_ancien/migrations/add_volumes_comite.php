<?php
/**
 * Ajoute les colonnes comité éditorial à la table volumes (un comité par année).
 * Usage: php migrations/add_volumes_comite.php
 */

$autoload = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
if (!file_exists($autoload)) {
    $autoload = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
}
require_once $autoload;

$db = new \Models\Database();
$db->connect();

$columns = [
    ['name' => 'comite_editorial', 'def' => 'TEXT NULL', 'after' => 'description'],
    ['name' => 'redacteur_chef', 'def' => 'VARCHAR(255) NULL', 'after' => 'comite_editorial'],
];
foreach ($columns as $col) {
    $r = $db->fetchOne(
        "SELECT COUNT(*) as c FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'volumes' AND column_name = ?",
        [$col['name']]
    );
    if (($r['c'] ?? 0) > 0) {
        echo "Colonne volumes.{$col['name']} existe déjà.\n";
    } else {
        $db->execute("ALTER TABLE volumes ADD COLUMN `{$col['name']}` {$col['def']} AFTER `{$col['after']}`", []);
        echo "Colonne volumes.{$col['name']} ajoutée.\n";
    }
}
echo "Terminé.\n";
