<?php
namespace Controllers;

use Models\Database;
use Models\UserModel;
use Models\ArticleModel;
use Models\ReviewModel;
use Models\RevueInfoModel;
use Models\VolumeModel;
use Models\IssueModel;
use Service\AuthService;

class AdminController extends Controller
{
    private function requireAdmin()
    {
        AuthService::requireLogin();
        $role = $_SESSION['active_role'] ?? $_SESSION['user_role'] ?? null;
        // On ne bloque pas si le rôle n'est pas renseigné, mais on peut restreindre si disponible
        if ($role && strtolower($role) !== 'admin' && strtolower($role) !== 'rédacteur en chef' && strtolower($role) !== 'redacteur en chef') {
            header('Location: ' . \Router\Router::route(''));
            exit;
        }
        return $_SESSION['user_id'] ?? null;
    }

    public function index()
    {
        $userId = $this->requireAdmin();
        $db = $this->db();
        $userModel = new UserModel($db);
        $articleModel = new ArticleModel($db);

        $user = $userModel->getUserById($userId);

        // Stats
        $articlesTotal = $db->fetchOne("SELECT COUNT(*) as c FROM articles")['c'] ?? 0;
        $articlesPublies = $db->fetchOne("SELECT COUNT(*) as c FROM articles WHERE statut IN ('valide','publie','publié')")['c'] ?? 0;
        $evaluateursActifs = $db->fetchOne("SELECT COUNT(*) as c FROM users")['c'] ?? 0;
        $revenusMois = $db->fetchOne("SELECT COALESCE(SUM(montant),0) as total FROM paiements WHERE statut='valide' AND date_paiement IS NOT NULL AND MONTH(date_paiement)=MONTH(CURDATE()) AND YEAR(date_paiement)=YEAR(CURDATE())")['total'] ?? 0;

        $recentSubmissions = $db->fetchAll("
            SELECT a.id, a.titre, a.date_soumission, a.statut, u.prenom, u.nom
            FROM articles a
            JOIN users u ON u.id = a.auteur_id
            ORDER BY a.date_soumission DESC
            LIMIT 5
        ");

        $data = [
            'user' => $user,
            'stats' => [
                'articles_total' => $articlesTotal,
                'articles_publies' => $articlesPublies,
                'evaluateurs_actifs' => $evaluateursActifs,
                'revenus_mois' => $revenusMois,
            ],
            'recentSubmissions' => $recentSubmissions,
            'current_page' => 'dashboard',
        ];

        \App\App::view('admin' . DIRECTORY_SEPARATOR . 'index', $data);
    }

    public function users()
    {
        $userId = $this->requireAdmin();
        $db = $this->db();
        $userModel = new UserModel($db);
        $user = $userModel->getUserById($userId);
        $users = $db->fetchAll("SELECT id, nom, prenom, email, role, statut, created_at FROM users ORDER BY created_at DESC LIMIT 200");

        \App\App::view('admin' . DIRECTORY_SEPARATOR . 'users', [
            'user' => $user,
            'users' => $users,
            'current_page' => 'users'
        ]);
    }


    public function paiements()
    {
        $userId = $this->requireAdmin();
        $db = $this->db();
        $userModel = new UserModel($db);
        $user = $userModel->getUserById($userId);
        $paiements = $db->fetchAll("
            SELECT p.id, p.utilisateur_id, p.montant, p.moyen, p.statut, p.date_paiement, u.prenom, u.nom, u.email
            FROM paiements p
            JOIN users u ON u.id = p.utilisateur_id
            ORDER BY p.created_at DESC
            LIMIT 200
        ");

        \App\App::view('admin' . DIRECTORY_SEPARATOR . 'paiements', [
            'user' => $user,
            'paiements' => $paiements,
            'current_page' => 'paiements'
        ]);
    }

    public function settings()
    {
        $userId = $this->requireAdmin();
        $db = $this->db();
        $userModel = new UserModel($db);
        $user = $userModel->getUserById($userId);
        \App\App::view('admin' . DIRECTORY_SEPARATOR . 'settings', [
            'user' => $user,
            'current_page' => 'settings'
        ]);
    }

    /**
     * Page de gestion des évaluations
     */
    public function evaluations()
    {
        $userId = $this->requireAdmin();
        $db = $this->db();
        $userModel = new UserModel($db);
        $reviewModel = new ReviewModel($db);
        
        $user = $userModel->getUserById($userId);
        
        // Récupérer toutes les évaluations avec les informations de l'article et de l'évaluateur
        $evaluations = $db->fetchAll("
            SELECT e.*,
                   a.id as article_id,
                   a.titre as article_titre,
                   a.statut as article_statut,
                   u_eval.nom as evaluateur_nom,
                   u_eval.prenom as evaluateur_prenom,
                   u_eval.email as evaluateur_email,
                   DATEDIFF(e.date_echeance, CURDATE()) as jours_restants
            FROM evaluations e
            JOIN articles a ON e.article_id = a.id
            JOIN users u_eval ON e.evaluateur_id = u_eval.id
            ORDER BY e.created_at DESC
        ");
        
        // Calculer les statistiques
        $stats = [
            'total' => count($evaluations),
            'en_attente' => 0,
            'en_cours' => 0,
            'terminees' => 0,
            'annulees' => 0
        ];
        
        foreach ($evaluations as $eval) {
            $statut = strtolower($eval['statut'] ?? '');
            switch ($statut) {
                case 'en_attente':
                    $stats['en_attente']++;
                    break;
                case 'en_cours':
                    $stats['en_cours']++;
                    break;
                case 'termine':
                    $stats['terminees']++;
                    break;
                case 'annule':
                    $stats['annulees']++;
                    break;
            }
        }
        
        // Calculer les stats pour la sidebar
        $articlesTotal = $db->fetchOne("SELECT COUNT(*) as c FROM articles")['c'] ?? 0;
        
        \App\App::view('admin' . DIRECTORY_SEPARATOR . 'evaluations', [
            'user' => $user,
            'evaluations' => $evaluations,
            'stats' => array_merge($stats, [
                'articles_total' => $articlesTotal
            ]),
            'current_page' => 'evaluations'
        ]);
    }

    /**
     * Page de gestion des publications
     */
    public function publications()
    {
        $userId = $this->requireAdmin();
        $db = $this->db();
        $userModel = new UserModel($db);
        
        $user = $userModel->getUserById($userId);
        
        // Récupérer les articles publiés et acceptés/validés (non encore publiés)
        $publications = $db->fetchAll("
            SELECT a.*,
                   u.nom as auteur_nom,
                   u.prenom as auteur_prenom,
                   u.email as auteur_email
            FROM articles a
            JOIN users u ON a.auteur_id = u.id
            WHERE a.statut IN ('publie', 'publié', 'accepte', 'accepted', 'valide', 'validé')
            ORDER BY 
                CASE 
                    WHEN a.statut IN ('publie', 'publié') THEN 1
                    ELSE 2
                END,
                a.updated_at DESC
        ");
        
        // Calculer les statistiques
        $totalPublies = 0;
        $ceMois = 0;
        $enAttente = 0;
        $moisActuel = date('Y-m');
        
        foreach ($publications as $pub) {
            $statut = strtolower($pub['statut'] ?? '');
            if (strpos($statut, 'publ') !== false) {
                $totalPublies++;
                $datePub = $pub['date_publication'] ?? $pub['updated_at'] ?? $pub['created_at'];
                if (strpos($datePub, $moisActuel) === 0) {
                    $ceMois++;
                }
            } else {
                $enAttente++;
            }
        }
        
        $stats = [
            'total' => $totalPublies,
            'ce_mois' => $ceMois,
            'en_attente' => $enAttente
        ];
        
        // Calculer les stats pour la sidebar
        $articlesTotal = $db->fetchOne("SELECT COUNT(*) as c FROM articles")['c'] ?? 0;
        
        \App\App::view('admin' . DIRECTORY_SEPARATOR . 'publications', [
            'user' => $user,
            'publications' => $publications,
            'stats' => array_merge($stats, [
                'articles_total' => $articlesTotal
            ]),
            'current_page' => 'publications'
        ]);
    }

    /**
     * Détails d'une évaluation
     */
    public function evaluationDetails($params = [])
    {
        $this->requireAdmin();
        
        $evaluationId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$evaluationId) {
            header('Location: ' . \Router\Router::route('admin') . '/evaluations');
            exit;
        }
        
        $db = $this->db();
        $reviewModel = new ReviewModel($db);
        $userModel = new UserModel($db);
        
        $evaluation = $reviewModel->getReviewById($evaluationId);
        
        if (!$evaluation) {
            header('Location: ' . \Router\Router::route('admin') . '/evaluations');
            exit;
        }
        
        $user = $userModel->getUserById($_SESSION['user_id']);
        
        \App\App::view('admin' . DIRECTORY_SEPARATOR . 'evaluation-details', [
            'user' => $user,
            'evaluation' => $evaluation,
            'current_page' => 'evaluations'
        ]);
    }

    public function articles()
    {
        $userId = $this->requireAdmin();
        $db = $this->db();
        $userModel = new UserModel($db);
        $user = $userModel->getUserById($userId);
        
        $articles = $db->fetchAll("
            SELECT a.id, a.titre, a.date_soumission, a.statut, a.contenu, a.fichier_path,
                   u.prenom, u.nom, u.email
            FROM articles a
            JOIN users u ON u.id = a.auteur_id
            ORDER BY a.date_soumission DESC
        ");

        // Récupérer tous les numéros pour l'assignation d'articles
        $issues = $db->fetchAll("
            SELECT r.id, r.numero, r.titre, r.date_publication,
                   v.annee, v.numero_volume
            FROM revues r
            LEFT JOIN volumes v ON r.volume_id = v.id
            WHERE r.type = 'issue' OR r.type IS NULL
            ORDER BY v.annee DESC, r.numero ASC
        ");

        // Calculer les stats pour la sidebar
        $articlesTotal = $db->fetchOne("SELECT COUNT(*) as c FROM articles")['c'] ?? 0;

        \App\App::view('admin' . DIRECTORY_SEPARATOR . 'articles', [
            'user' => $user,
            'articles' => $articles,
            'issues' => $issues,
            'stats' => ['articles_total' => $articlesTotal],
            'current_page' => 'articles'
        ]);
    }

    public function articleDetails($params = [])
    {
        $userId = $this->requireAdmin();
        $db = $this->db();
        $userModel = new UserModel($db);
        $user = $userModel->getUserById($userId);
        
        $articleId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$articleId) {
            header('Location: ' . \Router\Router::route('admin') . '/articles');
            exit;
        }

        $articleModel = new ArticleModel($db);
        $article = $articleModel->getArticleById($articleId);
        
        if (!$article) {
            header('Location: ' . \Router\Router::route('admin') . '/articles');
            exit;
        }

        // Récupérer les informations de l'auteur
        $auteur = $userModel->getUserById($article['auteur_id']);
        
        // Récupérer l'historique des révisions
        $revisions = [];
        $revisionCount = 0;
        try {
            $revisionModel = new \Models\RevisionModel($db);
            $revisions = $revisionModel->getArticleRevisions($articleId);
            $revisionCount = $revisionModel->getRevisionCount($articleId);
        } catch (\Exception $e) {
            error_log('Erreur lors de la récupération des révisions: ' . $e->getMessage());
        }

        \App\App::view('admin' . DIRECTORY_SEPARATOR . 'article-details', [
            'user' => $user,
            'article' => $article,
            'auteur' => $auteur,
            'revisions' => $revisions,
            'revisionCount' => $revisionCount,
            'current_page' => 'articles'
        ]);
    }

    public function updateArticleStatus($params = [])
    {
        $this->requireAdmin();
        
        $articleId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$articleId) {
            $this->respond(['error' => 'ID d\'article manquant'], 400);
            return;
        }

        $data = $this->input();
        $statut = $data['statut'] ?? null;
        
        if (!in_array($statut, ['soumis', 'valide', 'rejete'])) {
            $this->respond(['error' => 'Statut invalide'], 400);
            return;
        }

        $db = $this->db();
        $articleModel = new ArticleModel($db);
        
        $success = $db->execute(
            "UPDATE articles SET statut = :statut, updated_at = NOW() WHERE id = :id",
            [':statut' => $statut, ':id' => $articleId]
        );

        if ($success) {
            // Notifier l'auteur si le statut change
            try {
                $article = $articleModel->getArticleById($articleId);
                if ($article) {
                    $notificationModel = new \Models\NotificationModel($db);
                    $notificationModel->notifyArticleStatusChange($articleId, $statut, "Statut modifié par l'administrateur");
                }
            } catch (\Exception $e) {
                error_log('Erreur notification: ' . $e->getMessage());
            }
            
            $this->respond(['success' => true, 'message' => 'Statut mis à jour avec succès'], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }
    
    /**
     * Publier un article
     */
    public function publishArticle($params = [])
    {
        $this->requireAdmin();
        
        $articleId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$articleId) {
            $this->respond(['error' => 'ID d\'article manquant'], 400);
            return;
        }
        
        $db = $this->db();
        $articleModel = new ArticleModel($db);
        
        // Récupérer l'article
        $article = $articleModel->getArticleById($articleId);
        if (!$article) {
            $this->respond(['error' => 'Article introuvable'], 404);
            return;
        }
        
        // Vérifier que l'article est accepté
        $statut = strtolower($article['statut'] ?? '');
        if (strpos($statut, 'accept') === false) {
            $this->respond(['error' => 'Seuls les articles acceptés peuvent être publiés'], 400);
            return;
        }
        
        // Publier l'article
        $success = $articleModel->changeArticleStatus($articleId, 'publie');
        
        if ($success) {
            // Notifier l'auteur
            try {
                $notificationModel = new \Models\NotificationModel($db);
                $notificationModel->notifyArticleStatusChange($articleId, 'publie', "Votre article a été publié avec succès !");
            } catch (\Exception $e) {
                error_log('Erreur notification: ' . $e->getMessage());
            }
            
            $this->respond(['success' => true, 'message' => 'Article publié avec succès'], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la publication'], 500);
        }
    }

    public function deleteArticle($params = [])
    {
        $this->requireAdmin();
        
        $articleId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$articleId) {
            $this->respond(['error' => 'ID d\'article manquant'], 400);
            return;
        }

        $db = $this->db();
        $articleModel = new ArticleModel($db);
        
        // Récupérer l'article pour supprimer le fichier
        $article = $articleModel->getArticleById($articleId);
        if ($article && !empty($article['fichier_path'])) {
            $filePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $article['fichier_path'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $success = $articleModel->deleteArticle($articleId);

        if ($success) {
            $this->respond(['success' => true, 'message' => 'Article supprimé avec succès'], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la suppression'], 500);
        }
    }

    public function userDetails($params = [])
    {
        $userId = $this->requireAdmin();
        $db = $this->db();
        $userModel = new UserModel($db);
        $adminUser = $userModel->getUserById($userId);
        
        $targetUserId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$targetUserId) {
            header('Location: ' . \Router\Router::route('admin') . '/users');
            exit;
        }

        $userDetail = $userModel->getUserById($targetUserId);
        if (!$userDetail) {
            header('Location: ' . \Router\Router::route('admin') . '/users');
            exit;
        }

        // Récupérer les statistiques
        $stats = $userModel->getUserStatistics($targetUserId);

        \App\App::view('admin' . DIRECTORY_SEPARATOR . 'user-details', [
            'user' => $adminUser,
            'userDetail' => $userDetail,
            'stats' => $stats,
            'current_page' => 'users'
        ]);
    }

    public function getUser($params = [])
    {
        $this->requireAdmin();
        
        $userId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$userId) {
            $this->respond(['error' => 'ID utilisateur manquant'], 400);
            return;
        }

        $db = $this->db();
        $userModel = new UserModel($db);
        $user = $userModel->getUserById($userId);
        
        if (!$user) {
            $this->respond(['error' => 'Utilisateur introuvable'], 404);
            return;
        }

        // Ne pas renvoyer le mot de passe
        unset($user['password']);
        
        $this->respond(['user' => $user], 200);
    }

    public function createUser()
    {
        $this->requireAdmin();
        
        $data = $this->input();
        
        // Validation
        if (empty($data['nom']) || empty($data['prenom']) || empty($data['email']) || empty($data['password'])) {
            $this->respond(['error' => 'Tous les champs sont requis'], 400);
            return;
        }

        if (strlen($data['password']) < 8) {
            $this->respond(['error' => 'Le mot de passe doit contenir au moins 8 caractères'], 400);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->respond(['error' => 'Email invalide'], 400);
            return;
        }

        $db = $this->db();
        $userModel = new UserModel($db);
        
        // Vérifier si l'email existe déjà
        if ($userModel->emailExists($data['email'])) {
            $this->respond(['error' => 'Un utilisateur avec cet email existe déjà'], 409);
            return;
        }

        // Créer l'utilisateur
        $userData = [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'statut' => 'actif',
            'role' => $data['role'] ?? 'user'
        ];

        // Insérer avec le rôle
        $sql = "INSERT INTO users (nom, prenom, email, password, statut, role, created_at, updated_at) 
                VALUES (:nom, :prenom, :email, :password, :statut, :role, NOW(), NOW())";
        
        $success = $db->execute($sql, [
            ':nom' => $userData['nom'],
            ':prenom' => $userData['prenom'],
            ':email' => $userData['email'],
            ':password' => $userData['password'],
            ':statut' => $userData['statut'],
            ':role' => $userData['role']
        ]);

        if ($success) {
            $this->respond(['success' => true, 'message' => 'Utilisateur créé avec succès'], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la création'], 500);
        }
    }

    public function createEvaluator()
    {
        $this->requireAdmin();
        
        $data = $this->input();
        
        // Validation
        if (empty($data['nom']) || empty($data['prenom']) || empty($data['email']) || empty($data['password'])) {
            $this->respond(['error' => 'Tous les champs sont requis'], 400);
            return;
        }

        if (strlen($data['password']) < 8) {
            $this->respond(['error' => 'Le mot de passe doit contenir au moins 8 caractères'], 400);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->respond(['error' => 'Email invalide'], 400);
            return;
        }

        $db = $this->db();
        $userModel = new UserModel($db);
        
        // Vérifier si l'email existe déjà
        if ($userModel->emailExists($data['email'])) {
            $this->respond(['error' => 'Un utilisateur avec cet email existe déjà'], 409);
            return;
        }

        // Créer l'évaluateur (rôle rédacteur)
        $sql = "INSERT INTO users (nom, prenom, email, password, statut, role, created_at, updated_at) 
                VALUES (:nom, :prenom, :email, :password, :statut, :role, NOW(), NOW())";
        
        $success = $db->execute($sql, [
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':statut' => 'actif',
            ':role' => 'redacteur' // Les évaluateurs sont des rédacteurs
        ]);

        if ($success) {
            $this->respond(['success' => true, 'message' => 'Évaluateur créé avec succès'], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la création'], 500);
        }
    }

    public function updateUser($params = [])
    {
        $this->requireAdmin();
        
        $userId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$userId) {
            $this->respond(['error' => 'ID utilisateur manquant'], 400);
            return;
        }

        $data = $this->input();
        
        // Validation
        if (empty($data['nom']) || empty($data['prenom']) || empty($data['email'])) {
            $this->respond(['error' => 'Tous les champs sont requis'], 400);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->respond(['error' => 'Email invalide'], 400);
            return;
        }

        $db = $this->db();
        $userModel = new UserModel($db);
        
        // Vérifier si l'email existe déjà pour un autre utilisateur
        if ($userModel->emailExists($data['email'], $userId)) {
            $this->respond(['error' => 'Un utilisateur avec cet email existe déjà'], 409);
            return;
        }

        // Construire la requête de mise à jour
        $updateFields = [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'role' => $data['role'] ?? 'user',
            'statut' => $data['statut'] ?? 'actif'
        ];

        $sql = "UPDATE users SET nom = :nom, prenom = :prenom, email = :email, role = :role, statut = :statut";
        $params = [
            ':id' => $userId,
            ':nom' => $updateFields['nom'],
            ':prenom' => $updateFields['prenom'],
            ':email' => $updateFields['email'],
            ':role' => $updateFields['role'],
            ':statut' => $updateFields['statut']
        ];

        // Ajouter le mot de passe si fourni
        if (!empty($data['password'])) {
            if (strlen($data['password']) < 8) {
                $this->respond(['error' => 'Le mot de passe doit contenir au moins 8 caractères'], 400);
                return;
            }
            $sql .= ", password = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $sql .= ", updated_at = NOW() WHERE id = :id";

        $success = $db->execute($sql, $params);

        if ($success) {
            $this->respond(['success' => true, 'message' => 'Utilisateur mis à jour avec succès'], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    public function deleteUser($params = [])
    {
        $this->requireAdmin();
        
        $userId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$userId) {
            $this->respond(['error' => 'ID utilisateur manquant'], 400);
            return;
        }

        // Ne pas permettre la suppression de l'admin connecté
        if ($userId == $_SESSION['user_id']) {
            $this->respond(['error' => 'Vous ne pouvez pas supprimer votre propre compte'], 400);
            return;
        }

        $db = $this->db();
        $success = $db->execute("DELETE FROM users WHERE id = :id", [':id' => $userId]);

        if ($success) {
            $this->respond(['success' => true, 'message' => 'Utilisateur supprimé avec succès'], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la suppression'], 500);
        }
    }

    public function updateUserStatus($params = [])
    {
        $this->requireAdmin();
        
        $userId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$userId) {
            $this->respond(['error' => 'ID utilisateur manquant'], 400);
            return;
        }

        $data = $this->input();
        $statut = $data['statut'] ?? null;
        
        if (!in_array($statut, ['actif', 'suspendu'])) {
            $this->respond(['error' => 'Statut invalide'], 400);
            return;
        }

        $db = $this->db();
        $success = $db->execute(
            "UPDATE users SET statut = :statut, updated_at = NOW() WHERE id = :id",
            [':statut' => $statut, ':id' => $userId]
        );

        if ($success) {
            $this->respond(['success' => true, 'message' => 'Statut utilisateur mis à jour avec succès'], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    public function deleteVolume($params = [])
    {
        $this->requireAdmin();
        
        $volumeId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$volumeId) {
            $this->respond(['error' => 'ID volume manquant'], 400);
            return;
        }

        $db = $this->db();
        $success = $db->execute("DELETE FROM revues WHERE id = :id", [':id' => $volumeId]);

        if ($success) {
            $this->respond(['success' => true, 'message' => 'Volume supprimé avec succès'], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la suppression'], 500);
        }
    }

    public function updatePaymentStatus($params = [])
    {
        $this->requireAdmin();
        
        $paymentId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$paymentId) {
            $this->respond(['error' => 'ID paiement manquant'], 400);
            return;
        }

        $data = $this->input();
        $statut = $data['statut'] ?? null;
        
        if (!in_array($statut, ['valide', 'refuse', 'en_attente'])) {
            $this->respond(['error' => 'Statut invalide'], 400);
            return;
        }

        $db = $this->db();
        $datePaiement = ($statut === 'valide') ? 'NOW()' : 'NULL';
        
        $success = $db->execute(
            "UPDATE paiements SET statut = :statut, date_paiement = " . $datePaiement . ", updated_at = NOW() WHERE id = :id",
            [':statut' => $statut, ':id' => $paymentId]
        );

        if ($success) {
            $this->respond(['success' => true, 'message' => 'Statut paiement mis à jour avec succès'], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    /**
     * Récupérer les évaluateurs disponibles pour un article
     */
    public function getAvailableReviewers($params = [])
    {
        $this->requireAdmin();
        
        $articleId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$articleId) {
            $this->respond(['error' => 'ID d\'article manquant'], 400);
            return;
        }

        $db = $this->db();
        
        // Récupérer tous les évaluateurs (rôles: redacteur, redacteur en chef, reviewer, admin)
        // Les admins peuvent aussi être évaluateurs (même s'ils sont auteurs de l'article)
        $evaluateurs = $db->fetchAll("
            SELECT u.id, u.nom, u.prenom, u.email, u.role,
                   COUNT(DISTINCT e.id) as review_count,
                   AVG(e.note_finale) as avg_score
            FROM users u 
            LEFT JOIN evaluations e ON u.id = e.evaluateur_id 
            WHERE u.role IN ('redacteur', 'redacteur en chef', 'reviewer', 'rédacteur', 'rédacteur en chef', 'admin')
            AND u.statut = 'actif'
            AND u.id NOT IN (
                SELECT evaluateur_id 
                FROM evaluations 
                WHERE article_id = :articleId
            )
            AND (
                u.role = 'admin' 
                OR u.id NOT IN (
                    SELECT auteur_id 
                    FROM articles 
                    WHERE id = :articleId
                )
            )
            GROUP BY u.id 
            ORDER BY 
                CASE WHEN u.role = 'admin' THEN 0 ELSE 1 END,
                review_count DESC, 
                u.nom ASC
        ", [':articleId' => $articleId]);

        $this->respond(['evaluateurs' => $evaluateurs], 200);
    }

    /**
     * Assigner un article à un évaluateur
     */
    public function assignArticleToReviewer($params = [])
    {
        try {
            $this->requireAdmin();
            
            $articleId = is_array($params) ? ($params['id'] ?? null) : $params;
            if (!$articleId) {
                $this->respond(['error' => 'ID d\'article manquant'], 400);
                return;
            }

            // Lire les données (JSON ou POST)
            $data = $this->input();
            
            // Debug: vérifier les données reçues
            if (empty($data)) {
                error_log('assignArticleToReviewer: Aucune donnée reçue');
                $this->respond(['error' => 'Aucune donnée reçue'], 400);
                return;
            }

            $reviewerId = isset($data['reviewer_id']) ? (int)$data['reviewer_id'] : null;
            $deadlineDays = isset($data['deadline_days']) ? (int)$data['deadline_days'] : 14;

            if (!$reviewerId || $reviewerId <= 0) {
                $this->respond(['error' => 'ID d\'évaluateur manquant ou invalide', 'debug' => $data], 400);
                return;
            }

            // Valider le délai
            if ($deadlineDays < 1 || $deadlineDays > 90) {
                $this->respond(['error' => 'Le délai doit être entre 1 et 90 jours'], 400);
                return;
            }

            // Valider l'ID d'article
            $articleId = (int)$articleId;
            if ($articleId <= 0) {
                $this->respond(['error' => 'ID d\'article invalide'], 400);
                return;
            }

            $db = $this->db();
            $db->connect(); // S'assurer que la connexion est établie
            $reviewModel = new ReviewModel($db);
            
            // Vérifier si l'article existe
            $article = $db->fetchOne("SELECT id, statut FROM articles WHERE id = :id", [':id' => $articleId]);
            if (!$article) {
                $this->respond(['error' => 'Article introuvable'], 404);
                return;
            }

            // Vérifier si l'évaluateur existe et a le bon rôle (inclure admin)
            $reviewer = $db->fetchOne("
                SELECT id FROM users 
                WHERE id = :id 
                AND role IN ('redacteur', 'redacteur en chef', 'reviewer', 'rédacteur', 'rédacteur en chef', 'admin')
                AND statut = 'actif'
            ", [':id' => $reviewerId]);
            
            if (!$reviewer) {
                $this->respond(['error' => 'Évaluateur introuvable ou non autorisé'], 404);
                return;
            }

            // Vérifier si l'évaluateur n'est pas l'auteur de l'article (sauf si c'est un admin)
            $articleAuthor = $db->fetchOne("SELECT auteur_id FROM articles WHERE id = :id", [':id' => $articleId]);
            $reviewerRole = $db->fetchOne("SELECT role FROM users WHERE id = :id", [':id' => $reviewerId]);
            $isAdmin = strtolower($reviewerRole['role'] ?? '') === 'admin';
            
            if ($articleAuthor && $articleAuthor['auteur_id'] == $reviewerId && !$isAdmin) {
                $this->respond(['error' => 'Un auteur ne peut pas évaluer son propre article'], 400);
                return;
            }

            // Assigner l'évaluateur
            try {
                $success = $reviewModel->assignReviewer($articleId, $reviewerId, $deadlineDays);
            } catch (\Exception $e) {
                error_log('Erreur assignReviewer: ' . $e->getMessage());
                error_log('Stack trace: ' . $e->getTraceAsString());
                $this->respond([
                    'error' => 'Erreur lors de l\'assignation de l\'évaluateur',
                    'message' => $e->getMessage()
                ], 500);
                return;
            }

            if ($success) {
                // Mettre à jour le statut de l'article si nécessaire
                try {
                    $currentStatus = $article['statut'] ?? '';
                    if ($currentStatus === 'soumis') {
                        $db->execute("UPDATE articles SET statut = 'en_evaluation', updated_at = NOW() WHERE id = :id", [':id' => $articleId]);
                    }
                } catch (\Exception $e) {
                    error_log('Erreur mise à jour statut article: ' . $e->getMessage());
                    // Ne pas bloquer si la mise à jour du statut échoue
                }
                
                // Envoyer une notification à l'évaluateur
                try {
                    $notificationModel = new \Models\NotificationModel($db);
                    $notificationModel->createNotification(
                        $reviewerId,
                        'article_assigned',
                        'Nouvel article assigné',
                        "Un nouvel article vous a été assigné pour évaluation. Délai: {$deadlineDays} jours.",
                        $articleId,
                        null
                    );
                } catch (\Exception $e) {
                    error_log('Erreur notification: ' . $e->getMessage());
                    // Ne pas bloquer l'assignation si la notification échoue
                }
                
                $this->respond([
                    'success' => true, 
                    'message' => 'Article assigné à l\'évaluateur avec succès'
                ], 200);
            } else {
                // Vérifier si c'est parce que l'assignation existe déjà
                try {
                    $existing = $db->fetchOne("
                        SELECT id FROM evaluations 
                        WHERE article_id = :articleId AND evaluateur_id = :reviewerId
                    ", [':articleId' => $articleId, ':reviewerId' => $reviewerId]);
                    
                    if ($existing) {
                        $this->respond(['error' => 'Cet évaluateur est déjà assigné à cet article'], 400);
                    } else {
                        $this->respond(['error' => 'Erreur lors de l\'assignation. Veuillez réessayer.'], 500);
                    }
                } catch (\Exception $e) {
                    error_log('Erreur vérification assignation existante: ' . $e->getMessage());
                    $this->respond(['error' => 'Erreur lors de la vérification de l\'assignation'], 500);
                }
            }
        } catch (\Exception $e) {
            error_log('Erreur assignArticleToReviewer: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            $this->respond([
                'error' => 'Une erreur est survenue lors de l\'attribution',
                'message' => $e->getMessage(),
                'debug' => (defined('APP_DEBUG') && APP_DEBUG) ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Récupérer les évaluateurs assignés à un article
     */
    public function getArticleReviewers($params = [])
    {
        $this->requireAdmin();
        
        $articleId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$articleId) {
            $this->respond(['error' => 'ID d\'article manquant'], 400);
            return;
        }

        $db = $this->db();
        
        $evaluateurs = $db->fetchAll("
            SELECT e.id as evaluation_id, e.statut, e.date_assignation, e.date_echeance,
                   u.id, u.nom, u.prenom, u.email, u.role,
                   DATEDIFF(e.date_echeance, CURDATE()) as jours_restants
            FROM evaluations e
            JOIN users u ON u.id = e.evaluateur_id
            WHERE e.article_id = :articleId
            ORDER BY e.date_assignation DESC
        ", [':articleId' => $articleId]);

        $this->respond(['evaluateurs' => $evaluateurs], 200);
    }

    /**
     * Retirer un évaluateur d'un article
     */
    public function unassignReviewer($params = [])
    {
        $this->requireAdmin();
        
        $articleId = is_array($params) ? ($params['article_id'] ?? null) : null;
        $evaluationId = is_array($params) ? ($params['evaluation_id'] ?? null) : null;
        
        if (!$articleId || !$evaluationId) {
            $this->respond(['error' => 'Paramètres manquants'], 400);
            return;
        }

        $db = $this->db();
        $reviewModel = new ReviewModel($db);
        
        // Récupérer l'évaluation pour obtenir l'évaluateur_id
        $evaluation = $db->fetchOne("
            SELECT evaluateur_id FROM evaluations 
            WHERE id = :id AND article_id = :articleId
        ", [':id' => $evaluationId, ':articleId' => $articleId]);
        
        if (!$evaluation) {
            $this->respond(['error' => 'Évaluation introuvable'], 404);
            return;
        }

        $success = $reviewModel->unassignReviewer($articleId, $evaluation['evaluateur_id']);

        if ($success) {
            $this->respond([
                'success' => true, 
                'message' => 'Évaluateur retiré avec succès'
            ], 200);
        } else {
            $this->respond(['error' => 'Erreur lors du retrait de l\'évaluateur'], 500);
        }
    }

    /**
     * Gérer l'identité de la revue
     */
    public function revueSettings() {
        $userId = $this->requireAdmin();
        $db = $this->db();
        $userModel = new UserModel($db);
        $user = $userModel->getUserById($userId);
        
        $revueInfoModel = new RevueInfoModel($db);
        $revueInfo = $revueInfoModel->getRevueInfo();
        
        \App\App::view('admin' . DIRECTORY_SEPARATOR . 'revue-settings', [
            'user' => $user,
            'revueInfo' => $revueInfo,
            'current_page' => 'revue-settings'
        ]);
    }

    /**
     * Mettre à jour l'identité de la revue
     */
    public function updateRevueSettings() {
        $userId = $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->respond(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $data = [
            'nom_officiel' => $_POST['nom_officiel'] ?? '',
            'description' => $_POST['description'] ?? '',
            'ligne_editoriale' => $_POST['ligne_editoriale'] ?? '',
            'objectifs' => $_POST['objectifs'] ?? '',
            'domaines_couverts' => $_POST['domaines_couverts'] ?? '',
            'issn' => $_POST['issn'] ?? '',
            'comite_scientifique' => $_POST['comite_scientifique'] ?? '',
            'comite_redaction' => $_POST['comite_redaction'] ?? ''
        ];
        
        $db = $this->db();
        $revueInfoModel = new RevueInfoModel($db);
        $result = $revueInfoModel->updateRevueInfo($data);
        
        if ($result !== false) {
            $this->respond([
                'success' => true,
                'message' => 'Paramètres de la revue mis à jour avec succès'
            ], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    /**
     * Améliorer la méthode volumes pour afficher volumes et issues
     */
    public function volumes() {
        $userId = $this->requireAdmin();
        $db = $this->db();
        $userModel = new UserModel($db);
        $user = $userModel->getUserById($userId);
        
        $volumeModel = new VolumeModel($db);
        $volumes = $volumeModel->getAllVolumes(1, 100);
        
        // Pour chaque volume, récupérer ses issues
        foreach ($volumes as &$volume) {
            $volume['issues'] = $volumeModel->getVolumeIssues($volume['id']);
        }
        
        // Récupérer aussi les issues non assignées à un volume
        $unassignedIssues = $db->fetchAll("
            SELECT r.*, 
                   (SELECT COUNT(*) FROM articles WHERE issue_id = r.id) as article_count
            FROM revues r 
            WHERE r.volume_id IS NULL 
            ORDER BY r.date_publication DESC
        ");
        
        \App\App::view('admin' . DIRECTORY_SEPARATOR . 'volumes', [
            'user' => $user,
            'volumes' => $volumes,
            'unassignedIssues' => $unassignedIssues,
            'current_page' => 'volumes'
        ]);
    }

    /**
     * Créer un volume
     */
    public function createVolume() {
        $userId = $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->respond(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $annee = (int)($_POST['annee'] ?? 0);
        if ($annee < 1980 || $annee > 2100) {
            $this->respond(['error' => 'Année invalide'], 400);
            return;
        }
        
        $data = [
            'numero_volume' => $_POST['numero_volume'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];
        
        $db = $this->db();
        $volumeModel = new VolumeModel($db);
        $result = $volumeModel->createVolume($annee, $data);
        
        if ($result !== false) {
            $this->respond([
                'success' => true,
                'message' => 'Volume créé avec succès',
                'volume_id' => $result
            ], 201);
        } else {
            $this->respond(['error' => 'Erreur lors de la création du volume (peut-être déjà existant)'], 500);
        }
    }

    /**
     * Créer un numéro (issue) dans un volume
     */
    public function createIssue() {
        // Désactiver l'affichage des erreurs pour éviter les messages HTML
        $oldDisplayErrors = ini_get('display_errors');
        ini_set('display_errors', '0');
        
        try {
            $userId = $this->requireAdmin();
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->respond(['error' => 'Méthode non autorisée'], 405);
                return;
            }
            
            $volumeId = (int)($_POST['volume_id'] ?? 0);
            if ($volumeId <= 0) {
                $this->respond(['error' => 'Volume invalide'], 400);
                return;
            }
            
            if (empty($_POST['numero']) || empty($_POST['titre'])) {
                $this->respond(['error' => 'Le numéro et le titre sont requis'], 400);
                return;
            }
            
            $data = [
                'numero' => trim($_POST['numero'] ?? ''),
                'titre' => trim($_POST['titre'] ?? ''),
                'description' => !empty($_POST['description']) ? trim($_POST['description']) : '',
                'fichier_path' => !empty($_POST['fichier_path']) ? trim($_POST['fichier_path']) : null,
                'date_publication' => !empty($_POST['date_publication']) ? trim($_POST['date_publication']) : null,
                'type' => $_POST['type'] ?? 'issue'
            ];
            
            $db = $this->db();
            $issueModel = new IssueModel($db);
            
            $result = $issueModel->createIssue($volumeId, $data);
            
            if ($result !== false && $result > 0) {
                $this->respond([
                    'success' => true,
                    'message' => 'Numéro créé avec succès',
                    'issue_id' => $result
                ], 201);
            } else {
                $this->respond(['error' => 'Erreur lors de la création du numéro. Vérifiez que le volume existe.'], 500);
            }
        } catch (\Exception $e) {
            error_log('Erreur createIssue AdminController: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            $this->respond([
                'error' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        } catch (\Error $e) {
            error_log('Erreur fatale createIssue AdminController: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            $this->respond([
                'error' => 'Erreur fatale: ' . $e->getMessage()
            ], 500);
        } finally {
            // Restaurer les paramètres d'erreur
            ini_set('display_errors', $oldDisplayErrors);
        }
    }

    /**
     * Assigner un article à un numéro
     */
    /**
     * Détails d'un numéro (issue) avec articles - Admin uniquement
     */
    public function issueDetails($params) {
        $userId = $this->requireAdmin();
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        
        if ($id <= 0) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Numéro introuvable']);
            return;
        }
        
        $db = $this->db();
        $issueModel = new IssueModel($db);
        $issue = $issueModel->getIssueById($id);
        
        if (!$issue) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Numéro introuvable']);
            return;
        }
        
        // Récupérer les articles de ce numéro
        $articles = $issueModel->getIssueArticles($id);
        
        // Récupérer les articles non assignés (publiés/acceptés) pour pouvoir les assigner
        $articleModel = new ArticleModel($db);
        $unassignedArticles = $articleModel->getUnassignedArticles(1, 100);
        
        $userModel = new UserModel($db);
        $user = $userModel->getUserById($userId);
        
        \App\App::view('admin' . DIRECTORY_SEPARATOR . 'issue-details', [
            'user' => $user,
            'issue' => $issue,
            'articles' => $articles,
            'unassignedArticles' => $unassignedArticles,
            'current_page' => 'volumes'
        ]);
    }

    /**
     * Mettre à jour un volume
     */
    public function updateVolume() {
        $userId = $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->respond(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $volumeId = (int)($_POST['volume_id'] ?? 0);
        if ($volumeId <= 0) {
            $this->respond(['error' => 'Volume invalide'], 400);
            return;
        }
        
        $data = [
            'numero_volume' => trim($_POST['numero_volume'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'comite_editorial' => trim($_POST['comite_editorial'] ?? '') ?: null,
            'redacteur_chef' => trim($_POST['redacteur_chef'] ?? '') ?: null
        ];
        
        $db = $this->db();
        $volumeModel = new VolumeModel($db);
        $result = $volumeModel->updateVolume($volumeId, $data);
        
        if ($result !== false) {
            $this->respond([
                'success' => true,
                'message' => 'Volume mis à jour avec succès'
            ], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la mise à jour du volume'], 500);
        }
    }

    /**
     * Mettre à jour un numéro (issue)
     */
    public function updateIssue() {
        $userId = $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->respond(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $issueId = (int)($_POST['issue_id'] ?? 0);
        if ($issueId <= 0) {
            $this->respond(['error' => 'Numéro invalide'], 400);
            return;
        }
        
        $data = [];
        if (isset($_POST['numero'])) {
            $data['numero'] = trim($_POST['numero']);
        }
        if (isset($_POST['titre'])) {
            $data['titre'] = trim($_POST['titre']);
        }
        if (isset($_POST['description'])) {
            $data['description'] = trim($_POST['description']);
        }
        if (isset($_POST['date_publication'])) {
            $data['date_publication'] = trim($_POST['date_publication']);
        }
        if (isset($_POST['volume_id'])) {
            $data['volume_id'] = (int)$_POST['volume_id'];
        }
        
        if (empty($data)) {
            $this->respond(['error' => 'Aucune donnée à mettre à jour'], 400);
            return;
        }
        
        $db = $this->db();
        $issueModel = new IssueModel($db);
        $result = $issueModel->updateIssue($issueId, $data);
        
        if ($result !== false) {
            $this->respond([
                'success' => true,
                'message' => 'Numéro mis à jour avec succès'
            ], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la mise à jour du numéro'], 500);
        }
    }

    /**
     * Assigner un numéro à un volume
     */
    public function assignIssueToVolume() {
        $userId = $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->respond(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $issueId = (int)($_POST['issue_id'] ?? 0);
        $volumeId = (int)($_POST['volume_id'] ?? 0);
        
        if ($issueId <= 0) {
            $this->respond(['error' => 'Numéro invalide'], 400);
            return;
        }
        
        if ($volumeId <= 0) {
            $this->respond(['error' => 'Volume invalide'], 400);
            return;
        }
        
        $db = $this->db();
        $issueModel = new IssueModel($db);
        
        // Vérifier que le volume existe
        $volumeModel = new VolumeModel($db);
        $volume = $volumeModel->getVolumeById($volumeId);
        if (!$volume) {
            $this->respond(['error' => 'Volume introuvable'], 404);
            return;
        }
        
        // Mettre à jour le numéro
        $result = $issueModel->updateIssue($issueId, ['volume_id' => $volumeId]);
        
        if ($result !== false) {
            $this->respond([
                'success' => true,
                'message' => 'Numéro assigné au volume avec succès'
            ], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de l\'assignation du numéro'], 500);
        }
    }

    /**
     * Détails d'un volume - Admin uniquement
     */
    public function volumeDetails($params) {
        $userId = $this->requireAdmin();
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        
        if ($id <= 0) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Volume introuvable']);
            return;
        }
        
        $db = $this->db();
        $volumeModel = new VolumeModel($db);
        $volume = $volumeModel->getVolumeById($id);
        
        if (!$volume) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Volume introuvable']);
            return;
        }
        
        // Récupérer les numéros de ce volume
        $issues = $volumeModel->getVolumeIssues($volume['id']);
        
        $userModel = new UserModel($db);
        $user = $userModel->getUserById($userId);
        
        \App\App::view('admin' . DIRECTORY_SEPARATOR . 'volume-details', [
            'user' => $user,
            'volume' => $volume,
            'issues' => $issues,
            'current_page' => 'volumes'
        ]);
    }

    public function assignArticleToIssue() {
        $userId = $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->respond(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $articleId = (int)($_POST['article_id'] ?? 0);
        $issueId = (int)($_POST['issue_id'] ?? 0);
        
        if ($articleId <= 0 || $issueId <= 0) {
            $this->respond(['error' => 'Paramètres invalides'], 400);
            return;
        }
        
        $db = $this->db();
        $articleModel = new ArticleModel($db);
        $result = $articleModel->assignToIssue($articleId, $issueId);
        
        if ($result !== false) {
            $this->respond([
                'success' => true,
                'message' => 'Article assigné au numéro avec succès'
            ], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de l\'assignation'], 500);
        }
    }
}

