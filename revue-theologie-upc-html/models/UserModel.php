<?php
namespace Models;

/**
 * Modèle utilisateurs (table users).
 */
class UserModel
{
    public static function getByEmail(string $email): ?array
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, nom, prenom, email, password, statut, role FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function getById(int $id): ?array
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, nom, prenom, email, statut, role FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Créer un utilisateur (inscription). Retourne l'id ou null en cas d'erreur.
     */
    public static function create(string $nom, string $prenom, string $email, string $passwordHash, string $role = 'user'): ?int
    {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO users (nom, prenom, email, password, statut, role, created_at, updated_at)
            VALUES (:nom, :prenom, :email, :password, 'actif', :role, NOW(), NOW())
        ");
        $ok = $stmt->execute([
            ':nom'      => $nom,
            ':prenom'   => $prenom,
            ':email'    => $email,
            ':password' => $passwordHash,
            ':role'     => $role,
        ]);
        return $ok ? (int) $db->lastInsertId() : null;
    }

    /** Vérifier si un email est déjà utilisé */
    public static function emailExists(string $email, ?int $excludeId = null): bool
    {
        $db = getDB();
        if ($excludeId !== null) {
            $stmt = $db->prepare("SELECT 1 FROM users WHERE email = :email AND id != :id LIMIT 1");
            $stmt->execute([':email' => $email, ':id' => $excludeId]);
        } else {
            $stmt = $db->prepare("SELECT 1 FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
        }
        return (bool) $stmt->fetchColumn();
    }

    /** Liste des utilisateurs (admin) */
    public static function getAll(int $limit = 100, int $offset = 0): array
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, nom, prenom, email, statut, role, created_at FROM users ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function countAll(): int
    {
        $db = getDB();
        return (int) $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    /** Compter les utilisateurs avec un rôle donné */
    public static function countByRole(string $role): int
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE role = :role AND statut = 'actif'");
        $stmt->execute([':role' => $role]);
        return (int) $stmt->fetchColumn();
    }

    /** Mise à jour par l'admin (sans mot de passe si null) */
    public static function update(int $id, string $nom, string $prenom, string $email, string $role, string $statut, ?string $passwordHash = null): bool
    {
        $db = getDB();
        if ($passwordHash !== null) {
            $stmt = $db->prepare("UPDATE users SET nom = :nom, prenom = :prenom, email = :email, role = :role, statut = :statut, password = :password, updated_at = NOW() WHERE id = :id");
            return $stmt->execute([
                ':id' => $id, ':nom' => $nom, ':prenom' => $prenom, ':email' => $email,
                ':role' => $role, ':statut' => $statut, ':password' => $passwordHash,
            ]);
        }
        $stmt = $db->prepare("UPDATE users SET nom = :nom, prenom = :prenom, email = :email, role = :role, statut = :statut, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([
            ':id' => $id, ':nom' => $nom, ':prenom' => $prenom, ':email' => $email, ':role' => $role, ':statut' => $statut,
        ]);
    }
}
