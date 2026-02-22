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
        ], 'Revue de la Faculté de Théologie | UPC');
    }

    public function publications(array $params = []): void
    {
        $articles = ArticleModel::getPublished(100);
        $this->render('publications', [
            'articles' => $articles,
            'base'     => defined('BASE_URL') ? rtrim(BASE_URL, '/') : '',
        ], 'Publications | Revue de la Faculté de Théologie - UPC');
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
        ], 'Archives | Revue de la Faculté de Théologie - UPC');
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
        ], htmlspecialchars($article['titre']) . ' | Revue UPC');
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
        ], 'Numéro ' . htmlspecialchars($revue['numero']) . ' | Revue UPC');
    }

    public function presentation(array $params = []): void
    {
        $revueInfo = RevueInfoModel::get();
        $this->render('presentation', [
            'revueInfo' => $revueInfo,
            'base'      => defined('BASE_URL') ? rtrim(BASE_URL, '/') : '',
        ], 'Présentation | Revue de la Faculté de Théologie - UPC');
    }

    public function comite(array $params = []): void
    {
        $revueInfo = RevueInfoModel::get();
        $this->render('comite', [
            'revueInfo' => $revueInfo,
            'base'      => defined('BASE_URL') ? rtrim(BASE_URL, '/') : '',
        ], 'Comité éditorial | Revue de la Faculté de Théologie - UPC');
    }

    public function contact(array $params = []): void
    {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $this->render('contact', ['base' => $base], 'Contact | Revue de la Faculté de Théologie - UPC');
    }

    public function faq(array $params = []): void
    {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $this->render('faq', ['base' => $base], 'FAQ | Revue de la Faculté de Théologie - UPC');
    }

    public function politiqueEditoriale(array $params = []): void
    {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $this->render('politique-editoriale', ['base' => $base], 'Politique éditoriale | Revue UPC');
    }

    public function instructionsAuteurs(array $params = []): void
    {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $this->render('instructions-auteurs', ['base' => $base], 'Instructions aux auteurs | Revue UPC');
    }

    public function actualites(array $params = []): void
    {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $this->render('actualites', ['base' => $base], 'Actualités | Revue UPC');
    }

    public function mentionsLegales(array $params = []): void
    {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $this->render('mentions-legales', ['base' => $base], 'Mentions légales | Revue UPC');
    }
}
