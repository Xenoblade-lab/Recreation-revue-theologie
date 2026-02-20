# Plan d'Implémentation : Gestion Complète Revue → Volumes → Numéros → Articles → Archives

## 📊 État Actuel

### Structure existante :
- ✅ Table `articles` : Articles individuels (soumis, en évaluation, publiés)
- ✅ Table `revues` : Représente les numéros/volumes (mais pas de distinction claire)
- ✅ Table `revue_article` : Liaison many-to-many entre revues et articles
- ✅ Page `admin/volumes.php` : Liste des revues (statique)
- ✅ Page `archives.php` : Existe mais avec contenu statique

### Ce qui manque :
- ❌ Pas de distinction claire entre **Volume** (année) et **Numéro** (édition)
- ❌ Pas de table pour l'**identité de la revue** (nom, comité, objectifs, ligne éditoriale)
- ❌ Les articles ne sont pas directement liés à un numéro (`issue_id` manquant)
- ❌ Pas de page admin pour gérer l'identité de la revue
- ❌ Archives non dynamiques (contenu statique)

---

## 🎯 Objectif Final

Créer une hiérarchie claire :
```
Revue (identité globale)
  └── Volumes (par année : 2025, 2024, etc.)
      └── Numéros (Volume 28, Numéro 1, etc.)
          └── Articles (publiés dans ce numéro)
```

---

## 📋 Plan d'Implémentation

### **ÉTAPE 1 : Structure de la Base de Données**

#### 1.1 Créer la table `revue_info` (identité de la revue)
```sql
CREATE TABLE `revue_info` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `nom_officiel` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `ligne_editoriale` TEXT,
  `objectifs` TEXT,
  `domaines_couverts` TEXT,
  `issn` VARCHAR(50),
  `comite_scientifique` TEXT, -- JSON ou texte formaté
  `comite_redaction` TEXT,
  `created_at` TIMESTAMP,
  `updated_at` TIMESTAMP
);
```

#### 1.2 Créer la table `volumes` (regroupement par année)
```sql
CREATE TABLE `volumes` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `annee` INT NOT NULL UNIQUE,
  `numero_volume` VARCHAR(50), -- Ex: "Volume 28"
  `description` TEXT,
  `created_at` TIMESTAMP,
  `updated_at` TIMESTAMP
);
```

#### 1.3 Modifier la table `revues` pour en faire des `issues` (numéros)
- Option A : Renommer `revues` → `issues` et ajouter `volume_id`
- Option B : Garder `revues` mais ajouter `volume_id` et `type` ('issue')

**Recommandation : Option B** (moins de migration)
```sql
ALTER TABLE `revues` 
  ADD COLUMN `volume_id` INT NULL,
  ADD COLUMN `type` ENUM('issue', 'special') DEFAULT 'issue',
  ADD INDEX `idx_volume_id` (`volume_id`);
```

#### 1.4 Ajouter `issue_id` dans `articles` (lien direct)
```sql
ALTER TABLE `articles` 
  ADD COLUMN `issue_id` INT NULL,
  ADD INDEX `idx_issue_id` (`issue_id`);
```

**Note** : Un article peut être dans `revue_article` (ancien système) ET avoir `issue_id` (nouveau système) pour compatibilité.

---

### **ÉTAPE 2 : Modèles PHP**

#### 2.1 Créer `models/RevueInfoModel.php`
- `getRevueInfo()` : Récupérer l'identité de la revue
- `updateRevueInfo($data)` : Mettre à jour l'identité

#### 2.2 Créer `models/VolumeModel.php` (améliorer l'existant)
- `createVolume($annee, $data)` : Créer un volume pour une année
- `getVolumeByYear($annee)` : Récupérer un volume par année
- `getAllVolumes()` : Liste de tous les volumes
- `getVolumeIssues($volumeId)` : Récupérer les numéros d'un volume

#### 2.3 Améliorer `models/IssueModel.php`
- Utiliser `revues` comme table de base
- `assignArticleToIssue($articleId, $issueId)` : Assigner un article à un numéro
- `getIssueArticles($issueId)` : Récupérer les articles d'un numéro

#### 2.4 Améliorer `models/ArticleModel.php`
- `assignToIssue($articleId, $issueId)` : Assigner un article à un numéro
- `getArticlesByIssue($issueId)` : Récupérer les articles d'un numéro

---

### **ÉTAPE 3 : Contrôleurs**

#### 3.1 Créer `controllers/RevueController.php` (public)
- `index()` : Page d'accueil avec identité de la revue
- `archives()` : Archives dynamiques (par année/volume/numéro)
- `volume($year)` : Détails d'un volume
- `issue($id)` : Détails d'un numéro avec articles

#### 3.2 Améliorer `controllers/AdminController.php`
- `revueSettings()` : Gérer l'identité de la revue (nom, comité, objectifs)
- `volumes()` : Gérer les volumes (déjà existe, améliorer)
- `createVolume()` : Créer un nouveau volume
- `createIssue()` : Créer un nouveau numéro dans un volume
- `assignArticleToIssue()` : Assigner un article publié à un numéro

---

### **ÉTAPE 4 : Vues (Frontend Public)**

#### 4.1 Créer `views/revue-info.php` (page "À propos de la revue")
- Afficher : nom, description, ligne éditoriale, objectifs, comités

#### 4.2 Améliorer `views/archives.php` (rendre dynamique)
- Charger les volumes/numéros depuis la BDD
- Filtrer par année
- Afficher les numéros avec leurs articles
- Pagination

#### 4.3 Créer `views/volume-details.php`
- Détails d'un volume (année)
- Liste des numéros de ce volume
- Statistiques (nombre d'articles, pages, etc.)

#### 4.4 Créer `views/issue-details.php`
- Détails d'un numéro
- Liste des articles publiés dans ce numéro
- Téléchargement du PDF du numéro complet

---

### **ÉTAPE 5 : Vues Admin**

#### 5.1 Créer `views/admin/revue-settings.php`
- Formulaire pour éditer l'identité de la revue
- Sections : Informations générales, Comités, Objectifs, Domaines

#### 5.2 Améliorer `views/admin/volumes.php`
- Afficher les volumes avec leurs numéros
- Boutons : Créer volume, Créer numéro, Assigner articles

#### 5.3 Créer `views/admin/volume-create.php`
- Formulaire pour créer un volume

#### 5.4 Créer `views/admin/issue-create.php`
- Formulaire pour créer un numéro dans un volume

#### 5.5 Améliorer `views/admin/articles.php`
- Ajouter une colonne "Numéro assigné"
- Bouton "Assigner à un numéro" pour les articles publiés

---

### **ÉTAPE 6 : Routes**

#### 6.1 Routes publiques (`routes/web.php`)
```php
// Revue
Router::get('', 'RevueController@index');
Router::get('revue', 'RevueController@index');
Router::get('archives', 'RevueController@archives');
Router::get('volume/:year', 'RevueController@volume');
Router::get('numero/:id', 'RevueController@issue');

// Admin
Router::get('admin/revue/settings', 'AdminController@revueSettings');
Router::post('admin/revue/settings', 'AdminController@updateRevueSettings');
Router::post('admin/volumes/create', 'AdminController@createVolume');
Router::post('admin/issues/create', 'AdminController@createIssue');
Router::post('admin/articles/:id/assign-issue', 'AdminController@assignArticleToIssue');
```

---

### **ÉTAPE 7 : Migration des Données Existantes**

#### 7.1 Script de migration
- Créer des volumes pour chaque année présente dans `revues.date_publication`
- Lier les `revues` existantes aux volumes correspondants
- Optionnel : Migrer les articles de `revue_article` vers `articles.issue_id`

---

## 🎨 Interface Utilisateur

### **Page Publique : Archives**
```
[Année 2025] [2024] [2023] [2022] ...

┌─────────────────────────────────┐
│ Volume 28 (2025)                │
│ ┌─────────────────────────────┐ │
│ │ Numéro 1 - Janvier 2025     │ │
│ │ 12 articles | 250 pages    │ │
│ │ [Voir] [PDF]                │ │
│ └─────────────────────────────┘ │
│ ┌─────────────────────────────┐ │
│ │ Numéro 2 - Juin 2025        │ │
│ │ 10 articles | 220 pages    │ │
│ │ [Voir] [PDF]                │ │
│ └─────────────────────────────┘ │
└─────────────────────────────────┘
```

### **Page Admin : Gestion Revue**
```
┌─────────────────────────────────┐
│ Identité de la Revue            │
│ - Nom officiel                  │
│ - Description                   │
│ - Ligne éditoriale              │
│ - Objectifs                     │
│ - Comités (scientifique, rédac) │
└─────────────────────────────────┘

┌─────────────────────────────────┐
│ Volumes                         │
│ [Créer Volume]                  │
│ - Volume 28 (2025) [Gérer]      │
│ - Volume 27 (2024) [Gérer]     │
└─────────────────────────────────┘
```

---

## ✅ Checklist d'Implémentation

- [ ] **Phase 1 : Base de données**
  - [ ] Créer `revue_info`
  - [ ] Créer `volumes`
  - [ ] Modifier `revues` (ajouter `volume_id`)
  - [ ] Modifier `articles` (ajouter `issue_id`)

- [ ] **Phase 2 : Modèles**
  - [ ] `RevueInfoModel`
  - [ ] Améliorer `VolumeModel`
  - [ ] Améliorer `IssueModel`
  - [ ] Améliorer `ArticleModel`

- [ ] **Phase 3 : Contrôleurs**
  - [ ] `RevueController` (public)
  - [ ] Améliorer `AdminController`

- [ ] **Phase 4 : Vues publiques**
  - [ ] `revue-info.php`
  - [ ] `archives.php` (dynamique)
  - [ ] `volume-details.php`
  - [ ] `issue-details.php`

- [ ] **Phase 5 : Vues admin**
  - [ ] `admin/revue-settings.php`
  - [ ] Améliorer `admin/volumes.php`
  - [ ] `admin/volume-create.php`
  - [ ] `admin/issue-create.php`

- [ ] **Phase 6 : Routes**
  - [ ] Routes publiques
  - [ ] Routes admin

- [ ] **Phase 7 : Migration**
  - [ ] Script de migration des données

---

## 🚀 Ordre d'Implémentation Recommandé

1. **Créer les tables** (ÉTAPE 1)
2. **Créer les modèles de base** (ÉTAPE 2)
3. **Page admin : Gérer l'identité de la revue** (ÉTAPE 5.1)
4. **Page admin : Créer volumes et numéros** (ÉTAPE 5.2-5.4)
5. **Assigner articles aux numéros** (ÉTAPE 5.5)
6. **Page publique : Archives dynamiques** (ÉTAPE 4.2)
7. **Pages publiques : Détails volume/numéro** (ÉTAPE 4.3-4.4)

---

## 💡 Notes Importantes

1. **Compatibilité** : Garder `revue_article` pour compatibilité avec l'ancien système
2. **Migration progressive** : Les articles peuvent avoir `issue_id` OU être dans `revue_article`
3. **Flexibilité** : Un article peut être assigné à un numéro APRÈS publication
4. **Archives** : Les numéros non assignés à un volume apparaîtront dans "Non classés"

---

## ❓ Questions à Résoudre

1. **Un volume = une année ?** Oui, recommandé pour simplicité
2. **Plusieurs numéros par année ?** Oui (Numéro 1, Numéro 2, etc.)
3. **Articles non assignés ?** Oui, ils restent dans "Articles publiés" sans numéro
4. **Supprimer `revue_article` ?** Non, garder pour compatibilité

---

**Prêt à commencer l'implémentation ?** 🚀

