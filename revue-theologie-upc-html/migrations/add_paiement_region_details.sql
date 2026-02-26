-- Région choisie et détails de paiement (téléphone ou masqué carte) pour les demandes d'abonnement
ALTER TABLE paiements ADD COLUMN region VARCHAR(50) DEFAULT NULL AFTER moyen;
ALTER TABLE paiements ADD COLUMN payment_details TEXT DEFAULT NULL COMMENT 'JSON: phoneNumber ou cardLast4' AFTER region;
