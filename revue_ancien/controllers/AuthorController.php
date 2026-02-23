<?php
namespace Controllers;

use Models\Database;
use Models\UserModel;
use Models\ArticleModel;

class AuthorController extends Controller
{
    /**
     * Vérifie que l'utilisateur est connecté (pas un admin)
     * Permet l'accès même sans abonnement pour afficher la page d'abonnement
     */
    private function requireAuthorOrSubscribe() {
        \Service\AuthService::requireLogin();
        
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: ' . \Router\Router::route('login'));
            exit;
        }
        
        $principalRole = $_SESSION['user_role'] ?? null;
        
        // Bloquer les admins même s'ils ont basculé de rôle
        if ($principalRole && strtolower($principalRole) === 'admin') {
            header('Location: ' . \Router\Router::route('admin'));
            exit;
        }
        
        return $userId;
    }

    /**
     * Vérifie que l'utilisateur est un auteur (pas un admin)
     * Un utilisateur devient auteur quand il s'abonne (abonnement actif)
     */
    private function requireAuthor() {
        $userId = $this->requireAuthorOrSubscribe();
        
        $activeRole = $_SESSION['active_role'] ?? $_SESSION['user_role'] ?? null;
        
        // Vérifier si l'utilisateur a le rôle auteur
        $isAuthorRole = $activeRole && (strtolower($activeRole) === 'auteur' || strtolower($activeRole) === 'author');
        
        // Si l'utilisateur n'a pas le rôle auteur, vérifier s'il a un abonnement actif
        if (!$isAuthorRole) {
            $db = $this->db();
            $userModel = new UserModel($db);
            
            // Vérifier si l'utilisateur a un abonnement actif
            $hasActiveSubscription = $userModel->isSubscribedAndActive($userId);
            
            if (!$hasActiveSubscription) {
                // L'utilisateur n'a ni le rôle auteur ni un abonnement actif
                header('Location: ' . \Router\Router::route('author') . '/subscribe');
                exit;
            }
        }
        
        return $userId;
    }

    /**
     * Affiche la page d'abonnement pour les utilisateurs non abonnés
     */
    public function subscribe()
    {
        $userId = $this->requireAuthorOrSubscribe();
        
        $db = $this->db();
        $userModel = new UserModel($db);
        
        // Récupérer les informations de l'utilisateur
        $user = $userModel->getUserById($userId);
        if (!$user) {
            header('Location: ' . \Router\Router::route('login'));
            exit;
        }
        
        // Vérifier si l'utilisateur a déjà un abonnement actif
        $hasActiveSubscription = $userModel->isSubscribedAndActive($userId);
        if ($hasActiveSubscription) {
            // Rediriger vers le dashboard si déjà abonné
            header('Location: ' . \Router\Router::route('author'));
            exit;
        }
        
        // Récupérer l'abonnement en attente ou expiré
        $abonnement = $db->fetchOne(
            "SELECT * FROM abonnements WHERE utilisateur_id = :userId ORDER BY created_at DESC LIMIT 1",
            [':userId' => $userId]
        );
        
        // Déterminer le tarif selon la région (par défaut Afrique)
        // TODO: Détecter automatiquement la région de l'utilisateur
        $region = 'afrique'; // Par défaut
        $tarifs = [
            'afrique' => 25.00,
            'europe' => 30.00,
            'amerique' => 35.00
        ];
        $montant = $tarifs[$region];
        
        $data = [
            'user' => $user,
            'abonnement' => $abonnement,
            'montant' => $montant,
            'region' => $region,
            'tarifs' => $tarifs
        ];
        
        \App\App::view('author' . DIRECTORY_SEPARATOR . 'subscribe', $data);
    }

    /**
     * Crée un nouvel abonnement et met à jour le rôle de l'utilisateur
     */
    public function createSubscription()
    {
        $userId = $this->requireAuthorOrSubscribe();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->respond(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $data = $this->input();
        $moyen = $data['moyen'] ?? null;
        $region = $data['region'] ?? 'afrique';
        $phoneNumber = $data['phoneNumber'] ?? null;
        $cardNumber = $data['cardNumber'] ?? null;
        $cardExpiry = $data['cardExpiry'] ?? null;
        $cardCVC = $data['cardCVC'] ?? null;
        $cardName = $data['cardName'] ?? null;
        
        // Validation
        if (!$moyen) {
            $this->respond(['error' => 'Moyen de paiement requis'], 400);
            return;
        }
        
        // Validation des données selon le mode de paiement
        if ($moyen === 'bancaire') {
            if (!$cardNumber || !$cardExpiry || !$cardCVC || !$cardName) {
                $this->respond(['error' => 'Tous les champs de la carte bancaire sont requis'], 400);
                return;
            }
            // Validation basique du numéro de carte (16 chiffres)
            if (!preg_match('/^\d{16}$/', str_replace(' ', '', $cardNumber))) {
                $this->respond(['error' => 'Numéro de carte invalide'], 400);
                return;
            }
        } else {
            if (!$phoneNumber) {
                $this->respond(['error' => 'Numéro de téléphone requis'], 400);
                return;
            }
            // Validation basique du numéro de téléphone
            if (strlen(trim($phoneNumber)) < 9) {
                $this->respond(['error' => 'Numéro de téléphone invalide'], 400);
                return;
            }
        }
        
        $tarifs = [
            'afrique' => 25.00,
            'europe' => 30.00,
            'amerique' => 35.00
        ];
        $montant = $tarifs[$region] ?? 25.00;
        
        // Moyens de paiement valides
        $moyensValides = ['orange_money', 'mpesa', 'airtel_money', 'bancaire'];
        if (!in_array($moyen, $moyensValides)) {
            $this->respond(['error' => 'Moyen de paiement invalide'], 400);
            return;
        }
        
        $db = $this->db();
        
        try {
            // S'assurer que la connexion est établie et obtenir la connexion PDO
            $pdo = $db->getConnection();
            
            // Démarrer une transaction
            $pdo->beginTransaction();
            
            // Créer le paiement
            $db->execute(
                "INSERT INTO paiements (utilisateur_id, montant, moyen, statut, created_at, updated_at) 
                 VALUES (:userId, :montant, :moyen, 'en_attente', NOW(), NOW())",
                [
                    ':userId' => $userId,
                    ':montant' => $montant,
                    ':moyen' => $moyen
                ]
            );
            
            $paiementId = $db->lastInsertId();
            
            if (!$paiementId) {
                throw new \Exception('Erreur lors de la création du paiement');
            }
            
            // Pour la simulation, on active directement le paiement et l'abonnement
            // En production, cela devrait être fait après validation manuelle ou via API
            $db->execute(
                "UPDATE paiements SET statut = 'valide', date_paiement = NOW() WHERE id = :id",
                [':id' => $paiementId]
            );
            
            // Calculer les dates d'abonnement (1 an à partir d'aujourd'hui)
            $dateDebut = date('Y-m-d');
            $dateFin = date('Y-m-d', strtotime('+1 year'));
            
            // Créer l'abonnement
            $db->execute(
                "INSERT INTO abonnements (utilisateur_id, date_debut, date_fin, statut, created_at, updated_at) 
                 VALUES (:userId, :date_debut, :date_fin, 'actif', NOW(), NOW())",
                [
                    ':userId' => $userId,
                    ':date_debut' => $dateDebut,
                    ':date_fin' => $dateFin
                ]
            );
            
            // Mettre à jour le rôle de l'utilisateur en 'auteur'
            $userModel = new UserModel($db);
            $userModel->updateUser($userId, ['role' => 'auteur']);
            
            // Mettre à jour la session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_role'] = 'auteur';
            $_SESSION['active_role'] = 'auteur';
            
            // Valider la transaction
            $pdo->commit();
            
            $this->respond([
                'success' => true,
                'message' => 'Abonnement créé avec succès. Vous êtes maintenant auteur.',
                'redirect' => \Router\Router::route('author')
            ], 200);
            
        } catch (\PDOException $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Erreur PDO lors de la création de l\'abonnement: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            $this->respond(['error' => 'Erreur lors de la création de l\'abonnement: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Erreur lors de la création de l\'abonnement: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            $this->respond(['error' => 'Erreur lors de la création de l\'abonnement: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Affiche le dashboard de l'auteur
     */
    public function index()
    {
        $userId = $this->requireAuthor();
        
        $db = $this->db();
        $userModel = new UserModel($db);
        $articleModel = new ArticleModel($db);
        
        // Récupérer les informations de l'utilisateur
        $user = $userModel->getUserById($userId);
        if (!$user) {
            header('Location: ' . \Router\Router::route('login'));
            exit;
        }
        
        // Vérifier et mettre à jour le rôle si l'utilisateur a un abonnement actif
        // mais que son rôle est encore 'user'
        if (($user['role'] ?? 'user') === 'user') {
            $hasActiveSubscription = $userModel->isSubscribedAndActive($userId);
            if ($hasActiveSubscription) {
                // Mettre à jour le rôle dans la base de données
                $userModel->updateUser($userId, ['role' => 'auteur']);
                // Rafraîchir les données utilisateur
                $user = $userModel->getUserById($userId);
                // Mettre à jour la session
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_role'] = 'auteur';
                $_SESSION['active_role'] = 'auteur';
                $_SESSION['user'] = $user;
            }
        }
        
        // Récupérer tous les articles de l'auteur
        $allArticles = $articleModel->getArticlesByAuthor($userId, 1, 1000);
        
        // S'assurer que $allArticles est un tableau
        if (!is_array($allArticles)) {
            $allArticles = [];
        }
        
        // Calculer les statistiques
        $stats = [
            'total' => count($allArticles),
            'soumis' => 0,
            'en_evaluation' => 0,
            'accepte' => 0,
            'publie' => 0,
            'rejete' => 0
        ];
        
        foreach ($allArticles as $article) {
            $statut = strtolower($article['statut'] ?? '');
            switch ($statut) {
                case 'soumis':
                    $stats['soumis']++;
                    break;
                case 'en évaluation':
                case 'en_evaluation':
                case 'en evaluation':
                    $stats['en_evaluation']++;
                    break;
                case 'accepté':
                case 'accepte':
                case 'accepted':
                    $stats['accepte']++;
                    break;
                case 'valide':
                case 'validé':
                case 'publié':
                case 'publie':
                case 'published':
                    // Tout ce qui est validé / publié est compté comme publié pour l'auteur
                    $stats['publie']++;
                    break;
                case 'rejeté':
                case 'rejete':
                case 'rejected':
                    $stats['rejete']++;
                    break;
            }
        }
        
        // Formater les articles pour l'affichage
        $articles = [];
        if (!empty($allArticles)) {
            $articles = array_map(function($article) {
                return [
                    'id' => $article['id'],
                    'titre' => $article['titre'] ?? 'Sans titre',
                    'date_soumission' => $article['date_soumission'] ?? $article['created_at'] ?? date('Y-m-d'),
                    'statut' => $article['statut'] ?? 'soumis',
                    'statut_display' => $this->formatStatut($article['statut'] ?? 'soumis')
                ];
            }, array_slice($allArticles, 0, 10)); // Limiter à 10 pour l'affichage
        }
        
        // Récupérer le nombre de notifications non lues (si la table existe)
        $unreadCount = 0;
        try {
            $notificationModel = new \Models\NotificationModel($db);
            $unreadCount = $notificationModel->countUnread($userId);
        } catch (\Exception $e) {
            // Si la table n'existe pas encore, ignorer l'erreur
            error_log('Erreur lors de la récupération des notifications: ' . $e->getMessage());
        }
        
        // Passer les données à la vue
        $data = [
            'user' => $user,
            'articles' => $articles ?? [],
            'stats' => $stats,
            'total_articles' => $stats['total'],
            'unreadCount' => $unreadCount,
            'currentPage' => 'dashboard'
        ];
        
        \App\App::view('author' . DIRECTORY_SEPARATOR . 'index', $data);
    }
    
    /**
     * Affiche les articles publiés de l'auteur
     */
    public function articles()
    {
        $userId = $this->requireAuthor();
        
        $db = $this->db();
        $userModel = new UserModel($db);
        $articleModel = new ArticleModel($db);
        
        // Récupérer les informations de l'utilisateur
        $user = $userModel->getUserById($userId);
        if (!$user) {
            header('Location: ' . \Router\Router::route('login'));
            exit;
        }
        
        // Récupérer uniquement les articles publiés / validés / acceptés
        $allArticles = $articleModel->getArticlesByAuthor($userId, 1, 1000);
        $publishedArticles = array_filter($allArticles, function($article) {
            $statut = strtolower($article['statut'] ?? '');
            return in_array($statut, [
                'publié', 'publie', 'published', // publiés
                'valide', 'validé',              // validés par l’admin
                'accepte', 'accepté', 'accepted' // acceptés
            ]);
        });
        
        // Calculer les statistiques pour la sidebar
        $stats = [
            'total' => count($allArticles),
            'publie' => count($publishedArticles)
        ];
        
        $data = [
            'user' => $user,
            'publishedArticles' => array_values($publishedArticles),
            'stats' => $stats
        ];
        
        \App\App::view('author' . DIRECTORY_SEPARATOR . 'articles', $data);
    }
    
    /**
     * Affiche les abonnements et paiements de l'auteur
     */
    public function abonnement()
    {
        $userId = $this->requireAuthor();
        
        $db = $this->db();
        $userModel = new UserModel($db);
        $articleModel = new ArticleModel($db);
        
        // Récupérer les informations de l'utilisateur
        $user = $userModel->getUserById($userId);
        if (!$user) {
            header('Location: ' . \Router\Router::route('login'));
            exit;
        }
        
        // Récupérer le dernier abonnement (actif, expiré ou en attente)
        $abonnement = $db->fetchOne(
            "SELECT * FROM abonnements WHERE utilisateur_id = :userId ORDER BY created_at DESC LIMIT 1",
            [':userId' => $userId]
        );
        
        // Récupérer tous les paiements
        $paiements = $db->fetchAll(
            "SELECT * FROM paiements WHERE utilisateur_id = :userId ORDER BY created_at DESC",
            [':userId' => $userId]
        );
        
        // Calculer les statistiques pour la sidebar
        $allArticles = $articleModel->getArticlesByAuthor($userId, 1, 1000);
        $stats = [
            'total' => count($allArticles)
        ];
        foreach ($allArticles as $article) {
            $statut = strtolower($article['statut'] ?? '');
            if (in_array($statut, ['publié', 'publie', 'published', 'valide', 'validé', 'accepte', 'accepté', 'accepted'])) {
                $stats['publie'] = ($stats['publie'] ?? 0) + 1;
            }
        }
        
        // Récupérer le nombre de notifications non lues
        $unreadCount = 0;
        try {
            $notificationModel = new \Models\NotificationModel($db);
            $unreadCount = $notificationModel->countUnread($userId);
        } catch (\Exception $e) {
            error_log('Erreur lors de la récupération des notifications: ' . $e->getMessage());
        }

        $data = [
            'user' => $user,
            'abonnement' => $abonnement,
            'paiements' => $paiements,
            'stats' => $stats,
            'unreadCount' => $unreadCount
        ];
        
        \App\App::view('author' . DIRECTORY_SEPARATOR . 'abonnement', $data);
    }

    /**
     * Résilie un abonnement actif
     */
    public function cancelSubscription()
    {
        $userId = $this->requireAuthor();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->respond(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $data = $this->input();
        $abonnementId = $data['abonnement_id'] ?? null;
        
        if (!$abonnementId) {
            $this->respond(['error' => 'ID d\'abonnement requis'], 400);
            return;
        }
        
        $db = $this->db();
        
        try {
            // Vérifier que l'abonnement appartient à l'utilisateur
            $abonnement = $db->fetchOne(
                "SELECT * FROM abonnements WHERE id = :id AND utilisateur_id = :userId",
                [
                    ':id' => $abonnementId,
                    ':userId' => $userId
                ]
            );
            
            if (!$abonnement) {
                $this->respond(['error' => 'Abonnement introuvable'], 404);
                return;
            }
            
            if ($abonnement['statut'] !== 'actif') {
                $this->respond(['error' => 'Cet abonnement n\'est pas actif'], 400);
                return;
            }
            
            $pdo = $db->getConnection();
            $pdo->beginTransaction();
            
            // Marquer l'abonnement comme résilié (statut expire)
            $db->execute(
                "UPDATE abonnements SET statut = 'expire', updated_at = NOW() WHERE id = :id",
                [':id' => $abonnementId]
            );
            
            // Vérifier si l'utilisateur a encore un abonnement actif
            $hasActiveSubscription = (new UserModel($db))->isSubscribedAndActive($userId);
            
            // Si plus d'abonnement actif, remettre le rôle à 'user'
            if (!$hasActiveSubscription) {
                $userModel = new UserModel($db);
                $userModel->updateUser($userId, ['role' => 'user']);
                
                // Mettre à jour la session
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_role'] = 'user';
                $_SESSION['active_role'] = 'user';
            }
            
            $pdo->commit();
            
            $this->respond([
                'success' => true,
                'message' => 'Abonnement résilié avec succès. Votre statut d\'auteur sera révoqué à la fin de la période payée.'
            ], 200);
            
        } catch (\Exception $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Erreur lors de la résiliation de l\'abonnement: ' . $e->getMessage());
            $this->respond(['error' => 'Erreur lors de la résiliation de l\'abonnement'], 500);
        }
    }

    /**
     * Annule un paiement en attente
     */
    public function cancelPayment()
    {
        $userId = $this->requireAuthorOrSubscribe();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->respond(['error' => 'Méthode non autorisée'], 405);
            return;
        }
        
        $data = $this->input();
        $paiementId = $data['paiement_id'] ?? null;
        
        if (!$paiementId) {
            $this->respond(['error' => 'ID de paiement requis'], 400);
            return;
        }
        
        $db = $this->db();
        
        try {
            // Vérifier que le paiement appartient à l'utilisateur
            $paiement = $db->fetchOne(
                "SELECT * FROM paiements WHERE id = :id AND utilisateur_id = :userId",
                [
                    ':id' => $paiementId,
                    ':userId' => $userId
                ]
            );
            
            if (!$paiement) {
                $this->respond(['error' => 'Paiement introuvable'], 404);
                return;
            }
            
            if ($paiement['statut'] !== 'en_attente') {
                $this->respond(['error' => 'Seuls les paiements en attente peuvent être annulés'], 400);
                return;
            }
            
            // Marquer le paiement comme refusé (annulé)
            $db->execute(
                "UPDATE paiements SET statut = 'refuse', updated_at = NOW() WHERE id = :id",
                [':id' => $paiementId]
            );
            
            $this->respond([
                'success' => true,
                'message' => 'Paiement annulé avec succès'
            ], 200);
            
        } catch (\Exception $e) {
            error_log('Erreur lors de l\'annulation du paiement: ' . $e->getMessage());
            $this->respond(['error' => 'Erreur lors de l\'annulation du paiement'], 500);
        }
    }

    /**
     * Génère et télécharge un reçu pour un paiement
     */
    public function downloadReceipt($params = [])
    {
        $userId = $this->requireAuthorOrSubscribe();
        
        // Extraire l'ID du paiement depuis les paramètres de route
        // Gérer le cas où $params peut être un tableau ou directement l'ID
        $paiementId = is_array($params) ? ($params['id'] ?? null) : $params;
        
        if (!$paiementId) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'ID de paiement requis']);
            exit;
        }
        
        $db = $this->db();
        $userModel = new UserModel($db);
        
        // Récupérer le paiement
        $paiement = $db->fetchOne(
            "SELECT * FROM paiements WHERE id = :id AND utilisateur_id = :userId",
            [
                ':id' => $paiementId,
                ':userId' => $userId
            ]
        );
        
        if (!$paiement) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Paiement introuvable']);
            exit;
        }
        
        // Récupérer les informations de l'utilisateur
        $user = $userModel->getUserById($userId);
        if (!$user) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Utilisateur introuvable']);
            exit;
        }
        
        // Récupérer l'abonnement associé si disponible
        $abonnement = $db->fetchOne(
            "SELECT * FROM abonnements WHERE utilisateur_id = :userId ORDER BY created_at DESC LIMIT 1",
            [':userId' => $userId]
        );
        
        // Générer le reçu en HTML
        $html = $this->generateReceiptHTML($paiement, $user, $abonnement);
        
        // Si le reçu n'existe pas encore, le sauvegarder
        if (empty($paiement['recu_path'])) {
            $receiptDir = __DIR__ . '/../public/receipts/';
            if (!is_dir($receiptDir)) {
                mkdir($receiptDir, 0755, true);
            }
            
            $filename = 'recu_' . $paiementId . '_' . time() . '.html';
            $filepath = $receiptDir . $filename;
            file_put_contents($filepath, $html);
            
            // Mettre à jour le chemin du reçu dans la base de données
            $db->execute(
                "UPDATE paiements SET recu_path = :path, updated_at = NOW() WHERE id = :id",
                [
                    ':id' => $paiementId,
                    ':path' => 'receipts/' . $filename
                ]
            );
        }
        
        // Envoyer le reçu en HTML (peut être converti en PDF côté client ou serveur)
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="recu_paiement_' . $paiementId . '.html"');
        echo $html;
        exit;
    }
    
    /**
     * Génère le HTML du reçu
     */
    private function generateReceiptHTML($paiement, $user, $abonnement = null)
    {
        $datePaiement = $paiement['date_paiement'] ?? $paiement['created_at'];
        $formattedDate = date('d/m/Y à H:i', strtotime($datePaiement));
        $moyenPaiement = ucfirst(str_replace('_', ' ', $paiement['moyen']));
        $montant = number_format($paiement['montant'], 2, ',', ' ') . ' $';
        
        $html = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de paiement #' . htmlspecialchars($paiement['id']) . '</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #f5f5f5;
        }
        .receipt {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2563eb;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header p {
            color: #6b7280;
            font-size: 14px;
        }
        .receipt-info {
            margin-bottom: 30px;
        }
        .receipt-info h2 {
            color: #1f2937;
            font-size: 18px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .info-label {
            font-weight: 600;
            color: #6b7280;
        }
        .info-value {
            color: #1f2937;
            text-align: right;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-valide {
            background: #d1fae5;
            color: #065f46;
        }
        .status-en_attente {
            background: #fef3c7;
            color: #92400e;
        }
        .status-refuse {
            background: #fee2e2;
            color: #991b1b;
        }
        @media print {
            body { background: white; padding: 0; }
            .receipt { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>Revue Congolaise de Théologie protestante</h1>
            <p>Université Protestante au Congo</p>
            <p style="margin-top: 10px;">Reçu de paiement</p>
        </div>
        
        <div class="receipt-info">
            <h2>Informations du paiement</h2>
            <div class="info-row">
                <span class="info-label">Numéro de reçu:</span>
                <span class="info-value">#' . htmlspecialchars($paiement['id']) . '</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date du paiement:</span>
                <span class="info-value">' . htmlspecialchars($formattedDate) . '</span>
            </div>
            <div class="info-row">
                <span class="info-label">Montant:</span>
                <span class="info-value amount">' . htmlspecialchars($montant) . '</span>
            </div>
            <div class="info-row">
                <span class="info-label">Moyen de paiement:</span>
                <span class="info-value">' . htmlspecialchars($moyenPaiement) . '</span>
            </div>
            <div class="info-row">
                <span class="info-label">Statut:</span>
                <span class="info-value">
                    <span class="status-badge status-' . htmlspecialchars($paiement['statut']) . '">
                        ' . ucfirst(str_replace('_', ' ', $paiement['statut'])) . '
                    </span>
                </span>
            </div>';
        
        if (!empty($paiement['numero_transaction'])) {
            $html .= '
            <div class="info-row">
                <span class="info-label">Numéro de transaction:</span>
                <span class="info-value">' . htmlspecialchars($paiement['numero_transaction']) . '</span>
            </div>';
        }
        
        if (!empty($paiement['region'])) {
            $html .= '
            <div class="info-row">
                <span class="info-label">Région:</span>
                <span class="info-value">' . ucfirst(htmlspecialchars($paiement['region'])) . '</span>
            </div>';
        }
        
        $html .= '
        </div>
        
        <div class="receipt-info">
            <h2>Informations du client</h2>
            <div class="info-row">
                <span class="info-label">Nom:</span>
                <span class="info-value">' . htmlspecialchars($user['nom'] . ' ' . $user['prenom']) . '</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">' . htmlspecialchars($user['email']) . '</span>
            </div>
        </div>';
        
        if ($abonnement) {
            $dateDebut = date('d/m/Y', strtotime($abonnement['date_debut']));
            $dateFin = date('d/m/Y', strtotime($abonnement['date_fin']));
            $html .= '
        <div class="receipt-info">
            <h2>Informations de l\'abonnement</h2>
            <div class="info-row">
                <span class="info-label">Période:</span>
                <span class="info-value">Du ' . htmlspecialchars($dateDebut) . ' au ' . htmlspecialchars($dateFin) . '</span>
            </div>
            <div class="info-row">
                <span class="info-label">Statut:</span>
                <span class="info-value">
                    <span class="status-badge status-' . htmlspecialchars($abonnement['statut']) . '">
                        ' . ucfirst(str_replace('_', ' ', $abonnement['statut'])) . '
                    </span>
                </span>
            </div>
        </div>';
        }
        
        $html .= '
        <div class="footer">
            <p>Ce document est un reçu de paiement électronique.</p>
            <p>Pour toute question, contactez-nous à l\'adresse email de l\'université.</p>
            <p style="margin-top: 10px;">Généré le ' . date('d/m/Y à H:i') . '</p>
        </div>
    </div>
</body>
</html>';
        
        return $html;
    }
    
    /**
     * Affiche et gère le profil de l'auteur
     */
    public function profil()
    {
        $userId = $this->requireAuthor();
        
        $db = $this->db();
        $userModel = new UserModel($db);
        $articleModel = new ArticleModel($db);
        
        // Récupérer les informations de l'utilisateur
        $user = $userModel->getUserById($userId);
        if (!$user) {
            header('Location: ' . \Router\Router::route('login'));
            exit;
        }
        
        // Calculer les statistiques
        $allArticles = $articleModel->getArticlesByAuthor($userId, 1, 1000);
        $stats = [
            'total_articles' => count($allArticles),
            'publie' => 0
        ];
        
        foreach ($allArticles as $article) {
            $statut = strtolower($article['statut'] ?? '');
            if (in_array($statut, ['publié', 'publie', 'published', 'valide', 'validé', 'accepte', 'accepté', 'accepted'])) {
                $stats['publie']++;
            }
        }
        
        // Récupérer le nombre de notifications non lues
        $unreadCount = 0;
        try {
            $notificationModel = new \Models\NotificationModel($db);
            $unreadCount = $notificationModel->countUnread($userId);
        } catch (\Exception $e) {
            error_log('Erreur lors de la récupération des notifications: ' . $e->getMessage());
        }

        $data = [
            'user' => $user,
            'stats' => $stats,
            'unreadCount' => $unreadCount
        ];
        
        \App\App::view('author' . DIRECTORY_SEPARATOR . 'profil', $data);
    }
    
    /**
     * Affiche les détails d'un article
     */
    public function articleDetails($params = [])
    {
        $userId = $this->requireAuthor();
        
        $articleId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$articleId) {
            header('Location: ' . \Router\Router::route('author'));
            exit;
        }
        
        $db = $this->db();
        $articleModel = new ArticleModel($db);
        
        // Récupérer l'article
        $article = $articleModel->getArticleById($articleId);
        
        // Vérifier que l'article appartient à l'utilisateur connecté
        if (!$article || $article['auteur_id'] != $userId) {
            header('Location: ' . \Router\Router::route('author'));
            exit;
        }
        
        // Récupérer le nombre de notifications non lues
        $notificationModel = new \Models\NotificationModel($db);
        $unreadCount = $notificationModel->countUnread($userId);
        
        // Récupérer le nombre de révisions (si la table existe)
        $revisionCount = 0;
        try {
            $revisionModel = new \Models\RevisionModel($db);
            $revisionCount = $revisionModel->getRevisionCount($articleId);
        } catch (\Exception $e) {
            // Si la table n'existe pas encore, ignorer l'erreur
            error_log('Erreur lors de la récupération des révisions: ' . $e->getMessage());
        }
        
        // Récupérer les évaluations terminées avec commentaires publics
        $evaluations = [];
        $evaluationsEnCours = [];
        $hasRevisionRequest = false;
        try {
            $reviewModel = new \Models\ReviewModel($db);
            $evaluations = $reviewModel->getReviewsByArticle($articleId, true); // Seulement les terminées
            $evaluationsEnCours = $reviewModel->getReviewsByArticle($articleId, false); // Toutes les évaluations pour le workflow
            
            // Vérifier si des évaluations demandent des révisions
            foreach ($evaluations as $eval) {
                $rec = strtolower($eval['recommendation'] ?? '');
                if (in_array($rec, ['revision_majeure', 'accepte_avec_modifications'])) {
                    $hasRevisionRequest = true;
                    break;
                }
            }
        } catch (\Exception $e) {
            error_log('Erreur lors de la récupération des évaluations: ' . $e->getMessage());
        }
        
        $data = [
            'article' => $article,
            'user' => (new UserModel($db))->getUserById($userId),
            'unreadCount' => $unreadCount,
            'revisionCount' => $revisionCount,
            'evaluations' => $evaluations,
            'evaluationsEnCours' => $evaluationsEnCours,
            'hasRevisionRequest' => $hasRevisionRequest
        ];
        
        \App\App::view('author' . DIRECTORY_SEPARATOR . 'article-details', $data);
    }
    
    /**
     * Affiche le formulaire d'édition d'un article
     */
    public function articleEdit($params = [])
    {
        $userId = $this->requireAuthor();
        
        $articleId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$articleId) {
            header('Location: ' . \Router\Router::route('author'));
            exit;
        }
        
        $db = $this->db();
        $articleModel = new ArticleModel($db);
        
        // Récupérer l'article
        $article = $articleModel->getArticleById($articleId);
        
        // Vérifier que l'article appartient à l'utilisateur connecté et qu'il peut être modifié
        if (!$article || $article['auteur_id'] != $userId) {
            header('Location: ' . \Router\Router::route('author'));
            exit;
        }
        
        // Vérifier que l'article peut être modifié (seulement si statut = soumis)
        $statut = strtolower($article['statut'] ?? '');
        if ($statut !== 'soumis') {
            header('Location: ' . \Router\Router::route('author') . '/article/' . $articleId);
            exit;
        }
        
        $data = [
            'article' => $article,
            'user' => (new UserModel($db))->getUserById($userId)
        ];
        
        \App\App::view('author' . DIRECTORY_SEPARATOR . 'article-edit', $data);
    }
    
    /**
     * Met à jour un article
     */
    public function articleUpdate($params = [])
    {
        try {
            $userId = $this->requireAuthor();
            
            $articleId = is_array($params) ? ($params['id'] ?? null) : $params;
            if (!$articleId) {
                $this->respond(['error' => 'ID d\'article manquant'], 400);
                return;
            }
            
            $db = $this->db();
            $articleModel = new ArticleModel($db);
            
            // Récupérer l'article
            $article = $articleModel->getArticleById($articleId);
            
            // Vérifier que l'article appartient à l'utilisateur connecté
            if (!$article || (int)$article['auteur_id'] !== (int)$userId) {
                $this->respond(['error' => 'Article introuvable ou non autorisé'], 403);
                return;
            }
            
        // Vérifier que l'article peut être modifié (seulement si statut = soumis ou revision_requise)
        $statut = strtolower($article['statut'] ?? '');
        if ($statut !== 'soumis' && strpos($statut, 'revision') === false) {
            $this->respond(['error' => 'Cet article ne peut plus être modifié. Seuls les articles avec le statut "soumis" ou "révisions requises" peuvent être modifiés.'], 400);
            return;
        }
            
            // Récupérer les données du formulaire (peuvent venir de $_POST ou de $this->input())
            $inputData = $this->input();
            $postData = $_POST ?? [];
            
            // Préparer les données de mise à jour
            $updateData = [];
            
            // Titre
            if (isset($inputData['titre']) || isset($postData['titre'])) {
                $titre = trim($inputData['titre'] ?? $postData['titre'] ?? '');
                if (empty($titre)) {
                    $this->respond(['error' => 'Le titre est requis'], 400);
                    return;
                }
                $updateData['titre'] = $titre;
            }
            
            // Contenu
            if (isset($inputData['contenu']) || isset($postData['contenu'])) {
                $contenu = trim($inputData['contenu'] ?? $postData['contenu'] ?? '');
                if (empty($contenu)) {
                    $this->respond(['error' => 'Le résumé est requis'], 400);
                    return;
                }
                $updateData['contenu'] = $contenu;
            }
            
            // Gérer l'upload du fichier si un nouveau fichier est fourni
            if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'articles' . DIRECTORY_SEPARATOR;
                
                if (!is_dir($uploadDir)) {
                    if (!mkdir($uploadDir, 0755, true)) {
                        $this->respond(['error' => 'Impossible de créer le répertoire d\'upload'], 500);
                        return;
                    }
                }
                
                $file = $_FILES['fichier'];
                $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['pdf', 'doc', 'docx', 'tex'];
                
                if (!in_array($fileExtension, $allowedExtensions)) {
                    $this->respond(['error' => 'Format de fichier non autorisé. Formats acceptés : PDF, Word (.doc, .docx), LaTeX (.tex)'], 400);
                    return;
                }
                
                // Vérifier la taille du fichier (max 10MB)
                $maxFileSize = 10 * 1024 * 1024; // 10MB
                if ($file['size'] > $maxFileSize) {
                    $this->respond(['error' => 'Le fichier est trop volumineux. Taille maximale : 10MB'], 400);
                    return;
                }
                
                // Supprimer l'ancien fichier s'il existe
                if (!empty($article['fichier_path'])) {
                    $oldFilePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $article['fichier_path'];
                    if (file_exists($oldFilePath)) {
                        @unlink($oldFilePath);
                    }
                }
                
                // Générer un nom de fichier unique
                $fileName = uniqid('article_', true) . '_' . time() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;
                
                // Déplacer le fichier
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    $updateData['fichier_path'] = 'uploads/articles/' . $fileName;
                } else {
                    $this->respond(['error' => 'Erreur lors de l\'upload du fichier'], 500);
                    return;
                }
            }
            
            // Vérifier qu'il y a au moins une donnée à mettre à jour
            if (empty($updateData)) {
                $this->respond(['error' => 'Aucune donnée à mettre à jour'], 400);
                return;
            }
            
            // Mettre à jour l'article
            $success = $articleModel->updateArticle($articleId, $updateData);
            
            if ($success) {
                // Si l'article était en "revision_requise", gérer la resoumission
                if (strpos($statut, 'revision') !== false) {
                    // Récupérer tous les évaluateurs qui ont déjà évalué cet article (historique complet)
                    $reviewModel = new \Models\ReviewModel($db);
                    $allEvaluations = $reviewModel->getReviewsByArticle($articleId, false);
                    
                    // Extraire les IDs uniques des évaluateurs
                    $evaluatorIds = [];
                    $existingEvaluations = [];
                    foreach ($allEvaluations as $eval) {
                        $evaluatorId = $eval['evaluateur_id'] ?? null;
                        if ($evaluatorId && !in_array($evaluatorId, $evaluatorIds)) {
                            $evaluatorIds[] = $evaluatorId;
                            $existingEvaluations[$evaluatorId] = $eval;
                        }
                    }
                    
                    $reassignedCount = 0;
                    
                    // Réassigner automatiquement tous les évaluateurs précédents
                    if (!empty($evaluatorIds)) {
                        foreach ($evaluatorIds as $evaluatorId) {
                            $eval = $existingEvaluations[$evaluatorId] ?? null;
                            
                            if ($eval && isset($eval['id'])) {
                                // Évaluation existante : la réinitialiser
                                $db->execute("
                                    UPDATE evaluations 
                                    SET statut = 'en_attente',
                                        date_assignation = NOW(),
                                        date_echeance = DATE_ADD(NOW(), INTERVAL 14 DAY),
                                        recommendation = NULL,
                                        qualite_scientifique = NULL,
                                        originalite = NULL,
                                        pertinence = NULL,
                                        clarte = NULL,
                                        note_finale = NULL,
                                        commentaires_public = NULL,
                                        commentaires_prives = NULL,
                                        suggestions = NULL,
                                        date_soumission = NULL,
                                        updated_at = NOW()
                                    WHERE id = :id
                                ", [':id' => $eval['id']]);
                                
                                $evaluationId = $eval['id'];
                            } else {
                                // Pas d'évaluation existante : en créer une nouvelle
                                $deadline = date('Y-m-d', strtotime('+14 days'));
                                $db->execute("
                                    INSERT INTO evaluations (article_id, evaluateur_id, statut, date_assignation, date_echeance, created_at, updated_at)
                                    VALUES (:article_id, :evaluateur_id, 'en_attente', NOW(), :date_echeance, NOW(), NOW())
                                ", [
                                    ':article_id' => $articleId,
                                    ':evaluateur_id' => $evaluatorId,
                                    ':date_echeance' => $deadline
                                ]);
                                
                                $evaluationId = $db->lastInsertId();
                            }
                            
                            // Notifier l'évaluateur que l'article a été resoumis et réassigné
                            try {
                                $notificationModel = new \Models\NotificationModel($db);
                                $notificationModel->createNotification(
                                    $evaluatorId,
                                    'article_resubmitted',
                                    'Article resoumis pour réévaluation',
                                    "L'article \"" . htmlspecialchars($article['titre']) . "\" a été modifié et resoumis par l'auteur. Il vous a été automatiquement réassigné pour une nouvelle évaluation.",
                                    $articleId,
                                    $evaluationId
                                );
                            } catch (\Exception $e) {
                                error_log('Erreur notification évaluateur: ' . $e->getMessage());
                            }
                            
                            $reassignedCount++;
                        }
                        
                        // Mettre le statut à "en_evaluation" car des évaluateurs sont assignés
                        $articleModel->changeArticleStatus($articleId, 'en_evaluation');
                        
                        // Créer une entrée de révision
                        try {
                            $revisionModel = new \Models\RevisionModel($db);
                            $revisionModel->createRevision(
                                $articleId,
                                'revision_requise',
                                'en_evaluation',
                                'Article resoumis après révisions par l\'auteur. ' . $reassignedCount . ' évaluateur(s) automatiquement réassigné(s) pour réévaluation.'
                            );
                        } catch (\Exception $e) {
                            error_log('Erreur création révision: ' . $e->getMessage());
                        }
                    } else {
                        // Pas d'évaluateurs précédents, remettre en "soumis"
                        $articleModel->changeArticleStatus($articleId, 'soumis');
                        
                        // Créer une entrée de révision
                        try {
                            $revisionModel = new \Models\RevisionModel($db);
                            $revisionModel->createRevision(
                                $articleId,
                                'revision_requise',
                                'soumis',
                                'Article resoumis après révisions par l\'auteur. En attente d\'assignation d\'évaluateurs.'
                            );
                        } catch (\Exception $e) {
                            error_log('Erreur création révision: ' . $e->getMessage());
                        }
                    }
                    
                    // Notifier l'admin
                    try {
                        $notificationModel = new \Models\NotificationModel($db);
                        // Trouver l'admin (premier utilisateur avec rôle admin)
                        $admin = $db->fetchOne("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
                        if ($admin) {
                            $user = $userModel->getUserById($userId);
                            $notificationModel->createNotification(
                                $admin['id'],
                                'article_resubmitted',
                                'Article resoumis',
                                "L'article \"" . htmlspecialchars($article['titre']) . "\" a été modifié et resoumis par l'auteur " . htmlspecialchars(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) . ".",
                                $articleId,
                                null
                            );
                        }
                    } catch (\Exception $e) {
                        error_log('Erreur notification admin: ' . $e->getMessage());
                    }
                }
                
                // Préparer le message de réponse
                $message = 'Article modifié avec succès';
                if (strpos($statut, 'revision') !== false) {
                    if (isset($reassignedCount) && $reassignedCount > 0) {
                        $message = "Article modifié et resoumis avec succès. {$reassignedCount} évaluateur(s) automatiquement réassigné(s) et notifié(s).";
                    } else {
                        $message = 'Article modifié et resoumis avec succès.';
                    }
                }
                
                $this->respond([
                    'success' => true, 
                    'message' => $message
                ], 200);
            } else {
                $this->respond(['error' => 'Erreur lors de la modification de l\'article'], 500);
            }
        } catch (\Exception $e) {
            error_log('Erreur articleUpdate: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            $this->respond([
                'error' => 'Une erreur est survenue lors de la modification de l\'article',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Supprime un article
     */
    public function articleDelete($params = [])
    {
        $userId = $this->requireAuthor();
        
        $articleId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$articleId) {
            $this->respond(['error' => 'ID d\'article manquant'], 400);
            return;
        }
        
        $db = $this->db();
        $articleModel = new ArticleModel($db);
        
        // Récupérer l'article
        $article = $articleModel->getArticleById($articleId);
        
        // Vérifier que l'article appartient à l'utilisateur connecté
        if (!$article || $article['auteur_id'] != $userId) {
            $this->respond(['error' => 'Article introuvable ou non autorisé'], 403);
            return;
        }
        
        // Vérifier que l'article peut être supprimé (seulement si statut = soumis)
        $statut = strtolower($article['statut'] ?? '');
        if ($statut !== 'soumis') {
            $this->respond(['error' => 'Cet article ne peut plus être supprimé'], 400);
            return;
        }
        
        // Supprimer le fichier associé s'il existe
        if (!empty($article['fichier_path'])) {
            $filePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $article['fichier_path'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        
        // Supprimer l'article
        $success = $articleModel->deleteArticle($articleId);
        
        if ($success) {
            $this->respond(['success' => true, 'message' => 'Article supprimé avec succès'], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la suppression de l\'article'], 500);
        }
    }
    
    /**
     * Affiche les notifications de l'auteur
     */
    public function notifications()
    {
        $userId = $this->requireAuthor();
        
        $db = $this->db();
        $userModel = new UserModel($db);
        $notificationModel = new \Models\NotificationModel($db);
        
        $user = $userModel->getUserById($userId);
        if (!$user) {
            header('Location: ' . \Router\Router::route('login'));
            exit;
        }
        
        $notifications = $notificationModel->getUserNotifications($userId, false, 100);
        $unreadCount = $notificationModel->countUnread($userId);
        
        // Calculer les statistiques pour la sidebar
        $allArticles = (new ArticleModel($db))->getArticlesByAuthor($userId, 1, 1000);
        $stats = ['total' => count($allArticles)];
        
        $data = [
            'user' => $user,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'stats' => $stats,
            'currentPage' => 'notifications'
        ];
        
        \App\App::view('author' . DIRECTORY_SEPARATOR . 'notifications', $data);
    }
    
    /**
     * Marque une notification comme lue
     */
    public function markNotificationRead($params = [])
    {
        $userId = $this->requireAuthor();
        
        $notificationId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$notificationId) {
            $this->respond(['error' => 'ID de notification manquant'], 400);
            return;
        }
        
        $db = $this->db();
        $notificationModel = new \Models\NotificationModel($db);
        
        $success = $notificationModel->markAsRead($notificationId, $userId);
        
        if ($success) {
            $this->respond(['success' => true, 'message' => 'Notification marquée comme lue'], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }
    
    /**
     * Marque toutes les notifications comme lues
     */
    public function markAllNotificationsRead()
    {
        $userId = $this->requireAuthor();
        
        $db = $this->db();
        $notificationModel = new \Models\NotificationModel($db);
        
        $success = $notificationModel->markAllAsRead($userId);
        
        if ($success) {
            $this->respond(['success' => true, 'message' => 'Toutes les notifications ont été marquées comme lues'], 200);
        } else {
            $this->respond(['error' => 'Erreur lors de la mise à jour'], 500);
        }
    }
    
    /**
     * Affiche les révisions d'un article
     */
    public function articleRevisions($params = [])
    {
        $userId = $this->requireAuthor();
        
        $articleId = is_array($params) ? ($params['id'] ?? null) : $params;
        if (!$articleId) {
            header('Location: ' . \Router\Router::route('author'));
            exit;
        }
        
        $db = $this->db();
        $articleModel = new ArticleModel($db);
        $userModel = new UserModel($db);
        
        $article = $articleModel->getArticleById($articleId);
        
        // Vérifier que l'article appartient à l'utilisateur
        if (!$article || $article['auteur_id'] != $userId) {
            header('Location: ' . \Router\Router::route('author'));
            exit;
        }
        
        // Récupérer les révisions (si la table existe)
        $revisions = [];
        $revisionCount = 0;
        try {
            $revisionModel = new \Models\RevisionModel($db);
            $revisions = $revisionModel->getArticleRevisions($articleId);
            $revisionCount = $revisionModel->getRevisionCount($articleId);
        } catch (\Exception $e) {
            // Si la table n'existe pas encore, ignorer l'erreur
            error_log('Erreur lors de la récupération des révisions: ' . $e->getMessage());
        }
        
        $data = [
            'article' => $article,
            'revisions' => $revisions,
            'revisionCount' => $revisionCount,
            'user' => $userModel->getUserById($userId)
        ];
        
        \App\App::view('author' . DIRECTORY_SEPARATOR . 'article-revisions', $data);
    }
    
    /**
     * Formate le statut pour l'affichage
     */
    private function formatStatut($statut)
    {
        $statut = strtolower($statut);
        $statuts = [
            'soumis' => 'Soumis',
            'en évaluation' => 'En évaluation',
            'en_evaluation' => 'En évaluation',
            'en evaluation' => 'En évaluation',
            'revision_requise' => 'Révisions requises',
            'accepté' => 'Accepté',
            'accepte' => 'Accepté',
            'accepted' => 'Accepté',
            'publié' => 'Publié',
            'publie' => 'Publié',
            'published' => 'Publié',
            'rejeté' => 'Rejeté',
            'rejete' => 'Rejeté',
            'rejected' => 'Rejeté'
        ];
        
        return $statuts[$statut] ?? ucfirst($statut);
    }
}

