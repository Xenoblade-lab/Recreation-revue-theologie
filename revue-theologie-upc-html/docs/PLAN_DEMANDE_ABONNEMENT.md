# Plan : Demande d'abonnement et abonnement (étape par étape)

Ce document décrit les étapes à suivre pour implémenter le flux complet **demande d'abonnement** (utilisateur) puis **validation et abonnement** (admin), sans approbation automatique au clic.

**État actuel :** Partie 1 et Partie 2 sont implémentées. Pensez à **exécuter la migration** (étape 1.1, une fois) si ce n'est pas déjà fait.

---

## Vue d'ensemble du flux

1. **Utilisateur** : choisit la région → ouvre le modal « Choisir un moyen de paiement » → remplit les infos (téléphone ou carte) → clique « Valider le paiement ».
2. **Système** : crée un **paiement en attente** (pas d'abonnement, pas de changement de rôle). Optionnel : notifier les admins.
3. **Admin** : consulte les paiements en attente, valide ou refuse.
4. **Système (quand admin valide)** : crée l'abonnement, met le rôle à `auteur`, notifie l'utilisateur.

Référence interface : `revue-ancien/views/author/subscribe.php` (cartes région, modal, formulaire téléphone/carte).

---

## Partie 1 — Demande d'abonnement (côté utilisateur)

### Étape 1.1 — Base de données (paiements)

- [x] **Migration** : ajouter les colonnes optionnelles sur `paiements` (si pas déjà fait).
  - Fichier : `migrations/add_paiement_region_details.sql`
  - Colonnes : `region` (VARCHAR 50), `payment_details` (TEXT, pour JSON : téléphone ou derniers chiffres carte).
- [ ] **À faire** : exécuter la migration sur la base (une seule fois) : `mysql -u ... -p ... < migrations/add_paiement_region_details.sql` ou via phpMyAdmin.

### Étape 1.2 — Page S'abonner : sélection de la région

- [x] La page `/author/s-abonner` affiche les **3 cartes région** (Afrique 25 $, Europe 30 $, Amérique 35 $).
- [x] Le **bouton principal** affiche le montant selon la région choisie (« S'abonner - XX $ ») et **ouvre un modal** au clic.

### Étape 1.3 — Modal « Choisir un moyen de paiement »

- [x] Au clic sur « S'abonner - XX $ », ouverture d'un **modal** avec :
  - Titre : « Choisir un moyen de paiement »
  - **Résumé de l'abonnement** : région, durée (1 an), total (XX $).
  - **4 moyens** : Orange Money, M-Pesa, Airtel Money, Paiement bancaire (boutons cliquables).
  - Bouton « Valider le paiement » désactivé tant qu'un moyen n'est pas choisi et le formulaire pas rempli.

### Étape 1.4 — Formulaire « Informations de paiement » dans le modal

- [x] Selon le moyen choisi, formulaire différent :
  - **Mobile Money** : champ **Numéro de téléphone** (+243…), texte d'aide.
  - **Paiement bancaire** : **Numéro de carte**, **Date d'expiration** (MM/AA), **CVC**, **Nom sur la carte**.
- [x] Validation côté client pour activer « Valider le paiement ».

### Étape 1.5 — Soumission de la demande (sans activer l'abonnement)

- [x] POST vers `/author/s-abonner` : `formule_id`, `moyen`, `phoneNumber` ou champs carte.
- [x] **Côté serveur** : `PaiementModel::createDemandeAbonnement()` uniquement (paiement `en_attente`), pas d'abonnement ni changement de rôle.
- [x] Redirection vers `/author/abonnement` avec message « Votre demande d'abonnement a été enregistrée… » (`subscribe_pending_success`).

### Étape 1.6 — Notifier les admins (optionnel mais recommandé)

- [x] Après création du paiement, notification pour chaque admin / rédacteur en chef : `UserModel::getIdsByRole('admin', 'redacteur en chef')` puis `NotificationModel::create(..., 'subscription_request', ...)`.

---

## Partie 2 — Validation et abonnement (côté admin)

### Étape 2.1 — Page admin Paiements

- [x] La page `/admin/paiements` liste les paiements (utilisateur, montant, moyen, statut, date).
- [x] Les paiements **en_attente** sont visibles avec les boutons **Valider** et **Refuser** pour chaque ligne.

### Étape 2.2 — Action « Valider » un paiement

- [x] Lorsque l'admin met le statut à **valide** (`paiementStatut`) :
  1. Mise à jour du paiement : `PaiementModel::setValide($id)` → `statut = 'valide'`, `date_paiement = NOW()`.
  2. **Création de l'abonnement** : `AbonnementModel::create($utilisateur_id)` (1 an, statut `actif`), uniquement si le paiement était `en_attente`.
  3. **Mise à jour du rôle** : `UserModel::updateRole($utilisateur_id, 'auteur')`.
  4. **Notification utilisateur** : `NotificationModel::create(..., 'subscription_approved', ...)` avec lien vers `/author`.
- [x] La session admin n'est pas modifiée ; l'utilisateur voit son nouveau rôle à la prochaine connexion ou après rafraîchissement.

### Étape 2.3 — Action « Refuser » un paiement

- [x] Lorsque l'admin met le statut à **refuse** : mise à jour du paiement via `PaiementModel::updateStatut($id, 'refuse')`.
- [x] Notification de l'utilisateur : `NotificationModel::create(..., 'subscription_refused', ...)` avec lien vers `/author/s-abonner`.

---

## Partie 3 — Récapitulatif technique (fichiers concernés)

| Étape | Fichier(s) | Action |
|-------|------------|--------|
| 1.1 | `migrations/add_paiement_region_details.sql` | Créer / exécuter la migration. |
| 1.2–1.4 | `views/author/s-abonner.php` | Refonte : bouton ouvre un modal ; contenu du modal (résumé, 4 moyens, formulaire téléphone/carte). |
| 1.2–1.4 | `public/js/` (optionnel) | Script pour modal, choix du moyen, validation des champs, envoi POST (form ou fetch). |
| 1.5 | `controllers/AuthorController.php` | `sAbonnerSubmit` : créer paiement en_attente uniquement (createDemandeAbonnement), pas d'abonnement ni rôle. |
| 1.5 | `models/PaiementModel.php` | Méthode `createDemandeAbonnement()` (déjà ajoutée si tu as suivi le plan précédent). |
| 1.6 | `controllers/AuthorController.php` + `models/UserModel.php` | Après création du paiement : récupérer les ids admin, créer une notification par admin. |
| 2.1 | `views/admin/paiements.php` | Vérifier affichage des statuts et boutons Valider / Refuser. |
| 2.2 | `controllers/AdminController.php` | Dans `paiementStatut` : si nouveau statut = `valide`, alors créer abonnement, update rôle, notifier l'utilisateur. |
| 2.2 | `models/AbonnementModel.php`, `UserModel.php`, `NotificationModel.php` | Utiliser create, updateRole, create. |
| 2.3 | `controllers/AdminController.php` | Gérer le cas `refuse` (optionnel : notification utilisateur). |

---

## Ordre suggéré pour implémenter

1. **1.1** — Migration (pour pouvoir stocker région + détails).
2. **1.5** — Côté serveur : création paiement en_attente uniquement (sans abonnement ni rôle).
3. **1.2 → 1.4** — Interface : modal + formulaire paiement (moyen + infos).
4. **1.6** — Notification des admins.
5. **2.1 → 2.3** — Admin : validation / refus + création abonnement et rôle + notification utilisateur.

Tu peux cocher les cases au fur et à mesure dans ce fichier pour suivre l'avancement.
