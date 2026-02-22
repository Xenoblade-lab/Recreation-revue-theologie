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
        $stmt = $db->prepare("
            SELECT id, numero, titre, description, date_publication, volume_id
            FROM revues
            WHERE titre LIKE :q1 OR numero LIKE :q2 OR (description IS NOT NULL AND description LIKE :q3)
            ORDER BY date_publication DESC, id DESC
            LIMIT :lim
        ");
        $stmt->bindValue(':q1', $term, \PDO::PARAM_STR);
        $stmt->bindValue(':q2', $term, \PDO::PARAM_STR);
        $stmt->bindValue(':q3', $term, \PDO::PARAM_STR);
        $stmt->bindValue(':lim', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
