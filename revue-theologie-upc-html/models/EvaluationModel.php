<?php
namespace Models;

/**
 * Modèle évaluations (table evaluations).
 * Liaison article / évaluateur, statut, recommandation, notes, commentaires.
 */
class EvaluationModel
{
    /**
     * Évaluations assignées à un évaluateur (avec infos article).
     * $statut = null pour toutes, sinon filtrer par statut.
     */
    public static function getByEvaluateurId(int $evaluateurId, ?string $statut = null, int $limit = 50, int $offset = 0): array
    {
        $db = getDB();
        $sql = "
            SELECT e.id, e.article_id, e.evaluateur_id, e.statut, e.date_assignation, e.date_echeance, e.date_soumission,
                   e.recommendation, e.qualite_scientifique, e.originalite, e.pertinence, e.clarte, e.note_finale,
                   e.commentaires_public, e.commentaires_prives, e.suggestions, e.decision_finale,
                   a.titre AS article_titre, a.contenu AS article_contenu, a.fichier_path AS article_fichier_path, a.statut AS article_statut
            FROM evaluations e
            INNER JOIN articles a ON a.id = e.article_id
            WHERE e.evaluateur_id = :eid
        ";
        if ($statut !== null) {
            $sql .= " AND e.statut = :statut";
        }
        $sql .= " ORDER BY e.date_assignation DESC LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':eid', $evaluateurId, \PDO::PARAM_INT);
        if ($statut !== null) {
            $stmt->bindValue(':statut', $statut, \PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Une évaluation par ID uniquement si elle appartient à l'évaluateur (avec article).
     */
    public static function getByIdForReviewer(int $evaluationId, int $evaluateurId): ?array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT e.id, e.article_id, e.evaluateur_id, e.statut, e.date_assignation, e.date_echeance, e.date_soumission,
                   e.recommendation, e.qualite_scientifique, e.originalite, e.pertinence, e.clarte, e.note_finale,
                   e.commentaires_public, e.commentaires_prives, e.suggestions, e.decision_finale,
                   a.titre AS article_titre, a.contenu AS article_contenu, a.fichier_path AS article_fichier_path, a.statut AS article_statut
            FROM evaluations e
            INNER JOIN articles a ON a.id = e.article_id
            WHERE e.id = :id AND e.evaluateur_id = :eid
        ");
        $stmt->execute([':id' => $evaluationId, ':eid' => $evaluateurId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Évaluation par ID d'évaluation (pour les routes qui utilisent l'id de l'évaluation).
     * Alternative: on peut aussi utiliser article_id dans l'URL; alors il faut getByArticleIdAndEvaluateur.
     */
    public static function getByArticleIdAndEvaluateur(int $articleId, int $evaluateurId): ?array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT e.id, e.article_id, e.evaluateur_id, e.statut, e.date_assignation, e.date_echeance, e.date_soumission,
                   e.recommendation, e.qualite_scientifique, e.originalite, e.pertinence, e.clarte, e.note_finale,
                   e.commentaires_public, e.commentaires_prives, e.suggestions, e.decision_finale,
                   a.titre AS article_titre, a.contenu AS article_contenu, a.fichier_path AS article_fichier_path
            FROM evaluations e
            INNER JOIN articles a ON a.id = e.article_id
            WHERE e.article_id = :aid AND e.evaluateur_id = :eid
        ");
        $stmt->execute([':aid' => $articleId, ':eid' => $evaluateurId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Nombre d'évaluations par évaluateur et statut */
    public static function countByEvaluateurIdAndStatut(int $evaluateurId, string $statut): int
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM evaluations WHERE evaluateur_id = :eid AND statut = :statut");
        $stmt->execute([':eid' => $evaluateurId, ':statut' => $statut]);
        return (int) $stmt->fetchColumn();
    }

    /** Total des évaluations pour un évaluateur */
    public static function countByEvaluateurId(int $evaluateurId): int
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM evaluations WHERE evaluateur_id = :eid");
        $stmt->execute([':eid' => $evaluateurId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Sauvegarder un brouillon (statut = en_cours, pas de date_soumission).
     */
    public static function saveDraft(
        int $evaluationId,
        int $evaluateurId,
        ?string $recommendation,
        ?int $qualiteScientifique,
        ?int $originalite,
        ?int $pertinence,
        ?int $clarte,
        ?int $noteFinale,
        ?string $commentairesPublic,
        ?string $commentairesPrives,
        ?string $suggestions
    ): bool {
        $db = getDB();
        $stmt = $db->prepare("
            UPDATE evaluations SET
                statut = 'en_cours',
                recommendation = :recommendation,
                qualite_scientifique = :qs, originalite = :orig, pertinence = :pert, clarte = :clarte, note_finale = :note_finale,
                commentaires_public = :cpub, commentaires_prives = :cpriv, suggestions = :suggestions,
                updated_at = NOW()
            WHERE id = :id AND evaluateur_id = :eid AND statut IN ('en_attente', 'en_cours')
        ");
        return $stmt->execute([
            ':id' => $evaluationId,
            ':eid' => $evaluateurId,
            ':recommendation' => $recommendation,
            ':qs' => $qualiteScientifique,
            ':orig' => $originalite,
            ':pert' => $pertinence,
            ':clarte' => $clarte,
            ':note_finale' => $noteFinale,
            ':cpub' => $commentairesPublic,
            ':cpriv' => $commentairesPrives,
            ':suggestions' => $suggestions,
        ]);
    }

    /**
     * Soumettre l'évaluation (statut = termine, date_soumission = NOW()).
     */
    public static function submit(
        int $evaluationId,
        int $evaluateurId,
        string $recommendation,
        ?int $qualiteScientifique,
        ?int $originalite,
        ?int $pertinence,
        ?int $clarte,
        ?int $noteFinale,
        ?string $commentairesPublic,
        ?string $commentairesPrives,
        ?string $suggestions
    ): bool {
        $db = getDB();
        $stmt = $db->prepare("
            UPDATE evaluations SET
                statut = 'termine',
                date_soumission = NOW(),
                recommendation = :recommendation,
                qualite_scientifique = :qs, originalite = :orig, pertinence = :pert, clarte = :clarte, note_finale = :note_finale,
                commentaires_public = :cpub, commentaires_prives = :cpriv, suggestions = :suggestions,
                decision_finale = CASE
                    WHEN :recommendation IN ('accepte') THEN 'accepte'
                    WHEN :recommendation IN ('rejete') THEN 'rejete'
                    ELSE 'revision_requise'
                END,
                updated_at = NOW()
            WHERE id = :id AND evaluateur_id = :eid AND statut IN ('en_attente', 'en_cours')
        ");
        return $stmt->execute([
            ':id' => $evaluationId,
            ':eid' => $evaluateurId,
            ':recommendation' => $recommendation,
            ':qs' => $qualiteScientifique,
            ':orig' => $originalite,
            ':pert' => $pertinence,
            ':clarte' => $clarte,
            ':note_finale' => $noteFinale,
            ':cpub' => $commentairesPublic,
            ':cpriv' => $commentairesPrives,
            ':suggestions' => $suggestions,
        ]);
    }

    /** Assigner un évaluateur à un article (admin). Évite doublon (article_id, evaluateur_id) unique. */
    public static function assign(int $articleId, int $evaluateurId, ?string $dateEcheance = null): ?int
    {
        $db = getDB();
        $echeance = $dateEcheance ?? date('Y-m-d', strtotime('+14 days'));
        $stmt = $db->prepare("
            INSERT INTO evaluations (article_id, evaluateur_id, statut, date_assignation, date_echeance, decision_finale, created_at, updated_at)
            VALUES (:aid, :eid, 'en_attente', NOW(), :echeance, 'en_attente', NOW(), NOW())
        ");
        try {
            $ok = $stmt->execute([':aid' => $articleId, ':eid' => $evaluateurId, ':echeance' => $echeance]);
            return $ok ? (int) $db->lastInsertId() : null;
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) return null; // duplicate
            throw $e;
        }
    }

    /** Évaluations pour un article (admin) */
    public static function getByArticleId(int $articleId): array
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT e.id, e.evaluateur_id, e.statut, e.recommendation, e.date_soumission,
                   u.nom AS evaluateur_nom, u.prenom AS evaluateur_prenom
            FROM evaluations e
            LEFT JOIN users u ON u.id = e.evaluateur_id
            WHERE e.article_id = :aid ORDER BY e.date_assignation DESC
        ");
        $stmt->execute([':aid' => $articleId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
