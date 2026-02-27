# Plan de travail : minimum 2 évaluateurs et lien comité éditorial

Ce document décrit un **plan de travail par étapes** pour :
1. **Imposer au moins 2 évaluateurs** (ou plus) par article, pour ne pas avoir qu’un seul avis.
2. **Clarifier le lien entre évaluateurs et comité éditorial** (les évaluateurs font partie du comité).

On peut avancer **étape par étape** et valider chaque point avant de passer au suivant.

---

## Contexte actuel

- **Assignation** : l’admin peut assigner **un évaluateur à la fois** via le formulaire sur la fiche article. Il peut répéter l’action pour ajouter d’autres évaluateurs (pas de doublon article + évaluateur). Il n’y a **aucune contrainte** sur un nombre minimum.
- **Évaluateurs** : ce sont les utilisateurs dont le rôle est `redacteur` ou `redacteur en chef` (filtrés dans `AdminController::articleDetail`). Ils ont accès à l’espace « Évaluateur » et au formulaire d’évaluation.
- **Comité éditorial** : la page publique « Comité éditorial » affiche le texte stocké dans `revue_info` (comité de rédaction, comité scientifique). Les **noms** du comité sont dans ce texte ; les **comptes évaluateurs** (rédacteurs) ne sont pas liés explicitement à cette liste dans la base.

---

## Objectifs

| Objectif | Description |
|----------|-------------|
| **Minimum 2 évaluateurs** | S’assurer qu’un article ne peut pas être traité avec un seul avis : au moins 2 évaluateurs assignés (ou blocage / rappel si 1 seul). |
| **Lien comité / évaluateurs** | Faire en sorte que les évaluateurs soient clairement considérés comme membres du comité éditorial (documentation, interface, ou gestion dédiée). |

---

## Étape 1 — Afficher le nombre d’évaluateurs assignés et rappeler le minimum (sans bloquer)

**But :** Que l’admin voie clairement combien d’évaluateurs sont assignés et qu’il soit rappelé qu’il en faut au moins 2.

**Fichiers :**
- `views/admin/article-detail.php`

**Actions :**
- [ ] Au-dessus du formulaire « Assigner un évaluateur », afficher une phrase du type : **« X évaluateur(s) assigné(s) pour cet article. Nous recommandons au moins 2 évaluateurs. »** (X = nombre d’évaluations pour cet article, par ex. `count($evaluations)`).
- [ ] Si X &lt; 2, afficher un **message d’attention** (classe CSS type « alert » / « warning ») : « Un seul évaluateur est assigné. Veuillez en assigner au moins un second pour une évaluation en double aveugle. »
- [ ] Ajouter les clés de traduction nécessaires dans `lang/fr.php` (et optionnellement `en.php`, `ln.php`), ex. : `admin.evaluators_assigned`, `admin.min_two_reviewers`, `admin.only_one_reviewer_warning`.

**Critère de validation :** Sur la fiche article, avec 0 ou 1 évaluateur assigné, le message s’affiche ; avec 2 ou plus, le rappel « au moins 2 » peut rester discret (recommandation).

---

## Étape 2 — Empêcher de passer en « Publié » / « Rejeté » tant qu’il n’y a pas au moins 2 évaluateurs (optionnel mais recommandé)

**But :** Ne pas permettre à l’admin de trancher (statut Publié ou Rejeté) tant que l’article n’a pas au moins 2 évaluateurs assignés (et idéalement au moins 2 évaluations soumises, selon la règle métier choisie).

**Fichiers :**
- `controllers/AdminController.php` (méthode `articleUpdateStatut`)
- `views/admin/article-detail.php` (formulaire « Changer le statut »)

**Actions :**
- [ ] Dans `articleUpdateStatut`, avant d’accepter le passage à `valide` ou `rejete` :
  - Récupérer le nombre d’évaluations pour cet article : `EvaluationModel::getByArticleId($id)` puis compter.
  - (Optionnel) Compter seulement les évaluations **terminées** (`statut = 'termine'`).
  - Si le nombre &lt; 2 : ne pas mettre à jour le statut, rediriger vers la fiche article avec un message d’erreur en session (ex. « Veuillez assigner au moins 2 évaluateurs et attendre leurs rapports avant de publier ou rejeter. »).
- [ ] Dans la vue, **désactiver ou masquer** les options « Publié » / « Rejeté » (ou tout le formulaire de changement de statut) lorsque le nombre d’évaluateurs assignés (ou d’évaluations terminées) &lt; 2, avec une courte explication : « Assignez au moins 2 évaluateurs avant de pouvoir publier ou rejeter. »

**Critère de validation :** Avec 0 ou 1 évaluateur (ou 0–1 évaluation terminée), l’admin ne peut pas choisir Publié ou Rejeté ; avec 2 ou plus, il peut.

---

## Étape 3 — Permettre d’assigner plusieurs évaluateurs en une fois (optionnel)

**But :** Réduire les allers-retours : assigner 2 ou 3 évaluateurs en une seule action.

**Fichiers :**
- `views/admin/article-detail.php` (formulaire d’assignation)
- `controllers/AdminController.php` (méthode `articleAssign` ou nouvelle route)
- `routes/web.php` (si nouvelle route)

**Actions :**
- [ ] Changer le formulaire « Assigner un évaluateur » en **liste multi-sélection** (select multiple ou cases à cocher) avec la liste des évaluateurs (rédacteurs / rédacteur en chef), en excluant ceux déjà assignés à cet article.
- [ ] Côté contrôleur : en POST, traiter une liste `evaluateur_ids[]` ; pour chaque id, appeler `EvaluationModel::assign($articleId, $evaluateurId)` (en ignorant les doublons déjà présents).
- [ ] Afficher un message du type « X évaluateur(s) assigné(s) avec succès » et envoyer une notification à chaque nouvel évaluateur.

**Critère de validation :** L’admin peut sélectionner 2 ou 3 noms et soumettre une fois ; les 2 ou 3 évaluations sont créées et les notifs envoyées.

---

## Étape 4 — Documenter le lien « Évaluateurs = membres du comité éditorial »

**But :** Que ce soit clair pour les utilisateurs et les mainteneurs : les évaluateurs sont des membres du comité éditorial.

**Fichiers :**
- `docs/PROCESSUS_EVALUATION_ET_PUBLICATION.md` ou `docs/CRUD_ET_WORKFLOW_ANCIEN_NOUVEAU.md`
- Texte affiché côté admin (aide ou encart)

**Actions :**
- [ ] Dans la doc (ou dans un encart sur la page Admin → Paramètres revue / Comité) : ajouter une phrase du type : **« Les évaluateurs (utilisateurs avec le rôle Rédacteur ou Rédacteur en chef) sont des membres du comité éditorial. Seuls ces comptes peuvent être assignés pour évaluer un article. »**
- [ ] Sur la fiche article, à côté du bloc « Assigner un évaluateur », ajouter une **note courte** : « Les évaluateurs sont des membres du comité éditorial (rôle Rédacteur / Rédacteur en chef). »
- [ ] Optionnel : sur la page publique **Comité éditorial**, ajouter une phrase : « Les évaluations des articles sont réalisées par des membres du comité éditorial (double aveugle). »

**Critère de validation :** La documentation et l’interface reflètent clairement que évaluateurs = comité éditorial.

---

## Étape 5 — (Optionnel) Gérer la liste « Comité éditorial » côté admin et la lier aux comptes évaluateurs

**But :** Avoir une liste explicite des membres du comité pouvant évaluer (affichée ou utilisée pour filtrer les évaluateurs).

**Options possibles :**

- **Option A — Liste affichée uniquement**  
  - Dans Admin → Paramètres revue (ou une page « Comité »), afficher la liste des utilisateurs dont le rôle est `redacteur` ou `redacteur en chef`, avec la mention « Ces comptes sont les évaluateurs (membres du comité éditorial). »  
  - Aucune nouvelle table : on s’appuie sur le rôle.

- **Option B — Table dédiée « membres du comité »**  
  - Créer une table du type `comite_editorial` (id, user_id, ordre, titre_affiché, actif, …).  
  - Seuls les utilisateurs présents dans cette table (et ayant un compte avec rôle rédacteur) sont proposés dans la liste « Assigner un évaluateur ».  
  - Page admin pour gérer la liste (ajouter / retirer des membres, ordre).  
  - Plus de travail (migrations, CRUD, formulaire).

**Recommandation pour commencer :** Option A (affichage des comptes évaluateurs = comité), puis Option B seulement si besoin de distinguer « membre du comité » de « peut évaluer ».

**Actions (Option A) :**
- [ ] Créer une section ou une page Admin « Membres du comité (évaluateurs) » qui liste les utilisateurs avec rôle `redacteur` ou `redacteur en chef` (lecture seule ou avec lien vers édition utilisateur).
- [ ] Ajouter un lien depuis la page « Comité éditorial » (public) ou depuis Admin → Paramètres : « Gérer les comptes évaluateurs (membres du comité) » → cette liste.

**Critère de validation :** L’admin voit clairement qui sont les membres du comité pouvant être assignés comme évaluateurs.

---

## Étape 6 — Tests et vérifications

**But :** S’assurer que le flux éditorial respecte bien « au moins 2 évaluateurs » et que la doc est à jour.

**Actions :**
- [ ] Scénario 1 : article avec 0 évaluateur → message rappel « au moins 2 » ; impossibilité (si étape 2 faite) de publier/rejeter.
- [ ] Scénario 2 : article avec 1 évaluateur → même chose.
- [ ] Scénario 3 : article avec 2 évaluateurs (et si étape 2 : 2 évaluations terminées) → l’admin peut passer en Publié ou Rejeté.
- [ ] Vérifier que les notifications sont bien envoyées à chaque évaluateur assigné.
- [ ] Relire la doc (processus d’évaluation, workflow éditorial) et la mettre à jour si besoin (mention « minimum 2 évaluateurs », « évaluateurs = comité éditorial »).

---

## Résumé des étapes (ordre suggéré)

| # | Étape | Priorité | Blocage métier ? |
|---|--------|----------|-------------------|
| 1 | Afficher le nombre d’évaluateurs + rappel « au moins 2 » | Haute | Non |
| 2 | Bloquer Publié/Rejeté si &lt; 2 évaluateurs (ou &lt; 2 évaluations terminées) | Haute | Oui |
| 3 | Assignation multiple en une fois | Moyenne | Non |
| 4 | Documenter « évaluateurs = comité éditorial » | Haute | Non |
| 5 | Liste / gestion « comité » côté admin (Option A ou B) | Basse / optionnel | Non |
| 6 | Tests et relecture doc | Haute | — |

On peut **travailler étape par étape** : valider l’étape 1, puis 2, puis 4, puis 3 et 5 si besoin. Dès les étapes 1 et 2 (et 4), le « minimum 2 évaluateurs » et le lien comité sont en place.

---

## Fichiers concernés (référence)

| Fichier | Rôle |
|---------|------|
| `controllers/AdminController.php` | `articleDetail` (données), `articleUpdateStatut` (contrôle minimum 2), `articleAssign` (assignation simple ou multiple) |
| `views/admin/article-detail.php` | Affichage du nombre d’évaluateurs, message rappel, formulaire assignation (simple ou multi), désactivation Publié/Rejeté si &lt; 2 |
| `models/EvaluationModel.php` | `getByArticleId`, `assign` (déjà prêt pour plusieurs assignations) |
| `lang/fr.php` (et en, ln) | Clés pour messages « X évaluateurs assignés », « au moins 2 », « évaluateurs = comité » |
| `docs/PROCESSUS_EVALUATION_ET_PUBLICATION.md` | Mention processus « minimum 2 évaluateurs » et « évaluateurs = comité » |
| `docs/CRUD_ET_WORKFLOW_ANCIEN_NOUVEAU.md` | Optionnel : rappel workflow éditorial « au moins 2 évaluateurs » |

---

*Document à utiliser comme plan de travail : cocher les cases au fur et à mesure et avancer étape par étape.*
