<?php
namespace Models;

/**
 * Modèle abonnements (table abonnements).
 */
class AbonnementModel
{
    /** Abonnement actif de l'utilisateur (statut actif et date_fin >= aujourd'hui) */
    public static function getActiveByUserId(int $userId): ?array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT id, utilisateur_id, date_debut, date_fin, statut, created_at
            FROM abonnements
            WHERE utilisateur_id = :uid AND statut = 'actif'
            AND (date_fin IS NULL OR date_fin >= CURDATE())
            ORDER BY date_fin DESC LIMIT 1
        ");
        $stmt->execute([':uid' => $userId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Tous les abonnements de l'utilisateur (historique) */
    public static function getByUserId(int $userId, int $limit = 20): array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT id, utilisateur_id, date_debut, date_fin, statut, created_at
            FROM abonnements WHERE utilisateur_id = :uid ORDER BY created_at DESC LIMIT :limit
        ");
        $stmt->bindValue(':uid', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** Vérifier si l'utilisateur a un abonnement actif */
    public static function hasActiveSubscription(int $userId): bool
    {
        return self::getActiveByUserId($userId) !== null;
    }

    /** Créer un abonnement (1 an à partir de aujourd'hui, statut actif). */
    public static function create(int $utilisateurId): ?int
    {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO abonnements (utilisateur_id, date_debut, date_fin, statut, created_at, updated_at)
            VALUES (:uid, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 'actif', NOW(), NOW())
        ");
        $ok = $stmt->execute([':uid' => $utilisateurId]);
        return $ok ? (int) $db->lastInsertId() : null;
    }

    /** Résilier un abonnement (passer le statut à 'expire'). L'abonnement doit appartenir à l'utilisateur et être actif. */
    public static function cancel(int $abonnementId, int $userId): bool
    {
        $db = getDB();
        $stmt = $db->prepare("
            UPDATE abonnements
            SET statut = 'expire'
            WHERE id = :id AND utilisateur_id = :uid AND statut = 'actif'
        ");
        $stmt->execute([':id' => $abonnementId, ':uid' => $userId]);
        return $stmt->rowCount() > 0;
    }
}
