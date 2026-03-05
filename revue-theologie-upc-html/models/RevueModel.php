<?php
namespace Models;

/**
 * Modèle revues (numéros) — table revues.
 */
class RevueModel
{
    /** Liste des revues/numéros, optionnellement par volume_id */
    public static function getAll(?int $volumeId = null, int $limit = 100): array
    {
        $db = getDB();
        $sql = "SELECT id, numero, titre, description, fichier_path, date_publication, volume_id, type, issue_id
                FROM revues ORDER BY created_at DESC, id DESC LIMIT :limit";
        if ($volumeId !== null) {
            $sql = "SELECT id, numero, titre, description, fichier_path, date_publication, volume_id, type, issue_id
                    FROM revues WHERE volume_id = :vid ORDER BY created_at DESC, id DESC LIMIT :limit";
        }
        $stmt = $db->prepare($sql);
        if ($volumeId !== null) {
            $stmt->bindValue(':vid', $volumeId, \PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getById(int $id): ?array
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM revues WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Créer un nouveau numéro (revue) lié à un volume. Retourne l'id créé ou null. */
    public static function create(int $volumeId, string $numero, string $titre, ?string $description = null, ?string $datePublication = null): ?int
    {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO revues (volume_id, numero, titre, description, date_publication, created_at, updated_at) VALUES (:volume_id, :numero, :titre, :description, :date_publication, NOW(), NOW())");
        $ok = $stmt->execute([
            ':volume_id' => $volumeId,
            ':numero' => $numero,
            ':titre' => $titre,
            ':description' => $description,
            ':date_publication' => $datePublication,
        ]);
        return $ok ? (int) $db->lastInsertId() : null;
    }

    /** Indique s'il existe des numéros créés/mis en ligne dans les X derniers jours (pour badge "Nouveau" archives). */
    public static function hasNewInLastDays(int $days = 7): bool
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT 1 FROM revues
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
            LIMIT 1
        ");
        $stmt->bindValue(':days', $days, \PDO::PARAM_INT);
        $stmt->execute();
        return (bool) $stmt->fetch();
    }

    /** Derniers numéros (pour l'accueil) */
    public static function getLatest(int $limit = 5): array
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, numero, titre, description, fichier_path, date_publication, volume_id
                             FROM revues ORDER BY created_at DESC, id DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** Recherche dans les numéros (titre, numero, description) */
    public static function search(string $query, int $limit = 20): array
    {
        if (trim($query) === '') {
            return [];
        }
        $db = getDB();
        $term = '%' . trim($query) . '%';
        $limit = (int) $limit;
        $stmt = $db->prepare("
            SELECT id, numero, titre, description, date_publication, volume_id
            FROM revues
            WHERE titre LIKE :q1 OR numero LIKE :q2 OR (description IS NOT NULL AND description LIKE :q3)
            ORDER BY date_publication DESC, id DESC
            LIMIT " . $limit . "
        ");
        $stmt->bindValue(':q1', $term, \PDO::PARAM_STR);
        $stmt->bindValue(':q2', $term, \PDO::PARAM_STR);
        $stmt->bindValue(':q3', $term, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function update(int $id, string $numero, string $titre, ?string $description, ?string $datePublication): bool
    {
        $db = getDB();
        $stmt = $db->prepare("UPDATE revues SET numero = :numero, titre = :titre, description = :description, date_publication = :date_publication, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':numero' => $numero,
            ':titre' => $titre,
            ':description' => $description,
            ':date_publication' => $datePublication ?: null,
        ]);
    }
}
