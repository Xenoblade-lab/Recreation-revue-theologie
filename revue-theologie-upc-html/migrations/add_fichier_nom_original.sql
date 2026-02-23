-- Nom d'origine du fichier PDF pour l'affichage et le téléchargement
-- Exécuter une fois : mysql -u root -p revue < migrations/add_fichier_nom_original.sql

ALTER TABLE articles
  ADD COLUMN fichier_nom_original VARCHAR(255) NULL DEFAULT NULL
  AFTER fichier_path;
