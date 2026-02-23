<?php
namespace Models;

class IssueModel {
    private $db;

    public function __construct(\Models\Database $db) {
        $this->db = $db;
    }

    /**
     * Créer un numéro (issue) dans un volume
     * Utilise la table revues comme table de base pour les issues
     */
    public function createIssue($volumeId, $data) {
        // Vérifier que le volume existe
        $volumeCheck = $this->db->fetchOne("SELECT id FROM volumes WHERE id = :volume_id", [':volume_id' => $volumeId]);
        if (!$volumeCheck) {
            error_log('Erreur createIssue: Volume introuvable (ID: ' . $volumeId . ')');
            return false;
        }
        
        // Construire la requête SQL dynamiquement selon les champs fournis
        $fields = ['volume_id', 'numero', 'titre', 'type', 'created_at', 'updated_at'];
        $values = [':volume_id', ':numero', ':titre', ':type', 'NOW()', 'NOW()'];
        $params = [
            ':volume_id' => $volumeId,
            ':numero' => $data['numero'] ?? '',
            ':titre' => $data['titre'] ?? '',
            ':type' => $data['type'] ?? 'issue'
        ];
        
        // Ajouter les champs optionnels s'ils sont fournis
        if (!empty($data['description'])) {
            $fields[] = 'description';
            $values[] = ':description';
            $params[':description'] = $data['description'];
        }
        
        if (!empty($data['fichier_path'])) {
            $fields[] = 'fichier_path';
            $values[] = ':fichier_path';
            $params[':fichier_path'] = $data['fichier_path'];
        }
        
        if (!empty($data['date_publication'])) {
            $fields[] = 'date_publication';
            $values[] = ':date_publication';
            $params[':date_publication'] = $data['date_publication'];
        }
        
        $sql = "INSERT INTO revues (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $values) . ")";
        
        try {
            $this->db->execute($sql, $params);
            $insertId = $this->db->lastInsertId();
            return $insertId;
        } catch (\PDOException $e) {
            error_log('Erreur PDO createIssue: ' . $e->getMessage());
            error_log('SQL: ' . $sql);
            error_log('Params: ' . print_r($params, true));
            return false; // Retourner false au lieu de lancer l'exception
        } catch (\Exception $e) {
            error_log('Erreur createIssue: ' . $e->getMessage());
            error_log('SQL: ' . $sql);
            error_log('Params: ' . print_r($params, true));
            return false; // Retourner false au lieu de lancer l'exception
        }
    }

    /**
     * Créer un numéro/partie dans une revue (ancienne méthode - compatibilité)
     */
    public function createIssueOld($journalId, $data) {
        // Déterminer le prochain ordre disponible
        $orderSql = "SELECT COALESCE(MAX(ordre), 0) + 1 as next_order 
                     FROM revue_parts 
                     WHERE revue_id = :journalId";
        
        $orderResult = $this->db->fetchOne($orderSql, [':journalId' => $journalId]);
        $nextOrder = $orderResult['next_order'] ?? 1;
        
        $sql = "INSERT INTO revue_parts (revue_id, type, titre, auteurs, pages, ordre, file_path, is_free_preview, created_at, updated_at) 
                VALUES (:journalId, :type, :titre, :auteurs, :pages, :ordre, :file_path, :is_free_preview, NOW(), NOW())";
        
        $params = [
            ':journalId' => $journalId,
            ':type' => $data['type'],
            ':titre' => $data['titre'],
            ':auteurs' => $data['auteurs'] ?? null,
            ':pages' => $data['pages'] ?? null,
            ':ordre' => $nextOrder,
            ':file_path' => $data['file_path'],
            ':is_free_preview' => $data['is_free_preview'] ?? 0
        ];
        
        $this->db->execute($sql, $params);
        return $this->db->lastInsertId();
    }

    /**
     * Récupérer un numéro (issue) par ID depuis la table revues
     */
    public function getIssueById($issueId) {
        $sql = "SELECT r.*, 
                       v.annee, v.numero_volume,
                       (SELECT COUNT(*) FROM articles WHERE issue_id = r.id) as article_count
                FROM revues r 
                LEFT JOIN volumes v ON r.volume_id = v.id 
                WHERE r.id = :id";
        
        return $this->db->fetchOne($sql, [':id' => $issueId]);
    }

    /**
     * Récupérer les articles d'un numéro (issue)
     */
    public function getIssueArticles($issueId) {
        $sql = "SELECT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom,
                       u.email as auteur_email
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                WHERE a.issue_id = :issue_id 
                ORDER BY a.date_soumission ASC";
        
        return $this->db->fetchAll($sql, [':issue_id' => $issueId]);
    }

    /**
     * Assigner un article à un numéro (issue)
     */
    public function assignArticleToIssue($articleId, $issueId) {
        $sql = "UPDATE articles SET issue_id = :issue_id, updated_at = NOW() WHERE id = :article_id";
        
        try {
            return $this->db->execute($sql, [
                ':issue_id' => $issueId,
                ':article_id' => $articleId
            ]);
        } catch (\Exception $e) {
            error_log('Erreur assignArticleToIssue: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Retirer un article d'un numéro
     */
    public function unassignArticleFromIssue($articleId) {
        $sql = "UPDATE articles SET issue_id = NULL, updated_at = NOW() WHERE id = :article_id";
        
        try {
            return $this->db->execute($sql, [':article_id' => $articleId]);
        } catch (\Exception $e) {
            error_log('Erreur unassignArticleFromIssue: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mettre à jour un numéro (issue)
     */
    public function updateIssue($issueId, $data) {
        $updates = [];
        $params = [':id' => $issueId];
        
        if (isset($data['numero'])) {
            $updates[] = 'numero = :numero';
            $params[':numero'] = $data['numero'];
        }
        if (isset($data['titre'])) {
            $updates[] = 'titre = :titre';
            $params[':titre'] = $data['titre'];
        }
        if (isset($data['description'])) {
            $updates[] = 'description = :description';
            $params[':description'] = $data['description'];
        }
        if (isset($data['fichier_path'])) {
            $updates[] = 'fichier_path = :fichier_path';
            $params[':fichier_path'] = $data['fichier_path'];
        }
        if (isset($data['date_publication'])) {
            $updates[] = 'date_publication = :date_publication';
            $params[':date_publication'] = $data['date_publication'];
        }
        if (isset($data['volume_id'])) {
            $updates[] = 'volume_id = :volume_id';
            $params[':volume_id'] = $data['volume_id'];
        }
        
        $updates[] = 'updated_at = NOW()';
        
        if (empty($updates)) {
            return false;
        }
        
        $sql = "UPDATE revues SET " . implode(', ', $updates) . " WHERE id = :id";
        
        try {
            return $this->db->execute($sql, $params);
        } catch (\Exception $e) {
            error_log('Erreur updateIssue: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprimer un numéro (issue) - seulement si aucun article n'y est assigné
     */
    public function deleteIssue($issueId) {
        // Vérifier s'il y a des articles assignés
        $articles = $this->getIssueArticles($issueId);
        if (!empty($articles)) {
            return false; // Ne pas supprimer si des articles sont assignés
        }
        
        $sql = "DELETE FROM revues WHERE id = :id";
        try {
            return $this->db->execute($sql, [':id' => $issueId]);
        } catch (\Exception $e) {
            error_log('Erreur deleteIssue: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer tous les numéros d'une revue
     */
    public function getIssuesByJournal($journalId, $onlyFree = false) {
        $sql = "SELECT * FROM revue_parts 
                WHERE revue_id = :journalId";
        
        if ($onlyFree) {
            $sql .= " AND is_free_preview = 1";
        }
        
        $sql .= " ORDER BY ordre ASC";
        
        return $this->db->fetchAll($sql, [':journalId' => $journalId]);
    }

    /**
     * Récupérer les numéros par type
     */
    public function getIssuesByType($journalId, $type) {
        $sql = "SELECT * FROM revue_parts 
                WHERE revue_id = :journalId AND type = :type 
                ORDER BY ordre ASC";
        
        return $this->db->fetchAll($sql, [
            ':journalId' => $journalId,
            ':type' => $type
        ]);
    }

    /**
     * Récupérer les numéros gratuits d'une revue
     */
    public function getFreeIssues($journalId) {
        return $this->getIssuesByJournal($journalId, true);
    }

    /**
     * Compter le nombre de numéros dans une revue
     */
    public function countIssuesInJournal($journalId) {
        $sql = "SELECT COUNT(*) as total FROM revue_parts WHERE revue_id = :journalId";
        $result = $this->db->fetchOne($sql, [':journalId' => $journalId]);
        return $result['total'] ?? 0;
    }

    /**
     * Compter les numéros gratuits dans une revue
     */
    public function countFreeIssues($journalId) {
        $sql = "SELECT COUNT(*) as total FROM revue_parts 
                WHERE revue_id = :journalId AND is_free_preview = 1";
        
        $result = $this->db->fetchOne($sql, [':journalId' => $journalId]);
        return $result['total'] ?? 0;
    }

    /**
     * Changer l'ordre des numéros
     */
    public function updateIssueOrder($issueId, $newOrder) {
        $sql = "UPDATE revue_parts SET ordre = :ordre, updated_at = NOW() WHERE id = :id";
        return $this->db->execute($sql, [
            ':id' => $issueId,
            ':ordre' => $newOrder
        ]);
    }

    /**
     * Réorganiser tous les numéros d'une revue
     */
    public function reorderIssues($journalId, $issueIds) {
        $this->db->beginTransaction();
        
        try {
            $order = 1;
            foreach ($issueIds as $issueId) {
                $this->updateIssueOrder($issueId, $order);
                $order++;
            }
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Basculer le statut gratuit/payant d'un numéro
     */
    public function toggleFreePreview($issueId) {
        // Récupérer le statut actuel
        $sql = "SELECT is_free_preview FROM revue_parts WHERE id = :id";
        $current = $this->db->fetchOne($sql, [':id' => $issueId]);
        
        if (!$current) {
            return false;
        }
        
        $newStatus = $current['is_free_preview'] ? 0 : 1;
        
        $updateSql = "UPDATE revue_parts SET is_free_preview = :status, updated_at = NOW() WHERE id = :id";
        return $this->db->execute($updateSql, [
            ':id' => $issueId,
            ':status' => $newStatus
        ]);
    }

    /**
     * Marquer un numéro comme gratuit
     */
    public function setAsFree($issueId) {
        $sql = "UPDATE revue_parts SET is_free_preview = 1, updated_at = NOW() WHERE id = :id";
        return $this->db->execute($sql, [':id' => $issueId]);
    }

    /**
     * Marquer un numéro comme payant
     */
    public function setAsPaid($issueId) {
        $sql = "UPDATE revue_parts SET is_free_preview = 0, updated_at = NOW() WHERE id = :id";
        return $this->db->execute($sql, [':id' => $issueId]);
    }

    /**
     * Rechercher des numéros par titre ou auteurs
     */
    public function searchIssues($searchTerm, $journalId = null, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT rp.*, r.titre as revue_titre, r.numero as revue_numero 
                FROM revue_parts rp 
                JOIN revues r ON rp.revue_id = r.id 
                WHERE (rp.titre LIKE :search OR rp.auteurs LIKE :search)";
        
        $params = [':search' => '%' . $searchTerm . '%'];
        
        if ($journalId) {
            $sql .= " AND rp.revue_id = :journalId";
            $params[':journalId'] = $journalId;
        }
        
        $sql .= " ORDER BY r.date_publication DESC, rp.ordre ASC 
                 LIMIT :limit OFFSET :offset";
        
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Récupérer les numéros les plus téléchargés
     */
    public function getMostDownloadedIssues($limit = 10) {
        // Note: Cette requête nécessiterait une table de suivi des téléchargements par issue
        // Pour l'instant, nous retournons simplement les issues avec leur revue
        $sql = "SELECT rp.*, r.titre as revue_titre, 
                       (SELECT COUNT(*) FROM telechargements t WHERE t.revue_id = r.id) as journal_downloads
                FROM revue_parts rp 
                JOIN revues r ON rp.revue_id = r.id 
                WHERE rp.is_free_preview = 1 
                ORDER BY journal_downloads DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, [':limit' => $limit]);
    }

    /**
     * Récupérer les numéros récemment ajoutés
     */
    public function getRecentlyAddedIssues($limit = 10) {
        $sql = "SELECT rp.*, r.titre as revue_titre, r.numero as revue_numero 
                FROM revue_parts rp 
                JOIN revues r ON rp.revue_id = r.id 
                ORDER BY rp.created_at DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, [':limit' => $limit]);
    }

    /**
     * Récupérer le numéro précédent dans l'ordre
     */
    public function getPreviousIssue($journalId, $currentOrder) {
        $sql = "SELECT * FROM revue_parts 
                WHERE revue_id = :journalId AND ordre < :currentOrder 
                ORDER BY ordre DESC 
                LIMIT 1";
        
        return $this->db->fetchOne($sql, [
            ':journalId' => $journalId,
            ':currentOrder' => $currentOrder
        ]);
    }

    /**
     * Récupérer le numéro suivant dans l'ordre
     */
    public function getNextIssue($journalId, $currentOrder) {
        $sql = "SELECT * FROM revue_parts 
                WHERE revue_id = :journalId AND ordre > :currentOrder 
                ORDER BY ordre ASC 
                LIMIT 1";
        
        return $this->db->fetchOne($sql, [
            ':journalId' => $journalId,
            ':currentOrder' => $currentOrder
        ]);
    }

    /**
     * Récupérer les numéros par plage de pages
     */
    public function getIssuesByPageRange($journalId, $startPage, $endPage) {
        $sql = "SELECT * FROM revue_parts 
                WHERE revue_id = :journalId 
                AND pages LIKE :pageRange 
                ORDER BY ordre ASC";
        
        // Convertir la plage en format SQL LIKE
        $pageRange = "%$startPage-$endPage%";
        
        return $this->db->fetchAll($sql, [
            ':journalId' => $journalId,
            ':pageRange' => $pageRange
        ]);
    }

    /**
     * Vérifier si un utilisateur peut accéder à un numéro
     */
    public function canUserAccessIssue($issueId, $userId) {
        // Récupérer les informations du numéro
        $issue = $this->getIssueById($issueId);
        if (!$issue) {
            return false;
        }
        
        // Si le numéro est gratuit
        if ($issue['is_free_preview']) {
            return true;
        }
        
        // Vérifier si l'utilisateur est abonné
        $userModel = new UserModel();
        return $userModel->isSubscribedAndActive($userId);
    }

    /**
     * Récupérer les métadonnées d'un numéro pour l'export
     */
    public function getIssueMetadata($issueId) {
        $issue = $this->getIssueById($issueId);
        if (!$issue) {
            return null;
        }
        
        // Récupérer la revue parent
        $journalModel = new JournalModel();
        $journal = $journalModel->getJournalById($issue['revue_id']);
        
        // Récupérer les statistiques de la revue
        $journalStats = $journalModel->getJournalStatistics($issue['revue_id']);
        
        return [
            'issue' => $issue,
            'journal' => [
                'id' => $journal['id'],
                'titre' => $journal['titre'],
                'numero' => $journal['numero'],
                'date_publication' => $journal['date_publication']
            ],
            'statistics' => $journalStats
        ];
    }

    /**
     * Récupérer les numéros avec des informations de revue
     */
    public function getIssuesWithJournalInfo($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT rp.*, 
                       r.titre as journal_titre, 
                       r.numero as journal_number,
                       r.date_publication as journal_date,
                       (SELECT COUNT(*) FROM commentaires c WHERE c.revue_id = r.id) as journal_comments
                FROM revue_parts rp 
                JOIN revues r ON rp.revue_id = r.id 
                ORDER BY r.date_publication DESC, rp.ordre ASC 
                LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, [
            ':limit' => $limit,
            ':offset' => $offset
        ]);
    }

    /**
     * Récupérer les types de numéros disponibles
     */
    public function getAvailableIssueTypes() {
        $sql = "SELECT DISTINCT type FROM revue_parts ORDER BY type ASC";
        $types = $this->db->fetchAll($sql);
        
        $typeList = [];
        foreach ($types as $type) {
            if (!empty($type['type'])) {
                $typeList[] = $type['type'];
            }
        }
        
        return $typeList;
    }

    /**
     * Compter les numéros par type
     */
    public function countIssuesByType($journalId = null) {
        if ($journalId) {
            $sql = "SELECT type, COUNT(*) as count 
                    FROM revue_parts 
                    WHERE revue_id = :journalId 
                    GROUP BY type 
                    ORDER BY count DESC";
            
            return $this->db->fetchAll($sql, [':journalId' => $journalId]);
        } else {
            $sql = "SELECT type, COUNT(*) as count 
                    FROM revue_parts 
                    GROUP BY type 
                    ORDER BY count DESC";
            
            return $this->db->fetchAll($sql);
        }
    }

    /**
     * Dupliquer un numéro
     */
    public function duplicateIssue($issueId, $newJournalId = null) {
        // Récupérer le numéro original
        $original = $this->getIssueById($issueId);
        if (!$original) {
            return false;
        }
        
        // Déterminer l'ID de la revue cible
        $targetJournalId = $newJournalId ?? $original['revue_id'];
        
        // Trouver le prochain ordre
        $orderSql = "SELECT COALESCE(MAX(ordre), 0) + 1 as next_order 
                     FROM revue_parts 
                     WHERE revue_id = :journalId";
        
        $orderResult = $this->db->fetchOne($orderSql, [':journalId' => $targetJournalId]);
        $nextOrder = $orderResult['next_order'] ?? 1;
        
        // Créer la copie
        $sql = "INSERT INTO revue_parts (revue_id, type, titre, auteurs, pages, ordre, file_path, is_free_preview, created_at, updated_at) 
                VALUES (:journalId, :type, :titre, :auteurs, :pages, :ordre, :file_path, :is_free_preview, NOW(), NOW())";
        
        $params = [
            ':journalId' => $targetJournalId,
            ':type' => $original['type'],
            ':titre' => $original['titre'] . ' (Copie)',
            ':auteurs' => $original['auteurs'],
            ':pages' => $original['pages'],
            ':ordre' => $nextOrder,
            ':file_path' => $original['file_path'],
            ':is_free_preview' => $original['is_free_preview']
        ];
        
        $this->db->execute($sql, $params);
        return $this->db->lastInsertId();
    }

    /**
     * Déplacer un numéro vers une autre revue
     */
    public function moveIssueToJournal($issueId, $newJournalId) {
        // Récupérer le numéro
        $issue = $this->getIssueById($issueId);
        if (!$issue) {
            return false;
        }
        
        // Trouver le prochain ordre dans la nouvelle revue
        $orderSql = "SELECT COALESCE(MAX(ordre), 0) + 1 as next_order 
                     FROM revue_parts 
                     WHERE revue_id = :journalId";
        
        $orderResult = $this->db->fetchOne($orderSql, [':journalId' => $newJournalId]);
        $nextOrder = $orderResult['next_order'] ?? 1;
        
        // Mettre à jour
        $sql = "UPDATE revue_parts SET 
                revue_id = :newJournalId, 
                ordre = :newOrder, 
                updated_at = NOW() 
                WHERE id = :issueId";
        
        return $this->db->execute($sql, [
            ':issueId' => $issueId,
            ':newJournalId' => $newJournalId,
            ':newOrder' => $nextOrder
        ]);
    }

    /**
     * Récupérer les numéros par auteur
     */
    public function getIssuesByAuthor($authorName, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT rp.*, r.titre as revue_titre, r.numero as revue_numero 
                FROM revue_parts rp 
                JOIN revues r ON rp.revue_id = r.id 
                WHERE rp.auteurs LIKE :author 
                ORDER BY r.date_publication DESC 
                LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, [
            ':author' => '%' . $authorName . '%',
            ':limit' => $limit,
            ':offset' => $offset
        ]);
    }

    /**
     * Récupérer les auteurs fréquents
     */
    public function getFrequentAuthors($limit = 20) {
        $sql = "SELECT auteurs, COUNT(*) as issue_count 
                FROM revue_parts 
                WHERE auteurs IS NOT NULL AND auteurs != '' 
                GROUP BY auteurs 
                ORDER BY issue_count DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, [':limit' => $limit]);
    }

    /**
     * Mettre à jour le fichier d'un numéro
     */
    public function updateIssueFile($issueId, $filePath) {
        $sql = "UPDATE revue_parts SET file_path = :file_path, updated_at = NOW() WHERE id = :id";
        return $this->db->execute($sql, [
            ':id' => $issueId,
            ':file_path' => $filePath
        ]);
    }

    /**
     * Vérifier si un titre de numéro est unique dans une revue
     */
    public function isTitleUniqueInJournal($journalId, $title, $excludeIssueId = null) {
        $sql = "SELECT COUNT(*) as count FROM revue_parts 
                WHERE revue_id = :journalId AND titre = :title";
        
        if ($excludeIssueId) {
            $sql .= " AND id != :excludeId";
            $result = $this->db->fetchOne($sql, [
                ':journalId' => $journalId,
                ':title' => $title,
                ':excludeId' => $excludeIssueId
            ]);
        } else {
            $result = $this->db->fetchOne($sql, [
                ':journalId' => $journalId,
                ':title' => $title
            ]);
        }
        
        return ($result['count'] ?? 0) === 0;
    }

    /**
     * Récupérer le sommaire d'une revue
     */
    public function getJournalTableOfContents($journalId) {
        $sql = "SELECT id, type, titre, auteurs, pages, ordre, is_free_preview 
                FROM revue_parts 
                WHERE revue_id = :journalId 
                ORDER BY ordre ASC";
        
        $issues = $this->db->fetchAll($sql, [':journalId' => $journalId]);
        
        // Grouper par type
        $toc = [];
        foreach ($issues as $issue) {
            $type = $issue['type'] ?? 'Autre';
            if (!isset($toc[$type])) {
                $toc[$type] = [];
            }
            $toc[$type][] = $issue;
        }
        
        return $toc;
    }
}
?>