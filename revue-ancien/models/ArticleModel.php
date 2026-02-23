<?php
namespace Models;

class ArticleModel {
    private $db;

    public function __construct(\Models\Database $db) {
        $this->db = $db;
    }

    /**
     * Créer un nouvel article
     */
    public function createArticle($data) {
        $sql = "INSERT INTO articles (titre, contenu, fichier_path, auteur_id, statut, date_soumission, created_at, updated_at) 
                VALUES (:titre, :contenu, :fichier_path, :auteur_id, :statut, NOW(), NOW(), NOW())";
        
        $params = [
            ':titre' => $data['titre'],
            ':contenu' => $data['contenu'],
            ':fichier_path' => $data['fichier_path'] ?? null,
            ':auteur_id' => $data['auteur_id'],
            ':statut' => $data['statut'] ?? 'soumis'
        ];
        
        $this->db->execute($sql, $params);
        return $this->db->lastInsertId();
    }

    /**
     * Mettre à jour un article
     */
    public function updateArticle($id, $data) {
        $updates = [];
        $params = [':id' => $id];
        
        // Construire dynamiquement la requête SQL avec seulement les champs fournis
        if (isset($data['titre'])) {
            $updates[] = 'titre = :titre';
            $params[':titre'] = $data['titre'];
        }
        
        if (isset($data['contenu'])) {
            $updates[] = 'contenu = :contenu';
            $params[':contenu'] = $data['contenu'];
        }
        
        if (isset($data['statut'])) {
            $updates[] = 'statut = :statut';
            $params[':statut'] = $data['statut'];
        }
        
        if (isset($data['fichier_path'])) {
            $updates[] = 'fichier_path = :fichier_path';
            $params[':fichier_path'] = $data['fichier_path'];
        }
        
        // Toujours mettre à jour updated_at
        $updates[] = 'updated_at = NOW()';
        
        if (empty($updates)) {
            return false; // Aucune donnée à mettre à jour
        }
        
        $sql = "UPDATE articles SET " . implode(', ', $updates) . " WHERE id = :id";
        
        try {
            return $this->db->execute($sql, $params);
        } catch (\Exception $e) {
            error_log('Erreur updateArticle: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprimer un article
     */
    public function deleteArticle($id) {
        $sql = "DELETE FROM articles WHERE id = :id";
        return $this->db->execute($sql, [':id' => $id]);
    }

    /**
     * Récupérer un article par ID
     */
    public function getArticleById($id) {
        $sql = "SELECT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom, 
                       u.email as auteur_email,
                       GROUP_CONCAT(r.id) as revue_ids,
                       GROUP_CONCAT(r.titre SEPARATOR '||') as revue_titres
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                LEFT JOIN revue_article ra ON a.id = ra.article_id 
                LEFT JOIN revues r ON ra.revue_id = r.id 
                WHERE a.id = :id 
                GROUP BY a.id";
        
        $article = $this->db->fetchOne($sql, [':id' => $id]);
        
        if ($article) {
            // Formater les revues associées
            if ($article['revue_ids']) {
                $revueIds = explode(',', $article['revue_ids']);
                $revueTitres = explode('||', $article['revue_titres']);
                
                $article['revues'] = [];
                foreach ($revueIds as $index => $revueId) {
                    $article['revues'][] = [
                        'id' => $revueId,
                        'titre' => $revueTitres[$index] ?? ''
                    ];
                }
            } else {
                $article['revues'] = [];
            }
            
            unset($article['revue_ids'], $article['revue_titres']);
        }
        
        return $article;
    }

    /**
     * Récupérer tous les articles avec pagination
     */
    public function getAllArticles($page = 1, $limit = 20, $orderBy = 'date_soumission', $order = 'DESC') {
        $offset = ($page - 1) * $limit;
        
        $validOrders = ['date_soumission', 'created_at', 'titre', 'statut'];
        $orderBy = in_array($orderBy, $validOrders) ? $orderBy : 'date_soumission';
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
        
        // LIMIT et OFFSET doivent être des entiers, pas des paramètres nommés
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        $sql = "SELECT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom 
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                ORDER BY a.$orderBy $order 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql, []);
    }

    /**
     * Récupérer les articles par auteur
     */
    public function getArticlesByAuthor($authorId, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        // LIMIT et OFFSET doivent être des entiers, pas des paramètres nommés
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        $sql = "SELECT a.* 
                FROM articles a 
                WHERE a.auteur_id = :authorId 
                ORDER BY a.date_soumission DESC 
                LIMIT $limit OFFSET $offset";
        
        return $this->db->fetchAll($sql, [
            ':authorId' => $authorId
        ]);
    }

    /**
     * Récupérer les articles par statut
     */
    public function getArticlesByStatus($status, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom 
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                WHERE a.statut = :status 
                ORDER BY a.date_soumission DESC 
                LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, [
            ':status' => $status,
            ':limit' => $limit,
            ':offset' => $offset
        ]);
    }

    /**
     * Rechercher des articles
     */
    public function searchArticles($searchTerm, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom 
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                WHERE a.titre LIKE :search OR 
                      a.contenu LIKE :search OR 
                      u.nom LIKE :search OR 
                      u.prenom LIKE :search 
                ORDER BY a.date_soumission DESC 
                LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, [
            ':search' => '%' . $searchTerm . '%',
            ':limit' => $limit,
            ':offset' => $offset
        ]);
    }

    /**
     * Récupérer les articles récents
     */
    public function getRecentArticles($limit = 10) {
        // MySQL n'accepte pas les paramètres nommés pour LIMIT, on force un entier
        $limit = (int) $limit;
        $sql = "SELECT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom 
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                WHERE a.statut IN ('publie', 'valide') 
                ORDER BY a.date_soumission DESC 
                LIMIT {$limit}";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Changer le statut d'un article
     */
    public function changeArticleStatus($articleId, $status) {
        $validStatuses = ['soumis', 'en_evaluation', 'revision_requise', 'accepte', 'rejete', 'publie', 'valide'];
        
        if (!in_array($status, $validStatuses)) {
            return false;
        }
        
        $sql = "UPDATE articles SET statut = :statut, updated_at = NOW() WHERE id = :id";
        return $this->db->execute($sql, [
            ':id' => $articleId,
            ':statut' => $status
        ]);
    }

    /**
     * Valider un article
     */
    public function validateArticle($articleId) {
        return $this->changeArticleStatus($articleId, 'valide');
    }

    /**
     * Rejeter un article
     */
    public function rejectArticle($articleId) {
        return $this->changeArticleStatus($articleId, 'rejete');
    }

    /**
     * Soumettre un article (remettre à soumis)
     */
    public function submitArticle($articleId) {
        return $this->changeArticleStatus($articleId, 'soumis');
    }

    /**
     * Compter le nombre total d'articles
     */
    public function countArticles() {
        $sql = "SELECT COUNT(*) as total FROM articles";
        $result = $this->db->fetchOne($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Compter les articles par statut
     */
    public function countArticlesByStatus($status = null) {
        if ($status) {
            $sql = "SELECT COUNT(*) as total FROM articles WHERE statut = :status";
            $result = $this->db->fetchOne($sql, [':status' => $status]);
            return $result['total'] ?? 0;
        } else {
            $sql = "SELECT statut, COUNT(*) as count 
                    FROM articles 
                    GROUP BY statut 
                    ORDER BY count DESC";
            
            return $this->db->fetchAll($sql);
        }
    }

    /**
     * Compter les articles par auteur
     */
    public function countArticlesByAuthor($authorId) {
        $sql = "SELECT COUNT(*) as total FROM articles WHERE auteur_id = :authorId";
        $result = $this->db->fetchOne($sql, [':authorId' => $authorId]);
        return $result['total'] ?? 0;
    }

    /**
     * Récupérer les revues associées à un article
     */
    public function getArticleRevues($articleId) {
        $sql = "SELECT r.* 
                FROM revues r 
                JOIN revue_article ra ON r.id = ra.revue_id 
                WHERE ra.article_id = :articleId 
                ORDER BY ra.created_at ASC";
        
        return $this->db->fetchAll($sql, [':articleId' => $articleId]);
    }

    /**
     * Associer un article à une revue
     */
    public function addArticleToRevue($articleId, $revueId) {
        // Vérifier si l'association existe déjà
        $checkSql = "SELECT COUNT(*) as count FROM revue_article 
                     WHERE article_id = :articleId AND revue_id = :revueId";
        
        $check = $this->db->fetchOne($checkSql, [
            ':articleId' => $articleId,
            ':revueId' => $revueId
        ]);
        
        if (($check['count'] ?? 0) === 0) {
            $sql = "INSERT INTO revue_article (article_id, revue_id, created_at, updated_at) 
                    VALUES (:articleId, :revueId, NOW(), NOW())";
            
            return $this->db->execute($sql, [
                ':articleId' => $articleId,
                ':revueId' => $revueId
            ]);
        }
        
        return false;
    }

    /**
     * Retirer un article d'une revue
     */
    public function removeArticleFromRevue($articleId, $revueId) {
        $sql = "DELETE FROM revue_article 
                WHERE article_id = :articleId AND revue_id = :revueId";
        
        return $this->db->execute($sql, [
            ':articleId' => $articleId,
            ':revueId' => $revueId
        ]);
    }


    /**
     * Récupérer les articles en attente de validation
     */
    public function getPendingArticles($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom 
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                WHERE a.statut = 'soumis' 
                ORDER BY a.date_soumission ASC 
                LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, [
            ':limit' => $limit,
            ':offset' => $offset
        ]);
    }

    /**
     * Mettre à jour le fichier d'un article
     */
    public function updateArticleFile($articleId, $filePath) {
        $sql = "UPDATE articles SET fichier_path = :fichier_path, updated_at = NOW() WHERE id = :id";
        return $this->db->execute($sql, [
            ':id' => $articleId,
            ':fichier_path' => $filePath
        ]);
    }

    /**
     * Récupérer les statistiques d'un article
     */
    public function getArticleStatistics($articleId) {
        $stats = [];
        
        // Récupérer l'article
        $article = $this->getArticleById($articleId);
        if (!$article) {
            return $stats;
        }
        
        $stats['article'] = $article;
        
        // Compter le nombre de revues associées
        $sql = "SELECT COUNT(*) as count FROM revue_article WHERE article_id = :articleId";
        $result = $this->db->fetchOne($sql, [':articleId' => $articleId]);
        $stats['revue_count'] = $result['count'] ?? 0;
        
        // Récupérer les revues
        $stats['revues'] = $this->getArticleRevues($articleId);
        
        // Statistiques de téléchargement (approximatif via les téléchargements des revues)
        $totalDownloads = 0;
        foreach ($stats['revues'] as $revue) {
            $downloadSql = "SELECT COUNT(*) as count FROM telechargements WHERE revue_id = :revueId";
            $downloadResult = $this->db->fetchOne($downloadSql, [':revueId' => $revue['id']]);
            $totalDownloads += $downloadResult['count'] ?? 0;
        }
        $stats['estimated_downloads'] = $totalDownloads;
        
        return $stats;
    }

    /**
     * Récupérer les articles les plus récents par auteur
     */
    public function getRecentArticlesByAuthor($authorId, $limit = 5) {
        $sql = "SELECT * FROM articles 
                WHERE auteur_id = :authorId 
                AND statut = 'valide' 
                ORDER BY date_soumission DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, [
            ':authorId' => $authorId,
            ':limit' => $limit
        ]);
    }

    /**
     * Rechercher des articles par période
     */
    public function getArticlesByDateRange($startDate, $endDate, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom 
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                WHERE a.date_soumission BETWEEN :startDate AND :endDate 
                ORDER BY a.date_soumission DESC 
                LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, [
            ':startDate' => $startDate,
            ':endDate' => $endDate,
            ':limit' => $limit,
            ':offset' => $offset
        ]);
    }

    /**
     * Récupérer les articles similaires
     */
    public function getSimilarArticles($articleId, $limit = 5) {
        // Récupérer l'article actuel
        $current = $this->getArticleById($articleId);
        if (!$current) {
            return [];
        }
        
        // Extraire des mots-clés du titre
        $keywords = explode(' ', $current['titre']);
        $searchTerm = implode(' ', array_slice($keywords, 0, 3));
        
        $sql = "SELECT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom 
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                WHERE a.id != :excludeId 
                AND a.statut = 'valide' 
                AND (a.titre LIKE :search OR a.contenu LIKE :search) 
                ORDER BY a.date_soumission DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, [
            ':excludeId' => $articleId,
            ':search' => '%' . $searchTerm . '%',
            ':limit' => $limit
        ]);
    }

    /**
     * Exporter les métadonnées d'un article
     */
    public function exportArticleMetadata($articleId) {
        $article = $this->getArticleById($articleId);
        if (!$article) {
            return null;
        }
        
        // Récupérer toutes les revues associées avec leurs détails
        $revues = $this->getArticleRevues($articleId);
        
        // Pour chaque revue, récupérer les statistiques
        $journalModel = new JournalModel($this->db);
        foreach ($revues as &$revue) {
            $revue['statistics'] = $journalModel->getJournalStatistics($revue['id']);
        }
        
        $metadata = [
            'article' => $article,
            'revues' => $revues,
            'export_date' => date('Y-m-d H:i:s'),
            'format_version' => '1.0'
        ];
        
        return $metadata;
    }

    /**
     * Dupliquer un article
     */
    public function duplicateArticle($articleId, $newAuthorId = null) {
        // Récupérer l'article original
        $original = $this->getArticleById($articleId);
        if (!$original) {
            return false;
        }
        
        // Utiliser l'auteur original ou un nouvel auteur
        $authorId = $newAuthorId ?? $original['auteur_id'];
        
        $sql = "INSERT INTO articles (titre, contenu, fichier_path, auteur_id, statut, date_soumission, created_at, updated_at) 
                VALUES (:titre, :contenu, :fichier_path, :auteur_id, :statut, NOW(), NOW(), NOW())";
        
        $params = [
            ':titre' => $original['titre'] . ' (Copie)',
            ':contenu' => $original['contenu'],
            ':fichier_path' => $original['fichier_path'],
            ':auteur_id' => $authorId,
            ':statut' => 'soumis' // Remettre en soumis pour révision
        ];
        
        $this->db->execute($sql, $params);
        $newArticleId = $this->db->lastInsertId();
        
        // Copier les associations avec les revues
        $revues = $this->getArticleRevues($articleId);
        foreach ($revues as $revue) {
            $this->addArticleToRevue($newArticleId, $revue['id']);
        }
        
        return $newArticleId;
    }

    /**
     * Récupérer les articles par nombre de revues associées
     */
    public function getArticlesByRevueCount($minCount = 1, $maxCount = null, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom,
                       COUNT(ra.revue_id) as revue_count
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                LEFT JOIN revue_article ra ON a.id = ra.article_id 
                GROUP BY a.id 
                HAVING revue_count >= :minCount";
        
        $params = [
            ':minCount' => $minCount,
            ':limit' => $limit,
            ':offset' => $offset
        ];
        
        if ($maxCount !== null) {
            $sql .= " AND revue_count <= :maxCount";
            $params[':maxCount'] = $maxCount;
        }
        
        $sql .= " ORDER BY revue_count DESC, a.date_soumission DESC 
                  LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Compter les articles par année
     */
    public function countArticlesByYear() {
        $sql = "SELECT YEAR(date_soumission) as year, 
                       COUNT(*) as count,
                       SUM(CASE WHEN statut = 'valide' THEN 1 ELSE 0 END) as validated_count,
                       SUM(CASE WHEN statut = 'rejete' THEN 1 ELSE 0 END) as rejected_count
                FROM articles 
                WHERE date_soumission IS NOT NULL 
                GROUP BY YEAR(date_soumission) 
                ORDER BY year DESC";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Récupérer les auteurs les plus prolifiques
     */
    public function getTopAuthors($limit = 10) {
        $sql = "SELECT u.id, u.nom, u.prenom, u.email,
                       COUNT(a.id) as article_count,
                       SUM(CASE WHEN a.statut = 'valide' THEN 1 ELSE 0 END) as validated_count
                FROM users u 
                LEFT JOIN articles a ON u.id = a.auteur_id 
                GROUP BY u.id 
                HAVING article_count > 0 
                ORDER BY article_count DESC, validated_count DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, [':limit' => $limit]);
    }

    /**
     * Vérifier si un auteur peut modifier un article
     */
    public function canAuthorEditArticle($articleId, $authorId) {
        $sql = "SELECT COUNT(*) as count FROM articles 
                WHERE id = :articleId AND auteur_id = :authorId AND statut = 'soumis'";
        
        $result = $this->db->fetchOne($sql, [
            ':articleId' => $articleId,
            ':authorId' => $authorId
        ]);
        
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Récupérer les articles avec leurs fichiers
     */
    public function getArticlesWithFiles($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom 
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                WHERE a.fichier_path IS NOT NULL 
                AND a.fichier_path != '' 
                ORDER BY a.date_soumission DESC 
                LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, [
            ':limit' => $limit,
            ':offset' => $offset
        ]);
    }

    /**
     * Mettre à jour le contenu d'un article
     */
    public function updateArticleContent($articleId, $content) {
        $sql = "UPDATE articles SET contenu = :contenu, updated_at = NOW() WHERE id = :id";
        return $this->db->execute($sql, [
            ':id' => $articleId,
            ':contenu' => $content
        ]);
    }

    /**
     * Récupérer le résumé d'un article (premiers caractères)
     */
    public function getArticleExcerpt($articleId, $length = 200) {
        $article = $this->getArticleById($articleId);
        if (!$article || empty($article['contenu'])) {
            return '';
        }
        
        $content = strip_tags($article['contenu']);
        if (strlen($content) <= $length) {
            return $content;
        }
        
        return substr($content, 0, $length) . '...';
    }

    /**
     * Rechercher des articles avec des filtres avancés
     */
    public function searchArticlesAdvanced($filters, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom 
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                WHERE 1=1";
        
        $params = [];
        
        // Filtre par titre
        if (!empty($filters['titre'])) {
            $sql .= " AND a.titre LIKE :titre";
            $params[':titre'] = '%' . $filters['titre'] . '%';
        }
        
        // Filtre par auteur
        if (!empty($filters['auteur'])) {
            $sql .= " AND (u.nom LIKE :auteur OR u.prenom LIKE :auteur)";
            $params[':auteur'] = '%' . $filters['auteur'] . '%';
        }
        
        // Filtre par statut
        if (!empty($filters['statut'])) {
            $sql .= " AND a.statut = :statut";
            $params[':statut'] = $filters['statut'];
        }
        
        // Filtre par date de début
        if (!empty($filters['date_debut'])) {
            $sql .= " AND a.date_soumission >= :date_debut";
            $params[':date_debut'] = $filters['date_debut'];
        }
        
        // Filtre par date de fin
        if (!empty($filters['date_fin'])) {
            $sql .= " AND a.date_soumission <= :date_fin";
            $params[':date_fin'] = $filters['date_fin'];
        }
        
        // Filtre par contenu
        if (!empty($filters['contenu'])) {
            $sql .= " AND a.contenu LIKE :contenu";
            $params[':contenu'] = '%' . $filters['contenu'] . '%';
        }
        
        // LIMIT et OFFSET doivent être des entiers, pas des paramètres nommés
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        $sql .= " ORDER BY a.date_soumission DESC 
                  LIMIT {$limit} OFFSET {$offset}";
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Vérifier si un titre d'article est unique pour un auteur
     */
    public function isTitleUniqueForAuthor($authorId, $title, $excludeArticleId = null) {
        $sql = "SELECT COUNT(*) as count FROM articles 
                WHERE auteur_id = :authorId AND titre = :title";
        
        if ($excludeArticleId) {
            $sql .= " AND id != :excludeId";
            $result = $this->db->fetchOne($sql, [
                ':authorId' => $authorId,
                ':title' => $title,
                ':excludeId' => $excludeArticleId
            ]);
        } else {
            $result = $this->db->fetchOne($sql, [
                ':authorId' => $authorId,
                ':title' => $title
            ]);
        }
        
        return ($result['count'] ?? 0) === 0;
    }

    /**
     * Assigner un article à un numéro (issue)
     */
    public function assignToIssue($articleId, $issueId) {
        $sql = "UPDATE articles SET issue_id = :issue_id, updated_at = NOW() WHERE id = :article_id";
        
        try {
            return $this->db->execute($sql, [
                ':issue_id' => $issueId,
                ':article_id' => $articleId
            ]);
        } catch (\Exception $e) {
            error_log('Erreur assignToIssue: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Retirer un article d'un numéro
     */
    public function unassignFromIssue($articleId) {
        $sql = "UPDATE articles SET issue_id = NULL, updated_at = NOW() WHERE id = :article_id";
        
        try {
            return $this->db->execute($sql, [':article_id' => $articleId]);
        } catch (\Exception $e) {
            error_log('Erreur unassignFromIssue: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer les articles d'un numéro (issue)
     */
    public function getArticlesByIssue($issueId, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom,
                       u.email as auteur_email
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                WHERE a.issue_id = :issue_id 
                ORDER BY a.date_soumission ASC 
                LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        
        return $this->db->fetchAll($sql, [':issue_id' => $issueId]);
    }

    /**
     * Récupérer les articles non assignés à un numéro
     */
    public function getUnassignedArticles($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT a.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom
                FROM articles a 
                LEFT JOIN users u ON a.auteur_id = u.id 
                WHERE a.issue_id IS NULL 
                  AND a.statut IN ('publie', 'publié', 'valide', 'validé', 'accepte', 'accepté', 'accepted')
                ORDER BY a.date_soumission DESC 
                LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        
        return $this->db->fetchAll($sql);
    }
}
?>