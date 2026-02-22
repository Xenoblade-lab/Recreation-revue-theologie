<?php
namespace Service;

use Models\UserModel;

/**
 * Service d'authentification : login, logout, session, rôles.
 */
class AuthService
{
    private const SESSION_KEY = 'revue_user';

    /**
     * Tente une connexion. Retourne true si succès, false sinon.
     */
    public static function login(string $email, string $password): bool
    {
        $user = UserModel::getByEmail($email);
        if (!$user || $user['statut'] !== 'actif') {
            return false;
        }
        if (!password_verify($password, $user['password'])) {
            return false;
        }
        $_SESSION[self::SESSION_KEY] = [
            'id'     => (int) $user['id'],
            'email'  => $user['email'],
            'nom'    => $user['nom'],
            'prenom' => $user['prenom'],
            'role'   => $user['role'],
        ];
        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    /** Utilisateur connecté (tableau ou null) */
    public static function getUser(): ?array
    {
        return $_SESSION[self::SESSION_KEY] ?? null;
    }

    public static function isLoggedIn(): bool
    {
        return self::getUser() !== null;
    }

    /** Vérifie si l'utilisateur a l'un des rôles donnés (ex: admin, redacteur, redacteur en chef) */
    public static function hasRole(string ...$roles): bool
    {
        $user = self::getUser();
        if (!$user || empty($user['role'])) {
            return false;
        }
        return in_array($user['role'], $roles, true);
    }

    /**
     * URL de redirection après connexion selon le rôle.
     * admin → /admin, redacteur / redacteur en chef → /reviewer, auteur → /author, sinon → /
     */
    public static function getRedirectAfterLogin(): string
    {
        $user = self::getUser();
        if (!$user) {
            return defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/' : '/';
        }
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        switch ($user['role']) {
            case 'admin':
            case 'redacteur en chef':
                return $base . '/admin';
            case 'redacteur':
                return $base . '/reviewer';
            case 'auteur':
                return $base . '/author';
            default:
                return $base . '/';
        }
    }
}
