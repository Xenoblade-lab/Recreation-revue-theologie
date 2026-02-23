# Plan d’améliorations : ancien site → nouveau site

Ce document compare **revue_ancien** et **revue-theologie-upc-html** pour identifier les éléments importants ou manquants et proposer un plan de travail pour rendre le nouveau site optimal.

---

## 1. Comparatif synthétique

| Domaine | Ancien site | Nouveau site | Action |
|--------|-------------|--------------|--------|
| **Pages publiques** | Accueil riche (hero, dernier numéro, articles en vedette, actualités, newsletter, à propos, stats) | Accueil plus sobre (hero, 1 article à la une, numéros, recherche) | Enrichir accueil |
| **Multilingue** | FR uniquement (ou partiel) | FR / EN / Lingala (i18n complet) | ✓ déjà mieux |
| **Authentification** | Login, register, notifications + dropdown header, switch rôle admin/reviewer | Login, register, forgot-password, header simple (lien espace) | Ajouter notifications header, optionnel switch rôle |
| **Espace auteur** | Dashboard, articles, abonnement, **s’abonner (paiement M-Pesa/Orange)**, **révisions article**, **profil** | Dashboard, articles, abonnement (historique), soumettre, notifications, détail/édition article | Abonnement payant, révisions, profil |
| **Espace évaluateur** | Dashboard, évaluations, terminées, historique, **profil**, **publications** | Dashboard, évaluations, terminées, historique, notifications | Profil, lien « Publications » |
| **Administration** | Dashboard, users, articles, **détail article (assigner, numéro)**, volumes, **détail volume/numéro**, **évaluations** (liste + détail), **paiements (Valider/Refuser)**, **paramètres revue** | Dashboard, users, articles, volumes, paiements (lecture seule), paramètres | Détail article admin, actions paiements, liste évaluations |
| **Contenu légal / pages** | **Conditions**, **Confidentialité** (Next.js) | Mentions légales, FAQ, contact | Conditions, Confidentialité (PHP) |
| **Instructions aux auteurs** | **Télécharger les modèles** (template Word, template LaTeX) | Format + processus uniquement | Ajouter section « Télécharger les modèles » + liens Word/LaTeX |
| **Comité éditorial** | Ancien : comités globaux + comités par année. **Choix actuel : comité permanent** pour toutes les années. | Comités globaux (rédaction, scientifique) depuis paramètres — **conforme** | ✓ Rien à ajouter (comité permanent = paramètres revue) |
| **Abonnement auteur** | **Résilier l'abonnement** (bouton + confirmation), renouveler, télécharger reçu | Statut + historique des paiements uniquement | Ajouter bouton « Résilier l'abonnement » + route/action |
| **UX / design** | Hero « Revue Congolaise », badges, stats (28 ans, 500+ articles), newsletter, réseaux sociaux | Design propre, barre langue, moins de blocs marketing | Garder cohérence, ajouter sections utiles |

---

## 2. Éléments importants ou améliorations à apporter

### 2.1 Pages publiques (vitrine)

- **Accueil**
  - **À garder / ajouter (inspiré ancien)** : section « Dernier numéro » mise en avant (couverture, description, lien vers le numéro) ; section « Actualités & Événements » (ou réutiliser la page actualités) ; section « Publiez votre recherche » (CTA + avantages) ; bloc « À propos » avec **chiffres clés** (années de publication, nombre d’articles, pays) ; **Newsletter** (formulaire « Restez informé »).
  - **Déjà bien sur le nouveau** : recherche, choix de la rédaction, numéros par année, multilingue.

- **Footer**
  - Ancien : Navigation, Ressources, Suivez-nous (Facebook, Twitter, LinkedIn, ResearchGate), ISSN.
  - Nouveau : Vérifier que liens utiles, réseaux sociaux et ISSN sont présents et cohérents.

### 2.2 Authentification et header

- **Notifications globales (header)** : sur l’ancien, un bouton « Notifications » + dropdown (liste + « Tout marquer comme lu ») dans le header une fois connecté. À reproduire sur le nouveau pour tous les rôles (auteur, évaluateur, admin) ou au moins auteur/évaluateur.
- **Switch de rôle** (optionnel) : ancien site permet à un admin de basculer entre « Admin » et « Évaluateur » dans le menu utilisateur. Utile si les mêmes personnes gèrent les deux espaces.
- **Inscription « auteur » dédiée** : l’ancien a une page **Inscription Auteur** (messages d’erreur dédiés, champs spécifiques). Le nouveau a une inscription générique ; on peut soit garder une seule inscription, soit ajouter une page/parcours « Inscription auteur » avec textes adaptés.

### 2.3 Espace auteur

- **Abonnement payant (s’abonner)** : l’ancien a une page **S’abonner** avec cartes tarifaires et **paiement (M-Pesa, Orange Money, etc.)**. Le nouveau a « Abonnement » + historique des paiements mais pas de tunnel de souscription/paiement. À prévoir : page « S’abonner » (choix formule, montant) + intégration moyen(s) de paiement (M-Pesa/Orange ou autre) + enregistrement en BDD et redirection après succès.
- **Historique des révisions d’article** : l’ancien a une page **Révisions** par article (timeline : changement de statut, commentaires, etc.). À ajouter côté nouveau : modèle/vue « révisions » pour un article (lecture seule pour l’auteur), et lien depuis la fiche article auteur.
- **Profil utilisateur** : l’ancien a une page **Profil** (auteur) pour modifier nom, prénom, email, mot de passe, etc. À ajouter sur le nouveau (une page « Mon profil » dans l’espace auteur, et éventuellement dans reviewer/admin).
- **Résilier l’abonnement** : sur l’ancien, dans la page **Abonnement** (dashboard auteur), lorsqu’un abonnement est actif, un bouton **« Résilier l’abonnement »** est affiché (avec confirmation via `cancelSubscription(id)`). La documentation prévoit une route `POST /author/abonnement/cancel`. Le nouveau site n’a pas cette action : ajouter le bouton « Résilier l’abonnement » sur la page `views/author/abonnement.php` (si abonnement actif), une route `POST /author/abonnement/cancel` (ou `POST /author/abonnement/[id]/cancel`), et la logique côté `AuthorController` + `AbonnementModel` (mise à jour du statut de l’abonnement, ex. `annule` ou `resilie`). Optionnel : téléchargement du reçu de paiement (l’ancien a « Télécharger le reçu » par paiement si `recu_path` est renseigné).

### 2.4 Espace évaluateur

- **Profil** : idem que pour l’auteur, page **Profil** évaluateur (ancien). À ajouter si pas présent.
- **Publications** : l’ancien a une page **Publications** dans l’espace évaluateur (accès rapide aux numéros/articles). Sur le nouveau, un lien « Publications » ou « Voir les publications » dans le menu ou le dashboard peut suffire (vers la page publique `/publications` ou `/archives`).

### 2.5 Administration

- **Détail article (admin)** : le nouveau a les routes et le contrôleur (`articleDetail`, `articleAssign`, `articleSetIssue`) mais il **manque probablement la vue** `views/admin/article-detail.php`. À créer : page détail d’un article avec infos, statut, **assignation d’un évaluateur**, **rattachement à un numéro** (issue), et historique des évaluations.
- **Paiements – actions** : l’ancien permet de **Valider** ou **Refuser** un paiement (boutons + appel API/script). Le nouveau affiche la liste des paiements en lecture seule. À ajouter : boutons « Valider » / « Refuser » + route (ex. `POST /admin/paiement/[id]/statut`) et mise à jour du statut en BDD (et notification si besoin).
- **Liste des évaluations (admin)** : l’ancien a une page **Évaluations** (stats + tableau des évaluations). À ajouter sur le nouveau : page « Évaluations » (liste globale avec filtres optionnels) + éventuellement détail d’une évaluation (lecture seule pour traçabilité).
- **Détail volume / numéro (admin)** : l’ancien a **volume-details**, **issue-details** en admin. Sur le nouveau, si la gestion des volumes est minimale (liste dans parametres/volumes), on peut reporter en phase 2 l’édition détaillée des volumes et numéros ; sinon, ajouter des vues détail + formulaires d’édition.
- **Paramètres revue** : les deux ont une page paramètres (nom, description, ligne éditoriale, etc.). Vérifier que tous les champs utiles de l’ancien sont présents (ISSN, comités, etc.) — déjà listés dans `parametres.php` du nouveau.

### 2.6 Pages légales et contenu

- **Conditions d’utilisation** : présentes sur l’ancien (app Next.js). À ajouter sur le nouveau en PHP (une page `/conditions-utilisation` ou `/conditions` avec contenu statique ou depuis BDD) et lien dans le footer.
- **Politique de confidentialité** : idem (ancien en Next.js). À ajouter sur le nouveau (page `/confidentialite` ou `/politique-confidentialite`) + lien dans le footer et, si besoin, dans la page d’inscription.

### 2.7 Contenu et identité

- **Hero / nom** : l’ancien met en avant « Revue Congolaise de Théologie protestante » et « Université Protestante au Congo ». S’assurer que le nouveau utilise le même intitulé officiel et la même identité (titres, sous-titres, textes d’accueil).
- **Articles en vedette (accueil)** : l’ancien affiche plusieurs cartes d’articles avec catégorie, auteurs, extrait, pages, DOI. Le nouveau affiche un seul article à la une. On peut ajouter 2–3 cartes « Articles en vedette » supplémentaires (données dynamiques depuis la BDD) pour rapprocher de l’ancien.
- **Réseaux sociaux** : liens Facebook, Twitter, LinkedIn, ResearchGate dans le header ou le footer, comme sur l’ancien.

### 2.8 Instructions aux auteurs — téléchargement des modèles

Sur l’**ancien site** (`revue_ancien/views/instructions.php` et `instruct.html`), la page Instructions contient une section explicite **« Télécharger les modèles »** :

- Titre : **Télécharger les modèles**
- Texte : *« Utilisez impérativement l'un des modèles suivants pour préparer votre manuscrit. »*
- **Télécharger le template Word** : lien vers `templates/template.docx` (attribut `download="template-revue-theologie-upc.docx"`).
- **Télécharger le template LaTeX** : lien vers `templates/template.tex` (attribut `download="template-revue-theologie-upc.tex"`).

Le **nouveau site** (`views/public/instructions-auteurs.php`) affiche uniquement le format des manuscrits et le processus de soumission, **sans** cette section ni les liens de téléchargement.

**À faire** : Dans `views/public/instructions-auteurs.php`, ajouter une section « Télécharger les modèles » avec le même texte et deux liens (Word et LaTeX). Prévoir les fichiers dans `public/templates/` (ou un chemin cohérent) : `template.docx` et `template.tex` (fichiers réels à fournir par la rédaction, ou placeholders en attendant). Ajouter les clés de traduction dans `lang/fr.php`, `lang/en.php`, `lang/ln.php` (ex. `instructions.download_models`, `instructions.download_models_intro`, `instructions.download_word`, `instructions.download_latex`).

Référence : **documentation.txt** (section 11) et **DOCUMENTATION_COMPLETE.md** — « Modèle Word / LaTeX téléchargeable ».

### 2.9 Comité éditorial — comité permanent

La revue a instauré un **comité éditorial permanent** pour toutes les années (un seul comité, pas de comité différent par volume/année).

Le **nouveau site** (`views/public/comite.php`) affiche déjà les comités **globaux** (Comité de rédaction, Comité scientifique) depuis les paramètres de la revue (`revue_info` : `comite_redaction`, `comite_scientifique`). Cette approche correspond au choix du comité permanent.

**Conclusion** : Aucune évolution à prévoir pour la page Comité éditorial. Les contenus se gèrent via **Administration → Paramètres revue** (champs Comité scientifique, Comité de rédaction). L’ancien site proposait en plus des comités par année (rédacteur en chef et membres par volume) ; ce n’est pas retenu ici.

### 2.10 Récapitulatif des éléments manquants (vérification complète)

D’après la comparaison **revue_ancien**, **revue-theologie-upc-html**, **DOCUMENTATION_COMPLETE.md** et **documentation.txt**, les éléments suivants ont été identifiés comme manquants ou à compléter sur le nouveau site :

| Élément | Où (ancien / doc) | Statut nouveau | Action |
|--------|-------------------|-----------------|--------|
| Télécharger les modèles (Word, LaTeX) | Instructions | Manquant | § 2.8 |
| Résilier l’abonnement | Abonnement auteur | Manquant | § 2.3 + Phase B |
| Comité éditorial (permanent) | Paramètres revue | ✓ OK (comité unique, pas par année) | — |
| Vue admin détail article | Admin | Vue absente | A1 |
| Valider/Refuser paiement | Admin paiements | Lecture seule | A2 |
| Conditions, Confidentialité | Pages légales | Manquantes | A3 |
| Notifications header | Header connecté | Manquant | A4 |
| Profil auteur/évaluateur | Espaces connectés | Manquant | B3 |
| Page S’abonner (paiement) | Auteur | Manquant | B1 |
| Historique révisions article | Auteur | Manquant | B2 |
| Page Admin Évaluations | Admin | Manquant | B4 |
| Télécharger reçu paiement | Abonnement auteur | À vérifier (champ `recu_path`) | Optionnel |

---

## 3. Plan de travail proposé (priorisé)

### Phase A — Priorité haute (fonctionnel et légal)

| # | Tâche | Fichiers / actions |
|---|--------|---------------------|
| A1 | **Vue admin détail article** | Créer `views/admin/article-detail.php` : affichage article, liste évaluations, formulaire assignation évaluateur, formulaire rattachement numéro, bouton(s) changement statut. |
| A2 | **Actions paiements (Valider / Refuser)** | Dans `views/admin/paiements.php` : boutons par ligne. Ajouter route `POST /admin/paiement/[id]/statut` et méthode dans `AdminController` + mise à jour `PaiementModel`. |
| A3 | **Pages Conditions et Confidentialité** | Créer `views/public/conditions.php` et `views/public/confidentialite.php`, routes dans `web.php`, contenu (statique ou BDD). Ajouter liens dans le footer et dans `PLAN_TRADUCTION` si traduction prévue. |
| A4 | **Notifications dans le header (connecté)** | Dans `views/layouts/header.php` : afficher un bloc « Notifications » (icône + badge) pour les utilisateurs connectés, avec dropdown ou lien vers `/author/notifications` ou `/reviewer/notifications` selon le rôle. Optionnel : API/endpoint pour liste courte en AJAX. |
| A5 | **Instructions aux auteurs — Télécharger les modèles** | Dans `views/public/instructions-auteurs.php` : ajouter une section « Télécharger les modèles » avec le texte « Utilisez impérativement l'un des modèles suivants pour préparer votre manuscrit », lien **Télécharger le template Word** (ex. `public/templates/template.docx`), lien **Télécharger le template LaTeX** (ex. `public/templates/template.tex`). Ajouter les clés i18n (`instructions.download_models`, `instructions.download_word`, `instructions.download_latex`). Créer le dossier `public/templates/` et y placer ou prévoir les fichiers (ou placeholders). |

### Phase B — Priorité moyenne (parité avec l’ancien)

| # | Tâche | Fichiers / actions |
|---|--------|---------------------|
| B1 | **Abonnement payant (s’abonner)** | Page « S’abonner » (formules, montant) ; intégration paiement (M-Pesa / Orange ou autre) ; enregistrement en BDD ; redirection et notification. Dépend des choix techniques (API paiement, webhook). |
| B2 | **Historique des révisions (auteur)** | Modèle/référence « révisions » par article (si pas déjà en BDD) ; page ou section « Révisions » dans l’espace auteur (timeline par article) ; lien depuis détail article auteur. |
| B3 | **Profil utilisateur (auteur et évaluateur)** | Pages « Mon profil » : formulaire (nom, prénom, email, mot de passe). Routes ex. `/author/profil`, `/reviewer/profil` ; contrôleurs ; mise à jour `UserModel`. |
| B4 | **Page Admin Évaluations** | Liste des évaluations (toutes ou par article) avec statuts. Vue `views/admin/evaluations.php`, route, méthode `AdminController::evaluations`. Optionnel : détail d’une évaluation en lecture seule. |
| B5 | **Résilier l’abonnement (espace auteur)** | Dans `views/author/abonnement.php` : afficher un bouton « Résilier l’abonnement » lorsque l’abonnement est actif (avec confirmation). Ajouter route `POST /author/abonnement/cancel` (ou avec id), méthode `AuthorController::abonnementCancel`, mise à jour du statut dans `AbonnementModel` (ex. `annule` ou `resilie`). Clés i18n : `author.cancel_subscription`, `author.cancel_subscription_confirm`. |
### Phase C — Priorité plus basse (enrichissement)

| # | Tâche | Fichiers / actions |
|---|--------|---------------------|
| C1 | **Enrichir l’accueil** | Section « Dernier numéro » (couverture, description, CTA). Section « Actualités » (dernières actualités ou lien vers `/actualites`). Section « Publiez votre recherche » (CTA + avantages). Bloc « À propos » avec chiffres (années, nombre d’articles). Newsletter (formulaire + enregistrement email en BDD ou envoi mail). |
| C2 | **Articles en vedette (plusieurs)** | Sur l’accueil, afficher 2–3 articles « choix de la rédaction » (au lieu d’un seul) avec cartes (titre, auteur, extrait, lien). |
| C3 | **Footer** | Vérifier et compléter : Navigation, Ressources, Suivez-nous (réseaux sociaux), ISSN. Traduire les libellés si besoin. |
| C4 | **Switch de rôle (admin ↔ évaluateur)** | Menu utilisateur : si rôle principal = admin, afficher option « Basculer vers Espace évaluateur » (et inversement) avec mise à jour de session. |
| C5 | **Détails volume / numéro (admin)** | Vues détail + édition volume et numéro si la rédaction en a besoin (sinon garder la liste actuelle). |

---

## 4. Ordre recommandé pour avancer

1. **A1** (vue admin article-detail) — indispensable pour assigner des évaluateurs et rattacher les articles aux numéros.
2. **A2** (actions paiements) — nécessaire pour valider les abonnements / paiements.
3. **A3** (Conditions + Confidentialité) — conformité et confiance.
4. **A4** (notifications header) — meilleure UX pour les utilisateurs connectés.
5. **A5** (Instructions — télécharger les modèles Word/LaTeX) — demandé explicitement, rapide à mettre en place.
6. **B3** (profil utilisateur) — demande courante.
7. **B4** (page admin Évaluations) — suivi éditorial.
8. **B5** (résilier l’abonnement) — parité avec l’ancien, attendu côté auteur.
9. **B2** (révisions article) — traçabilité pour l’auteur.
10. **B1** (abonnement payant) — selon priorité métier et disponibilité des APIs paiement.
11. **C1 à C5** — selon le temps disponible et la priorité contenu/identité.

---

## 5. Fichiers à créer ou modifier (résumé)

- **À créer** : `views/admin/article-detail.php`, `views/public/conditions.php`, `views/public/confidentialite.php`, `views/admin/evaluations.php` (phase B4), vues profil auteur/reviewer (phase B3), page « S’abonner » + logique paiement (phase B1), éventuellement révisions auteur (phase B2). Dossier `public/templates/` avec `template.docx` et `template.tex` (ou placeholders) pour les modèles (phase A5).
- **À modifier** : `views/layouts/header.php` (notifications), `views/admin/paiements.php` (boutons + formulaire/JS), `views/public/instructions-auteurs.php` (section « Télécharger les modèles » + liens Word/LaTeX), `views/author/abonnement.php` (bouton « Résilier l’abonnement » + confirmation), `routes/web.php` (nouvelles routes dont `POST /author/abonnement/cancel`), `controllers/AuthorController.php` (abonnementCancel), `controllers/AdminController.php` (paiement statut, evaluations), `models/AbonnementModel.php` (méthode annuler/résilier), `views/public/index.php` et/ou layout accueil (sections C1–C2), footer (liens, réseaux, ISSN).
- **Traduction** : ajouter les clés pour les nouvelles pages et libellés (Conditions, Confidentialité, Profil, Évaluations admin, **instructions.download_models**, **instructions.download_word**, **instructions.download_latex**, **author.cancel_subscription**, **author.cancel_subscription_confirm**, etc.) dans `lang/fr.php`, `lang/en.php`, `lang/ln.php` selon le plan existant.

---

Tu peux demander par exemple : « On fait A1 » ou « On fait la phase A » et on enchaînera les modifications étape par étape.
