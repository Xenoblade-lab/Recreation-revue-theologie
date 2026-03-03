# Plan responsive – 3 dashboards (Admin, Auteur, Évaluateur)

Ce document décrit les étapes pour rendre les trois espaces (admin, auteur, évaluateur) utilisables et lisibles sur mobile, tablette et desktop.

---

## Contexte

- **Layouts concernés :**
  - `views/layouts/admin-dashboard.php`
  - `views/layouts/author-dashboard.php`
  - `views/layouts/reviewer-dashboard.php`
- **Structure commune :** topbar + `dashboard-body` (sidebar + main).
- **Feuille de style :** `public/css/styles.css` (classes `dashboard-*`, `stat-card`, `dashboard-table`, etc.).
- **Breakpoints déjà utilisés :** 640px, 768px, 1024px.

---

## Objectifs

1. **Mobile (&lt; 768px)** : menu latéral masqué par défaut, ouvrable par bouton ; topbar lisible ; tableaux en scroll horizontal ou liste ; cartes stats en 1 colonne.
2. **Tablette (768px – 1023px)** : sidebar en colonne fixe ou drawer ; contenu principal confortable ; tableaux scroll horizontal si besoin.
3. **Desktop (≥ 1024px)** : disposition actuelle conservée (sidebar fixe 16rem + main).

---

## Phase 1 – Structure globale et topbar

### Étape 1.1 – Body et conteneur principal

- [ ] **1.1.1** En mobile, faire passer `dashboard-body` en colonne et permettre à la sidebar de ne pas prendre toute la largeur quand elle est affichée.
  - Dans `styles.css`, pour `max-width: 1023px` :  
    `.dashboard-body { flex-direction: column; }`  
  - S’assurer que `.dashboard-main` a bien `min-width: 0` pour éviter les débordements (flex).

- [ ] **1.1.2** Donner une largeur fixe à la sidebar sur mobile quand elle est ouverte (ex. 280px ou 100% en drawer plein écran), et garder `width: 16rem` à partir de 1024px.

### Étape 1.2 – Topbar

- [ ] **1.2.1** Réduire ou masquer le texte long « Revue Congolaise de Théologie Protestante » sur très petit écran (ex. &lt; 480px) : afficher uniquement le logo + « Administration » / « Auteur » / « Évaluateur », ou un titre court.

- [ ] **1.2.2** Passer le bloc breadcrumb en dessous du premier bloc (logo + espace) sur petit écran, avec `flex-wrap` déjà présent, et réduire la taille de police si nécessaire (`font-size: 0.8125rem` en mobile).

- [ ] **1.2.3** Espacer correctement les liens du breadcrumb (gap) et éviter qu’ils ne se chevauchent ; si besoin, masquer certains liens intermédiaires en mobile et garder « Accueil », « Dashboard », « Déconnexion ».

---

## Phase 2 – Sidebar et menu mobile

### Étape 2.1 – Bouton menu (hamburger)

- [ ] **2.1.1** Ajouter dans les **trois** layouts un bouton « menu » (hamburger) dans la topbar, visible uniquement en dessous de 1024px (classe existante `dashboard-mobile-menu` ou équivalent).  
  - Attributs d’accessibilité : `aria-label="Ouvrir le menu"`, `aria-expanded="false"` (à toggler en JS).  
  - Le bouton doit être à gauche du logo ou à droite de la topbar selon la maquette.

- [ ] **2.1.2** En CSS, s’assurer que `.dashboard-mobile-menu` est affiché en bloc en &lt; 1024px et masqué au-dessus (déjà prévu dans le fichier actuel).

### Étape 2.2 – Comportement de la sidebar

- [ ] **2.2.1** En &lt; 1024px : sidebar **masquée par défaut** (classe `collapsed` ou `hidden` sur la sidebar).  
  - Au clic sur le hamburger : retirer la classe pour afficher la sidebar (drawer).  
  - Option : sidebar en overlay (position fixe, fond semi-transparent derrière) pour ne pas pousser le contenu.

- [ ] **2.2.2** Sur overlay : au clic sur le fond (backdrop), fermer la sidebar.  
  - À la fermeture : remettre `aria-expanded="false"` sur le bouton et focus sur le bouton pour l’accessibilité.

- [ ] **2.2.3** En &lt; 1024px, quand la sidebar est ouverte, empêcher le scroll du body (overflow: hidden) pour éviter le double scroll.

- [ ] **2.2.4** En ≥ 1024px : sidebar toujours visible, pas de classe collapsed ; le bouton hamburger est masqué.

### Étape 2.3 – JavaScript commun

- [ ] **2.3.1** Dans `public/js/main.js` (ou script inclus dans les layouts) :  
  - Sélecteur du bouton `.dashboard-mobile-menu` et de la sidebar `#dashboard-sidebar`.  
  - Toggle d’une classe (ex. `sidebar-open`) sur la sidebar et mise à jour de `aria-expanded`.  
  - Clic sur overlay (si présent) pour fermer.  
  - Fermeture au clic sur un lien de la sidebar (navigation effectuée) pour éviter de laisser le menu ouvert après navigation.

---

## Phase 3 – Zone principale (main)

### Étape 3.1 – Padding et largeur

- [ ] **3.1.1** Vérifier les paddings de `.dashboard-main` : par ex. `1rem` en mobile, `1.5rem` en tablette, `2rem` en desktop (déjà partiellement en place).

- [ ] **3.1.2** S’assurer que le contenu principal ne dépasse pas la largeur (pas de overflow horizontal) : `min-width: 0` sur `.dashboard-main` si dans un flex.

### Étape 3.2 – En-têtes de page

- [ ] **3.2.1** `.dashboard-header` : titre `h1` en `font-size: 1.25rem` en mobile, `1.5rem` à partir de 768px.  
  - Sous-titre et liens en dessous avec `flex-wrap` pour qu’ils passent à la ligne si besoin.

- [ ] **3.2.2** Blocs « Actions rapides » ou boutons en tête : les mettre en `flex-wrap` et `gap` pour qu’ils s’alignent sur plusieurs lignes sur petit écran.

### Étape 3.3 – Cartes statistiques (stat-cards)

- [ ] **3.3.1** Conserver ou renforcer la grille : 1 colonne en mobile, 2 à partir de 640px, 4 à partir de 1024px (déjà en place pour `.dashboard-stats`).

- [ ] **3.3.2** En mobile, réduire légèrement le padding des `.stat-card` (ex. `1rem`) et la taille du `.stat-value` (ex. `1.25rem`) pour éviter que les cartes soient trop hautes.

---

## Phase 4 – Tableaux (dashboard-table)

### Étape 4.1 – Scroll horizontal

- [ ] **4.1.1** S’assurer que chaque tableau est dans un conteneur avec `overflow-x: auto` (classe `.overflow-auto` déjà utilisée).  
  - En mobile/tablette, le tableau garde sa structure mais le conteneur scroll horizontalement pour éviter de casser la mise en page.

- [ ] **4.1.2** Option : ajouter une ombre ou un indicateur visuel sur le bord droit quand il y a du contenu masqué (scroll possible), pour inviter l’utilisateur à faire défiler.

### Étape 4.2 – Colonnes et cellules

- [ ] **4.2.1** En mobile, réduire le padding des cellules (ex. `0.5rem 0.75rem`) pour gagner de la place.

- [ ] **4.2.2** Colonne « Actions » : garder les boutons/icônes en `flex-wrap` (déjà le cas pour `.actions-cell`).  
  - S’assurer que les icônes ne se chevauchent pas et restent cliquables (zone de touch suffisante, min 44px recommandé).

### Étape 4.3 – (Optionnel) Vue liste sur très petit écran

- [ ] **4.3.1** Pour certaines listes (ex. articles, évaluations), envisager une variante « carte » en &lt; 640px : une ligne du tableau devient une carte (bloc) avec les infos empilées et le lien « Voir » / actions en bas.  
  - À traiter page par page si nécessaire (admin articles, admin users, author articles, reviewer listes, etc.).

---

## Phase 5 – Cartes de contenu et listes

### Étape 5.1 – dashboard-card

- [ ] **5.1.1** Padding des `.dashboard-card` : en mobile `1rem`, puis `1.5rem` à partir de 768px.

- [ ] **5.1.2** Titres `h2` dans les cartes : taille de police adaptée (ex. `1rem` en mobile, `1.125rem` au-dessus).

### Étape 5.2 – Listes (notifications, activités récentes)

- [ ] **5.2.1** Listes en `ul` / `li` : padding et séparateurs lisibles ; pas de texte trop long sans retour à la ligne (word-break si besoin sur les URLs ou titres longs).

- [ ] **5.2.2** Boutons « Marquer comme lu » / « Lire » : taille minimale tactile (44px de hauteur ou zone cliquable équivalente).

### Étape 5.3 – Formulaires dans les dashboards

- [ ] **5.3.1** Champs en `width: 100%` ou `max-width: 100%` dans les cartes pour éviter le débordement.

- [ ] **5.3.2** Boutons de formulaire (Valider, Refuser, Soumettre, etc.) en `flex-wrap` et pleine largeur en mobile si nécessaire (`width: 100%` ou `flex: 1 1 100%`).

---

## Phase 6 – Footer et modales

### Étape 6.1 – Footer dashboard

- [ ] **6.1.1** Le footer des dashboards (`.site-footer` dans les layouts) : texte et lien en colonne en mobile, côte à côte à partir de 768px (déjà géré ailleurs pour `.footer-bottom` ; vérifier la cohérence).

### Étape 6.2 – Modal de confirmation

- [ ] **6.2.1** La modale de confirmation (`.confirm-modal`) : déjà en `max-width: 420px` et `width: 100%` avec `padding: 1rem`.  
  - Vérifier qu’en très petit écran elle ne touche pas les bords et que les boutons restent accessibles (empilés en mobile si besoin).

---

## Phase 7 – Vérifications communes aux 3 dashboards

### Étape 7.1 – Admin

- [ ] **7.1.1** Page tableau de bord : stats, dernières soumissions, activités récentes, actions rapides.  
- [ ] **7.1.2** Pages listes : Utilisateurs, Articles, Évaluations, Paiements, Volumes, Comité éditorial.  
- [ ] **7.1.3** Pages détail : article, utilisateur, paiement.  
- [ ] **7.1.4** Formulaires : création utilisateur, paramètres, etc.

### Étape 7.2 – Auteur

- [ ] **7.2.1** Tableau de bord, liste des articles, détail article (workflow, commentaires évaluateurs).  
- [ ] **7.2.2** Soumission d’article (formulaire avec fichier).  
- [ ] **7.2.3** Abonnement, notifications, profil.

### Étape 7.3 – Évaluateur

- [ ] **7.3.1** Tableau de bord, évaluations en attente, terminées, historique.  
- [ ] **7.3.2** Page formulaire d’évaluation (formulaire long).  
- [ ] **7.3.3** Notifications, profil.

---

## Récapitulatif des breakpoints

| Breakpoint | Usage |
|------------|--------|
| &lt; 480px | Très petit mobile : titre court, padding réduits, 1 colonne partout. |
| 480px – 639px | Mobile : sidebar en drawer, stats 1 col, tableaux en scroll. |
| 640px – 767px | Grande mobile / petite tablette : stats 2 colonnes. |
| 768px – 1023px | Tablette : sidebar drawer ou colonne, main confortable. |
| ≥ 1024px | Desktop : sidebar fixe 16rem, 4 colonnes stats, layout actuel. |

---

## Fichiers à modifier (résumé)

| Fichier | Rôle |
|---------|------|
| `public/css/styles.css` | Media queries dashboard, topbar, sidebar, stats, tables, cartes. |
| `views/layouts/admin-dashboard.php` | Bouton hamburger, structure topbar/sidebar si besoin. |
| `views/layouts/author-dashboard.php` | Idem. |
| `views/layouts/reviewer-dashboard.php` | Idem. |
| `public/js/main.js` | Toggle sidebar, overlay, aria-expanded, fermeture au clic lien. |

---

## Ordre recommandé

1. **Phase 1** (structure + topbar) pour une base cohérente.  
2. **Phase 2** (sidebar + menu mobile) pour la navigation sur petit écran.  
3. **Phase 3** (main + stats) pour le confort de lecture.  
4. **Phase 4** (tableaux) pour les listes admin/auteur/reviewer.  
5. **Phase 5 et 6** (cartes, listes, formulaires, footer, modales).  
6. **Phase 7** (tests manuels sur les 3 espaces à différentes largeurs).

Une fois ce plan suivi, les trois dashboards seront utilisables de façon progressive (mobile first ou desktop first selon préférence) avec une expérience cohérente entre Admin, Auteur et Évaluateur.
