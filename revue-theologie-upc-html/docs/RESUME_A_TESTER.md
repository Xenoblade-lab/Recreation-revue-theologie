# Résumé — À tester (plan minimum 2 évaluateurs et comité)

Ce document liste les **nouveautés à vérifier** après les mises en œuvre des étapes 1 à 8.

---

## 1. Admin — Fiche article

### Nombre d’évaluateurs et rappel « au moins 2 »
- [ ] Sur la fiche article (admin), au-dessus du bloc « Assigner un évaluateur », une phrase indique **« X évaluateur(s) assigné(s)… Nous recommandons au moins 2 évaluateurs »**.
- [ ] S’il n’y a que **0 ou 1** évaluateur assigné, un **message d’attention** (encart orange) s’affiche : « Un seul évaluateur est assigné. Veuillez en assigner au moins un second… ».

### Blocage Publié / Rejeté si &lt; 2 évaluateurs
- [ ] Avec **0 ou 1** évaluateur : les options **« Publié »** et **« Rejeté »** dans le formulaire « Changer le statut » sont **désactivées** (ou masquées), avec le texte « Assignez au moins 2 évaluateurs avant de pouvoir publier ou rejeter ».
- [ ] Si on tente de forcer le statut (ex. requête POST directe) avec &lt; 2 évaluateurs : **redirection** vers la fiche article + **message d’erreur** en session.
- [ ] Avec **2 évaluateurs ou plus** : les options Publié et Rejeté sont **actives**, l’admin peut enregistrer.

### Avis divergents
- [ ] Quand il y a **au moins 2 évaluations terminées** et des avis **mixtes** (au moins un favorable : Accepté / Accepté avec modif. / Révisions mineures, et au moins un défavorable : Rejeté / Révisions majeures), un **encart d’information** (orange) s’affiche **au-dessus** du formulaire « Changer le statut » : *« Avis divergents : X favorable(s), Y défavorable(s). La décision finale revient au rédacteur en chef. »*.

### Assignation multiple
- [ ] Le formulaire « Assigner un évaluateur » propose des **cases à cocher** (plus un seul menu déroulant).
- [ ] On peut **sélectionner plusieurs** évaluateurs et soumettre une fois : tous sont assignés, message « X évaluateur(s) assigné(s) avec succès », et **notification** envoyée à chaque nouvel évaluateur.
- [ ] Les évaluateurs **déjà assignés** à cet article n’apparaissent pas dans la liste (ou sont exclus).

### Lien comité éditorial
- [ ] Une **note** à côté du bloc assignation indique que les évaluateurs sont des membres du comité éditorial (rôle Rédacteur / Rédacteur en chef).

---

## 2. Admin — Comité éditorial (si table `comite_editorial` en place)

- [ ] Un lien **« Comité éditorial »** existe dans le menu admin (sidebar).
- [ ] La page liste les **membres du comité** (ordre, nom, titre affiché, actif/inactif) avec actions Modifier / Retirer.
- [ ] On peut **ajouter** un membre (choix d’un utilisateur, ordre, titre, actif).
- [ ] Dans la liste « Assigner un évaluateur » sur la fiche article, seuls les **membres actifs du comité** sont proposés (si la table existe).

---

## 3. Double aveugle et anonymat

### Page article publique
- [ ] Pour un article **non publié** (`statut` ≠ `valide`) : en ouvrant **`/article/:id`** (en navigation privée ou déconnecté), on obtient une **404** ou une page « Article non disponible » (pas de nom d’auteur, pas d’article).

### Évaluateur
- [ ] Dans l’espace évaluateur, sur la page d’évaluation d’un article, **aucun nom d’auteur** n’est affiché.
- [ ] Le lien **« Télécharger le manuscrit »** fonctionne et télécharge le PDF via une route contrôlée (l’évaluateur assigné a bien accès).
- [ ] Une note indique que la page article publique sera visible **après publication**.

### Après publication
- [ ] Une fois l’article **publié** (`valide`), la page publique **`/article/:id`** affiche l’article **avec le nom de l’auteur**.

### Instructions / politique
- [ ] Page **Instructions aux auteurs** : une section **« Manuscrit anonyme »** précise que le fichier soumis doit être une **version anonyme** (pas de nom ni d’éléments d’identification dans le fichier).
- [ ] Page **Politique éditoriale** : le paragraphe sur l’évaluation mentionne que les auteurs doivent fournir une **version anonyme** du manuscrit.

---

## 4. Auteur — Fiche détail article

### Commentaires des évaluateurs
- [ ] **Après** la section « Fichiers joints », une section **« Commentaires des évaluateurs »** apparaît s’il y a au moins une évaluation.
- [ ] Pour chaque évaluation : **avant publication** de l’article → affichage « Évaluateur 1 », « Évaluateur 2 » (pas de noms) ; **après publication** → affichage des **noms** des évaluateurs.
- [ ] Pour chaque évaluation : date, **badge** de décision (Accepté, Accepté avec modif., Révisions majeures requises, Rejeté), **Commentaires**, **Suggestions d’amélioration**, **Notes** (Qualité scientifique, Originalité, Pertinence, Clarté, Note finale /10).

### État du workflow
- [ ] Une section **« État du workflow »** affiche un **stepper** : **Reçu → En évaluation → Révisions → Accepté → Publié**.
- [ ] Les étapes sont marquées **complétées** (✓), **courante** (•) ou **en attente** (◦) selon le statut de l’article (brouillon, soumis, en évaluation, révision requise, accepté, publié, rejeté).

---

## 5. Récap rapide par rôle

| Rôle      | Où tester | À vérifier |
|-----------|-----------|------------|
| **Admin** | Fiche article | Nombre d’évaluateurs, rappel « au moins 2 », blocage Publié/Rejeté si &lt; 2, encart avis divergents, assignation multiple (cases à cocher), note comité. |
| **Admin** | Menu + page Comité éditorial | Liste, ajout, modification, retrait de membres ; liste assignation = membres comité si table utilisée. |
| **Évaluateur** | Page évaluation | Pas de nom d’auteur, téléchargement manuscrit OK, note « page article après publication ». |
| **Auteur** | Fiche détail article | Sections Commentaires des évaluateurs (noms après publication) et État du workflow (stepper). |
| **Public** | `/article/:id` | 404 si non publié ; affichage avec auteur si publié. |
| **Public** | Instructions / Politique | Mention manuscrit anonyme. |

---

*Pour une checklist détaillée scénario par scénario, voir `docs/TESTS_ETAPE_8_CHECKLIST.md`.*
