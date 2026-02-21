# Plan de refactorisation — Revue de Théologie UPC

## Contexte

Refactorisation du design et du site **dans le dossier `revue-theologie-upc-html/`**. Les fonctionnalités déjà présentes côté ancien site (PHP MVC à la racine) seront adaptées au nouveau design et rendues dynamiques. La base de données de référence est `revue-theologie-upc-html/frontend/revue_theologie_2.sql`.

---

## 1. Objectifs

- **Design** : Nouveau design actuel dans `revue-theologie-upc-html/frontend/` (HTML/CSS/JS).
- **Dynamisation** : Remplacer les contenus statiques par des données issues de la base `revue`.
- **Architecture** : Structure MVC (Modèles, Vues, Contrôleurs) conforme à DOCUMENTATION_COMPLETE.md.
- **Fonctionnalités** : Reprendre et adapter toutes les fonctionnalités de l’ancien site (auth, articles, évaluations, abonnements, etc.).

---

## 2. Structure cible du projet (`revue-theologie-upc-html/`)

```
revue-theologie-upc-html/
├── config/                 # Configuration
│   └── config.php
├── controllers/            # Logique métier
│   ├── AdminController.php
│   ├── AuthorController.php
│   ├── ReviewerController.php
│   ├── RevueController.php
│   ├── ArticleController.php
│   ├── UserController.php
│   └── ...
├── models/                 # Accès base de données
│   ├── ArticleModel.php
│   ├── UserModel.php
│   ├── ReviewModel.php
│   ├── RevueModel.php
│   ├── VolumeModel.php
│   ├── AbonnementModel.php
│   ├── PaiementModel.php
│   ├── NotificationModel.php
│   └── ...
├── views/                  # Templates PHP (design du frontend actuel)
│   ├── layouts/            # En-tête, pied de page, sidebar
│   │   ├── header.php
│   │   ├── footer.php
│   │   ├── _header.html    # Base du design actuel
│   │   └── mobile.php
│   ├── public/             # Pages publiques
│   │   ├── index.php       # Accueil (design index.html)
│   │   ├── publications.php
│   │   ├── archives.php
│   │   ├── article-details.php
│   │   ├── presentation.php
│   │   ├── comite.php
│   │   ├── contact.php
│   │   ├── faq.php
│   │   ├── politique-editoriale.php
│   │   ├── instructions-auteurs.php
│   │   ├── actualites.php
│   │   └── mentions-legales.php
│   ├── auth/               # Auth
│   │   ├── login.php
│   │   ├── register.php
│   │   └── forgot-password.php
│   ├── author/             # Espace auteur
│   │   ├── index.php
│   │   ├── abonnement.php
│   │   ├── notifications.php
│   │   └── ...
│   ├── reviewer/           # Espace évaluateur
│   │   ├── index.php
│   │   ├── evaluation.php
│   │   ├── historique.php
│   │   └── terminees.php
│   ├── admin/              # Administration
│   │   └── index.php
│   └── numero/             # Pages numéros (dynamiques)
│       └── details.php
├── public/                 # Point d'entrée web
│   ├── index.php           # Front controller
│   ├── .htaccess
│   ├── css/
│   │   └── styles.css      # Depuis frontend/css/styles.css
│   ├── js/
│   │   └── main.js         # Depuis frontend/js/main.js
│   ├── images/
│   ├── uploads/
│   └── assets/
├── router/                 # Système de routage
│   └── Router.php
├── routes/
│   ├── web.php
│   └── api.php
├── service/                # Services métier
│   ├── AuthService.php
│   └── Abonnement.php
├── includes/               # Helpers, config
│   ├── db.php
│   └── auth.php
├── frontend/               # Maquettes HTML de référence (conservées)
│   ├── index.html
│   ├── css/styles.css
│   ├── js/main.js
│   └── revue_theologie_2.sql
└── migrations/             # Scripts SQL si ajustements
```

---

## 3. Base de données

**Fichier** : `revue-theologie-upc-html/frontend/revue_theologie_2.sql`  
**Base** : `revue`

### Tables principales utilisées

| Table | Rôle |
|-------|------|
| `users` | Utilisateurs (admin, auteur, redacteur, etc.) |
| `articles` | Articles soumis (avec statut, auteur, issue_id) |
| `revues` | Numéros / numéros de la revue |
| `volumes` | Volumes annuels |
| `evaluations` | Évaluations par les reviewers |
| `abonnements` | Abonnements auteurs |
| `paiements` | Paiements |
| `notifications` | Notifications utilisateurs |
| `revue_parts` | Contenu des numéros (articles, éditoriaux) |
| `revue_article` | Liaison revue ↔ article |
| `revue_info` | Infos générales de la revue |

### Points d’attention

- La table `articles` utilise `issue_id` pour lier aux numéros.
- Les statuts d’articles dans le dump : `soumis`, `valide`, `rejete`. À harmoniser avec le workflow documenté (soumis, en_evaluation, revision_requise, accepte, rejete, publie) si besoin.
- Le rôle `redacteur` correspond au reviewer dans la doc.

---

## 4. Plan de travail par phases

### Phase 1 — Infrastructure (Fondations)

1. Créer la structure MVC dans `revue-theologie-upc-html/`.
2. Configurer `config/config.php` (DB, base path, etc.).
3. Créer le point d’entrée `public/index.php` et le routeur.
4. Copier et adapter les assets du frontend actuel (CSS, JS, images) vers `public/`.
5. Créer les vues de layout (header, footer) à partir de `_header.html` et du design existant.
6. Configurer la connexion à la base `revue`.

### Phase 2 — Pages publiques (Design + données)

| Priorité | Page | Source HTML | Contenu dynamique |
|----------|------|-------------|-------------------|
| 1 | Accueil | index.html | Derniers articles, derniers numéros |
| 2 | Publications | publications.html | Liste articles depuis `articles` + `revue_article` |
| 3 | Archives | archives.html | Volumes + revues |
| 4 | Détail article | article/X.html | Un article par ID |
| 5 | Détail numéro | numero/X.html | Un numéro (revue) + revue_parts |
| 6 | Présentation | presentation.html | Données `revue_info` |
| 7 | Comité | comite.html | Données éventuelles (comité, revue_info) |
| 8 | Contact, FAQ, Politique éditoriale, Instructions | .html correspondants | Contenu éditable ou statique |
| 9 | Actualités, mentions légales | .html correspondants | Idem |

### Phase 3 — Authentification et autorisation

1. Créer `AuthService.php` et `includes/auth.php`.
2. Pages : login, register, forgot-password (design existant).
3. Sessions et vérification des rôles (admin, auteur, redacteur).
4. Redirections selon le rôle après connexion.

### Phase 4 — Espace auteur

1. Dashboard auteur (design author/index.html).
2. Page abonnement (author/abonnement.html).
3. Soumission d’article (soumettre.html) → liaison avec `articles`.
4. Liste et détail des articles de l’auteur.
5. Notifications (author/notifications.html).

### Phase 5 — Espace évaluateur (reviewer)

1. Dashboard reviewer (design reviewer/index.html).
2. Liste des articles assignés.
3. Page d’évaluation (reviewer/evaluation.html) → formulaire vers `evaluations`.
4. Historique et évaluations terminées.

### Phase 6 — Espace administration

1. Dashboard admin (design admin/index.html).
2. Gestion utilisateurs.
3. Gestion articles (assignation, statuts, publication).
4. Gestion volumes et numéros.
5. Gestion paiements et abonnements.
6. Paramètres de la revue (`revue_info`).

### Phase 7 — Intégration et finition

1. Recherche (si prévue).
2. Export / téléchargement PDF.
3. Système de notifications (affichage, marquage lu).
4. Tests, correction des bugs, responsive.
5. SEO, meta tags, accessibilité.

---

## 5. Correspondance design → dynamisation

| Fichier actuel (frontend) | Vue PHP cible | Données |
|---------------------------|---------------|---------|
| index.html | views/public/index.php | Articles récents, numéros récents |
| publications.html | views/public/publications.php | `articles` JOIN users, revues |
| archives.html | views/public/archives.php | `volumes`, `revues` |
| article/1.html…8.html | views/public/article-details.php?id=X | `articles`, auteur, revue |
| numero/1.html…5.html | views/public/numero-details.php?id=X | `revues`, `revue_parts`, `revue_article` |
| presentation.html | views/public/presentation.php | `revue_info` |
| comite.html | views/public/comite.php | `revue_info`, comité |
| login.html | views/auth/login.php | Formulaire → AuthService |
| register.html | views/auth/register.php | Formulaire → users |
| soumettre.html | views/author/soumettre.php | Formulaire → articles |
| author/index.html | views/author/index.php | Articles de l’auteur |
| author/abonnement.html | views/author/abonnement.php | Abonnements, paiements |
| reviewer/index.html | views/reviewer/index.php | Évaluations assignées |
| reviewer/evaluation.html | views/reviewer/evaluation.php | Formulaire évaluation |
| admin/index.html | views/admin/index.php | Stats, derniers articles, etc. |

---

## 6. Références

- **DOCUMENTATION_COMPLETE.md** : Architecture, rôles, workflow, routes, palette couleurs.
- **documentation.txt** : Objectifs, contenus, recommandations design, workflow éditorial.
- **Ancien site (racine)** : contrôleurs, modèles, vues, routes, services à transposer.
- **Palette UPC** : Bleu #1a3365, Rouge #b3001b, Jaune #ffbb00, gris, etc.

---

## 7. Ordre recommandé des tâches

1. Phase 1 — Infrastructure.
2. Phase 2 — Accueil, publications, archives, détail article et numéro.
3. Phase 3 — Auth (login, register).
4. Phase 4 — Espace auteur (soumission, abonnement).
5. Phase 5 — Espace reviewer.
6. Phase 6 — Administration.
7. Phase 7 — Intégration et finition.

Chaque phase peut être découpée en petites tâches concrètes selon le besoin.
