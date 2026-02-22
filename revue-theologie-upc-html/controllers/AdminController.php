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
        $pageTitle = $pageTitle ?? 'Administration | Revue UPC';
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
        ], 'Tableau de bord | Administration - Revue UPC', 'index');
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

    public function articleDetail(array $params = []): void
    {
        $id = (int) ($params['id'] ?? 0);
        $article = $id ? ArticleModel::getById($id) : null;
        if (!$article) {
            header('Location: ' . $this->base() . '/admin/articles');
            exit;
        }
        $evaluations = EvaluationModel::getByArticleId($id);
        $reviewers = UserModel::getAll(200);
        $reviewers = array_filter($reviewers, function ($u) {
            return in_array($u['role'] ?? '', ['redacteur', 'redacteur en chef'], true);
        });
        $volumes = VolumeModel::getAll();
        $revues = RevueModel::getAll(null, 200);
        $this->render('article-detail', [
            'article' => $article,
            'evaluations' => $evaluations,
            'reviewers' => $reviewers,
            'volumes' => $volumes,
            'revues' => $revues,
        ], 'Article #' . $id . ' | Administration', 'articles');
    }

    public function articleUpdateStatut(array $params = []): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/admin/articles');
            exit;
        }
        $id = (int) ($params['id'] ?? 0);
        $statut = $_POST['statut'] ?? '';
        if (!in_array($statut, ['soumis', 'valide', 'rejete'], true)) {
            header('Location: ' . $this->base() . '/admin/article/' . $id);
            exit;
        }
        ArticleModel::updateStatut($id, $statut);
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
        $id = (int) ($params['id'] ?? 0);
        $evaluateurId = (int) ($_POST['evaluateur_id'] ?? 0);
        if (!$id || !$evaluateurId) {
            header('Location: ' . $this->base() . '/admin/article/' . $id);
            exit;
        }
        EvaluationModel::assign($id, $evaluateurId);
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
        $this->render('paiements', ['paiements' => $paiements], 'Paiements | Administration', 'paiements');
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

    public function parametres(array $params = []): void
    {
        $info = RevueInfoModel::get();
        $success = !empty($_SESSION['admin_success']);
        unset($_SESSION['admin_success']);
        $this->render('parametres', ['info' => $info, 'success' => $success], 'Paramètres de la revue | Administration', 'parametres');
    }

    public function parametresUpdate(array $params = []): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/admin/parametres');
            exit;
        }
        $nom = trim($_POST['nom_officiel'] ?? '');
        if ($nom === '') $nom = 'Revue de Théologie de l\'UPC';
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
}
