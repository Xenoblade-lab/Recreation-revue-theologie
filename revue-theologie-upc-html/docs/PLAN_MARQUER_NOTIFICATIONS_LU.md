# Plan : corriger « Marquer comme lu » partout sur le site

**Objectif :** Que le marquage « Marquer comme lu » et « Tout marquer comme lu » fonctionne correctement sur les trois espaces (Admin, Auteur, Évaluateur), que le badge de notifications se mette à jour, et que l’utilisateur reçoive un retour en cas d’échec.

**Problème observé :** Après avoir cliqué sur « Marquer comme lu », le badge reste identique et la notification reste affichée comme non lue. Causes possibles : identifiant mal transmis, mise à jour non effectuée, ou cache navigateur.

---

## Phase 1 — Administration (`/admin/notifications`)

- [x] **1.1** Dans `AdminController::notifications()`, envoyer les en-têtes anti-cache pour que la page ne soit pas mise en cache après une redirection (éviter que le navigateur réaffiche l’ancien état).
- [x] **1.2** Dans `AdminController::notificationMarkRead()` :
  - Normaliser l’`id` : `trim((string) ($params['id'] ?? ''))`.
  - Utiliser la valeur de retour de `NotificationModel::markAsRead($id, $userId)`.
  - Si `markAsRead` retourne `false`, définir `$_SESSION['admin_error']` avec un message dédié (clé `admin.mark_read_failed`).
  - Appeler `release_session()` avant la redirection.
- [x] **1.3** Vérifier que la vue `views/admin/notifications.php` affiche bien `$error` et que les formulaires « Marquer comme lu » envoient l’URL correcte (avec `$base` et l’`id` de la notification).

---

## Phase 2 — Espace auteur (`/author/notifications`)

- [x] **2.1** Dans `AuthorController::notifications()`, envoyer les en-têtes anti-cache (même logique que Phase 1.1).
- [x] **2.2** Dans `AuthorController::notificationMarkRead()` :
  - Normaliser l’`id` : `trim((string) ($params['id'] ?? ''))`.
  - Utiliser la valeur de retour de `NotificationModel::markAsRead($id, $userId)`.
  - Si `markAsRead` retourne `false`, définir `$_SESSION['author_error']` avec un message dédié (clé `author.mark_read_failed`).
  - Appeler `release_session()` avant la redirection.
- [x] **2.3** Vérifier que la vue `views/author/notifications.php` affiche `$error` et que les formulaires utilisent la bonne URL (avec `$base` et l’`id`).

---

## Phase 3 — Espace évaluateur (`/reviewer/notifications`)

- [x] **3.1** Dans `ReviewerController::notifications()`, en-têtes anti-cache déjà ajoutés.
- [x] **3.2** Dans `ReviewerController::notificationMarkRead()`, id normalisé, vérification du retour de `markAsRead`, message d’erreur `reviewer.mark_read_failed`, et `release_session()` avant redirection.
- [x] **3.3** Clé de traduction `reviewer.mark_read_failed` déjà ajoutée (fr, en, ln).

---

## Phase 4 — Traductions (messages d’échec)

- [x] **4.1** Ajouter la clé `admin.mark_read_failed` dans `lang/fr.php`, `lang/en.php`, `lang/ln.php`.
- [x] **4.2** Ajouter la clé `author.mark_read_failed` dans `lang/fr.php`, `lang/en.php`, `lang/ln.php`.

---

## Phase 5 — Vérifications finales

- [ ] **5.1** Tester en Admin : cliquer « Marquer comme lu » sur une notification → la notification passe en « lue », le badge diminue ; en cas d’échec (ex. id invalide), le message d’erreur s’affiche.
- [ ] **5.2** Tester en Auteur : même scénario.
- [ ] **5.3** Tester en Évaluateur : même scénario (déjà corrigé).
- [ ] **5.4** Tester « Tout marquer comme lu » sur chaque espace : toutes les notifications passent en lues, badge à 0.

---

## Fichiers concernés

| Fichier | Modifications |
|--------|----------------|
| `controllers/AdminController.php` | `notifications()` : en-têtes cache ; `notificationMarkRead()` : id trim, retour `markAsRead`, message erreur |
| `controllers/AuthorController.php` | Idem pour auteur |
| `controllers/ReviewerController.php` | Déjà mis à jour (Phase 3) |
| `lang/fr.php`, `lang/en.php`, `lang/ln.php` | `admin.mark_read_failed`, `author.mark_read_failed` |
| `models/NotificationModel.php` | Aucun changement (déjà correct) |

---

## Correctif « badge ne diminue pas » (3 dashboards)

Pour que le nombre du badge se mette à jour après « Marquer comme lu » :

- **Cache :** En-têtes anti-cache ajoutés dans les **trois layouts** (admin, auteur, évaluateur) : chaque page du dashboard envoie `Cache-Control: no-store, no-cache, must-revalidate, max-age=0`, `Pragma: no-cache`, `Expires: 0`. Ainsi, après une redirection, le navigateur ne réaffiche pas une ancienne version et le badge est recalculé.
- **Modèle :** Dans `NotificationModel::markAsRead()`, l’`id` est trimé, et les paramètres sont liés explicitement avec `bindValue` (`:id` en `PARAM_STR`, `:uid` en `PARAM_INT`) pour éviter tout souci de typage avec l’UUID.

---

## Notes techniques

- **Identifiant notification :** UUID (string), transmis dans l’URL `/admin/notification/[id]/read`. Le routeur utilise `[s:id]` ; s’assurer que l’`id` n’est pas tronqué (pas de caractère interdit dans l’URL).
- **Badge :** Le nombre est calculé à chaque rendu du layout via `NotificationModel::countUnreadByUserId()`. Après une redirection GET, le layout est re-rendu avec le nouveau compte si l’UPDATE a bien eu lieu.
- **Cache :** En-têtes no-cache dans les layouts dashboard (voir section ci-dessus) et sur la page liste des notifications.
