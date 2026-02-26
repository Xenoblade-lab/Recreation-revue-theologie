-- Ajouter le statut 'brouillon' aux articles (sauvegarde sans soumettre)
-- Exécuter une fois : mysql -u root -p revue < migrations/add_article_statut_brouillon.sql

ALTER TABLE articles
  MODIFY COLUMN statut ENUM('brouillon','soumis','valide','rejete') NOT NULL DEFAULT 'soumis';
