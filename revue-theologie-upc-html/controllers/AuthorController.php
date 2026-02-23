<?php
namespace Controllers;

use Service\AuthService;
use Models\ArticleModel;
use Models\AbonnementModel;
use Models\PaiementModel;
use Models\NotificationModel;

/**
 * Contrôleur espace auteur (dashboard, abonnement, soumission, articles, notifications).
 */
class AuthorController
{
    private function base(): string
    {
        return defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
    }

    private function render(string $viewName, array $data = [], ?string $pageTitle = null, string $authorPage = ''): void
    {
        requireAuthor();
        $_SESSION['author_page'] = $authorPage;
        $base = $this->base();
        $user = AuthService::getUser();
        $data['base'] = $base;
        $data['currentUser'] = $user;
        extract($data);
        ob_start();
        require BASE_PATH . '/views/author/' . $viewName . '.php';
        $viewContent = ob_get_clean();
        $pageTitle = $pageTitle ?? 'Espace auteur | Revue UPC';
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
            'soumis'   => ArticleModel::countByAuthorIdAndStatut($userId, 'soumis'),
            'valide'   => ArticleModel::countByAuthorIdAndStatut($userId, 'valide'),
            'rejete'   => ArticleModel::countByAuthorIdAndStatut($userId, 'rejete'),
        ];
        $this->render('index', [
            'articles'   => $articles,
            'abonnement' => $abonnement,
            'stats'      => $stats,
        ], 'Tableau de bord auteur | Revue UPC', 'index');
    }

    public function abonnement(array $params = []): void
    {
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $abonnements = AbonnementModel::getByUserId($userId);
        $paiements = PaiementModel::getByUserId($userId);
        $abonnementActif = AbonnementModel::getActiveByUserId($userId);
        $this->render('abonnement', [
            'abonnements'     => $abonnements,
            'paiements'       => $paiements,
            'abonnementActif' => $abonnementActif,
        ], 'Mon abonnement | Espace auteur - Revue UPC', 'abonnement');
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
        ], 'Soumettre un article | Revue UPC', 'soumettre');
        unset($_SESSION['author_error'], $_SESSION['author_old']);
    }

    public function soumettre(array $params = []): void
    {
        requireAuthor();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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

        $id = ArticleModel::create($userId, $titre, $contenu, $fichierPath, $fichierNomOriginal);
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
            $pageTitle = 'Article | Revue UPC';
            require BASE_PATH . '/views/layouts/author-dashboard.php';
            return;
        }
        $_SESSION['author_page'] = '';
        $this->render('article-detail', ['article' => $article], htmlspecialchars($article['titre']) . ' | Mes articles', '');
    }

    public function articleEdit(array $params = []): void
    {
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $id = (int) ($params['id'] ?? 0);
        $article = $id ? ArticleModel::getByIdForAuthor($id, $userId) : null;
        if (!$article || $article['statut'] !== 'soumis') {
            header('Location: ' . $this->base() . '/author');
            exit;
        }
        $error = $_SESSION['author_error'] ?? null;
        unset($_SESSION['author_error']);
        $this->render('article-edit', [
            'article' => $article,
            'error'   => $error,
        ], 'Modifier l\'article | Revue UPC', '');
    }

    public function articleUpdate(array $params = []): void
    {
        requireAuthor();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/author');
            exit;
        }
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $id = (int) ($params['id'] ?? 0);
        $article = $id ? ArticleModel::getByIdForAuthor($id, $userId) : null;
        if (!$article || $article['statut'] !== 'soumis') {
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
        header('Location: ' . $this->base() . '/author/article/' . $id);
        exit;
    }

    public function notifications(array $params = []): void
    {
        $user = AuthService::getUser();
        $userId = (int) $user['id'];
        $notifications = NotificationModel::getByUserId($userId);
        $this->render('notifications', [
            'notifications' => $notifications,
        ], 'Notifications | Espace auteur - Revue UPC', 'notifications');
    }

    /** Marquer une notification comme lue (POST) */
    public function notificationMarkRead(array $params = []): void
    {
        requireAuth();
        $id = $params['id'] ?? '';
        $user = AuthService::getUser();
        if ($id !== '') {
            NotificationModel::markAsRead($id, (int) $user['id']);
        }
        header('Location: ' . $this->base() . '/author/notifications');
        exit;
    }

    /** Marquer toutes les notifications comme lues (POST) */
    public function notificationsMarkAllRead(array $params = []): void
    {
        requireAuth();
        $user = AuthService::getUser();
        NotificationModel::markAllAsRead((int) $user['id']);
        header('Location: ' . $this->base() . '/author/notifications');
        exit;
    }
}
