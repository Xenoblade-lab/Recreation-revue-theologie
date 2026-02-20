<?php
/**
 * Traitement de la connexion
 * Revue de la Faculté de Théologie - UPC
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

// Vérifier que c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $baseUrl = dirname(dirname($_SERVER['PHP_SELF']));
    header('Location: ' . $baseUrl . '/pages/login.php?error=invalid_request');
    exit;
}

// Récupérer les données du formulaire
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']) && $_POST['remember'] == '1';

// Base URL pour les redirections
$baseUrl = dirname(dirname($_SERVER['PHP_SELF']));

// Validation
if (empty($email) || empty($password)) {
    header('Location: ' . $baseUrl . '/pages/login.php?error=required&email=' . urlencode($email));
    exit;
}

// Vérifier l'email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . $baseUrl . '/pages/login.php?error=invalid_email&email=' . urlencode($email));
    exit;
}

try {
    // Rechercher l'utilisateur
    $stmt = $pdo->prepare("SELECT id, nom, prenom, email, password, role, statut, affiliation FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Vérifier si l'utilisateur existe et si le mot de passe est correct
    if (!$user || !password_verify($password, $user['password'])) {
        header('Location: ' . $baseUrl . '/pages/login.php?error=invalid&email=' . urlencode($email));
        exit;
    }

    // Vérifier si le compte est actif
    if ($user['statut'] !== 'actif') {
        header('Location: ' . $baseUrl . '/pages/login.php?error=suspended&email=' . urlencode($email));
        exit;
    }

    // Démarrer la session si elle n'est pas déjà démarrée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Créer la session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_nom'] = $user['nom'];
    $_SESSION['user_prenom'] = $user['prenom'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_affiliation'] = $user['affiliation'] ?? null;
    $_SESSION['logged_in'] = true;
    $_SESSION['login_time'] = time();

    // Cookie "Se souvenir de moi" (30 jours)
    if ($remember) {
        $cookieValue = base64_encode($user['id'] . ':' . hash('sha256', $user['email'] . $user['password']));
        setcookie('remember_me', $cookieValue, time() + (30 * 24 * 60 * 60), '/', '', false, true);
    }

    // Logger l'action
    try {
        $logStmt = $pdo->prepare("INSERT INTO logs (user_id, action, model_type, model_id, description, ip_address, user_agent) VALUES (?, 'login', 'User', ?, 'Connexion réussie', ?, ?)");
        $logStmt->execute([
            $user['id'],
            $user['id'],
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    } catch (Exception $e) {
        // Ignorer les erreurs de log
        error_log("Erreur lors de l'enregistrement du log: " . $e->getMessage());
    }

    // Rediriger selon le rôle
    $baseUrl = dirname(dirname($_SERVER['PHP_SELF']));
    switch ($user['role']) {
        case 'admin':
            $redirectUrl = $baseUrl . '/pages/admin-dashboard.php';
            break;
        case 'auteur':
        case 'reviewer':
            $redirectUrl = $baseUrl . '/pages/espace-auteur.php';
            break;
        default:
            $redirectUrl = $baseUrl . '/pages/index.php';
            break;
    }

    header('Location: ' . $redirectUrl);
    exit;

} catch (PDOException $e) {
    error_log("Erreur de connexion: " . $e->getMessage());
    $baseUrl = dirname(dirname($_SERVER['PHP_SELF']));
    header('Location: ' . $baseUrl . '/pages/login.php?error=database&email=' . urlencode($email));
    exit;
}

