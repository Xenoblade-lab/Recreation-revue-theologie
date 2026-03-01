<?php
namespace Controllers;

use Service\AuthService;
use Models\ArticleModel;
use Models\UserModel;
use Models\PaiementModel;
use Models\EvaluationModel;
use Models\VolumeModel;
use Models\RevueModel;
use Models\RevueInfoModel;
use Models\NotificationModel;
use Models\AbonnementModel;
use Models\ComiteEditorialModel;

/**
 * Contrôleur administration : dashboard, utilisateurs, articles, paiements, volumes, paramètres.
 */
class AdminController
{
    private function base(): string
    {
        return defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
    }

    private function render(string $viewName, array $data = [], ?string $pageTitle = null, string $adminPage = ''): void
    {
        requireAdmin();
        $_SESSION['admin_page'] = $adminPage;
        $base = $this->base();
        $user = AuthService::getUser();
        $data['base'] = $base;
        $data['currentUser'] = $user;
        extract($data);
        ob_start();
        require BASE_PATH . '/views/admin/' . $viewName . '.php';
        $viewContent = ob_get_clean();
        release_session(); // après rendu de la vue pour que csrf_field() ait enregistré le jeton en session
        $pageTitle = $pageTitle ?? 'Administration | Revue Congolaise de Théologie Protestante';
        require BASE_PATH . '/views/layouts/admin-dashboard.php';
    }

    public function index(array $params = []): void
    {
        $totalArticles = ArticleModel::countAll();
        $publishedArticles = ArticleModel::countPublished();
        $reviewersCount = UserModel::countByRole('redacteur') + UserModel::countByRole('redacteur en chef');
        $monthlyRevenue = PaiementModel::getMonthlyTotal();
        $lastSubmissions = ArticleModel::getAllForAdmin(10);
        $this->render('index', [
            'totalArticles' => $totalArticles,
            'publishedArticles' => $publishedArticles,
            'reviewersCount' => $reviewersCount,
            'monthlyRevenue' => $monthlyRevenue,
            'lastSubmissions' => $lastSubmissions,
        ], 'Tableau de bord | Administration - Revue Congolaise de Théologie Protestante', 'index');
    }

    public function users(array $params = []): void
    {
        $users = UserModel::getAll(100);
        $this->render('users', ['users' => $users], 'Utilisateurs | Administration', 'users');
    }

    public function userCreate(array $params = []): void
    {
        $error = $_SESSION['admin_error'] ?? null;
        $old = $_SESSION['admin_old'] ?? [];
        unset($_SESSION['admin_error'], $_SESSION['admin_old']);
        $this->render('user-form', ['user' => null, 'error' => $error, 'old' => $old], 'Créer un utilisateur | Administration', 'users');
    }

    public function userStore(array $params = []): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/admin/users');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['admin_error'] = 'Requête invalide. Veuillez réessayer.';
            release_session();
            header('Location: ' . $this->base() . '/admin/users/create');
            exit;
        }
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';
        $_SESSION['admin_old'] = ['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'role' => $role];
        if (!$nom || !$prenom || !$email || strlen($password) < 6) {
            $_SESSION['admin_error'] = 'Tous les champs sont obligatoires ; le mot de passe doit faire au moins 6 caractères.';
            header('Location: ' . $this->base() . '/admin/users/create');
            exit;
        }
        if (UserModel::emailExists($email)) {
            $_SESSION['admin_error'] = 'Cet email est déjà utilisé.';
            header('Location: ' . $this->base() . '/admin/users/create');
            exit;
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $id = UserModel::create($nom, $prenom, $email, $hash, $role);
        if ($id) {
            unset($_SESSION['admin_old']);
            header('Location: ' . $this->base() . '/admin/users');
            exit;
        }
        $_SESSION['admin_error'] = 'Erreur lors de la création.';
        header('Location: ' . $this->base() . '/admin/users/create');
        exit;
    }

    public function userEdit(array $params = []): void
    {
        $id = (int) ($params['id'] ?? 0);
        $user = $id ? UserModel::getById($id) : null;
        if (!$user) {
            header('Location: ' . $this->base() . '/admin/users');
            exit;
        }
        $error = $_SESSION['admin_error'] ?? null;
        unset($_SESSION['admin_error']);
        $this->render('user-form', ['user' => $user, 'error' => $error, 'old' => []], 'Modifier l\'utilisateur | Administration', 'users');
    }

    public function userUpdate(array $params = []): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/admin/users');
            exit;
        }
        if (!validate_csrf()) {
            $id = (int) ($params['id'] ?? 0);
            $_SESSION['admin_error'] = 'Requête invalide. Veuillez réessayer.';
            release_session();
            header('Location: ' . $this->base() . '/admin/users/' . $id . '/edit');
            exit;
        }
        $id = (int) ($params['id'] ?? 0);
        $user = $id ? UserModel::getById($id) : null;
        if (!$user) {
            header('Location: ' . $this->base() . '/admin/users');
            exit;
        }
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'user';
        $statut = $_POST['statut'] ?? 'actif';
        $newPassword = $_POST['password'] ?? '';
        if (!$nom || !$prenom || !$email) {
            $_SESSION['admin_error'] = 'Nom, prénom et email sont obligatoires.';
            header('Location: ' . $this->base() . '/admin/users/' . $id . '/edit');
            exit;
        }
        if (UserModel::emailExists($email, $id)) {
            $_SESSION['admin_error'] = 'Cet email est déjà utilisé.';
            header('Location: ' . $this->base() . '/admin/users/' . $id . '/edit');
            exit;
        }
        $hash = strlen($newPassword) >= 6 ? password_hash($newPassword, PASSWORD_DEFAULT) : null;
        UserModel::update($id, $nom, $prenom, $email, $role, $statut, $hash);
        header('Location: ' . $this->base() . '/admin/users');
        exit;
    }

    public function articles(array $params = []): void
    {
        $articles = ArticleModel::getAllForAdmin(50);
        $this->render('articles', ['articles' => $articles], 'Articles | Administration', 'articles');
    }

    public function evaluations(array $params = []): void
    {
        $statut = isset($_GET['statut']) && $_GET['statut'] !== '' ? (string) $_GET['statut'] : null;
        $evaluations = EvaluationModel::getAllForAdmin($statut, 100, 0);
        $total = EvaluationModel::countAllForAdmin($statut);
        $this->render('evaluations', [
            'evaluations' => $evaluations,
            'total' => $total,
            'filterStatut' => $statut,
        ], 'Évaluations | Administration', 'evaluations');
    }

    public function articleDetail(array $params = []): void
    {
        $id = (int) ($params['id'] ?? 0);
        $article = $id ? ArticleModel::getById($id) : null;
        if (!$article) {
            header('Location: ' . $this->base() . '/admin/articles');
            exit;
        }
        $evaluations = EvaluationModel::getByArticleId($id);
        $conflictingRecommendations = false;
        $conflictingFavorable = 0;
        $conflictingUnfavorable = 0;
        $terminated = array_filter($evaluations, function ($e) {
            return ($e['statut'] ?? '') === 'termine';
        });
        if (count($terminated) >= 2) {
            foreach ($terminated as $e) {
                $rec = $e['recommendation'] ?? '';
                if (in_array($rec, ['accepte', 'accepte_avec_modifications', 'revision_mineure'], true)) {
                    $conflictingFavorable++;
                } elseif (in_array($rec, ['rejete', 'revision_majeure'], true)) {
                    $conflictingUnfavorable++;
                }
            }
            if ($conflictingFavorable >= 1 && $conflictingUnfavorable >= 1) {
                $conflictingRecommendations = true;
            }
        }
        if (ComiteEditorialModel::tableExists()) {
            $reviewers = ComiteEditorialModel::getActiveReviewers();
        } else {
            $reviewers = UserModel::getAll(200);
            $reviewers = array_filter($reviewers, function ($u) {
                return in_array($u['role'] ?? '', ['redacteur', 'redacteur en chef'], true);
            });
        }
        $volumes = VolumeModel::getAll();
        $revues = RevueModel::getAll(null, 200);
        $error = $_SESSION['admin_error'] ?? null;
        $assignSuccessCount = isset($_SESSION['admin_success']) ? (int) $_SESSION['admin_success'] : null;
        unset($_SESSION['admin_error'], $_SESSION['admin_success']);
        $this->render('article-detail', [
            'article' => $article,
            'evaluations' => $evaluations,
            'reviewers' => $reviewers,
            'volumes' => $volumes,
            'revues' => $revues,
            'error' => $error,
            'assignSuccessCount' => $assignSuccessCount,
            'conflictingRecommendations' => $conflictingRecommendations,
            'conflictingFavorable' => $conflictingFavorable,
            'conflictingUnfavorable' => $conflictingUnfavorable,
        ], 'Article #' . $id . ' | Administration', 'articles');
    }

    public function articleUpdateStatut(array $params = []): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/admin/articles');
            exit;
        }
        if (!validate_csrf()) {
            $id = (int) ($params['id'] ?? 0);
            $_SESSION['admin_error'] = 'Requête invalide. Veuillez réessayer.';
            release_session();
            header('Location: ' . $this->base() . '/admin/article/' . $id);
            exit;
        }
        $id = (int) ($params['id'] ?? 0);
        $statut = $_POST['statut'] ?? '';
        if (!in_array($statut, ['soumis', 'valide', 'rejete'], true)) {
            header('Location: ' . $this->base() . '/admin/article/' . $id);
            exit;
        }
        if (in_array($statut, ['valide', 'rejete'], true)) {
            $evaluations = \Models\EvaluationModel::getByArticleId($id);
            if (count($evaluations) < 2) {
                $_SESSION['admin_error'] = function_exists('__') ? __('admin.cannot_publish_reject_min_two') : 'Veuillez assigner au moins 2 évaluateurs et attendre leurs rapports avant de publier ou rejeter.';
                release_session();
                header('Location: ' . $this->base() . '/admin/article/' . $id);
                exit;
            }
        }
        ArticleModel::updateStatut($id, $statut);
        $article = ArticleModel::getById($id);
        if ($article && !empty($article['auteur_id'])) {
            $msg = 'Le statut de votre article a été mis à jour.';
            $statutLabels = ['soumis' => 'Soumis', 'valide' => 'Publié', 'rejete' => 'Rejeté'];
            $label = $statutLabels[$statut] ?? $statut;
            NotificationModel::create((int) $article['auteur_id'], 'ArticleStatusChanged', [
                'message' => 'Votre article « ' . ($article['titre'] ?? '') . ' » : ' . $label . '.',
                'link' => 'author/article/' . $id,
            ]);
        }
        header('Location: ' . $this->base() . '/admin/article/' . $id);
        exit;
    }

    public function articleAssign(array $params = []): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/admin/articles');
            exit;
        }
        if (!validate_csrf()) {
            $id = (int) ($params['id'] ?? 0);
            $_SESSION['admin_error'] = 'Requête invalide. Veuillez réessayer.';
            release_session();
            header('Location: ' . $this->base() . '/admin/article/' . $id);
            exit;
        }
        $id = (int) ($params['id'] ?? 0);
        $evaluateurIds = isset($_POST['evaluateur_ids']) && is_array($_POST['evaluateur_ids'])
            ? array_map('intval', array_filter($_POST['evaluateur_ids']))
            : [];
        if (!$id) {
            header('Location: ' . $this->base() . '/admin/articles');
            exit;
        }
        $assignedCount = 0;
        $article = ArticleModel::getById($id);
        $titre = $article['titre'] ?? 'Article';
        foreach (array_unique($evaluateurIds) as $evaluateurId) {
            if (!$evaluateurId) continue;
            $evalId = EvaluationModel::assign($id, $evaluateurId);
            if ($evalId) {
                $assignedCount++;
                NotificationModel::create($evaluateurId, 'EvaluationAssigned', [
                    'message' => 'Un nouvel article vous a été assigné pour évaluation : « ' . $titre . ' ».',
                    'link' => 'reviewer/evaluation/' . $evalId,
                ]);
            }
        }
        if ($assignedCount > 0) {
            $_SESSION['admin_success'] = $assignedCount;
        }
        header('Location: ' . $this->base() . '/admin/article/' . $id);
        exit;
    }

    public function articleSetIssue(array $params = []): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/admin/articles');
            exit;
        }
        if (!validate_csrf()) {
            $id = (int) ($params['id'] ?? 0);
            $_SESSION['admin_error'] = 'Requête invalide. Veuillez réessayer.';
            release_session();
            header('Location: ' . $this->base() . '/admin/article/' . $id);
            exit;
        }
        $id = (int) ($params['id'] ?? 0);
        $issueId = isset($_POST['issue_id']) && $_POST['issue_id'] !== '' ? (int) $_POST['issue_id'] : null;
        if ($id) {
            ArticleModel::setIssueId($id, $issueId);
        }
        header('Location: ' . $this->base() . '/admin/article/' . $id);
        exit;
    }

    public function paiements(array $params = []): void
    {
        $paiements = PaiementModel::getAll(50);
        $error = $_SESSION['admin_error'] ?? null;
        $success = $_SESSION['admin_success'] ?? null;
        unset($_SESSION['admin_error'], $_SESSION['admin_success']);
        $this->render('paiements', ['paiements' => $paiements, 'error' => $error, 'success' => $success], 'Paiements | Administration', 'paiements');
    }

    public function paiementStatut(array $params = []): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/admin/paiements');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['admin_error'] = 'Requête invalide. Veuillez réessayer.';
            release_session();
            header('Location: ' . $this->base() . '/admin/paiements');
            exit;
        }
        $id = (int) ($params['id'] ?? 0);
        $statut = trim($_POST['statut'] ?? '');
        if (!$id || !in_array($statut, ['valide', 'refuse'], true)) {
            header('Location: ' . $this->base() . '/admin/paiements');
            exit;
        }
        $paiement = PaiementModel::getById($id);
        if (!$paiement) {
            header('Location: ' . $this->base() . '/admin/paiements');
            exit;
        }
        $userId = (int) ($paiement['utilisateur_id'] ?? 0);
        $wasEnAttente = ($paiement['statut'] ?? '') === 'en_attente';

        if ($statut === 'valide') {
            PaiementModel::setValide($id);
            if ($wasEnAttente && $userId) {
                AbonnementModel::create($userId);
                UserModel::updateRole($userId, 'auteur');
                NotificationModel::create($userId, 'subscription_approved', [
                    'message' => __('author.subscription_approved_notif') ?: 'Votre demande d\'abonnement auteur a été validée. Vous pouvez maintenant soumettre des articles.',
                    'link' => '/author',
                ]);
            }
        } else {
            PaiementModel::updateStatut($id, 'refuse');
            if ($wasEnAttente && $userId) {
                NotificationModel::create($userId, 'subscription_refused', [
                    'message' => __('author.subscription_refused_notif') ?: 'Votre demande d\'abonnement n\'a pas été retenue. Vous pouvez réessayer en vous rendant sur S\'abonner.',
                    'link' => '/author/s-abonner',
                ]);
            }
            $_SESSION['admin_success'] = 'paiement_refuse';
        }
        header('Location: ' . $this->base() . '/admin/paiements');
        exit;
    }

    /** Valider un paiement (route dédiée POST /admin/paiement/[id]/valider) */
    public function paiementValider(array $params = []): void
    {
        $this->paiementStatutAction((int) ($params['id'] ?? 0), 'valide');
    }

    /** Refuser un paiement (route dédiée POST /admin/paiement/[id]/refuser) */
    public function paiementRefuser(array $params = []): void
    {
        $this->paiementStatutAction((int) ($params['id'] ?? 0), 'refuse');
    }

    /** Traitement commun : valider ou refuser un paiement. */
    private function paiementStatutAction(int $id, string $statut): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/admin/paiements');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['admin_error'] = 'Requête invalide. Veuillez réessayer.';
            release_session();
            header('Location: ' . $this->base() . '/admin/paiements');
            exit;
        }
        if (!$id || !in_array($statut, ['valide', 'refuse'], true)) {
            header('Location: ' . $this->base() . '/admin/paiements');
            exit;
        }
        $paiement = PaiementModel::getById($id);
        if (!$paiement) {
            header('Location: ' . $this->base() . '/admin/paiements');
            exit;
        }
        $userId = (int) ($paiement['utilisateur_id'] ?? 0);
        $wasEnAttente = ($paiement['statut'] ?? '') === 'en_attente';

        if ($statut === 'valide') {
            PaiementModel::setValide($id);
            if ($wasEnAttente && $userId) {
                AbonnementModel::create($userId);
                UserModel::updateRole($userId, 'auteur');
                NotificationModel::create($userId, 'subscription_approved', [
                    'message' => __('author.subscription_approved_notif') ?: 'Votre demande d\'abonnement auteur a été validée. Vous pouvez maintenant soumettre des articles.',
                    'link' => '/author',
                ]);
            }
        } else {
            PaiementModel::updateStatut($id, 'refuse');
            if ($wasEnAttente && $userId) {
                NotificationModel::create($userId, 'subscription_refused', [
                    'message' => __('author.subscription_refused_notif') ?: 'Votre demande d\'abonnement n\'a pas été retenue. Vous pouvez réessayer en vous rendant sur S\'abonner.',
                    'link' => '/author/s-abonner',
                ]);
            }
            $_SESSION['admin_success'] = 'paiement_refuse';
        }
        header('Location: ' . $this->base() . '/admin/paiements');
        exit;
    }

    public function notifications(array $params = []): void
    {
        $user = AuthService::getUser();
        $notifications = NotificationModel::getByUserId((int) $user['id']);
        $error = $_SESSION['admin_error'] ?? null;
        unset($_SESSION['admin_error']);
        $this->render('notifications', [
            'notifications' => $notifications,
            'error'         => $error,
        ], 'Notifications | Administration - Revue Congolaise de Théologie Protestante', 'notifications');
    }

    public function notificationMarkRead(array $params = []): void
    {
        requireAdmin();
        if (!validate_csrf()) {
            $_SESSION['admin_error'] = 'Requête invalide. Veuillez réessayer.';
            release_session();
            header('Location: ' . $this->base() . '/admin/notifications');
            exit;
        }
        $id = $params['id'] ?? '';
        $user = AuthService::getUser();
        if ($id !== '') {
            NotificationModel::markAsRead($id, (int) $user['id']);
        }
        release_session();
        header('Location: ' . $this->base() . '/admin/notifications');
        exit;
    }

    public function notificationsMarkAllRead(array $params = []): void
    {
        requireAdmin();
        if (!validate_csrf()) {
            $_SESSION['admin_error'] = 'Requête invalide. Veuillez réessayer.';
            release_session();
            header('Location: ' . $this->base() . '/admin/notifications');
            exit;
        }
        $user = AuthService::getUser();
        NotificationModel::markAllAsRead((int) $user['id']);
        release_session();
        header('Location: ' . $this->base() . '/admin/notifications');
        exit;
    }

    public function volumes(array $params = []): void
    {
        $volumes = VolumeModel::getAll();
        $revuesByVolume = [];
        foreach ($volumes as $v) {
            $revuesByVolume[$v['id']] = RevueModel::getAll((int) $v['id'], 50);
        }
        $this->render('volumes', ['volumes' => $volumes, 'revuesByVolume' => $revuesByVolume], 'Volumes & Numéros | Administration', 'volumes');
    }

    public function volumeDetail(array $params = []): void
    {
        $id = (int) ($params['id'] ?? 0);
        $volume = $id ? VolumeModel::getById($id) : null;
        if (!$volume) {
            header('Location: ' . $this->base() . '/admin/volumes');
            exit;
        }
        $revues = RevueModel::getAll($id, 50);
        $error = $_SESSION['admin_error'] ?? null;
        unset($_SESSION['admin_error']);
        $this->render('volume-detail', ['volume' => $volume, 'revues' => $revues, 'error' => $error], 'Volume ' . $id . ' | Administration', 'volumes');
    }

    public function volumeUpdate(array $params = []): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/admin/volumes');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['admin_error'] = 'Requête invalide.';
            header('Location: ' . $this->base() . '/admin/volumes');
            exit;
        }
        $id = (int) ($params['id'] ?? 0);
        $volume = $id ? VolumeModel::getById($id) : null;
        if (!$volume) {
            header('Location: ' . $this->base() . '/admin/volumes');
            exit;
        }
        $annee = (int) ($_POST['annee'] ?? 0);
        $numeroVolume = trim($_POST['numero_volume'] ?? '');
        $description = trim($_POST['description'] ?? '') ?: null;
        $redacteurChef = trim($_POST['redacteur_chef'] ?? '') ?: null;
        if ($annee < 1900 || $annee > 2100) {
            $_SESSION['admin_error'] = 'Année invalide.';
            release_session();
            header('Location: ' . $this->base() . '/admin/volume/' . $id);
            exit;
        }
        VolumeModel::update($id, $annee, $numeroVolume, $description, $redacteurChef);
        release_session();
        header('Location: ' . $this->base() . '/admin/volume/' . $id);
        exit;
    }

    public function numeroDetail(array $params = []): void
    {
        $id = (int) ($params['id'] ?? 0);
        $numero = $id ? RevueModel::getById($id) : null;
        if (!$numero) {
            header('Location: ' . $this->base() . '/admin/volumes');
            exit;
        }
        $volume = !empty($numero['volume_id']) ? VolumeModel::getById((int) $numero['volume_id']) : null;
        $error = $_SESSION['admin_error'] ?? null;
        unset($_SESSION['admin_error']);
        $this->render('numero-detail', ['numero' => $numero, 'volume' => $volume, 'error' => $error], 'Numéro ' . ($numero['numero'] ?? '') . ' | Administration', 'volumes');
    }

    public function numeroUpdate(array $params = []): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/admin/volumes');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['admin_error'] = 'Requête invalide.';
            header('Location: ' . $this->base() . '/admin/volumes');
            exit;
        }
        $id = (int) ($params['id'] ?? 0);
        $numero = $id ? RevueModel::getById($id) : null;
        if (!$numero) {
            header('Location: ' . $this->base() . '/admin/volumes');
            exit;
        }
        $numeroVal = trim($_POST['numero'] ?? '');
        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '') ?: null;
        $datePublication = trim($_POST['date_publication'] ?? '') ?: null;
        if ($numeroVal === '' || $titre === '') {
            $_SESSION['admin_error'] = 'Numéro et titre obligatoires.';
            release_session();
            header('Location: ' . $this->base() . '/admin/numero/' . $id);
            exit;
        }
        RevueModel::update($id, $numeroVal, $titre, $description, $datePublication);
        release_session();
        header('Location: ' . $this->base() . '/admin/numero/' . $id);
        exit;
    }

    public function parametres(array $params = []): void
    {
        $info = RevueInfoModel::get();
        $success = !empty($_SESSION['admin_success']);
        $error = $_SESSION['admin_error'] ?? null;
        unset($_SESSION['admin_success'], $_SESSION['admin_error']);
        $this->render('parametres', ['info' => $info, 'success' => $success, 'error' => $error], 'Paramètres de la revue | Administration', 'parametres');
    }

    public function parametresUpdate(array $params = []): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/admin/parametres');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['admin_error'] = 'Requête invalide. Veuillez réessayer.';
            release_session();
            header('Location: ' . $this->base() . '/admin/parametres');
            exit;
        }
        $nom = trim($_POST['nom_officiel'] ?? '');
        if ($nom === '') $nom = 'Revue Congolaise de Théologie Protestante';
        RevueInfoModel::update(
            $nom,
            trim($_POST['description'] ?? '') ?: null,
            trim($_POST['ligne_editoriale'] ?? '') ?: null,
            trim($_POST['objectifs'] ?? '') ?: null,
            trim($_POST['domaines_couverts'] ?? '') ?: null,
            trim($_POST['issn'] ?? '') ?: null,
            trim($_POST['comite_scientifique'] ?? '') ?: null,
            trim($_POST['comite_redaction'] ?? '') ?: null
        );
        $_SESSION['admin_success'] = true;
        header('Location: ' . $this->base() . '/admin/parametres');
        exit;
    }

    public function comiteEditorialIndex(array $params = []): void
    {
        if (!ComiteEditorialModel::tableExists()) {
            $_SESSION['admin_error'] = function_exists('__') ? __('admin.comite_table_missing') : 'Table comité éditorial absente. Exécutez la migration add_comite_editorial.sql.';
            header('Location: ' . $this->base() . '/admin');
            exit;
        }
        $members = ComiteEditorialModel::getAllWithUsers();
        $error = $_SESSION['admin_error'] ?? null;
        $success = $_SESSION['admin_success'] ?? null;
        unset($_SESSION['admin_error'], $_SESSION['admin_success']);
        $this->render('comite-editorial', ['members' => $members, 'error' => $error, 'success' => $success], 'Membres du comité | Administration', 'comite-editorial');
    }

    public function comiteEditorialCreate(array $params = []): void
    {
        if (!ComiteEditorialModel::tableExists()) {
            header('Location: ' . $this->base() . '/admin/comite-editorial');
            exit;
        }
        $allReviewers = UserModel::getAll(500);
        $allReviewers = array_filter($allReviewers, function ($u) {
            return in_array($u['role'] ?? '', ['redacteur', 'redacteur en chef'], true);
        });
        $existingIds = ComiteEditorialModel::getAllUserIds();
        $candidates = array_filter($allReviewers, function ($u) use ($existingIds) {
            return !in_array((int) $u['id'], array_map('intval', $existingIds), true);
        });
        $error = $_SESSION['admin_error'] ?? null;
        $old = $_SESSION['admin_old'] ?? [];
        unset($_SESSION['admin_error'], $_SESSION['admin_old']);
        $this->render('comite-editorial-form', ['member' => null, 'candidates' => $candidates, 'error' => $error, 'old' => $old], 'Ajouter un membre | Administration', 'comite-editorial');
    }

    public function comiteEditorialStore(array $params = []): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/admin/comite-editorial');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['admin_error'] = 'Requête invalide.';
            header('Location: ' . $this->base() . '/admin/comite-editorial/create');
            exit;
        }
        $userId = (int) ($_POST['user_id'] ?? 0);
        $ordre = (int) ($_POST['ordre'] ?? 0);
        $titreAffiche = trim($_POST['titre_affiche'] ?? '') ?: null;
        $actif = isset($_POST['actif']) && $_POST['actif'] !== '0';
        if (!$userId) {
            $_SESSION['admin_error'] = function_exists('__') ? __('admin.comite_choose_user') : 'Veuillez choisir un utilisateur.';
            $_SESSION['admin_old'] = ['ordre' => $ordre, 'titre_affiche' => $titreAffiche, 'actif' => $actif];
            header('Location: ' . $this->base() . '/admin/comite-editorial/create');
            exit;
        }
        $id = ComiteEditorialModel::create($userId, $ordre, $titreAffiche, $actif);
        if ($id) {
            $_SESSION['admin_success'] = function_exists('__') ? __('admin.comite_member_added') : 'Membre ajouté.';
            header('Location: ' . $this->base() . '/admin/comite-editorial');
            exit;
        }
        $_SESSION['admin_error'] = function_exists('__') ? __('admin.comite_member_already') : 'Cet utilisateur est déjà dans le comité.';
        $_SESSION['admin_old'] = ['user_id' => $userId, 'ordre' => $ordre, 'titre_affiche' => $titreAffiche, 'actif' => $actif];
        header('Location: ' . $this->base() . '/admin/comite-editorial/create');
        exit;
    }

    public function comiteEditorialEdit(array $params = []): void
    {
        $id = (int) ($params['id'] ?? 0);
        $member = $id ? ComiteEditorialModel::getById($id) : null;
        if (!$member) {
            header('Location: ' . $this->base() . '/admin/comite-editorial');
            exit;
        }
        $error = $_SESSION['admin_error'] ?? null;
        $old = $_SESSION['admin_old'] ?? [];
        unset($_SESSION['admin_error'], $_SESSION['admin_old']);
        $this->render('comite-editorial-form', ['member' => $member, 'candidates' => [], 'error' => $error, 'old' => $old], 'Modifier le membre | Administration', 'comite-editorial');
    }

    public function comiteEditorialUpdate(array $params = []): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/admin/comite-editorial');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['admin_error'] = 'Requête invalide.';
            header('Location: ' . $this->base() . '/admin/comite-editorial');
            exit;
        }
        $id = (int) ($params['id'] ?? 0);
        $member = $id ? ComiteEditorialModel::getById($id) : null;
        if (!$member) {
            header('Location: ' . $this->base() . '/admin/comite-editorial');
            exit;
        }
        $ordre = (int) ($_POST['ordre'] ?? 0);
        $titreAffiche = trim($_POST['titre_affiche'] ?? '') ?: null;
        $actif = isset($_POST['actif']) && $_POST['actif'] !== '0';
        ComiteEditorialModel::update($id, $ordre, $titreAffiche, $actif);
        $_SESSION['admin_success'] = function_exists('__') ? __('admin.comite_member_updated') : 'Membre mis à jour.';
        header('Location: ' . $this->base() . '/admin/comite-editorial');
        exit;
    }

    public function comiteEditorialDelete(array $params = []): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !validate_csrf()) {
            header('Location: ' . $this->base() . '/admin/comite-editorial');
            exit;
        }
        $id = (int) ($params['id'] ?? 0);
        if ($id) {
            ComiteEditorialModel::delete($id);
            $_SESSION['admin_success'] = function_exists('__') ? __('admin.comite_member_removed') : 'Membre retiré du comité.';
        }
        header('Location: ' . $this->base() . '/admin/comite-editorial');
        exit;
    }
}
