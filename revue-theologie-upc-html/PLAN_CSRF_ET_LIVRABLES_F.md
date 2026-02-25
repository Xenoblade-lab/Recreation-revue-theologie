# Plan CSRF, tests en local et livrables F (devis)

Ce document décrit : (1) le plan d’ajout des jetons CSRF sur les formulaires sensibles, (2) la checklist des tests en local, (3) la proposition de libellé pour la section F du devis (tests, formation, hébergement).

---

## 1. Plan d’ajout des jetons CSRF

### 1.1 Objectif

Protéger tous les formulaires qui envoient des données en POST contre les attaques CSRF (Cross-Site Request Forgery), conformément à la prestation « Sécurité CSRF » du devis.

### 1.2 Principe

- À l’affichage d’un formulaire : générer un jeton unique, le stocker en session et l’inclure dans un champ caché.
- À la réception du POST : vérifier que le jeton envoyé existe et correspond à celui en session ; sinon rejeter la requête (403 ou redirection).

### 1.3 Implémentation technique

**Étape 1 — Helper CSRF (à ajouter dans `includes/auth.php` ou nouveau fichier `includes/csrf.php`)**

- `csrf_token()` : si aucun jeton en session, en générer un (`bin2hex(random_bytes(32))`), le stocker en `$_SESSION['csrf_token']`, le retourner. Sinon retourner le jeton existant.
- `csrf_field()` : retourne le HTML du champ caché : `<input type="hidden" name="csrf_token" value="...">`.
- `validate_csrf()` : retourne `true` si `$_POST['csrf_token']` est présent et égal à `$_SESSION['csrf_token']`, sinon `false`. Optionnel : régénérer le jeton après validation (perte d’usage unique).

**Étape 2 — Liste des formulaires à protéger**

| Formulaire | Vue | Action (contrôleur) |
|------------|-----|---------------------|
| Connexion | `views/auth/login.php` | `AuthController::login` |
| Inscription | `views/auth/register.php` | `AuthController::register` |
| Mot de passe oublié | `views/auth/forgot-password.php` | `AuthController::forgotPassword` |
| Soumission d’article | `views/author/soumettre.php` | `AuthorController::soumettre` |
| Édition d’article | `views/author/article-edit.php` | `AuthorController::articleUpdate` |
| Notifications : tout marquer lu | `views/author/notifications.php` | `AuthorController::notificationsMarkAllRead` |
| Notifications : marquer une lue | `views/author/notifications.php` (boucle) | `AuthorController::notificationMarkRead` |
| Évaluation (brouillon / soumettre) | `views/reviewer/evaluation.php` | `ReviewerController::evaluationPost` |
| Notifications évaluateur | `views/reviewer/notifications.php` | idem (read-all, read one) |
| Création utilisateur | `views/admin/user-form.php` (create) | `AdminController::userStore` |
| Édition utilisateur | `views/admin/user-form.php` (edit) | `AdminController::userUpdate` |
| Changement statut article | `views/admin/article-detail.php` | `AdminController::articleUpdateStatut` |
| Affectation évaluateur | `views/admin/article-detail.php` | `AdminController::articleAssign` |
| Assignation au numéro | `views/admin/article-detail.php` | `AdminController::articleSetIssue` |
| Statut paiement | `views/admin/paiements.php` (Valider / Refuser) | `AdminController::paiementStatut` |
| Paramètres revue | `views/admin/parametres.php` | `AdminController::parametresUpdate` |

**Étape 3 — Modifications par zone**

- **Auth**  
  - Dans `showLogin`, `showRegister`, `showForgotPassword` : s’assurer que la vue reçoit le jeton (ou appeler `csrf_token()` dans la vue).  
  - Dans les vues : ajouter `<?= csrf_field()` ?>` (ou équivalent) à l’intérieur de chaque `<form>`.  
  - Dans `login()`, `register()`, `forgotPassword()` : en tout début de traitement POST, si `!validate_csrf()` alors redirection (ex. vers la même page avec message d’erreur) ou 403.

- **Author**  
  - Même chose : fournir le jeton aux vues (soumettre, article-edit, notifications), ajouter le champ dans chaque formulaire POST, et dans `soumettre`, `articleUpdate`, `notificationMarkRead`, `notificationsMarkAllRead` : vérifier le CSRF avant tout traitement.

- **Reviewer**  
  - Vue évaluation + vues notifications : champ CSRF.  
  - `evaluationPost`, `notificationMarkRead`, `notificationsMarkAllRead` : validation CSRF en début d’action.

- **Admin**  
  - Toutes les vues avec formulaire POST (user-form, article-detail, paiements, parametres) : ajouter le champ CSRF.  
  - Toutes les méthodes qui traitent ces POST : validation CSRF en premier (sinon 403 ou redirection avec message).

**Étape 4 — Inclure le helper**

- Si le code est dans `includes/csrf.php`, l’inclure après la session (ex. dans `public/index.php` après `auth.php` ou dans `auth.php` en bas). Les fonctions `csrf_token`, `csrf_field`, `validate_csrf` doivent être disponibles partout où on affiche un formulaire ou on traite un POST.

**Étape 5 — Ordre conseillé**

1. Créer `includes/csrf.php` avec les trois fonctions.  
2. Inclure ce fichier dans le bootstrap.  
3. Auth (login, register, forgot-password) : vues + contrôleur.  
4. Author (soumettre, article edit, notifications).  
5. Reviewer (evaluation, notifications).  
6. Admin (users, article-detail, paiements, parametres).  
7. Test manuel : soumettre chaque formulaire avec jeton valide, puis sans jeton ou jeton falsifié → doit être rejeté.

---

## 2. Checklist des tests en local

À exécuter en local (navigateur + base de données) pour valider les fonctionnalités importantes avant livraison. Cocher au fur et à mesure.

### 2.1 Authentification

- [ ] Connexion avec identifiants valides → accès au tableau de bord selon le rôle.
- [ ] Connexion avec identifiants invalides → message d’erreur, pas d’accès.
- [ ] Inscription d’un nouveau compte → compte créé, possibilité de se connecter.
- [ ] Déconnexion → redirection, plus d’accès aux zones protégées.
- [ ] Mot de passe oublié : envoi du formulaire (email valide) → pas d’erreur fatale ; email envoyé ou message affiché selon implémentation.

**Procédure pour 2.1 (à exécuter en local)**

1. **Connexion — identifiants valides**  
   - Aller sur `/login` (ex. `http://localhost/.../public/login`).  
   - Saisir un email et mot de passe d’un utilisateur existant en base (auteur, évaluateur ou admin).  
   - Soumettre le formulaire.  
   - **Attendu :** redirection vers le tableau de bord correspondant (`/author`, `/reviewer` ou `/admin`), pas de message « Requête invalide ».

2. **Connexion — identifiants invalides**  
   - Sur `/login`, saisir un email inexistant ou un mauvais mot de passe.  
   - Soumettre.  
   - **Attendu :** message d’erreur (ex. « Email ou mot de passe incorrect »), rester sur la page de connexion.

3. **Inscription**  
   - Aller sur `/register`.  
   - Remplir prénom, nom, email (non utilisé), mot de passe (≥ 8 caractères).  
   - Soumettre.  
   - **Attendu :** redirection (ex. accueil ou dashboard), pas d’erreur.  
   - Se déconnecter puis se reconnecter avec le nouvel email/mot de passe → **attendu :** connexion OK.

4. **Déconnexion**  
   - Une fois connecté, cliquer sur le lien de déconnexion (ou aller sur `/logout`).  
   - **Attendu :** redirection vers l’accueil.  
   - Tester l’accès direct à `/author`, `/reviewer` ou `/admin` → **attendu :** redirection vers `/login` ou accueil.

5. **Mot de passe oublié**  
   - Aller sur `/forgot-password`.  
   - Saisir un email valide (existant ou non en base).  
   - Soumettre.  
   - **Attendu :** message de succès ou confirmation (pas d’erreur fatale).  
   - (L’envoi d’email de réinitialisation peut être non implémenté ; le formulaire doit au moins répondre correctement.)

Une fois chaque point validé, cocher la case correspondante dans la liste 2.1 ci-dessus.

### 2.2 Espace public (sans être connecté)

- [ ] Accueil : affichage correct, liens principaux.
- [ ] Publications : liste des articles, liens vers détail.
- [ ] Détail d’un article : titre, auteurs, résumé, boutons Lire PDF / Télécharger si présents.
- [ ] Archives : volumes et numéros, liens « Voir le volume » / numéro.
- [ ] Détail d’un numéro : liste des articles, liens PDF si présents.
- [ ] Recherche : par mot-clé, par auteur → résultats cohérents.
- [ ] Pages : Présentation, Comité, Contact, FAQ, Politique éditoriale, Instructions aux auteurs, Actualités, Mentions légales, Conditions, Confidentialité.
- [ ] Changement de langue (FR / EN / Lingala) : texte et liens cohérents.

### 2.3 Espace auteur (connecté en tant qu’auteur)

- [ ] Tableau de bord : résumé, liens.
- [ ] Soumettre un article : formulaire (titre, contenu, fichier) → article créé et visible dans « Mes articles ».
- [ ] Mes articles : liste, accès au détail et à l’édition.
- [ ] Édition d’un article : modification et enregistrement.
- [ ] Abonnement : page accessible, historique / statut si applicable.
- [ ] Notifications : liste, marquer une comme lue, tout marquer comme lu.
- [ ] Téléchargement des modèles (Word, LaTeX) depuis Instructions aux auteurs.

### 2.4 Espace évaluateur (connecté en tant qu’évaluateur)

- [ ] Tableau de bord : évaluations à faire, terminées, historique.
- [ ] Ouvrir une évaluation : formulaire (recommandation, notes, commentaires).
- [ ] Sauvegarder brouillon puis soumettre l’évaluation → statut mis à jour.
- [ ] Notifications : liste, marquer comme lu.

### 2.5 Espace admin

- [ ] Utilisateurs : liste, création, édition (rôle, statut, mot de passe).
- [ ] Articles : liste, détail, changement de statut, affectation évaluateur, assignation au numéro.
- [ ] Paiements : liste, Valider / Refuser un paiement.
- [ ] Volumes / numéros : consultation.
- [ ] Paramètres revue : enregistrement des modifications.

### 2.6 Sécurité et ergonomie

- [ ] Accès direct par URL aux zones protégées sans être connecté → redirection vers login ou accueil.
- [ ] Accès avec un rôle inadapté (ex. auteur qui tape une URL admin) → 403 ou redirection.
- [ ] Après ajout CSRF : chaque formulaire sensible accepte uniquement les requêtes avec jeton valide.

---

## 3. Section F du devis — Proposition de libellé

Remplacer ou préciser la section **F. Tests, formation et déploiement** comme suit pour refléter : tests en local, formation, hébergement (coût hébergement).

### 3.1 Tableau des prestations F (à mettre dans le devis)

| Désignation | Prix (USD) |
|-------------|------------|
| Tests fonctionnels en local (vérification des fonctionnalités importantes : authentification, espaces auteur / évaluateur / admin, publication, recherche, formulaires) | 40 $ |
| Formation à l’utilisation (administration du site) | 44 $ |
| Hébergement (coût d’hébergement pour la mise en ligne du site, selon offre retenue) | 26 $ |
| **Sous-total F** | **110 $** |

### 3.2 Texte explicatif possible (à ajouter en note sous le tableau)

- **Tests** : tests effectués en local sur l’environnement de développement ; vérification des parcours principaux (connexion, soumission, évaluation, gestion des articles et des utilisateurs, pages publiques).
- **Hébergement** : le montant indiqué correspond au coût d’hébergement pour la mise en ligne du site (hébergeur, domaine si inclus, ou première période selon devis hébergeur). La configuration technique (déploiement sur le serveur) peut être incluse ou facturée séparément selon accord.

Ainsi, la section F reste à 110 $ au total, avec « Mise en ligne » remplacée par « Hébergement (coût hébergement) » et les tests définis comme tests en local des fonctionnalités importantes.

---

## 4. Résumé

| Livrable | Contenu |
|----------|--------|
| **CSRF** | Helpers + ajout du champ dans tous les formulaires POST listés + validation dans chaque action concernée. |
| **Tests** | Checklist ci-dessus à exécuter en local avant livraison. |
| **Devis F** | Remplacer « Mise en ligne » par « Hébergement (coût hébergement) » et préciser que les tests sont des tests fonctionnels en local. |
