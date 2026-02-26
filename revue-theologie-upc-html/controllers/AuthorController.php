<?php
namespace Controllers;

use Service\AuthService;
use Models\ArticleModel;
use Models\AbonnementModel;
use Models\PaiementModel;
use Models\NotificationModel;
use Models\UserModel;
use Models\EvaluationModel;

/**
 * Contrôleur espace auteur (dashboard, abonnement, soumission, articles, notifications).
 */
class AuthorController
{
    private function base(): string
    {
        return defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
    }

    private function render(string $viewName, array $data = [], ?string $pageTitle = null, string $authorPage = '', bool $requireAuthor = true): void
    {
        if ($requireAuthor) {
            requireAuthor();
        } else {
            requireAuthorOrSubscribe();
        }
        $_SESSION['author_page'] = $authorPage;
        // Ne pas fermer la session ici : les vues (ex. article-edit) utilisent csrf_field() qui doit pouvoir enregistrer le jeton pour la soumission POST.
        $base = $this->base();
        $user = AuthService::getUser();
        $data['base'] = $base;
        $data['currentUser'] = $user;
        $data['isAuthor'] = AuthService::hasRole('auteur') || AuthService::hasRole('admin')
            || (class_exists('Models\AbonnementModel') && \Models\AbonnementModel::hasActiveSubscription((int) ($user['id'] ?? 0)));
        extract($data);
        ob_start();
        require BASE_PATH . '/views/author/' . $viewName . '.php';
        $viewContent = ob_get_clean();
        $pageTitle = $pageTitle ?? 'Espace auteur | Revue Congolaise de Théologie Protestante';
        require BASE_PATH . '/views/layouts/author-dashboard.php';
    }

    public function index(array $params = []): void
    {
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $articles = ArticleModel::getByAuthorId($userId, 50);
        $abonnement = AbonnementModel::getActiveByUserId($userId);
        $stats = [
            'total'    => ArticleModel::countByAuthorId($userId),
            'brouillon' => ArticleModel::countByAuthorIdAndStatut($userId, 'brouillon'),
            'soumis'   => ArticleModel::countByAuthorIdAndStatut($userId, 'soumis'),
            'valide'   => ArticleModel::countByAuthorIdAndStatut($userId, 'valide'),
            'rejete'   => ArticleModel::countByAuthorIdAndStatut($userId, 'rejete'),
        ];
        $this->render('index', [
            'articles'   => $articles,
            'abonnement' => $abonnement,
            'stats'      => $stats,
        ], 'Tableau de bord auteur | Revue Congolaise de Théologie Protestante', 'index', false);
    }

    public function abonnement(array $params = []): void
    {
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $abonnements = AbonnementModel::getByUserId($userId);
        $paiements = PaiementModel::getByUserId($userId);
        $abonnementActif = AbonnementModel::getActiveByUserId($userId);
        $error = $_SESSION['author_error'] ?? null;
        $success = $_SESSION['author_success'] ?? false;
        unset($_SESSION['author_error'], $_SESSION['author_success']);
        $this->render('abonnement', [
            'abonnements'     => $abonnements,
            'paiements'       => $paiements,
            'abonnementActif' => $abonnementActif,
            'error'           => $error,
            'success'         => $success,
        ], 'Mon abonnement | Espace auteur - Revue Congolaise de Théologie Protestante', 'abonnement', false);
    }

    /** Résilier l'abonnement actif (POST avec confirmation côté client). */
    public function abonnementCancel(array $params = []): void
    {
        requireAuthor();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/author/abonnement');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['author_error'] = 'Requête invalide. Veuillez réessayer.';
            header('Location: ' . $this->base() . '/author/abonnement');
            exit;
        }
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $abonnementId = (int) ($_POST['abonnement_id'] ?? 0);
        if ($abonnementId && AbonnementModel::cancel($abonnementId, $userId)) {
            if (!AbonnementModel::hasActiveSubscription($userId)) {
                UserModel::updateRole($userId, 'user');
                AuthService::refreshUser();
            }
            $_SESSION['author_success'] = true;
        } else {
            $_SESSION['author_error'] = __('author.cancel_subscription_error') ?: 'Impossible de résilier l\'abonnement.';
        }
        release_session();
        header('Location: ' . $this->base() . '/author/abonnement');
        exit;
    }

    /** Annuler un paiement en attente (POST). */
    public function paiementCancel(array $params = []): void
    {
        requireAuthor();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/author/abonnement');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['author_error'] = 'Requête invalide.';
            header('Location: ' . $this->base() . '/author/abonnement');
            exit;
        }
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $paiementId = (int) ($params['id'] ?? $_POST['paiement_id'] ?? 0);
        if ($paiementId && PaiementModel::cancelEnAttente($paiementId, $userId)) {
            $_SESSION['author_success'] = 'payment_cancelled';
        } else {
            $_SESSION['author_error'] = __('author.cancel_payment_error') ?: 'Impossible d\'annuler ce paiement.';
        }
        release_session();
        header('Location: ' . $this->base() . '/author/abonnement');
        exit;
    }

    /** Téléchargement / affichage du reçu (GET). */
    public function paiementReceipt(array $params = []): void
    {
        requireAuthor();
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $id = (int) ($params['id'] ?? 0);
        $p = $id ? PaiementModel::getByIdAndUser($id, $userId) : null;
        if (!$p) {
            header('Location: ' . $this->base() . '/author/abonnement');
            exit;
        }
        $base = $this->base();
        $date = !empty($p['date_paiement']) ? date('d/m/Y H:i', strtotime($p['date_paiement'])) : date('d/m/Y');
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>Reçu de paiement</title><body style="font-family:sans-serif;max-width:480px;margin:2rem auto;padding:1rem;">';
        echo '<h1>Reçu de paiement</h1>';
        echo '<p><strong>Date :</strong> ' . htmlspecialchars($date) . '</p>';
        echo '<p><strong>Montant :</strong> ' . htmlspecialchars(number_format((float) $p['montant'], 2, ',', ' ')) . ' USD</p>';
        echo '<p><strong>Moyen :</strong> ' . htmlspecialchars($p['moyen'] ?? '—') . '</p>';
        echo '<p><strong>Statut :</strong> ' . htmlspecialchars($p['statut'] ?? '—') . '</p>';
        echo '<p><a href="' . htmlspecialchars($base . '/author/abonnement') . '">← Retour à l\'abonnement</a></p>';
        echo '</body></html>';
        exit;
    }

    /** Formules d'abonnement auteur par région (doc : Afrique 25$, Europe 30$, Amérique 35$, durée 1 an). */
    private static function getFormulesAbonnement(): array
    {
        return [
            ['id' => 'afrique', 'region' => 'Afrique', 'duree_label' => '1 an', 'montant' => 25, 'currency' => 'USD'],
            ['id' => 'europe', 'region' => 'Europe', 'duree_label' => '1 an', 'montant' => 30, 'currency' => 'USD'],
            ['id' => 'amerique', 'region' => 'Amérique', 'duree_label' => '1 an', 'montant' => 35, 'currency' => 'USD'],
        ];
    }

    public function sAbonner(array $params = []): void
    {
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        if (AbonnementModel::hasActiveSubscription($userId)) {
            $_SESSION['author_success'] = true;
            release_session();
            header('Location: ' . $this->base() . '/author/abonnement');
            exit;
        }
        $hasPendingRequest = PaiementModel::hasPendingByUserId($userId);
        $formules = self::getFormulesAbonnement();
        $error = $_SESSION['author_error'] ?? null;
        unset($_SESSION['author_error']);
        $this->render('s-abonner', [
            'formules'          => $formules,
            'error'             => $error,
            'hasPendingRequest' => $hasPendingRequest,
        ], __('author.subscribe_title') ?: 'S\'abonner | Espace auteur', 's-abonner', false);
    }

    public function sAbonnerSubmit(array $params = []): void
    {
        requireAuthorOrSubscribe();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/author/s-abonner');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['author_error'] = 'Requête invalide. Veuillez réessayer.';
            header('Location: ' . $this->base() . '/author/s-abonner');
            exit;
        }
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        if (AbonnementModel::hasActiveSubscription($userId)) {
            release_session();
            header('Location: ' . $this->base() . '/author/abonnement');
            exit;
        }
        if (PaiementModel::hasPendingByUserId($userId)) {
            $_SESSION['author_error'] = __('author.subscribe_error_one_pending') ?: 'Vous avez déjà une demande d\'abonnement en attente. Attendez la validation ou le refus avant d\'en soumettre une nouvelle.';
            release_session();
            header('Location: ' . $this->base() . '/author/abonnement');
            exit;
        }
        $formuleId = trim($_POST['formule_id'] ?? '');
        $formules = self::getFormulesAbonnement();
        $formule = null;
        foreach ($formules as $f) {
            if (($f['id'] ?? '') === $formuleId) {
                $formule = $f;
                break;
            }
        }
        if (!$formule) {
            $_SESSION['author_error'] = __('author.subscribe_error_formula') ?: 'Veuillez choisir une formule.';
            header('Location: ' . $this->base() . '/author/s-abonner');
            exit;
        }
        $montant = (float) ($formule['montant'] ?? 0);
        if ($montant <= 0) {
            $_SESSION['author_error'] = __('author.subscribe_error_amount') ?: 'Montant invalide.';
            header('Location: ' . $this->base() . '/author/s-abonner');
            exit;
        }
        $region = $formule['id'] ?? $formuleId; // afrique, europe, amerique
        $regionLabel = $formule['region'] ?? $region;
        $moyen = trim($_POST['moyen'] ?? '');
        $moyensValides = ['orange_money', 'mpesa', 'airtel_money', 'bancaire'];
        if (!in_array($moyen, $moyensValides, true)) {
            $_SESSION['author_error'] = __('author.subscribe_error_payment_method') ?: 'Veuillez choisir un moyen de paiement.';
            header('Location: ' . $this->base() . '/author/s-abonner');
            exit;
        }
        $paymentDetailsJson = null;
        if ($moyen === 'bancaire') {
            $cardNumber = preg_replace('/\s/', '', trim($_POST['cardNumber'] ?? ''));
            $cardLast4 = strlen($cardNumber) >= 4 ? substr($cardNumber, -4) : '';
            $paymentDetailsJson = json_encode(['cardLast4' => $cardLast4], JSON_UNESCAPED_UNICODE);
        } else {
            $phone = trim($_POST['phoneNumber'] ?? '');
            if (strlen($phone) < 9) {
                $_SESSION['author_error'] = __('author.subscribe_error_phone') ?: 'Numéro de téléphone requis ou invalide.';
                header('Location: ' . $this->base() . '/author/s-abonner');
                exit;
            }
            $paymentDetailsJson = json_encode(['phoneNumber' => $phone], JSON_UNESCAPED_UNICODE);
        }
        $paiementId = PaiementModel::createDemandeAbonnement($userId, $montant, $moyen, $region, $paymentDetailsJson);
        if (!$paiementId) {
            $_SESSION['author_error'] = __('author.subscribe_error_save') ?: 'Erreur lors de l\'enregistrement. Veuillez réessayer.';
            header('Location: ' . $this->base() . '/author/s-abonner');
            exit;
        }
        foreach (UserModel::getIdsByRole('admin', 'redacteur en chef') as $adminId) {
            NotificationModel::create((int) $adminId, 'subscription_request', [
                'message' => __('admin.notif_subscription_request') ?: 'Nouvelle demande d\'abonnement auteur en attente de validation.',
                'link' => '/admin/paiements',
            ]);
        }
        $_SESSION['author_success'] = 'subscribe_pending';
        release_session();
        header('Location: ' . $this->base() . '/author/abonnement');
        exit;
    }

    public function profil(array $params = []): void
    {
        $user = AuthService::getUser();
        $error = $_SESSION['author_error'] ?? null;
        $success = !empty($_SESSION['author_success']);
        unset($_SESSION['author_error'], $_SESSION['author_success']);
        $this->render('profil', [
            'user'   => $user,
            'error'  => $error,
            'success' => $success,
        ], 'Mon profil | Espace auteur - Revue Congolaise de Théologie Protestante', 'profil');
    }

    public function profilUpdate(array $params = []): void
    {
        requireAuthor();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/author/profil');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['author_error'] = 'Requête invalide. Veuillez réessayer.';
            header('Location: ' . $this->base() . '/author/profil');
            exit;
        }
        $user = AuthService::getUser();
        $id = (int) $user['id'];
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $newPassword = $_POST['password'] ?? '';
        if (!$nom || !$prenom || !$email) {
            $_SESSION['author_error'] = __('author.profil_error_required') ?: 'Nom, prénom et email sont obligatoires.';
            header('Location: ' . $this->base() . '/author/profil');
            exit;
        }
        if (UserModel::emailExists($email, $id)) {
            $_SESSION['author_error'] = __('author.profil_error_email') ?: 'Cet email est déjà utilisé.';
            header('Location: ' . $this->base() . '/author/profil');
            exit;
        }
        $hash = strlen($newPassword) >= 6 ? password_hash($newPassword, PASSWORD_DEFAULT) : null;
        if (UserModel::updateProfile($id, $nom, $prenom, $email, $hash)) {
            $_SESSION['author_success'] = true;
            AuthService::refreshUser();
        } else {
            $_SESSION['author_error'] = __('author.profil_error_save') ?: 'Erreur lors de l\'enregistrement.';
        }
        release_session();
        header('Location: ' . $this->base() . '/author/profil');
        exit;
    }

    public function showSoumettre(array $params = []): void
    {
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $abonnementActif = AbonnementModel::hasActiveSubscription($userId);
        $this->render('soumettre', [
            'abonnementActif' => $abonnementActif,
            'error'           => $_SESSION['author_error'] ?? null,
            'old'             => $_SESSION['author_old'] ?? [],
        ], 'Soumettre un article | Revue Congolaise de Théologie Protestante', 'soumettre');
        unset($_SESSION['author_error'], $_SESSION['author_old']);
    }

    public function soumettre(array $params = []): void
    {
        requireAuthor();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/author/soumettre');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['author_error'] = 'Requête invalide. Veuillez réessayer.';
            header('Location: ' . $this->base() . '/author/soumettre');
            exit;
        }
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $titre = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');
        $_SESSION['author_old'] = ['titre' => $titre, 'contenu' => $contenu];

        if (!$titre || !$contenu) {
            $_SESSION['author_error'] = 'Titre et contenu sont obligatoires.';
            header('Location: ' . $this->base() . '/author/soumettre');
            exit;
        }

        $action = isset($_POST['action']) && $_POST['action'] === 'draft' ? 'draft' : 'submit';
        $statut = $action === 'draft' ? 'brouillon' : 'soumis';

        $fichierPath = null;
        $fichierNomOriginal = null;
        if (!empty($_FILES['fichier']['tmp_name']) && is_uploaded_file($_FILES['fichier']['tmp_name'])) {
            $uploadDir = BASE_PATH . '/public/uploads/articles';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }
            $ext = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['pdf', 'doc', 'docx'], true)) {
                $_SESSION['author_error'] = 'Format de fichier accepté : PDF, DOC, DOCX.';
                header('Location: ' . $this->base() . '/author/soumettre');
                exit;
            }
            $safeName = 'article_' . uniqid() . '_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['fichier']['tmp_name'], $uploadDir . '/' . $safeName)) {
                $fichierPath = 'uploads/articles/' . $safeName;
                $fichierNomOriginal = basename($_FILES['fichier']['name']);
            }
        }

        $id = ArticleModel::create($userId, $titre, $contenu, $fichierPath, $fichierNomOriginal, $statut);
        if ($id) {
            unset($_SESSION['author_old']);
            header('Location: ' . $this->base() . '/author/article/' . $id);
            exit;
        }
        $_SESSION['author_error'] = 'Une erreur est survenue. Veuillez réessayer.';
        header('Location: ' . $this->base() . '/author/soumettre');
        exit;
    }

    public function articleDetail(array $params = []): void
    {
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $id = (int) ($params['id'] ?? 0);
        $article = $id ? ArticleModel::getByIdForAuthor($id, $userId) : null;
        if (!$article) {
            http_response_code(404);
            $viewContent = '<div class="container section"><h1>Article introuvable</h1><p><a href="' . $this->base() . '/author">Retour au tableau de bord</a></p></div>';
            $pageTitle = 'Article | Revue Congolaise de Théologie Protestante';
            require BASE_PATH . '/views/layouts/author-dashboard.php';
            return;
        }
        $_SESSION['author_page'] = '';
        $success = $_SESSION['author_success'] ?? null;
        unset($_SESSION['author_success']);
        $this->render('article-detail', ['article' => $article, 'success' => $success], htmlspecialchars($article['titre']) . ' | Mes articles', '');
    }

    public function articleRevisions(array $params = []): void
    {
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $id = (int) ($params['id'] ?? 0);
        $article = $id ? ArticleModel::getByIdForAuthor($id, $userId) : null;
        if (!$article) {
            http_response_code(404);
            $viewContent = '<div class="container section"><h1>Article introuvable</h1><p><a href="' . $this->base() . '/author">Retour au tableau de bord</a></p></div>';
            $pageTitle = 'Révisions | Revue Congolaise de Théologie Protestante';
            require BASE_PATH . '/views/layouts/author-dashboard.php';
            return;
        }
        $evaluations = EvaluationModel::getByArticleIdForAuthor($id);
        $this->render('article-revisions', [
            'article'      => $article,
            'evaluations'  => $evaluations,
        ], __('author.revisions_title') ?: 'Historique des révisions | ' . htmlspecialchars($article['titre']), '');
    }

    public function articleEdit(array $params = []): void
    {
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $id = (int) ($params['id'] ?? 0);
        $article = $id ? ArticleModel::getByIdForAuthor($id, $userId) : null;
        if (!$article || !in_array($article['statut'] ?? '', ['soumis', 'brouillon'], true)) {
            header('Location: ' . $this->base() . '/author');
            exit;
        }
        $error = $_SESSION['author_error'] ?? null;
        unset($_SESSION['author_error']);
        $this->render('article-edit', [
            'article' => $article,
            'error'   => $error,
        ], 'Modifier l\'article | Revue Congolaise de Théologie Protestante', '');
    }

    public function articleUpdate(array $params = []): void
    {
        requireAuthor();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/author');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['author_error'] = 'Requête invalide. Veuillez réessayer.';
            $id = (int) ($params['id'] ?? 0);
            header('Location: ' . $this->base() . '/author/article/' . $id . '/edit');
            exit;
        }
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $id = (int) ($params['id'] ?? 0);
        $article = $id ? ArticleModel::getByIdForAuthor($id, $userId) : null;
        if (!$article || !in_array($article['statut'] ?? '', ['soumis', 'brouillon'], true)) {
            header('Location: ' . $this->base() . '/author');
            exit;
        }
        $titre = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');
        if (!$titre || !$contenu) {
            $_SESSION['author_error'] = 'Titre et contenu sont obligatoires.';
            header('Location: ' . $this->base() . '/author/article/' . $id . '/edit');
            exit;
        }
        $fichierPath = null;
        $fichierNomOriginal = null;
        if (!empty($_FILES['fichier']['tmp_name']) && is_uploaded_file($_FILES['fichier']['tmp_name'])) {
            $uploadDir = BASE_PATH . '/public/uploads/articles';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }
            $ext = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['pdf', 'doc', 'docx'], true)) {
                $safeName = 'article_' . uniqid() . '_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['fichier']['tmp_name'], $uploadDir . '/' . $safeName)) {
                    $fichierPath = 'uploads/articles/' . $safeName;
                    $fichierNomOriginal = basename($_FILES['fichier']['name']);
                }
            }
        }
        ArticleModel::updateByAuthor($id, $userId, $titre, $contenu, $fichierPath, $fichierNomOriginal);
        $_SESSION['author_success'] = 'saved'; // message après redirection
        header('Location: ' . $this->base() . '/author/article/' . $id);
        exit;
    }

    /** Soumettre un brouillon (POST) : statut brouillon → soumis */
    public function articleSubmitDraft(array $params = []): void
    {
        requireAuthor();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/author');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['author_error'] = 'Requête invalide. Veuillez réessayer.';
            $id = (int) ($params['id'] ?? 0);
            header('Location: ' . $this->base() . '/author/article/' . $id);
            exit;
        }
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $id = (int) ($params['id'] ?? 0);
        $article = $id ? ArticleModel::getByIdForAuthor($id, $userId) : null;
        if (!$article || ($article['statut'] ?? '') !== 'brouillon') {
            header('Location: ' . $this->base() . '/author');
            exit;
        }
        if (ArticleModel::submitDraft($id, $userId)) {
            $_SESSION['author_success'] = 'submitted';
        }
        header('Location: ' . $this->base() . '/author/article/' . $id);
        exit;
    }

    public function notifications(array $params = []): void
    {
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $notifications = NotificationModel::getByUserId($userId);
        $error = $_SESSION['author_error'] ?? null;
        unset($_SESSION['author_error']);
        $this->render('notifications', [
            'notifications' => $notifications,
            'error'          => $error,
        ], 'Notifications | Espace auteur - Revue Congolaise de Théologie Protestante', 'notifications', false);
    }

    /** Marquer une notification comme lue (POST) */
    public function notificationMarkRead(array $params = []): void
    {
        requireAuth();
        if (!validate_csrf()) {
            $_SESSION['author_error'] = 'Requête invalide. Veuillez réessayer.';
            release_session();
            header('Location: ' . $this->base() . '/author/notifications');
            exit;
        }
        $id = $params['id'] ?? '';
        $user = AuthService::getUser();
        if ($id !== '') {
            NotificationModel::markAsRead($id, (int) $user['id']);
        }
        release_session();
        header('Location: ' . $this->base() . '/author/notifications');
        exit;
    }

    /** Marquer toutes les notifications comme lues (POST) */
    public function notificationsMarkAllRead(array $params = []): void
    {
        requireAuth();
        if (!validate_csrf()) {
            $_SESSION['author_error'] = 'Requête invalide. Veuillez réessayer.';
            release_session();
            header('Location: ' . $this->base() . '/author/notifications');
            exit;
        }
        $user = AuthService::getUser();
        NotificationModel::markAllAsRead((int) $user['id']);
        release_session();
        header('Location: ' . $this->base() . '/author/notifications');
        exit;
    }
}
