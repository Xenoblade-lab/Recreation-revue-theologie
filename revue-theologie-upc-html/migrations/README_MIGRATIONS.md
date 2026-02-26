# Migrations base de données

Exécuter les migrations **une seule fois** si besoin.

## add_paiement_region_details.sql

Ajoute les colonnes **region** et **payment_details** à la table `paiements` (pour l’historique des paiements et l’affichage de la région côté auteur).

**Sans cette migration :** la colonne « Région » reste vide dans l’historique des paiements (page Mon abonnement).

### Avec Laragon (MySQL en ligne de commande)

Dans un terminal, à la racine du projet `revue-theologie-upc-html` :

```bash
cd c:\laragon\www\Recreation-revu-theologie\revue-theologie-upc-html
mysql -u root revue < migrations/add_paiement_region_details.sql
```

(Si votre base a un autre nom que `revue`, remplacez `revue` par le nom de votre base.)

### Avec phpMyAdmin

1. Ouvrir phpMyAdmin (ex. http://localhost/phpmyadmin).
2. Sélectionner la base `revue` (ou votre base).
3. Onglet **SQL**.
4. Copier-coller le contenu de `migrations/add_paiement_region_details.sql`.
5. Exécuter.

**Note :** Les paiements déjà créés avant la migration auront une région vide (NULL). Seuls les **nouveaux** paiements (nouvelles demandes d’abonnement) afficheront la région dans l’historique.
