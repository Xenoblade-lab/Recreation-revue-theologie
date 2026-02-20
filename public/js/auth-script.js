// Authentication page scripts
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    const passwordToggles = document.querySelectorAll('.password-toggle');
    
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            // Toggle icon (you can add different SVG for eye-closed if needed)
            this.classList.toggle('active');
        });
    });

    // Login form submission
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Basic validation
            if (!email || !password) {
                showError('Veuillez remplir tous les champs');
                return;
            }
            
            try {
                const response = await fetch('http://localhost/Revue-Theologie-Upc/public/login', {
                    method: 'POST',
                    body: JSON.stringify({ email, password }),
                    headers: { 'Content-Type': 'application/json' }
                });

                const result = await response.json();
                
                if (result.status === 200) {
                    showSuccess('Connexion réussie ! Redirection...');
                    setTimeout(() => {
                        if (result.redirect) {
                            window.location.href = result.redirect;
                        } else {
                            window.location.href = 'http://localhost/Revue-Theologie-Upc/public/';
                        }
                    }, 1000);
                } else {
                    showError(result.message || 'Erreur de connexion');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showError('Une erreur est survenue lors de la connexion');
            }
        });
    }

    // Register form submission
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit',async function(e) {
            e.preventDefault();
            
            const fullname = document.getElementById('fullname').value;
            const prenom = document.getElementById("prenom").value;
            const email = document.getElementById('email').value;
            const institution = document.getElementById('institution').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            const terms = document.getElementById('terms').checked;
            
            // Validation
            if (!fullname || !email || !password || !confirmPassword) {
                showError('Veuillez remplir tous les champs obligatoires');
                return;
            }
            
            if (password.length < 8) {
                showError('Le mot de passe doit contenir au moins 8 caractères');
                return;
            }
            
            if (password !== confirmPassword) {
                showError('Les mots de passe ne correspondent pas');
                return;
            }
            
            if (!terms) {
                showError('Veuillez accepter les conditions d\'utilisation');
                return;
            }
            
            // Envoyer les données d'inscription
            try {
                const response = await fetch('http://localhost/Revue-Theologie-Upc/public/register', {
                    method: 'POST',
                    body: JSON.stringify({
                        nom: fullname,
                        prenom: prenom,
                        email: email,
                        institution: institution,
                        password: password,
                        'confirm-password': confirmPassword,
                        terms: terms
                    }),
                    headers: { 'Content-Type': 'application/json' }
                });

                const result = await response.json();
                
                if (result.status === 200) {
                    showSuccess('Compte créé avec succès ! Redirection...');
                    setTimeout(() => {
                        if (result.redirect) {
                            window.location.href = result.redirect;
                        } else {
                            window.location.href = 'http://localhost/Revue-Theologie-Upc/public/login';
                        }
                    }, 2000);
                } else {
                    showError(result.message || 'Erreur lors de l\'inscription');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showError('Une erreur est survenue lors de l\'inscription');
            }
        });
    }

    // Social login buttons
    const googleLoginBtn = document.getElementById('google-login');
    const googleRegisterBtn = document.getElementById('google-register');
    
    if (googleLoginBtn) {
        googleLoginBtn.addEventListener('click', function() {
            console.log('Google login clicked');
            // Implement Google OAuth here
            alert('Connexion avec Google (à implémenter)');
        });
    }
    
    if (googleRegisterBtn) {
        googleRegisterBtn.addEventListener('click', function() {
            console.log('Google register clicked');
            // Implement Google OAuth here
            alert('Inscription avec Google (à implémenter)');
        });
    }

    // Helper functions
    function showError(message) {
        const errorDiv = document.getElementById('error-message');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.classList.add('show');
            
            setTimeout(() => {
                errorDiv.classList.remove('show');
            }, 5000);
        }
    }

    function showSuccess(message) {
        const successDiv = document.getElementById('success-message');
        if (successDiv) {
            successDiv.textContent = message;
            successDiv.classList.add('show');
        }
    }
});