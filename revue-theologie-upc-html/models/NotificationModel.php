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

    /** Marquer une notification comme lue (si elle appartient à l'utilisateur) */
    public static function markAsRead(string $id, int $userId): bool
    {
        $db = getDB();
        $stmt = $db->prepare("UPDATE notifications SET read_at = NOW() WHERE id = :id AND notifiable_id = :uid AND read_at IS NULL");
        $stmt->execute([':id' => $id, ':uid' => $userId]);
        return $stmt->rowCount() > 0;
    }

    /** Marquer toutes les notifications comme lues pour un utilisateur */
    public static function markAllAsRead(int $userId): bool
    {
        $db = getDB();
        $stmt = $db->prepare("UPDATE notifications SET read_at = NOW() WHERE notifiable_id = :uid AND read_at IS NULL");
        $stmt->execute([':uid' => $userId]);
        return true;
    }

    /** Créer une notification pour un utilisateur. $data = ['message' => ..., 'link' => ... (optionnel)] */
    public static function create(int $userId, string $type, array $data = []): ?string
    {
        $db = getDB();
        $id = sprintf('%08x-%04x-%04x-%04x-%012x', mt_rand(0, 0xffffffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0xffffffffffff));
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $stmt = $db->prepare("INSERT INTO notifications (id, type, notifiable_type, notifiable_id, data, created_at, updated_at) VALUES (:id, :type, 'App\\\\Models\\\\User', :uid, :data, NOW(), NOW())");
        $ok = $stmt->execute([
            ':id' => $id,
            ':type' => $type,
            ':uid' => $userId,
            ':data' => $json,
        ]);
        return $ok ? $id : null;
    }
}
