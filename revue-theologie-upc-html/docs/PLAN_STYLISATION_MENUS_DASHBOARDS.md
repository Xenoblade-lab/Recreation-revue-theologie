# Plan : stylisation des menus d’option (select / listes déroulantes) des 3 dashboards

**Objectif :** Styliser les listes déroulantes (`<select>`) des formulaires dans les espaces Admin, Auteur et Évaluateur pour qu’elles soient cohérentes avec le reste du design (bordures, couleurs, focus, options au survol) et plus agréables à l’usage que l’apparence native du navigateur.

**Périmètre :** Tous les `<select>` utilisés dans les dashboards, par exemple :
- **Évaluateur :** « Recommandation * » (Choisir, Accepté, Révisions mineures/majeures requises, Rejeté), et tout autre select dans les formulaires.
- **Admin :** statut article, numéro de parution, rôle/utilisateur dans les formulaires, filtre statut évaluations, etc.
- **Auteur :** tout select dans les formulaires (soumission, profil, etc.).

---

## État actuel

- Les `<select>` utilisent la classe `input` (et souvent `input w-full`) mais il n’existe pas de règle CSS dédiée à `.input` ; les champs texte/textarea ont des styles globaux (padding, bordure, radius).
- Les `select` ne sont pas inclus dans les mêmes règles que `input[type="text"]` et `textarea` dans la section « Forms » de `styles.css`, donc ils gardent l’apparence **native** du navigateur (gris, flèche par défaut, options basiques).
- Fichiers concernés : `views/reviewer/evaluation.php` (Recommandation), `views/admin/article-detail.php`, `views/admin/user-form.php`, `views/admin/evaluations.php`, `views/admin/comite-editorial-form.php`, `views/admin/numero-form.php`, etc.

---

## Phase 1 — Styles de base pour tous les select (dashboard + formulaires publics)

- [x] **1.1** Dans `styles.css`, ajouter des règles pour les `select` afin de les aligner visuellement sur les champs texte :
  - Même famille de police, taille (ex. 0.875rem), padding (ex. 0.5rem 0.75rem), hauteur minimale si besoin.
  - Bordure (ex. 1px solid var(--input) ou var(--border)), border-radius (var(--radius)), fond (var(--background)).
  - Couleur du texte (var(--foreground)).
- [x] **1.2** Inclure les `select` dans les règles de **focus** (outline ou box-shadow avec var(--ring)) pour l’accessibilité clavier.
- [x] **1.3** S’assurer que les select à l’intérieur de `.dashboard-card` (et éventuellement `.form-group`) héritent de ces styles et de `max-width: 100%` / `box-sizing: border-box` pour le responsive.

---

## Phase 2 — Flèche du select (apparence personnalisée)

- [x] **2.1** Masquer la flèche native du navigateur (appearance: none ou -webkit-appearance: none, -moz-appearance: none) et définir un cursor: pointer.
- [x] **2.2** Ajouter une flèche personnalisée (icône chevron vers le bas) via background-image (SVG ou image) ou un pseudo-élément, positionnée à droite du champ, avec padding-right suffisant pour que le texte ne passe pas sous la flèche.
- [x] **2.3** Vérifier l’affichage sur les navigateurs courants (Chrome, Firefox, Safari, Edge).

---

## Phase 3 — Liste des options (dropdown ouvert)

- [x] **3.1** Les options (`<option>`) à l’intérieur d’un `<select>` ont une apparence très limitée en CSS (selon le navigateur). Documenter les options :
  - Style possible sur `option` (couleur de fond, couleur de texte, padding selon navigateur).
  - Option « Rejeté » ou états négatifs : possibilité de couleur accent (rouge) pour certaines options si le navigateur le permet.
- [x] **3.2** Si besoin d’un contrôle total (couleur de fond de la liste, hover par option, bordures), envisager une solution custom (div avec rôle listbox + aria) en complément ou à la place du select natif — à décider selon la complexité et l’accessibilité. *Pour l’instant : select natif conservé.*
- [x] **3.3** Pour l’instant, viser une **liste déroulante native cohérente** : même police, couleurs de fond et de texte lisibles, contraste suffisant.

*Implémentation :* règles `select option` (font, couleur, fond, padding) et `select option[value="rejete"]` (couleur accent). Le rendu des `<option>` varie selon le navigateur (Chrome/Edge limitent souvent le style natif ; Firefox applique davantage les propriétés).

---

## Phase 4 — Cohérence avec les autres champs (classe .input)

- [x] **4.1** Définir une classe `.input` (ou étendre les sélecteurs existants) pour que `input[type="text"]`, `input[type="email"]`, `input[type="password"]`, `input[type="number"]`, `textarea` et **select** partagent les mêmes propriétés de base (padding, bordure, radius, focus).
- [x] **4.2** Appliquer `.input` et `.w-full` aux select là où c’est déjà le cas en HTML ; vérifier que les select dans les dashboards ont bien la classe `input` (déjà le cas pour recommandation, user-form, article-detail, etc.).
- [x] **4.3** Gérer les select avec largeur limitée (ex. `max-width: 20rem` sur « Recommandation ») sans casser le responsive : min-width et max-width: 100% sur petits écrans.

---

## Phase 5 — Responsive et accessibilité

- [x] **5.1** Sur mobile, s’assurer que la hauteur du select est tactile (min-height ~44px) et que la liste déroulante reste utilisable (taille de police lisible).
- [x] **5.2** Conserver ou ajouter l’association label/select (attribut `for` / `id`) pour l’accessibilité.
- [x] **5.3** Focus visible au clavier (déjà visé en Phase 1.2).

---

## Phase 6 — Cas particuliers (optionnel)

- [x] **6.1** Select « Recommandation » (formulaire évaluateur) : s’assurer que le libellé « Recommandation * » et le placeholder « Choisir » sont clairs ; les options (Accepté, Révisions mineures/majeures, Rejeté) restent lisibles.
- [x] **6.2** Filtres (ex. statut sur la page des évaluations admin) : même style que les select de formulaire pour cohérence.
- [x] **6.3** Si des select sont utilisés en dehors des `.dashboard-card`, appliquer les mêmes règles globales (ex. `select.input` ou `select` dans un bloc commun).

---

## Fichiers concernés

| Fichier | Rôle |
|--------|------|
| `public/css/styles.css` | Règles pour `select`, `.input`, focus, flèche, options si possible. |
| `views/reviewer/evaluation.php` | Select « Recommandation » (Recommandation *). |
| `views/admin/article-detail.php` | Select statut, numéro de parution. |
| `views/admin/user-form.php` | Select rôle, statut. |
| `views/admin/evaluations.php` | Filtre statut. |
| `views/admin/comite-editorial-form.php` | Select utilisateur. |
| `views/admin/numero-form.php` | Select volume. |
| Autres vues avec `<select>` dans les 3 dashboards | Vérifier la présence de la classe `input` et du couple label/for. |

---

## Ordre d’application recommandé

1. **Phase 1** : Styles de base (padding, bordure, radius, focus) pour tous les select.
2. **Phase 4** : Unifier avec la classe `.input` et les autres champs.
3. **Phase 2** : Flèche personnalisée pour un rendu proche de l’image fournie (liste déroulante propre).
4. **Phase 3** : Ajuster ce qui est possible sur les options (couleurs, lisibilité).
5. **Phase 5** : Responsive et accessibilité.
6. **Phase 6** : Cas particuliers si besoin.

---

## Note sur la sidebar

La **barre latérale de navigation** (Tableau de bord, Utilisateurs, Articles, etc.) des dashboards est un autre type de « menu ». Si vous souhaitez aussi un plan de stylisation pour cette barre latérale, il peut être rédigé dans un document séparé (ex. `PLAN_STYLISATION_SIDEBAR_DASHBOARDS.md`).
