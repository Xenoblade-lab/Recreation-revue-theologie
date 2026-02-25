<?php
/**
 * Helpers CSRF — protection contre les attaques Cross-Site Request Forgery.
 * À inclure après session_start() et auth.php (session doit être active).
 */

/**
 * Retourne le jeton CSRF de la session ; en génère un si absent.
 */
function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return '';
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Retourne le HTML du champ caché à placer dans chaque formulaire POST.
 */
function csrf_field(): string
{
    $token = csrf_token();
    if ($token === '') {
        return '';
    }
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

/**
 * Vérifie que la requête POST contient un jeton CSRF valide (présent et égal à celui en session).
 * À appeler en début de traitement de chaque action POST sensible.
 */
function validate_csrf(): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return false;
    }
    $sent = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : '';
    $expected = isset($_SESSION['csrf_token']) ? (string) $_SESSION['csrf_token'] : '';
    return $sent !== '' && hash_equals($expected, $sent);
}
