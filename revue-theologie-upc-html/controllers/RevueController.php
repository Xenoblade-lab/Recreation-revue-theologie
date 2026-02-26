<?php
namespace Controllers;

use Models\ArticleModel;
use Models\RevueModel;
use Models\RevuePartModel;
use Models\VolumeModel;
use Models\RevueInfoModel;
use Models\NewsletterModel;

/**
 * Contrôleur des pages publiques de la revue.
 */
class RevueController
{
    private function render(string $viewName, array $data = [], ?string $title = null): void
    {
        release_session();
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        extract($data);
        ob_start();
        require BASE_PATH . '/views/public/' . $viewName . '.php';
        $viewContent = ob_get_clean();
        if ($title !== null) {
            $pageTitle = $title;
        }
        require BASE_PATH . '/views/layouts/main.php';
    }

    public function index(array $params = []): void
    {
        $articles = ArticleModel::getLatest(6);
        $numeros = RevueModel::getLatest(5);
        $totalArticles = ArticleModel::countPublished();
        $volumes = VolumeModel::getAll();
        $totalVolumes = count($volumes);
        $firstYear = null;
        foreach ($volumes as $v) {
            $y = (int) ($v['annee'] ?? 0);
            if ($y && ($firstYear === null || $y < $firstYear)) {
                $firstYear = $y;
            }
        }
        $yearsPublication = $firstYear ? (int) date('Y') - $firstYear + 1 : 28;
        $this->render('index', [
            'articles'         => $articles,
            'numeros'          => $numeros,
            'stats'            => [
                'totalArticles' => $totalArticles,
                'totalVolumes'  => $totalVolumes,
                'yearsPublication' => $yearsPublication,
            ],
            'base'             => defined('BASE_URL') ? rtrim(BASE_URL, '/') : '',
        ], 'Revue Congolaise de Théologie Protestante | UPC');
    }

    public function newsletterSubmit(array $params = []): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '') . '/');
            exit;
        }
        $email = trim($_POST['email'] ?? '');
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        if ($email !== '' && NewsletterModel::subscribe($email)) {
            $_SESSION['newsletter_success'] = true;
        } else {
            $_SESSION['newsletter_error'] = true;
        }
        release_session();
        header('Location: ' . $base . '/?newsletter=1');
        exit;
    }

    public function publications(array $params = []): void
    {
        $articles = ArticleModel::getPublished(100);
        $this->render('publications', [
            'articles'             => $articles,
            'base'                 => defined('BASE_URL') ? rtrim(BASE_URL, '/') : '',
            'canAccessFullArticle' => $this->canUserAccessFullArticle(),
        ], 'Publications | Revue Congolaise de Théologie Protestante - UPC');
    }

    public function archives(array $params = []): void
    {
        $volumes = VolumeModel::getAll();
        $revuesByVolume = [];
        foreach ($volumes as $v) {
            $revuesByVolume[$v['id']] = RevueModel::getAll((int) $v['id'], 50);
        }
        $this->render('archives', [
            'volumes'         => $volumes,
            'revuesByVolume'  => $revuesByVolume,
            'base'            => defined('BASE_URL') ? rtrim(BASE_URL, '/') : '',
        ], 'Archives | Revue Congolaise de Théologie Protestante - UPC');
    }

    public function articleDetails(array $params = []): void
    {
        $id = (int) ($params['id'] ?? 0);
        $article = $id ? ArticleModel::getById($id) : null;
        if (!$article) {
            release_session();
            http_response_code(404);
            $viewContent = '<div class="container section"><h1>Article introuvable</h1></div>';
            require BASE_PATH . '/views/layouts/main.php';
            return;
        }
        $canAccessFullArticle = $this->canUserAccessFullArticle();
        $this->render('article-details', [
            'article'              => $article,
            'base'                 => defined('BASE_URL') ? rtrim(BASE_URL, '/') : '',
            'canAccessFullArticle' => $canAccessFullArticle,
        ], htmlspecialchars($article['titre']) . ' | Revue Congolaise de Théologie Protestante');
    }

    /** Utilisateur connecté avec droit de lire l'article en entier et de télécharger le PDF (auteur ou admin ou abonnement actif). */
    private function canUserAccessFullArticle(): bool
    {
        if (!class_exists('Service\AuthService') || !\Service\AuthService::isLoggedIn()) {
            return false;
        }
        $user = \Service\AuthService::getUser();
        $userId = (int) ($user['id'] ?? 0);
        if (\Service\AuthService::hasRole('admin') || \Service\AuthService::hasRole('auteur')) {
            return true;
        }
        return $userId && class_exists('Models\AbonnementModel') && \Models\AbonnementModel::hasActiveSubscription($userId);
    }

    public function numeroDetails(array $params = []): void
    {
        $id = (int) ($params['id'] ?? 0);
        $revue = $id ? RevueModel::getById($id) : null;
        if (!$revue) {
            release_session();
            http_response_code(404);
            $viewContent = '<div class="container section"><h1>Numéro introuvable</h1></div>';
            require BASE_PATH . '/views/layouts/main.php';
            return;
        }
        $parts = RevuePartModel::getByRevueId($id);
        $volume = $revue['volume_id'] ? VolumeModel::getById((int) $revue['volume_id']) : null;
        $articlesNumero = !empty($revue['issue_id']) ? \Models\ArticleModel::getByIssueId((int) $revue['issue_id']) : [];
        $this->render('numero-details', [
            'revue'           => $revue,
            'parts'           => $parts,
            'volume'          => $volume,
            'articlesNumero'  => $articlesNumero,
            'base'            => defined('BASE_URL') ? rtrim(BASE_URL, '/') : '',
        ], 'Numéro ' . htmlspecialchars($revue['numero']) . ' | Revue Congolaise de Théologie Protestante');
    }

    public function presentation(array $params = []): void
    {
        $revueInfo = RevueInfoModel::get();
        $this->render('presentation', [
            'revueInfo' => $revueInfo,
            'base'      => defined('BASE_URL') ? rtrim(BASE_URL, '/') : '',
        ], 'Présentation | Revue Congolaise de Théologie Protestante - UPC');
    }

    public function comite(array $params = []): void
    {
        $revueInfo = RevueInfoModel::get();
        $this->render('comite', [
            'revueInfo' => $revueInfo,
            'base'      => defined('BASE_URL') ? rtrim(BASE_URL, '/') : '',
        ], 'Comité éditorial | Revue Congolaise de Théologie Protestante - UPC');
    }

    public function contact(array $params = []): void
    {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $this->render('contact', ['base' => $base], 'Contact | Revue Congolaise de Théologie Protestante - UPC');
    }

    public function faq(array $params = []): void
    {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $this->render('faq', ['base' => $base], 'FAQ | Revue Congolaise de Théologie Protestante - UPC');
    }

    public function politiqueEditoriale(array $params = []): void
    {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $this->render('politique-editoriale', ['base' => $base], 'Politique éditoriale | Revue Congolaise de Théologie Protestante');
    }

    public function instructionsAuteurs(array $params = []): void
    {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $this->render('instructions-auteurs', ['base' => $base], 'Instructions aux auteurs | Revue Congolaise de Théologie Protestante');
    }

    /**
     * Téléchargement des modèles (Word / LaTeX). Fichiers servis depuis public/templates/.
     */
    public function downloadTemplate(array $params = []): void
    {
        release_session();
        $allowed = ['template.docx', 'template.tex'];
        $file = isset($params['file']) ? basename($params['file']) : '';
        if (!in_array($file, $allowed, true)) {
            http_response_code(404);
            echo '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>Fichier non disponible</title></head><body style="font-family:sans-serif;max-width:560px;margin:2rem auto;padding:1rem;"><h1>Fichier non disponible</h1><p>Ce modèle n’existe pas.</p></body></html>';
            return;
        }
        $path = BASE_PATH . '/public/templates/' . $file;
        if (!is_file($path)) {
            http_response_code(404);
            echo '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>Fichier non disponible</title></head><body style="font-family:sans-serif;max-width:560px;margin:2rem auto;padding:1rem;"><h1>Fichier non disponible</h1><p>Le fichier ' . htmlspecialchars($file) . ' n’est pas encore disponible sur le site.</p></body></html>';
            return;
        }
        $mimes = [
            'template.docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'template.tex'  => 'application/x-tex',
        ];
        header('Content-Type: ' . ($mimes[$file] ?? 'application/octet-stream'));
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    public function actualites(array $params = []): void
    {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $this->render('actualites', ['base' => $base], 'Actualités | Revue Congolaise de Théologie Protestante');
    }

    public function mentionsLegales(array $params = []): void
    {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $this->render('mentions-legales', ['base' => $base], 'Mentions légales | Revue Congolaise de Théologie Protestante');
    }

    public function conditionsUtilisation(array $params = []): void
    {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $this->render('conditions', ['base' => $base], (function_exists('__') ? __('legal.conditions_title') : 'Conditions d\'utilisation') . ' | Revue Congolaise de Théologie Protestante');
    }

    public function confidentialite(array $params = []): void
    {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $this->render('confidentialite', ['base' => $base], (function_exists('__') ? __('legal.confidentiality_title') : 'Politique de confidentialité') . ' | Revue Congolaise de Théologie Protestante');
    }

    /** Recherche : articles et numéros par mot-clé (GET /search?q=...) */
    public function search(array $params = []): void
    {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $q = trim($_GET['q'] ?? '');
        $articles = [];
        $numeros = [];
        if ($q !== '') {
            $articles = ArticleModel::search($q, 30);
            $numeros = RevueModel::search($q, 20);
        }
        $this->render('search', [
            'q'                    => $q,
            'articles'             => $articles,
            'numeros'               => $numeros,
            'base'                  => $base,
            'canAccessFullArticle'  => $this->canUserAccessFullArticle(),
        ], 'Recherche' . ($q !== '' ? ' : ' . $q : '') . ' | Revue Congolaise de Théologie Protestante');
    }

    /** Téléchargement PDF d'un article (contrôle d'accès : publié ou auteur/admin) */
    public function downloadArticle(array $params = []): void
    {
        $id = (int) ($params['id'] ?? 0);
        $article = $id ? ArticleModel::getById($id) : null;
        if (!$article || empty($article['fichier_path'])) {
            http_response_code(404);
            header('Content-Type: text/plain; charset=utf-8');
            echo 'Article ou fichier introuvable.';
            return;
        }
        $path = $article['fichier_path'];
        $path = ltrim(str_replace('\\', '/', $path), '/');
        // Essayer plusieurs emplacements possibles
        $candidates = [
            BASE_PATH . '/public/' . $path,
        ];
        if (strpos($path, 'articles/') !== false || strpos($path, 'uploads/') !== false) {
            $candidates[] = BASE_PATH . '/public/uploads/articles/' . basename($path);
        }
        if (strpos($path, 'articles/') === 0) {
            $candidates[] = BASE_PATH . '/public/articles/' . basename($path);
        }
        $fullPath = null;
        foreach ($candidates as $c) {
            if (is_file($c)) {
                $fullPath = $c;
                break;
            }
        }
        if ($fullPath === null) {
            $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
            $articleUrl = $base . '/article/' . $id;
            http_response_code(404);
            header('Content-Type: text/html; charset=utf-8');
            $nomFichier = htmlspecialchars(basename($path));
            echo '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>PDF non disponible</title></head><body style="font-family:sans-serif;max-width:560px;margin:2rem auto;padding:1rem;">';
            echo '<h1>PDF non disponible</h1>';
            echo '<p>Le fichier <strong>' . $nomFichier . '</strong> n’est pas présent sur le serveur.</p>';
            echo '<p>Pour activer le téléchargement, placez ce fichier dans le dossier <code>public/uploads/articles/</code> du projet.</p>';
            echo '<p><a href="' . htmlspecialchars($articleUrl) . '">← Retour à l’article</a></p>';
            echo '</body></html>';
            return;
        }
        $statut = $article['statut'] ?? '';
        $allowed = false;
        if ($statut === 'valide') {
            if (class_exists('Service\AuthService') && \Service\AuthService::isLoggedIn()) {
                $user = \Service\AuthService::getUser();
                $userId = (int) ($user['id'] ?? 0);
                $allowed = \Service\AuthService::hasRole('admin')
                    || \Service\AuthService::hasRole('auteur')
                    || $userId === (int) ($article['auteur_id'] ?? 0)
                    || (class_exists('Models\AbonnementModel') && \Models\AbonnementModel::hasActiveSubscription($userId));
            }
        } else {
            if (class_exists('Service\AuthService') && \Service\AuthService::isLoggedIn()) {
                $user = \Service\AuthService::getUser();
                $userId = (int) ($user['id'] ?? 0);
                $allowed = ($userId === (int) ($article['auteur_id'] ?? 0)) || \Service\AuthService::hasRole('admin');
            }
        }
        if (!$allowed) {
            http_response_code(403);
            header('Content-Type: text/plain; charset=utf-8');
            echo 'Accès non autorisé à ce fichier.';
            return;
        }
        $filename = !empty($article['fichier_nom_original']) ? basename($article['fichier_nom_original']) : basename($path);
        $filename = preg_replace('/[^\w\.\-]/', '_', $filename) ?: 'article.pdf';
        $inline = isset($_GET['inline']) && $_GET['inline'] === '1';
        header('Content-Type: application/pdf');
        header('Content-Disposition: ' . ($inline ? 'inline' : 'attachment') . '; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
        exit;
    }
}
