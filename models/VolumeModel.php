<?php
namespace Models;
require "JournalModel.php";
class VolumeModel {
    private $db;

    public function __construct(\Models\Database $db) {
        $this->db = $db;
    }

    /**
     * Dans cette approche, nous considérons que la table "revues" 
     * représente en fait des volumes, et chaque volume peut avoir 
     * plusieurs numéros (qui seraient les "revue_parts")
     */

    /**
     * Créer un volume pour une année
     */
    public function createVolume($annee, $data = []) {
        // Vérifier si un volume existe déjà pour cette année
        $existing = $this->getVolumeByYear($annee);
        if ($existing) {
            return false; // Volume déjà existant
        }
        
        $sql = "INSERT INTO volumes (annee, numero_volume, description, comite_editorial, redacteur_chef, created_at, updated_at) 
                VALUES (:annee, :numero_volume, :description, :comite_editorial, :redacteur_chef, NOW(), NOW())";
        
        $params = [
            ':annee' => $annee,
            ':numero_volume' => $data['numero_volume'] ?? "Volume " . ($annee - 1985),
            ':description' => $data['description'] ?? '',
            ':comite_editorial' => $data['comite_editorial'] ?? null,
            ':redacteur_chef' => $data['redacteur_chef'] ?? null
        ];
        
        try {
            $this->db->execute($sql, $params);
            return $this->db->lastInsertId();
        } catch (\Exception $e) {
            error_log('Erreur createVolume: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer un volume par ID
     */
    public function getVolumeById($id) {
        $sql = "SELECT * FROM volumes WHERE id = :id";
        return $this->db->fetchOne($sql, [':id' => $id]);
    }

    /**
     * Récupérer un volume par année
     */
    public function getVolumeByYear($year) {
        $sql = "SELECT * FROM volumes WHERE annee = :year LIMIT 1";
        return $this->db->fetchOne($sql, [':year' => $year]);
    }

    /**
     * Récupérer tous les volumes
     */
    public function getAllVolumes($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT v.*, 
                       (SELECT COUNT(*) FROM revues WHERE volume_id = v.id) as issue_count,
                       (SELECT COUNT(*) FROM articles WHERE issue_id IN (SELECT id FROM revues WHERE volume_id = v.id)) as article_count
                FROM volumes v 
                ORDER BY v.annee DESC 
                LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Récupérer les numéros (issues) d'un volume
     */
    public function getVolumeIssues($volumeId) {
        $sql = "SELECT r.*, 
                       (SELECT COUNT(*) FROM articles WHERE issue_id = r.id) as article_count
                FROM revues r 
                WHERE r.volume_id = :volume_id 
                ORDER BY r.numero ASC, r.date_publication ASC";
        
        return $this->db->fetchAll($sql, [':volume_id' => $volumeId]);
    }

    /**
     * Mettre à jour un volume
     */
    public function updateVolume($id, $data) {
        $sql = "UPDATE volumes SET 
                numero_volume = :numero_volume,
                description = :description,
                comite_editorial = :comite_editorial,
                redacteur_chef = :redacteur_chef,
                updated_at = NOW()
                WHERE id = :id";
        
        $params = [
            ':id' => $id,
            ':numero_volume' => $data['numero_volume'] ?? '',
            ':description' => $data['description'] ?? '',
            ':comite_editorial' => $data['comite_editorial'] ?? null,
            ':redacteur_chef' => $data['redacteur_chef'] ?? null
        ];
        
        try {
            return $this->db->execute($sql, $params);
        } catch (\Exception $e) {
            error_log('Erreur updateVolume: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprimer un volume (seulement si aucun numéro n'y est lié)
     */
    public function deleteVolume($id) {
        // Vérifier s'il y a des numéros liés
        $issues = $this->getVolumeIssues($id);
        if (!empty($issues)) {
            return false; // Ne pas supprimer si des numéros existent
        }
        
        $sql = "DELETE FROM volumes WHERE id = :id";
        try {
            return $this->db->execute($sql, [':id' => $id]);
        } catch (\Exception $e) {
            error_log('Erreur deleteVolume: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer les volumes regroupés par année
     */
    public function getVolumesGroupedByYear() {
        $sql = "SELECT YEAR(date_publication) as year, 
                       COUNT(*) as volume_count,
                       GROUP_CONCAT(id ORDER BY numero ASC) as volume_ids
                FROM revues 
                WHERE date_publication IS NOT NULL 
                GROUP BY YEAR(date_publication) 
                ORDER BY year DESC";
        
        $years = $this->db->fetchAll($sql);
        
        // Récupérer les détails des volumes pour chaque année
        foreach ($years as &$year) {
            $volumeIds = explode(',', $year['volume_ids']);
            $year['volumes'] = [];
            
            foreach ($volumeIds as $volumeId) {
                $volume = $this->getVolumeById($volumeId);
                if ($volume) {
                    $year['volumes'][] = $volume;
                }
            }
            
            unset($year['volume_ids']);
        }
        
        return $years;
    }

    /**
     * Récupérer le dernier volume publié
     */
    public function getLatestVolume() {
        $sql = "SELECT * FROM revues 
                WHERE date_publication IS NOT NULL 
                ORDER BY date_publication DESC 
                LIMIT 1";
        
        return $this->db->fetchOne($sql);
    }

    /**
     * Récupérer les volumes avec statistiques
     */
    public function getVolumesWithStats($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT r.*,
                       (SELECT COUNT(*) FROM revue_parts WHERE revue_id = r.id) as issue_count,
                       (SELECT COUNT(*) FROM commentaires WHERE revue_id = r.id) as comment_count,
                       (SELECT COUNT(*) FROM telechargements WHERE revue_id = r.id) as download_count,
                       (SELECT AVG(valeur) FROM notes WHERE revue_id = r.id) as average_rating
                FROM revues r 
                ORDER BY r.date_publication DESC 
                LIMIT :limit OFFSET :offset";
        
        $volumes = $this->db->fetchAll($sql, [
            ':limit' => $limit,
            ':offset' => $offset
        ]);
        
        // Formater les notes
        foreach ($volumes as &$volume) {
            $volume['average_rating'] = round($volume['average_rating'] ?? 0, 1);
        }
        
        return $volumes;
    }
}
?>