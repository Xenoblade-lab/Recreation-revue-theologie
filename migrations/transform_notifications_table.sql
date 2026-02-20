-- Migration pour transformer la table notifications existante vers la nouvelle structure
-- Date: 2025-12-13

-- Vérifier si la table existe avec l'ancienne structure Laravel
-- Si oui, la transformer vers la nouvelle structure

-- Étape 1: Sauvegarder les données existantes (si nécessaire)
-- CREATE TABLE IF NOT EXISTS notifications_backup AS SELECT * FROM notifications;

-- Étape 2: Supprimer l'ancienne table si elle existe avec l'ancienne structure
DROP TABLE IF EXISTS `notifications`;

-- Étape 3: Créer la nouvelle table avec la structure correcte
CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` ENUM('article_status_change', 'evaluation_assigned', 'evaluation_completed', 'revision_required', 'article_accepted', 'article_rejected', 'article_published', 'article_resubmitted') NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `related_article_id` bigint UNSIGNED DEFAULT NULL,
  `related_evaluation_id` bigint UNSIGNED DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_is_read` (`is_read`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_related_article_id` (`related_article_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`related_article_id`) REFERENCES `articles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

