# Plan de travail — Revue de Théologie UPC (étape par étape)

Référence : [Plan_refactorisation.md](Plan_refactorisation.md)

---

## Comment utiliser ce plan

- Cocher `[x]` quand une étape est terminée.
- Travailler dans l’ordre ; les étapes suivantes dépendent des précédentes.
- Toutes les créations de dossiers/fichiers se font **dans** `revue-theologie-upc-html/`.

---

## Phase 1 — Infrastructure

### 1.1 Structure des dossiers

- [x] **1.1.1** Créer `revue-theologie-upc-html/config/`
- [x] **1.1.2** Créer `revue-theologie-upc-html/controllers/`
- [x] **1.1.3** Créer `revue-theologie-upc-html/models/`
- [x] **1.1.4** Créer `revue-theologie-upc-html/views/` et sous-dossiers : `layouts/`, `public/`, `auth/`, `author/`, `reviewer/`, `admin/`, `numero/`
- [x] **1.1.5** Créer `revue-theologie-upc-html/public/` et sous-dossiers : `css/`, `js/`, `images/`, `uploads/`, `assets/`
- [x] **1.1.6** Créer `revue-theologie-upc-html/router/`
- [x] **1.1.7** Créer `revue-theologie-upc-html/routes/`
- [x] **1.1.8** Créer `revue-theologie-upc-html/service/`
- [x] **1.1.9** Créer `revue-theologie-upc-html/includes/`
- [x] **1.1.10** Créer `revue-theologie-upc-html/migrations/` (optionnel)

### 1.2 Configuration et base de données

- [x] **1.2.1** Créer `config/config.php` (constantes : BASE_PATH, DB_HOST, DB_NAME, DB_USER, DB_PASS, BASE_URL)
- [x] **1.2.2** Créer `includes/db.php` (connexion PDO ; base = celle où `revue_theologie_2.sql` est importé)
- [ ] **1.2.3** Vérifier que la base existe et que `revue_theologie_2.sql` est importé (à faire par vous)

### 1.3 Routeur et point d’entrée

- [x] **1.3.1** Créer `router/Router.php` (routeur simple sans Composer)
- [x] **1.3.2** Créer `routes/web.php` (déclaration des routes GET/POST)
- [x] **1.3.3** Créer `public/index.php` (front controller : bootstrap, config + db, Router, dispatch)
- [x] **1.3.4** Créer `public/.htaccess` (rewrite vers index.php)

### 1.4 Assets (design actuel)

- [x] **1.4.1** Copier `frontend/css/styles.css` → `public/css/styles.css`
- [x] **1.4.2** Copier `frontend/js/main.js` → `public/js/main.js`
- [x] **1.4.3** Copier le contenu de `frontend/images/` → `public/images/`
- [x] **1.4.4** Adapter dans les vues les chemins des assets (via `$base` dans les layouts)

### 1.5 Layouts (en-tête / pied de page)

- [x] **1.5.1** Créer `views/layouts/header.php` à partir de `frontend/_header.html` (structure + nav)
- [x] **1.5.2** Extraire le pied de page du frontend et créer `views/layouts/footer.php`
- [x] **1.5.3** Créer `views/layouts/main.php` qui inclut header + contenu variable + footer

---

## Phase 2 — Pages publiques

### 2.1 Accueil

- [x] **2.1.1** Créer la route `GET /` dans `routes/web.php`
- [x] **2.1.2** Créer une méthode dans un contrôleur (ex. `RevueController::index()` ou `HomeController::index()`)
- [x] **2.1.3** Créer `views/public/index.php` à partir de `frontend/index.html` ou `index_2.html`
- [x] **2.1.4** Dans le contrôleur : récupérer les derniers articles et/ou numéros (modèles)
- [x] **2.1.5** Passer les données à la vue et remplacer le contenu statique par des boucles PHP

### 2.2 Publications

- [x] **2.2.1** Créer la route `GET /publications`
- [x] **2.2.2** Créer la méthode contrôleur + `ArticleModel` (ou équivalent) pour lister les articles publiés/valides
- [x] **2.2.3** Créer `views/public/publications.php` à partir de `frontend/publications.html`
- [x] **2.2.4** Afficher la liste dynamique (titre, auteur, résumé, lien vers l’article)

### 2.3 Archives

- [x] **2.3.1** Créer la route `GET /archives`
- [x] **2.3.2** Créer `VolumeModel` et/ou modèle pour les revues (numéros)
- [x] **2.3.3** Créer `views/public/archives.php` à partir de `frontend/archives.html`
- [x] **2.3.4** Afficher volumes et numéros depuis la BDD

### 2.4 Détail d’un article

- [x] **2.4.1** Créer la route `GET /article/[id]`
- [x] **2.4.2** Créer la méthode contrôleur qui charge un article par ID (avec auteur)
- [x] **2.4.3** Créer `views/public/article-details.php` à partir d’un `frontend/article/X.html`
- [x] **2.4.4** Afficher titre, auteur, résumé, lien PDF, métadonnées

### 2.5 Détail d’un numéro

- [x] **2.5.1** Créer la route `GET /numero/[id]`
- [x] **2.5.2** Créer la méthode contrôleur (revue + revue_parts ou articles du numéro)
- [x] **2.5.3** Créer `views/public/numero-details.php` (ou `views/numero/details.php`) à partir de `frontend/numero/X.html`
- [x] **2.5.4** Afficher les infos du numéro et la liste des articles/parties

### 2.6 Pages de contenu (présentation, comité, etc.)

- [x] **2.6.1** Route + vue **Présentation** (`/presentation`) — données `revue_info` si besoin
- [x] **2.6.2** Route + vue **Comité** (`/comite`)
- [x] **2.6.3** Route + vue **Contact** (`/contact`)
- [x] **2.6.4** Route + vue **FAQ** (`/faq`)
- [x] **2.6.5** Route + vue **Politique éditoriale** (`/politique-editoriale`)
- [x] **2.6.6** Route + vue **Instructions aux auteurs** (`/instructions-auteurs`)
- [x] **2.6.7** Route + vue **Actualités** (`/actualites`)
- [x] **2.6.8** Route + vue **Mentions légales** (`/mentions-legales`)

---

## Phase 3 — Authentification

### 3.1 Service d’authentification

- [x] **3.1.1** Créer `service/AuthService.php` (login, logout, vérification session, rôle)
- [x] **3.1.2** Créer `includes/auth.php` (helper : est connecté ?, rôle, redirection si non connecté)

### 3.2 Pages login / register / mot de passe oublié

- [x] **3.2.1** Route `GET /login` et `POST /login` — créer `views/auth/login.php` depuis `frontend/login.html`
- [x] **3.2.2** Route `GET /register` et `POST /register` — créer `views/auth/register.php` depuis `frontend/register.html`
- [x] **3.2.3** Route `GET /logout` (déconnexion)
- [x] **3.2.4** Route `GET /forgot-password` (et POST si envoi email) — vue depuis `frontend/forgot-password.html`

### 3.3 Sécurité des zones

- [x] **3.3.1** Protéger les routes `/author/*` (réservé rôle auteur)
- [x] **3.3.2** Protéger les routes `/reviewer/*` (réservé rôle redacteur/reviewer)
- [x] **3.3.3** Protéger les routes `/admin/*` (réservé admin)
- [x] **3.3.4** Redirection après login selon le rôle (auteur → /author, admin → /admin, etc.)

---

## Phase 4 — Espace auteur

### 4.1 Dashboard auteur

- [x] **4.1.1** Route `GET /author` (avec middleware auth + rôle auteur)
- [x] **4.1.2** Créer `AuthorController` et méthode `index()`
- [x] **4.1.3** Créer `views/author/index.php` à partir de `frontend/author/index.html`
- [x] **4.1.4** Afficher les articles de l’auteur connecté (liste depuis BDD)

### 4.2 Abonnement

- [x] **4.2.1** Route `GET /author/abonnement` (et POST si formulaire)
- [x] **4.2.2** Créer `views/author/abonnement.php` depuis `frontend/author/abonnement.html`
- [x] **4.2.3** Lier au modèle abonnements / paiements (affichage statut, historique)

### 4.3 Soumission d’article

- [x] **4.3.1** Route `GET /author/soumettre` et `POST /author/soumettre`
- [x] **4.3.2** Créer `views/author/soumettre.php` depuis `frontend/soumettre.html`
- [x] **4.3.3** Formulaire : titre, contenu/résumé, fichier (PDF/Word) → enregistrement en BDD + upload
- [x] **4.3.4** Vérifier qu’un abonnement actif est requis (selon doc)

### 4.4 Gestion des articles (auteur)

- [x] **4.4.1** Route `GET /author/article/[id]` (détail d’un article de l’auteur)
- [x] **4.4.2** Route `GET /author/article/[id]/edit` et `POST` pour mise à jour (si statut = soumis)
- [x] **4.4.3** Vue détail + vue édition

### 4.5 Notifications

- [x] **4.5.1** Route `GET /author/notifications`
- [x] **4.5.2** Créer `views/author/notifications.php` depuis `frontend/author/notifications.html`
- [x] **4.5.3** Afficher les notifications de l’utilisateur (table `notifications` ou équivalent)

---

## Phase 5 — Espace évaluateur (reviewer)

### 5.1 Dashboard reviewer

- [x] **5.1.1** Route `GET /reviewer`
- [x] **5.1.2** Créer `ReviewerController::index()`
- [x] **5.1.3** Créer `views/reviewer/index.php` depuis `frontend/reviewer/index.html`
- [x] **5.1.4** Afficher les articles assignés à l’évaluateur (table `evaluations`)

### 5.2 Page d’évaluation

- [x] **5.2.1** Route `GET /reviewer/evaluation/[id]`
- [x] **5.2.2** Route `POST /reviewer/evaluation/[id]/save-draft` et `POST .../submit`
- [x] **5.2.3** Créer `views/reviewer/evaluation.php` depuis `frontend/reviewer/evaluation.html`
- [x] **5.2.4** Formulaire : recommandation, commentaires public/privé, notes → enregistrement en BDD

### 5.3 Historique et terminées

- [x] **5.3.1** Route `GET /reviewer/terminees` — vue depuis `frontend/reviewer/terminees.html`
- [x] **5.3.2** Route `GET /reviewer/historique` — vue depuis `frontend/reviewer/historique.html`
- [x] **5.3.3** Lister les évaluations terminées et l’historique depuis la BDD

---

## Phase 6 — Administration

### 6.1 Dashboard admin

- [ ] **6.1.1** Route `GET /admin`
- [ ] **6.1.2** Créer `AdminController::index()`
- [ ] **6.1.3** Créer `views/admin/index.php` depuis `frontend/admin/index.html`
- [ ] **6.1.4** Afficher statistiques (nombre articles, utilisateurs, paiements, etc.)

### 6.2 Gestion des utilisateurs

- [ ] **6.2.1** Routes : liste `/admin/users`, détail, créer, modifier, supprimer
- [ ] **6.2.2** Vues et formulaires (à partir de l’ancien site ou à créer)
- [ ] **6.2.3** Utiliser `UserModel`

### 6.3 Gestion des articles

- [ ] **6.3.1** Routes : liste `/admin/articles`, détail article, changer statut, assigner évaluateurs, publier
- [ ] **6.3.2** Vues et logique (assignation, publication dans un numéro)

### 6.4 Gestion des volumes et numéros

- [ ] **6.4.1** Routes : liste volumes, CRUD volume, liste numéros, CRUD numéro
- [ ] **6.4.2** Assignation d’articles à un numéro

### 6.5 Gestion des paiements

- [ ] **6.5.1** Route `GET /admin/paiements` (liste, détail, changer statut)

### 6.6 Paramètres de la revue

- [ ] **6.6.1** Route GET/POST pour éditer `revue_info` (nom, description, comité, etc.)

---

## Phase 7 — Intégration et finition

### 7.1 Recherche

- [x] **7.1.1** Route `GET /search` (paramètre `q`) + formulaire header et accueil
- [x] **7.1.2** Vue résultats (articles publiés + numéros) selon mots-clés

### 7.2 Téléchargements

- [x] **7.2.1** Route sécurisée pour télécharger un PDF d’article
- [x] **7.2.2** Lien « Télécharger PDF » sur pages article, publications et recherche

### 7.3 Notifications

- [x] **7.3.1** Création des notifications (changement statut article, assignation évaluateur)
- [x] **7.3.2** Marquage « lu » (POST author/reviewer notification/read et read-all)
- [x] **7.3.3** Badge compteur dans le header + lien notifications (auteur/reviewer)

### 7.4 Tests et corrections

- [ ] **7.4.1** Tester toutes les routes (public, auth, author, reviewer, admin)
- [ ] **7.4.2** Vérifier responsive (mobile / tablette)
- [ ] **7.4.3** Corriger les bugs et les liens cassés

### 7.5 SEO et accessibilité

- [x] **7.5.1** Meta title (contrôleurs) et description (layout main.php, `$metaDescription` optionnelle)
- [ ] **7.5.2** Vérifier contrastes et structure des titres (accessibilité)

---

## Récapitulatif des étapes (ordre global)

| # | Phase        | Première étape      | Dernière étape              |
|---|--------------|---------------------|-----------------------------|
| 1 | Infrastructure | 1.1.1 Structure dossiers | 1.5.3 Layout main           |
| 2 | Pages publiques | 2.1.1 Route accueil   | 2.6.8 Mentions légales     |
| 3 | Authentification | 3.1.1 AuthService   | 3.3.4 Redirection par rôle  |
| 4 | Espace auteur | 4.1.1 Route /author  | 4.5.3 Notifications         |
| 5 | Espace reviewer | 5.1.1 Route /reviewer | 5.3.3 Historique            |
| 6 | Administration | 6.1.1 Route /admin   | 6.6.1 Paramètres revue     |
| 7 | Finition      | 7.1.1 Recherche      | 7.5.2 Accessibilité         |

---

*Document à mettre à jour au fur et à mesure de l’avancement.*
