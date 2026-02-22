<?php
namespace Models;

/**
 * Modèle volumes — table volumes.
 */
class VolumeModel
{
    public static function getAll(): array
    {
        $db = getDB();
        $stmt = $db->query("SELECT id, annee, numero_volume, description, comite_editorial, redacteur_chef, comite_scientifique, comite_redaction
                            FROM volumes ORDER BY annee DESC, id DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getById(int $id): ?array
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM volumes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
