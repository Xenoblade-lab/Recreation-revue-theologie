<?php
namespace Models;

/**
 * Modèle comité éditorial (table comite_editorial).
 * Option B : liste explicite des membres pouvant être assignés comme évaluateurs.
 */
class ComiteEditorialModel
{
    /** Liste des membres avec infos utilisateur (pour la page admin). */
    public static function getAllWithUsers(): array
    {
        $db = getDB();
        $stmt = $db->query("
            SELECT c.id, c.user_id, c.ordre, c.titre_affiche, c.actif, c.created_at, c.updated_at,
                   u.nom, u.prenom, u.email, u.role
            FROM comite_editorial c
            INNER JOIN users u ON u.id = c.user_id
            ORDER BY c.ordre ASC, c.id ASC
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** Utilisateurs à proposer comme évaluateurs (actif = 1, avec id, nom, prenom, email). */
    public static function getActiveReviewers(): array
    {
        $db = getDB();
        $stmt = $db->query("
            SELECT u.id, u.nom, u.prenom, u.email
            FROM comite_editorial c
            INNER JOIN users u ON u.id = c.user_id
            WHERE c.actif = 1 AND (u.role = 'redacteur' OR u.role = 'redacteur en chef')
            ORDER BY c.ordre ASC, c.id ASC
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** Un membre par id. */
    public static function getById(int $id): ?array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT c.id, c.user_id, c.ordre, c.titre_affiche, c.actif, c.created_at, c.updated_at,
                   u.nom, u.prenom, u.email, u.role
            FROM comite_editorial c
            INNER JOIN users u ON u.id = c.user_id
            WHERE c.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Ids des user_id déjà dans le comité (pour exclure du formulaire d'ajout). */
    public static function getAllUserIds(): array
    {
        $db = getDB();
        $stmt = $db->query("SELECT user_id FROM comite_editorial");
        return array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'user_id');
    }

    /** Ajouter un membre. user_id doit être unique. */
    public static function create(int $userId, int $ordre = 0, ?string $titreAffiche = null, bool $actif = true): ?int
    {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO comite_editorial (user_id, ordre, titre_affiche, actif, created_at, updated_at)
            VALUES (:user_id, :ordre, :titre_affiche, :actif, NOW(), NOW())
        ");
        try {
            $ok = $stmt->execute([
                ':user_id' => $userId,
                ':ordre' => $ordre,
                ':titre_affiche' => $titreAffiche ?: null,
                ':actif' => $actif ? 1 : 0,
            ]);
            return $ok ? (int) $db->lastInsertId() : null;
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) return null; // duplicate user_id
            throw $e;
        }
    }

    /** Mettre à jour ordre, titre_affiche, actif. */
    public static function update(int $id, int $ordre, ?string $titreAffiche, bool $actif): bool
    {
        $db = getDB();
        $stmt = $db->prepare("
            UPDATE comite_editorial SET ordre = :ordre, titre_affiche = :titre_affiche, actif = :actif, updated_at = NOW()
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':ordre' => $ordre,
            ':titre_affiche' => $titreAffiche ?: null,
            ':actif' => $actif ? 1 : 0,
        ]);
    }

    /** Supprimer un membre du comité par id (clé primaire). */
    public static function delete(int $id): bool
    {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM comite_editorial WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /** Supprimer un membre du comité par user_id (ex. avant suppression de l'utilisateur). */
    public static function deleteByUserId(int $userId): bool
    {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM comite_editorial WHERE user_id = :uid");
        return $stmt->execute([':uid' => $userId]);
    }

    /** Vérifier si la table existe (pour fallback si migration non faite). */
    public static function tableExists(): bool
    {
        $db = getDB();
        $stmt = $db->query("SHOW TABLES LIKE 'comite_editorial'");
        return $stmt->rowCount() > 0;
    }
}
