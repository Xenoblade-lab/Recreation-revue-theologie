<?php
/**
 * Routes web — Revue Congolaise de Théologie Protestante
 */
use Router\Router;
use Controllers\RevueController;
use Controllers\AuthController;
use Controllers\AuthorController;
use Controllers\ReviewerController;
use Controllers\AdminController;
use Service\AuthService;

// Changement de langue (FR, EN, Lingala)
Router::get('/lang', function (array $params = []) {
    $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
    $l = isset($_GET['l']) ? trim($_GET['l']) : '';
    if (set_lang($l)) {
        $redirect = $_GET['redirect'] ?? $_SERVER['HTTP_REFERER'] ?? $base . '/';
        if (!preg_match('#^' . preg_quote($base, '#') . '#', $redirect)) {
            $redirect = $base . '/';
        }
        header('Location: ' . $redirect);
        exit;
    }
    header('Location: ' . $base . '/');
    exit;
});

Router::get('/login', [AuthController::class, 'showLogin']);
Router::post('/login', [AuthController::class, 'login']);
Router::get('/register', [AuthController::class, 'showRegister']);
Router::post('/register', [AuthController::class, 'register']);
Router::get('/logout', [AuthController::class, 'logout']);
Router::get('/forgot-password', [AuthController::class, 'showForgotPassword']);
Router::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Router::get('/author', [AuthorController::class, 'index']);
Router::get('/author/abonnement', [AuthorController::class, 'abonnement']);
Router::post('/author/abonnement/cancel', [AuthorController::class, 'abonnementCancel']);
Router::post('/author/paiement/[i:id]/cancel', [AuthorController::class, 'paiementCancel']);
Router::get('/author/paiement/receipt/[i:id]', [AuthorController::class, 'paiementReceipt']);
Router::get('/author/s-abonner', [AuthorController::class, 'sAbonner']);
Router::post('/author/s-abonner', [AuthorController::class, 'sAbonnerSubmit']);
Router::get('/author/profil', [AuthorController::class, 'profil']);
Router::post('/author/profil', [AuthorController::class, 'profilUpdate']);
Router::get('/author/soumettre', [AuthorController::class, 'showSoumettre']);
Router::post('/author/soumettre', [AuthorController::class, 'soumettre']);
Router::get('/author/article/[i:id]', [AuthorController::class, 'articleDetail']);
Router::get('/author/article/[i:id]/revisions', [AuthorController::class, 'articleRevisions']);
Router::get('/author/article/[i:id]/edit', [AuthorController::class, 'articleEdit']);
Router::post('/author/article/[i:id]/edit', [AuthorController::class, 'articleUpdate']);
Router::post('/author/article/[i:id]/submit', [AuthorController::class, 'articleSubmitDraft']);
Router::get('/author/notifications', [AuthorController::class, 'notifications']);
Router::post('/author/notification/[s:id]/read', [AuthorController::class, 'notificationMarkRead']);
Router::post('/author/notifications/read-all', [AuthorController::class, 'notificationsMarkAllRead']);

Router::get('/soumettre', function (array $params = []) {
    $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
    if (!AuthService::isLoggedIn()) { header('Location: ' . $base . '/login'); exit; }
    if (AuthService::hasRole('auteur')) { header('Location: ' . $base . '/author/soumettre'); exit; }
    header('Location: ' . $base . '/author/s-abonner'); exit;
});

Router::get('/reviewer', [ReviewerController::class, 'index']);
Router::get('/reviewer/evaluation/[i:id]', [ReviewerController::class, 'evaluation']);
Router::post('/reviewer/evaluation/[i:id]', [ReviewerController::class, 'evaluationPost']);
Router::get('/reviewer/terminees', [ReviewerController::class, 'terminees']);
Router::get('/reviewer/historique', [ReviewerController::class, 'historique']);
Router::get('/reviewer/notifications', [ReviewerController::class, 'notifications']);
Router::get('/reviewer/profil', [ReviewerController::class, 'profil']);
Router::post('/reviewer/profil', [ReviewerController::class, 'profilUpdate']);
Router::post('/reviewer/notification/[s:id]/read', [ReviewerController::class, 'notificationMarkRead']);
Router::post('/reviewer/notifications/read-all', [ReviewerController::class, 'notificationsMarkAllRead']);

Router::get('/admin', [AdminController::class, 'index']);
Router::get('/admin/notifications', [AdminController::class, 'notifications']);
Router::post('/admin/notification/[s:id]/read', [AdminController::class, 'notificationMarkRead']);
Router::post('/admin/notifications/read-all', [AdminController::class, 'notificationsMarkAllRead']);
Router::get('/admin/users', [AdminController::class, 'users']);
Router::get('/admin/users/create', [AdminController::class, 'userCreate']);
Router::post('/admin/users/create', [AdminController::class, 'userStore']);
Router::get('/admin/users/[i:id]/edit', [AdminController::class, 'userEdit']);
Router::post('/admin/users/[i:id]/edit', [AdminController::class, 'userUpdate']);
Router::get('/admin/articles', [AdminController::class, 'articles']);
Router::get('/admin/evaluations', [AdminController::class, 'evaluations']);
Router::get('/admin/article/[i:id]', [AdminController::class, 'articleDetail']);
Router::post('/admin/article/[i:id]/statut', [AdminController::class, 'articleUpdateStatut']);
Router::post('/admin/article/[i:id]/assign', [AdminController::class, 'articleAssign']);
Router::post('/admin/article/[i:id]/issue', [AdminController::class, 'articleSetIssue']);
Router::get('/admin/paiements', [AdminController::class, 'paiements']);
Router::post('/admin/paiement/[i:id]/statut', [AdminController::class, 'paiementStatut']);
Router::post('/admin/paiement/[i:id]/valider', [AdminController::class, 'paiementValider']);
Router::post('/admin/paiement/[i:id]/refuser', [AdminController::class, 'paiementRefuser']);
Router::get('/admin/volumes', [AdminController::class, 'volumes']);
Router::get('/admin/volume/[i:id]', [AdminController::class, 'volumeDetail']);
Router::post('/admin/volume/[i:id]', [AdminController::class, 'volumeUpdate']);
Router::get('/admin/numero/[i:id]', [AdminController::class, 'numeroDetail']);
Router::post('/admin/numero/[i:id]', [AdminController::class, 'numeroUpdate']);
Router::get('/admin/parametres', [AdminController::class, 'parametres']);
Router::post('/admin/parametres', [AdminController::class, 'parametresUpdate']);

Router::get('/', [RevueController::class, 'index']);
Router::post('/newsletter', [RevueController::class, 'newsletterSubmit']);
Router::get('/search', [RevueController::class, 'search']);
Router::get('/download/article/[i:id]', [RevueController::class, 'downloadArticle']);
Router::get('/publications', [RevueController::class, 'publications']);
Router::get('/archives', [RevueController::class, 'archives']);
Router::get('/article/[i:id]', [RevueController::class, 'articleDetails']);
Router::get('/numero/[i:id]', [RevueController::class, 'numeroDetails']);
Router::get('/presentation', [RevueController::class, 'presentation']);
Router::get('/comite', [RevueController::class, 'comite']);
Router::get('/contact', [RevueController::class, 'contact']);
Router::get('/faq', [RevueController::class, 'faq']);
Router::get('/politique-editoriale', [RevueController::class, 'politiqueEditoriale']);
Router::get('/instructions-auteurs', [RevueController::class, 'instructionsAuteurs']);
Router::get('/templates/[s:file]', [RevueController::class, 'downloadTemplate']);
Router::get('/actualites', [RevueController::class, 'actualites']);
Router::get('/mentions-legales', [RevueController::class, 'mentionsLegales']);
Router::get('/conditions-utilisation', [RevueController::class, 'conditionsUtilisation']);
Router::get('/confidentialite', [RevueController::class, 'confidentialite']);
