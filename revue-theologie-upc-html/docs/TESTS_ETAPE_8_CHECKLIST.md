# Checklist — Étape 8 : Tests et vérifications

Ce document permet de valider manuellement les scénarios de l’étape 8 du plan « minimum 2 évaluateurs et comité éditorial ».

---

## 1. Minimum 2 évaluateurs (étapes 1 et 2)

| # | Scénario | Action à tester | Résultat attendu | ☐ |
|---|----------|------------------|-----------------|---|
| 1.1 | Article avec **0 évaluateur** | Ouvrir la fiche article (admin), regarder le bloc « Assigner un évaluateur » et le formulaire « Changer le statut ». | Message du type « 0 évaluateur(s) assigné(s) », rappel « au moins 2 » ; options « Publié » et « Rejeté » désactivées ou masquées avec explication. | |
| 1.2 | Article avec **1 évaluateur** | Idem avec un seul évaluateur assigné. | Message d’attention « Un seul évaluateur… », options Publié/Rejeté désactivées. Tenter de forcer le statut (ex. via outil) : redirection + message d’erreur. | |
| 1.3 | Article avec **2 évaluateurs** (et si possible 2 évaluations terminées) | Assigner 2 évaluateurs, (optionnel) soumettre 2 évaluations, puis ouvrir « Changer le statut ». | Options « Publié » et « Rejeté » disponibles ; l’admin peut choisir Publié ou Rejeté et enregistrer. | |

---

## 2. Notifications

| # | Scénario | Action à tester | Résultat attendu | ☐ |
|---|----------|------------------|-----------------|---|
| 2.1 | Assignation multiple | Assigner 2 ou 3 évaluateurs en une fois (cases à cocher). | Message « X évaluateur(s) assigné(s) avec succès » ; chaque nouvel évaluateur reçoit une notification (vérifier espace évaluateur ou table/queue de notifications). | |

---

## 3. Double aveugle (étape 6)

| # | Scénario | Action à tester | Résultat attendu | ☐ |
|---|----------|------------------|-----------------|---|
| 3.1 | Article **non publié** — page publique | Avec un article en `soumis` ou `en_evaluation`, ouvrir en navigation privée ou déconnecté : `/article/:id` (URL publique). | Page 404 ou « Article non disponible » (pas d’affichage de l’article ni de l’auteur). | |
| 3.2 | Évaluateur assigné — téléchargement PDF | Connecté en tant qu’évaluateur assigné à un article non publié, utiliser le lien « Télécharger le manuscrit » depuis l’espace évaluateur (page d’évaluation). | Le PDF se télécharge (route `/download/article/:id` autorise l’évaluateur assigné). | |
| 3.3 | Après publication — page article | Mettre l’article en `valide`, puis ouvrir `/article/:id` en public. | La page article s’affiche avec le nom de l’auteur. | |
| 3.4 | Côté auteur — noms évaluateurs | En tant qu’auteur : ouvrir la fiche détail d’un article **non publié** ayant des évaluations. Puis publier l’article et rafraîchir la même fiche. | Avant publication : section « Commentaires des évaluateurs » affiche « Évaluateur 1 », « Évaluateur 2 » (pas de noms). Après publication : les noms des évaluateurs apparaissent. | |

---

## 4. Page détail auteur — Commentaires et workflow (étape 7)

| # | Scénario | Action à tester | Résultat attendu | ☐ |
|---|----------|------------------|-----------------|---|
| 4.1 | Section « Commentaires des évaluateurs » | Ouvrir la fiche détail d’un article (auteur) qui a au moins une évaluation (terminée ou avec contenu). | Section visible après « Fichiers joints » ; pour chaque évaluation : en-tête (nom ou « Évaluateur N »), date, badge de décision ; commentaires, suggestions, notes (qualité, originalité, pertinence, clarté, note finale). | |
| 4.2 | Section « État du workflow » | Ouvrir la fiche détail d’un article (auteur) avec différents statuts (ex. soumis, en_evaluation, revision_requise, valide). | Stepper affiché : Reçu → En évaluation → Révisions → Accepté → Publié ; étapes complétées (coche verte), étape courante (cercle), étapes en attente selon le statut. | |

---

## 5. Documentation

| # | Action | Résultat attendu | ☐ |
|---|--------|-----------------|---|
| 5.1 | Relire `docs/PROCESSUS_EVALUATION_ET_PUBLICATION.md`. | La section « Règles métier — nouveau projet » (ou équivalent) mentionne : minimum 2 évaluateurs, évaluateurs = comité éditorial, anonymat jusqu’à publication. | |
| 5.2 | Optionnel : `docs/CRUD_ET_WORKFLOW_ANCIEN_NOUVEAU.md`. | Si pertinent, rappel du workflow « au moins 2 évaluateurs » et du double aveugle. | |

---

*Une fois tous les scénarios validés, cocher les actions de l’étape 8 dans `PLAN_MINIMUM_2_EVALUATEURS_ET_COMITE.md`.*
