-- Migration pour ajouter les types de notifications manquants
-- Date: 2025-12-13

ALTER TABLE `notifications` 
MODIFY COLUMN `type` ENUM(
    'article_status_change', 
    'evaluation_assigned', 
    'evaluation_completed', 
    'revision_required', 
    'article_accepted', 
    'article_rejected', 
    'article_published',
    'article_resubmitted'
) NOT NULL;

