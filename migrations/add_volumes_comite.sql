-- Comité éditorial par année (un seul bloc par volume) + rédacteur en chef
-- Exécuter une seule fois. Si une colonne existe déjà, MySQL renverra une erreur "duplicate column" : ignorer.

ALTER TABLE `volumes`
  ADD COLUMN `comite_editorial` TEXT NULL AFTER `description`;
ALTER TABLE `volumes`
  ADD COLUMN `redacteur_chef` VARCHAR(255) NULL AFTER `comite_editorial`;
