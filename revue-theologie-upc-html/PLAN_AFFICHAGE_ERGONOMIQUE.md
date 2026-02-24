# Plan d’affichage ergonomique — Réduire le volume d’affichage

Objectif : **diminuer le volume d’affichage** des pages internes pour un aspect plus ergonomique, **sans changer le design** (couleurs, polices, composants). On y va **étape par étape, page par page**, pour ne pas surcharger.

---

## 1. Périmètre

### À ne pas modifier
- **Page d’accueil** (`/` — `views/public/index.php`)
- **Tous les dashboards** (auteur, admin, reviewer)
- **Header** (`views/layouts/header.php`)
- **Footer** (`views/layouts/footer.php`)

### À modifier (pages internes uniquement)
Toutes les autres pages listées dans le plan page par page ci‑dessous.

---

## 2. Principes généraux (à appliquer page par page)

Ces réglages seront appliqués **uniquement** dans un contexte dédié (ex. classe `.page-content-compact` sur le contenu des pages concernées), pour ne pas impacter accueil, header, footer ni dashboards.

| Levier | Action |
|--------|--------|
| **Conteneur** | Limiter la largeur max du contenu (meilleure longueur de ligne). |
| **Espacements verticaux** | Réduire marges entre sections (`.section`, `.banner`, etc.). |
| **Cartes** | Réduire le padding des `.card` et les gaps dans les grilles/listes. |
| **Typographie** | Légère réduction des tailles de titres (h1, h2) sur ces pages. |
| **Listes / lignes** | Réduire l’espace entre les items (articles, numéros, résultats). |
| **Bannières** | Réduire la hauteur des `.banner` des pages internes. |

Le **design** (couleurs, polices, boutons, bordures) reste **inchangé**.

---

## 3. Plan page par page

Chaque page est traitée **séparément**. On peut en faire une à la fois et valider avant de passer à la suivante.

---

### Page 1 — Publications (liste des articles)

- **URL** : `/publications`
- **Vue** : `views/public/publications.php`
- **À faire** :
  - [ ] Appliquer la classe de contexte compact (ex. sur le `main` ou le conteneur de la page).
  - [ ] Réduire espacement entre la bannière et le contenu.
  - [ ] Réduire padding des cartes d’articles et gap entre les cartes.
  - [ ] Ajuster si besoin la taille des titres (h1 / h2).
- **Fichiers** : `publications.php`, `public/css/styles.css` (règles sous `.page-content-compact` ou équivalent).

---

### Page 2 — Détail d’un article

- **URL** : `/article/[id]`
- **Vue** : `views/public/article-details.php`
- **À faire** :
  - [ ] Contexte compact sur la page.
  - [ ] Réduire hauteur/espaces de la bannière article.
  - [ ] Réduire padding section + cartes (blocs Lire PDF / Télécharger, encadré Infos).
  - [ ] Espacement entre contenu et sidebar.
- **Fichiers** : `article-details.php`, `styles.css`.

---

### Page 3 — Archives (volumes et numéros)

- **URL** : `/archives`
- **Vue** : `views/public/archives.php`
- **À faire** :
  - [ ] Contexte compact.
  - [ ] Réduire espacements bannière + entre blocs volumes/numéros.
  - [ ] Réduire padding des cartes et gap des grilles.
- **Fichiers** : `archives.php`, `styles.css`.

---

### Page 4 — Détail d’un numéro

- **URL** : `/numero/[id]`
- **Vue** : `views/public/numero-details.php`
- **À faire** :
  - [ ] Contexte compact.
  - [ ] Bannière + espacements sections.
  - [ ] Cartes (numéro, liste d’articles) plus compactes.
- **Fichiers** : `numero-details.php`, `styles.css`.

---

### Page 5 — Présentation

- **URL** : `/presentation`
- **Vue** : `views/public/presentation.php`
- **À faire** :
  - [ ] Contexte compact.
  - [ ] Bannière + marges entre blocs de texte / image.
  - [ ] Réduire padding si cartes ou blocs présents.
- **Fichiers** : `presentation.php`, `styles.css`.

---

### Page 6 — Comité éditorial

- **URL** : `/comite`
- **Vue** : `views/public/comite.php`
- **À faire** :
  - [ ] Contexte compact.
  - [ ] Bannière + espacements.
  - [ ] Cartes / listes comités plus compactes.
- **Fichiers** : `comite.php`, `styles.css`.

---

### Page 7 — Politique éditoriale

- **URL** : `/politique-editoriale`
- **Vue** : `views/public/politique-editoriale.php`
- **À faire** :
  - [ ] Contexte compact.
  - [ ] Bannière + marges entre paragraphes / sections.
- **Fichiers** : `politique-editoriale.php`, `styles.css`.

---

### Page 8 — Instructions aux auteurs

- **URL** : `/instructions-auteurs`
- **Vue** : `views/public/instructions-auteurs.php`
- **À faire** :
  - [ ] Contexte compact.
  - [ ] Bannière + espacements.
  - [ ] Blocs « Télécharger les modèles » et listes plus compacts.
- **Fichiers** : `instructions-auteurs.php`, `styles.css`.

---

### Page 9 — Contact

- **URL** : `/contact`
- **Vue** : `views/public/contact.php`
- **À faire** :
  - [ ] Contexte compact.
  - [ ] Bannière + formulaire et éventuelles cartes.
  - [ ] Réduire padding / gaps.
- **Fichiers** : `contact.php`, `styles.css`.

---

### Page 10 — FAQ

- **URL** : `/faq`
- **Vue** : `views/public/faq.php`
- **À faire** :
  - [ ] Contexte compact.
  - [ ] Bannière + espacement entre questions/réponses.
- **Fichiers** : `faq.php`, `styles.css`.

---

### Page 11 — Actualités

- **URL** : `/actualites`
- **Vue** : `views/public/actualites.php`
- **À faire** :
  - [ ] Contexte compact.
  - [ ] Bannière + listes/cartes d’actualités.
- **Fichiers** : `actualites.php`, `styles.css`.

---

### Page 12 — Recherche (résultats)

- **URL** : `/search?q=...`
- **Vue** : `views/public/search.php`
- **À faire** :
  - [ ] Contexte compact.
  - [ ] Bannière + espacements.
  - [ ] Cartes articles/numéros et gaps réduits.
- **Fichiers** : `search.php`, `styles.css`.

---

### Page 13 — Mentions légales

- **URL** : `/mentions-legales`
- **Vue** : `views/public/mentions-legales.php`
- **À faire** :
  - [ ] Contexte compact.
  - [ ] Bannière + marges entre sections.
- **Fichiers** : `mentions-legales.php`, `styles.css`.

---

### Page 14 — Conditions d’utilisation

- **URL** : `/conditions-utilisation`
- **Vue** : `views/public/conditions.php`
- **À faire** :
  - [ ] Contexte compact.
  - [ ] Bannière + marges entre sections.
- **Fichiers** : `conditions.php`, `styles.css`.

---

### Page 15 — Politique de confidentialité

- **URL** : `/confidentialite`
- **Vue** : `views/public/confidentialite.php`
- **À faire** :
  - [ ] Contexte compact.
  - [ ] Bannière + marges entre sections.
- **Fichiers** : `confidentialite.php`, `styles.css`.

---

## 4. Ordre suggéré pour avancer

1. Mettre en place **une fois** le contexte CSS compact (ex. `.page-content-compact` dans `styles.css`) avec des règles de base (conteneur, `.section`, `.banner`).
2. Traiter **une page à la fois** dans l’ordre 1 → 15, en cochant au fur et à mesure dans ce fichier.
3. Après chaque page : vérifier en local (desktop + mobile) puis passer à la suivante.

Commencer par **Page 1 — Publications** est un bon point de départ (liste d’articles, cartes, bannière).

---

## 5. Résumé

| Élément | Statut |
|--------|--------|
| Accueil, dashboards, header, footer | **Non modifiés** |
| Design (couleurs, polices, composants) | **Conservé** |
| Pages 1 à 15 | **À traiter une par une** avec ce plan |

Pour démarrer, on peut commencer par la **Page 1 — Publications** et le fichier `PLAN_AFFICHAGE_ERGONOMIQUE.md` servira de suivi page par page.
