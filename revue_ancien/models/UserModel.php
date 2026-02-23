<?php
namespace Models;

class UserModel {
    private $db;

    public function __construct(\Models\Database $db) {
        $this->db = $db;
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function createUser($data) {
        // Si plusieurs arguments sont passés, prendre le premier (tableau)
        if (func_num_args() > 1) {
            $data = func_get_arg(0);
        }
        
        // S'assurer que $data est un tableau
        if (!is_array($data)) {
            return false;
        }
        
        $sql = "INSERT INTO users (nom, prenom, email, password, statut, role, created_at, updated_at) 
                VALUES (:nom, :prenom, :email, :password, :statut, :role, NOW(), NOW())";
        
        $params = [
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':statut' => $data['statut'] ?? 'actif',
            ':role' => $data['role'] ?? 'user'
        ];
        
        return $this->db->execute($sql, $params);
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function updateUser($id, $data) {
        // Construire dynamiquement la requête SQL selon les champs fournis
        $fields = [];
        $params = [':id' => $id];
        
        if (isset($data['nom'])) {
            $fields[] = 'nom = :nom';
            $params[':nom'] = $data['nom'];
        }
        if (isset($data['prenom'])) {
            $fields[] = 'prenom = :prenom';
            $params[':prenom'] = $data['prenom'];
        }
        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $params[':email'] = $data['email'];
        }
        if (isset($data['statut'])) {
            $fields[] = 'statut = :statut';
            $params[':statut'] = $data['statut'];
        }
        if (isset($data['role'])) {
            $fields[] = 'role = :role';
            $params[':role'] = $data['role'];
        }
        if (isset($data['password'])) {
            $fields[] = 'password = :password';
            $params[':password'] = $data['password'];
        }
        
        if (empty($fields)) {
            return false; // Aucun champ à mettre à jour
        }
        
        $fields[] = 'updated_at = NOW()';
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        
        return $this->db->execute($sql, $params);
    }

    /**
     * Supprimer un utilisateur (soft delete - mise à jour du statut)
     */
    public function deleteUser($id) {
        $sql = "UPDATE users SET statut = 'suspendu', updated_at = NOW() WHERE id = :id";
        return $this->db->execute($sql, [':id' => $id]);
    }

    /**
     * Récupérer un utilisateur par ID
     */
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        return $this->db->fetchOne($sql, [':id' => $id]);
    }

    /**
     * Récupérer un utilisateur par email
     */
    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        return $this->db->fetchOne($sql, [':email' => $email]);
    }

    /**
     * Récupérer tous les utilisateurs avec pagination
     */
    public function getAllUsers($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT * FROM users ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, [
            ':limit' => $limit,
            ':offset' => $offset
        ]);
    }

    /**
     * Récupérer les utilisateurs par statut
     */
    public function getUsersByStatus($status) {
        $sql = "SELECT * FROM users WHERE statut = :status ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, [':status' => $status]);
    }

    /**
     * Rechercher des utilisateurs
     */
    public function searchUsers($searchTerm) {
        $sql = "SELECT * FROM users 
                WHERE nom LIKE :search OR 
                      prenom LIKE :search OR 
                      email LIKE :search 
                ORDER BY created_at DESC";
        
        return $this->db->fetchAll($sql, [
            ':search' => '%' . $searchTerm . '%'
        ]);
    }

    /**
     * Compter le nombre total d'utilisateurs
     */
    public function countUsers() {
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = $this->db->fetchOne($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Compter les utilisateurs par statut
     */
    public function countUsersByStatus($status) {
        $sql = "SELECT COUNT(*) as total FROM users WHERE statut = :status";
        $result = $this->db->fetchOne($sql, [':status' => $status]);
        return $result['total'] ?? 0;
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword($userId, $newPassword) {
        $sql = "UPDATE users SET password = :password, updated_at = NOW() WHERE id = :id";
        
        return $this->db->execute($sql, [
            ':id' => $userId,
            ':password' => $newPassword
        ]);
    }

    /**
     * Vérifier si l'email existe déjà (pour l'inscription)
     */
    public function emailExists($email, $excludeUserId = null) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE email = :email";
        
        if ($excludeUserId) {
            $sql .= " AND id != :exclude_id";
            $result = $this->db->fetchOne($sql, [
                ':email' => $email,
                ':exclude_id' => $excludeUserId
            ]);
        } else {
            $result = $this->db->fetchOne($sql, [':email' => $email]);
        }
        
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Activer/désactiver un utilisateur
     */
    public function toggleUserStatus($userId, $status) {
        $sql = "UPDATE users SET statut = :status, updated_at = NOW() WHERE id = :id";
        
        return $this->db->execute($sql, [
            ':id' => $userId,
            ':status' => $status
        ]);
    }

    /**
     * Mettre à jour le token de rappel
     */
    public function updateRememberToken($userId, $token) {
        $sql = "UPDATE users SET remember_token = :token, updated_at = NOW() WHERE id = :id";
        
        return $this->db->execute($sql, [
            ':id' => $userId,
            ':token' => $token
        ]);
    }

    /**
     * Récupérer l'abonnement d'un utilisateur
     */
    public function getUserSubscription($userId) {
        $sql = "SELECT * FROM abonnements WHERE utilisateur_id = :userId ORDER BY created_at DESC LIMIT 1";
        return $this->db->fetchOne($sql, [':userId' => $userId]);
    }

    /**
     * Récupérer les articles d'un utilisateur (auteur)
     */
    public function getUserArticles($userId) {
        $sql = "SELECT * FROM articles WHERE auteur_id = :userId ORDER BY date_soumission DESC";
        return $this->db->fetchAll($sql, [':userId' => $userId]);
    }

    /**
     * Récupérer les commentaires d'un utilisateur
     */
    public function getUserComments($userId) {
        $sql = "SELECT c.*, r.titre as revue_titre 
                FROM commentaires c 
                JOIN revues r ON c.revue_id = r.id 
                WHERE c.utilisateur_id = :userId 
                ORDER BY c.created_at DESC";
        
        return $this->db->fetchAll($sql, [':userId' => $userId]);
    }

    /**
     * Récupérer les téléchargements d'un utilisateur
     */
    public function getUserDownloads($userId) {
        $sql = "SELECT t.*, r.titre as revue_titre 
                FROM telechargements t 
                JOIN revues r ON t.revue_id = r.id 
                WHERE t.utilisateur_id = :userId 
                ORDER BY t.date_heure DESC";
        
        return $this->db->fetchAll($sql, [':userId' => $userId]);
    }

    /**
     * Récupérer les notes données par un utilisateur
     */
    public function getUserRatings($userId) {
        $sql = "SELECT n.*, r.titre as revue_titre 
                FROM notes n 
                JOIN revues r ON n.revue_id = r.id 
                WHERE n.user_id = :userId 
                ORDER BY n.created_at DESC";
        
        return $this->db->fetchAll($sql, [':userId' => $userId]);
    }

    /**
     * Vérifier les permissions d'un utilisateur via les rôles
     */
    public function getUserRoles($userId) {
        $sql = "SELECT r.* 
                FROM model_has_roles mhr 
                JOIN roles r ON mhr.role_id = r.id 
                WHERE mhr.model_id = :userId AND mhr.model_type = 'Models\\UserModel'";
        
        return $this->db->fetchAll($sql, [':userId' => $userId]);
    }

    /**
     * Vérifier si un utilisateur a un rôle spécifique
     */
    public function hasRole($userId, $roleName) {
        $sql = "SELECT COUNT(*) as count 
                FROM model_has_roles mhr 
                JOIN roles r ON mhr.role_id = r.id 
                WHERE mhr.model_id = :userId 
                AND mhr.model_type = 'Models\\UserModel' 
                AND r.name = :roleName";
        
        $result = $this->db->fetchOne($sql, [
            ':userId' => $userId,
            ':roleName' => $roleName
        ]);
        
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Vérifier si un utilisateur a une permission spécifique
     */
    public function hasPermission($userId, $permissionName) {
        $sql = "SELECT COUNT(*) as count 
                FROM model_has_permissions mhp 
                JOIN permissions p ON mhp.permission_id = p.id 
                WHERE mhp.model_id = :userId 
                AND mhp.model_type = 'Models\\UserModel' 
                AND p.name = :permissionName";
        
        $result = $this->db->fetchOne($sql, [
            ':userId' => $userId,
            ':permissionName' => $permissionName
        ]);
        
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Assigner un rôle à un utilisateur
     */
    public function assignRole($userId, $roleId) {
        // Vérifier d'abord si le rôle existe déjà
        $checkSql = "SELECT COUNT(*) as count 
                     FROM model_has_roles 
                     WHERE model_id = :userId 
                     AND model_type = 'Models\\UserModel' 
                     AND role_id = :roleId";
        
        $check = $this->db->fetchOne($checkSql, [
            ':userId' => $userId,
            ':roleId' => $roleId
        ]);
        
        if (($check['count'] ?? 0) === 0) {
            $sql = "INSERT INTO model_has_roles (role_id, model_type, model_id) 
                    VALUES (:roleId, 'Models\\UserModel', :userId)";
            
            return $this->db->execute($sql, [
                ':roleId' => $roleId,
                ':userId' => $userId
            ]);
        }
        
        return false; // Le rôle existe déjà
    }

    /**
     * Retirer un rôle à un utilisateur
     */
    public function removeRole($userId, $roleId) {
        $sql = "DELETE FROM model_has_roles 
                WHERE model_id = :userId 
                AND model_type = 'Models\\UserModel' 
                AND role_id = :roleId";
        
        return $this->db->execute($sql, [
            ':userId' => $userId,
            ':roleId' => $roleId
        ]);
    }

    /**
     * Vérifier si un utilisateur est abonné et actif
     */
    public function isSubscribedAndActive($userId) {
        $sql = "SELECT * FROM abonnements 
                WHERE utilisateur_id = :userId 
                AND statut = 'actif' 
                AND date_fin >= CURDATE() 
                LIMIT 1";
        
        $subscription = $this->db->fetchOne($sql, [':userId' => $userId]);
        return !empty($subscription);
    }

    /**
     * Récupérer les statistiques d'un utilisateur
     */
    public function getUserStatistics($userId) {
        $stats = [];
        
        // Nombre d'articles soumis
        $sql = "SELECT COUNT(*) as count FROM articles WHERE auteur_id = :userId";
        $result = $this->db->fetchOne($sql, [':userId' => $userId]);
        $stats['articles_count'] = $result['count'] ?? 0;
        
        // Nombre d'articles validés
        $sql = "SELECT COUNT(*) as count FROM articles WHERE auteur_id = :userId AND statut = 'valide'";
        $result = $this->db->fetchOne($sql, [':userId' => $userId]);
        $stats['validated_articles_count'] = $result['count'] ?? 0;
        
        // Nombre de commentaires
        $sql = "SELECT COUNT(*) as count FROM commentaires WHERE utilisateur_id = :userId";
        $result = $this->db->fetchOne($sql, [':userId' => $userId]);
        $stats['comments_count'] = $result['count'] ?? 0;
        
        // Nombre de téléchargements
        $sql = "SELECT COUNT(*) as count FROM telechargements WHERE utilisateur_id = :userId";
        $result = $this->db->fetchOne($sql, [':userId' => $userId]);
        $stats['downloads_count'] = $result['count'] ?? 0;
        
        // Nombre de notes données
        $sql = "SELECT COUNT(*) as count FROM notes WHERE user_id = :userId";
        $result = $this->db->fetchOne($sql, [':userId' => $userId]);
        $stats['ratings_count'] = $result['count'] ?? 0;
        
        return $stats;
    }

    /**
     * Récupérer l'historique d'activité d'un utilisateur
     */
    public function getUserActivityHistory($userId, $limit = 50) {
        // Cette requête combine différentes activités de l'utilisateur
        $sql = "
            (SELECT 'article' as type, id as item_id, titre as title, date_soumission as activity_date, statut
             FROM articles 
             WHERE auteur_id = :userId)
            UNION ALL
            (SELECT 'commentaire' as type, id as item_id, SUBSTRING(contenu, 1, 100) as title, created_at as activity_date, NULL as statut
             FROM commentaires 
             WHERE utilisateur_id = :userId)
            UNION ALL
            (SELECT 'telechargement' as type, id as item_id, NULL as title, date_heure as activity_date, NULL as statut
             FROM telechargements 
             WHERE utilisateur_id = :userId)
            UNION ALL
            (SELECT 'note' as type, id as item_id, CONCAT('Note: ', valeur, '/5') as title, created_at as activity_date, NULL as statut
             FROM notes 
             WHERE user_id = :userId)
            ORDER BY activity_date DESC 
            LIMIT :limit
        ";
        
        return $this->db->fetchAll($sql, [
            ':userId' => $userId,
            ':limit' => $limit
        ]);
    }

    /**
     * Déconnecter un utilisateur (supprimer les sessions)
     */
    public function logoutUser($userId) {
        $sql = "DELETE FROM sessions WHERE user_id = :userId";
        return $this->db->execute($sql, [':userId' => $userId]);
    }

    /**
     * Récupérer les notifications d'un utilisateur
     */
    public function getUserNotifications($userId, $unreadOnly = false) {
        $sql = "SELECT * FROM notifications 
                WHERE notifiable_id = :userId 
                AND notifiable_type = 'Models\\UserModel'";
        
        if ($unreadOnly) {
            $sql .= " AND read_at IS NULL";
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        return $this->db->fetchAll($sql, [':userId' => $userId]);
    }

    /**
     * Marquer les notifications comme lues
     */
    public function markNotificationsAsRead($userId, $notificationIds = []) {
        if (empty($notificationIds)) {
            // Marquer toutes les notifications comme lues
            $sql = "UPDATE notifications 
                    SET read_at = NOW() 
                    WHERE notifiable_id = :userId 
                    AND notifiable_type = 'Models\\UserModel' 
                    AND read_at IS NULL";
            
            return $this->db->execute($sql, [':userId' => $userId]);
        } else {
            // Marquer des notifications spécifiques
            $placeholders = implode(',', array_fill(0, count($notificationIds), '?'));
            $sql = "UPDATE notifications 
                    SET read_at = NOW() 
                    WHERE id IN ($placeholders) 
                    AND notifiable_id = ? 
                    AND notifiable_type = 'Models\\UserModel'";
            
            $params = array_merge($notificationIds, [$userId]);
            return $this->db->execute($sql, $params);
        }
    }
}
?>