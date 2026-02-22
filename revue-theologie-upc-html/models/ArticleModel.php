<?php
namespace Models;

/**
 * Modèle articles (table articles + jointure users pour auteur).
 */
class ArticleModel
{
    /** Articles avec statut "valide" = publiés / visibles publiquement */
    public static function getPublished(int $limit = 50, int $offset = 0): array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT a.id, a.titre, a.contenu, a.fichier_path, a.statut, a.date_soumission, a.issue_id,
                   u.nom AS auteur_nom, u.prenom AS auteur_prenom
            FROM articles a
            LEFT JOIN users u ON u.id = a.auteur_id
            WHERE a.statut = 'valide'
            ORDER BY a.date_soumission DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getById(int $id): ?array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT a.*, u.nom AS auteur_nom, u.prenom AS auteur_prenom, u.email AS auteur_email
            FROM articles a
            LEFT JOIN users u ON u.id = a.auteur_id
            WHERE a.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Derniers articles publiés (pour l'accueil) */
    public static function getLatest(int $limit = 6): array
    {
        return self::getPublished($limit, 0);
    }

    /** Nombre total d'articles publiés */
    public static function countPublished(): int
    {
        $db = getDB();
        $stmt = $db->query("SELECT COUNT(*) FROM articles WHERE statut = 'valide'");
        return (int) $stmt->fetchColumn();
    }

    /** Articles publiés par issue_id (numéro) */
    public static function getByIssueId(int $issueId): array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT a.id, a.titre, a.date_soumission, u.nom AS auteur_nom, u.prenom AS auteur_prenom
            FROM articles a
            LEFT JOIN users u ON u.id = a.auteur_id
            WHERE a.statut = 'valide' AND a.issue_id = :iid
            ORDER BY a.date_soumission ASC
        ");
        $stmt->execute([':iid' => $issueId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** Articles d'un auteur (tous statuts) */
    public static function getByAuthorId(int $authorId, int $limit = 50, int $offset = 0): array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT id, titre, contenu, fichier_path, statut, date_soumission, issue_id
            FROM articles WHERE auteur_id = :aid ORDER BY date_soumission DESC LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':aid', $authorId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** Un article par ID uniquement si appartient à l'auteur */
    public static function getByIdForAuthor(int $id, int $authorId): ?array
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM articles WHERE id = :id AND auteur_id = :aid");
        $stmt->execute([':id' => $id, ':aid' => $authorId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Nombre d'articles par auteur (tous statuts) */
    public static function countByAuthorId(int $authorId): int
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM articles WHERE auteur_id = :aid");
        $stmt->execute([':aid' => $authorId]);
        return (int) $stmt->fetchColumn();
    }

    /** Nombre d'articles par auteur et statut */
    public static function countByAuthorIdAndStatut(int $authorId, string $statut): int
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM articles WHERE auteur_id = :aid AND statut = :statut");
        $stmt->execute([':aid' => $authorId, ':statut' => $statut]);
        return (int) $stmt->fetchColumn();
    }

    /** Créer un article (soumission) */
    public static function create(int $auteurId, string $titre, string $contenu, ?string $fichierPath = null): ?int
    {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO articles (titre, contenu, fichier_path, auteur_id, statut, date_soumission, created_at, updated_at)
            VALUES (:titre, :contenu, :fichier_path, :auteur_id, 'soumis', NOW(), NOW(), NOW())
        ");
        $ok = $stmt->execute([
            ':titre'        => $titre,
            ':contenu'      => $contenu,
            ':fichier_path' => $fichierPath,
            ':auteur_id'    => $auteurId,
        ]);
        return $ok ? (int) $db->lastInsertId() : null;
    }

    /** Mettre à jour un article (seulement si statut = soumis et appartient à l'auteur) */
    public static function updateByAuthor(int $id, int $authorId, string $titre, string $contenu, ?string $fichierPath = null): bool
    {
        $db = getDB();
        $stmt = $db->prepare("
            UPDATE articles SET titre = :titre, contenu = :contenu, updated_at = NOW()
            " . ($fichierPath !== null ? ", fichier_path = :fichier_path" : "") . "
            WHERE id = :id AND auteur_id = :aid AND statut = 'soumis'
        ");
        $params = [':titre' => $titre, ':contenu' => $contenu, ':id' => $id, ':aid' => $authorId];
        if ($fichierPath !== null) {
            $params[':fichier_path'] = $fichierPath;
        }
        return $stmt->execute($params);
    }

    /** Liste tous les articles pour l'admin (avec auteur) */
    public static function getAllForAdmin(int $limit = 50, int $offset = 0): array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT a.id, a.titre, a.statut, a.date_soumission, a.issue_id,
                   u.nom AS auteur_nom, u.prenom AS auteur_prenom
            FROM articles a
            LEFT JOIN users u ON u.id = a.auteur_id
            ORDER BY a.date_soumission DESC, a.id DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function countAll(): int
    {
        $db = getDB();
        return (int) $db->query("SELECT COUNT(*) FROM articles")->fetchColumn();
    }

    /** Changer le statut (admin) */
    public static function updateStatut(int $id, string $statut): bool
    {
        $db = getDB();
        $stmt = $db->prepare("UPDATE articles SET statut = :statut, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $id, ':statut' => $statut]);
    }

    /** Assigner à un numéro (issue_id) */
    public static function setIssueId(int $id, ?int $issueId): bool
    {
        $db = getDB();
        $stmt = $db->prepare("UPDATE articles SET issue_id = :iid, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $id, ':iid' => $issueId]);
    }
}
