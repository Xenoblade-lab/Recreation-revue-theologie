# Plan de travail : minimum 2 évaluateurs et lien comité éditorial

Ce document décrit un **plan de travail par étapes** pour :
1. **Imposer au moins 2 évaluateurs** (ou plus) par article, pour ne pas avoir qu’un seul avis.
2. **Clarifier le lien entre évaluateurs et comité éditorial** : le comité éditorial est **déjà en place** sur le site (page publique « Comité éditorial » via `/comite`, lien dans la navigation) ; les évaluateurs (rôles rédacteur / rédacteur en chef) sont les membres de ce comité qui évaluent les articles.
3. **Garantir l’anonymat double aveugle** : l’évaluateur et l’auteur ne se connaissent qu’**après la publication** — réciproque : pas d’affichage de l’auteur tant que l’article n’est pas publié ; pas d’affichage des noms des évaluateurs côté auteur tant que l’article n’est pas publié. **Après publication** : l’auteur est affiché sur la page article ; l’auteur peut voir les noms des évaluateurs sur la fiche détail de son article.

On peut avancer **étape par étape** et valider chaque point avant de passer au suivant.

---

## Contexte actuel

- **Assignation** : l’admin peut assigner **un évaluateur à la fois** via le formulaire sur la fiche article. Il peut répéter l’action pour ajouter d’autres évaluateurs (pas de doublon article + évaluateur). Il n’y a **aucune contrainte** sur un nombre minimum.
- **Évaluateurs** : ce sont les utilisateurs dont le rôle est `redacteur` ou `redacteur en chef` (filtrés dans `AdminController::articleDetail`). Ils ont accès à l’espace « Évaluateur » et au formulaire d’évaluation.
- **Comité éditorial (déjà en place)** : le comité éditorial est **déjà présent sur le site** : page publique **« Comité éditorial »** accessible via la navigation (lien `/comite` dans le header et le footer). Cette page est alimentée par la table `revue_info` (champs `comite_redaction`, `comite_scientifique`) et affiche le directeur, le rédacteur en chef, les listes Comité de Rédaction et Comité Scientifique. Les **évaluateurs** dans le système (utilisateurs avec rôle `redacteur` ou `redacteur en chef`) sont les membres de ce même comité qui peuvent être assignés pour évaluer les articles — la liste affichée sur `/comite` et les comptes « évaluateurs » sont donc une seule et même réalité (comité éditorial), vue côté public (noms) et côté plateforme (comptes pour l’évaluation).

---

## Objectifs

| Objectif | Description |
|----------|-------------|
| **Minimum 2 évaluateurs** | S’assurer qu’un article ne peut pas être traité avec un seul avis : au moins 2 évaluateurs assignés (ou blocage / rappel si 1 seul). |
| **Lien comité / évaluateurs** | Le comité éditorial est déjà affiché sur le site (page `/comite`, navigation). Les évaluateurs = membres de ce comité (rôles rédacteur / rédacteur en chef) ; documenter et clarifier ce lien dans l’interface. |
| **Anonymat jusqu’à publication** | Réciproque : l’évaluateur ne connaît pas l’auteur, et l’auteur ne connaît pas les évaluateurs, tant que l’article n’est pas publié. **Après publication** : l’auteur est affiché sur la page article ; sur la fiche détail article côté auteur, les noms des évaluateurs peuvent être affichés (section « Commentaires des évaluateurs »). |

---

## Étape 1 — Afficher le nombre d’évaluateurs assignés et rappeler le minimum (sans bloquer)

**But :** Que l’admin voie clairement combien d’évaluateurs sont assignés et qu’il soit rappelé qu’il en faut au moins 2.

**Fichiers :**
- `views/admin/article-detail.php`

**Actions :**
- [x] Au-dessus du formulaire « Assigner un évaluateur », afficher une phrase du type : **« X évaluateur(s) assigné(s) pour cet article. Nous recommandons au moins 2 évaluateurs. »** (X = nombre d’évaluations pour cet article, par ex. `count($evaluations)`).
- [x] Si X &lt; 2, afficher un **message d’attention** (classe CSS type « alert » / « warning ») : « Un seul évaluateur est assigné. Veuillez en assigner au moins un second pour une évaluation en double aveugle. »
- [x] Ajouter les clés de traduction nécessaires dans `lang/fr.php` (et optionnellement `en.php`, `ln.php`), ex. : `admin.evaluators_assigned`, `admin.min_two_reviewers`, `admin.only_one_reviewer_warning`.

**Critère de validation :** Sur la fiche article, avec 0 ou 1 évaluateur assigné, le message s’affiche ; avec 2 ou plus, le rappel « au moins 2 » peut rester discret (recommandation).

---

## Étape 2 — Empêcher de passer en « Publié » / « Rejeté » tant qu’il n’y a pas au moins 2 évaluateurs (optionnel mais recommandé)

**But :** Ne pas permettre à l’admin de trancher (statut Publié ou Rejeté) tant que l’article n’a pas au moins 2 évaluateurs assignés (et idéalement au moins 2 évaluations soumises, selon la règle métier choisie).

**Fichiers :**
- `controllers/AdminController.php` (méthode `articleUpdateStatut`)
- `views/admin/article-detail.php` (formulaire « Changer le statut »)

**Actions :**
- [x] Dans `articleUpdateStatut`, avant d’accepter le passage à `valide` ou `rejete` :
  - Récupérer le nombre d’évaluations pour cet article : `EvaluationModel::getByArticleId($id)` puis compter.
  - (Optionnel) Compter seulement les évaluations **terminées** (`statut = 'termine'`).
  - Si le nombre &lt; 2 : ne pas mettre à jour le statut, rediriger vers la fiche article avec un message d’erreur en session (ex. « Veuillez assigner au moins 2 évaluateurs et attendre leurs rapports avant de publier ou rejeter. »).
- [x] Dans la vue, **désactiver ou masquer** les options « Publié » / « Rejeté » (ou tout le formulaire de changement de statut) lorsque le nombre d’évaluateurs assignés (ou d’évaluations terminées) &lt; 2, avec une courte explication : « Assignez au moins 2 évaluateurs avant de pouvoir publier ou rejeter. »

**Critère de validation :** Avec 0 ou 1 évaluateur (ou 0–1 évaluation terminée), l’admin ne peut pas choisir Publié ou Rejeté ; avec 2 ou plus, il peut.

---

## Gestion des avis divergents (ex. un évaluateur accepte, l’autre rejette)

**Question :** Que se passe-t-il si deux évaluateurs donnent des avis différents (ex. l’un « Accepté », l’autre « Rejeté ») ?

**Comportement actuel (nouveau et ancien projet) :**  
La décision finale (Publié / Rejeté) est **toujours prise manuellement** par l’administrateur (rédacteur en chef). Le système **ne calcule pas** automatiquement un statut à partir des recommandations. L’admin voit la liste des évaluations (tableau « Liste des évaluations » sur la fiche article) avec, pour chaque évaluateur, sa recommandation (Accepté, Rejeté, Révisions majeures, etc.) et peut lire les commentaires détaillés. C’est ensuite à lui de **trancher** : il choisit « Publié » ou « Rejeté » dans le formulaire « Changer le statut ».

**Comment le rédacteur en chef tranche-t-il ?**  
Il **ne réévalue pas** l’article dans le système (pas de formulaire d’évaluation supplémentaire à remplir). Il **se base sur les rapports existants** : il consulte la liste des évaluations, lit les recommandations, les commentaires pour l’éditeur et les notes de chaque évaluateur (via le lien « Voir » sur chaque ligne), puis choisit le statut final (Publié ou Rejeté) dans le formulaire « Changer le statut ». La décision est donc une **synthèse des avis** déjà rendus, pas une troisième évaluation technique.

**En résumé :**
- **Pas de règle automatique** du type « si un rejette alors rejeter » ou « majorité l’emporte ».
- **Pas de réévaluation** par le rédacteur en chef : il tranche en s’appuyant uniquement sur les rapports des évaluateurs.
- **Décision humaine** : le rédacteur en chef (ou le comité) prend la décision en tenant compte de tous les avis et des commentaires déjà présents.

**Amélioration possible (optionnel) :**  
Sur la fiche article admin, lorsqu’il y a au moins deux évaluations terminées et que les recommandations sont **divergentes** (ex. au moins un « Accepté » ou « Accepté avec modifications » et au moins un « Rejeté »), afficher un **message d’attention** du type : *« Avis divergents : X Accepté(s), Y Rejeté(s) [ou Révisions]. La décision finale revient au rédacteur en chef. »* — pour rappeler que c’est bien une décision manuelle et inciter à consulter les rapports avant de trancher.

**À ajouter dans le plan si vous retenez cette amélioration :**
- [x] Détecter les cas « avis divergents » (au moins une reco favorable et une défavorable).
- [x] Afficher un encart d’information au-dessus du formulaire « Changer le statut » dans ce cas.
- [x] Clé i18n ex. : `admin.conflicting_recommendations`.

---

## Étape 3 — Permettre d’assigner plusieurs évaluateurs en une fois (optionnel)

**But :** Réduire les allers-retours : assigner 2 ou 3 évaluateurs en une seule action.

**Fichiers :**
- `views/admin/article-detail.php` (formulaire d’assignation)
- `controllers/AdminController.php` (méthode `articleAssign` ou nouvelle route)
- `routes/web.php` (si nouvelle route)

**Actions :**
- [x] Changer le formulaire « Assigner un évaluateur » en **liste multi-sélection** (select multiple ou cases à cocher) avec la liste des évaluateurs (rédacteurs / rédacteur en chef), en excluant ceux déjà assignés à cet article.
- [x] Côté contrôleur : en POST, traiter une liste `evaluateur_ids[]` ; pour chaque id, appeler `EvaluationModel::assign($articleId, $evaluateurId)` (en ignorant les doublons déjà présents).
- [x] Afficher un message du type « X évaluateur(s) assigné(s) avec succès » et envoyer une notification à chaque nouvel évaluateur.

**Critère de validation :** L’admin peut sélectionner 2 ou 3 noms et soumettre une fois ; les 2 ou 3 évaluations sont créées et les notifs envoyées.

---

## Étape 4 — Documenter le lien « Évaluateurs = membres du comité éditorial »

**But :** Que ce soit clair pour les utilisateurs et les mainteneurs : les évaluateurs sont des membres du comité éditorial.

**Fichiers :**
- `docs/PROCESSUS_EVALUATION_ET_PUBLICATION.md` ou `docs/CRUD_ET_WORKFLOW_ANCIEN_NOUVEAU.md`
- Texte affiché côté admin (aide ou encart)

**Actions :**
- [x] Dans la doc (ou dans un encart sur la page Admin → Paramètres revue / Comité) : ajouter une phrase du type : **« Les évaluateurs (utilisateurs avec le rôle Rédacteur ou Rédacteur en chef) sont des membres du comité éditorial. Seuls ces comptes peuvent être assignés pour évaluer un article. »**
- [x] Sur la fiche article, à côté du bloc « Assigner un évaluateur », ajouter une **note courte** : « Les évaluateurs sont des membres du comité éditorial (rôle Rédacteur / Rédacteur en chef). »
- [x] Optionnel : sur la page publique **Comité éditorial**, ajouter une phrase : « Les évaluations des articles sont réalisées par des membres du comité éditorial (double aveugle). »

**Critère de validation :** La documentation et l’interface reflètent clairement que évaluateurs = comité éditorial.

---

## Étape 5 — Gérer la liste « Comité éditorial » côté admin (Option B recommandée)

**But :** Avoir une liste explicite des membres du comité pouvant évaluer, avec une vraie gestion (ajout / retrait, ordre).

**Options :**

- **Option A — Liste affichée uniquement**  
  - Dans Admin → Paramètres revue (ou une page « Comité »), afficher la liste des utilisateurs dont le rôle est `redacteur` ou `redacteur en chef`.  
  - Aucune nouvelle table : on s’appuie sur le rôle.

- **Option B — Table dédiée « membres du comité »** *(recommandée)*  
  - Créer une table du type `comite_editorial` (id, user_id, ordre, titre_affiché, actif, …).  
  - Seuls les utilisateurs présents dans cette table (et ayant un compte avec rôle rédacteur) sont proposés dans la liste « Assigner un évaluateur ».  
  - Page admin pour gérer la liste (ajouter / retirer des membres, ordre).  
  - Permet de distinguer clairement « membre du comité » (qui peut évaluer) du simple compte « rédacteur ».

**Recommandation :** **Option B** — meilleure traçabilité et gestion des évaluateurs = membres du comité.

**Actions (Option B) :**
- [x] Créer la table `comite_editorial` (id, user_id, ordre, titre_affiché, actif, created_at, updated_at).
- [x] Modèle `ComiteEditorialModel` (ou équivalent) : CRUD pour les membres du comité.
- [x] Page admin « Membres du comité » : liste, ajout (liaison user_id), ordre, activation/désactivation.
- [x] Dans la liste « Assigner un évaluateur » (fiche article admin), filtrer les évaluateurs proposés pour n’afficher que les membres du comité (présents dans `comite_editorial` avec actif = 1).
- [ ] Optionnel : synchroniser ou afficher cette liste avec la page publique `/comite` (texte `revue_info` ou export depuis la table).

**Critère de validation :** L’admin gère une liste explicite de membres du comité ; seuls ces membres sont proposés comme évaluateurs à l’assignation.

---

## Étape 6 — Anonymat double aveugle : évaluateur et auteur ne se connaissent qu’après la publication

**But :** Garantir que l’évaluateur ne connaît pas l’auteur (et réciproquement, si souhaité) tant que l’article n’est pas publié. Après publication, l’auteur est affiché sur la page de l’article ; on peut optionnellement afficher les noms des évaluateurs après publication.

**Constat actuel :**
- Dans l’espace évaluateur (formulaire d’évaluation), **aucun nom d’auteur** n’est affiché (les données passées viennent de `EvaluationModel::getByIdForReviewer` qui ne joint pas la table `users` auteur) — c’est déjà correct.
- En revanche, la **page publique** `/article/:id` affiche l’article **avec le nom de l’auteur** même lorsque l’article n’est **pas encore publié** (`statut !== 'valide'`). Un évaluateur qui clique sur « Voir la page article » peut donc découvrir l’auteur avant publication — à corriger.
- Le **téléchargement PDF** d’un article non publié est actuellement autorisé seulement pour l’auteur et l’admin ; les **évaluateurs assignés** ne sont pas autorisés. Il faut soit permettre aux évaluateurs assignés d’accéder au PDF via une route contrôlée (ex. `/download/article/:id` avec vérification « assigné à cet article »), soit s’assurer que le lien « Télécharger le manuscrit » dans l’espace évaluateur utilise bien une URL qui vérifie l’assignation.

**Fichiers :**
- `controllers/RevueController.php` : `articleDetails`, `downloadArticle`
- `views/public/article-details.php` (optionnel : masquer auteur si non publié, si on garde l’accès à la page)
- `views/reviewer/evaluation.php` : lien « Voir la page article » (à adapter ou supprimer pour les articles non publiés)

**Actions :**
- [x] **Page `/article/:id`** : n’afficher l’article que si `statut === 'valide'` (publié). Si l’article n’est pas publié, retourner **404** (ou une page « Article non disponible »). Ainsi, l’évaluateur qui clique sur « Voir la page article » pour un article en cours d’évaluation ne voit pas l’auteur.
- [x] **Lien « Voir la page article »** (côté évaluateur) : pour les articles non publiés, soit retirer ce lien, soit le garder mais la page renverra 404 — on peut ajouter une note du type « La page article sera visible après publication. » pour éviter la confusion.
- [x] **Téléchargement PDF** (`downloadArticle`) : pour les articles **non publiés**, autoriser en plus l’accès aux **utilisateurs assignés comme évaluateurs** pour cet article (vérifier dans la table `evaluations` que `article_id = :id` et `evaluateur_id = :userId`). Ainsi l’évaluateur peut télécharger le manuscrit sans passer par un lien direct vers un fichier (et sans voir l’auteur sur la page article).
- [x] **Côté auteur** : sur la page détail article de l’auteur, les noms des évaluateurs ne doivent être affichés **qu’après publication** (statut `valide`). Avant publication : afficher « Évaluateur » ou « Évaluateur 1 / 2 » sans nom. Après publication : afficher le nom de chaque évaluateur dans la section « Commentaires des évaluateurs » (voir étape 8).
- [x] **Manuscrit PDF** : si le fichier PDF déposé par l’auteur contient son nom, l’anonymat dépend du dépôt d’une version anonymisée par l’auteur. Indiquer dans les instructions aux auteurs (page soumission / politique) de fournir une version anonyme du manuscrit pour l’évaluation.

**Critère de validation :** Pour un article non publié, un évaluateur assigné ne peut pas voir l’auteur (page article = 404) ; il peut télécharger le manuscrit via l’interface. Après publication, la page article affiche l’auteur comme aujourd’hui.

---

## Étape 7 — Page détail article côté auteur : « Commentaires des évaluateurs » et « État du workflow »

**But :** Reproduire dans le nouveau projet les sections présentes dans l’ancien projet sur la fiche détail article de l’auteur : afficher les commentaires des évaluateurs (avec nom de l’évaluateur **uniquement après publication**) et l’état du workflow (Reçu → En évaluation → Révisions → Accepté → Publié). Ces blocs sont placés **juste après la section « Fichiers joints »**.

**Référence (ancien projet) :** `revue-ancien/views/author/article-details.php` — section « Commentaires des évaluateurs » (cartes par évaluation) et section « État du workflow » (stepper visuel).

**Fichiers :**
- `views/author/article-detail.php` (nouveau projet)
- `controllers/AuthorController.php` (méthode `articleDetail` : passer les évaluations et le statut article pour afficher ou non les noms)
- `models/EvaluationModel.php` (étendre les données renvoyées à l’auteur : suggestions, notes ; noms des évaluateurs uniquement si article publié)
- CSS (dashboard auteur ou global) pour les cartes d’évaluation et le stepper workflow

**Actions :**

1. **Données pour l’auteur**
   - [x] Étendre `EvaluationModel::getByArticleIdForAuthor` (ou ajouter une variante) pour retourner : `suggestions`, `qualite_scientifique`, `originalite`, `pertinence`, `clarte`, `note_finale`. Pour le **nom de l’évaluateur** : soit une méthode `getByArticleIdForAuthorWithEvaluatorNames(int $articleId)` utilisée uniquement quand l’article est publié, soit un paramètre `$includeEvaluatorNames` ; si `true`, joindre `users` et retourner `evaluateur_nom`, `evaluateur_prenom`.
   - [x] Dans `AuthorController::articleDetail`, récupérer les évaluations avec ou sans noms selon `$article['statut'] === 'valide'`, et passer à la vue : `evaluations`, `article` (déjà passé).

2. **Section « Commentaires des évaluateurs »** (après « Fichiers joints »)
   - [x] Afficher uniquement s’il existe au moins une évaluation (terminée ou avec contenu).
   - [x] Pour chaque évaluation : **en-tête** avec nom de l’évaluateur (si article publié) ou « Évaluateur » / « Évaluateur 1 » (si non publié), date de soumission (ex. 20 Feb 2026), **badge** de décision (Accepté / Accepté avec modifications / Révisions majeures requises / Rejeté).
   - [x] Puis : **Commentaires :** (texte `commentaires_public`), **Suggestions d’amélioration :** (texte `suggestions`), **Notes :** Qualité scientifique X/10, Originalité X/10, Pertinence X/10, Clarté X/10, Note finale X/10.
   - [x] Style : cartes (`.evaluation-card`) par évaluation, cohérent avec l’ancien projet / maquette fournie.

3. **Section « État du workflow »**
   - [x] Titre « État du workflow ».
   - [x] Stepper visuel : **Reçu** → **En évaluation** → **Révisions** → **Accepté** → **Publié**, avec indicateurs « complété » (coche verte) et « étape courante » (cercle bleu).
   - [x] Règles de progression à partir du `statut` de l’article (ex. `soumis` → Reçu complété ; `en_evaluation` ou évaluations présentes → En évaluation complété ; `revision_requise` ou au-delà → Révisions complétées ; `accepte` ou `valide` → Accepté complété ; `valide` → Publié complété). S’inspirer de la logique dans `revue-ancien/views/author/article-details.php` (lignes 261–365).
   - [x] CSS pour `.workflow-steps`, `.workflow-step`, `.workflow-step.completed`, `.workflow-step.current`, `.workflow-arrow` (réutiliser ou adapter `revue-ancien/public/css/dashboard-styles.css`).

4. **Traductions**
   - [x] Clés i18n pour « Commentaires des évaluateurs », « État du workflow », libellés des recommandations (Accepté, Révisions majeures requises, etc.), « Qualité scientifique », « Originalité », « Pertinence », « Clarté », « Note finale », « Commentaires », « Suggestions d’amélioration », noms des étapes (Reçu, En évaluation, Révisions, Accepté, Publié).

**Critère de validation :** Sur la page détail d’un article côté auteur, après « Fichiers joints », la section « Commentaires des évaluateurs » affiche pour chaque évaluation les champs prévus (nom de l’évaluateur seulement si article publié), et la section « État du workflow » affiche le stepper avec les étapes complétées et l’étape courante.

---

## Étape 8 — Tests et vérifications

**But :** S’assurer que le flux éditorial respecte « au moins 2 évaluateurs », que le double aveugle est garanti jusqu’à la publication, que la page détail auteur affiche bien commentaires et workflow, et que la doc est à jour.

**Actions :**
- [x] Scénario 1 : article avec 0 évaluateur → message rappel « au moins 2 » ; impossibilité (si étape 2 faite) de publier/rejeter.
- [x] Scénario 2 : article avec 1 évaluateur → même chose.
- [x] Scénario 3 : article avec 2 évaluateurs (et si étape 2 : 2 évaluations terminées) → l’admin peut passer en Publié ou Rejeté.
- [x] Vérifier que les notifications sont bien envoyées à chaque évaluateur assigné.
- [x] Double aveugle : article non publié → page `/article/:id` renvoie 404 ; évaluateur assigné peut télécharger le PDF ; après publication, la page article affiche l’auteur ; côté auteur, noms des évaluateurs visibles seulement après publication (étape 7).
- [x] Page détail auteur : sections « Commentaires des évaluateurs » et « État du workflow » affichées correctement (étape 7).
- [x] Relire la doc (processus d’évaluation, workflow éditorial) et la mettre à jour si besoin (mention « minimum 2 évaluateurs », « évaluateurs = comité », « anonymat jusqu’à publication »).

---

## Résumé des étapes (ordre suggéré)

| # | Étape | Priorité | Blocage métier ? |
|---|--------|----------|-------------------|
| 1 | Afficher le nombre d’évaluateurs + rappel « au moins 2 » | Haute | Non |
| 2 | Bloquer Publié/Rejeté si &lt; 2 évaluateurs (ou &lt; 2 évaluations terminées) | Haute | Oui |
| 3 | Assignation multiple en une fois | Moyenne | Non |
| 4 | Documenter « évaluateurs = comité éditorial » (et rappel : comité déjà à l’accueil / page `/comite`) | Haute | Non |
| 5 | Gestion « comité » côté admin — **Option B** (table dédiée) | Moyenne | Non |
| 6 | **Anonymat double aveugle** : évaluateur et auteur ne se connaissent qu’après publication (réciproque) | Haute | Oui |
| 7 | **Page détail article auteur** : Commentaires des évaluateurs + État du workflow (après Fichiers joints) | Haute | Non |
| 8 | Tests et relecture doc | Haute | — |

On peut **travailler étape par étape** : valider 1, 2, 4, 6 (double aveugle), puis **7** (commentaires + workflow côté auteur), puis 3 et 5 si besoin. Dès les étapes 1, 2, 4, 6 et 7, le « minimum 2 évaluateurs », le lien comité, l’anonymat jusqu’à publication et l’affichage auteur sont en place.

---

## Fichiers concernés (référence)

| Fichier | Rôle |
|---------|------|
| `controllers/AdminController.php` | `articleDetail` (données), `articleUpdateStatut` (contrôle minimum 2), `articleAssign` (assignation simple ou multiple) |
| `views/admin/article-detail.php` | Affichage du nombre d’évaluateurs, message rappel, formulaire assignation (simple ou multi), désactivation Publié/Rejeté si &lt; 2 |
| `models/EvaluationModel.php` | `getByArticleId`, `assign` (déjà prêt pour plusieurs assignations) |
| `lang/fr.php` (et en, ln) | Clés pour messages « X évaluateurs assignés », « au moins 2 », « évaluateurs = comité » |
| `controllers/RevueController.php` | `articleDetails` (404 si non publié), `downloadArticle` (autoriser évaluateur assigné pour non publié) |
| `views/public/article-details.php` | Auteur affiché uniquement si article publié (si on garde une page « article non disponible ») |
| `controllers/AuthorController.php` | `articleDetail` : passer `evaluations` (avec ou sans noms selon statut) |
| `views/author/article-detail.php` | Sections « Commentaires des évaluateurs » et « État du workflow » après Fichiers joints |
| `models/EvaluationModel.php` | Données auteur : suggestions, notes ; noms évaluateurs si article publié |
| CSS (dashboard auteur / global) | Styles `.workflow-steps`, `.evaluation-card`, etc. |
| `docs/PROCESSUS_EVALUATION_ET_PUBLICATION.md` | Mention « minimum 2 évaluateurs », « évaluateurs = comité », « anonymat jusqu’à publication » |
| `docs/CRUD_ET_WORKFLOW_ANCIEN_NOUVEAU.md` | Optionnel : rappel workflow éditorial « au moins 2 évaluateurs » |

---

*Document à utiliser comme plan de travail : cocher les cases au fur et à mesure et avancer étape par étape.*
