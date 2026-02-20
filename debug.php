<?php
/**
 * Fichier de debug pour diagnostiquer les problèmes
 */
echo "<h1>Debug Information</h1>";

echo "<h2>Informations serveur</h2>";
echo "<pre>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'N/A') . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . "\n";
echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'N/A') . "\n";
echo "</pre>";

echo "<h2>Chemins</h2>";
echo "<pre>";
echo "__DIR__: " . __DIR__ . "\n";
echo "getcwd(): " . getcwd() . "\n";
echo "</pre>";

echo "<h2>Test fichiers</h2>";
$files = [
    'pages/login.php',
    'pages/index.php',
    'pages/inscription-auteur.php',
    'includes/header.php',
    'includes/auth.php',
    'includes/db.php'
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        echo "<p style='color: green;'>✓ $file existe</p>";
    } else {
        echo "<p style='color: red;'>✗ $file n'existe pas</p>";
    }
}

echo "<h2>Test URL</h2>";
$baseUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/Revue test 2/';
echo "<p>URL de base: <a href='$baseUrl'>$baseUrl</a></p>";
echo "<p>Test login: <a href='{$baseUrl}pages/login.php'>{$baseUrl}pages/login.php</a></p>";
echo "<p>Test index: <a href='{$baseUrl}pages/index.php'>{$baseUrl}pages/index.php</a></p>";

echo "<h2>Test includes</h2>";
try {
    require_once __DIR__ . '/includes/db.php';
    echo "<p style='color: green;'>✓ includes/db.php chargé</p>";
    if (isset($pdo)) {
        echo "<p style='color: green;'>✓ PDO initialisé</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Erreur: " . $e->getMessage() . "</p>";
}
?>

