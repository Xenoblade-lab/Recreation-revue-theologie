<?php
namespace Models;

class RevueInfoModel {
    private $db;

    public function __construct(\Models\Database $db) {
        $this->db = $db;
    }

    /**
     * Récupérer l'identité de la revue
     */
    public function getRevueInfo() {
        $sql = "SELECT * FROM revue_info ORDER BY id DESC LIMIT 1";
        $info = $this->db->fetchOne($sql);
        
        // Si aucune info n'existe, retourner des valeurs par défaut
        if (!$info) {
            return [
                'id' => null,
                'nom_officiel' => 'Revue de Théologie de l\'UPC',
                'description' => '',
                'ligne_editoriale' => '',
                'objectifs' => '',
                'domaines_couverts' => '',
                'issn' => '',
                'comite_scientifique' => '',
                'comite_redaction' => ''
            ];
        }
        
        return $info;
    }

    /**
     * Mettre à jour l'identité de la revue
     */
    public function updateRevueInfo($data) {
        // Vérifier si une entrée existe déjà
        $existing = $this->db->fetchOne("SELECT id FROM revue_info ORDER BY id DESC LIMIT 1");
        
        if ($existing) {
            // Mise à jour
            $sql = "UPDATE revue_info SET 
                    nom_officiel = :nom_officiel,
                    description = :description,
                    ligne_editoriale = :ligne_editoriale,
                    objectifs = :objectifs,
                    domaines_couverts = :domaines_couverts,
                    issn = :issn,
                    comite_scientifique = :comite_scientifique,
                    comite_redaction = :comite_redaction,
                    updated_at = NOW()
                    WHERE id = :id";
            
            $params = [
                ':id' => $existing['id'],
                ':nom_officiel' => $data['nom_officiel'] ?? '',
                ':description' => $data['description'] ?? '',
                ':ligne_editoriale' => $data['ligne_editoriale'] ?? '',
                ':objectifs' => $data['objectifs'] ?? '',
                ':domaines_couverts' => $data['domaines_couverts'] ?? '',
                ':issn' => $data['issn'] ?? '',
                ':comite_scientifique' => $data['comite_scientifique'] ?? '',
                ':comite_redaction' => $data['comite_redaction'] ?? ''
            ];
        } else {
            // Création
            $sql = "INSERT INTO revue_info 
                    (nom_officiel, description, ligne_editoriale, objectifs, domaines_couverts, issn, comite_scientifique, comite_redaction, created_at, updated_at) 
                    VALUES 
                    (:nom_officiel, :description, :ligne_editoriale, :objectifs, :domaines_couverts, :issn, :comite_scientifique, :comite_redaction, NOW(), NOW())";
            
            $params = [
                ':nom_officiel' => $data['nom_officiel'] ?? 'Revue de Théologie de l\'UPC',
                ':description' => $data['description'] ?? '',
                ':ligne_editoriale' => $data['ligne_editoriale'] ?? '',
                ':objectifs' => $data['objectifs'] ?? '',
                ':domaines_couverts' => $data['domaines_couverts'] ?? '',
                ':issn' => $data['issn'] ?? '',
                ':comite_scientifique' => $data['comite_scientifique'] ?? '',
                ':comite_redaction' => $data['comite_redaction'] ?? ''
            ];
        }
        
        try {
            $this->db->execute($sql, $params);
            return $existing ? $existing['id'] : $this->db->lastInsertId();
        } catch (\Exception $e) {
            error_log('Erreur updateRevueInfo: ' . $e->getMessage());
            return false;
        }
    }
}

