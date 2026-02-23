<?php
namespace Models;

class JournalModel {
    private $db;

    public function __construct(\Models\Database $db) {
        $this->db = $db;
    }

    /**
     * Créer une nouvelle revue
     */
    public function createJournal($data) {
        $sql = "INSERT INTO revues (numero, titre, description, fichier_path, date_publication, created_at, updated_at) 
                VALUES (:numero, :titre, :description, :fichier_path, :date_publication, NOW(), NOW())";
        
        $params = [
            ':numero' => $data['numero'],
            ':titre' => $data['titre'],
            ':description' => $data['description'] ?? null,
            ':fichier_path' => $data['fichier_path'] ?? null,
            ':date_publication' => $data['date_publication'] ?? null
        ];
        
        $this->db->execute($sql, $params);
        return $this->db->lastInsertId();
    }

    /**
     * Mettre à jour une revue
     */
    public function updateJournal($id, $data) {
        $sql = "UPDATE revues SET 
                numero = :numero, 
                titre = :titre, 
                description = :description, 
                fichier_path = :fichier_path, 
                date_publication = :date_publication, 
                updated_at = NOW() 
                WHERE id = :id";
        
        $params = [
            ':id' => $id,
            ':numero' => $data['numero'],
            ':titre' => $data['titre'],
            ':description' => $data['description'] ?? null,
            ':fichier_path' => $data['fichier_path'] ?? null,
            ':date_publication' => $data['date_publication'] ?? null
        ];
        
        return $this->db->execute($sql, $params);
    }

    /**
     * Supprimer une revue
     */
    public function deleteJournal($id) {
        $sql = "DELETE FROM revues WHERE id = :id";
        return $this->db->execute($sql, [':id' => $id]);
    }

    /**
     * Récupérer une revue par ID
     */
    public function getJournalById($id) {
        $sql = "SELECT * FROM revues WHERE id = :id";
        return $this->db->fetchOne($sql, [':id' => $id]);
    }

    /**
     * Récupérer une revue par numéro
     */
    public function getJournalByNumber($number) {
        $sql = "SELECT * FROM revues WHERE numero = :numero";
        return $this->db->fetchOne($sql, [':numero' => $number]);
    }

    /**
     * Récupérer toutes les revues avec pagination
     */
    public function getAllJournals($page = 1, $limit = 20, $orderBy = 'date_publication', $order = 'DESC') {
        $offset = ($page - 1) * $limit;
        
        $validOrders = ['date_publication', 'created_at', 'numero', 'titre'];
        $orderBy = in_array($orderBy, $validOrders) ? $orderBy : 'date_publication';
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
        
        $sql = "SELECT * FROM revues ORDER BY $orderBy $order LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, [
            ':limit' => $limit,
            ':offset' => $offset
        ]);
    }

    /**
     * Récupérer les revues récentes
     */
    public function getRecentJournals($limit = 5) {
        $sql = "SELECT * FROM revues ORDER BY date_publication DESC LIMIT :limit";
        return $this->db->fetchAll($sql, [':limit' => $limit]);
    }

    /**
     * Rechercher des revues
     */
    public function searchJournals($searchTerm, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT * FROM revues 
                WHERE titre LIKE :search OR 
                      description LIKE :search OR 
                      numero LIKE :search 
                ORDER BY date_publication DESC 
                LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, [
            ':search' => '%' . $searchTerm . '%',
            ':limit' => $limit,
            ':offset' => $offset
        ]);
    }

    /**
     * Compter le nombre total de revues
     */
    public function countJournals() {
        $sql = "SELECT COUNT(*) as total FROM revues";
        $result = $this->db->fetchOne($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Compter les résultats de recherche
     */
    public function countSearchResults($searchTerm) {
        $sql = "SELECT COUNT(*) as total FROM revues 
                WHERE titre LIKE :search OR 
                      description LIKE :search OR 
                      numero LIKE :search";
        
        $result = $this->db->fetchOne($sql, [':search' => '%' . $searchTerm . '%']);
        return $result['total'] ?? 0;
    }

    /**
     * Récupérer les articles d'une revue
     */
    public function getJournalArticles($journalId) {
        $sql = "SELECT a.* 
                FROM articles a 
                JOIN revue_article ra ON a.id = ra.article_id 
                WHERE ra.revue_id = :journalId 
                ORDER BY ra.created_at ASC";
        
        return $this->db->fetchAll($sql, [':journalId' => $journalId]);
    }

    /**
     * Ajouter un article à une revue
     */
    public function addArticleToJournal($journalId, $articleId) {
        // Vérifier si l'association existe déjà
        $checkSql = "SELECT COUNT(*) as count FROM revue_article 
                     WHERE revue_id = :journalId AND article_id = :articleId";
        
        $check = $this->db->fetchOne($checkSql, [
            ':journalId' => $journalId,
            ':articleId' => $articleId
        ]);
        
        if (($check['count'] ?? 0) === 0) {
            $sql = "INSERT INTO revue_article (revue_id, article_id, created_at, updated_at) 
                    VALUES (:journalId, :articleId, NOW(), NOW())";
            
            return $this->db->execute($sql, [
                ':journalId' => $journalId,
                ':articleId' => $articleId
            ]);
        }
        
        return false;
    }

    /**
     * Retirer un article d'une revue
     */
    public function removeArticleFromJournal($journalId, $articleId) {
        $sql = "DELETE FROM revue_article 
                WHERE revue_id = :journalId AND article_id = :articleId";
        
        return $this->db->execute($sql, [
            ':journalId' => $journalId,
            ':articleId' => $articleId
        ]);
    }

    /**
     * Récupérer les parties d'une revue (revue_parts)
     */
    public function getJournalParts($journalId) {
        $sql = "SELECT * FROM revue_parts 
                WHERE revue_id = :journalId 
                ORDER BY ordre ASC";
        
        return $this->db->fetchAll($sql, [':journalId' => $journalId]);
    }

    /**
     * Ajouter une partie à une revue
     */
    public function addJournalPart($journalId, $data) {
        // Trouver le prochain ordre disponible
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
     * Mettre à jour l'ordre des parties
     */
    public function updatePartOrder($partId, $newOrder) {
        $sql = "UPDATE revue_parts SET ordre = :ordre, updated_at = NOW() WHERE id = :id";
        return $this->db->execute($sql, [
            ':id' => $partId,
            ':ordre' => $newOrder
        ]);
    }

    /**
     * Supprimer une partie
     */
    public function deleteJournalPart($partId) {
        $sql = "DELETE FROM revue_parts WHERE id = :id";
        return $this->db->execute($sql, [':id' => $partId]);
    }

    /**
     * Récupérer les photos d'une revue
     */
    public function getJournalPhotos($journalId) {
        $sql = "SELECT * FROM revue_photos 
                WHERE revue_id = :journalId 
                ORDER BY created_at ASC";
        
        return $this->db->fetchAll($sql, [':journalId' => $journalId]);
    }

    /**
     * Ajouter une photo à une revue
     */
    public function addJournalPhoto($journalId, $data) {
        $sql = "INSERT INTO revue_photos (revue_id, path, caption, created_at, updated_at) 
                VALUES (:journalId, :path, :caption, NOW(), NOW())";
        
        return $this->db->execute($sql, [
            ':journalId' => $journalId,
            ':path' => $data['path'],
            ':caption' => $data['caption'] ?? null
        ]);
    }

    /**
     * Supprimer une photo
     */
    public function deleteJournalPhoto($photoId) {
        $sql = "DELETE FROM revue_photos WHERE id = :id";
        return $this->db->execute($sql, [':id' => $photoId]);
    }

    /**
     * Récupérer les commentaires d'une revue
     */
    public function getJournalComments($journalId, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT c.*, u.nom, u.prenom 
                FROM commentaires c 
                JOIN users u ON c.utilisateur_id = u.id 
                WHERE c.revue_id = :journalId 
                ORDER BY c.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, [
            ':journalId' => $journalId,
            ':limit' => $limit,
            ':offset' => $offset
        ]);
    }

    /**
     * Ajouter un commentaire à une revue
     */
    public function addComment($journalId, $userId, $content) {
        $sql = "INSERT INTO commentaires (utilisateur_id, revue_id, contenu, created_at, updated_at) 
                VALUES (:userId, :journalId, :contenu, NOW(), NOW())";
        
        return $this->db->execute($sql, [
            ':userId' => $userId,
            ':journalId' => $journalId,
            ':contenu' => $content
        ]);
    }

    /**
     * Supprimer un commentaire
     */
    public function deleteComment($commentId) {
        $sql = "DELETE FROM commentaires WHERE id = :id";
        return $this->db->execute($sql, [':id' => $commentId]);
    }

    /**
     * Récupérer les notes d'une revue
     */
    public function getJournalRatings($journalId) {
        $sql = "SELECT n.*, u.nom, u.prenom 
                FROM notes n 
                JOIN users u ON n.user_id = u.id 
                WHERE n.revue_id = :journalId 
                ORDER BY n.created_at DESC";
        
        return $this->db->fetchAll($sql, [':journalId' => $journalId]);
    }

    /**
     * Ajouter/mettre à jour une note
     */
    public function setRating($journalId, $userId, $rating) {
        // Vérifier si l'utilisateur a déjà noté
        $checkSql = "SELECT id FROM notes WHERE revue_id = :journalId AND user_id = :userId";
        $existing = $this->db->fetchOne($checkSql, [
            ':journalId' => $journalId,
            ':userId' => $userId
        ]);
        
        if ($existing) {
            // Mettre à jour la note existante
            $sql = "UPDATE notes SET valeur = :rating, updated_at = NOW() 
                    WHERE id = :id";
            return $this->db->execute($sql, [
                ':id' => $existing['id'],
                ':rating' => $rating
            ]);
        } else {
            // Créer une nouvelle note
            $sql = "INSERT INTO notes (revue_id, user_id, valeur, created_at, updated_at) 
                    VALUES (:journalId, :userId, :rating, NOW(), NOW())";
            return $this->db->execute($sql, [
                ':journalId' => $journalId,
                ':userId' => $userId,
                ':rating' => $rating
            ]);
        }
    }

    /**
     * Obtenir la note moyenne d'une revue
     */
    public function getAverageRating($journalId) {
        $sql = "SELECT AVG(valeur) as average, COUNT(*) as count 
                FROM notes 
                WHERE revue_id = :journalId";
        
        $result = $this->db->fetchOne($sql, [':journalId' => $journalId]);
        return [
            'average' => round($result['average'] ?? 0, 1),
            'count' => $result['count'] ?? 0
        ];
    }

    /**
     * Vérifier si un utilisateur a noté une revue
     */
    public function getUserRating($journalId, $userId) {
        $sql = "SELECT * FROM notes 
                WHERE revue_id = :journalId AND user_id = :userId";
        
        return $this->db->fetchOne($sql, [
            ':journalId' => $journalId,
            ':userId' => $userId
        ]);
    }

    /**
     * Récupérer les téléchargements d'une revue
     */
    public function getJournalDownloads($journalId, $page = 1, $limit = 50) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT t.*, u.nom, u.prenom, u.email 
                FROM telechargements t 
                JOIN users u ON t.utilisateur_id = u.id 
                WHERE t.revue_id = :journalId 
                ORDER BY t.date_heure DESC 
                LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, [
            ':journalId' => $journalId,
            ':limit' => $limit,
            ':offset' => $offset
        ]);
    }

    /**
     * Enregistrer un téléchargement
     */
    public function recordDownload($journalId, $userId, $ip = null, $userAgent = null) {
        $sql = "INSERT INTO telechargements (utilisateur_id, revue_id, date_heure, ip, user_agent, created_at, updated_at) 
                VALUES (:userId, :journalId, NOW(), :ip, :userAgent, NOW(), NOW())";
        
        return $this->db->execute($sql, [
            ':userId' => $userId,
            ':journalId' => $journalId,
            ':ip' => $ip,
            ':userAgent' => $userAgent
        ]);
    }

    /**
     * Compter les téléchargements d'une revue
     */
    public function countDownloads($journalId) {
        $sql = "SELECT COUNT(*) as total FROM telechargements WHERE revue_id = :journalId";
        $result = $this->db->fetchOne($sql, [':journalId' => $journalId]);
        return $result['total'] ?? 0;
    }

    /**
     * Obtenir les statistiques d'une revue
     */
    public function getJournalStatistics($journalId) {
        $stats = [];
        
        // Nombre d'articles
        $sql = "SELECT COUNT(*) as count FROM revue_article WHERE revue_id = :journalId";
        $result = $this->db->fetchOne($sql, [':journalId' => $journalId]);
        $stats['articles_count'] = $result['count'] ?? 0;
        
        // Nombre de commentaires
        $sql = "SELECT COUNT(*) as count FROM commentaires WHERE revue_id = :journalId";
        $result = $this->db->fetchOne($sql, [':journalId' => $journalId]);
        $stats['comments_count'] = $result['count'] ?? 0;
        
        // Nombre de téléchargements
        $stats['downloads_count'] = $this->countDownloads($journalId);
        
        // Note moyenne
        $ratingStats = $this->getAverageRating($journalId);
        $stats['average_rating'] = $ratingStats['average'];
        $stats['ratings_count'] = $ratingStats['count'];
        
        // Nombre de parties
        $sql = "SELECT COUNT(*) as count FROM revue_parts WHERE revue_id = :journalId";
        $result = $this->db->fetchOne($sql, [':journalId' => $journalId]);
        $stats['parts_count'] = $result['count'] ?? 0;
        
        // Nombre de photos
        $sql = "SELECT COUNT(*) as count FROM revue_photos WHERE revue_id = :journalId";
        $result = $this->db->fetchOne($sql, [':journalId' => $journalId]);
        $stats['photos_count'] = $result['count'] ?? 0;
        
        return $stats;
    }

    /**
     * Récupérer les revues les plus téléchargées
     */
    public function getMostDownloadedJournals($limit = 10) {
        $sql = "SELECT r.*, COUNT(t.id) as download_count 
                FROM revues r 
                LEFT JOIN telechargements t ON r.id = t.revue_id 
                GROUP BY r.id 
                ORDER BY download_count DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, [':limit' => $limit]);
    }

    /**
     * Récupérer les revues les mieux notées
     */
    public function getTopRatedJournals($limit = 10) {
        $sql = "SELECT r.*, AVG(n.valeur) as average_rating, COUNT(n.id) as rating_count 
                FROM revues r 
                LEFT JOIN notes n ON r.id = n.revue_id 
                GROUP BY r.id 
                HAVING rating_count > 0 
                ORDER BY average_rating DESC, rating_count DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, [':limit' => $limit]);
    }

    /**
     * Récupérer les revues par année de publication
     */
    public function getJournalsByYear($year) {
        $sql = "SELECT * FROM revues 
                WHERE YEAR(date_publication) = :year 
                ORDER BY date_publication DESC";
        
        return $this->db->fetchAll($sql, [':year' => $year]);
    }

    /**
     * Obtenir les années disponibles
     */
    public function getAvailableYears() {
        $sql = "SELECT DISTINCT YEAR(date_publication) as year 
                FROM revues 
                WHERE date_publication IS NOT NULL 
                ORDER BY year DESC";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Vérifier si un utilisateur peut télécharger une revue
     */
    public function canUserDownloadJournal($journalId, $userId) {
        // Vérifier si l'utilisateur est abonné et actif
        $userModel = new UserModel($this->db);
        $isSubscribed = $userModel->isSubscribedAndActive($userId);
        
        // Vérifier si la revue a des parties gratuites
        $sql = "SELECT COUNT(*) as count FROM revue_parts 
                WHERE revue_id = :journalId AND is_free_preview = 1";
        
        $result = $this->db->fetchOne($sql, [':journalId' => $journalId]);
        $hasFreeParts = ($result['count'] ?? 0) > 0;
        
        return $isSubscribed || $hasFreeParts;
    }

    /**
     * Récupérer les parties gratuites d'une revue
     */
    public function getFreeParts($journalId) {
        $sql = "SELECT * FROM revue_parts 
                WHERE revue_id = :journalId AND is_free_preview = 1 
                ORDER BY ordre ASC";
        
        return $this->db->fetchAll($sql, [':journalId' => $journalId]);
    }

    /**
     * Marquer une partie comme gratuite ou payante
     */
    public function setPartFreePreview($partId, $isFree) {
        $sql = "UPDATE revue_parts SET is_free_preview = :isFree, updated_at = NOW() 
                WHERE id = :id";
        
        return $this->db->execute($sql, [
            ':id' => $partId,
            ':isFree' => $isFree ? 1 : 0
        ]);
    }

    /**
     * Récupérer les revues similaires
     */
    public function getSimilarJournals($journalId, $limit = 5) {
        // D'abord récupérer la revue actuelle
        $current = $this->getJournalById($journalId);
        
        if (!$current) {
            return [];
        }
        
        // Rechercher des revues avec des mots-clés similaires
        $keywords = explode(' ', $current['titre']);
        $searchTerm = implode(' ', array_slice($keywords, 0, 3));
        
        $sql = "SELECT * FROM revues 
                WHERE id != :excludeId 
                AND (titre LIKE :search OR description LIKE :search) 
                ORDER BY date_publication DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, [
            ':excludeId' => $journalId,
            ':search' => '%' . $searchTerm . '%',
            ':limit' => $limit
        ]);
    }

    /**
     * Exporter les métadonnées d'une revue
     */
    public function exportJournalMetadata($journalId) {
        $journal = $this->getJournalById($journalId);
        if (!$journal) {
            return null;
        }
        
        $metadata = [
            'journal' => $journal,
            'articles' => $this->getJournalArticles($journalId),
            'parts' => $this->getJournalParts($journalId),
            'statistics' => $this->getJournalStatistics($journalId),
            'ratings' => $this->getAverageRating($journalId)
        ];
        
        return $metadata;
    }

    /**
     * Vérifier l'unicité du numéro de revue
     */
    public function isNumberUnique($number, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM revues WHERE numero = :numero";
        
        if ($excludeId) {
            $sql .= " AND id != :excludeId";
            $result = $this->db->fetchOne($sql, [
                ':numero' => $number,
                ':excludeId' => $excludeId
            ]);
        } else {
            $result = $this->db->fetchOne($sql, [':numero' => $number]);
        }
        
        return ($result['count'] ?? 0) === 0;
    }
}
?>