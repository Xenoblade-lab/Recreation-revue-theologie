-- Migration pour ajouter les statuts manquants dans la table articles
-- Date: 2025-12-13

-- Modifier l'ENUM de la colonne statut pour inclure tous les statuts nécessaires
ALTER TABLE `articles` 
MODIFY COLUMN `statut` ENUM(
    'soumis',
    'en_evaluation',
    'revision_requise',
    'accepte',
    'rejete',
    'publie',
    'valide'  -- Garder pour compatibilité avec les anciennes données
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'soumis';

-- Mettre à jour les statuts existants pour correspondre aux nouveaux
-- 'valide' devient 'accepte'
UPDATE `articles` SET `statut` = 'accepte' WHERE `statut` = 'valide';

