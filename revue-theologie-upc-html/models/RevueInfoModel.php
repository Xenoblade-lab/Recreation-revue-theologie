<?php
namespace Models;

/**
 * Modèle revue_info (paramètres de la revue, une seule ligne).
 */
class RevueInfoModel
{
    public static function get(): ?array
    {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM revue_info WHERE id = 1 LIMIT 1");
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function update(
        string $nomOfficiel,
        ?string $description,
        ?string $ligneEditoriale,
        ?string $objectifs,
        ?string $domainesCouverts,
        ?string $issn,
        ?string $comiteScientifique,
        ?string $comiteRedaction
    ): bool {
        $db = getDB();
        $stmt = $db->prepare("
            UPDATE revue_info SET
                nom_officiel = :nom, description = :desc, ligne_editoriale = :ligne,
                objectifs = :objectifs, domaines_couverts = :domaines, issn = :issn,
                comite_scientifique = :cs, comite_redaction = :cr, updated_at = NOW()
            WHERE id = 1
        ");
        return $stmt->execute([
            ':nom' => $nomOfficiel,
            ':desc' => $description,
            ':ligne' => $ligneEditoriale,
            ':objectifs' => $objectifs,
            ':domaines' => $domainesCouverts,
            ':issn' => $issn,
            ':cs' => $comiteScientifique,
            ':cr' => $comiteRedaction,
        ]);
    }
}
