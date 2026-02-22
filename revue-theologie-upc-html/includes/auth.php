<?php
/**
 * Helpers d'authentification — à inclure après config et db.
 * Utilise Service\AuthService (charger le service avant ou via autoload).
 */

use Service\AuthService;

/**
 * Redirige vers la page de connexion si non connecté.
 * À appeler en début d'action pour les zones protégées.
 */
function requireAuth(): void
{
    if (!AuthService::isLoggedIn()) {
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
        http_response_code(403);
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        header('Location: ' . $base . '/');
        exit;
    }
}

/**
 * Zone réservée aux auteurs.
 */
function requireAuthor(): void
{
    requireRole('auteur');
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
