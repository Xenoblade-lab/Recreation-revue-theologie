<?php
namespace Models;

/**
 * Modèle paiements (table paiements).
 */
class PaiementModel
{
    /** Paiements d'un utilisateur (historique) */
    public static function getByUserId(int $userId, int $limit = 20): array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT id, utilisateur_id, montant, moyen, recu_path, statut, date_paiement, created_at
            FROM paiements WHERE utilisateur_id = :uid ORDER BY date_paiement DESC, created_at DESC LIMIT :limit
        ");
        $stmt->bindValue(':uid', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** Tous les paiements (admin) */
    public static function getAll(int $limit = 50, int $offset = 0): array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT p.id, p.utilisateur_id, p.montant, p.moyen, p.statut, p.date_paiement, p.created_at,
                   u.nom AS user_nom, u.prenom AS user_prenom, u.email AS user_email
            FROM paiements p
            LEFT JOIN users u ON u.id = p.utilisateur_id
            ORDER BY p.date_paiement DESC, p.created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** Mettre à jour le statut d'un paiement (admin) */
    public static function updateStatut(int $id, string $statut): bool
    {
        if (!in_array($statut, ['en_attente', 'valide', 'refuse'], true)) {
            return false;
        }
        $db = getDB();
        $stmt = $db->prepare("UPDATE paiements SET statut = :statut, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $id, ':statut' => $statut]);
    }

    /** Revenus du mois en cours (paiements validés) */
    public static function getMonthlyTotal(): float
    {
        $db = getDB();
        $stmt = $db->query("SELECT COALESCE(SUM(montant), 0) FROM paiements WHERE statut = 'valide' AND date_paiement >= DATE_FORMAT(NOW(), '%Y-%m-01')");
        return (float) $stmt->fetchColumn();
    }
}
