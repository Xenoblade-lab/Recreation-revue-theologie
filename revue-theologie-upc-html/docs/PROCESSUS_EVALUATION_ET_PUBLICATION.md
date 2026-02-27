# Processus d'évaluation et de publication — Ancien vs nouveau projet

Ce document décrit et compare le **processus d'évaluation** des articles et le **processus de publication** dans l’ancien projet (`revue-ancien/`) et le nouveau (`revue-theologie-upc-html/`).

---

## 1. Vue d’ensemble

| Aspect | Ancien projet (`revue-ancien`) | Nouveau projet (`revue-theologie-upc-html`) |
|--------|---------------------------------|---------------------------------------------|
| **Soumission** | Formulaire soumission (après inscription / abonnement), article créé avec statut `soumis` | Espace auteur : brouillon → soumission ; statut `soumis` ou `brouillon` |
| **Évaluation** | Double aveugle ; assignation par l’admin ; évaluateur remplit formulaire (notes, recommandation) ; **statut article mis à jour automatiquement** selon les recommandations | Double aveugle ; assignation par l’admin ; évaluateur remplit formulaire (notes, recommandation) ; **pas de mise à jour automatique** du statut article — l’admin décide |
| **Statuts article** | `soumis`, `en_evaluation`, `revision_requise`, `accepte`, `rejete`, `publie`, `valide` | `brouillon`, `soumis`, `valide`, `rejete` (affichage possible de `revision_requise`) |
| **Publication** | Admin peut passer en `valide` ou déclencher « Publier » → `publie` ; lien article–numéro via `issue_id` (table `revues`) | Admin met le statut à `valide` ; rattachement à un numéro (`issue_id`) ; pas de statut distinct `publie` — `valide` = publié côté public |
| **Numéros / Volumes** | `volumes` → `revues` (numéros) ; `articles.issue_id` → `revues.id` ; possibilité `revue_article` (article dans une revue/numéro) | `volumes` → `revues` (numéros) ; `articles.issue_id` → `revues.id` ; page détail numéro liste les articles du numéro |

---

## 2. Processus d’évaluation

### 2.1 Ancien projet (`revue-ancien`)

1. **Soumission**
   - L’auteur crée un article (titre, contenu, fichier) et le soumet → statut `soumis`.
   - Modèle : `ArticleModel` (insert avec `statut = 'soumis'`, `date_soumission = NOW()`).

2. **Assignation**
   - L’admin assigne un ou plusieurs évaluateurs à l’article depuis la fiche article.
   - `ReviewModel::assignReviewer($articleId, $reviewerId, $deadlineDays)` crée une ligne dans `evaluations` (statut `en_attente`, `date_echeance`).
   - Table `evaluations` : `article_id`, `evaluateur_id`, `statut` (`en_attente`, `en_cours`, `termine`, `annule`), recommandation, notes (qualité scientifique, originalité, pertinence, clarté, note finale), commentaires public/privé, suggestions.

3. **Travail de l’évaluateur**
   - Accepter / refuser l’assignation (`acceptReviewAssignment` / `declineReviewAssignment`).
   - Formulaire d’évaluation : recommandation (`accepte`, `accepte_avec_modifications`, `revision_majeure`, `rejete`), notes sur 10, commentaires.
   - À la soumission : `ReviewModel::submitReview()` met l’évaluation en `termine` et **appelle `updateArticleStatusBasedOnReviews($articleId)`**.

4. **Mise à jour automatique du statut de l’article**
   - Selon les recommandations des évaluations terminées, l’article peut passer à `revision_requise`, `accepte`, `rejete`, etc.
   - Notification à l’auteur et création d’une révision si `revision_requise`.

5. **Intervention admin**
   - Consultation des évaluations (liste, détail).
   - Modification manuelle du statut possible (soumis, valide, rejete).
   - Action « Publier » si l’article est accepté → statut `publie`.

**Fichiers clés :** `models/ReviewModel.php`, `models/ArticleModel.php`, `controllers/ReviewController.php`, `controllers/AdminController.php`, `views/admin/article-details.php`, `views/admin/evaluation-details.php`, `views/reviewer/evaluation-details.php`.

---

### 2.2 Nouveau projet (`revue-theologie-upc-html`)

1. **Soumission**
   - L’auteur crée un article (brouillon ou soumission directe) ; statut `brouillon` ou `soumis`, `date_soumission` enregistrée à la soumission.
   - Modèle : `ArticleModel::create()` / `ArticleModel::submit()`.

2. **Assignation**
   - Depuis la fiche article (admin), formulaire « Assigner un évaluateur » : choix de l’évaluateur, POST vers `/admin/article/[id]/assign`.
   - `EvaluationModel::assign($articleId, $evaluateurId)` insère une ligne dans `evaluations` (statut `en_attente`, `date_echeance` par défaut +14 jours).
   - Notification à l’évaluateur (nouvelle assignation).

3. **Travail de l’évaluateur**
   - Pas d’acceptation/refus explicite d’assignation dans le flux principal ; l’évaluateur ouvre directement l’évaluation.
   - Formulaire d’évaluation : recommandation (`accepte`, `accepte_avec_modifications`, `revision_majeure`, `rejete`), notes (qualité scientifique, originalité, pertinence, clarté), note finale, commentaires public/privé, suggestions.
   - **Brouillon** : `EvaluationModel::saveDraft()` → statut évaluation `en_cours`, pas de date de soumission.
   - **Soumettre l’évaluation** : `EvaluationModel::submit()` → statut `termine`, `date_soumission = NOW()`, et mise à jour de `decision_finale` (accepte / rejete / revision_requise) **uniquement dans la table `evaluations`**. **Le statut de l’article n’est pas modifié automatiquement.**

4. **Décision éditoriale**
   - L’admin consulte la fiche article et les évaluations.
   - L’admin choisit le statut de l’article : **Soumis**, **Publié** (`valide`) ou **Rejeté** (`rejete`) via le formulaire « Changer le statut » (POST `/admin/article/[id]/statut`).
   - Aucune action « Publier » séparée : « Publié » = statut `valide`.

5. **Rattachement au numéro**
   - Sur la fiche article, formulaire « Rattacher à un numéro » : choix d’un numéro (`issue_id`), POST `/admin/article/[id]/issue`. Optionnel pour la visibilité publique ; les articles avec `statut = 'valide'` sont déjà considérés publiés.

**Fichiers clés :** `models/EvaluationModel.php`, `models/ArticleModel.php`, `controllers/AdminController.php` (articleUpdateStatut, articleAssign, articleSetIssue), `controllers/ReviewerController.php`, `views/admin/article-detail.php`, `views/reviewer/evaluation.php`.

---

## 3. Processus de publication

### 3.1 Ancien projet

- **Statuts « publiés »** : les articles visibles publiquement sont ceux dont le statut est dans `('publie', 'publié', 'valide', 'validé', 'accepte', 'accepté', 'accepted')`.
- **Numéros** : table `revues` (numéros), liée aux `volumes`. Un article peut être associé à un numéro via `articles.issue_id` (ou, selon les requêtes, `revue_article` pour l’ancien modèle revue/journal).
- **Workflow typique** : soumis → en évaluation (automatique ou manuel) → revision_requise / accepte / rejete (automatique selon évaluations ou manuel) → admin met « Validé » ou déclenche « Publier » → `valide` ou `publie`. L’article peut être assigné à un numéro (issue) pour l’archivage par volume/numéro.

### 3.2 Nouveau projet

- **Statut « publié »** : un article est considéré publié dès que **`statut = 'valide'`**. Il apparaît dans les listes publiques (publications, recherche, page d’accueil, etc.).
- **Numéros** : table `revues` (numéros), avec `volume_id`. Les articles ont un champ `issue_id` (nullable) pour les rattacher à un numéro. La page détail d’un numéro affiche les articles dont `issue_id` correspond.
- **Workflow typique** : brouillon → soumis → admin assigne des évaluateurs → évaluateurs soumettent leurs rapports → admin décide et met le statut à **valide** (publié) ou **rejete**. En option, l’admin rattache l’article à un numéro pour l’organisation en volumes/numéros.

---

## 4. Synthèse des différences

| Étape | Ancien | Nouveau |
|-------|--------|---------|
| Soumission auteur | Article soumis → `soumis` | Brouillon ou soumission → `brouillon` / `soumis` |
| Assignation évaluateur | Admin assigne ; échéance configurable | Admin assigne ; échéance par défaut 14 jours |
| Formulaire évaluateur | Notes, recommandation, commentaires ; soumission définitive | Idem + **brouillon** (sauvegarde sans soumettre) |
| Après soumission évaluation | **Mise à jour automatique** du statut article (revision_requise, accepte, rejete, etc.) | **Aucune mise à jour** du statut article ; l’admin lit les évaluations et choisit le statut |
| Statuts article utilisés | soumis, en_evaluation, revision_requise, accepte, rejete, publie, valide | brouillon, soumis, valide, rejete |
| « Publication » | Action « Publier » → `publie` ; ou statut `valide` | Mise du statut à `valide` = article publié (visible) |
| Rattachement numéro | `articles.issue_id` → `revues` ; possiblement `revue_article` | `articles.issue_id` → `revues` ; formulaire admin « Rattacher à un numéro » |
| Notifications | Notification auteur (changement de statut, évaluation terminée, etc.) | Notification auteur (changement de statut, assignation évaluateur) ; notification évaluateur (nouvelle assignation) |

---

## 5. Politique éditoriale affichée (texte)

Les deux projets s’appuient sur une **politique éditoriale** décrite côté public (page « Politique éditoriale » / « Processus d’évaluation ») :

- **Évaluation en double aveugle** par au moins deux experts.
- Le comité éditorial peut demander des **modifications** ou **refuser** les manuscrits ne répondant pas aux critères.
- **Droits d’auteur** : les auteurs conservent leurs droits ; première publication par la revue ; licence **CC BY-NC 4.0**.

Dans le **nouveau** projet, cette page est gérée par `views/public/politique-editoriale.php` et les textes par les clés de traduction `politique.*` (fr, en, ln).

---

## 6. Fichiers de référence

### Ancien projet (`revue-ancien`)

- **Évaluation** : `models/ReviewModel.php`, `controllers/ReviewController.php`, `controllers/AdminController.php` (evaluations, evaluationDetails, updateArticleStatus, publishArticle), `views/admin/evaluations.php`, `views/admin/evaluation-details.php`, `views/admin/article-details.php`, `views/reviewer/evaluation-details.php`.
- **Publication / numéros** : `models/ArticleModel.php` (changeArticleStatus, issue_id, statuts valide/publie), `models/IssueModel.php`, `models/VolumeModel.php`, `views/admin/volumes.php`, `views/admin/issue-details.php`.

### Nouveau projet (`revue-theologie-upc-html`)

- **Évaluation** : `models/EvaluationModel.php`, `controllers/ReviewerController.php` (evaluation, evaluationPost), `controllers/AdminController.php` (articleAssign, articleUpdateStatut), `views/admin/article-detail.php`, `views/admin/evaluations.php`, `views/reviewer/evaluation.php`, `views/reviewer/index.php`, `views/reviewer/terminees.php`.
- **Publication** : `models/ArticleModel.php` (updateStatut, setIssueId, requêtes `statut = 'valide'`), `controllers/AdminController.php` (articleUpdateStatut, articleSetIssue), `models/RevueModel.php`, `models/VolumeModel.php`, `views/admin/volumes.php`, `views/admin/numero-detail.php`, `views/public/numero-details.php`.

---

*Document généré à partir de l’analyse des codebases `revue-ancien` et `revue-theologie-upc-html`.*
