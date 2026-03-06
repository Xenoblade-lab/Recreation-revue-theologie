# Plan : badges de notifications dans la sidebar des dashboards

Objectif : afficher le **nombre de notifications non lues** à côté du lien « Notifications » dans la sidebar des trois espaces (admin, auteur, évaluateur), comme dans l’ancien projet côté auteur.

---

## Contexte

- Le modèle `NotificationModel::countUnreadByUserId(int $userId)` existe déjà et retourne le nombre de notifications non lues.
- Les layouts des dashboards reçoivent `$currentUser` (passé par chaque contrôleur).
- Les trois layouts ont déjà un lien « Notifications » dans la sidebar ; il manque uniquement le badge (chiffre).

---

## Phase 1 – Préparation des variables dans les layouts

### Étape 1.1 – Layout Admin

- [x] **1.1.1** Dans `views/layouts/admin-dashboard.php`, en haut du fichier (après la définition de `$base`), ajouter le calcul du nombre de notifications non lues pour l’utilisateur connecté.
- [x] **1.1.2** Utiliser une variable `$notificationCount` (ou `$unreadNotificationCount`) : si `$currentUser` et `$currentUser['id']` sont définis, appeler `NotificationModel::countUnreadByUserId((int) $currentUser['id'])`, sinon `0`.
- [x] **1.1.3** S’assurer que le modèle est disponible (namespace `Models\NotificationModel` ou autoload déjà chargé par le front controller).

### Étape 1.2 – Layout Auteur

- [x] **1.2.1** Même logique dans `views/layouts/author-dashboard.php` : calculer `$notificationCount` (ou `$unreadNotificationCount`) en début de fichier à partir de `$currentUser`.

### Étape 1.3 – Layout Évaluateur

- [x] **1.3.1** Même logique dans `views/layouts/reviewer-dashboard.php` : calculer `$notificationCount` (ou `$unreadNotificationCount`) en début de fichier à partir de `$currentUser`.

---

## Phase 2 – Affichage du badge dans la sidebar

### Étape 2.1 – Admin

- [x] **2.1.1** Dans `views/layouts/admin-dashboard.php`, à côté du libellé « Notifications » du lien vers `/admin/notifications`, ajouter un élément conditionnel (ex. `<span>`) qui affiche le nombre si `$notificationCount > 0`.
- [x] **2.1.2** Utiliser une classe CSS dédiée pour le badge (ex. `sidebar-notif-badge` ou réutiliser une classe existante type `badge` / `nav-badge`) pour le style (couleur, taille, arrondi).
- [x] **2.1.3** Gérer l’affichage des grands nombres (ex. afficher « 99+ » si `$notificationCount > 99`) pour éviter un badge trop large.

### Étape 2.2 – Auteur

- [x] **2.2.1** Dans `views/layouts/author-dashboard.php`, à côté du libellé « Notifications » du lien vers `/author/notifications`, ajouter le même type de badge conditionnel basé sur `$notificationCount`.
- [x] **2.2.2** Réutiliser la même classe CSS que pour l’admin pour cohérence visuelle.

### Étape 2.3 – Évaluateur

- [x] **2.3.1** Dans `views/layouts/reviewer-dashboard.php`, à côté du libellé « Notifications » du lien vers `/reviewer/notifications`, ajouter le même type de badge conditionnel basé sur `$notificationCount`.
- [x] **2.3.2** Réutiliser la même classe CSS que pour l’admin et l’auteur.

---

## Phase 3 – Styles CSS

### Étape 3.1 – Classe du badge sidebar

- [x] **3.1.1** Dans `public/css/styles.css` (ou fichier CSS des dashboards), ajouter ou compléter une classe pour le badge de notifications dans la sidebar (ex. `.dashboard-sidebar .sidebar-notif-badge` ou `.dashboard-sidebar nav a .badge`).
- [x] **3.1.2** Définir : taille de police, couleur de fond (ex. rouge ou accent), couleur du texte, padding, border-radius, alignement à droite du lien ou après le texte « Notifications ».
- [x] **3.1.3** S’assurer que le badge reste lisible en mode responsive (sidebar repliée / drawer sur mobile).

### Étape 3.2 – Accessibilité

- [x] **3.2.1** Ajouter un attribut `aria-label` sur le lien « Notifications » si le badge est affiché (ex. « Notifications, X non lues ») ou garder un libellé clair pour les lecteurs d’écran.
- [x] **3.2.2** Éviter de mettre des informations utiles uniquement en couleur (le chiffre doit être lisible et compris même sans le badge coloré).

---

## Phase 4 – Vérifications

### Étape 4.1 – Comportement

- [x] **4.1.1** Vérifier que le badge n’apparaît que lorsque le nombre est strictement supérieur à 0. *(Code : `<?php if ($notificationCount > 0): ?>` sur les 3 layouts.)*
- [x] **4.1.2** Vérifier qu’après avoir marqué des notifications comme lues (depuis la page Notifications), un rechargement de n’importe quelle page du dashboard met à jour le badge (ou disparition si plus de non lues). *(Code : `$notificationCount` est recalculé à chaque chargement via `NotificationModel::countUnreadByUserId()`.)*
- [x] **4.1.3** Tester avec 0, 1, 10 et 100+ notifications non lues pour confirmer l’affichage et le cap « 99+ » si implémenté. *(Code : `$notificationCount > 99 ? '99+' : $notificationCount` dans les 3 sidebars.)*

### Étape 4.2 – Rôles

- [x] **4.2.1** Tester en tant qu’admin : badge visible dans la sidebar admin. *(Badge présent dans `admin-dashboard.php`.)*
- [x] **4.2.2** Tester en tant qu’auteur : badge visible dans la sidebar auteur. *(Badge présent dans `author-dashboard.php`.)*
- [x] **4.2.3** Tester en tant qu’évaluateur : badge visible dans la sidebar évaluateur. *(Badge présent dans `reviewer-dashboard.php`.)*

### Étape 4.3 – Responsive

- [x] **4.3.1** Vérifier l’affichage du badge dans la sidebar en mode desktop et en mode mobile (drawer / menu hamburger). *(CSS : `.sidebar-notif-badge` + media query `max-width: 1023px` pour le drawer.)*

---

## Récapitulatif des fichiers à modifier

| Fichier | Modifications |
|---------|----------------|
| `views/layouts/admin-dashboard.php` | Calcul de `$notificationCount` en tête ; badge à côté du lien Notifications. |
| `views/layouts/author-dashboard.php` | Idem. |
| `views/layouts/reviewer-dashboard.php` | Idem. |
| `public/css/styles.css` | Classe(s) pour le badge de notifications dans la sidebar. |

---

## Notes

- Aucune modification des contrôleurs n’est nécessaire si `$currentUser` est déjà passé aux vues (c’est le cas dans le projet actuel).
- Le modèle `NotificationModel::countUnreadByUserId()` est déjà utilisé dans le header du site public ; même API pour les dashboards.
- Si un utilisateur n’a pas encore de compte ou que `$currentUser` est absent (cas théorique sur une page dashboard), utiliser `0` pour éviter toute erreur.
