<?php
/**
 * Traitement de la déconnexion
 * Revue de la Faculté de Théologie - UPC
 */

require_once __DIR__ . '/../includes/auth.php';

// Démarrer la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Logger la déconnexion si l'utilisateur est connecté
if (isLoggedIn()) {
    try {
        require_once __DIR__ . '/../includes/db.php';
        $logStmt = $pdo->prepare("INSERT INTO logs (user_id, action, model_type, model_id, description, ip_address, user_agent) VALUES (?, 'logout', 'User', ?, 'Déconnexion', ?, ?)");
        $logStmt->execute([
            getUserId(),
            getUserId(),
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    } catch (Exception $e) {
        // Ignorer les erreurs de log
        error_log("Erreur lors de l'enregistrement du log: " . $e->getMessage());
    }
}

// Supprimer le cookie "Se souvenir de moi"
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/', '', false, true);
}

// Détruire la session
logout();

// Rediriger vers la page de connexion avec message de succès
$baseUrl = dirname(dirname($_SERVER['PHP_SELF']));
header('Location: ' . $baseUrl . '/pages/login.php?success=logout');
exit;

