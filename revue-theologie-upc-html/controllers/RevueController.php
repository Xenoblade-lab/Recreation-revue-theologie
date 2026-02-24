<?php
namespace Controllers;

use Models\ArticleModel;
use Models\RevueModel;
use Models\RevuePartModel;
use Models\VolumeModel;
use Models\RevueInfoModel;

/**
 * Contrôleur des pages publiques de la revue.
 */
class RevueController
{
    private function render(string $viewName, array $data = [], ?string $title = null): void
    {
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
        $this->render('index', [
            'articles' => $articles,
            'numeros'  => $numeros,
            'base'     => defined('BASE_URL') ? rtrim(BASE_URL, '/') : '',
        ], 'Revue Congolaise de Théologie Protestante | UPC');
    }

    public function publications(array $params = []): void
    {
        $articles = ArticleModel::getPublished(100);
        $this->render('publications', [
            'articles' => $articles,
            'base'     => defined('BASE_URL') ? rtrim(BASE_URL, '/') : '',
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
            http_response_code(404);
            $viewContent = '<div class="container section"><h1>Article introuvable</h1></div>';
            require BASE_PATH . '/views/layouts/main.php';
            return;
        }
        $this->render('article-details', [
            'article' => $article,
            'base'    => defined('BASE_URL') ? rtrim(BASE_URL, '/') : '',
        ], htmlspecialchars($article['titre']) . ' | Revue Congolaise de Théologie Protestante');
    }

    public function numeroDetails(array $params = []): void
    {
        $id = (int) ($params['id'] ?? 0);
        $revue = $id ? RevueModel::getById($id) : null;
        if (!$revue) {
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
            'q'         => $q,
            'articles' => $articles,
            'numeros'   => $numeros,
            'base'      => $base,
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
        $allowed = ($statut === 'valide');
        if (!$allowed && class_exists('Service\AuthService') && \Service\AuthService::isLoggedIn()) {
            $user = \Service\AuthService::getUser();
            $userId = (int) ($user['id'] ?? 0);
            $allowed = ($userId === (int) ($article['auteur_id'] ?? 0))
                || \Service\AuthService::hasRole('admin');
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
