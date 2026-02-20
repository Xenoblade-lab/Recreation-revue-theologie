<?php
namespace Controllers;

use Models\UserModel;
use Models\ReviewModel;
use Service\AuthService;

class ReviewerController extends Controller
{
    /**
     * Vérifie que l'utilisateur est connecté et reviewer
     */
    private function requireReviewer(): int
    {
        AuthService::requireLogin();

        $userId = $_SESSION['user_id'] ?? null;
        $role   = \Service\AuthService::getActiveRole();

        // Accepter les rôles 'redacteur' et 'redacteur en chef' comme évaluateurs
        if (!$userId || !in_array(strtolower($role ?? ''), ['reviewer', 'redacteur', 'redacteur en chef'])) {
            header('Location: ' . \Router\Router::route('login'));
            exit;
        }

        return $userId;
    }

    /**
     * Tableau de bord reviewer (articles en attente / en cours)
     */
    public function index()
    {
        $userId = $this->requireReviewer();
        $db = $this->db();

        $userModel  = new UserModel($db);
        $reviewModel = new ReviewModel($db);

        $user  = $userModel->getUserById($userId);
        $stats = $reviewModel->getReviewerStats($userId);

        $evaluations = $reviewModel->getReviewsByReviewer($userId, null, 1, 50) ?? [];
        // Ne garder que les évaluations non terminées
        $evaluations = array_values(array_filter($evaluations, function ($eval) {
            return strtolower($eval['statut'] ?? '') !== 'termine';
        }));

        \App\App::view('reviewer' . DIRECTORY_SEPARATOR . 'index', [
            'user'        => $user,
            'stats'       => $stats,
            'evaluations' => $evaluations,
            'current_page'=> 'dashboard',
        ]);
    }

    /**
     * Évaluations terminées
     */
    public function terminees()
    {
        $userId = $this->requireReviewer();
        $db = $this->db();

        $userModel  = new UserModel($db);
        $reviewModel = new ReviewModel($db);

        $user  = $userModel->getUserById($userId);
        $stats = $reviewModel->getReviewerStats($userId);
        $evaluations = $reviewModel->getReviewsByReviewer($userId, 'termine', 1, 50) ?? [];

        \App\App::view('reviewer' . DIRECTORY_SEPARATOR . 'terminees', [
            'user'        => $user,
            'stats'       => $stats,
            'evaluations' => $evaluations,
            'current_page'=> 'terminees',
        ]);
    }

    /**
     * Historique complet des évaluations
     */
    public function historique()
    {
        $userId = $this->requireReviewer();
        $db = $this->db();

        $userModel  = new UserModel($db);
        $reviewModel = new ReviewModel($db);

        $user  = $userModel->getUserById($userId);
        $stats = $reviewModel->getReviewerStats($userId);
        $evaluations = $reviewModel->getReviewsByReviewer($userId, null, 1, 200) ?? [];

        \App\App::view('reviewer' . DIRECTORY_SEPARATOR . 'historique', [
            'user'        => $user,
            'stats'       => $stats,
            'evaluations' => $evaluations,
            'current_page'=> 'historique',
        ]);
    }

    /**
     * Publications (articles publiés) visibles par l'évaluateur
     */
    public function publications()
    {
        $userId = $this->requireReviewer();
        $db = $this->db();

        $userModel   = new UserModel($db);
        $reviewModel = new ReviewModel($db);
        $articleModel = new \Models\ArticleModel($db);

        $user  = $userModel->getUserById($userId);
        $stats = $reviewModel->getReviewerStats($userId);

        // Récupérer les articles publiés (ou validés)
        $publications = $db->fetchAll("
            SELECT a.*,
                   u.nom as auteur_nom,
                   u.prenom as auteur_prenom
            FROM articles a
            JOIN users u ON a.auteur_id = u.id
            WHERE a.statut IN ('publie', 'publié', 'valide', 'accepte', 'accepted')
            ORDER BY a.updated_at DESC
        ");

        \App\App::view('reviewer' . DIRECTORY_SEPARATOR . 'publications', [
            'user'         => $user,
            'stats'        => $stats,
            'publications' => $publications,
            'current_page' => 'publications',
        ]);
    }

    /**
     * Profil évaluateur
     */
    public function profil()
    {
        $userId = $this->requireReviewer();
        $db = $this->db();

        $userModel  = new UserModel($db);
        $reviewModel = new ReviewModel($db);

        $user  = $userModel->getUserById($userId);
        $stats = $reviewModel->getReviewerStats($userId);

        \App\App::view('reviewer' . DIRECTORY_SEPARATOR . 'profil', [
            'user'        => $user,
            'stats'       => $stats,
            'current_page'=> 'profil',
        ]);
    }

    /**
     * Détails d'une évaluation
     */
    public function evaluationDetails($params = [])
    {
        $userId = $this->requireReviewer();
        $db = $this->db();

        $evaluationId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$evaluationId) {
            header('Location: ' . \Router\Router::route('reviewer'));
            exit;
        }

        $userModel = new UserModel($db);
        $reviewModel = new ReviewModel($db);

        $user = $userModel->getUserById($userId);
        $stats = $reviewModel->getReviewerStats($userId);
        
        // Récupérer l'évaluation avec vérification que c'est bien celle de l'évaluateur
        $evaluation = $reviewModel->getReviewById($evaluationId);
        
        if (!$evaluation || (int)$evaluation['evaluateur_id'] !== (int)$userId) {
            header('Location: ' . \Router\Router::route('reviewer'));
            exit;
        }

        // Calculer les jours restants
        $joursRestants = null;
        if (!empty($evaluation['date_echeance'])) {
            $now = new \DateTime();
            $echeance = new \DateTime($evaluation['date_echeance']);
            $diff = $now->diff($echeance);
            $joursRestants = $echeance > $now ? $diff->days : -$diff->days;
        }
        $evaluation['jours_restants'] = $joursRestants;

        // Si l'évaluation est en attente, la passer en cours
        if (strtolower($evaluation['statut'] ?? '') === 'en_attente') {
            $reviewModel->acceptReviewAssignment($evaluationId);
            $evaluation['statut'] = 'en_cours';
        }

        \App\App::view('reviewer' . DIRECTORY_SEPARATOR . 'evaluation-details', [
            'user'        => $user,
            'stats'       => $stats,
            'evaluation'  => $evaluation,
            'current_page'=> 'dashboard',
        ]);
    }

    /**
     * Sauvegarder un brouillon d'évaluation
     */
    public function saveDraft($params = [])
    {
        $userId = $this->requireReviewer();
        $db = $this->db();

        $evaluationId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$evaluationId) {
            $this->respond(['error' => 'ID d\'évaluation manquant'], 400);
            return;
        }

        $reviewModel = new ReviewModel($db);
        
        // Vérifier que l'évaluation appartient à l'évaluateur
        $evaluation = $reviewModel->getReviewById($evaluationId);
        if (!$evaluation || (int)$evaluation['evaluateur_id'] !== (int)$userId) {
            $this->respond(['error' => 'Évaluation introuvable ou non autorisée'], 403);
            return;
        }

        $data = $this->input();
        $success = $reviewModel->saveDraft($evaluationId, $data);

        if ($success) {
            $this->respond([
                'success' => true,
                'message' => 'Brouillon sauvegardé avec succès'
            ], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la sauvegarde'], 500);
        }
    }

    /**
     * Soumettre une évaluation
     */
    public function submitEvaluation($params = [])
    {
        try {
            $userId = $this->requireReviewer();
            $db = $this->db();

            $evaluationId = is_array($params) ? ($params['id'] ?? null) : $params;
            if (!$evaluationId) {
                $this->respond(['error' => 'ID d\'évaluation manquant'], 400);
                return;
            }

            $reviewModel = new ReviewModel($db);
            
            // Vérifier que l'évaluation appartient à l'évaluateur
            $evaluation = $reviewModel->getReviewById($evaluationId);
            if (!$evaluation || (int)$evaluation['evaluateur_id'] !== (int)$userId) {
                $this->respond(['error' => 'Évaluation introuvable ou non autorisée'], 403);
                return;
            }

            $data = $this->input();
            
            // Validation des champs requis
            if (empty($data['recommendation'])) {
                $this->respond(['error' => 'La recommandation est requise'], 400);
                return;
            }

            if (empty($data['commentaires_public'])) {
                $this->respond(['error' => 'Les commentaires publics sont requis'], 400);
                return;
            }

            // Validation des notes
            $requiredNotes = ['qualite_scientifique', 'originalite', 'pertinence', 'clarte'];
            foreach ($requiredNotes as $note) {
                if (!isset($data[$note]) || $data[$note] === '' || $data[$note] === null) {
                    $this->respond(['error' => 'Toutes les notes sont requises'], 400);
                    return;
                }
                $noteValue = (float)$data[$note];
                if ($noteValue < 0 || $noteValue > 10) {
                    $this->respond(['error' => 'Les notes doivent être entre 0 et 10'], 400);
                    return;
                }
            }

            $success = $reviewModel->submitReview($evaluationId, $data);

            if ($success) {
                $this->respond([
                    'success' => true,
                    'message' => 'Évaluation soumise avec succès'
                ], 200);
            } else {
                $this->respond(['error' => 'Erreur lors de la soumission de l\'évaluation'], 500);
            }
        } catch (\Exception $e) {
            error_log('Erreur submitEvaluation: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            $this->respond([
                'error' => 'Une erreur est survenue lors de la soumission',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Formate le statut d'évaluation
     */
    protected function formatStatut($statut): string
    {
        $statut = strtolower($statut ?? '');
        $map = [
            'en_attente' => 'En attente',
            'en cours'   => 'En cours',
            'en_cours'   => 'En cours',
            'termine'    => 'Terminé',
            'rejete'     => 'Rejeté',
            'accepte'    => 'Accepté',
        ];
        return $map[$statut] ?? ucfirst($statut);
    }
}

