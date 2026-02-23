<?php
/**
 * Page de connexion
 * Revue de la Faculté de Théologie - UPC
 */

// Si déjà connecté, rediriger selon le rôle
require_once __DIR__ . '/../includes/auth.php';
if (isLoggedIn()) {
    if (isAdmin()) {
        header('Location: ' . dirname($_SERVER['PHP_SELF']) . '/admin-dashboard.php');
    } elseif (isAuthor()) {
        header('Location: ' . dirname($_SERVER['PHP_SELF']) . '/espace-auteur.php');
    } else {
        header('Location: ' . dirname($_SERVER['PHP_SELF']) . '/index.php');
    }
    exit;
}

$pageTitle = "Connexion";
$additionalCSS = ['../auth-styles.css'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h1>Connexion</h1>
                <p>Revue de la Faculté de Théologie - UPC</p>
            </div>

            <div class="auth-body">
                <?php if (isset($_GET['error'])): ?>
                    <div class="error-message" id="error-message">
                        <?php 
                        $errors = [
                            'invalid' => 'Email ou mot de passe incorrect.',
                            'suspended' => 'Votre compte a été suspendu. Contactez l\'administrateur.',
                            'required' => 'Veuillez remplir tous les champs.',
                            'session' => 'Votre session a expiré. Veuillez vous reconnecter.'
                        ];
                        echo htmlspecialchars($errors[$_GET['error']] ?? 'Une erreur est survenue.');
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="success-message" id="success-message">
                        <?php 
                        $success = [
                            'registered' => 'Inscription réussie ! Vous pouvez maintenant vous connecter.',
                            'logout' => 'Vous avez été déconnecté avec succès.'
                        ];
                        echo htmlspecialchars($success[$_GET['success']] ?? '');
                        ?>
                    </div>
                <?php endif; ?>
                
                <form class="auth-form" id="login-form" method="POST" action="../gestion/login.php">
                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" id="email" name="email" placeholder="votre.email@exemple.com" required value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                            <button type="button" class="password-toggle" id="toggle-password">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="eye-open">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" id="remember" value="1">
                            <span>Se souvenir de moi</span>
                        </label>
                        <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="submit-btn">Se connecter</button>
                </form>

                <div class="divider">ou continuer avec</div>

                <div class="social-login">
                    <button type="button" class="social-btn" id="google-login">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Google
                    </button>
                </div>
            </div>

            <div class="auth-footer">
                Vous n'avez pas de compte ? <a href="inscription-auteur.php">Créer un compte auteur</a>
            </div>
        </div>

        <div class="back-home">
            <a href="index.php">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à l'accueil
            </a>
        </div>
    </div>
</div>

<script src="../js/main.js"></script>
<script>
// Toggle password visibility
document.getElementById('toggle-password')?.addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
