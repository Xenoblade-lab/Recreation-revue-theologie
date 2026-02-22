<?php
namespace Models;

/**
 * Modèle revue_parts (contenu d'un numéro : articles, éditorial, etc.).
 */
class RevuePartModel
{
    public static function getByRevueId(int $revueId): array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT id, revue_id, type, titre, auteurs, pages, ordre, file_path, is_free_preview
            FROM revue_parts WHERE revue_id = :rid ORDER BY ordre ASC, id ASC
        ");
        $stmt->execute([':rid' => $revueId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
