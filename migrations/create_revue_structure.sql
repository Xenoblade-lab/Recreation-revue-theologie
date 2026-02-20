-- Migration : Structure complète Revue → Volumes → Numéros → Articles
-- Date : 2025-01-15

-- 1. Créer la table revue_info (identité de la revue)
CREATE TABLE IF NOT EXISTS `revue_info` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `nom_officiel` VARCHAR(255) NOT NULL DEFAULT 'Revue de Théologie de l\'UPC',
  `description` TEXT,
  `ligne_editoriale` TEXT,
  `objectifs` TEXT,
  `domaines_couverts` TEXT,
  `issn` VARCHAR(50),
  `comite_scientifique` TEXT,
  `comite_redaction` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insérer une entrée par défaut
INSERT INTO `revue_info` (`nom_officiel`, `description`, `created_at`, `updated_at`) 
VALUES ('Revue de Théologie de l\'UPC', 'Revue scientifique de la Faculté de Théologie de l\'Université Protestante au Congo', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nom_officiel` = `nom_officiel`;

-- 2. Créer la table volumes (regroupement par année)
CREATE TABLE IF NOT EXISTS `volumes` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `annee` INT NOT NULL UNIQUE,
  `numero_volume` VARCHAR(50),
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_annee` (`annee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Modifier la table revues pour en faire des issues (numéros)
-- Ajouter volume_id et type si elles n'existent pas déjà
SET @dbname = DATABASE();
SET @tablename = 'revues';

-- Vérifier et ajouter volume_id
SELECT COUNT(*) INTO @col_exists FROM INFORMATION_SCHEMA.COLUMNS
WHERE table_schema = @dbname AND table_name = @tablename AND column_name = 'volume_id';

SET @sql = IF(@col_exists = 0,
    'ALTER TABLE `revues` ADD COLUMN `volume_id` INT NULL, ADD INDEX `idx_volume_id` (`volume_id`)',
    'SELECT 1 as skip');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Vérifier et ajouter type
SELECT COUNT(*) INTO @col_exists FROM INFORMATION_SCHEMA.COLUMNS
WHERE table_schema = @dbname AND table_name = @tablename AND column_name = 'type';

SET @sql = IF(@col_exists = 0,
    'ALTER TABLE `revues` ADD COLUMN `type` ENUM(\'issue\', \'special\') DEFAULT \'issue\'',
    'SELECT 1 as skip');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 4. Ajouter issue_id dans articles (lien direct)
SET @tablename = 'articles';
SELECT COUNT(*) INTO @col_exists FROM INFORMATION_SCHEMA.COLUMNS
WHERE table_schema = @dbname AND table_name = @tablename AND column_name = 'issue_id';

SET @sql = IF(@col_exists = 0,
    'ALTER TABLE `articles` ADD COLUMN `issue_id` INT NULL, ADD INDEX `idx_issue_id` (`issue_id`)',
    'SELECT 1 as skip');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

