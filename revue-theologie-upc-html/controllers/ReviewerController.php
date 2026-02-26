<?php
namespace Controllers;

use Service\AuthService;
use Models\EvaluationModel;
use Models\NotificationModel;
use Models\UserModel;

/**
 * Contrôleur espace évaluateur (reviewer) : dashboard, évaluations assignées, formulaire, terminées, historique.
 */
class ReviewerController
{
    private function base(): string
    {
        return defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
    }

    private function render(string $viewName, array $data = [], ?string $pageTitle = null, string $reviewerPage = ''): void
    {
        requireReviewer();
        $_SESSION['reviewer_page'] = $reviewerPage;
        release_session();
        $base = $this->base();
        $user = AuthService::getUser();
        $data['base'] = $base;
        $data['currentUser'] = $user;
        extract($data);
        ob_start();
        require BASE_PATH . '/views/reviewer/' . $viewName . '.php';
        $viewContent = ob_get_clean();
        $pageTitle = $pageTitle ?? 'Espace évaluateur | Revue Congolaise de Théologie Protestante';
        require BASE_PATH . '/views/layouts/reviewer-dashboard.php';
    }

    public function index(array $params = []): void
    {
        $user = AuthService::getUser();
        $evaluateurId = (int) $user['id'];
        $assignations = EvaluationModel::getByEvaluateurId($evaluateurId, null, 50);
        $enAttente = EvaluationModel::countByEvaluateurIdAndStatut($evaluateurId, 'en_attente');
        $enCours = EvaluationModel::countByEvaluateurIdAndStatut($evaluateurId, 'en_cours');
        $terminees = EvaluationModel::countByEvaluateurIdAndStatut($evaluateurId, 'termine');
        $total = EvaluationModel::countByEvaluateurId($evaluateurId);
        $tauxCompletion = $total > 0 ? (int) round(($terminees / $total) * 100) : 0;
        $this->render('index', [
            'assignations'   => $assignations,
            'enAttente'      => $enAttente,
            'enCours'        => $enCours,
            'terminees'      => $terminees,
            'tauxCompletion' => $tauxCompletion,
        ], 'Tableau de bord évaluateur | Revue Congolaise de Théologie Protestante', 'index');
    }

    public function evaluation(array $params = []): void
    {
        $user = AuthService::getUser();
        $evaluateurId = (int) $user['id'];
        $id = (int) ($params['id'] ?? 0);
        $evaluation = $id ? EvaluationModel::getByIdForReviewer($id, $evaluateurId) : null;
        if (!$evaluation || in_array($evaluation['statut'], ['termine', 'annule'], true)) {
            $base = $this->base();
            requireReviewer();
            $_SESSION['reviewer_page'] = '';
            release_session();
            http_response_code(404);
            $viewContent = '<div class="container section"><h1>Évaluation introuvable</h1><p><a href="' . $base . '/reviewer">Retour au tableau de bord</a></p></div>';
            $pageTitle = 'Évaluation | Revue Congolaise de Théologie Protestante';
            require BASE_PATH . '/views/layouts/reviewer-dashboard.php';
            return;
        }
        $error = $_SESSION['reviewer_error'] ?? null;
        unset($_SESSION['reviewer_error']);
        $this->render('evaluation', [
            'evaluation' => $evaluation,
            'error'      => $error,
        ], 'Évaluation | Revue Congolaise de Théologie Protestante', '');
    }

    /**
     * POST unique : draft (bouton "Sauvegarder le brouillon") ou submit (bouton "Soumettre l'évaluation").
     */
    public function evaluationPost(array $params = []): void
    {
        requireReviewer();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/reviewer');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['reviewer_error'] = 'Requête invalide. Veuillez réessayer.';
            $id = (int) ($params['id'] ?? 0);
            header('Location: ' . $this->base() . '/reviewer/evaluation/' . $id);
            exit;
        }
        $user = AuthService::getUser();
        $evaluateurId = (int) $user['id'];
        $id = (int) ($params['id'] ?? 0);
        $evaluation = $id ? EvaluationModel::getByIdForReviewer($id, $evaluateurId) : null;
        if (!$evaluation || !in_array($evaluation['statut'], ['en_attente', 'en_cours'], true)) {
            header('Location: ' . $this->base() . '/reviewer');
            exit;
        }
        $isDraft = !empty($_POST['draft']);
        if (!$isDraft) {
            $recommendation = trim($_POST['recommendation'] ?? '');
            if ($recommendation === '') {
                $_SESSION['reviewer_error'] = 'La recommandation est obligatoire pour soumettre.';
                header('Location: ' . $this->base() . '/reviewer/evaluation/' . $id);
                exit;
            }
        }
        $ok = $this->applyEvaluationForm($id, $evaluateurId, $isDraft);
        if ($ok) {
            if ($isDraft) {
                header('Location: ' . $this->base() . '/reviewer/evaluation/' . $id);
            } else {
                header('Location: ' . $this->base() . '/reviewer/terminees');
            }
            exit;
        }
        $_SESSION['reviewer_error'] = 'Erreur lors de l\'enregistrement.';
        header('Location: ' . $this->base() . '/reviewer/evaluation/' . $id);
        exit;
    }

    private function applyEvaluationForm(int $evaluationId, int $evaluateurId, bool $draft): bool
    {
        $recommendation = trim($_POST['recommendation'] ?? '');
        if ($recommendation === 'revision_mineure') {
            $recommendation = 'accepte_avec_modifications';
        }
        $qualite = isset($_POST['qualite_scientifique']) && $_POST['qualite_scientifique'] !== '' ? (int) $_POST['qualite_scientifique'] : null;
        $originalite = isset($_POST['originalite']) && $_POST['originalite'] !== '' ? (int) $_POST['originalite'] : null;
        $pertinence = isset($_POST['pertinence']) && $_POST['pertinence'] !== '' ? (int) $_POST['pertinence'] : null;
        $clarte = isset($_POST['clarte']) && $_POST['clarte'] !== '' ? (int) $_POST['clarte'] : null;
        $noteFinale = isset($_POST['note_finale']) && $_POST['note_finale'] !== '' ? (int) $_POST['note_finale'] : null;
        $commentairesPublic = trim($_POST['commentaires_public'] ?? '') ?: null;
        $commentairesPrives = trim($_POST['commentaires_prives'] ?? '') ?: null;
        $suggestions = trim($_POST['suggestions'] ?? '') ?: null;
        if ($draft) {
            return EvaluationModel::saveDraft(
                $evaluationId,
                $evaluateurId,
                $recommendation ?: null,
                $qualite,
                $originalite,
                $pertinence,
                $clarte,
                $noteFinale,
                $commentairesPublic,
                $commentairesPrives,
                $suggestions
            );
        }
        return EvaluationModel::submit(
            $evaluationId,
            $evaluateurId,
            $recommendation,
            $qualite,
            $originalite,
            $pertinence,
            $clarte,
            $noteFinale,
            $commentairesPublic,
            $commentairesPrives,
            $suggestions
        );
    }

    public function terminees(array $params = []): void
    {
        $user = AuthService::getUser();
        $evaluateurId = (int) $user['id'];
        $list = EvaluationModel::getByEvaluateurId($evaluateurId, 'termine', 50);
        $this->render('terminees', ['evaluations' => $list], 'Évaluations terminées | Revue Congolaise de Théologie Protestante', 'terminees');
    }

    public function historique(array $params = []): void
    {
        $user = AuthService::getUser();
        $evaluateurId = (int) $user['id'];
        $list = EvaluationModel::getByEvaluateurId($evaluateurId, null, 100);
        $this->render('historique', ['evaluations' => $list], 'Historique des évaluations | Revue Congolaise de Théologie Protestante', 'historique');
    }

    public function notifications(array $params = []): void
    {
        $user = AuthService::getUser();
        $notifications = NotificationModel::getByUserId((int) $user['id']);
        $error = $_SESSION['reviewer_error'] ?? null;
        unset($_SESSION['reviewer_error']);
        $this->render('notifications', [
            'notifications' => $notifications,
            'error'          => $error,
        ], 'Notifications | Espace évaluateur - Revue Congolaise de Théologie Protestante', 'notifications');
    }

    public function notificationMarkRead(array $params = []): void
    {
        requireReviewer();
        if (!validate_csrf()) {
            $_SESSION['reviewer_error'] = 'Requête invalide. Veuillez réessayer.';
            release_session();
            header('Location: ' . $this->base() . '/reviewer/notifications');
            exit;
        }
        $id = $params['id'] ?? '';
        $user = AuthService::getUser();
        if ($id !== '') {
            NotificationModel::markAsRead($id, (int) $user['id']);
        }
        header('Location: ' . $this->base() . '/reviewer/notifications');
        exit;
    }

    public function notificationsMarkAllRead(array $params = []): void
    {
        requireReviewer();
        if (!validate_csrf()) {
            $_SESSION['reviewer_error'] = 'Requête invalide. Veuillez réessayer.';
            release_session();
            header('Location: ' . $this->base() . '/reviewer/notifications');
            exit;
        }
        $user = AuthService::getUser();
        NotificationModel::markAllAsRead((int) $user['id']);
        header('Location: ' . $this->base() . '/reviewer/notifications');
        exit;
    }

    public function profil(array $params = []): void
    {
        $user = AuthService::getUser();
        $error = $_SESSION['reviewer_error'] ?? null;
        $success = !empty($_SESSION['reviewer_success']);
        unset($_SESSION['reviewer_error'], $_SESSION['reviewer_success']);
        $this->render('profil', [
            'user'   => $user,
            'error'  => $error,
            'success' => $success,
        ], 'Mon profil | Espace évaluateur - Revue Congolaise de Théologie Protestante', 'profil');
    }

    public function profilUpdate(array $params = []): void
    {
        requireReviewer();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->base() . '/reviewer/profil');
            exit;
        }
        if (!validate_csrf()) {
            $_SESSION['reviewer_error'] = 'Requête invalide. Veuillez réessayer.';
            header('Location: ' . $this->base() . '/reviewer/profil');
            exit;
        }
        $user = AuthService::getUser();
        $id = (int) $user['id'];
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $newPassword = $_POST['password'] ?? '';
        if (!$nom || !$prenom || !$email) {
            $_SESSION['reviewer_error'] = __('reviewer.profil_error_required') ?: 'Nom, prénom et email sont obligatoires.';
            header('Location: ' . $this->base() . '/reviewer/profil');
            exit;
        }
        if (UserModel::emailExists($email, $id)) {
            $_SESSION['reviewer_error'] = __('reviewer.profil_error_email') ?: 'Cet email est déjà utilisé.';
            header('Location: ' . $this->base() . '/reviewer/profil');
            exit;
        }
        $hash = strlen($newPassword) >= 6 ? password_hash($newPassword, PASSWORD_DEFAULT) : null;
        if (UserModel::updateProfile($id, $nom, $prenom, $email, $hash)) {
            $_SESSION['reviewer_success'] = true;
            AuthService::refreshUser();
        } else {
            $_SESSION['reviewer_error'] = __('reviewer.profil_error_save') ?: 'Erreur lors de l\'enregistrement.';
        }
        release_session();
        header('Location: ' . $this->base() . '/reviewer/profil');
        exit;
    }
}
