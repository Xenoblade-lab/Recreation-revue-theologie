<?php
/**
 * Helpers d'authentification — à inclure après config et db.
 * Utilise Service\AuthService (charger le service avant ou via autoload).
 */

use Service\AuthService;

/**
 * Libère le verrou de session pour éviter de bloquer les autres requêtes (ex. refresh).
 * À appeler dès qu'on n'a plus besoin d'écrire en session (après lecture des flash, avant rendu).
 */
function release_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_write_close();
    }
}

/**
 * Redirige vers la page de connexion si non connecté.
 * À appeler en début d'action pour les zones protégées.
 */
function requireAuth(): void
{
    if (!AuthService::isLoggedIn()) {
        release_session();
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        header('Location: ' . $base . '/login');
        exit;
    }
}

/**
 * Vérifie que l'utilisateur a l'un des rôles donnés.
 * Sinon : redirection vers /login ou / (si connecté mais mauvais rôle → accueil ou 403).
 */
function requireRole(string ...$roles): void
{
    requireAuth();
    if (!AuthService::hasRole(...$roles)) {
        release_session();
        http_response_code(403);
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        header('Location: ' . $base . '/');
        exit;
    }
}

/**
 * Zone réservée aux auteurs : utilisateur connecté + (rôle auteur OU abonnement actif).
 * Sinon redirection vers /author/s-abonner.
 */
function requireAuthor(): void
{
    requireAuth();
    $user = AuthService::getUser();
    if (!$user || empty($user['id'])) {
        return;
    }
    if (AuthService::hasRole('auteur')) {
        return;
    }
    if (AuthService::hasRole('admin')) {
        return;
    }
    if (class_exists('Models\AbonnementModel') && \Models\AbonnementModel::hasActiveSubscription((int) $user['id'])) {
        return;
    }
    release_session();
    $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
    header('Location: ' . $base . '/author/s-abonner');
    exit;
}

/**
 * Permet l'accès à la page S'abonner pour tout utilisateur connecté (sans exiger le rôle auteur).
 */
function requireAuthorOrSubscribe(): void
{
    requireAuth();
}

/**
 * Zone réservée aux évaluateurs (redacteur, redacteur en chef).
 */
function requireReviewer(): void
{
    requireRole('redacteur', 'redacteur en chef');
}

/**
 * Zone réservée aux administrateurs.
 */
function requireAdmin(): void
{
    requireRole('admin', 'redacteur en chef');
}
