<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Revue de Théologie UPC</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/auth-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-page">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-logo">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h1>Créer un compte</h1>
                    <p>Revue de la Faculté de Théologie - UPC</p>
                </div>

                <div class="auth-body">
                    <div class="error-message" id="error-message"></div>
                    <div class="success-message" id="success-message"></div>
                    
                    <form class="auth-form" id="register-form" method="POST" action="<?= Router\Router::route('register') ?>">
                        <div class="form-group">
                            <label for="fullname">Nom complet</label>
                            <input type="text" id="fullname" name="nom" placeholder="Votre nom " required>
                        </div>
                        
                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom" placeholder="Votre prenom" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Adresse email</label>
                            <input type="email" id="email" name="email" placeholder="votre.email@exemple.com" required>
                        </div>

                        <div class="form-group">
                            <label for="institution">Institution / Université</label>
                            <input type="text" id="institution" name="institution" placeholder="Université Protestante au Congo">
                        </div>

                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="password" name="password" placeholder="••••••••" required minlength="8">
                                <button type="button" class="password-toggle" id="toggle-password">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="eye-open">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm-password">Confirmer le mot de passe</label>
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
                                <span>J'accepte les <a href="#" style="color: var(--color-red);">conditions d'utilisation</a></span>
                            </label>
                        </div>

                        <button type="submit" class="submit-btn">Créer mon compte</button>
                    </form>

                    <div class="divider">ou s'inscrire avec</div>

                    <div class="social-login">
                        <button class="social-btn" id="google-register">
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
                    Vous avez déjà un compte ? <a href="<?= Router\Router::route('login') ?>">Se connecter</a>
                </div>
            </div>

            <div class="back-home">
                <a href="<?= Router\Router::route('') ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <script src="./js/auth-script.js"></script>
</body>
</html>