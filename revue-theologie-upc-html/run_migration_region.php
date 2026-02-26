<?php
/**
 * Exécute la migration add_paiement_region_details (colonnes region et payment_details).
 * À lancer une fois : php run_migration_region.php
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';

$sqls = [
    "ALTER TABLE paiements ADD COLUMN region VARCHAR(50) DEFAULT NULL AFTER moyen",
    "ALTER TABLE paiements ADD COLUMN payment_details TEXT DEFAULT NULL COMMENT 'JSON: phoneNumber ou cardLast4' AFTER region",
];

$db = getDB();
foreach ($sqls as $sql) {
    try {
        $db->exec($sql);
        echo "OK: " . substr($sql, 0, 60) . "...\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "Déjà fait (colonne existante): " . substr($sql, 0, 50) . "...\n";
        } else {
            echo "Erreur: " . $e->getMessage() . "\n";
            exit(1);
        }
    }
}

// Remplir la région pour les paiements où elle est vide (25=Afrique, 30=Europe, 35=Amérique)
try {
    $up = $db->exec("UPDATE paiements SET region = 'afrique' WHERE (region IS NULL OR region = '') AND montant = 25");
    $up += $db->exec("UPDATE paiements SET region = 'europe' WHERE (region IS NULL OR region = '') AND montant = 30");
    $up += $db->exec("UPDATE paiements SET region = 'amerique' WHERE (region IS NULL OR region = '') AND montant = 35");
    if ($up > 0) {
        echo "OK: $up paiement(s) mis à jour avec une région (déduite du montant).\n";
    }
} catch (PDOException $e) {
    echo "Info: pas de mise à jour région (table ou colonne absente?) " . $e->getMessage() . "\n";
}

echo "Migration terminée.\n";
