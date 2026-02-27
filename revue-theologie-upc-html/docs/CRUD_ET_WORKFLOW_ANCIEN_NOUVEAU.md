# Actions CRUD et workflow éditorial — Ancien vs nouveau projet

Ce document recense **toutes les actions CRUD** (Create, Read, Update, Delete) et le **workflow éditorial** (parcours de l'article de la soumission à la publication) dans l’ancien projet (`revue-ancien/`) et le nouveau (`revue-theologie-upc-html/`).

---

## 1. Résumé des entités et CRUD

### 1.1 Ancien projet (`revue-ancien`)

| Entité | Create | Read | Update | Delete | Remarques |
|--------|--------|------|--------|--------|-----------|
| **Users** | Oui (admin: createUser, createEvaluator ; register) | getUser, userDetails, getAvailableReviewers, etc. | updateUser, updateUserStatus, updatePassword | deleteUser | Rôles : admin, rédacteur, auteur, user. |
| **Articles** | Oui (AuthorController + route POST /articles) | getArticleById, liste admin, liste auteur, publications publiques | updateArticle, changeArticleStatus, assignIssue, resubmit (revision) | deleteArticle (admin) | Statuts : soumis, en_evaluation, revision_requise, accepte, rejete, publie, valide. |
| **Evaluations** | Oui (assignReviewer) | getReviewById, liste admin, liste reviewer | submitReview, acceptReviewAssignment, declineReviewAssignment, updateArticleStatusBasedOnReviews | unassignReviewer (suppression liaison) | Statuts : en_attente, en_cours, termine, annule. |
| **Volumes** | Oui (createVolume) | volumeDetails, liste volumes | updateVolume | deleteVolume | Table `volumes`. |
| **Revues / Numéros (issues)** | Oui (createIssue) | issueDetails, liste par volume | updateIssue, assignIssueToVolume, revue_parts/ordre | DELETE revues (IssueModel) | Table `revues` ; `revue_parts` pour contenu. |
| **Revue_article** | Oui (liaison article ↔ revue) | — | — | Oui (suppression liaison) | Lien many-to-many article / numéro (selon usage). |
| **Paiements** | Oui (création abonnement / demande) | Liste admin, liste auteur | updatePaymentStatus (valide/refuse) | — | Pas de suppression physique. |
| **Notifications** | Oui (notifyArticleStatusChange, etc.) | Liste par user, mark read | markNotificationRead, markAllRead | — | Lecture / marquage lu. |
| **Revue_info** | Oui (insert si vide) | get (paramètres revue) | updateRevueSettings | — | Une seule ligne (paramètres globaux). |
| **Révisions (article_revisions)** | Oui (createRevision après révision requise) | getArticleRevisions | — | — | Historique des changements de statut. |
| **Criteres_evaluation / scores_evaluation** | Oui (ReviewModel) | — | Oui (scores par critère) | Oui (scores) | Ancien : critères configurables. |

**Routes clés (ancien) :**
- Admin : `/admin/article/[id]/update-status`, `/admin/article/[id]/publish`, `/admin/article/[id]/delete`, `/admin/article/[id]/assign-reviewer`, `/admin/article/[id]/unassign-reviewer/[evaluation_id]`, `/admin/user/create`, `/admin/user/[id]/update`, `/admin/user/[id]/delete`, `/admin/volume/[id]/delete`, `/admin/paiement/[id]/update-status`, `/admin/volumes/create`, `/admin/issues/create`, `/admin/issues/update`, `/admin/articles/[id]/assign-issue`.
- Auteur : `/author/article/[id]/update`, `/author/article/[id]/delete`, POST `/articles` (création).
- Reviewer : POST `/reviewer/evaluation/[id]/save-draft`, POST `/reviewer/evaluation/[id]/submit`.

---

### 1.2 Nouveau projet (`revue-theologie-upc-html`)

| Entité | Create | Read | Update | Delete | Remarques |
|--------|--------|------|--------|--------|-----------|
| **Users** | Oui (register ; admin: userStore) | getById, getByEmail, getAll (admin) | updateProfile (user), update (admin), updateRole | **Non** | Pas de suppression utilisateur. |
| **Articles** | Oui (AuthorController::soumettre, article edit brouillon) | getById, liste auteur, liste admin, publications (statut = valide) | articleUpdate, articleSubmitDraft, updateStatut (admin), setIssueId (admin) | **Non** | Statuts : brouillon, soumis, valide, rejete. |
| **Evaluations** | Oui (EvaluationModel::assign par admin) | getByEvaluateurId, getByIdForReviewer, liste admin | saveDraft, submit (reviewer) | **Non** | Pas de désassignation (pas de DELETE). |
| **Volumes** | **Non** | getAll, getById | volumeUpdate (admin) | **Non** | Volumes supposés existants (seed/migration). |
| **Revues (numéros)** | **Non** | getAll, getById, getLatest, search | numeroUpdate (admin) | **Non** | Numéros supposés existants. |
| **Paiements** | Oui (PaiementModel::createDemandeAbonnement) | getByUserId, getById, getAll admin | updateStatut, setValide, cancel (auteur) | **Non** | Région + payment_details ; validation/refus admin. |
| **Abonnements** | Oui (AbonnementModel::create après validation paiement) | getActiveByUserId, getByUserId | cancel (résiliation par auteur) | **Non** | 1 an, statut actif/expire. |
| **Notifications** | Oui (NotificationModel::create) | Liste par notifiable_id | markRead, markAllRead | **Non** | Champ read_at. |
| **Revue_info** | **Non** (ligne id=1 existante) | RevueInfoModel::get | parametresUpdate (admin) | **Non** | Paramètres revue (comités, ISSN, etc.). |
| **Newsletter** | Oui (inscription email) | — | — | **Non** | Table newsletter_emails. |

**Routes clés (nouveau) :**
- Admin : POST `/admin/article/[id]/statut`, POST `/admin/article/[id]/assign`, POST `/admin/article/[id]/issue`, POST `/admin/users/create`, POST `/admin/users/[id]/edit`, POST `/admin/paiement/[id]/statut` (ou valider/refuser), POST `/admin/volume/[id]`, POST `/admin/numero/[id]`, POST `/admin/parametres`.
- Auteur : POST `/author/soumettre`, GET/POST `/author/article/[id]/edit`, POST `/author/article/[id]/submit`, POST `/author/s-abonner`, POST `/author/paiement/[id]/cancel`, POST `/author/abonnement/cancel`.
- Reviewer : POST `/reviewer/evaluation/[id]` (draft ou submit selon bouton).

---

## 2. Workflow éditorial

Le **workflow éditorial** décrit le parcours d’un article depuis sa soumission jusqu’à sa publication (ou rejet) : soumission → évaluation par les pairs → décision éditoriale → révision éventuelle → publication.

---

### 2.1 Workflow éditorial — Ancien projet

| Étape | Acteur | Action | Statut article / effet |
|-------|--------|--------|-------------------------|
| **1. Soumission** | Auteur | Soumet le manuscrit (titre, contenu, fichier) | `soumis` |
| **2. Préparation** | Admin | Consulte la fiche article, peut modifier le statut manuellement si besoin | — |
| **3. Assignation** | Admin | Assigne un ou plusieurs évaluateurs à l'article (avec date d'échéance) | Création de lignes `evaluations` (statut `en_attente`) |
| **4. Acceptation / refus** | Évaluateur | Accepte ou refuse l'assignation | Évaluation → `en_cours` ou `annule` |
| **5. Évaluation** | Évaluateur | Remplit le formulaire (recommandation, notes, commentaires) ; peut sauvegarder un brouillon | Brouillon : évaluation `en_cours`. Soumission : évaluation `termine` |
| **6. Décision automatique** | Système | Après soumission d'évaluation(s), `updateArticleStatusBasedOnReviews()` met à jour le statut de l'article selon les recommandations | Article → `revision_requise`, `accepte` ou `rejete` ; notification auteur ; création révision si révision requise |
| **7. Révision (si demandée)** | Auteur | Modifie l'article et resoumet | Article repasse à `soumis` (nouveau cycle possible) |
| **8. Décision finale** | Admin | Peut modifier le statut manuellement (soumis / valide / rejete) ou cliquer « Publier » si l'article est accepté | `valide` ou `publie` |
| **9. Publication** | Admin | Assigne l'article à un numéro (issue) pour l'archivage | `articles.issue_id` renseigné ; article visible dans le numéro |

**Schéma du flux éditorial :**

```
  Auteur              Admin                  Évaluateur(s)              Système
     │                   │                         │                      │
     │ 1. Soumet         │                         │                      │
     │──────────────────►│                         │                      │
     │                   │ 2–3. Assigne            │                      │
     │                   │────────────────────────►│                      │
     │                   │                         │ 4. Accepte/refuse    │
     │                   │                         │ 5. Brouillon/Submit  │
     │                   │                         │──────────────────────►│ 6. Mise à jour
     │◄──────────────────────────────────────────────────────────────────│    statut article
     │  Notif            │                         │                      │
     │ 7. Révision (si revision_requise)          │                      │
     │──────────────────►│                         │                      │
     │                   │ 8. Statut / Publier     │                      │
     │                   │ 9. Assigner au numéro  │                      │
```

**Points clés (ancien) :** La **décision éditoriale** (revision_requise / accepte / rejete) est **automatique** après les soumissions d'évaluations. L'admin peut recadrer le statut et dispose d'une action « Publier » (→ `publie`). Distinction possible entre `valide` et `publie`.

---

### 2.2 Workflow éditorial — Nouveau projet

```
| Étape | Acteur | Action | Statut article / effet |
|-------|--------|--------|-------------------------|
| **1. Soumission** | Auteur | Brouillon ou soumet (titre, contenu, fichier) | `brouillon` ou `soumis` |
| **2–3. Assignation** | Admin | Assigne évaluateurs | `evaluations` créées ; notif évaluateur |
| **4. Évaluation** | Évaluateur | Brouillon ou soumet (recommandation, notes) | Éval. `en_cours` ou `termine` ; **article inchangé** |
| **5. Décision** | Admin | Choisit statut : Soumis / Publié (`valide`) / Rejeté (`rejete`) | Notif auteur |
| **7. Publication** | Admin | Optionnel : rattacher au numéro | `valide` = visible en public |

**Flux éditorial (nouveau) :**

```
  Auteur              Admin                  Évaluateur(s)
     │                   │                         │
     │ 1. Soumis         │                         │
     │──────────────────►│ 2–3. Assigne            │
     │                   │────────────────────────►│ 4. Brouillon/Submit
     │                   │ 5. Statut (soumis/valide/rejete)
     │◄──────────────────│                         │
     │  Notif            │ 7. Rattacher numéro     │
```

**Points clés :** Décision **manuelle** (admin). `valide` = publié. Pas d'acceptation/refus d'assignation.

---

### 2.3 Comparaison du workflow éditorial

| Étape / critère | Ancien | Nouveau |
|------------------|--------|--------|
| **Soumission** | Article créé en `soumis` | Brouillon ou `soumis` |
| **Assignation évaluateurs** | Admin assigne ; échéance configurable | Admin assigne ; échéance par défaut (ex. 14 jours) |
| **Réaction évaluateur** | Peut accepter ou refuser l'assignation | Accès direct au formulaire (pas d'acceptation/refus) |
| **Brouillon évaluation** | Oui | Oui |
| **Décision après évaluation** | **Automatique** (updateArticleStatusBasedOnReviews) | **Manuelle** (admin choisit le statut) |
| **Statuts article « finaux »** | revision_requise, accepte, rejete, valide, publie | soumis, valide, rejete |
| **Publication** | Action « Publier » → `publie` ; ou statut `valide` | Statut `valide` = article publié (visible) |
| **Rattachement au numéro** | Admin assigne l'article à un numéro (issue) | Admin rattache à un numéro (issue_id) ; optionnel |

---

## 3. Tableau comparatif CRUD

| Entité | Create | Read | Update | Delete |
|--------|--------|------|--------|--------|
| **Users** | Ancien : oui (admin + register). Nouveau : oui (admin + register) | Les deux | Les deux | Ancien : oui (admin). Nouveau : **non** |
| **Articles** | Les deux | Les deux | Les deux | Ancien : oui (admin). Nouveau : **non** |
| **Evaluations** | Les deux (assignation) | Les deux | Les deux (submit, draft) | Ancien : oui (unassign). Nouveau : **non** |
| **Volumes** | Ancien : oui. Nouveau : **non** | Les deux | Les deux | Ancien : oui. Nouveau : **non** |
| **Revues / Numéros** | Ancien : oui. Nouveau : **non** | Les deux | Les deux | Ancien : oui. Nouveau : **non** |
| **Paiements** | Les deux | Les deux | Les deux (statut) | Aucun (physique) |
| **Abonnements** | Nouveau : oui (après validation paiement). Ancien : logique différente | Les deux (selon besoin) | Nouveau : cancel. Ancien : cancel possible | — |
| **Notifications** | Les deux | Les deux | Marquer lu (les deux) | — |
| **Revue_info** | Ancien : possible. Nouveau : non (ligne fixe) | Les deux | Les deux | — |

---

## 4. Synthèse des différences (workflow éditorial et CRUD)

| Aspect | Ancien | Nouveau |
|--------|--------|--------|
| **Décision après évaluation** | Automatique (updateArticleStatusBasedOnReviews) | Manuelle (admin choisit le statut) |
| **Publication** | Statuts `valide` et `publie` ; action « Publier » explicite | Un seul statut « publié » : `valide` |
| **Création volumes / numéros** | Admin peut créer et supprimer | Admin ne fait que modifier (volumes/numéros préexistants) |
| **Suppression** | Article, user, volume, désassignation évaluateur possibles | Aucune suppression métier (pas de delete article, user, volume, numero, evaluation) |
| **Abonnement auteur** | Inscription / abonnement (subscribe, paiement) | Demande (s-abonner) → paiement en attente → admin valide/refuse → abonnement créé + rôle auteur |
| **Évaluateur** | Peut accepter ou refuser l’assignation | Pas d’acceptation/refus ; accès direct au formulaire |
| **Brouillon évaluation** | Oui (save-draft) | Oui (bouton « Sauvegarder le brouillon ») |

---

## 5. Fichiers de référence

### Ancien projet
- **Routes :** `routes/web.php` (GET/POST admin, author, reviewer, articles, revues).
- **Modèles :** `models/ArticleModel.php`, `models/ReviewModel.php`, `models/UserModel.php`, `models/VolumeModel.php`, `models/IssueModel.php`, `models/RevueInfoModel.php`, `models/NotificationModel.php`, `models/RevisionModel.php`.
- **Contrôleurs :** `controllers/AdminController.php`, `controllers/AuthorController.php`, `controllers/ReviewerController.php`.

### Nouveau projet
- **Routes :** `routes/web.php` (déclaration des routes admin, author, reviewer, public).
- **Modèles :** `models/ArticleModel.php`, `models/EvaluationModel.php`, `models/UserModel.php`, `models/VolumeModel.php`, `models/RevueModel.php`, `models/RevueInfoModel.php`, `models/PaiementModel.php`, `models/AbonnementModel.php`, `models/NotificationModel.php`, `models/NewsletterModel.php`.
- **Contrôleurs :** `controllers/AdminController.php`, `controllers/AuthorController.php`, `controllers/ReviewerController.php`, `controllers/AuthController.php`, `controllers/RevueController.php`.

---

*Document généré à partir de l’analyse des codebases `revue-ancien` et `revue-theologie-upc-html`.*
