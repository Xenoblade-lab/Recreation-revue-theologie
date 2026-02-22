<?php
namespace Models;

/**
 * Modèle notifications (table notifications).
 * notifiable_type = 'App\Models\User', notifiable_id = user_id.
 */
class NotificationModel
{
    /** Notifications pour un utilisateur (notifiable_id = user_id) */
    public static function getByUserId(int $userId, int $limit = 50): array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT id, type, data, read_at, created_at
            FROM notifications
            WHERE notifiable_id = :uid
            ORDER BY created_at DESC LIMIT :limit
        ");
        $stmt->bindValue(':uid', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** Nombre de notifications non lues */
    public static function countUnreadByUserId(int $userId): int
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM notifications WHERE notifiable_id = :uid AND read_at IS NULL");
        $stmt->execute([':uid' => $userId]);
        return (int) $stmt->fetchColumn();
    }
}
