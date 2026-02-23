# Plan de traduction du site (FR / EN / Lingala)

Ce document décrit les étapes pour traduire toutes les pages du site en utilisant les fichiers `lang/fr.php`, `lang/en.php`, `lang/ln.php` et la fonction `__('cle')`.

---

## Principe

- **Clés** : on ajoute des clés dans les 3 fichiers de langue (ex. `home.welcome`, `contact.title`).
- **Vues** : on remplace les textes en dur par `<?= htmlspecialchars(__('cle')) ?>` (ou `__(...)` si le contenu est déjà échappé).
- **Contenu dynamique** (texte saisi en base, ex. description de la revue) : on peut soit le garder en français, soit prévoir plus tard des champs multilingues en BDD.

---

## Phase 1 — Pages publiques « vitrine » (priorité haute)

| Étape | Page | Fichier(s) | Contenu à traduire |
|-------|------|------------|---------------------|
| **1.1** | **Accueil** | `views/public/index.php` | Titres sections (Article à la une, Bienvenue, Choix de la rédaction, Voir tout, Rechercher…), boutons, liens, labels formulaire recherche, textes de fallback, newsletter, sondage, etc. |
| **1.2** | **Présentation** | `views/public/presentation.php` | Bannière, titres (Présentation, Objectifs, Domaines), textes par défaut, stats (Années de publication…), badge « Depuis 1960 ». |
| **1.3** | **Comité éditorial** | `views/public/comite.php` | Titre page, libellés (rôle, biographie, etc.). |
| **1.4** | **Politique éditoriale** | `views/public/politique-editoriale.php` | Titre, sous-titres, paragraphes (ou contenu depuis BDD). |
| **1.5** | **Publications** | `views/public/publications.php` | Titre, filtres (catégorie, année), « Voir tout », « Aucun article », pagination, boutons. |
| **1.6** | **Instructions aux auteurs** | `views/public/instructions-auteurs.php` | Titre, sections, listes, boutons. |
| **1.7** | **Archives** | `views/public/archives.php` | Titre, Volumes & numéros, listes, liens. |
| **1.8** | **Actualités** | `views/public/actualites.php` | Titre, liste, dates, « Lire la suite ». |
| **1.9** | **Contact** | `views/public/contact.php` | Titre, sous-titres (Informations de contact, Envoyez-nous un message), Adresse, Email, Téléphone, Heures d'ouverture, bouton. |
| **1.10** | **FAQ** | `views/public/faq.php` | Titre, questions/réponses (ou clés par question). |
| **1.11** | **Mentions légales** | `views/public/mentions-legales.php` | Titre, sections juridiques. |
| **1.12** | **Détail article** | `views/public/article-details.php` | Titre, Auteur, Résumé, Télécharger PDF, etc. |
| **1.13** | **Détail numéro** | `views/public/numero-details.php` | Titre, Articles du numéro, etc. |
| **1.14** | **Recherche** | `views/public/search.php` | Titre, « Résultats pour », « Aucun résultat », pagination. |

---

## Phase 2 — Authentification (priorité moyenne)

| Étape | Page | Fichier(s) | Contenu à traduire |
|-------|------|------------|---------------------|
| **2.1** | **Connexion** | `views/auth/login.php` | Titre, sous-titre, labels (Email, Mot de passe), « Mot de passe oublié », « Se connecter », « Créer un compte », « Retour à l'accueil », liens Admin/Auteur/Évaluateur. |
| **2.2** | **Inscription** | `views/auth/register.php` | Titre, champs formulaire, bouton, lien Connexion. |
| **2.3** | **Mot de passe oublié** | `views/auth/forgot-password.php` | Titre, message, bouton, lien retour. |

---

## Phase 3 — Espaces connectés (priorité plus basse)

On peut garder l’interface des espaces connectés en français dans un premier temps, ou traduire les libellés communs (tableaux, boutons, menus).

| Étape | Zone | Fichiers | Remarque |
|-------|------|----------|----------|
| **3.1** | **Espace auteur** | `views/author/*.php`, `views/layouts/author-dashboard.php` | Tableau de bord, Soumettre, Mes articles, Notifications, Abonnement. |
| **3.2** | **Espace évaluateur** | `views/reviewer/*.php`, `views/layouts/reviewer-dashboard.php` | Évaluations, Historique, Terminées, Notifications. |
| **3.3** | **Administration** | `views/admin/*.php`, `views/layouts/admin-dashboard.php` | Utilisateurs, Articles, Volumes, Paiements, Paramètres. |

---

## Ordre recommandé pour avancer « étape par étape »

1. **Phase 1.1** — Accueil (beaucoup de libellés, bonne base pour les clés `home.*`).
2. **Phase 1.2** — Présentation.
3. **Phase 1.9** — Contact (page courte, rapide).
4. **Phase 1.10** — FAQ.
5. **Phase 2.1** — Connexion (réutilisable pour auth).
6. Puis enchaîner 1.3 → 1.4 → 1.5 → 1.6 → 1.7 → 1.8 → 1.11 → 1.12 → 1.13 → 1.14, puis Phase 2.2 et 2.3, puis Phase 3 si souhaité.

---

## Convention de nommage des clés

- **Par page** : `home.*`, `presentation.*`, `contact.*`, `auth.*`, etc.
- **Réutilisables** : `common.read_more`, `common.download`, `common.back`, `common.save`, etc.

Exemple dans `lang/fr.php` :

```php
'home.welcome' => 'Bienvenue',
'home.featured' => 'Article à la une',
'contact.title' => 'Contact',
'contact.send_message' => 'Envoyez-nous un message',
```

---

## Fichiers à modifier à chaque étape

- `lang/fr.php` — ajouter les clés en français.
- `lang/en.php` — ajouter les traductions anglaises.
- `lang/ln.php` — ajouter les traductions lingala.
- La ou les vues concernées — remplacer les textes par `__('cle')`.

Tu peux demander : « On fait l’étape 1.1 (Accueil) » et on appliquera les changements pour cette étape uniquement.
