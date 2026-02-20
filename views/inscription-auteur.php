<?php
/**
 * Page d'inscription auteur
 * Revue de la Faculté de Théologie - UPC
 */

// Si déjà connecté, rediriger
require_once __DIR__ . '/../includes/auth.php';
if (isLoggedIn()) {
    header('Location: ' . (isAdmin() ? 'admin-dashboard.php' : 'espace-auteur.php'));
    exit;
}

$pageTitle = "Inscription Auteur";
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
                <h1>Inscription Auteur</h1>
                <p>Revue de la Faculté de Théologie - UPC</p>
            </div>

            <div class="auth-body">
                <?php if (isset($_GET['error'])): ?>
                    <div class="error-message" id="error-message">
                        <?php 
                        $errors = [
                            'email_exists' => 'Cet email est déjà utilisé.',
                            'password_mismatch' => 'Les mots de passe ne correspondent pas.',
                            'password_weak' => 'Le mot de passe doit contenir au moins 8 caractères.',
                            'required' => 'Veuillez remplir tous les champs obligatoires.',
                            'invalid_email' => 'Adresse email invalide.',
                            'database' => 'Erreur lors de l\'inscription. Veuillez réessayer.'
                        ];
                        echo htmlspecialchars($errors[$_GET['error']] ?? 'Une erreur est survenue.');
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="success-message" id="success-message">
                        Inscription réussie ! Vous pouvez maintenant vous connecter.
                    </div>
                <?php endif; ?>
                
                <form class="auth-form" id="register-form" method="POST" action="../gestion/inscription_auteur.php">
                    <div class="form-group">
                        <label for="nom">Nom *</label>
                        <input type="text" id="nom" name="nom" placeholder="Votre nom" required value="<?php echo isset($_GET['nom']) ? htmlspecialchars($_GET['nom']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom *</label>
                        <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required value="<?php echo isset($_GET['prenom']) ? htmlspecialchars($_GET['prenom']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Adresse email *</label>
                        <input type="email" id="email" name="email" placeholder="votre.email@exemple.com" required value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="affiliation">Institution / Université</label>
                        <input type="text" id="affiliation" name="affiliation" placeholder="Université Protestante au Congo" value="<?php echo isset($_GET['affiliation']) ? htmlspecialchars($_GET['affiliation']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="orcid">ORCID ID (optionnel)</label>
                        <input type="text" id="orcid" name="orcid" placeholder="0000-0000-0000-0000" pattern="[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}" value="<?php echo isset($_GET['orcid']) ? htmlspecialchars($_GET['orcid']) : ''; ?>">
                        <small>Format: 0000-0000-0000-0000</small>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe *</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="password" name="password" placeholder="••••••••" required minlength="8">
                            <button type="button" class="password-toggle" id="toggle-password">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="eye-open">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <small>Minimum 8 caractères</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Confirmer le mot de passe *</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="confirm-password" name="confirm-password" placeholder="••••••••" required minlength="8">
                            <button type="button" class="password-toggle" id="toggle-confirm-password">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="eye-open">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="terms" id="terms" required>
                            <span>J'accepte les <a href="politique-editoriale.php" style="color: var(--color-red);">conditions d'utilisation</a></span>
                        </label>
                    </div>

                    <button type="submit" class="submit-btn">Créer mon compte</button>
                </form>
            </div>

            <div class="auth-footer">
                Vous avez déjà un compte ? <a href="login.php">Se connecter</a>
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

document.getElementById('toggle-confirm-password')?.addEventListener('click', function() {
    const passwordInput = document.getElementById('confirm-password');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
});

// Validation côté client
document.getElementById('register-form')?.addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas.');
        return false;
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
