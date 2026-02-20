<?php
namespace Models;

class ReviewModel {
    private $db;

    public function __construct(\Models\Database $db) {
        $this->db = $db;
    }

    /**
     * Créer la table des évaluations si elle n'existe pas
     * Cette méthode devrait être exécutée une seule fois
     */
    public function createReviewsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS evaluations (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                article_id BIGINT UNSIGNED NOT NULL,
                evaluateur_id BIGINT UNSIGNED NOT NULL,
                statut ENUM('en_attente', 'en_cours', 'termine', 'annule') DEFAULT 'en_attente',
                date_assignation TIMESTAMP NULL DEFAULT NULL,
                date_echeance DATE NULL DEFAULT NULL,
                date_soumission TIMESTAMP NULL DEFAULT NULL,
                recommendation ENUM('accepte', 'accepte_avec_modifications', 'revision_majeure', 'rejete') DEFAULT NULL,
                qualite_scientifique TINYINT DEFAULT NULL COMMENT 'Note sur 10',
                originalite TINYINT DEFAULT NULL COMMENT 'Note sur 10',
                pertinence TINYINT DEFAULT NULL COMMENT 'Note sur 10',
                clarte TINYINT DEFAULT NULL COMMENT 'Note sur 10',
                note_finale TINYINT DEFAULT NULL COMMENT 'Note moyenne sur 10',
                commentaires_public TEXT COMMENT 'Commentaires visibles par les auteurs',
                commentaires_prives TEXT COMMENT 'Commentaires pour le comité éditorial',
                suggestions TEXT COMMENT 'Suggestions d\'amélioration',
                decision_finale ENUM('en_attente', 'accepte', 'rejete', 'revision_requise') DEFAULT 'en_attente',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
                FOREIGN KEY (evaluateur_id) REFERENCES users(id) ON DELETE CASCADE,
                UNIQUE KEY unique_article_evaluateur (article_id, evaluateur_id),
                INDEX idx_statut (statut),
                INDEX idx_date_echeance (date_echeance),
                INDEX idx_recommendation (recommendation)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        return $this->db->execute($sql);
    }

    /**
     * Créer la table des critères d'évaluation
     */
    public function createReviewCriteriaTable() {
        $sql = "CREATE TABLE IF NOT EXISTS criteres_evaluation (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                categorie VARCHAR(100) NOT NULL,
                critere VARCHAR(255) NOT NULL,
                description TEXT,
                poids TINYINT DEFAULT 1 COMMENT 'Poids du critère (1-5)',
                ordre TINYINT DEFAULT 0,
                actif BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY unique_critere (categorie, critere)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        return $this->db->execute($sql);
    }

    /**
     * Créer la table des scores par critère
     */
    public function createReviewScoresTable() {
        $sql = "CREATE TABLE IF NOT EXISTS scores_evaluation (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                evaluation_id BIGINT UNSIGNED NOT NULL,
                critere_id BIGINT UNSIGNED NOT NULL,
                score TINYINT NOT NULL COMMENT 'Score sur 10',
                commentaire TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (evaluation_id) REFERENCES evaluations(id) ON DELETE CASCADE,
                FOREIGN KEY (critere_id) REFERENCES criteres_evaluation(id) ON DELETE CASCADE,
                UNIQUE KEY unique_evaluation_critere (evaluation_id, critere_id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        return $this->db->execute($sql);
    }

    /**
     * Assigner un évaluateur à un article
     */
    public function assignReviewer($articleId, $reviewerId, $deadlineDays = 14) {
        try {
            // Vérifier si l'assignation existe déjà
            $checkSql = "SELECT COUNT(*) as count FROM evaluations 
                         WHERE article_id = :articleId AND evaluateur_id = :reviewerId";
            
            $check = $this->db->fetchOne($checkSql, [
                ':articleId' => (int)$articleId,
                ':reviewerId' => (int)$reviewerId
            ]);
            
            if (($check['count'] ?? 0) > 0) {
                error_log("Assignation déjà existante pour article {$articleId} et évaluateur {$reviewerId}");
                return false; // Assignation déjà existante
            }
            
            // Calculer la date d'échéance
            $deadline = date('Y-m-d', strtotime("+{$deadlineDays} days"));
            
            $sql = "INSERT INTO evaluations (article_id, evaluateur_id, statut, date_assignation, date_echeance, created_at, updated_at) 
                    VALUES (:articleId, :reviewerId, 'en_attente', NOW(), :date_echeance, NOW(), NOW())";
            
            $result = $this->db->execute($sql, [
                ':articleId' => (int)$articleId,
                ':reviewerId' => (int)$reviewerId,
                ':date_echeance' => $deadline
            ]);
            
            if ($result === false) {
                error_log("Échec de l'insertion dans evaluations pour article {$articleId} et évaluateur {$reviewerId}");
            }
            
            return $result !== false;
        } catch (\PDOException $e) {
            error_log('Erreur PDO assignReviewer: ' . $e->getMessage());
            error_log('Code erreur: ' . $e->getCode());
            throw $e; // Re-lancer l'exception pour que le contrôleur puisse la gérer
        } catch (\Exception $e) {
            error_log('Erreur assignReviewer: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            throw $e; // Re-lancer l'exception pour que le contrôleur puisse la gérer
        }
    }

    /**
     * Désassigner un évaluateur d'un article
     */
    public function unassignReviewer($articleId, $reviewerId) {
        $sql = "DELETE FROM evaluations 
                WHERE article_id = :articleId AND evaluateur_id = :reviewerId";
        
        return $this->db->execute($sql, [
            ':articleId' => $articleId,
            ':reviewerId' => $reviewerId
        ]);
    }

    /**
     * Accepter une évaluation (évaluateur)
     */
    public function acceptReviewAssignment($evaluationId) {
        $sql = "UPDATE evaluations SET statut = 'en_cours', updated_at = NOW() 
                WHERE id = :id AND statut = 'en_attente'";
        
        return $this->db->execute($sql, [':id' => $evaluationId]);
    }

    /**
     * Refuser une évaluation (évaluateur)
     */
    public function declineReviewAssignment($evaluationId, $reason = null) {
        $sql = "UPDATE evaluations SET statut = 'annule', updated_at = NOW() 
                WHERE id = :id AND statut = 'en_attente'";
        
        return $this->db->execute($sql, [':id' => $evaluationId]);
    }

    /**
     * Soumettre une évaluation complète
     */
    public function submitReview($evaluationId, $data) {
        try {
            // Récupérer l'évaluation pour obtenir l'article_id
            $evaluation = $this->getReviewById($evaluationId);
            if (!$evaluation) {
                return false;
            }
            
            $articleId = $evaluation['article_id'];
            
            // Calculer la note finale moyenne
            $scores = [
                $data['qualite_scientifique'] ?? 0,
                $data['originalite'] ?? 0,
                $data['pertinence'] ?? 0,
                $data['clarte'] ?? 0
            ];
            
            $validScores = array_filter($scores, function($score) {
                return $score !== null && $score >= 0 && $score <= 10;
            });
            
            $averageScore = !empty($validScores) ? round(array_sum($validScores) / count($validScores)) : null;
            
            $sql = "UPDATE evaluations SET 
                    statut = 'termine', 
                    date_soumission = NOW(), 
                    recommendation = :recommendation, 
                    qualite_scientifique = :qualite_scientifique, 
                    originalite = :originalite, 
                    pertinence = :pertinence, 
                    clarte = :clarte, 
                    note_finale = :note_finale, 
                    commentaires_public = :commentaires_public, 
                    commentaires_prives = :commentaires_prives, 
                    suggestions = :suggestions, 
                    updated_at = NOW() 
                    WHERE id = :id";
            
            $params = [
                ':id' => $evaluationId,
                ':recommendation' => $data['recommendation'],
                ':qualite_scientifique' => isset($data['qualite_scientifique']) ? (float)$data['qualite_scientifique'] : null,
                ':originalite' => isset($data['originalite']) ? (float)$data['originalite'] : null,
                ':pertinence' => isset($data['pertinence']) ? (float)$data['pertinence'] : null,
                ':clarte' => isset($data['clarte']) ? (float)$data['clarte'] : null,
                ':note_finale' => $averageScore,
                ':commentaires_public' => $data['commentaires_public'] ?? null,
                ':commentaires_prives' => $data['commentaires_prives'] ?? null,
                ':suggestions' => $data['suggestions'] ?? null
            ];
            
            $result = $this->db->execute($sql, $params);
            
            if ($result) {
                // Récupérer l'ancien statut de l'article
                $articleModel = new \Models\ArticleModel($this->db);
                $currentArticle = $articleModel->getArticleById($articleId);
                $oldStatus = strtolower($currentArticle['statut'] ?? 'soumis');
                
                // Mettre à jour le statut de l'article en fonction des évaluations
                $this->updateArticleStatusBasedOnReviews($articleId);
                
                // Récupérer le nouveau statut
                $updatedArticle = $articleModel->getArticleById($articleId);
                $newStatus = strtolower($updatedArticle['statut'] ?? $oldStatus);
                
                // Si le statut a changé, créer une notification et une révision
                if ($oldStatus !== $newStatus) {
                    // Créer une notification pour l'auteur
                    $notificationModel = new \Models\NotificationModel($this->db);
                    $reason = "Évaluation terminée avec recommandation : " . ($data['recommendation'] ?? 'N/A');
                    $notificationModel->notifyArticleStatusChange($articleId, $newStatus, $reason);
                    
                    // Créer une entrée de révision si le statut est "revision_requise"
                    if ($newStatus === 'revision_requise') {
                        $revisionModel = new \Models\RevisionModel($this->db);
                        $revisionReason = "Révisions requises suite à l'évaluation. " . ($data['commentaires_public'] ?? '');
                        $revisionModel->createRevision($articleId, $oldStatus, $newStatus, $revisionReason);
                    }
                }
            }
            
            return $result;
        } catch (\Exception $e) {
            error_log('Erreur submitReview: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mettre à jour le statut de l'article en fonction des évaluations
     */
    public function updateArticleStatusBasedOnReviews($articleId) {
        try {
            // Récupérer toutes les évaluations de l'article (pas seulement les terminées)
            $allEvaluations = $this->getReviewsByArticle($articleId, false);
            
            if (empty($allEvaluations)) {
                return false;
            }
            
            // Séparer les évaluations terminées et non terminées
            $completedEvaluations = [];
            $pendingEvaluations = [];
            
            foreach ($allEvaluations as $eval) {
                if (strtolower($eval['statut'] ?? '') === 'termine') {
                    $completedEvaluations[] = $eval;
                } else {
                    $pendingEvaluations[] = $eval;
                }
            }
            
            // Si aucune évaluation n'est terminée, ne rien faire
            if (empty($completedEvaluations)) {
                return false;
            }
            
            // Compter les évaluations terminées par recommandation
            $recommendations = [];
            foreach ($completedEvaluations as $eval) {
                if (!empty($eval['recommendation'])) {
                    $rec = $eval['recommendation'];
                    $recommendations[$rec] = ($recommendations[$rec] ?? 0) + 1;
                }
            }
            
            // Si aucune recommandation, ne rien faire
            if (empty($recommendations)) {
                return false;
            }
            
            // Déterminer le nouveau statut de l'article
            $newStatus = null;
            $totalCompleted = count($completedEvaluations);
            $totalEvaluations = count($allEvaluations);
            
            // Si toutes les évaluations sont terminées
            if ($totalCompleted === $totalEvaluations) {
                // Nombre total d'évaluations avec une recommandation d'acceptation (avec ou sans modifs)
                $acceptedTotal = ($recommendations['accepte'] ?? 0) + ($recommendations['accepte_avec_modifications'] ?? 0);
                
                // Si toutes les évaluations recommandent l'acceptation (avec ou sans modifs) → Publication automatique
                if ($acceptedTotal === $totalCompleted && empty($recommendations['rejete']) && empty($recommendations['revision_majeure'])) {
                    $newStatus = 'publie';
                }
                // Si au moins une évaluation recommande "rejete" → Rejeté
                elseif (isset($recommendations['rejete']) && $recommendations['rejete'] > 0) {
                    $newStatus = 'rejete';
                }
                // Si au moins une évaluation recommande "revision_majeure" → Révisions majeures
                elseif (isset($recommendations['revision_majeure']) && $recommendations['revision_majeure'] > 0) {
                    $newStatus = 'revision_requise';
                }
                // Si au moins une évaluation recommande "accepte_avec_modifications" (et pas toutes acceptées) → Révisions mineures
                elseif (isset($recommendations['accepte_avec_modifications']) && $recommendations['accepte_avec_modifications'] > 0) {
                    $newStatus = 'revision_requise';
                }
            } else {
                // Si certaines évaluations sont encore en cours, garder "en_evaluation"
                // Mais si au moins une évaluation recommande "rejete", on peut déjà rejeter
                if (isset($recommendations['rejete']) && $recommendations['rejete'] > 0) {
                    $newStatus = 'rejete';
                }
            }
            
            // Mettre à jour le statut de l'article si un nouveau statut a été déterminé
            if ($newStatus) {
                // Récupérer l'ancien statut
                $articleModel = new \Models\ArticleModel($this->db);
                $currentArticle = $articleModel->getArticleById($articleId);
                $oldStatus = strtolower($currentArticle['statut'] ?? 'soumis');
                
                // Mettre à jour le statut
                $sql = "UPDATE articles SET statut = :statut, updated_at = NOW() WHERE id = :id";
                $result = $this->db->execute($sql, [
                    ':statut' => $newStatus,
                    ':id' => $articleId
                ]);
                
                // Si le statut a changé, créer une notification
                if ($result && $oldStatus !== strtolower($newStatus)) {
                    $notificationModel = new \Models\NotificationModel($this->db);
                    $reason = "Statut mis à jour suite aux évaluations terminées";
                    $notificationModel->notifyArticleStatusChange($articleId, $newStatus, $reason);
                    
                    // Créer une entrée de révision si nécessaire
                    if (strtolower($newStatus) === 'revision_requise') {
                        $revisionModel = new \Models\RevisionModel($this->db);
                        $revisionReason = "Révisions requises suite aux évaluations";
                        $revisionModel->createRevision($articleId, $oldStatus, $newStatus, $revisionReason);
                    }
                }
                
                return $result;
            }
            
            return true;
        } catch (\Exception $e) {
            error_log('Erreur updateArticleStatusBasedOnReviews: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer une évaluation par ID
     */
    public function getReviewById($reviewId) {
        $sql = "SELECT e.*, 
                       a.titre as article_titre, 
                       a.statut as article_statut,
                       a.contenu as article_contenu,
                       a.fichier_path as article_fichier_path,
                       a.date_soumission as article_date_soumission,
                       DATEDIFF(e.date_echeance, CURDATE()) as jours_restants,
                       u_reviewer.nom as evaluateur_nom, 
                       u_reviewer.prenom as evaluateur_prenom,
                       u_reviewer.email as evaluateur_email,
                       u_author.nom as auteur_nom,
                       u_author.prenom as auteur_prenom
                FROM evaluations e 
                JOIN articles a ON e.article_id = a.id 
                JOIN users u_reviewer ON e.evaluateur_id = u_reviewer.id 
                JOIN users u_author ON a.auteur_id = u_author.id 
                WHERE e.id = :id";
        
        return $this->db->fetchOne($sql, [':id' => $reviewId]);
    }

    /**
     * Récupérer les évaluations d'un article
     */
    public function getReviewsByArticle($articleId, $onlyCompleted = false) {
        $sql = "SELECT e.*, 
                       u.nom as evaluateur_nom, 
                       u.prenom as evaluateur_prenom,
                       u.email as evaluateur_email
                FROM evaluations e 
                JOIN users u ON e.evaluateur_id = u.id 
                WHERE e.article_id = :articleId";
        
        if ($onlyCompleted) {
            $sql .= " AND e.statut = 'termine'";
        }
        
        $sql .= " ORDER BY e.date_soumission DESC, e.created_at DESC";
        
        return $this->db->fetchAll($sql, [':articleId' => $articleId]);
    }

    /**
     * Récupérer les évaluations d'un évaluateur
     */
    public function getReviewsByReviewer($reviewerId, $status = null, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        // S'assurer que limit et offset sont des entiers
        $limit = (int)$limit;
        $offset = (int)$offset;

        // Requête de base (sans les révisions)
        $baseSql = "SELECT e.*, 
                           a.titre as article_titre,
                           a.date_soumission as article_date,
                           a.fichier_path as article_fichier_path,
                           a.id as article_id,
                           a.statut as article_statut,
                           DATEDIFF(e.date_echeance, CURDATE()) as jours_restants
                    FROM evaluations e 
                    JOIN articles a ON e.article_id = a.id 
                    WHERE e.evaluateur_id = :reviewerId";

        // Requête enrichie avec le nombre de révisions (peut échouer si la table n'existe pas)
        $sqlWithRevisions = "SELECT e.*, 
                                   a.titre as article_titre,
                                   a.date_soumission as article_date,
                                   a.fichier_path as article_fichier_path,
                                   a.id as article_id,
                                   a.statut as article_statut,
                                   DATEDIFF(e.date_echeance, CURDATE()) as jours_restants,
                                   (
                                       SELECT COUNT(*) 
                                       FROM article_revisions ar 
                                       WHERE ar.article_id = a.id
                                   ) as revision_count
                            FROM evaluations e 
                            JOIN articles a ON e.article_id = a.id 
                            WHERE e.evaluateur_id = :reviewerId";
        
        $params = [':reviewerId' => $reviewerId];
        
        if ($status) {
            $baseSql         .= " AND e.statut = :status";
            $sqlWithRevisions .= " AND e.statut = :status";
            $params[':status'] = $status;
        }

        $orderAndLimit = " ORDER BY 
                  CASE 
                    WHEN e.statut = 'en_attente' THEN 1
                    WHEN e.statut = 'en_cours' THEN 2
                    WHEN e.statut = 'termine' THEN 3
                    ELSE 4
                  END,
                  e.date_echeance ASC 
                LIMIT {$limit} OFFSET {$offset}";

        try {
            // On tente d'abord avec la table article_revisions
            return $this->db->fetchAll($sqlWithRevisions . $orderAndLimit, $params);
        } catch (\PDOException $e) {
            // Si la table article_revisions n'existe pas encore, on retombe sur la requête de base
            if ($e->getCode() === '42S02') {
                return $this->db->fetchAll($baseSql . $orderAndLimit, $params);
            }
            // Pour toute autre erreur SQL, on relance l'exception
            throw $e;
        }
    }

    /**
     * Récupérer les évaluations en retard
     */
    public function getOverdueReviews($daysOverdue = 0) {
        $sql = "SELECT e.*, 
                       a.titre as article_titre,
                       u.nom as evaluateur_nom, 
                       u.prenom as evaluateur_prenom,
                       DATEDIFF(CURDATE(), e.date_echeance) as jours_retard
                FROM evaluations e 
                JOIN articles a ON e.article_id = a.id 
                JOIN users u ON e.evaluateur_id = u.id 
                WHERE e.statut IN ('en_attente', 'en_cours') 
                AND e.date_echeance < CURDATE() 
                AND DATEDIFF(CURDATE(), e.date_echeance) >= :daysOverdue 
                ORDER BY e.date_echeance ASC";
        
        return $this->db->fetchAll($sql, [':daysOverdue' => $daysOverdue]);
    }

    /**
     * Récupérer les évaluations à venir (dans les X jours)
     */
    public function getUpcomingReviews($daysAhead = 7) {
        $sql = "SELECT e.*, 
                       a.titre as article_titre,
                       u.nom as evaluateur_nom, 
                       u.prenom as evaluateur_prenom,
                       DATEDIFF(e.date_echeance, CURDATE()) as jours_restants
                FROM evaluations e 
                JOIN articles a ON e.article_id = a.id 
                JOIN users u ON e.evaluateur_id = u.id 
                WHERE e.statut IN ('en_attente', 'en_cours') 
                AND e.date_echeance >= CURDATE() 
                AND DATEDIFF(e.date_echeance, CURDATE()) <= :daysAhead 
                ORDER BY e.date_echeance ASC";
        
        return $this->db->fetchAll($sql, [':daysAhead' => $daysAhead]);
    }

    /**
     * Compter les évaluations par statut
     */
    public function countReviewsByStatus($articleId = null) {
        if ($articleId) {
            $sql = "SELECT statut, COUNT(*) as count 
                    FROM evaluations 
                    WHERE article_id = :articleId 
                    GROUP BY statut 
                    ORDER BY count DESC";
            
            return $this->db->fetchAll($sql, [':articleId' => $articleId]);
        } else {
            $sql = "SELECT statut, COUNT(*) as count 
                    FROM evaluations 
                    GROUP BY statut 
                    ORDER BY count DESC";
            
            return $this->db->fetchAll($sql);
        }
    }

    /**
     * Vérifier si un article a suffisamment d'évaluations
     */
    public function hasSufficientReviews($articleId, $minReviews = 2) {
        $sql = "SELECT COUNT(*) as count FROM evaluations 
                WHERE article_id = :articleId AND statut = 'termine'";
        
        $result = $this->db->fetchOne($sql, [':articleId' => $articleId]);
        return ($result['count'] ?? 0) >= $minReviews;
    }

    /**
     * Obtenir la décision finale basée sur les évaluations
     */
    public function getReviewConsensus($articleId) {
        $reviews = $this->getReviewsByArticle($articleId, true);
        
        if (empty($reviews)) {
            return [
                'decision' => 'en_attente',
                'average_score' => 0,
                'review_count' => 0,
                'recommendations' => []
            ];
        }
        
        // Compter les recommandations
        $recommendations = [];
        $totalScore = 0;
        $validScores = 0;
        
        foreach ($reviews as $review) {
            if ($review['recommendation']) {
                $recommendations[] = $review['recommendation'];
            }
            if ($review['note_finale']) {
                $totalScore += $review['note_finale'];
                $validScores++;
            }
        }
        
        $averageScore = $validScores > 0 ? round($totalScore / $validScores, 1) : 0;
        
        // Déterminer la décision basée sur les recommandations
        $decision = 'en_attente';
        $recommendationCounts = array_count_values($recommendations);
        
        if (count($reviews) >= 2) {
            // Logique de décision
            if (isset($recommendationCounts['accepte']) && $recommendationCounts['accepte'] >= 2) {
                $decision = 'accepte';
            } elseif (isset($recommendationCounts['rejete']) && $recommendationCounts['rejete'] >= 2) {
                $decision = 'rejete';
            } elseif ($averageScore >= 7) {
                $decision = 'accepte';
            } elseif ($averageScore >= 5) {
                $decision = 'revision_requise';
            } else {
                $decision = 'rejete';
            }
        }
        
        return [
            'decision' => $decision,
            'average_score' => $averageScore,
            'review_count' => count($reviews),
            'recommendations' => $recommendationCounts,
            'all_reviews' => $reviews
        ];
    }

    /**
     * Mettre à jour la décision finale d'un article
     */
    public function updateArticleDecision($articleId, $decision) {
        // Mettre à jour le statut de l'article
        $articleModel = new ArticleModel($this->db);
        
        $statusMap = [
            'accepte' => 'valide',
            'rejete' => 'rejete',
            'revision_requise' => 'soumis'
        ];
        
        if (isset($statusMap[$decision])) {
            return $articleModel->changeArticleStatus($articleId, $statusMap[$decision]);
        }
        
        return false;
    }

    /**
     * Récupérer les évaluateurs disponibles pour un article
     */
    public function getAvailableReviewers($articleId, $excludeAuthors = true, $limit = 10) {
        $sql = "SELECT u.id, u.nom, u.prenom, u.email, 
                       COUNT(e.id) as review_count,
                       AVG(e.note_finale) as avg_score
                FROM users u 
                LEFT JOIN evaluations e ON u.id = e.evaluateur_id 
                WHERE u.id NOT IN (
                    SELECT evaluateur_id 
                    FROM evaluations 
                    WHERE article_id = :articleId
                )";
        
        if ($excludeAuthors) {
            $sql .= " AND u.id NOT IN (
                SELECT auteur_id 
                FROM articles 
                WHERE id = :articleId
                UNION
                SELECT aa.auteur_id 
                FROM auteurs_articles aa 
                WHERE aa.article_id = :articleId
            )";
        }
        
        $limit = (int)$limit;
        $sql .= " GROUP BY u.id 
                  ORDER BY review_count DESC, u.nom ASC 
                  LIMIT {$limit}";
        
        return $this->db->fetchAll($sql, [
            ':articleId' => $articleId
        ]);
    }

    /**
     * Rechercher des évaluateurs par expertise
     */
    public function searchReviewersByExpertise($keywords, $excludeArticleId = null, $limit = 10) {
        // Cette fonction nécessiterait une table d'expertises des utilisateurs
        // Pour l'instant, nous recherchons dans les articles publiés
        $sql = "SELECT DISTINCT u.id, u.nom, u.prenom, u.email,
                       COUNT(DISTINCT a.id) as publication_count
                FROM users u 
                JOIN articles a ON u.id = a.auteur_id 
                WHERE a.statut = 'valide' 
                AND (a.titre LIKE :keywords OR a.contenu LIKE :keywords)";
        
        if ($excludeArticleId) {
            $sql .= " AND u.id NOT IN (
                SELECT auteur_id 
                FROM articles 
                WHERE id = :articleId
                UNION
                SELECT evaluateur_id 
                FROM evaluations 
                WHERE article_id = :articleId
            )";
        }
        
        $limit = (int)$limit;
        $sql .= " GROUP BY u.id 
                  ORDER BY publication_count DESC 
                  LIMIT {$limit}";
        
        $params = [
            ':keywords' => '%' . $keywords . '%'
        ];
        
        if ($excludeArticleId) {
            $params[':articleId'] = $excludeArticleId;
        }
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Prolonger la date d'échéance d'une évaluation
     */
    public function extendDeadline($evaluationId, $additionalDays) {
        $sql = "UPDATE evaluations SET 
                date_echeance = DATE_ADD(date_echeance, INTERVAL :days DAY), 
                updated_at = NOW() 
                WHERE id = :id";
        
        return $this->db->execute($sql, [
            ':id' => $evaluationId,
            ':days' => $additionalDays
        ]);
    }

    /**
     * Récupérer les statistiques d'un évaluateur
     */
    public function getReviewerStats($reviewerId) {
        $stats = [];
        
        // Nombre total d'évaluations assignées
        $sql = "SELECT COUNT(*) as total FROM evaluations WHERE evaluateur_id = :reviewerId";
        $result = $this->db->fetchOne($sql, [':reviewerId' => $reviewerId]);
        $stats['total_assigned'] = $result['total'] ?? 0;
        
        // Évaluations complétées
        $sql = "SELECT COUNT(*) as completed FROM evaluations 
                WHERE evaluateur_id = :reviewerId AND statut = 'termine'";
        $result = $this->db->fetchOne($sql, [':reviewerId' => $reviewerId]);
        $stats['completed'] = $result['completed'] ?? 0;
        
        // Évaluations en cours
        $sql = "SELECT COUNT(*) as in_progress FROM evaluations 
                WHERE evaluateur_id = :reviewerId AND statut = 'en_cours'";
        $result = $this->db->fetchOne($sql, [':reviewerId' => $reviewerId]);
        $stats['in_progress'] = $result['in_progress'] ?? 0;
        
        // Évaluations en attente
        $sql = "SELECT COUNT(*) as pending FROM evaluations 
                WHERE evaluateur_id = :reviewerId AND statut = 'en_attente'";
        $result = $this->db->fetchOne($sql, [':reviewerId' => $reviewerId]);
        $stats['pending'] = $result['pending'] ?? 0;
        
        // Temps moyen de réponse
        $sql = "SELECT AVG(DATEDIFF(date_soumission, date_assignation)) as avg_response_days 
                FROM evaluations 
                WHERE evaluateur_id = :reviewerId 
                AND statut = 'termine' 
                AND date_soumission IS NOT NULL";
        $result = $this->db->fetchOne($sql, [':reviewerId' => $reviewerId]);
        $stats['avg_response_days'] = round($result['avg_response_days'] ?? 0, 1);
        
        // Score moyen donné
        $sql = "SELECT AVG(note_finale) as avg_score 
                FROM evaluations 
                WHERE evaluateur_id = :reviewerId 
                AND statut = 'termine' 
                AND note_finale IS NOT NULL";
        $result = $this->db->fetchOne($sql, [':reviewerId' => $reviewerId]);
        $stats['avg_score_given'] = round($result['avg_score'] ?? 0, 1);
        
        // Distribution des recommandations
        $sql = "SELECT recommendation, COUNT(*) as count 
                FROM evaluations 
                WHERE evaluateur_id = :reviewerId 
                AND statut = 'termine' 
                AND recommendation IS NOT NULL 
                GROUP BY recommendation 
                ORDER BY count DESC";
        $stats['recommendations'] = $this->db->fetchAll($sql, [':reviewerId' => $reviewerId]);
        
        return $stats;
    }

    /**
     * Récupérer les critères d'évaluation
     */
    public function getReviewCriteria($activeOnly = true) {
        $sql = "SELECT * FROM criteres_evaluation 
                WHERE 1=1";
        
        if ($activeOnly) {
            $sql .= " AND actif = TRUE";
        }
        
        $sql .= " ORDER BY ordre ASC, categorie ASC";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Ajouter un critère d'évaluation
     */
    public function addReviewCriterion($data) {
        $sql = "INSERT INTO criteres_evaluation (categorie, critere, description, poids, ordre, actif, created_at, updated_at) 
                VALUES (:categorie, :critere, :description, :poids, :ordre, :actif, NOW(), NOW())";
        
        return $this->db->execute($sql, [
            ':categorie' => $data['categorie'],
            ':critere' => $data['critere'],
            ':description' => $data['description'] ?? null,
            ':poids' => $data['poids'] ?? 1,
            ':ordre' => $data['ordre'] ?? 0,
            ':actif' => $data['actif'] ?? true
        ]);
    }

    /**
     * Enregistrer les scores par critère pour une évaluation
     */
    public function saveCriterionScores($evaluationId, $scores) {
        try {
            // Supprimer les scores existants
            $deleteSql = "DELETE FROM scores_evaluation WHERE evaluation_id = :evaluationId";
            $this->db->execute($deleteSql, [':evaluationId' => $evaluationId]);
            
            // Insérer les nouveaux scores
            foreach ($scores as $criterionId => $scoreData) {
                $sql = "INSERT INTO scores_evaluation (evaluation_id, critere_id, score, commentaire, created_at, updated_at) 
                        VALUES (:evaluationId, :critereId, :score, :commentaire, NOW(), NOW())";
                
                $this->db->execute($sql, [
                    ':evaluationId' => $evaluationId,
                    ':critereId' => $criterionId,
                    ':score' => $scoreData['score'],
                    ':commentaire' => $scoreData['commentaire'] ?? null
                ]);
            }
            
            return true;
        } catch (\Exception $e) {
            error_log('Erreur saveCriterionScores: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer les scores par critère pour une évaluation
     */
    public function getCriterionScores($evaluationId) {
        $sql = "SELECT se.*, ce.categorie, ce.critere, ce.description, ce.poids 
                FROM scores_evaluation se 
                JOIN criteres_evaluation ce ON se.critere_id = ce.id 
                WHERE se.evaluation_id = :evaluationId 
                ORDER BY ce.ordre ASC";
        
        return $this->db->fetchAll($sql, [':evaluationId' => $evaluationId]);
    }

    /**
     * Générer un rapport d'évaluation
     */
    public function generateReviewReport($articleId) {
        $reviews = $this->getReviewsByArticle($articleId, true);
        
        if (empty($reviews)) {
            return null;
        }
        
        $report = [
            'article_id' => $articleId,
            'review_count' => count($reviews),
            'reviews' => [],
            'summary' => [],
            'recommendations' => []
        ];
        
        // Scores moyens par catégorie
        $categories = [
            'qualite_scientifique' => [],
            'originalite' => [],
            'pertinence' => [],
            'clarte' => []
        ];
        
        $recommendations = [];
        
        foreach ($reviews as $review) {
            $report['reviews'][] = [
                'reviewer' => $review['evaluateur_nom'] . ' ' . $review['evaluateur_prenom'],
                'recommendation' => $review['recommendation'],
                'final_score' => $review['note_finale'],
                'public_comments' => $review['commentaires_public'],
                'suggestions' => $review['suggestions'],
                'submission_date' => $review['date_soumission']
            ];
            
            // Collecter les scores
            foreach ($categories as $category => $scores) {
                if ($review[$category]) {
                    $categories[$category][] = $review[$category];
                }
            }
            
            if ($review['recommendation']) {
                $recommendations[] = $review['recommendation'];
            }
        }
        
        // Calculer les moyennes
        foreach ($categories as $category => $scores) {
            if (!empty($scores)) {
                $report['summary'][$category] = [
                    'average' => round(array_sum($scores) / count($scores), 1),
                    'min' => min($scores),
                    'max' => max($scores),
                    'count' => count($scores)
                ];
            }
        }
        
        // Compter les recommandations
        $recommendationCounts = array_count_values($recommendations);
        $report['recommendations'] = $recommendationCounts;
        
        // Consensus
        $consensus = $this->getReviewConsensus($articleId);
        $report['consensus'] = $consensus['decision'];
        $report['consensus_score'] = $consensus['average_score'];
        
        return $report;
    }

    /**
     * Notifier un évaluateur
     */
    public function notifyReviewer($evaluationId, $notificationType = 'assignment') {
        $review = $this->getReviewById($evaluationId);
        if (!$review) {
            return false;
        }
        
        // Ici, vous intégreriez votre système de notification
        // Par exemple, envoi d'email ou notification dans l'application
        
        $notificationData = [
            'review_id' => $evaluationId,
            'reviewer_id' => $review['evaluateur_id'],
            'reviewer_email' => $review['evaluateur_email'],
            'article_id' => $review['article_id'],
            'article_title' => $review['article_titre'],
            'deadline' => $review['date_echeance'],
            'notification_type' => $notificationType,
            'sent_at' => date('Y-m-d H:i:s')
        ];
        
        // Pour l'instant, on retourne les données de notification
        // Dans une vraie implémentation, vous enverriez l'email ou sauvegarderiez la notification
        return $notificationData;
    }

    /**
     * Récupérer l'historique des évaluations d'un article
     */
    public function getArticleReviewHistory($articleId) {
        $sql = "SELECT e.*, 
                       u.nom as evaluateur_nom, 
                       u.prenom as evaluateur_prenom,
                       CASE 
                         WHEN e.statut = 'en_attente' THEN 'Assigné'
                         WHEN e.statut = 'en_cours' THEN 'En cours'
                         WHEN e.statut = 'termine' THEN 'Terminé'
                         WHEN e.statut = 'annule' THEN 'Annulé'
                       END as statut_libelle
                FROM evaluations e 
                JOIN users u ON e.evaluateur_id = u.id 
                WHERE e.article_id = :articleId 
                ORDER BY e.created_at DESC";
        
        return $this->db->fetchAll($sql, [':articleId' => $articleId]);
    }

    /**
     * Récupérer les évaluateurs les plus actifs
     */
    public function getTopReviewers($limit = 10) {
        $sql = "SELECT u.id, u.nom, u.prenom, u.email,
                       COUNT(e.id) as review_count,
                       AVG(e.note_finale) as avg_score,
                       AVG(DATEDIFF(e.date_soumission, e.date_assignation)) as avg_response_time
                FROM users u 
                JOIN evaluations e ON u.id = e.evaluateur_id 
                WHERE e.statut = 'termine' 
                GROUP BY u.id 
                ORDER BY review_count DESC, avg_response_time ASC 
                LIMIT " . (int)$limit;
        
        return $this->db->fetchAll($sql, []);
    }

    /**
     * Vérifier les conflits d'intérêts pour un évaluateur
     */
    public function checkReviewerConflicts($reviewerId, $articleId) {
        // Vérifier si l'évaluateur est un auteur de l'article
        $sql = "SELECT COUNT(*) as count FROM articles 
                WHERE id = :articleId AND auteur_id = :reviewerId";
        
        $result = $this->db->fetchOne($sql, [
            ':articleId' => $articleId,
            ':reviewerId' => $reviewerId
        ]);
        
        if (($result['count'] ?? 0) > 0) {
            return ['conflict' => 'author', 'message' => 'L\'évaluateur est l\'auteur de l\'article'];
        }
        
        // Vérifier les co-auteurs
        $sql = "SELECT COUNT(*) as count FROM auteurs_articles 
                WHERE article_id = :articleId AND auteur_id = :reviewerId";
        
        $result = $this->db->fetchOne($sql, [
            ':articleId' => $articleId,
            ':reviewerId' => $reviewerId
        ]);
        
        if (($result['count'] ?? 0) > 0) {
            return ['conflict' => 'coauthor', 'message' => 'L\'évaluateur est un co-auteur de l\'article'];
        }
        
        // Vérifier les collaborations récentes (articles communs dans les 2 dernières années)
        $sql = "SELECT COUNT(DISTINCT aa2.article_id) as collaboration_count
                FROM auteurs_articles aa1 
                JOIN auteurs_articles aa2 ON aa1.article_id = aa2.article_id 
                JOIN articles a ON aa1.article_id = a.id 
                WHERE aa1.auteur_id = :reviewerId 
                AND aa2.auteur_id IN (
                    SELECT auteur_id 
                    FROM articles 
                    WHERE id = :articleId 
                    UNION 
                    SELECT auteur_id 
                    FROM auteurs_articles 
                    WHERE article_id = :articleId
                )
                AND a.date_soumission >= DATE_SUB(CURDATE(), INTERVAL 2 YEAR)";
        
        $result = $this->db->fetchOne($sql, [
            ':articleId' => $articleId,
            ':reviewerId' => $reviewerId
        ]);
        
        if (($result['collaboration_count'] ?? 0) > 0) {
            return ['conflict' => 'recent_collaboration', 'message' => 'Collaboration récente avec un auteur de l\'article'];
        }
        
        return ['conflict' => 'none', 'message' => 'Aucun conflit détecté'];
    }

    /**
     * Calculer le score de confiance d'un évaluateur
     */
    public function calculateReviewerTrustScore($reviewerId) {
        $stats = $this->getReviewerStats($reviewerId);
        
        if ($stats['completed'] == 0) {
            return 50; // Score par défaut pour les nouveaux évaluateurs
        }
        
        $score = 0;
        
        // Facteur: complétion des évaluations
        $completionRate = ($stats['completed'] / $stats['total_assigned']) * 100;
        $score += $completionRate * 0.3;
        
        // Facteur: temps de réponse moyen (plus rapide = mieux)
        $responseTimeScore = max(0, 100 - ($stats['avg_response_days'] * 5));
        $score += $responseTimeScore * 0.3;
        
        // Facteur: cohérence des scores (à implémenter avec plus de données)
        $score += 40; // Placeholder
        
        return min(100, max(0, round($score)));
    }
}
?>