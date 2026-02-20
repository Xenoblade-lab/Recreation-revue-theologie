<?php
use Models\ArticleModel;
use Models\Database;
use Models\IssueModel;
use Models\JournalModel;
use Models\ReviewModel;
use Models\UserModel;
use Models\VolumeModel;
//  =========== Helpers ================

/**
 * Crée une instance de base de données initialisée.
 */
function getDb(): Database
{
    $db = new Database();
    $db->connect();
    return $db;
}

/**
 * Récupère les données d'entrée (JSON ou POST classique).
 */
function input(): array
{
    $json = json_decode(file_get_contents('php://input'), true);
    return is_array($json) ? $json : ($_POST ?? []);
}

/**
 * Répond en JSON avec un statut HTTP optionnel.
 */
function respond($payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($payload);
}

//  =========== GET ================

Router\Router::get('/', [\Controllers\RevueController::class, 'index']);

// Page listant tous les articles publiés (vue publique)
Router\Router::get('/publications', [\Controllers\RevueController::class, 'publications']);

// Page de détails d'un article (vue publique)
Router\Router::get('/article/[i:id]', [\Controllers\RevueController::class, 'articleDetails']);
Router\Router::get('/download/article/[i:id]', [\Controllers\RevueController::class, 'downloadArticle']);
Router\Router::get('/download/issue/[i:id]', [\Controllers\RevueController::class, 'downloadIssue']);

// Routes publiques pour la revue
Router\Router::get('/archives', [\Controllers\RevueController::class, 'archives']);
Router\Router::get('/volume/[i:year]', [\Controllers\RevueController::class, 'volume']);
Router\Router::get('/admin/numero/[i:id]', [\Controllers\AdminController::class, 'issueDetails']);
// Redirection de l'ancienne route publique vers la route admin (pour compatibilité)
Router\Router::get('/numero/[i:id]', function($params) {
    // Admin : redirection vers l'interface admin du numéro
    if (isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) === 'admin') {
        header('Location: ' . \Router\Router::route('admin') . '/numero/' . $params['id']);
        exit;
    }
    // Public : page numéro avec liste des articles publiés
    (new \Controllers\RevueController())->issueDetailsPublic($params);
});

Router\Router::get('/comite', [\Controllers\RevueController::class, 'comite']);
Router\Router::get('/presentation', [\Controllers\RevueController::class, 'presentation']);

Router\Router::get('/instructions', function () {
    Service\AuthService::requireLogin();
    App\App::view('instructions');
});

Router\Router::get('/login', function () {
    App\App::view('login');
});

Router\Router::get('/search', [\Controllers\RevueController::class, 'search']);
Router\Router::post('/search', [\Controllers\RevueController::class, 'search']);

Router\Router::get('/register', function () {
    App\App::view('register');
});

Router\Router::get('/submit', function () {
    Service\AuthService::requireLogin();
    App\App::view('submit');
});

Router\Router::get('/test', function () {
    
});

Router\Router::get('/admin', [\Controllers\AdminController::class, 'index']);
Router\Router::get('/admin/users', [\Controllers\AdminController::class, 'users']);
Router\Router::get('/admin/volumes', [\Controllers\AdminController::class, 'volumes']);
Router\Router::get('/admin/paiements', [\Controllers\AdminController::class, 'paiements']);
Router\Router::get('/admin/settings', [\Controllers\AdminController::class, 'settings']);
Router\Router::get('/admin/articles', [\Controllers\AdminController::class, 'articles']);
Router\Router::get('/admin/article/[i:id]', [\Controllers\AdminController::class, 'articleDetails']);
Router\Router::get('/admin/evaluations', [\Controllers\AdminController::class, 'evaluations']);
Router\Router::get('/admin/evaluation/[i:id]', [\Controllers\AdminController::class, 'evaluationDetails']);
Router\Router::get('/admin/publications', [\Controllers\AdminController::class, 'publications']);
Router\Router::post('/admin/article/[i:id]/update-status', [\Controllers\AdminController::class, 'updateArticleStatus']);
Router\Router::post('/admin/article/[i:id]/publish', [\Controllers\AdminController::class, 'publishArticle']);
Router\Router::post('/admin/article/[i:id]/delete', [\Controllers\AdminController::class, 'deleteArticle']);
Router\Router::get('/admin/article/[i:id]/reviewers', [\Controllers\AdminController::class, 'getAvailableReviewers']);
Router\Router::get('/admin/article/[i:id]/assigned-reviewers', [\Controllers\AdminController::class, 'getArticleReviewers']);
Router\Router::post('/admin/article/[i:id]/assign-reviewer', [\Controllers\AdminController::class, 'assignArticleToReviewer']);
Router\Router::post('/admin/article/[i:article_id]/unassign-reviewer/[i:evaluation_id]', [\Controllers\AdminController::class, 'unassignReviewer']);
Router\Router::get('/admin/user/[i:id]', [\Controllers\AdminController::class, 'userDetails']);
Router\Router::get('/admin/user/[i:id]/json', [\Controllers\AdminController::class, 'getUser']);
Router\Router::post('/admin/user/create', [\Controllers\AdminController::class, 'createUser']);
Router\Router::post('/admin/evaluator/create', [\Controllers\AdminController::class, 'createEvaluator']);
Router\Router::post('/admin/user/[i:id]/update', [\Controllers\AdminController::class, 'updateUser']);
Router\Router::post('/admin/user/[i:id]/delete', [\Controllers\AdminController::class, 'deleteUser']);
Router\Router::post('/admin/user/[i:id]/update-status', [\Controllers\AdminController::class, 'updateUserStatus']);
Router\Router::post('/admin/volume/[i:id]/delete', [\Controllers\AdminController::class, 'deleteVolume']);
Router\Router::post('/admin/paiement/[i:id]/update-status', [\Controllers\AdminController::class, 'updatePaymentStatus']);
// Routes pour la gestion de la revue
Router\Router::get('/admin/revue/settings', [\Controllers\AdminController::class, 'revueSettings']);
Router\Router::post('/admin/revue/settings', [\Controllers\AdminController::class, 'updateRevueSettings']);
Router\Router::post('/admin/volumes/create', [\Controllers\AdminController::class, 'createVolume']);
Router\Router::post('/admin/issues/create', [\Controllers\AdminController::class, 'createIssue']);
Router\Router::post('/admin/issues/update', [\Controllers\AdminController::class, 'updateIssue']);
Router\Router::post('/admin/issues/assign-volume', [\Controllers\AdminController::class, 'assignIssueToVolume']);
Router\Router::post('/admin/volumes/update', [\Controllers\AdminController::class, 'updateVolume']);
Router\Router::get('/admin/volume/[i:id]', [\Controllers\AdminController::class, 'volumeDetails']);
// Redirection pour /admin/volume-details vers /admin/volumes
Router\Router::get('/admin/volume-details', function() {
    header('Location: ' . \Router\Router::route('admin') . '/volumes');
    exit;
});
Router\Router::post('/admin/articles/[i:id]/assign-issue', [\Controllers\AdminController::class, 'assignArticleToIssue']);
// Bascule de rôle (admin <-> reviewer/auteur) via AuthService
Router\Router::post('/switch-role', [\Service\AuthService::class, 'switchRole']);
Router\Router::get('/author', [\Controllers\AuthorController::class, 'index']);
Router\Router::get('/author/subscribe', [\Controllers\AuthorController::class, 'subscribe']);
Router\Router::post('/author/subscribe', [\Controllers\AuthorController::class, 'createSubscription']);
Router\Router::post('/author/abonnement/cancel', [\Controllers\AuthorController::class, 'cancelSubscription']);
Router\Router::post('/author/paiement/cancel', [\Controllers\AuthorController::class, 'cancelPayment']);
Router\Router::get('/author/paiement/receipt/[i:id]', [\Controllers\AuthorController::class, 'downloadReceipt']);
Router\Router::get('/author/articles', [\Controllers\AuthorController::class, 'articles']);
Router\Router::get('/author/abonnement', [\Controllers\AuthorController::class, 'abonnement']);
Router\Router::get('/author/profil', [\Controllers\AuthorController::class, 'profil']);
Router\Router::get('/author/article/[i:id]', [\Controllers\AuthorController::class, 'articleDetails']);
Router\Router::get('/author/article/[i:id]/edit', [\Controllers\AuthorController::class, 'articleEdit']);
Router\Router::get('/author/article/[i:id]/revisions', [\Controllers\AuthorController::class, 'articleRevisions']);
Router\Router::post('/author/article/[i:id]/update', [\Controllers\AuthorController::class, 'articleUpdate']);
Router\Router::post('/author/article/[i:id]/delete', [\Controllers\AuthorController::class, 'articleDelete']);
Router\Router::get('/author/notifications', [\Controllers\AuthorController::class, 'notifications']);
Router\Router::post('/author/notification/[i:id]/read', [\Controllers\AuthorController::class, 'markNotificationRead']);
Router\Router::post('/author/notifications/read-all', [\Controllers\AuthorController::class, 'markAllNotificationsRead']);

// ======== Reviewer Dashboard ========
Router\Router::get('/reviewer', [\Controllers\ReviewerController::class, 'index']);
Router\Router::get('/reviewer/terminees', [\Controllers\ReviewerController::class, 'terminees']);
Router\Router::get('/reviewer/historique', [\Controllers\ReviewerController::class, 'historique']);
Router\Router::get('/reviewer/profil', [\Controllers\ReviewerController::class, 'profil']);
Router\Router::get('/reviewer/publications', [\Controllers\ReviewerController::class, 'publications']);
Router\Router::get('/reviewer/evaluation/[i:id]', [\Controllers\ReviewerController::class, 'evaluationDetails']);
Router\Router::post('/reviewer/evaluation/[i:id]/save-draft', [\Controllers\ReviewerController::class, 'saveDraft']);
Router\Router::post('/reviewer/evaluation/[i:id]/submit', [\Controllers\ReviewerController::class, 'submitEvaluation']);

// ======== Routes ArticleModel ========
Router\Router::get('/articles', function () {
    $db = getDb();
    $model = new ArticleModel($db);
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 20;
    respond($model->getAllArticles($page, $limit));
});

Router\Router::get('/articles/[i:id]', function ($params) {
    $db = getDb();
    $model = new ArticleModel($db);
    $article = $model->getArticleById($params['id']);
    $article ? respond($article) : respond(['message' => 'Article introuvable'], 404);
});

Router\Router::post('/articles', function () {
    Service\AuthService::requireLogin();
    
    $db = getDb();
    $model = new ArticleModel($db);
    
    // Récupérer l'ID de l'utilisateur connecté
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        respond(['error' => 'Vous devez être connecté pour soumettre un article'], 401);
        return;
    }
    
    // Gérer l'upload du fichier
    $fichierPath = null;
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'articles' . DIRECTORY_SEPARATOR;
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $file = $_FILES['fichier'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['pdf', 'doc', 'docx', 'tex'];
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            respond(['error' => 'Format de fichier non autorisé. Formats acceptés : PDF, Word (.doc, .docx), LaTeX (.tex)'], 400);
            return;
        }
        
        // Générer un nom de fichier unique
        $fileName = uniqid('article_', true) . '_' . time() . '.' . $fileExtension;
        $filePath = $uploadDir . $fileName;
        
        // Déplacer le fichier
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Chemin relatif pour la base de données
            $fichierPath = 'uploads/articles/' . $fileName;
        } else {
            respond(['error' => 'Erreur lors de l\'upload du fichier'], 500);
            return;
        }
    } else {
        respond(['error' => 'Veuillez sélectionner un fichier'], 400);
        return;
    }
    
    // Préparer les données
    $data = [
        'titre' => $_POST['titre'] ?? '',
        'contenu' => $_POST['contenu'] ?? '',
        'fichier_path' => $fichierPath,
        'auteur_id' => $userId,
        'statut' => 'soumis'
    ];
    
    // Validation
    if (empty($data['titre']) || empty($data['contenu'])) {
        respond(['error' => 'Le titre et le résumé sont obligatoires'], 400);
        return;
    }
    
    try {
        $id = $model->createArticle($data);
        respond([
            'success' => true,
            'message' => 'Article soumis avec succès !',
            'id' => $id
        ], 201);
    } catch (Exception $e) {
        respond(['error' => 'Erreur lors de la création de l\'article : ' . $e->getMessage()], 500);
    }
});

Router\Router::post('/articles/[i:id]/update', function ($params) {
    $db = getDb();
    $model = new ArticleModel($db);
    $data = input();
    $model->updateArticle($params['id'], $data);
    respond(['message' => 'Article mis à jour']);
});

Router\Router::post('/articles/[i:id]/delete', function ($params) {
    $db = getDb();
    $model = new ArticleModel($db);
    $model->deleteArticle($params['id']);
    respond(['message' => 'Article supprimé']);
});

// ======== Routes JournalModel / VolumeModel ========
Router\Router::get('/revues', function () {
    $db = getDb();
    $model = new JournalModel($db);
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 20;
    respond($model->getAllJournals($page, $limit));
});

Router\Router::get('/revues/[i:id]', function ($params) {
    $db = getDb();
    $model = new JournalModel($db);
    $journal = $model->getJournalById($params['id']);
    $journal ? respond($journal) : respond(['message' => 'Revue introuvable'], 404);
});

Router\Router::post('/revues', function () {
    $db = getDb();
    $model = new VolumeModel($db);
    $data = input();
    $id = $model->createVolume($data);
    respond(['id' => $id], 201);
});

Router\Router::post('/revues/[i:id]/update', function ($params) {
    $db = getDb();
    $model = new JournalModel($db);
    $model->updateJournal($params['id'], input());
    respond(['message' => 'Revue mise à jour']);
});

Router\Router::post('/revues/[i:id]/delete', function ($params) {
    $db = getDb();
    $model = new JournalModel($db);
    $model->deleteJournal($params['id']);
    respond(['message' => 'Revue supprimée']);
});

// ======== Routes IssueModel (revue_parts) ========
Router\Router::get('/issues/[i:id]', function ($params) {
    $db = getDb();
    $model = new IssueModel($db);
    $issue = $model->getIssueById($params['id']);
    $issue ? respond($issue) : respond(['message' => 'Numéro introuvable'], 404);
});

Router\Router::get('/revues/[i:revueId]/issues', function ($params) {
    $db = getDb();
    $model = new IssueModel($db);
    respond($model->getIssuesByJournal($params['revueId']));
});

Router\Router::post('/revues/[i:revueId]/issues', function ($params) {
    $db = getDb();
    $model = new IssueModel($db);
    $id = $model->createIssue($params['revueId'], input());
    respond(['id' => $id], 201);
});

Router\Router::post('/issues/[i:id]/update', function ($params) {
    $db = getDb();
    $model = new IssueModel($db);
    $model->updateIssue($params['id'], input());
    respond(['message' => 'Numéro mis à jour']);
});

Router\Router::post('/issues/[i:id]/delete', function ($params) {
    $db = getDb();
    $model = new IssueModel($db);
    $model->deleteIssue($params['id']);
    respond(['message' => 'Numéro supprimé']);
});

// ======== Routes UserModel ========
Router\Router::get('/users', function () {
    $db = getDb();
    $model = new UserModel($db);
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 20;
    respond($model->getAllUsers($page, $limit));
});

Router\Router::get('/users/[i:id]', function ($params) {
    $db = getDb();
    $model = new UserModel($db);
    $user = $model->getUserById($params['id']);
    $user ? respond($user) : respond(['message' => 'Utilisateur introuvable'], 404);
});

Router\Router::post('/users', function () {
    $db = getDb();
    $model = new UserModel($db);
    $model->createUser(input());
    respond(['message' => 'Utilisateur créé'], 201);
});

Router\Router::post('/users/[i:id]/update', function ($params) {
    $db = getDb();
    $model = new UserModel($db);
    $model->updateUser($params['id'], input());
    respond(['message' => 'Utilisateur mis à jour']);
});

Router\Router::post('/users/[i:id]/delete', function ($params) {
    $db = getDb();
    $model = new UserModel($db);
    $model->deleteUser($params['id']);
    respond(['message' => 'Utilisateur suspendu']);
});

// ======== Routes ReviewModel (évaluations) ========
Router\Router::get('/reviews/[i:id]', function ($params) {
    $db = getDb();
    $model = new ReviewModel($db);
    $review = $model->getReviewById($params['id']);
    $review ? respond($review) : respond(['message' => 'Évaluation introuvable'], 404);
});

Router\Router::get('/articles/[i:id]/reviews', function ($params) {
    $db = getDb();
    $model = new ReviewModel($db);
    respond($model->getReviewsByArticle($params['id']));
});

Router\Router::post('/reviews/assign', function () {
    $db = getDb();
    $model = new ReviewModel($db);
    $data = input();
    $model->assignReviewer($data['article_id'], $data['reviewer_id'], $data['deadline_days'] ?? 14);
    respond(['message' => 'Évaluateur assigné']);
});

Router\Router::post('/reviews/[i:id]/submit', function ($params) {
    $db = getDb();
    $model = new ReviewModel($db);
    $model->submitReview($params['id'], input());
    respond(['message' => 'Évaluation soumise']);
});

Router\Router::post('/reviews/[i:id]/accept', function ($params) {
    $db = getDb();
    $model = new ReviewModel($db);
    $model->acceptReviewAssignment($params['id']);
    respond(['message' => 'Invitation acceptée']);
});

Router\Router::post('/reviews/[i:id]/decline', function ($params) {
    $db = getDb();
    $model = new ReviewModel($db);
    $data = input();
    $model->declineReviewAssignment($params['id'], $data['reason'] ?? null);
    respond(['message' => 'Invitation déclinée']);
});

// =========== POST ================
Router\Router::post('/login', function () {
    $auth = new Service\AuthService();
    $auth->login(input());
});

Router\Router::post('/register', function () {
    $auth = new Service\AuthService();
    $auth->sign(input());
});

Router\Router::get('/logout', function () {
    $auth = new Service\AuthService();
    $auth->logout();
});

Router\Router::post('/logout', function () {
    $auth = new Service\AuthService();
    $auth->logout();
});

// Route pour récupérer les notifications
Router\Router::get('/api/notifications', function () {
    Service\AuthService::requireLogin();
    $db = getDb();
    $userModel = new UserModel($db);
    $userId = $_SESSION['user_id'] ?? null;
    
    if (!$userId) {
        respond(['notifications' => []], 200);
        return;
    }
    
    // Récupérer les notifications (à adapter selon votre structure)
    $notifications = [];
    // TODO: Implémenter la récupération des notifications depuis la base de données
    
    respond(['notifications' => $notifications], 200);
});

// Route pour marquer une notification comme lue
Router\Router::post('/api/notifications/[i:id]/read', function ($params) {
    Service\AuthService::requireLogin();
    // TODO: Implémenter la mise à jour de la notification
    respond(['message' => 'Notification marquée comme lue'], 200);
});

?>