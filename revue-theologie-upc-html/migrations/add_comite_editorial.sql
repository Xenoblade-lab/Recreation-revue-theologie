-- Table comite_editorial : membres du comité pouvant être assignés comme évaluateurs (Option B étape 5)
-- Exécuter une fois : mysql -u root -p revue < migrations/add_comite_editorial.sql

CREATE TABLE IF NOT EXISTS comite_editorial (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  ordre INT NOT NULL DEFAULT 0,
  titre_affiche VARCHAR(255) NULL DEFAULT NULL,
  actif TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY comite_editorial_user_id (user_id),
  KEY comite_editorial_actif_ordre (actif, ordre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
