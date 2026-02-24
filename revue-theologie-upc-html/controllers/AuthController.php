<?php
namespace Controllers;

use Service\AuthService;
use Models\UserModel;

/**
 * Contrôleur authentification : login, register, logout, mot de passe oublié.
 */
class AuthController
{
    private function redirectIfLoggedIn(): void
    {
        if (AuthService::isLoggedIn()) {
            header('Location: ' . AuthService::getRedirectAfterLogin());
            exit;
        }
    }

    private function base(): string
    {
        return defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
    }

    public function showLogin(array $params = []): void
    {
        $this->redirectIfLoggedIn();
        $error = $_SESSION['auth_error'] ?? null;
        unset($_SESSION['auth_error']);
        $base = $this->base();
        $pageTitle = 'Connexion | Revue de la Faculté de Théologie - UPC';
        ob_start();
        require BASE_PATH . '/views/auth/login.php';
        $viewContent = ob_get_clean();
        require BASE_PATH . '/views/layouts/auth.php';
    }

    public function login(array $params = []): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/login');
            exit;
        }
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!$email || !$password) {
            $_SESSION['auth_error'] = 'Veuillez remplir l’email et le mot de passe.';
            header('Location: ' . $this->base() . '/login');
            exit;
        }
        if (AuthService::login($email, $password)) {
            header('Location: ' . AuthService::getRedirectAfterLogin());
            exit;
        }
        $_SESSION['auth_error'] = 'Email ou mot de passe incorrect.';
        header('Location: ' . $this->base() . '/login');
        exit;
    }

    public function showRegister(array $params = []): void
    {
        $this->redirectIfLoggedIn();
        $error = $_SESSION['auth_error'] ?? null;
        $old = $_SESSION['auth_old'] ?? [];
        unset($_SESSION['auth_error'], $_SESSION['auth_old']);
        $base = $this->base();
        $pageTitle = 'Créer un compte | Revue de la Faculté de Théologie - UPC';
        ob_start();
        require BASE_PATH . '/views/auth/register.php';
        $viewContent = ob_get_clean();
        require BASE_PATH . '/views/layouts/auth.php';
    }

    public function register(array $params = []): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/register');
            exit;
        }
        $prenom = trim($_POST['prenom'] ?? '');
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $_SESSION['auth_old'] = ['prenom' => $prenom, 'nom' => $nom, 'email' => $email];

        if (!$prenom || !$nom || !$email || !$password) {
            $_SESSION['auth_error'] = 'Tous les champs obligatoires doivent être remplis.';
            header('Location: ' . $this->base() . '/register');
            exit;
        }
        if (mb_strlen($password) < 8) {
            $_SESSION['auth_error'] = 'Le mot de passe doit contenir au moins 8 caractères.';
            header('Location: ' . $this->base() . '/register');
            exit;
        }
        if (UserModel::emailExists($email)) {
            $_SESSION['auth_error'] = 'Cette adresse email est déjà utilisée.';
            header('Location: ' . $this->base() . '/register');
            exit;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $userId = UserModel::create($nom, $prenom, $email, $hash, 'user');
        if (!$userId) {
            $_SESSION['auth_error'] = 'Une erreur est survenue. Veuillez réessayer.';
            header('Location: ' . $this->base() . '/register');
            exit;
        }
        unset($_SESSION['auth_old']);
        AuthService::login($email, $password);
        header('Location: ' . $this->base() . '/');
        exit;
    }

    public function logout(array $params = []): void
    {
        AuthService::logout();
        $base = $this->base();
        header('Location: ' . ($base !== '' ? $base . '/' : '/'));
        exit;
    }

    public function showForgotPassword(array $params = []): void
    {
        $success = !empty($_SESSION['forgot_success']);
        $error = $_SESSION['auth_error'] ?? null;
        unset($_SESSION['forgot_success'], $_SESSION['auth_error']);
        $base = $this->base();
        $pageTitle = 'Mot de passe oublié | Revue Congolaise de Théologie Protestante';
        ob_start();
        require BASE_PATH . '/views/auth/forgot-password.php';
        $viewContent = ob_get_clean();
        require BASE_PATH . '/views/layouts/auth.php';
    }

    public function forgotPassword(array $params = []): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/forgot-password');
            exit;
        }
        $email = trim($_POST['email'] ?? '');
        if (!$email) {
            $_SESSION['auth_error'] = 'Veuillez indiquer votre adresse email.';
            header('Location: ' . $this->base() . '/forgot-password');
            exit;
        }
        $user = UserModel::getByEmail($email);
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $db = getDB();
            $stmt = $db->prepare("INSERT INTO password_reset_tokens (email, token, created_at) VALUES (:email, :token, NOW()) ON DUPLICATE KEY UPDATE token = :token2, created_at = NOW()");
            $stmt->execute([':email' => $email, ':token' => $token, ':token2' => $token]);
            // TODO: envoyer email avec lien de réinitialisation (BASE_URL/reset-password?token=...)
        }
        $_SESSION['forgot_success'] = true;
        header('Location: ' . $this->base() . '/forgot-password');
        exit;
    }
}
