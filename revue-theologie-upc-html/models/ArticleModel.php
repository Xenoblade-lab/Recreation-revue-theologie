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

    /** Créer un article (brouillon ou soumission). $statut = 'brouillon' ou 'soumis'. */
    public static function create(int $auteurId, string $titre, string $contenu, ?string $fichierPath = null, ?string $fichierNomOriginal = null, string $statut = 'soumis'): ?int
    {
        $db = getDB();
        $statut = $statut === 'brouillon' ? 'brouillon' : 'soumis';
        $stmt = $db->prepare("
            INSERT INTO articles (titre, contenu, fichier_path, fichier_nom_original, auteur_id, statut, date_soumission, created_at, updated_at)
            VALUES (:titre, :contenu, :fichier_path, :fichier_nom_original, :auteur_id, :statut, NOW(), NOW(), NOW())
        ");
        $ok = $stmt->execute([
            ':titre'                 => $titre,
            ':contenu'               => $contenu,
            ':fichier_path'          => $fichierPath,
            ':fichier_nom_original'  => $fichierNomOriginal,
            ':auteur_id'             => $auteurId,
            ':statut'                => $statut,
        ]);
        return $ok ? (int) $db->lastInsertId() : null;
    }

    /** Passer un brouillon en soumis (date_soumission = NOW()). */
    public static function submitDraft(int $id, int $authorId): bool
    {
        $db = getDB();
        $stmt = $db->prepare("
            UPDATE articles SET statut = 'soumis', date_soumission = NOW(), updated_at = NOW()
            WHERE id = :id AND auteur_id = :aid AND statut = 'brouillon'
        ");
        return $stmt->execute([':id' => $id, ':aid' => $authorId]);
    }

    /** Mettre à jour un article (statut = soumis ou brouillon, appartient à l'auteur). */
    public static function updateByAuthor(int $id, int $authorId, string $titre, string $contenu, ?string $fichierPath = null, ?string $fichierNomOriginal = null): bool
    {
        $db = getDB();
        $setFile = $fichierPath !== null ? ", fichier_path = :fichier_path, fichier_nom_original = :fichier_nom_original" : "";
        $stmt = $db->prepare("
            UPDATE articles SET titre = :titre, contenu = :contenu, updated_at = NOW()" . $setFile . "
            WHERE id = :id AND auteur_id = :aid AND statut IN ('soumis', 'brouillon')
        ");
        $params = [':titre' => $titre, ':contenu' => $contenu, ':id' => $id, ':aid' => $authorId];
        if ($fichierPath !== null) {
            $params[':fichier_path'] = $fichierPath;
            $params[':fichier_nom_original'] = $fichierNomOriginal;
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

    /** Recherche dans les articles publiés (titre, contenu, nom et prénom auteur) */
    public static function search(string $query, int $limit = 30, int $offset = 0): array
    {
        if (trim($query) === '') {
            return [];
        }
        $db = getDB();
        $term = '%' . trim($query) . '%';
        $limit = (int) $limit;
        $offset = (int) $offset;
        // LIMIT/OFFSET en littéraux pour éviter le bug PDO/MySQL avec les requêtes préparées
        // Recherche aussi sur le nom complet (CONCAT) pour "Prénom Nom" ou "Nom Prénom"
        $stmt = $db->prepare("
            SELECT a.id, a.titre, a.contenu, a.fichier_path, a.date_soumission,
                   u.nom AS auteur_nom, u.prenom AS auteur_prenom
            FROM articles a
            LEFT JOIN users u ON u.id = a.auteur_id
            WHERE a.statut = 'valide'
              AND (
                a.titre LIKE :q1
                OR a.contenu LIKE :q2
                OR u.nom LIKE :q3
                OR u.prenom LIKE :q4
                OR CONCAT(IFNULL(u.prenom,''), ' ', IFNULL(u.nom,'')) LIKE :q5
                OR CONCAT(IFNULL(u.nom,''), ' ', IFNULL(u.prenom,'')) LIKE :q6
              )
            ORDER BY a.date_soumission DESC
            LIMIT " . $limit . " OFFSET " . $offset . "
        ");
        $stmt->bindValue(':q1', $term, \PDO::PARAM_STR);
        $stmt->bindValue(':q2', $term, \PDO::PARAM_STR);
        $stmt->bindValue(':q3', $term, \PDO::PARAM_STR);
        $stmt->bindValue(':q4', $term, \PDO::PARAM_STR);
        $stmt->bindValue(':q5', $term, \PDO::PARAM_STR);
        $stmt->bindValue(':q6', $term, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
