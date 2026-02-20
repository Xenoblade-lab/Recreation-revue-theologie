# Schéma de Base de Données Simplifié
## Revue de la Faculté de Théologie - UPC

### Vue d'ensemble

Ce schéma simplifié contient **9 tables principales** (au lieu de 26), conformément à la documentation du projet. Il couvre tous les besoins essentiels pour la gestion de la revue académique.

---

## Tables principales

### 1. `users` - Utilisateurs
**Rôle** : Gère tous les utilisateurs du système (auteurs, reviewers, admins, abonnés)

**Champs principaux** :
- `id`, `nom`, `prenom`, `email`, `password`
- `role` : admin, auteur, reviewer, abonne
- `affiliation` : Institution/Université
- `orcid` : Identifiant ORCID (optionnel)
- `statut` : actif, suspendu, en_attente

---

### 2. `volumes` - Volumes
**Rôle** : Représente les volumes annuels de la revue (ex: Volume 28, Volume 29)

**Champs principaux** :
- `id`, `annee`, `numero` (ex: "Volume 28" ou "28e Année")
- `description`

**Relation** : Un volume contient plusieurs `issues`

---

### 3. `issues` - Numéros
**Rôle** : Numéros spécifiques dans un volume (ex: Numéro 1, Numéro 2)

**Champs principaux** :
- `id`, `volume_id` (FK vers volumes)
- `titre`, `description`
- `date_publication`
- `fichier_path` : PDF complet du numéro
- `statut` : brouillon, en_preparation, publie

**Relation** : Un numéro contient plusieurs `articles`

---

### 4. `articles` - Articles
**Rôle** : Articles scientifiques (publiés ou en cours de traitement)

**Champs principaux** :
- `id`, `issue_id` (FK vers issues, NULL si pas encore publié)
- `title`, `title_en` : Titre FR et EN
- `abstract`, `abstract_en` : Résumés FR et EN
- `keywords`, `keywords_en` : Mots-clés
- `pdf_path` : Chemin vers le PDF
- `doi` : DOI si attribué
- `status` : soumis, en_revision, accepte, rejete, publie
- `pages` : Ex: "15-42"

**Relations** :
- Plusieurs auteurs via `authors_articles`
- Plusieurs fichiers via `files`
- Plusieurs évaluations via `reviews`

---

### 5. `authors_articles` - Relation Auteurs-Articles
**Rôle** : Table de liaison many-to-many entre auteurs et articles

**Champs principaux** :
- `id`, `article_id` (FK), `user_id` (FK)
- `ordre` : Ordre de l'auteur (1 = premier auteur)
- `affiliation_text` : Affiliation spécifique pour cet article

**Utilité** : Permet plusieurs auteurs par article avec ordre défini

---

### 6. `reviews` - Évaluations par les pairs
**Rôle** : Rapports de relecture (peer review)

**Champs principaux** :
- `id`, `article_id` (FK), `reviewer_id` (FK vers users)
- `report_text` : Rapport de relecture
- `recommendation` : accepte, revision_majeure, revision_mineure, rejete
- `date` : Date de soumission du rapport
- `confidential_comments` : Commentaires confidentiels pour l'éditeur

**Utilité** : Gère le processus d'évaluation par les pairs

---

### 7. `submissions` - Historique des soumissions
**Rôle** : Suivi des versions et soumissions d'articles

**Champs principaux** :
- `id`, `article_id` (FK)
- `submitted_at` : Date de soumission
- `version` : Version du manuscrit (ex: "1.0", "2.0")
- `track_changes` : Suivi des modifications
- `notes_editoriales` : Notes internes

**Utilité** : Historique complet des soumissions et révisions

---

### 8. `files` - Fichiers
**Rôle** : Fichiers associés aux articles

**Champs principaux** :
- `id`, `article_id` (FK)
- `file_type` : manuscrit, figure, tableau, annexe, autre
- `path` : Chemin vers le fichier
- `nom_original` : Nom original du fichier uploadé
- `description`, `taille`

**Utilité** : Gère tous les fichiers liés à un article (manuscrits, figures, etc.)

---

### 9. `logs` - Journal d'audit
**Rôle** : Traçabilité de toutes les actions importantes

**Champs principaux** :
- `id`, `user_id` (FK, nullable)
- `action` : Type d'action (create, update, delete, login, etc.)
- `model_type`, `model_id` : Modèle concerné
- `description` : Description de l'action
- `ip_address`, `user_agent`
- `created_at`

**Utilité** : Audit trail pour sécurité et traçabilité

---

## Schéma relationnel simplifié

```
users
  ├── authors_articles (many-to-many avec articles)
  ├── reviews (comme reviewer)
  └── logs (comme acteur)

volumes
  └── issues (one-to-many)

issues
  └── articles (one-to-many)

articles
  ├── authors_articles (many-to-many avec users)
  ├── reviews (one-to-many)
  ├── submissions (one-to-many)
  └── files (one-to-many)
```

---

## Comparaison avec l'ancien schéma

### Tables supprimées (non essentielles) :
- ❌ `abonnements`, `paiements` : Peuvent être gérés via `users.role = 'abonne'`
- ❌ `commentaires`, `notes` : Fonctionnalités optionnelles
- ❌ `notifications` : Peut être géré côté application
- ❌ `revue_photos`, `revue_parts` : Peuvent être dans `files`
- ❌ `cache`, `cache_locks`, `sessions` : Gérés par l'application
- ❌ `migrations`, `permissions`, `roles` : Spécifiques à Laravel
- ❌ `personal_access_tokens`, `failed_jobs`, `jobs` : Spécifiques à Laravel

### Tables conservées et adaptées :
- ✅ `users` : Simplifié (role direct au lieu de système de permissions)
- ✅ `articles` : Enrichi avec champs FR/EN, DOI, etc.
- ✅ `reviews` : Nouvelle table pour peer review
- ✅ `submissions` : Nouvelle table pour historique
- ✅ `files` : Nouvelle table pour gestion des fichiers
- ✅ `logs` : Nouvelle table pour audit

### Nouvelles tables :
- ✅ `volumes` : Séparation volumes/issues
- ✅ `issues` : Numéros dans les volumes
- ✅ `authors_articles` : Relation many-to-many propre

---

## Installation

1. **Importer le fichier SQL** :
   ```bash
   mysql -u root -p < revue_simplifie.sql
   ```

2. **Vérifier la connexion** :
   - Le fichier `includes/db.php` est déjà configuré pour `revue_theologie`
   - Modifier les constantes si nécessaire (DB_HOST, DB_USER, DB_PASS)

3. **Compte admin par défaut** :
   - Email : `admin@revue-theologie.upc.ac.cd`
   - Mot de passe : `admin123` (⚠️ À CHANGER EN PRODUCTION !)

---

## Avantages de cette simplification

✅ **Maintenabilité** : Moins de tables = code plus simple  
✅ **Performance** : Moins de jointures complexes  
✅ **Clarté** : Structure alignée avec la documentation  
✅ **Évolutivité** : Facile d'ajouter des tables si besoin  
✅ **Standards** : Conforme aux pratiques des revues académiques  

---

## Notes importantes

- Les **contraintes de clés étrangères** sont activées (InnoDB)
- Les **cascades** sont configurées pour la cohérence
- Le **charset** est `utf8mb4` pour support Unicode complet
- Les **index** sont optimisés pour les requêtes fréquentes

---

## Prochaines étapes

1. ✅ Tester l'import du schéma
2. ⏳ Adapter le code PHP existant aux nouvelles tables
3. ⏳ Créer les scripts de migration de données (si nécessaire)
4. ⏳ Documenter les requêtes SQL principales

