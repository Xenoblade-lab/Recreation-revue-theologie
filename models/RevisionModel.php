<?php
namespace Models;

class RevisionModel {
    private $db;

    public function __construct(\Models\Database $db) {
        $this->db = $db;
    }

    /**
     * Créer une entrée de révision
     */
    public function createRevision($articleId, $previousStatus, $newStatus, $revisionReason = null) {
        try {
            // Récupérer le numéro de révision actuel
            $currentRevision = $this->db->fetchOne(
                "SELECT MAX(revision_number) as max_revision FROM article_revisions WHERE article_id = :article_id",
                [':article_id' => $articleId]
            );
            
            $revisionNumber = ($currentRevision['max_revision'] ?? 0) + 1;
            
            $sql = "INSERT INTO article_revisions 
                    (article_id, revision_number, previous_status, new_status, revision_reason, submitted_at, created_at, updated_at) 
                    VALUES (:article_id, :revision_number, :previous_status, :new_status, :revision_reason, NOW(), NOW(), NOW())";
            
            $params = [
                ':article_id' => $articleId,
                ':revision_number' => $revisionNumber,
                ':previous_status' => $previousStatus,
                ':new_status' => $newStatus,
                ':revision_reason' => $revisionReason
            ];
            
            $this->db->execute($sql, $params);
            return $this->db->lastInsertId();
        } catch (\Exception $e) {
            // Si la table n'existe pas encore, retourner false sans erreur fatale
            error_log('Erreur createRevision: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer l'historique des révisions d'un article
     */
    public function getArticleRevisions($articleId) {
        try {
            $sql = "SELECT * FROM article_revisions 
                    WHERE article_id = :article_id 
                    ORDER BY revision_number ASC, created_at ASC";
            
            return $this->db->fetchAll($sql, [':article_id' => $articleId]);
        } catch (\Exception $e) {
            // Si la table n'existe pas encore, retourner un tableau vide
            error_log('Erreur getArticleRevisions: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer la révision actuelle d'un article
     */
    public function getCurrentRevision($articleId) {
        try {
            $sql = "SELECT * FROM article_revisions 
                    WHERE article_id = :article_id 
                    ORDER BY revision_number DESC, created_at DESC 
                    LIMIT 1";
            
            return $this->db->fetchOne($sql, [':article_id' => $articleId]);
        } catch (\Exception $e) {
            // Si la table n'existe pas encore, retourner null
            error_log('Erreur getCurrentRevision: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupérer le nombre de révisions d'un article
     */
    public function getRevisionCount($articleId) {
        try {
            $sql = "SELECT COUNT(*) as count FROM article_revisions WHERE article_id = :article_id";
            $result = $this->db->fetchOne($sql, [':article_id' => $articleId]);
            return $result['count'] ?? 0;
        } catch (\Exception $e) {
            // Si la table n'existe pas encore, retourner 0
            error_log('Erreur getRevisionCount: ' . $e->getMessage());
            return 0;
        }
    }
}

