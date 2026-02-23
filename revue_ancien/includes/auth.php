<?php
/**
 * Fonctions d'authentification et de gestion des sessions
 * Revue de la Faculté de Théologie - UPC
 */

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Vérifie si un utilisateur est connecté
 * @return bool True si connecté, false sinon
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Vérifie si l'utilisateur connecté est un administrateur
 * @return bool True si admin, false sinon
 */
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Vérifie si l'utilisateur connecté est un auteur
 * @return bool True si auteur, false sinon
 */
function isAuthor() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'auteur';
}

/**
 * Vérifie si l'utilisateur connecté est un reviewer/évaluateur
 * @return bool True si reviewer, false sinon
 */
function isReviewer() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'reviewer';
}

/**
 * Récupère l'ID de l'utilisateur connecté
 * @return int|null ID de l'utilisateur ou null si non connecté
 */
function getUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

/**
 * Récupère le rôle de l'utilisateur connecté
 * @return string|null Rôle de l'utilisateur ou null si non connecté
 */
function getUserRole() {
    return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
}

/**
 * Récupère les informations de l'utilisateur connecté
 * @return array|null Tableau avec les infos utilisateur ou null si non connecté
 */
function getUserInfo() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['user_email'] ?? null,
        'nom' => $_SESSION['user_nom'] ?? null,
        'prenom' => $_SESSION['user_prenom'] ?? null,
        'role' => $_SESSION['user_role'] ?? null,
    ];
}

/**
 * Requiert une connexion utilisateur, redirige vers login si non connecté
 * @param string $redirect URL de redirection en cas d'échec (par défaut: login.php)
 */
function requireLogin($redirect = 'login.php') {
    if (!isLoggedIn()) {
        header('Location: ' . $redirect);
        exit;
    }
}

/**
 * Requiert le rôle administrateur, redirige si non admin
 * @param string $redirect URL de redirection en cas d'échec (par défaut: index.php)
 */
function requireAdmin($redirect = 'index.php') {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . $redirect);
        exit;
    }
}

/**
 * Requiert le rôle auteur, redirige si non auteur
 * @param string $redirect URL de redirection en cas d'échec (par défaut: index.php)
 */
function requireAuthor($redirect = 'index.php') {
    requireLogin();
    if (!isAuthor()) {
        header('Location: ' . $redirect);
        exit;
    }
}

/**
 * Déconnecte l'utilisateur et détruit la session
 */
function logout() {
    $_SESSION = array();
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
}

