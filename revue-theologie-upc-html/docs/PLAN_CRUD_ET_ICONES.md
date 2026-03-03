# Plan d’ajout d’actions CRUD et d’icônes

Ce document décrit un **plan par étapes** pour :
1. **Combler les actions CRUD manquantes** dans le nouveau projet (`revue-theologie-upc-html`) par rapport à l’ancien (`revue-ancien`) et aux besoins métier.
2. **Remplacer les libellés texte des actions CRUD par des icônes** (œil, crayon, corbeille, plus, etc.) **uniquement dans les tableaux** (listes) — pas sur les pages de détail : les boutons et formulaires des pages de détail restent en texte.

Références : `docs/CRUD_ET_WORKFLOW_ANCIEN_NOUVEAU.md`, ancien projet `revue-ancien/views/admin/` (articles, users, evaluations avec `.action-btn` + SVG).

---

## 1. État des lieux — CRUD manquants (nouveau vs ancien)

| Entité | Action manquante (nouveau) | Présent dans l’ancien |
|--------|----------------------------|------------------------|
| **Users** | **Delete** (suppression utilisateur par admin) | Oui : `deleteUser`, bouton Supprimer avec icône |
| **Articles** | **Delete** (suppression article par admin) | Oui : `deleteArticle`, bouton Supprimer avec icône |
| **Evaluations** | **Delete / Unassign** (retirer un évaluateur assigné) | Oui : `unassignReviewer`, désassignation |
| **Volumes** | **Create** (création d’un volume par admin) | Oui : `createVolume` |
| **Revues / Numéros** | **Create** (création d’un numéro par admin) | Oui : `createIssue` |
| **Paiements** | — | CRUD limité (pas de suppression) ; nouveau a déjà Valider/Refuser |
| **Comité éditorial** | — | Nouveau a déjà CRUD + Modifier / Retirer (texte) |

**Résumé :** À ajouter côté **backend + UI** : suppression utilisateur, suppression article, désassignation évaluateur, création volume, création numéro. Optionnel : suppression logique plutôt que physique pour articles/users selon la politique.

---

## 2. Objectifs du plan

- **Phase A — CRUD manquants :** Implémenter les actions listées ci-dessus (routes, contrôleurs, modèles, vues) avec confirmation lorsque c’est destructif (delete, unassign).
- **Phase B — Icônes :** Remplacer les boutons/liens texte des actions CRUD **uniquement dans les tableaux** (colonnes « Actions » des listes : articles, utilisateurs, évaluations, paiements, comité éditorial, articles auteur). Les pages de détail (fiche article, formulaire utilisateur, etc.) conservent des boutons **texte** ; seuls les tableaux passent en icônes. Chaque bouton icône aura `title` et `aria-label` pour l’accessibilité.

---

## 3. Phase A — Ajout des actions CRUD manquantes

### Étape A1 — Suppression utilisateur (admin)

**But :** Permettre à l’admin de supprimer un utilisateur (avec garde-fous : ne pas supprimer le dernier admin, ou désactiver au lieu de supprimer si préféré).

**Fichiers :**
- `controllers/AdminController.php` (méthode `userDelete` ou équivalent)
- `models/UserModel.php` (méthode `delete(int $id)` ou soft delete)
- `routes/web.php` (POST `/admin/users/[id]/delete`)
- `views/admin/users.php` (bouton Supprimer par ligne)

**Actions :**
- [x] Créer la route POST `/admin/users/[id]/delete` avec vérification CSRF.
- [x] Dans le contrôleur : vérifier que l’utilisateur connecté est admin, que l’utilisateur cible n’est pas lui-même (optionnel), et appliquer la règle métier (ex. interdire suppression du dernier admin). Appeler `UserModel::delete($id)` (ou soft delete).
- [x] Dans la vue liste des utilisateurs : ajouter un bouton/lien « Supprimer » (ou icône corbeille) avec confirmation JavaScript (`confirm()` ou modal) avant envoi du formulaire POST.
- [ ] Gérer les contraintes d’intégrité (articles, évaluations, paiements liés) : soit refuser la suppression si des données dépendent de l’utilisateur, soit cascade/soft delete selon le schéma.

**Référence ancien :** `revue-ancien` — bouton delete avec icône SVG, `confirmDeleteUser()`.

---

### Étape A2 — Suppression article (admin)

**But :** Permettre à l’admin de supprimer un article (soumission).

**Fichiers :**
- `controllers/AdminController.php` (méthode `articleDelete`)
- `models/ArticleModel.php` (méthode `delete(int $id)` ; supprimer ou désactiver les évaluations liées selon la règle)
- `routes/web.php` (POST `/admin/article/[id]/delete`)
- `views/admin/article-detail.php` et/ou `views/admin/articles.php` (bouton Supprimer)

**Actions :**
- [x] Créer la route POST `/admin/article/[id]/delete` avec CSRF.
- [x] Dans le contrôleur : vérifier admin, charger l’article, supprimer les évaluations associées (ou les garder en orphelines selon le schéma), puis supprimer l’article. Rediriger vers la liste des articles avec un message de succès.
- [x] Dans la fiche article (admin) : ajouter un bouton « Supprimer l’article » (ou icône corbeille) avec confirmation.
- [ ] Optionnel : dans la liste des articles (admin), ajouter une colonne Actions avec icône Supprimer (après Phase B si on fait d’abord les icônes).

**Référence ancien :** `revue-ancien/views/admin/articles.php` — bouton delete avec icône, `confirmDeleteArticle()`.

---

### Étape A3 — Désassignation évaluateur (admin)

**But :** Permettre à l’admin de retirer un évaluateur assigné à un article (suppression de la ligne dans `evaluations`).

**Fichiers :**
- `controllers/AdminController.php` (méthode `evaluationUnassign` ou `articleUnassignReviewer`)
- `models/EvaluationModel.php` (méthode `unassign(int $evaluationId)` ou `deleteByArticleAndEvaluator`)
- `routes/web.php` (POST `/admin/evaluation/[id]/unassign` ou `/admin/article/[id]/unassign/[evaluation_id]`)
- `views/admin/article-detail.php` (dans le tableau « Liste des évaluations », bouton Retirer / désassigner par ligne)

**Actions :**
- [x] Créer la route POST pour désassigner (ex. `/admin/evaluation/[id]/unassign` où `id` = id de l’évaluation).
- [x] Dans le contrôleur : vérifier admin, vérifier que l’évaluation appartient bien à un article existant, supprimer la ligne `evaluations`. Rediriger vers la fiche article avec message.
- [x] Dans la vue fiche article, tableau des évaluations : pour chaque ligne, ajouter un bouton « Retirer » (ou icône) avec confirmation, pointant vers la route de désassignation.
- [x] Adapter la règle « minimum 2 évaluateurs » si besoin (message après désassignation si on repasse sous le seuil).

**Référence ancien :** unassign reviewer sur la fiche article admin.

---

### Étape A4 — Création de volume (admin)

**But :** Permettre à l’admin de créer un nouveau volume.

**Fichiers :**
- `controllers/AdminController.php` (méthodes `volumeCreate`, `volumeStore`)
- `models/VolumeModel.php` (méthode `create(array $data)`)
- `routes/web.php` (GET `/admin/volumes/create`, POST `/admin/volumes`)
- `views/admin/volumes.php` (lien « Créer un volume ») et `views/admin/volume-form.php` (formulaire création, champs : année, numéro_volume, description, redacteur_chef, etc.)

**Actions :**
- [x] Vérifier que `VolumeModel` expose une méthode `create` (ou l’ajouter). Schéma table `volumes` : id, annee, numero_volume, description, redacteur_chef, etc.
- [x] Créer les routes GET (formulaire) et POST (traitement).
- [x] Créer la vue formulaire (champs cohérents avec l’ancien projet ou la structure actuelle). Redirection vers la liste des volumes ou la fiche du volume créé après succès.

**Référence ancien :** `createVolume`, formulaire de création volume.

---

### Étape A5 — Création de numéro / revue (admin)

**But :** Permettre à l’admin de créer un nouveau numéro (issue) lié à un volume.

**Fichiers :**
- `controllers/AdminController.php` (méthodes `numeroCreate`, `numeroStore`)
- `models/RevueModel.php` (méthode `create` pour la table `revues` si absente)
- `routes/web.php` (GET `/admin/numeros/create`, POST `/admin/numeros` ; ou sous un volume : `/admin/volume/[id]/numero/create`)
- `views/admin/numero-form.php` (formulaire : volume_id, numéro, titre, date_publication, etc.)

**Actions :**
- [x] Vérifier le schéma de la table `revues` (volume_id, numero, titre, date_publication, etc.) et ajouter une méthode `create` dans le modèle approprié.
- [x] Créer les routes et le formulaire de création. Liste déroulante des volumes pour lier le numéro.
- [x] Après création, rediriger vers la fiche du numéro ou la liste des volumes/numéros.

**Référence ancien :** `createIssue`, création de numéro.

---

## 4. Phase B — Représenter les actions CRUD par des icônes (uniquement dans les tableaux)

**But :** Remplacer les libellés texte des actions CRUD par des **boutons icônes** **uniquement dans les tableaux** (listes : articles, utilisateurs, évaluations, paiements, comité éditorial, liste des articles auteur). Les **pages de détail** (fiche article admin, formulaire édition utilisateur, etc.) ne sont pas concernées : les boutons et formulaires y restent en **texte**. Chaque bouton icône dans un tableau aura `title` et `aria-label` pour l’accessibilité.

### Étape B1 — Enrichir le fichier d’icônes

**Fichier :** `public/images/icons.svg`

**Actions :**
- [x] Ajouter les symboles manquants pour les actions CRUD (si pas déjà présents) :
  - **eye** (Lire / Voir) — déjà présent.
  - **edit** ou **pencil** (Modifier) — crayon / stylo.
  - **trash** ou **trash-2** (Supprimer / Retirer) — corbeille.
  - **plus** ou **plus-circle** (Créer / Ajouter) — signe plus.
  - **check** (Valider / Accepter) — déjà présent.
  - **x** ou **x-circle** (Refuser / Annuler) — croix.
- [x] Utiliser des SVG au format `<symbol id="…">` avec `viewBox="0 0 24 24"` pour homogénéité. Référence : [Lucide](https://lucide.dev/) ou [Heroicons](https://heroicons.com/) (stroke).

**Exemple d’usage dans une vue :**
```html
<button type="button" class="btn-icon" title="Voir" aria-label="Voir les détails">
  <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#eye"/></svg>
</button>
```

---

### Étape B2 — Styles CSS pour boutons icônes

**Fichier :** `public/css/styles.css` (ou fichier dashboard)

**Actions :**
- [x] Définir des classes utilitaires pour les boutons icônes : `.btn-icon`, `.btn-icon-danger`, `.action-buttons` (conteneur flex), taille d’icône (ex. `.icon-20` = 20px).
- [x] Style au survol et focus (accessibilité) pour les boutons icônes. Couleur distincte pour l’action « Supprimer » (ex. rouge/danger).
- [x] S’inspirer de l’ancien projet : `.action-btn.view`, `.action-btn.edit`, `.action-btn.delete`.

---

### Étape B3 — Admin : liste des articles

**Fichier :** `views/admin/articles.php`

**Actions :**
- [x] Remplacer le lien/bouton texte « Lire » par un **bouton icône œil** (`#eye`) avec `title="Voir"` / `aria-label="Voir les détails"` pointant vers la fiche article.
- [x] Optionnel : ajouter une icône **Modifier** (crayon) vers la fiche article si la fiche sert aussi à « modifier » le statut (sinon garder uniquement Voir).
- [x] Si l’étape A2 est faite : ajouter un bouton icône **Supprimer** (corbeille) avec confirmation, pointant vers POST delete.

---

### Étape B4 — Admin : liste des utilisateurs

**Fichier :** `views/admin/users.php`

**Actions :**
- [x] Remplacer le bouton/lien texte « Modifier » par un **bouton icône crayon** (`#edit` / `#pencil`) avec `title="Modifier"`.
- [x] Ajouter un **bouton icône œil** pour « Voir » (fiche utilisateur) si une page détail existe, sinon garder uniquement Modifier.
- [x] Si l’étape A1 est faite : ajouter un **bouton icône corbeille** (Supprimer) avec confirmation.
- [x] Le bouton « Créer un utilisateur » en en-tête (hors tableau) reste en **texte** — pas de conversion en icône.

---

### Étape B5 — Admin : liste des évaluations

**Fichier :** `views/admin/evaluations.php`

**Actions :**
- [x] Pour chaque ligne, ajouter une colonne **Actions** avec :
  - Icône **œil** (Voir) : lien vers la fiche article ou vers une page détail de l’évaluation (si elle existe).
  - Si l’étape A3 est faite : icône **Retirer** (corbeille ou « user-minus ») pour désassigner, avec confirmation.
- [x] Les liens actuels (titre article cliquable) peuvent rester ; les icônes en complément pour uniformiser.

---

### Étape B6 — Admin : paiements

**Fichier :** `views/admin/paiements.php`

**Actions :**
- [x] Remplacer les boutons texte « Valider » et « Refuser » par des **boutons icônes** : **check** (Valider) et **x** (Refuser), avec `title` et `aria-label` explicites.
- [x] Garder un libellé court au survol (tooltip) pour éviter toute ambiguïté.

---

### Étape B7 — Admin : comité éditorial

**Fichier :** `views/admin/comite-editorial.php`

**Actions :**
- [x] Remplacer « Modifier » par un **bouton icône crayon** (dans le tableau).
- [x] Remplacer « Retirer du comité » (ou équivalent) par un **bouton icône corbeille** avec confirmation (dans le tableau).
- [x] Le bouton « Ajouter un membre » en en-tête (hors tableau) reste en **texte** — pas de conversion en icône.

---

### Étape B8 — Admin : fiche article — tableau des évaluations uniquement

**Fichier :** `views/admin/article-detail.php`

**Périmètre :** Uniquement le **tableau** « Liste des évaluations » sur cette page. Le reste de la page (bouton « Supprimer l’article », formulaire « Changer le statut », formulaire « Assigner un évaluateur », etc.) reste en **texte** — pas de conversion en icônes.

**Actions :**
- [x] Dans le **tableau** des évaluations uniquement : colonne Actions avec **icône œil** (Voir, lien vers détail évaluation si existant) et **icône corbeille** (Retirer / désassigner, étape A3) avec confirmation.
- [x] Ne pas modifier les boutons et formulaires hors tableau (Supprimer l’article, Changer le statut, Assigner, Enregistrer).

---

### Étape B9 — Auteur : tableau « Mes articles »

**Fichier :** `views/author/index.php`

**Périmètre :** Uniquement le **tableau** des articles (colonnes Titre, Date, Statut, Workflow, Actions). Le bouton « Soumettre un article » en haut de page reste en **texte**.

**Actions :**
- [x] Dans le **tableau** uniquement : remplacer « Lire » par un **bouton icône œil** (lien vers fiche détail article).
- [x] Remplacer « Modifier » par un **bouton icône crayon** (lien vers édition).
- [x] Remplacer « Soumettre » (bouton par ligne dans le tableau, pour les brouillons) par un **bouton icône** (envoi/upload ou check) avec `title="Soumettre"`.
- [x] Garder `title` et `aria-label` sur chaque bouton icône du tableau.

---

### Étape B10 — Cohérence globale et i18n

**Actions :**
- [x] Vérifier que tous les libellés d’actions (tooltips, `aria-label`) passent par les clés i18n (`admin.view`, `admin.edit`, `admin.delete`, `common.read`, etc.) dans `lang/fr.php`, `en.php`, `ln.php`.
- [x] Documenter dans ce plan ou dans un README la convention : **Read = eye**, **Update = pencil/edit**, **Delete = trash**, **Create = plus**, **Validate = check**, **Refuse = x**.

**Convention des icônes (tableaux uniquement) :**

| Action        | Symbole SVG   | Clé i18n (ex.)        | Usage                          |
|---------------|---------------|------------------------|--------------------------------|
| Lire / Voir   | `#eye`        | `common.read`          | Fiche détail, lecture           |
| Modifier      | `#pencil`     | `admin.modify`, `author.edit_article` | Édition d'un enregistrement     |
| Supprimer / Retirer | `#trash` | `admin.delete`, `admin.unassign_evaluator`, `admin.comite_remove` | Suppression, désassignation    |
| Créer / Ajouter | `#plus`     | (boutons hors tableau en texte) | —                              |
| Valider / Soumettre | `#check`  | `admin.validate`, `author.submit_article_btn` | Validation, soumission         |
| Refuser      | `#x`          | `admin.refuse`         | Refus (ex. paiement)            |

Tous les boutons icônes dans les tableaux utilisent `title` et `aria-label` avec la valeur traduite via `__('clé')` pour l'accessibilité et le i18n (fr, en, ln).

---

## 5. Ordre suggéré et priorisation

| Priorité | Étape | Description |
|----------|--------|-------------|
| 1 | B1, B2 | Icônes SVG + CSS (prérequis pour le reste de la Phase B) |
| 2 | B3, B4, B9 | Icônes sur les listes les plus utilisées (articles admin, users admin, articles auteur) |
| 3 | A3 | Désassignation évaluateur (utile au quotidien) |
| 4 | B5, B6, B7, B8 | Icônes dans les tableaux : évaluations, paiements, comité ; tableau évaluations sur fiche article |
| 5 | A1, A2 | Suppression utilisateur et article (à cadrer avec la politique métier) |
| 6 | A4, A5 | Création volume et numéro (moins fréquent) |
| 7 | B10 | i18n et cohérence |

On peut traiter **d’abord la Phase B** (icônes) sur les écrans existants pour améliorer l’ergonomie, puis ajouter les **CRUD manquants** (Phase A) en réutilisant directement les mêmes icônes.

---

## 6. Fichiers concernés (référence)

| Fichier | Rôle |
|---------|------|
| `public/images/icons.svg` | Symboles SVG : eye, edit/pencil, trash, plus, check, x |
| `public/css/styles.css` | Classes `.btn-icon`, `.action-buttons`, couleurs danger |
| `views/admin/articles.php` | Liste articles : icônes Voir, évent. Supprimer |
| `views/admin/users.php` | Liste users : icônes Modifier, Voir, Supprimer, Créer |
| `views/admin/evaluations.php` | Liste évaluations : icônes Voir, Retirer |
| `views/admin/paiements.php` | Valider / Refuser en icônes |
| `views/admin/comite-editorial.php` | Modifier / Retirer en icônes |
| `views/admin/article-detail.php` | Uniquement le **tableau** des évaluations : icônes Voir, Retirer (pas les boutons hors tableau) |
| `views/author/index.php` | Liste articles auteur : icônes Lire, Modifier, Soumettre |
| `controllers/AdminController.php` | Méthodes userDelete, articleDelete, evaluationUnassign, volumeCreate/Store, numeroCreate/Store |
| `models/UserModel.php`, `ArticleModel.php`, `EvaluationModel.php`, `VolumeModel.php`, `RevueModel.php` | Méthodes delete / create selon les besoins |
| `routes/web.php` | Routes POST delete, unassign, GET/POST create volume/numero |
| `lang/fr.php`, `en.php`, `ln.php` | Clés pour tooltips et aria-label des actions |

---

**Rappel :** La conversion en icônes (Phase B) s’applique **uniquement aux actions CRUD dans les tableaux** (listes). Les boutons et formulaires des **pages de détail** restent en texte.

*Document à utiliser comme plan de travail : cocher les cases au fur et à mesure. Les étapes Phase B (icônes) peuvent être réalisées indépendamment des étapes Phase A (CRUD manquants) pour un gain visuel immédiat.*
