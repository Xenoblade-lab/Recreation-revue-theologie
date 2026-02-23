<?php
namespace Controllers;

use Models\Database;
use Models\RevueInfoModel;
use Models\VolumeModel;
use Models\IssueModel;
use Models\ArticleModel;

class RevueController {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db->connect();
    }

    /**
     * Page d'accueil avec identité de la revue
     */
    public function index() {
        $revueInfoModel = new RevueInfoModel($this->db);
        $revueInfo = $revueInfoModel->getRevueInfo();
        
        $volumeModel = new VolumeModel($this->db);
        $latestVolumes = $volumeModel->getAllVolumes(1, 5);
        $latestVolume = !empty($latestVolumes) ? $latestVolumes[0] : null;
        
        \App\App::view('index', [
            'revueInfo' => $revueInfo,
            'latestVolumes' => $latestVolumes,
            'latestVolume' => $latestVolume
        ]);
    }

    /**
     * Archives dynamiques (par année/volume/numéro)
     */
    public function archives() {
        $volumeModel = new VolumeModel($this->db);
        $volumes = $volumeModel->getAllVolumes(1, 100); // Récupérer tous les volumes
        
        // Grouper par année
        $volumesByYear = [];
        if (!empty($volumes)) {
            foreach ($volumes as $volume) {
                $year = $volume['annee'];
                if (!isset($volumesByYear[$year])) {
                    $volumesByYear[$year] = [
                        'volume' => $volume,
                        'issues' => []
                    ];
                }
                
                // Récupérer les numéros de ce volume
                $issues = $volumeModel->getVolumeIssues($volume['id']);
                $volumesByYear[$year]['issues'] = $issues;
            }
        }
        
        // Trier par année décroissante
        krsort($volumesByYear);
        
        // Réindexer pour avoir des indices numériques pour la vue
        $volumesByYearIndexed = array_values($volumesByYear);
        $years = !empty($volumesByYear) ? array_keys($volumesByYear) : [];
        
        \App\App::view('archives', [
            'volumesByYear' => $volumesByYearIndexed,
            'years' => $years
        ]);
    }

    /**
     * Page comité éditorial : comité de rédaction, comité scientifique, et comités par année (volume)
     */
    public function comite() {
        $revueInfoModel = new RevueInfoModel($this->db);
        $revueInfo = $revueInfoModel->getRevueInfo();

        $volumeModel = new VolumeModel($this->db);
        $volumes = $volumeModel->getAllVolumes(1, 100);
        usort($volumes, function ($a, $b) {
            return ($b['annee'] ?? 0) - ($a['annee'] ?? 0);
        });

        \App\App::view('comite', [
            'volumes' => $volumes,
            'revueInfo' => $revueInfo
        ]);
    }

    /**
     * Page présentation de la revue (à propos)
     */
    public function presentation() {
        $revueInfoModel = new RevueInfoModel($this->db);
        $revueInfo = $revueInfoModel->getRevueInfo();
        \App\App::view('presentation', ['revueInfo' => $revueInfo]);
    }

    /**
     * Détails d'un volume (par année)
     */
    public function volume($params) {
        $year = $params['year'] ?? null;
        if (!$year) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Année non spécifiée']);
            return;
        }
        
        $volumeModel = new VolumeModel($this->db);
        $volume = $volumeModel->getVolumeByYear($year);
        
        if (!$volume) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Volume introuvable']);
            return;
        }
        
        // Récupérer les numéros de ce volume
        $issues = $volumeModel->getVolumeIssues($volume['id']);
        
        \App\App::view('volume-details', [
            'volume' => $volume,
            'issues' => $issues
        ]);
    }

    /**
     * Détails d'un numéro (issue) en vue publique : numéro + liste des articles publiés
     */
    public function issueDetailsPublic($params) {
        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Numéro introuvable']);
            return;
        }

        $issueModel = new IssueModel($this->db);
        $issue = $issueModel->getIssueById($id);
        if (!$issue) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Numéro introuvable']);
            return;
        }

        $allArticles = $issueModel->getIssueArticles($id);
        $publishedStatuts = ['publie', 'publié', 'valide', 'validé', 'accepte', 'accepté', 'accepted'];
        $articles = array_filter($allArticles, function ($a) use ($publishedStatuts) {
            return in_array(strtolower($a['statut'] ?? ''), $publishedStatuts);
        });
        $articles = array_values($articles);

        \App\App::view('issue-details', [
            'issue' => $issue,
            'articles' => $articles
        ]);
    }

    /**
     * Page des publications publiques
     */
    public function publications() {
        $articleModel = new ArticleModel($this->db);
        
        // Récupérer tous les articles publiés/acceptés avec leurs informations complètes
        $sql = "SELECT DISTINCT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom,
                       r.numero as issue_numero,
                       r.titre as issue_titre,
                       v.annee as volume_annee,
                       v.numero_volume
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                LEFT JOIN revues r ON a.issue_id = r.id
                LEFT JOIN volumes v ON r.volume_id = v.id
                WHERE a.statut IN ('publie', 'publié', 'valide', 'validé', 'accepte', 'accepté', 'accepted')
                ORDER BY a.date_soumission DESC
                LIMIT 100";
        
        $articles = $this->db->fetchAll($sql);
        
        \App\App::view('publications', [
            'articles' => $articles
        ]);
    }

    /**
     * Page de détails d'un article (vue publique)
     */
    public function articleDetails($params) {
        $articleId = $params['id'] ?? null;
        
        if (!$articleId) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Article introuvable']);
            return;
        }
        
        $articleModel = new ArticleModel($this->db);
        $article = $articleModel->getArticleById($articleId);
        
        // Vérifier que l'article existe et est publié
        if (!$article) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Article introuvable']);
            return;
        }
        
        $statut = strtolower($article['statut'] ?? '');
        $isPublished = in_array($statut, ['publie', 'publié', 'valide', 'validé', 'accepte', 'accepté', 'accepted']);
        
        if (!$isPublished) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Cet article n\'est pas encore publié']);
            return;
        }
        
        // Récupérer les informations complètes de l'article
        $sql = "SELECT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom,
                       u.email as auteur_email,
                       r.numero as issue_numero,
                       r.titre as issue_titre,
                       r.date_publication as issue_date,
                       v.annee as volume_annee,
                       v.numero_volume
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                LEFT JOIN revues r ON a.issue_id = r.id
                LEFT JOIN volumes v ON r.volume_id = v.id
                WHERE a.id = :id";
        
        $articleDetails = $this->db->fetchOne($sql, [':id' => $articleId]);
        
        if (!$articleDetails) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Article introuvable']);
            return;
        }
        
        \App\App::view('article-details', [
            'article' => $articleDetails
        ]);
    }

    /**
     * Page de recherche avancée
     */
    public function search() {
        $results = [];
        $searchParams = [
            'author' => '',
            'keyword' => '',
            'year_from' => '',
            'year_to' => '',
            'type' => 'all'
        ];
        
        // Si c'est une requête POST (recherche), traiter les résultats
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lire les données (peut être FormData ou JSON)
            $rawInput = file_get_contents('php://input');
            $postData = $_POST;
            
            // Si les données sont dans php://input (FormData), les parser
            if (empty($postData) && !empty($rawInput)) {
                parse_str($rawInput, $postData);
            }
            
            // Récupérer les paramètres de recherche
            $filters = [];
            
            $author = trim($postData['author'] ?? '');
            $keyword = trim($postData['keyword'] ?? '');
            $yearFrom = !empty($postData['year_from']) ? trim($postData['year_from']) : '';
            $yearTo = !empty($postData['year_to']) ? trim($postData['year_to']) : '';
            
            if (!empty($author)) {
                $filters['auteur'] = $author;
            }
            
            if (!empty($keyword)) {
                $filters['keyword'] = $keyword;
            }
            
            // Filtre par année
            if (!empty($yearFrom)) {
                $filters['year_from'] = (int)$yearFrom;
            }
            
            if (!empty($yearTo)) {
                $filters['year_to'] = (int)$yearTo;
            }
            
            // Log pour déboguer
            error_log('=== RECHERCHE DEBUG ===');
            error_log('POST reçu: ' . print_r($postData, true));
            error_log('Raw input: ' . substr($rawInput, 0, 500));
            error_log('Filtres construits: ' . print_r($filters, true));
            
            // Effectuer la recherche seulement si au moins un filtre est fourni
            if (!empty($filters)) {
                $results = $this->performSearch($filters);
                error_log('Résultats trouvés: ' . count($results));
                if (count($results) > 0) {
                    error_log('Premier résultat titre: ' . ($results[0]['titre'] ?? 'N/A'));
                }
            } else {
                // Si aucun filtre, retourner un tableau vide
                $results = [];
                error_log('Aucun filtre fourni, résultats vides');
            }
            
            $searchParams = [
                'author' => $author,
                'keyword' => $keyword,
                'year_from' => $yearFrom,
                'year_to' => $yearTo,
                'type' => $postData['type'] ?? 'all'
            ];
            
            // Si c'est une requête AJAX, retourner JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'results' => $results,
                    'count' => count($results),
                    'filters' => $filters // Pour déboguer
                ]);
                exit;
            }
        }
        
        \App\App::view('search', [
            'results' => $results,
            'searchParams' => $searchParams
        ]);
    }
    
    /**
     * Effectuer une recherche dans la base de données
     */
    private function performSearch($filters) {
        // Construire la requête SQL manuellement pour gérer les filtres complexes
        $sql = "SELECT DISTINCT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom,
                       r.numero as issue_numero,
                       r.titre as issue_titre,
                       v.annee as volume_annee,
                       v.numero_volume
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                LEFT JOIN revues r ON a.issue_id = r.id
                LEFT JOIN volumes v ON r.volume_id = v.id
                WHERE a.statut IN ('publie', 'publié', 'valide', 'validé', 'accepte', 'accepté', 'accepted')";
        
        $params = [];
        
        // Filtre par auteur
        if (!empty($filters['auteur'])) {
            $sql .= " AND (u.nom LIKE :auteur OR u.prenom LIKE :auteur OR CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) LIKE :auteur)";
            $params[':auteur'] = '%' . trim($filters['auteur']) . '%';
        }
        
        // Filtre par mot-clé (titre ou contenu)
        if (!empty($filters['keyword'])) {
            $keyword = trim($filters['keyword']);
            // Rechercher dans le titre et le contenu (pas de colonne mots_cles dans la table)
            // Utiliser COALESCE pour gérer les valeurs NULL
            $sql .= " AND (a.titre LIKE :keyword OR COALESCE(a.contenu, '') LIKE :keyword)";
            $params[':keyword'] = '%' . $keyword . '%';
        }
        
        // Filtre par année (utiliser l'année du volume si disponible, sinon date_soumission)
        if (!empty($filters['year_from'])) {
            $sql .= " AND (COALESCE(v.annee, YEAR(COALESCE(a.date_soumission, a.created_at))) >= :year_from)";
            $params[':year_from'] = (int)$filters['year_from'];
        }
        
        if (!empty($filters['year_to'])) {
            $sql .= " AND (COALESCE(v.annee, YEAR(COALESCE(a.date_soumission, a.created_at))) <= :year_to)";
            $params[':year_to'] = (int)$filters['year_to'];
        }
        
        $sql .= " ORDER BY a.date_soumission DESC LIMIT 100";
        
        try {
            error_log('SQL Recherche: ' . $sql);
            error_log('Params Recherche: ' . print_r($params, true));
            
            // Utiliser fetchAll de la classe Database
            $results = $this->db->fetchAll($sql, $params);
            
            error_log('Résultats trouvés: ' . count($results));
            if (count($results) > 0) {
                error_log('Exemple résultat - Titre: ' . ($results[0]['titre'] ?? 'N/A'));
                error_log('Exemple résultat - Auteur: ' . ($results[0]['auteur_nom'] ?? 'N/A'));
            }
            
            return $results;
        } catch (\Exception $e) {
            error_log('Erreur recherche: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            return [];
        }
    }

    /**
     * Téléchargement sécurisé d'un article (PDF) — réservé aux abonnés / auteurs / staff
     */
    public function downloadArticle($params) {
        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Article introuvable']);
            return;
        }
        if (!\Service\AuthService::canDownloadArticle()) {
            header('Location: ' . \Router\Router::route('author') . '/subscribe');
            exit;
        }
        $articleModel = new ArticleModel($this->db);
        $article = $articleModel->getArticleById($id);
        if (!$article || empty($article['fichier_path'])) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Fichier introuvable']);
            return;
        }
        $statut = strtolower($article['statut'] ?? '');
        $published = in_array($statut, ['publie', 'publié', 'valide', 'validé', 'accepte', 'accepté', 'accepted']);
        if (!$published) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Article non disponible']);
            return;
        }
        $path = $article['fichier_path'];
        if (strpos($path, '..') !== false) {
            http_response_code(400);
            exit;
        }
        $fullPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path;
        if (!is_file($fullPath)) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Fichier introuvable']);
            return;
        }
        $name = basename($path);
        $mime = 'application/octet-stream';
        if (function_exists('mime_content_type')) {
            $mime = mime_content_type($fullPath) ?: $mime;
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $fullPath) ?: $mime;
            finfo_close($finfo);
        }
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $name) . '"');
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
        exit;
    }

    /**
     * Téléchargement sécurisé du PDF d'un numéro — réservé aux abonnés / auteurs / staff
     */
    public function downloadIssue($params) {
        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Numéro introuvable']);
            return;
        }
        if (!\Service\AuthService::canDownloadArticle()) {
            header('Location: ' . \Router\Router::route('author') . '/subscribe');
            exit;
        }
        $issueModel = new IssueModel($this->db);
        $issue = $issueModel->getIssueById($id);
        if (!$issue || empty($issue['fichier_path'])) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Fichier introuvable']);
            return;
        }
        $path = $issue['fichier_path'];
        if (strpos($path, '..') !== false) {
            http_response_code(400);
            exit;
        }
        $fullPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path;
        if (!is_file($fullPath)) {
            http_response_code(404);
            \App\App::view('404', ['message' => 'Fichier introuvable']);
            return;
        }
        $name = basename($path);
        $mime = 'application/octet-stream';
        if (function_exists('mime_content_type')) {
            $mime = mime_content_type($fullPath) ?: $mime;
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $fullPath) ?: $mime;
            finfo_close($finfo);
        }
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $name) . '"');
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
        exit;
    }

}

