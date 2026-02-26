<?php
namespace Models;

/**
 * Modèle paiements (table paiements).
 */
class PaiementModel
{
    /** Paiements d'un utilisateur (historique). Inclut region si la colonne existe (migration add_paiement_region_details). */
    public static function getByUserId(int $userId, int $limit = 20): array
    {
        $db = getDB();
        $base = 'id, utilisateur_id, montant, moyen, recu_path, statut, date_paiement, created_at';
        $order = 'ORDER BY COALESCE(date_paiement, created_at) DESC, created_at DESC LIMIT :limit';
        try {
            $stmt = $db->prepare("SELECT {$base}, region FROM paiements WHERE utilisateur_id = :uid {$order}");
            $stmt->bindValue(':uid', $userId, \PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            $stmt = $db->prepare("SELECT {$base} FROM paiements WHERE utilisateur_id = :uid {$order}");
            $stmt->bindValue(':uid', $userId, \PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as &$r) {
                $r['region'] = '';
            }
            return $rows;
        }
    }

    /** Vérifie si l'utilisateur a déjà un paiement en attente (une seule demande à la fois). */
    public static function hasPendingByUserId(int $userId): bool
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT 1 FROM paiements WHERE utilisateur_id = :uid AND statut = 'en_attente' LIMIT 1");
        $stmt->execute([':uid' => $userId]);
        return (bool) $stmt->fetch();
    }

    /** Un paiement par id (admin). */
    public static function getById(int $id): ?array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT p.*, u.nom AS user_nom, u.prenom AS user_prenom, u.email AS user_email
            FROM paiements p
            LEFT JOIN users u ON u.id = p.utilisateur_id
            WHERE p.id = :id LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
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
            ORDER BY COALESCE(p.date_paiement, p.created_at) DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** Créer un paiement (demande d'abonnement auteur). */
    public static function create(int $utilisateurId, float $montant, string $moyen = 'en_attente', string $statut = 'en_attente'): ?int
    {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO paiements (utilisateur_id, montant, moyen, statut, date_paiement, created_at, updated_at)
            VALUES (:uid, :montant, :moyen, :statut, NOW(), NOW(), NOW())
        ");
        $ok = $stmt->execute([
            ':uid' => $utilisateurId,
            ':montant' => $montant,
            ':moyen' => $moyen,
            ':statut' => $statut,
        ]);
        return $ok ? (int) $db->lastInsertId() : null;
    }

    /** Créer une demande d'abonnement (paiement en_attente, date_paiement NULL). Optionnel : region, payment_details (JSON). */
    public static function createDemandeAbonnement(int $utilisateurId, float $montant, string $moyen, ?string $region = null, ?string $paymentDetailsJson = null): ?int
    {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO paiements (utilisateur_id, montant, moyen, statut, date_paiement, created_at, updated_at)
            VALUES (:uid, :montant, :moyen, 'en_attente', NULL, NOW(), NOW())
        ");
        $ok = $stmt->execute([
            ':uid' => $utilisateurId,
            ':montant' => $montant,
            ':moyen' => $moyen,
        ]);
        if (!$ok) {
            return null;
        }
        $id = (int) $db->lastInsertId();
        if ($id && ($region !== null || $paymentDetailsJson !== null)) {
            try {
                $up = $db->prepare("UPDATE paiements SET region = :region, payment_details = :details WHERE id = :id");
                $up->execute([
                    ':region' => $region,
                    ':details' => $paymentDetailsJson,
                    ':id' => $id,
                ]);
            } catch (\Throwable $e) {
                // Colonnes optionnelles (migration non exécutée)
            }
        }
        return $id;
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

    /** Valider un paiement (simulation : statut valide + date_paiement = maintenant). */
    public static function setValide(int $id): bool
    {
        $db = getDB();
        $stmt = $db->prepare("UPDATE paiements SET statut = 'valide', date_paiement = NOW(), updated_at = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /** Revenus du mois en cours (paiements validés) */
    public static function getMonthlyTotal(): float
    {
        $db = getDB();
        $stmt = $db->query("SELECT COALESCE(SUM(montant), 0) FROM paiements WHERE statut = 'valide' AND date_paiement >= DATE_FORMAT(NOW(), '%Y-%m-01')");
        return (float) $stmt->fetchColumn();
    }

    /** Annuler un paiement en attente (statut → refuse). Réservé à l'utilisateur propriétaire. */
    public static function cancelEnAttente(int $id, int $userId): bool
    {
        $db = getDB();
        $stmt = $db->prepare("UPDATE paiements SET statut = 'refuse', updated_at = NOW() WHERE id = :id AND utilisateur_id = :uid AND statut = 'en_attente'");
        return $stmt->execute([':id' => $id, ':uid' => $userId]) && $stmt->rowCount() > 0;
    }

    /** Récupérer un paiement par id et utilisateur (pour reçu). */
    public static function getByIdAndUser(int $id, int $userId): ?array
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, utilisateur_id, montant, moyen, statut, date_paiement, created_at FROM paiements WHERE id = :id AND utilisateur_id = :uid LIMIT 1");
        $stmt->execute([':id' => $id, ':uid' => $userId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
