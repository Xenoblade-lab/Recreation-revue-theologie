<?php 
  namespace Service;

  use Controllers\Controller;

  class AuthService extends Controller
  {
    public function sign(array $datas = [], array $fields = ['nom', 'prenom', 'email', 'password', 'confirm-password'])
    {
      // Normaliser les noms de champs (fullname -> nom)
      if (isset($datas['fullname']) && !isset($datas['nom'])) {
          $datas['nom'] = $datas['fullname'];
      }
      if (isset($datas['confirmPassword']) && !isset($datas['confirm-password'])) {
          $datas['confirm-password'] = $datas['confirmPassword'];
      }
      
      // Connexion à la base de données
      $db = $this->db();
      $userModel = new \Models\UserModel($db);
      
      if (!$this->isNotEmpty($datas) || !$this->verifyFields($datas, $fields)) {
           $this->jsonResponse([
              'status' => 400,
              'message' => 'Tous les champs sont requis'
          ]);
           return;
      }

     // Validation des longueurs
      if (
          !isset($datas['nom']) || !$this->valideLength($datas['nom'], 2, 64) ||
          !isset($datas['prenom']) || !$this->valideLength($datas['prenom'], 2, 64) ||
          !isset($datas['email']) || !$this->valideLength($datas['email'], 5, 64) ||
          !isset($datas['password']) || !$this->valideLength($datas['password'], 8, 64) ||
          !isset($datas['confirm-password']) || !$this->valideLength($datas['confirm-password'], 8, 64)
      ) {
          $this->jsonResponse([
              'status' => 400,
              'message' => 'Longueur incorrecte (nom et prénom min 2, email min 5, mot de passe min 8)'
          ]);
          return;
      }

      // Validation de l'email
      if (!$this->isEmailValid($datas['email'])) {
          $this->jsonResponse([
              'status' => 400,
              'message' => 'Email invalide'
          ]);
          return;
      }

     if(!$this->isEqual($datas['password'], $datas['confirm-password']))
     {
        $this->jsonResponse([
            'status' => 409,
            'message' => 'Les 2 mots de passe sont différents'
        ]);
        return;
     }

     // Vérifier si l'utilisateur existe déjà
     if($userModel->getUserByEmail($datas['email']))
     {
        $this->jsonResponse([
            'status' => 409,
            'message' => 'Un utilisateur avec cet email existe déjà'
        ]);
        return;
     }

     // Créer l'utilisateur avec un tableau associatif
     $userData = [
         'nom' => $datas['nom'],
         'prenom' => $datas['prenom'],
         'email' => $datas['email'],
         'password' => password_hash($datas['password'], PASSWORD_DEFAULT),
         'statut' => 'actif'
     ];
     
     $userModel->createUser($userData);

     $this->jsonResponse([
         'status' => 200,
         'message' => 'Inscription réussie',
         'redirect' => \Router\Router::route('login')
     ]);
    }
    
    // fonction pour la connexion 

    public function login($datas = [], $fields = ['email', 'password'])
    {
        // Connexion à la base de données
        $db = $this->db();
        $userModel = new \Models\UserModel($db);
        
        if (!$this->isNotEmpty($datas) || !$this->verifyFields($datas, $fields)) {
             $this->jsonResponse([
                'status' => 400,
                'message' => 'Tous les champs sont requis'
            ]);
             return;
        }

       if(
          !isset($datas['email']) || !$this->valideLength($datas['email'], 5, 64) ||
          !isset($datas['password']) || !$this->valideLength($datas['password'], 8, 64)
        ) {
          $this->jsonResponse([
              'status' => 400,
              'message' => 'Longueur incorrecte (email min 5, mot de passe min 8)'
          ]);
          return;
        }

        // Validation de l'email
        if (!$this->isEmailValid($datas['email'])) {
            $this->jsonResponse([
                'status' => 400,
                'message' => 'Email invalide'
            ]);
            return;
        }

        // Récupérer l'utilisateur par email
        $user = $userModel->getUserByEmail($datas['email']);
        
        if ($user && isset($user['password'])) {
            // Vérifier le mot de passe
            if (password_verify($datas['password'], $user['password'])) {
                // Démarrer la session si elle n'est pas déjà démarrée
                if (session_status() === PHP_SESSION_NONE) {
                    // Configurer les paramètres de session pour une durée de vie plus longue
                    ini_set('session.gc_maxlifetime', 28800); // 8 heures
                    session_set_cookie_params([
                        'lifetime' => 28800, // 8 heures
                        'path' => '/',
                        'domain' => '',
                        'secure' => false, // Mettre à true en production avec HTTPS
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]);
                    session_start();
                }

                // Utiliser directement le champ role de la table users (source de vérité)
                // Les valeurs possibles sont : 'admin', 'user', 'auteur', 'redacteur', 'redacteur en chef'
                $role = $user['role'] ?? 'user';
                
                // Si l'utilisateur a le rôle 'user', vérifier s'il a un abonnement actif
                // Si oui, mettre à jour automatiquement son rôle en 'auteur'
                if ($role === 'user') {
                    $userModel = new \Models\UserModel($this->db());
                    $hasActiveSubscription = $userModel->isSubscribedAndActive($user['id']);
                    
                    if ($hasActiveSubscription) {
                        // Mettre à jour le rôle dans la base de données
                        $userModel->updateUser($user['id'], ['role' => 'auteur']);
                        $role = 'auteur';
                        // Rafraîchir les données utilisateur
                        $user = $userModel->getUserById($user['id']);
                    }
                }
                
                // Régénérer l'ID de session pour éviter le fixation de session
                session_regenerate_id(true);

                // Stocker les informations de l'utilisateur en session
                $_SESSION['user'] = $user;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'] ?? null;
                $_SESSION['user_nom'] = $user['nom'] ?? null;
                $_SESSION['user_prenom'] = $user['prenom'] ?? null;
                $_SESSION['user_role'] = $role;
                // rôle actif = rôle principal par défaut
                $_SESSION['active_role'] = $role;
                $_SESSION['panier'] = [];
                $_SESSION['last_activity'] = time(); // Timestamp de la dernière activité
                
                // Rafraîchir le cookie de session
                setcookie(session_name(), session_id(), time() + 28800, '/', '', false, true);

                // Déterminer la redirection selon le rôle
                // Les rôles possibles : 'admin', 'user', 'auteur', 'redacteur', 'redacteur en chef'
                $redirectUrl = \Router\Router::route('');
                if ($role === 'admin') {
                    $redirectUrl = \Router\Router::route('admin');
                } elseif ($role === 'redacteur') {
                    // Les évaluateurs (rédacteurs) vont vers le dashboard reviewer
                    $redirectUrl = \Router\Router::route('reviewer');
                } elseif ($role === 'auteur') {
                    $redirectUrl = \Router\Router::route(''); // Page d'accueil
                } elseif ($role === 'redacteur en chef') {
                    // Les rédacteurs en chef peuvent accéder au dashboard admin ou reviewer selon les besoins
                    // Pour l'instant, on les redirige vers reviewer
                    $redirectUrl = \Router\Router::route('reviewer');
                } elseif ($role === 'user') {
                    // Les utilisateurs simples restent sur la page d'accueil
                    $redirectUrl = \Router\Router::route('');
                }

                $this->jsonResponse([
                    'status' => 200,
                    'message' => 'Connexion réussie',
                    'redirect' => $redirectUrl
                ]);
                return;
            }
        }

        // Si on arrive ici, les identifiants sont incorrects
        $this->jsonResponse([
            'status' => 401,
            'message' => 'Email ou mot de passe incorrect'
        ]);
    }
    

       public function logout()
      {
          // Démarrer la session si elle n'est pas déjà démarrée
          if (session_status() === PHP_SESSION_NONE) {
              session_start();
          }
          
          // Détruire toutes les variables de session
          $_SESSION = array();
          
          // Si vous voulez détruire complètement la session, supprimez aussi le cookie de session
          if (ini_get("session.use_cookies")) {
              $params = session_get_cookie_params();
              setcookie(session_name(), '', time() - 42000,
                  $params["path"], $params["domain"],
                  $params["secure"], $params["httponly"]
              );
          }
          
          // Détruire la session
          session_destroy();
          
          // Rediriger vers la page d'accueil
          header('Location: ' . \Router\Router::route(''));
          exit;
       }

       public static function requireLogin() 
       {
            if (!self::isLoggedIn()) {
                header('Location: ' .\Router\Router::route('login'));
                exit;
            }
        }
        
        public static function isLoggedIn() {
            if (session_status() === PHP_SESSION_NONE) {
                // Configurer les paramètres de session
                ini_set('session.gc_maxlifetime', 28800); // 8 heures
                session_set_cookie_params([
                    'lifetime' => 28800,
                    'path' => '/',
                    'domain' => '',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
                session_start();
            }
            
            // Vérifier si l'utilisateur est connecté
            if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
                return false;
            }
            
            // Vérifier si la session n'a pas expiré (8 heures d'inactivité)
            $lastActivity = $_SESSION['last_activity'] ?? time();
            $maxInactivity = 28800; // 8 heures en secondes
            
            // Si last_activity n'existe pas, le créer maintenant
            if (!isset($_SESSION['last_activity'])) {
                $_SESSION['last_activity'] = time();
            }
            
            if (time() - $lastActivity > $maxInactivity) {
                // Session expirée, détruire la session et le cookie
                $_SESSION = [];
                if (ini_get("session.use_cookies")) {
                    $params = session_get_cookie_params();
                    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
                }
                session_destroy();
                return false;
            }
            
            // Rafraîchir le timestamp de dernière activité et le cookie
            $_SESSION['last_activity'] = time();
            if (!headers_sent()) {
                setcookie(session_name(), session_id(), time() + 28800, '/', '', false, true);
            }
            
            return true;
        }

        /**
         * Récupère le rôle actif (bascule possible admin -> reviewer/auteur)
         */
        public static function getActiveRole(): ?string
        {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            return $_SESSION['active_role'] ?? $_SESSION['user_role'] ?? null;
        }

        /**
         * Indique si l'utilisateur connecté a le droit de télécharger les PDF (articles, numéros).
         * Autorisé : admin, rédacteur, auteur, ou utilisateur avec abonnement actif.
         */
        public static function canDownloadArticle(): bool
        {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (empty($_SESSION['user_id'])) {
                return false;
            }
            $role = strtolower($_SESSION['active_role'] ?? $_SESSION['user_role'] ?? '');
            if (in_array($role, ['admin', 'redacteur', 'redacteur en chef'], true)) {
                return true;
            }
            if ($role === 'auteur') {
                return true;
            }
            if ($role === 'user') {
                $db = new \Models\Database();
                $db->connect();
                $userModel = new \Models\UserModel($db);
                return $userModel->isSubscribedAndActive((int) $_SESSION['user_id']);
            }
            return false;
        }

        /**
         * Permet à un admin de basculer son rôle actif (ex: admin -> reviewer)
         * Route dédiée: POST /switch-role avec { role: 'reviewer' | 'admin' | ... }
         */
        public function switchRole()
        {
            self::requireLogin();

            $principalRole = $_SESSION['user_role'] ?? null;
            $target = $this->input()['role'] ?? null;

            if (!$principalRole || !$target) {
                $this->respond(['error' => 'Rôle cible manquant'], 400);
                return;
            }

            $principalRole = strtolower($principalRole);
            $target = strtolower($target);

            // Seuls les admins peuvent basculer vers un autre rôle
            if ($principalRole !== 'admin') {
                $this->respond(['error' => 'Bascule non autorisée'], 403);
                return;
            }

            // Rôles autorisés pour l'admin
            $allowedTargets = ['admin', 'reviewer', 'redacteur', 'redacteur en chef', 'auteur', 'user'];
            if (!in_array($target, $allowedTargets, true)) {
                $this->respond(['error' => 'Rôle cible invalide'], 400);
                return;
            }

            // Mise à jour du rôle actif en session
            $_SESSION['active_role'] = $target;

            // Redirection suggérée selon le rôle actif
            $redirect = \Router\Router::route('');
            if (in_array($target, ['reviewer', 'redacteur', 'redacteur en chef'])) {
                $redirect = \Router\Router::route('reviewer');
            } elseif ($target === 'auteur' || $target === 'user') {
                $redirect = \Router\Router::route('author');
            } elseif ($target === 'admin') {
                $redirect = \Router\Router::route('admin');
            }

            $this->respond(['success' => true, 'active_role' => $target, 'redirect' => $redirect], 200);
        }

  }
?>