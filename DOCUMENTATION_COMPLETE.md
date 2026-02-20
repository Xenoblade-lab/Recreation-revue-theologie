# Documentation Complète - Site Web Revue de Théologie UPC

## Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Architecture technique](#architecture-technique)
3. [Rôles et permissions](#rôles-et-permissions)
4. [Fonctionnalités par rôle](#fonctionnalités-par-rôle)
5. [Workflow des articles](#workflow-des-articles)
6. [Système d'abonnement et paiement](#système-dabonnement-et-paiement)
7. [Structure de la base de données](#structure-de-la-base-de-données)
8. [Routes principales](#routes-principales)
9. [Guide d'utilisation](#guide-dutilisation)
10. [Système de notifications](#système-de-notifications)
11. [Gestion des volumes et numéros](#gestion-des-volumes-et-numéros)
12. [Interface publique](#interface-publique)
13. [Palette de couleurs (identité UPC)](#palette-de-couleurs-identité-upc)

---

## Vue d'ensemble

Le site web de la **Revue de la Faculté de Théologie de l'Université Protestante au Congo (UPC)** est une plateforme complète de gestion et de publication d'articles scientifiques. Il permet :

- La soumission en ligne d'articles par les auteurs
- L'évaluation par les pairs (peer review)
- La gestion éditoriale complète
- La publication et l'archivage des articles
- La gestion des abonnements et paiements
- L'accès public aux publications

### Objectifs principaux

1. **Automatiser le processus éditorial** : De la soumission à la publication
2. **Faciliter la collaboration** : Entre auteurs, évaluateurs et administrateurs
3. **Gérer les abonnements** : Système de paiement intégré pour devenir auteur
4. **Archiver et publier** : Organisation en volumes et numéros
5. **Rendre accessible** : Interface publique pour consulter les publications

---

## Architecture technique

### Stack technologique

- **Backend** : PHP 7.4+ (orienté objet)
- **Base de données** : MySQL/MariaDB
- **Frontend** : HTML5, CSS3, JavaScript (vanilla)
- **Routing** : AltoRouter (système de routes personnalisé)
- **Architecture** : MVC (Model-View-Controller)
- **Sessions** : PHP Sessions pour l'authentification

### Structure du projet

```
Revue-Theologie-Upc/
├── controllers/          # Contrôleurs (logique métier)
│   ├── AdminController.php
│   ├── AuthorController.php
│   ├── ReviewerController.php
│   ├── RevueController.php
│   └── ...
├── models/              # Modèles (accès base de données)
│   ├── ArticleModel.php
│   ├── UserModel.php
│   ├── ReviewModel.php
│   └── ...
├── views/               # Vues (templates PHP)
│   ├── admin/
│   ├── author/
│   ├── reviewer/
│   └── ...
├── public/              # Fichiers publics
│   ├── css/
│   ├── js/
│   ├── uploads/
│   └── ...
├── Router/              # Système de routage
├── service/             # Services (authentification, etc.)
├── includes/            # Fichiers inclus (helpers, config)
└── routes/              # Définition des routes
```

### Principes de conception

- **Séparation des responsabilités** : Modèles, Vues, Contrôleurs séparés
- **Authentification centralisée** : Service AuthService pour gérer les sessions
- **Permissions basées sur les rôles** : Système de rôles (admin, auteur, reviewer)
- **Validation des données** : Validation côté serveur pour toutes les entrées
- **Gestion d'erreurs** : Try-catch et logs d'erreurs

---

## Rôles et permissions

### 1. **Administrateur / Rédacteur en chef**

**Rôle** : Gestion complète de la plateforme

**Permissions** :
- Accès au dashboard administrateur
- Gestion des utilisateurs (création, modification, suppression)
- Gestion des articles (assignation, changement de statut, publication)
- Gestion des évaluations (assignation d'évaluateurs)
- Gestion des volumes et numéros
- Gestion des paiements
- Paramètres de la revue
- Bascule de rôle (peut se mettre en mode reviewer ou auteur pour tester)

**Routes principales** :
- `/admin` - Dashboard
- `/admin/users` - Gestion utilisateurs
- `/admin/articles` - Gestion articles
- `/admin/evaluations` - Gestion évaluations
- `/admin/volumes` - Gestion volumes
- `/admin/paiements` - Gestion paiements

### 2. **Auteur**

**Rôle** : Soumettre et gérer ses articles

**Permissions** :
- Soumettre des articles
- Modifier ses articles (si statut = "soumis")
- Supprimer ses articles (si statut = "soumis")
- Consulter l'historique de ses soumissions
- Consulter les révisions demandées
- Gérer son profil
- Consulter ses notifications
- **Nécessite un abonnement actif** pour accéder aux fonctionnalités

**Routes principales** :
- `/author` - Dashboard auteur
- `/author/subscribe` - Page d'abonnement
- `/author/articles` - Liste des articles
- `/author/article/[id]` - Détails d'un article
- `/author/article/[id]/edit` - Édition d'un article
- `/author/abonnement` - Gestion abonnement
- `/author/notifications` - Notifications

### 3. **Évaluateur (Reviewer)**

**Rôle** : Évaluer les articles soumis

**Permissions** :
- Consulter les articles assignés
- Évaluer les articles (formulaire d'évaluation)
- Sauvegarder des brouillons d'évaluation
- Consulter l'historique des évaluations
- Gérer son profil
- Consulter les publications

**Routes principales** :
- `/reviewer` - Dashboard évaluateur
- `/reviewer/evaluation/[id]` - Page d'évaluation
- `/reviewer/terminees` - Évaluations terminées
- `/reviewer/historique` - Historique complet

### 4. **Utilisateur public**

**Rôle** : Consultation des publications

**Permissions** :
- Consulter les articles publiés
- Télécharger les PDFs
- Consulter les archives (volumes et numéros)
- Rechercher dans les publications
- Consulter les informations de la revue

**Routes principales** :
- `/` - Page d'accueil
- `/publications` - Liste des publications
- `/article/[id]` - Détails d'un article
- `/archives` - Archives
- `/volume/[year]` - Volume spécifique
- `/numero/[id]` - Numéro spécifique

---

## Fonctionnalités par rôle

### 🎯 Fonctionnalités Auteur

#### 1. **Abonnement**

**Processus** :
1. L'utilisateur doit s'abonner pour devenir auteur
2. Choix de la région (Afrique, Europe, Amérique) avec tarifs différents :
   - Afrique : 25,00 $
   - Europe : 30,00 $
   - Amérique : 35,00 $
3. Choix du moyen de paiement :
   - Orange Money
   - M-Pesa
   - Airtel Money
   - Carte bancaire
4. Création automatique de l'abonnement (durée : 1 an)
5. Attribution automatique du rôle "auteur"

**Fonctionnalités** :
- Page d'abonnement : `/author/subscribe`
- Gestion de l'abonnement : `/author/abonnement`
- Résiliation d'abonnement (avec confirmation)
- Téléchargement des reçus de paiement
- Historique des paiements

#### 2. **Soumission d'articles**

**Processus** :
1. Formulaire de soumission avec :
   - Titre de l'article (obligatoire)
   - Résumé/contenu (obligatoire)
   - Catégorie (Théologie Systématique, Études Bibliques, Éthique Chrétienne, Histoire de l'Église, Théologie Pratique)
   - Type de publication (Article de recherche, Note de recherche, Compte-rendu)
   - Fichier PDF/Word/LaTeX (obligatoire)
2. Upload du fichier (formats acceptés : PDF, DOC, DOCX, TEX)
3. Soumission → Statut initial : "soumis"
4. Notification automatique à l'administrateur

**Fonctionnalités** :
- Formulaire de soumission : Dashboard auteur (`/author`)
- Validation côté client et serveur
- Affichage du nom du fichier sélectionné
- Messages de succès/erreur via toasts

#### 3. **Gestion des articles**

**Actions possibles** :

- **Consulter** : Voir les détails d'un article
  - Statut actuel
  - Historique du workflow
  - Révisions demandées
  - Évaluations (si disponibles)

- **Modifier** : Modifier un article (seulement si statut = "soumis")
  - Modifier le titre
  - Modifier le résumé
  - Remplacer le fichier
  - Sauvegarder les modifications

- **Resoumettre après révision** :
  - Si l'article a le statut "revision_requise"
  - Modifier l'article selon les commentaires
  - Resoumettre → Statut repasse à "en_evaluation"
  - Les évaluations précédentes sont réinitialisées
  - Nouveaux délais d'évaluation (14 jours)

- **Supprimer** : Supprimer un article (seulement si statut = "soumis")
  - Confirmation via popup
  - Suppression définitive

**Statuts possibles** :
- `soumis` : Article soumis, en attente d'assignation
- `en_evaluation` : Article assigné à des évaluateurs
- `revision_requise` : Révisions demandées par les évaluateurs
- `accepte` : Article accepté pour publication
- `rejete` : Article rejeté
- `publie` : Article publié dans un numéro

#### 4. **Notifications**

**Types de notifications** :
- Changement de statut d'article
- Demande de révision
- Article accepté/rejeté
- Article publié
- Nouvelle évaluation assignée

**Fonctionnalités** :
- Liste des notifications non lues
- Marquer comme lu (individuel ou en masse)
- Lien direct vers l'article concerné
- Badge de compteur de notifications non lues

#### 5. **Profil**

**Gestion du profil** :
- Modifier les informations personnelles
- Changer le mot de passe
- Consulter les statistiques (nombre d'articles soumis, publiés, etc.)

---

### 🛠️ Fonctionnalités Administrateur

#### 1. **Dashboard**

**Statistiques affichées** :
- Nombre total d'articles
- Articles publiés
- Évaluateurs actifs
- Revenus du mois

**Dernières soumissions** :
- Liste des 5 dernières soumissions
- Informations : Titre, date, statut, auteur

#### 2. **Gestion des utilisateurs**

**Actions** :
- **Créer un utilisateur** :
  - Nom, prénom, email
  - Rôle (admin, auteur, reviewer, abonné)
  - Statut (actif, suspendu, en_attente)
  - Affiliation, ORCID (optionnel)

- **Modifier un utilisateur** :
  - Toutes les informations modifiables
  - Changement de rôle
  - Changement de statut

- **Supprimer/Suspendre** :
  - Suspension d'un utilisateur
  - Suppression définitive (avec confirmation)

- **Créer un évaluateur** :
  - Formulaire dédié pour créer un évaluateur
  - Attribution automatique du rôle reviewer

**Filtres et recherche** :
- Liste paginée (200 utilisateurs max)
- Tri par date de création

#### 3. **Gestion des articles**

**Actions** :

- **Consulter les détails** :
  - Informations complètes de l'article
  - Historique des statuts
  - Révisions effectuées
  - Évaluations associées

- **Changer le statut** :
  - Passage manuel d'un statut à un autre
  - Statuts disponibles : soumis, en_evaluation, revision_requise, accepte, rejete, publie

- **Assigner des évaluateurs** :
  - Liste des évaluateurs disponibles
  - Assignation d'un ou plusieurs évaluateurs
  - Délai d'évaluation (par défaut : 14 jours)
  - Désassignation possible

- **Publier un article** :
  - Assignation à un numéro spécifique
  - Attribution de pages (ex: "15-42")
  - Attribution de DOI (optionnel)
  - Publication finale

- **Supprimer un article** :
  - Suppression définitive (avec confirmation)
  - Suppression des fichiers associés

**Filtres** :
- Par statut
- Par auteur
- Par date de soumission
- Recherche par titre

#### 4. **Gestion des évaluations**

**Vue d'ensemble** :
- Liste de toutes les évaluations
- Informations : Article, évaluateur, statut, délai
- Statistiques : Total, en attente, en cours, terminées, annulées

**Actions** :
- Consulter les détails d'une évaluation
- Voir le rapport d'évaluation (si terminée)
- Réassigner si nécessaire

#### 5. **Gestion des volumes et numéros**

**Volumes** :
- Créer un volume (année, numéro, description)
- Modifier un volume
- Supprimer un volume
- Consulter les détails d'un volume (liste des numéros)

**Numéros** :
- Créer un numéro (titre, description, date de publication)
- Assigner un numéro à un volume
- Modifier un numéro
- Supprimer un numéro
- Assigner des articles à un numéro
- Upload du PDF complet du numéro

**Workflow** :
1. Créer un volume (ex: Volume 28, Année 2025)
2. Créer des numéros dans ce volume (ex: Numéro 1, Numéro 2)
3. Assigner des articles acceptés aux numéros
4. Uploader le PDF du numéro complet
5. Publier le numéro

#### 6. **Gestion des paiements**

**Fonctionnalités** :
- Liste de tous les paiements
- Informations : Utilisateur, montant, moyen, statut, date
- Changer le statut d'un paiement :
  - `en_attente` → `valide` / `refuse`
- Filtrer par statut
- Rechercher par utilisateur

**Statuts de paiement** :
- `en_attente` : Paiement en attente de validation
- `valide` : Paiement validé
- `refuse` : Paiement refusé

#### 7. **Paramètres de la revue**

**Paramètres configurables** :
- Informations générales de la revue
- Politique éditoriale
- Instructions aux auteurs
- Coordonnées de contact
- Paramètres de publication

---

### 📝 Fonctionnalités Évaluateur

#### 1. **Dashboard**

**Statistiques** :
- Articles assignés (en attente)
- Évaluations en cours
- Évaluations terminées
- Taux de complétion

**Articles assignés** :
- Liste des articles à évaluer
- Informations : Titre, date d'assignation, délai restant
- Statut : En attente, En cours, Terminé

#### 2. **Évaluation d'un article**

**Processus** :
1. Accès à la page d'évaluation (`/reviewer/evaluation/[id]`)
2. Consultation de l'article :
   - Téléchargement du PDF
   - Informations de l'article (titre, résumé, auteur)
3. Formulaire d'évaluation :
   - **Recommandation** (obligatoire) :
     - Accepté
     - Révisions mineures requises
     - Révisions majeures requises
     - Rejeté
   - **Commentaires pour l'auteur** (public) :
     - Commentaires visibles par l'auteur
   - **Commentaires pour l'éditeur** (privé) :
     - Commentaires confidentiels
   - **Note globale** (optionnel) :
     - Note sur 10 ou 100
4. **Sauvegarde de brouillon** :
   - Sauvegarder sans soumettre
   - Reprendre plus tard
5. **Soumission de l'évaluation** :
   - Validation finale
   - Mise à jour du statut de l'article
   - Notification à l'auteur et à l'admin

**Règles de mise à jour du statut** :
- Si tous les évaluateurs recommandent "Accepté" → Statut = "accepte"
- Si au moins un recommande "Révisions majeures" → Statut = "revision_requise"
- Si au moins un recommande "Révisions mineures" → Statut = "revision_requise"
- Si tous recommandent "Rejeté" → Statut = "rejete"
- Si mixte → Décision de l'admin

#### 3. **Historique des évaluations**

**Fonctionnalités** :
- Liste de toutes les évaluations (terminées et en cours)
- Filtrer par statut
- Consulter les évaluations passées
- Voir les articles évalués

#### 4. **Publications**

**Accès** :
- Consulter les articles publiés
- Télécharger les PDFs
- Rechercher dans les publications

---

## Workflow des articles

### Flux complet de soumission à publication

```
1. SOUMISSION
   └─> Auteur soumet un article
       └─> Statut : "soumis"
       └─> Notification admin

2. ASSIGNATION
   └─> Admin assigne des évaluateurs (1-3)
       └─> Statut : "en_evaluation"
       └─> Notification évaluateurs
       └─> Délai : 14 jours (configurable)

3. ÉVALUATION
   └─> Évaluateurs évaluent l'article
       └─> Sauvegarde brouillon possible
       └─> Soumission de l'évaluation
       └─> Mise à jour automatique du statut

4. DÉCISION
   ├─> ACCEPTÉ
   │   └─> Statut : "accepte"
   │   └─> Notification auteur
   │
   ├─> RÉVISIONS REQUISES
   │   └─> Statut : "revision_requise"
   │   └─> Notification auteur avec commentaires
   │   └─> Création d'une entrée de révision
   │
   └─> REJETÉ
       └─> Statut : "rejete"
       └─> Notification auteur

5. RÉVISION (si nécessaire)
   └─> Auteur modifie l'article
       └─> Resoumission
       └─> Statut : "en_evaluation"
       └─> Réinitialisation des évaluations
       └─> Nouveau délai d'évaluation

6. PUBLICATION
   └─> Admin assigne l'article à un numéro
       └─> Attribution de pages, DOI
       └─> Statut : "publie"
       └─> Notification auteur
       └─> Article visible publiquement
```

### Détails des statuts

| Statut | Description | Actions possibles |
|--------|-------------|-------------------|
| `soumis` | Article soumis, en attente | Modifier, Supprimer (auteur), Assigner évaluateurs (admin) |
| `en_evaluation` | Article en cours d'évaluation | Évaluer (reviewer), Consulter (admin/auteur) |
| `revision_requise` | Révisions demandées | Modifier et resoumettre (auteur) |
| `accepte` | Article accepté | Assigner à un numéro (admin) |
| `rejete` | Article rejeté | Consulter (auteur/admin) |
| `publie` | Article publié | Consulter publiquement, Télécharger |

### Système de révisions

**Création automatique** :
- Quand le statut passe à "revision_requise"
- Enregistrement dans la table `article_revisions`
- Informations : Ancien statut, nouveau statut, raison, date

**Resoumission** :
- L'auteur modifie l'article
- Resoumission → Statut repasse à "en_evaluation"
- Les évaluations précédentes sont réinitialisées
- Nouveau délai d'évaluation
- Notification aux évaluateurs assignés

---

## Système d'abonnement et paiement

### Modèle d'abonnement

**Durée** : 1 an (365 jours)

**Tarifs par région** :
- **Afrique** : 25,00 $
- **Europe** : 30,00 $
- **Amérique** : 35,00 $

### Moyens de paiement

1. **Orange Money**
   - Numéro de téléphone requis
   - Validation du format

2. **M-Pesa**
   - Numéro de téléphone requis
   - Validation du format

3. **Airtel Money**
   - Numéro de téléphone requis
   - Validation du format

4. **Carte bancaire**
   - Numéro de carte (16 chiffres)
   - Date d'expiration (MM/AA)
   - CVC (3 chiffres)
   - Nom sur la carte

### Processus de paiement

1. **Sélection de la région** → Affichage du tarif
2. **Choix du moyen de paiement** → Formulaire adapté
3. **Validation des données** → Vérification côté client et serveur
4. **Création du paiement** → Statut initial : "en_attente"
5. **Validation automatique** (simulation) → Statut : "valide"
6. **Création de l'abonnement** → Durée : 1 an
7. **Attribution du rôle "auteur"** → Accès aux fonctionnalités

### Gestion de l'abonnement

**Côté auteur** :
- Consulter l'abonnement actif
- Voir la date d'expiration
- Consulter l'historique des paiements
- Télécharger les reçus
- Résilier l'abonnement (avec confirmation)

**Côté admin** :
- Voir tous les paiements
- Changer le statut d'un paiement
- Valider/Refuser un paiement manuellement
- Consulter les statistiques de revenus

### Expiration de l'abonnement

- L'abonnement expire après 1 an
- L'utilisateur perd le rôle "auteur"
- Redirection vers la page d'abonnement
- Possibilité de renouveler

---

## Structure de la base de données

### Tables principales

#### 1. **users**
Gère tous les utilisateurs du système.

**Champs principaux** :
- `id` : Identifiant unique
- `nom`, `prenom` : Nom et prénom
- `email` : Email (unique)
- `password` : Mot de passe hashé
- `role` : Rôle principal (admin, auteur, reviewer, user, abonné)
- `statut` : Statut du compte (actif, suspendu, en_attente)
- `affiliation` : Institution/Université
- `orcid` : Identifiant ORCID (optionnel)
- `created_at`, `updated_at` : Dates

#### 2. **articles**
Stocke tous les articles soumis.

**Champs principaux** :
- `id` : Identifiant unique
- `titre` : Titre de l'article
- `contenu` : Résumé/contenu
- `fichier_path` : Chemin vers le fichier PDF/Word
- `auteur_id` : FK vers users
- `statut` : Statut actuel (soumis, en_evaluation, etc.)
- `categorie` : Catégorie de l'article
- `type_publication` : Type (article de recherche, etc.)
- `date_soumission` : Date de soumission
- `issue_id` : FK vers issues (si publié)
- `pages` : Pages dans le numéro (ex: "15-42")
- `doi` : DOI si attribué
- `created_at`, `updated_at` : Dates

#### 3. **evaluations**
Gère les évaluations des articles.

**Champs principaux** :
- `id` : Identifiant unique
- `article_id` : FK vers articles
- `evaluateur_id` : FK vers users (reviewer)
- `statut` : Statut (en_attente, en_cours, termine, annule)
- `recommendation` : Recommandation (accepte, revision_mineure, revision_majeure, rejete)
- `commentaires_public` : Commentaires visibles par l'auteur
- `commentaires_prive` : Commentaires pour l'éditeur
- `note` : Note globale (optionnel)
- `date_echeance` : Date limite d'évaluation
- `date_soumission` : Date de soumission de l'évaluation
- `created_at`, `updated_at` : Dates

#### 4. **article_revisions**
Historique des révisions demandées.

**Champs principaux** :
- `id` : Identifiant unique
- `article_id` : FK vers articles
- `previous_status` : Statut précédent
- `new_status` : Nouveau statut
- `revision_reason` : Raison de la révision
- `created_at` : Date

#### 5. **abonnements**
Gère les abonnements des auteurs.

**Champs principaux** :
- `id` : Identifiant unique
- `utilisateur_id` : FK vers users
- `date_debut` : Date de début
- `date_fin` : Date de fin
- `statut` : Statut (actif, expire, annule)
- `created_at`, `updated_at` : Dates

#### 6. **paiements**
Gère les paiements des abonnements.

**Champs principaux** :
- `id` : Identifiant unique
- `utilisateur_id` : FK vers users
- `montant` : Montant payé
- `moyen` : Moyen de paiement (orange_money, mpesa, airtel_money, bancaire)
- `statut` : Statut (en_attente, valide, refuse)
- `date_paiement` : Date de paiement
- `numero_transaction` : Numéro de transaction
- `region` : Région (afrique, europe, amerique)
- `numero_telephone` : Numéro de téléphone (si mobile money)
- `numero_carte` : Numéro de carte (si bancaire)
- `created_at`, `updated_at` : Dates

#### 7. **volumes**
Représente les volumes annuels de la revue.

**Champs principaux** :
- `id` : Identifiant unique
- `annee` : Année du volume
- `numero` : Numéro du volume (ex: "Volume 28")
- `description` : Description
- `created_at`, `updated_at` : Dates

#### 8. **issues** (revue_parts)
Représente les numéros dans un volume.

**Champs principaux** :
- `id` : Identifiant unique
- `volume_id` : FK vers volumes
- `titre` : Titre du numéro
- `description` : Description
- `date_publication` : Date de publication
- `fichier_path` : Chemin vers le PDF complet du numéro
- `statut` : Statut (brouillon, en_preparation, publie)
- `created_at`, `updated_at` : Dates

#### 9. **notifications**
Gère les notifications des utilisateurs.

**Champs principaux** :
- `id` : Identifiant unique
- `user_id` : FK vers users
- `type` : Type de notification
- `message` : Message
- `related_article_id` : FK vers articles (si lié à un article)
- `is_read` : Lu ou non
- `created_at` : Date

#### 10. **roles** et **permissions**
Système de rôles et permissions (Laravel-like).

**Tables** :
- `roles` : Liste des rôles
- `permissions` : Liste des permissions
- `model_has_roles` : Assignation de rôles aux utilisateurs
- `role_has_permissions` : Permissions par rôle

---

## Routes principales

### Routes publiques

```
GET  /                          → Page d'accueil
GET  /publications              → Liste des publications
GET  /article/[id]              → Détails d'un article
GET  /download/article/[id]    → Télécharger un article
GET  /archives                  → Archives
GET  /volume/[year]             → Volume spécifique
GET  /numero/[id]               → Numéro spécifique
GET  /comite                    → Comité éditorial
GET  /presentation              → Présentation de la revue
GET  /search                    → Recherche
POST /search                    → Recherche (formulaire)
```

### Routes authentification

```
GET  /login                     → Page de connexion
POST /login                     → Connexion
GET  /register                  → Page d'inscription
POST /register                  → Inscription
GET  /logout                    → Déconnexion
POST /logout                    → Déconnexion
```

### Routes auteur

```
GET  /author                    → Dashboard auteur
GET  /author/subscribe          → Page d'abonnement
POST /author/subscribe          → Créer un abonnement
GET  /author/articles           → Liste des articles
GET  /author/article/[id]       → Détails d'un article
GET  /author/article/[id]/edit  → Éditer un article
POST /author/article/[id]/update → Mettre à jour un article
POST /author/article/[id]/delete → Supprimer un article
GET  /author/article/[id]/revisions → Historique des révisions
GET  /author/abonnement         → Gestion abonnement
POST /author/abonnement/cancel  → Résilier abonnement
GET  /author/paiement/receipt/[id] → Télécharger reçu
POST /author/paiement/cancel    → Annuler un paiement
GET  /author/notifications      → Notifications
POST /author/notification/[id]/read → Marquer comme lu
POST /author/notifications/read-all → Tout marquer comme lu
GET  /author/profil             → Profil
POST /author/profil/update      → Mettre à jour le profil
```

### Routes admin

```
GET  /admin                     → Dashboard admin
GET  /admin/users               → Gestion utilisateurs
GET  /admin/user/[id]           → Détails utilisateur
POST /admin/user/create         → Créer un utilisateur
POST /admin/user/[id]/update    → Modifier un utilisateur
POST /admin/user/[id]/delete   → Supprimer un utilisateur
POST /admin/user/[id]/update-status → Changer le statut
GET  /admin/articles            → Gestion articles
GET  /admin/article/[id]        → Détails d'un article
POST /admin/article/[id]/update-status → Changer le statut
POST /admin/article/[id]/publish → Publier un article
POST /admin/article/[id]/delete → Supprimer un article
GET  /admin/article/[id]/reviewers → Évaluateurs disponibles
GET  /admin/article/[id]/assigned-reviewers → Évaluateurs assignés
POST /admin/article/[id]/assign-reviewer → Assigner un évaluateur
POST /admin/article/[article_id]/unassign-reviewer/[evaluation_id] → Désassigner
GET  /admin/evaluations         → Gestion évaluations
GET  /admin/evaluation/[id]     → Détails d'une évaluation
GET  /admin/volumes             → Gestion volumes
GET  /admin/volume/[id]         → Détails d'un volume
POST /admin/volumes/create      → Créer un volume
POST /admin/volumes/update      → Modifier un volume
POST /admin/volume/[id]/delete  → Supprimer un volume
POST /admin/issues/create       → Créer un numéro
POST /admin/issues/update       → Modifier un numéro
POST /admin/articles/[id]/assign-issue → Assigner à un numéro
GET  /admin/paiements           → Gestion paiements
POST /admin/paiement/[id]/update-status → Changer le statut
GET  /admin/settings            → Paramètres
GET  /admin/revue/settings      → Paramètres de la revue
POST /admin/revue/settings      → Mettre à jour les paramètres
```

### Routes évaluateur

```
GET  /reviewer                  → Dashboard évaluateur
GET  /reviewer/terminees        → Évaluations terminées
GET  /reviewer/historique       → Historique complet
GET  /reviewer/evaluation/[id]  → Page d'évaluation
POST /reviewer/evaluation/[id]/save-draft → Sauvegarder brouillon
POST /reviewer/evaluation/[id]/submit → Soumettre évaluation
GET  /reviewer/publications     → Publications
GET  /reviewer/profil           → Profil
```

### Routes API

```
GET  /articles                  → Liste des articles (JSON)
GET  /articles/[id]             → Détails d'un article (JSON)
POST /articles                  → Créer un article (JSON)
GET  /api/notifications         → Notifications (JSON)
POST /api/notifications/[id]/read → Marquer comme lu (JSON)
```

---

## Guide d'utilisation

### Pour les auteurs

#### 1. **S'inscrire et s'abonner**

1. Aller sur `/register`
2. Remplir le formulaire d'inscription
3. Se connecter avec ses identifiants
4. Aller sur `/author/subscribe`
5. Choisir sa région
6. Choisir un moyen de paiement
7. Remplir les informations de paiement
8. Confirmer → Abonnement créé automatiquement

#### 2. **Soumettre un article**

1. Aller sur `/author` (dashboard)
2. Remplir le formulaire "Soumettre un nouvel article" :
   - Titre (obligatoire)
   - Catégorie (obligatoire)
   - Type de publication (obligatoire)
   - Résumé/contenu (obligatoire)
   - Fichier PDF/Word/LaTeX (obligatoire)
3. Cliquer sur "Soumettre"
4. Message de succès → Article apparaît dans le tableau

#### 3. **Suivre son article**

1. Dans le tableau des soumissions, voir le statut :
   - **Soumis** : En attente d'assignation
   - **En évaluation** : Évaluateurs assignés
   - **Révisions requises** : Modifications demandées
   - **Accepté** : Prêt pour publication
   - **Rejeté** : Article non retenu
   - **Publié** : Article publié

2. Cliquer sur "Voir les détails" pour plus d'informations

#### 4. **Modifier un article**

1. Seulement si le statut est "Soumis"
2. Cliquer sur "Modifier" dans le tableau
3. Modifier les champs souhaités
4. Sauvegarder

#### 5. **Resoumettre après révision**

1. Si le statut est "Révisions requises"
2. Cliquer sur "Modifier et resoumettre"
3. Consulter les commentaires des évaluateurs
4. Modifier l'article selon les commentaires
5. Resoumettre → L'article repasse en évaluation

#### 6. **Consulter les notifications**

1. Aller sur `/author/notifications`
2. Voir les notifications non lues (badge)
3. Cliquer sur une notification pour voir les détails
4. Marquer comme lu individuellement ou tout marquer

### Pour les administrateurs

#### 1. **Gérer les articles**

1. Aller sur `/admin/articles`
2. Voir la liste de tous les articles
3. Filtrer par statut si nécessaire
4. Cliquer sur un article pour voir les détails

**Assigner des évaluateurs** :
1. Dans les détails d'un article, section "Évaluateurs"
2. Cliquer sur "Assigner un évaluateur"
3. Choisir un évaluateur dans la liste
4. Définir le délai (par défaut : 14 jours)
5. Confirmer → Notification envoyée à l'évaluateur

**Changer le statut** :
1. Dans les détails d'un article
2. Section "Actions"
3. Choisir le nouveau statut
4. Confirmer

**Publier un article** :
1. L'article doit être "Accepté"
2. Cliquer sur "Publier"
3. Choisir le numéro de publication
4. Renseigner les pages (ex: "15-42")
5. Optionnel : Ajouter un DOI
6. Confirmer → Article publié

#### 2. **Gérer les utilisateurs**

1. Aller sur `/admin/users`
2. Voir la liste des utilisateurs
3. **Créer un utilisateur** :
   - Cliquer sur "Créer un utilisateur"
   - Remplir le formulaire
   - Choisir le rôle
   - Confirmer
4. **Modifier** : Cliquer sur un utilisateur → Modifier
5. **Supprimer** : Cliquer sur "Supprimer" → Confirmer

#### 3. **Gérer les volumes et numéros**

**Créer un volume** :
1. Aller sur `/admin/volumes`
2. Cliquer sur "Créer un volume"
3. Renseigner : Année, Numéro, Description
4. Confirmer

**Créer un numéro** :
1. Dans la liste des volumes, cliquer sur un volume
2. Cliquer sur "Créer un numéro"
3. Renseigner : Titre, Description, Date de publication
4. Assigner au volume
5. Confirmer

**Assigner des articles à un numéro** :
1. Dans les détails d'un numéro
2. Section "Articles"
3. Cliquer sur "Assigner un article"
4. Choisir un article accepté
5. Confirmer

**Publier un numéro** :
1. Uploader le PDF complet du numéro
2. Changer le statut en "Publié"
3. Le numéro devient visible publiquement

#### 4. **Gérer les paiements**

1. Aller sur `/admin/paiements`
2. Voir la liste des paiements
3. Filtrer par statut si nécessaire
4. Cliquer sur un paiement pour voir les détails
5. Changer le statut :
   - `en_attente` → `valide` (valider le paiement)
   - `en_attente` → `refuse` (refuser le paiement)

### Pour les évaluateurs

#### 1. **Évaluer un article**

1. Aller sur `/reviewer` (dashboard)
2. Voir la liste des articles assignés
3. Cliquer sur "Évaluer" pour un article
4. Télécharger et lire l'article
5. Remplir le formulaire d'évaluation :
   - **Recommandation** (obligatoire)
   - **Commentaires pour l'auteur** (public)
   - **Commentaires pour l'éditeur** (privé)
   - **Note globale** (optionnel)
6. **Sauvegarder le brouillon** (si besoin) → Reprendre plus tard
7. **Soumettre l'évaluation** → Évaluation terminée

#### 2. **Consulter l'historique**

1. Aller sur `/reviewer/historique`
2. Voir toutes les évaluations (terminées et en cours)
3. Filtrer par statut si nécessaire
4. Consulter les évaluations passées

---

## Système de notifications

### Types de notifications

1. **Changement de statut d'article**
   - Quand le statut d'un article change
   - Message : "Votre article '[titre]' a changé de statut : [nouveau statut]"
   - Lien vers l'article

2. **Demande de révision**
   - Quand un article nécessite des révisions
   - Message : "Des révisions sont requises pour votre article '[titre]'"
   - Lien vers l'article avec les commentaires

3. **Article accepté**
   - Quand un article est accepté
   - Message : "Votre article '[titre]' a été accepté pour publication"
   - Lien vers l'article

4. **Article rejeté**
   - Quand un article est rejeté
   - Message : "Votre article '[titre]' a été rejeté"
   - Lien vers l'article

5. **Article publié**
   - Quand un article est publié
   - Message : "Votre article '[titre]' a été publié dans le numéro [numéro]"
   - Lien vers l'article

6. **Nouvelle évaluation assignée**
   - Pour les évaluateurs
   - Message : "Un nouvel article vous a été assigné pour évaluation"
   - Lien vers la page d'évaluation

7. **Article resoumis**
   - Pour les évaluateurs
   - Message : "L'article '[titre]' a été modifié et resoumis"
   - Lien vers la page d'évaluation

### Fonctionnalités

- **Badge de notification** : Compteur de notifications non lues
- **Marquer comme lu** : Individuel ou en masse
- **Lien direct** : Vers l'article ou la page concernée
- **Historique** : Toutes les notifications (lues et non lues)

---

## Gestion des volumes et numéros

### Structure hiérarchique

```
Revue
└── Volume (ex: Volume 28, Année 2025)
    ├── Numéro 1 (ex: Janvier-Mars 2025)
    │   ├── Article 1
    │   ├── Article 2
    │   └── Article 3
    ├── Numéro 2 (ex: Avril-Juin 2025)
    │   ├── Article 4
    │   └── Article 5
    └── Numéro 3 (ex: Juillet-Septembre 2025)
        └── ...
```

### Workflow de publication

1. **Créer un volume**
   - Année, Numéro, Description
   - Exemple : Volume 28, Année 2025

2. **Créer des numéros dans le volume**
   - Titre, Description, Date de publication
   - Exemple : Numéro 1 - Janvier-Mars 2025

3. **Assigner des articles acceptés aux numéros**
   - Choisir un article avec statut "accepte"
   - Assigner à un numéro spécifique
   - Renseigner les pages (ex: "15-42")
   - Optionnel : Ajouter un DOI

4. **Uploader le PDF du numéro complet**
   - PDF contenant tous les articles du numéro
   - Format final de publication

5. **Publier le numéro**
   - Changer le statut en "publié"
   - Le numéro devient visible publiquement
   - Les articles deviennent accessibles

### Interface publique

- **Archives** : Liste de tous les volumes et numéros
- **Volume** : Liste des numéros d'un volume
- **Numéro** : Liste des articles d'un numéro + PDF téléchargeable
- **Article** : Page dédiée avec métadonnées complètes

---

## Interface publique

### Pages publiques

1. **Page d'accueil** (`/`)
   - Présentation de la revue
   - Derniers articles publiés
   - Informations importantes

2. **Publications** (`/publications`)
   - Liste de tous les articles publiés
   - Filtres par catégorie, année, auteur
   - Recherche

3. **Détails d'un article** (`/article/[id]`)
   - Informations complètes
   - Métadonnées (auteur, catégorie, pages, DOI)
   - Téléchargement du PDF
   - Articles similaires

4. **Archives** (`/archives`)
   - Liste des volumes et numéros
   - Navigation par année
   - Accès aux PDFs complets

5. **Volume** (`/volume/[year]`)
   - Liste des numéros d'un volume
   - Articles par numéro

6. **Numéro** (`/numero/[id]`)
   - Liste des articles du numéro
   - Téléchargement du PDF complet
   - Informations du numéro

7. **Recherche** (`/search`)
   - Recherche par mots-clés
   - Filtres avancés
   - Résultats paginés

8. **Comité éditorial** (`/comite`)
   - Liste des membres du comité
   - Rôles et responsabilités

9. **Présentation** (`/presentation`)
   - Informations sur la revue
   - Politique éditoriale
   - Instructions aux auteurs

### Fonctionnalités publiques

- **Téléchargement de PDFs** : Articles individuels et numéros complets
- **Recherche** : Recherche dans les titres, résumés, auteurs
- **Filtres** : Par catégorie, année, auteur
- **Responsive** : Interface adaptée mobile/tablette/desktop

---

## Palette de couleurs (identité UPC)

Le site utilise une palette de couleurs alignée sur l’**identité visuelle de l’Université Protestante au Congo (UPC)** : bleu marine, rouge bordeaux et jaune/doré, complétés par des gris pour les textes et fonds.

### Couleurs principales (UPC)

| Nom | Variable CSS | Hex | Usage |
|-----|----------------|-----|--------|
| **Bleu UPC** | `--color-blue` | `#1a3365` | Couleur principale (titres, liens, boutons primaires, sidebar) |
| **Rouge UPC** | `--color-red` | `#b3001b` | Couleur d’accent (boutons d’action, alertes, suppression, accents) |
| **Jaune / Doré** | `--color-yellow` | `#ffbb00` | Accents (badges, alertes info, mise en avant) |
| **Blanc** | `--color-white` | `#ffffff` | Fonds, texte sur fond bleu/rouge |
| **Noir** | `--color-black` | `#000000` | Texte très contrasté si besoin |

### Couleurs de dégradé (dérivées)

| Usage | Couleur | Hex |
|--------|---------|-----|
| Bleu foncé (dégradés, sidebar) | Bleu très foncé | `#0f2847` |
| Bleu très foncé (boutons) | Bleu nuit | `#142850` |
| Rouge foncé (hover, dégradés) | Rouge bordeaux foncé | `#8b0015` |

Ces teintes sont utilisées dans les dégradés (ex. `linear-gradient(180deg, var(--color-blue) 0%, #0f2847 100%)` pour la sidebar, ou `linear-gradient(135deg, var(--color-red) 0%, #8b0015 100%)` pour les boutons rouges).

### Gris (texte et fonds)

| Nom | Variable CSS | Hex | Usage |
|-----|----------------|-----|--------|
| Gris 50 | `--color-gray-50` | `#f9fafb` | Fonds très clairs, zones secondaires |
| Gris 100 | `--color-gray-100` | `#f3f4f6` | Fonds de cartes, bordures légères |
| Gris 200 | `--color-gray-200` | `#e5e7eb` | Bordures, séparateurs |
| Gris 300 | `--color-gray-300` | `#d1d5db` | Bordures plus marquées |
| Gris 400 | `--color-gray-400` | *(utilisé dans certains CSS)* | Texte ou icônes atténués |
| Gris 500 | `--color-gray-500` | *(utilisé dans certains CSS)* | Texte secondaire |
| Gris 600 | `--color-gray-600` | `#4b5563` | Texte secondaire, labels |
| Gris 700 | `--color-gray-700` | `#374151` | Texte courant |
| Gris 800 | `--color-gray-800` | *(utilisé dans certains CSS)* | Texte foncé |
| Gris 900 | `--color-gray-900` | `#111827` | Texte principal, titres |

### Couleurs sémantiques (statuts, toasts)

| Contexte | Couleur | Hex |
|----------|---------|-----|
| Succès (validé, publié, toast succès) | Vert | `#22c55e` / `#10b981` / `#059669` |
| Erreur / rejet / danger | Rouge | `#ef4444` / `#dc2626` ou `var(--color-red)` |
| Info / en attente | Bleu | `#2563eb` |
| Avertissement | Orange / ambre | `#f59e0b` / `#d97706` |
| Publication / accent violet | Violet | `#7c3aed` |

### Où sont définies les couleurs

- **Variables globales** : `public/css/styles.css` (`:root`) — bleu, rouge, jaune, blanc, noir, gris 50 à 900.
- **Dashboard / admin** : `public/css/dashboard-styles.css` — réutilise ces variables et ajoute dégradés et couleurs sémantiques.
- **Pages spécifiques** :  
  - `public/css/numeros-styles.css` : réutilise `--color-blue`, `--color-red`, `--color-white`, gris.  
  - `public/css/comite-styles.css` : variables locales `--comite-primary: #1e3a5f`, `--comite-accent: #b3001b` (alignées sur le bleu et le rouge UPC).

### Récapitulatif pour la charte graphique

- **Primaire** : Bleu `#1a3365` (bleu UPC).  
- **Secondaire / accent** : Rouge `#b3001b` (rouge UPC).  
- **Tertiaire / accent** : Jaune `#ffbb00`.  
- **Neutres** : Blanc, noir, échelle de gris (#f9fafb → #111827).  
- **Dégradés** : Bleu → `#0f2847` ; Rouge → `#8b0015`.

En respectant cette palette, le site reflète les couleurs de l’UPC sur l’ensemble des écrans (public, auteur, évaluateur, admin).

---

## Sécurité

### Authentification

- **Sessions PHP** : Gestion des sessions serveur
- **Hash des mots de passe** : `password_hash()` avec bcrypt
- **Protection CSRF** : À implémenter pour les formulaires sensibles
- **Validation des entrées** : Côté client et serveur

### Autorisations

- **Vérification des rôles** : À chaque accès à une route protégée
- **Permissions par action** : Vérification avant chaque action
- **Protection des fichiers** : Accès restreint aux uploads

### Données sensibles

- **Mots de passe** : Jamais stockés en clair
- **Informations de paiement** : Stockage sécurisé (à améliorer avec chiffrement)
- **Fichiers uploadés** : Validation des types et tailles

---

## Améliorations futures

### Fonctionnalités à ajouter

1. **Système de commentaires** : Commentaires sur les articles publiés
2. **Export de données** : Export CSV/Excel des statistiques
3. **API REST complète** : Pour intégrations externes
4. **Notifications email** : Envoi d'emails pour les notifications importantes
5. **Gestion de versions** : Historique des versions d'un article
6. **Plagiat** : Intégration d'un outil de détection de plagiat
7. **Statistiques avancées** : Graphiques et analyses détaillées
8. **Multi-langue** : Support de plusieurs langues
9. **Thèmes** : Personnalisation de l'interface
10. **Mobile app** : Application mobile native

### Optimisations techniques

1. **Cache** : Mise en cache des requêtes fréquentes
2. **CDN** : Pour les fichiers statiques et PDFs
3. **Optimisation des images** : Compression et formats modernes
4. **Lazy loading** : Chargement différé des images
5. **Pagination améliorée** : Pagination côté serveur optimisée

---

## Support et maintenance

### Logs

- **Erreurs PHP** : Logs dans `error_log`
- **Erreurs applicatives** : `error_log()` dans le code
- **Traces** : Stack traces pour le débogage

### Sauvegardes

- **Base de données** : Sauvegardes régulières recommandées
- **Fichiers uploadés** : Sauvegarde des PDFs et documents
- **Configuration** : Sauvegarde des paramètres

### Maintenance

- **Mises à jour** : Mise à jour régulière de PHP et dépendances
- **Sécurité** : Mise à jour des correctifs de sécurité
- **Performance** : Monitoring et optimisation continue

---

## Conclusion

Cette documentation couvre l'ensemble des fonctionnalités du site web de la Revue de Théologie UPC. Pour toute question ou amélioration, consulter le code source ou contacter l'équipe de développement.

**Version du document** : 1.0  
**Dernière mise à jour** : Janvier 2026
