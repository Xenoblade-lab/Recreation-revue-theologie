<?php
namespace Models;

class NotificationModel {
    private $db;

    public function __construct(\Models\Database $db) {
        $this->db = $db;
    }

    /**
     * Créer une notification
     */
    public function createNotification($userId, $type, $title, $message, $relatedArticleId = null, $relatedEvaluationId = null) {
        try {
            // Vérifier d'abord si la colonne related_article_id existe
            $columns = $this->db->fetchAll("SHOW COLUMNS FROM notifications LIKE 'related_article_id'");
            
            if (empty($columns)) {
                error_log('Table notifications n\'a pas la structure attendue. Veuillez exécuter la migration transform_notifications_table.sql');
                return false;
            }
            
            $sql = "INSERT INTO notifications (user_id, type, title, message, related_article_id, related_evaluation_id, created_at, updated_at) 
                    VALUES (:user_id, :type, :title, :message, :related_article_id, :related_evaluation_id, NOW(), NOW())";
            
            $params = [
                ':user_id' => $userId,
                ':type' => $type,
                ':title' => $title,
                ':message' => $message,
                ':related_article_id' => $relatedArticleId,
                ':related_evaluation_id' => $relatedEvaluationId
            ];
            
            $this->db->execute($sql, $params);
            return $this->db->lastInsertId();
        } catch (\Exception $e) {
            error_log('Erreur createNotification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer les notifications d'un utilisateur
     */
    public function getUserNotifications($userId, $unreadOnly = false, $limit = 50) {
        try {
            // Vérifier d'abord si la colonne related_article_id existe
            $columns = $this->db->fetchAll("SHOW COLUMNS FROM notifications LIKE 'related_article_id'");
            
            if (empty($columns)) {
                // Ancienne structure Laravel - retourner un tableau vide pour l'instant
                // L'utilisateur devra exécuter la migration
                error_log('Table notifications n\'a pas la structure attendue. Veuillez exécuter la migration transform_notifications_table.sql');
                return [];
            }
            
            $sql = "SELECT n.*, 
                           a.titre as article_titre,
                           n.related_article_id,
                           n.related_evaluation_id
                    FROM notifications n
                    LEFT JOIN articles a ON n.related_article_id = a.id
                    WHERE n.user_id = :user_id";
            
            $params = [':user_id' => $userId];
            
            if ($unreadOnly) {
                $sql .= " AND n.is_read = 0";
            }
            
            $sql .= " ORDER BY n.created_at DESC LIMIT " . (int)$limit;
            
            $notifications = $this->db->fetchAll($sql, $params);
            
            // Ajouter les liens dynamiques selon le type de notification
            foreach ($notifications as &$notification) {
                if ($notification['related_evaluation_id'] && $notification['type'] === 'article_resubmitted') {
                    // Pour les évaluateurs, lien vers la page d'évaluation
                    $notification['link'] = '/reviewer/evaluation/' . $notification['related_evaluation_id'];
                } elseif ($notification['related_article_id']) {
                    // Pour les admins et auteurs, lien vers la page de détails de l'article
                    if (strpos($notification['type'], 'admin') !== false || $notification['type'] === 'article_resubmitted') {
                        $notification['link'] = '/admin/article/' . $notification['related_article_id'];
                    } else {
                        $notification['link'] = '/author/article/' . $notification['related_article_id'];
                    }
                }
            }
            
            return $notifications;
        } catch (\Exception $e) {
            error_log('Erreur getUserNotifications: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($notificationId, $userId) {
        try {
            // Vérifier d'abord si la colonne user_id existe
            $columns = $this->db->fetchAll("SHOW COLUMNS FROM notifications LIKE 'user_id'");
            
            if (empty($columns)) {
                return false;
            }
            
            $sql = "UPDATE notifications 
                    SET is_read = 1, read_at = NOW() 
                    WHERE id = :id AND user_id = :user_id";
            
            return $this->db->execute($sql, [
                ':id' => $notificationId,
                ':user_id' => $userId
            ]);
        } catch (\Exception $e) {
            error_log('Erreur markAsRead: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead($userId) {
        try {
            // Vérifier d'abord si la colonne user_id existe
            $columns = $this->db->fetchAll("SHOW COLUMNS FROM notifications LIKE 'user_id'");
            
            if (empty($columns)) {
                return false;
            }
            
            $sql = "UPDATE notifications 
                    SET is_read = 1, read_at = NOW() 
                    WHERE user_id = :user_id AND is_read = 0";
            
            return $this->db->execute($sql, [':user_id' => $userId]);
        } catch (\Exception $e) {
            error_log('Erreur markAllAsRead: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Compter les notifications non lues
     */
    public function countUnread($userId) {
        try {
            // Vérifier d'abord si la colonne user_id existe
            $columns = $this->db->fetchAll("SHOW COLUMNS FROM notifications LIKE 'user_id'");
            
            if (empty($columns)) {
                // Ancienne structure Laravel - retourner 0
                return 0;
            }
            
            $sql = "SELECT COUNT(*) as count 
                    FROM notifications 
                    WHERE user_id = :user_id AND is_read = 0";
            
            $result = $this->db->fetchOne($sql, [':user_id' => $userId]);
            return $result['count'] ?? 0;
        } catch (\Exception $e) {
            // Si la table n'existe pas encore, retourner 0
            error_log('Erreur countUnread: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Notifier l'auteur d'un changement de statut
     */
    public function notifyArticleStatusChange($articleId, $newStatus, $reason = null) {
        // Récupérer l'auteur de l'article
        $article = $this->db->fetchOne(
            "SELECT auteur_id, titre FROM articles WHERE id = :id",
            [':id' => $articleId]
        );
        
        if (!$article) {
            return false;
        }
        
        $statusLabels = [
            'soumis' => 'Soumis',
            'en_evaluation' => 'En évaluation',
            'revision_requise' => 'Révisions requises',
            'accepte' => 'Accepté',
            'rejete' => 'Rejeté',
            'publie' => 'Publié'
        ];
        
        $statusLabel = $statusLabels[$newStatus] ?? ucfirst($newStatus);
        
        $title = "Statut de votre article mis à jour";
        $message = "Votre article \"" . htmlspecialchars($article['titre']) . "\" a été mis à jour avec le statut : " . $statusLabel;
        
        if ($reason) {
            $message .= "\n\nRaison : " . htmlspecialchars($reason);
        }
        
        return $this->createNotification(
            $article['auteur_id'],
            'article_status_change',
            $title,
            $message,
            $articleId
        );
    }
}

