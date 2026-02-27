# Plan de recolorisation — Bleu de la couverture revue

Ce document décrit les étapes pour remplacer les bleus actuels du site par le bleu dominant de la couverture **revue-cover-upc.jpg** (Revue Congolaise de Théologie Protestante).

---

## Référence couleur couverture

- **Bleu principal (fond de couverture)** : `#2760A8` (RGB: 39, 96, 168)
- Ce bleu sera la nouvelle teinte de base pour `--primary` et `--upc-blue`.

---

## Palette actuelle → nouvelle palette

| Variable / usage        | Actuel      | Nouveau (proposé) | Rôle |
|-------------------------|------------|-------------------|------|
| `--primary`             | `#1a3365`  | `#2760A8`         | Bleu principal (boutons, liens, en-têtes) |
| `--upc-blue`            | `#1a3365`  | `#2760A8`         | Alias bleu UPC |
| `--upc-blue-dark`       | `#0f2847`  | `#1e4a82`         | Footer, hover bouton primary, dégradés sombres |
| `--ring`                | `#1a3365`  | `#2760A8`         | Focus / outline |
| `--secondary-foreground`| `#1a3365`  | `#2760A8`         | Texte secondaire bleu |
| `--hero-cover-blue`      | `#2A70AF`  | `#2760A8`         | Fond hero / couverture |
| `--hero-cover-blue-dark` | `#1e5a8e`  | `#1e4a82`         | Dégradés hero |
| `--hero-cover-blue-darker` | `#164670` | `#163a6b`       | Partie la plus sombre des dégradés |

**Valeurs dérivées (pour cohérence) :**
- **Bleu plus foncé** (footer, hover) : `#1e4a82`
- **Bleu encore plus foncé** (dégradés) : `#163a6b`
- Les **rgba(26,51,101,…)** seront remplacés par **rgba(39,96,168,…)** (équivalent du nouveau primary).

---

## Étapes de recolorisation

### Étape 1 — Variables CSS globales (`public/css/styles.css`)

- [x] **1.1** Dans `:root`, remplacer :
  - `--primary: #1a3365` → `--primary: #2760A8` ✓
  - `--secondary-foreground: #1a3365` → `--secondary-foreground: #2760A8` ✓
  - `--ring: #1a3365` → `--ring: #2760A8` ✓
  - `--upc-blue: #1a3365` → `--upc-blue: #2760A8` ✓
  - `--upc-blue-dark: #0f2847` → `--upc-blue-dark: #1e4a82` ✓
  - `--hero-cover-blue: #2A70AF` → `--hero-cover-blue: #2760A8` ✓
  - `--hero-cover-blue-dark: #1e5a8e` → `--hero-cover-blue-dark: #1e4a82` ✓
  - `--hero-cover-blue-darker: #164670` → `--hero-cover-blue-darker: #163a6b` ✓

Tous les composants qui utilisent déjà `var(--primary)`, `var(--upc-blue)`, `var(--upc-blue-dark)` ou `var(--hero-cover-*)` sont maintenant à jour.

---

### Étape 2 — Remplacement des rgba (bleu ancien → bleu couverture)

Les blocs utilisant `rgba(26,51,101,…)` correspondent à l’ancien primary. Les remplacer par l’équivalent du nouveau bleu **39, 96, 168** :

- [x] **2.1** `rgba(26,51,101,0.1)` → `rgba(39,96,168,0.1)` (pills, icônes, badges)
- [x] **2.2** Vérifier qu’il n’y a pas d’autres `rgba(26,51,101,…)` ou `#1a3365` / `#0f2847` en dur dans les vues (PHP/HTML inline) ou autres CSS.

Fichier concerné : `public/css/styles.css` (recherche « 26,51,101 »).

---

### Étape 3 — Fichier `frontend/css/styles.css` (si utilisé)

- [x] **3.1** Si le projet utilise aussi `frontend/css/styles.css`, appliquer les mêmes changements que pour **Étape 1** et **Étape 2** dans ce fichier, pour garder une cohérence (ex. maquettes ou anciennes pages).

---

### Étape 4 — Vérifications finales

- [ ] **4.1** Parcourir le site (accueil, header, footer, boutons, liens, cartes, hero, dashboards) et vérifier que le bleu affiché correspond au bleu de la couverture.
- [ ] **4.2** Vérifier les contrastes (texte blanc sur bleu, liens sur fond clair) pour l’accessibilité.
- [x] **4.3** Vues avec bleu en dur : `views/author/s-abonner.php` — fallbacks `#2563eb` → `#2760A8`, `rgba(37,99,235,…)` → `rgba(39,96,168,…)`, hover bouton → `var(--upc-blue-dark, #1e4a82)`. Aucun autre fichier PHP concerné.

---

## Résumé des fichiers à modifier

| Fichier | Actions |
|---------|--------|
| `public/css/styles.css` | Étape 1 (variables) + Étape 2 (rgba) |
| `frontend/css/styles.css` | Optionnel, même chose si le fichier est utilisé |
| Vues PHP avec style inline bleu | Remplacer par variable ou `#2760A8` si nécessaire |

---

## Ordre d’exécution recommandé

1. **Étape 1** — Modifier les variables dans `public/css/styles.css`.
2. **Étape 2** — Remplacer les `rgba(26,51,101,…)` dans le même fichier.
3. **Étape 3** — Si besoin, appliquer aux autres feuilles de style.
4. **Étape 4** — Contrôle visuel et accessibilité.

Une fois ces étapes faites, tout le site utilisera le bleu de la couverture de la revue.
