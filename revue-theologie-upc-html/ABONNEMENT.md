# Processus d'abonnement — Revue Congolaise de Théologie

Ce document décrit le processus d'abonnement tel qu'il fonctionnait dans l'ancien projet et ce qui doit être en place dans le nouveau.

---

## 1. Rôles et accès

| Rôle   | Description |
|--------|-------------|
| **user**  | Utilisateur inscrit, pas encore auteur. Peut uniquement accéder à la page **S'abonner**. |
| **auteur**| Utilisateur avec un abonnement actif (ou rôle auteur en base). Accès complet à l'espace auteur. |

---

## 2. Comportement pour un simple **user** (pas auteur)

| Action | Comportement |
|--------|--------------|
| Va sur `/author` (ou toute page auteur sauf S'abonner) | **Redirection vers `/author/subscribe`** (page S'abonner). |
| Va sur `/author/subscribe` | Voit la page **S'abonner** (tarifs par région, bouton, modal paiement). |
| Valide un abonnement (paiement réussi) | Rôle en base passé à **auteur**, session mise à jour, **redirection vers `/author`** (dashboard). |
| Plus tard : résilie son abonnement | Abonnement → statut **expiré** ; si plus aucun abonnement actif, rôle repasse à **user**. S'il retourne sur `/author`, il est de nouveau redirigé vers **S'abonner**. |

En résumé : un **user** ne peut pas utiliser l'espace auteur tant qu'il n'a pas d'abonnement actif ; il est systématiquement renvoyé sur la page S'abonner. C'est l'abonnement qui « donne » le statut auteur.

---

## 3. Flux détaillé (ancien projet)

### 3.1 Accès à l'espace auteur

- **requireAuthorOrSubscribe()** : utilisateur connecté (et pas admin). Utilisé pour les pages **S'abonner** (GET/POST).
- **requireAuthor()** : en plus, soit rôle **auteur** en session, soit **abonnement actif** en base. Sinon → redirection vers `/author/subscribe`.

### 3.2 Page « S'abonner » (GET `/author/subscribe`)

- Titre type : **« Devenez Auteur »**.
- **Choix de la région** (3 cartes) :
  - **Afrique** : 25 $
  - **Europe** : 30 $
  - **Amérique** : 35 $
- Durée : **1 an** (fixe).
- Bouton « S'abonner » activé après sélection d'une région.

### 3.3 Modal « Choisir un moyen de paiement »

- **Résumé** : région, durée (1 an), total en $.
- **4 moyens** :
  - Orange Money  
  - M-Pesa  
  - Airtel Money  
  - Paiement bancaire  
- Selon le moyen :
  - **Mobile Money** : champ « Numéro de téléphone ».
  - **Bancaire** : numéro de carte, date d'expiration, CVC, nom sur la carte.
- Bouton « Valider le paiement ».

### 3.4 Traitement serveur (POST `/author/subscribe`)

1. Vérification : moyen de paiement, région, et selon le cas téléphone ou champs carte.
2. **Tarifs** : Afrique 25 $, Europe 30 $, Amérique 35 $.
3. En **une transaction** :
   - Création d'un **paiement** : table `paiements` (utilisateur, montant, moyen, statut `en_attente`).
   - **Simulation** : le paiement est immédiatement passé à `valide` et `date_paiement` renseignée (pas d'API réelle).
   - Création d'un **abonnement** : table `abonnements` (utilisateur, `date_debut` = aujourd'hui, `date_fin` = +1 an, statut `actif`).
   - Mise à jour du **rôle** : `users.role` = `auteur`.
   - Mise à jour de la **session** : `user_role` et `active_role` = `auteur`.
4. Réponse JSON : `success`, `message`, `redirect` vers `/author`.

### 3.5 Page « Abonnement & Paiements » (GET `/author/abonnement`)

- **Si abonnement actif** : badge statut, dates début/fin, jours restants, boutons « Renouveler » (si ≤ 30 jours) et « Résilier ».
- **Si expiré** : message + bouton « Renouveler l'abonnement ».
- **Si en attente** : message « En attente de validation ».
- **Si aucun abonnement** : message + bouton « S'abonner maintenant ».
- **Historique des paiements** : tableau (date, montant, moyen, statut) avec :
  - Détails (modal),
  - Téléchargement reçu (HTML),
  - Annulation si statut `en_attente`.

### 3.6 Résiliation (POST `/author/abonnement/cancel`)

- Corps JSON : `{ "abonnement_id": id }`.
- Vérification : abonnement appartient à l'utilisateur et statut `actif`.
- Mise à jour : `abonnements.statut` = `expire`.
- Si plus aucun abonnement actif : rôle repassé à `user`, session mise à jour.
- Réponse JSON : `success` / `error`.

### 3.7 Annulation d'un paiement (POST `/author/paiement/cancel`)

- Pour un paiement en statut `en_attente` uniquement ; passage du paiement à `refuse`.

### 3.8 Reçu (GET `/author/paiement/receipt/[id]`)

- Génération d'un HTML de reçu, enregistrement du chemin en base (`recu_path`), téléchargement du fichier.

---

## 4. Tables et statuts

### abonnements

- **statut** : `en_attente` | `actif` | `refuse` | `expire`
- Résiliation = passage à `expire` (pas de valeur `annule` dans l'enum actuel).

### paiements

- **statut** : `en_attente` | `valide` | `refuse`
- **moyen** : ex. `orange_money`, `mpesa`, `airtel_money`, `bancaire`

---

## 5. À implémenter / aligner dans le nouveau projet

| Élément | Ancien | Nouveau (revue-theologie-upc-html) |
|--------|--------|-------------------------------------|
| User sans abonnement va sur /author | Redirection vers /author/subscribe | À faire : même redirection si rôle user et pas d'abonnement actif |
| Page S'abonner | Choix région (3 cartes) + modal paiement (4 moyens) | Page « S'abonner » existante avec formules ; à enrichir avec modal paiement (optionnel) |
| Après paiement | Paiement + abonnement + rôle auteur | Création paiement/abonnement déjà en place ; mise à jour rôle user→auteur si besoin |
| Résiliation | Abonnement → expire, rôle → user si plus d'abonnement | Statut `expire` déjà utilisé ; à ajouter : retirer rôle auteur si plus d'abonnement actif |
| Reçu téléchargeable | GET receipt/[id], HTML généré | À ajouter si souhaité |
| Annuler un paiement en attente | POST paiement/cancel | À ajouter si souhaité |

---

## 6. Référence code (ancien projet)

- **Contrôleur** : `revue-ancien/controllers/AuthorController.php`  
  - `requireAuthorOrSubscribe()`, `requireAuthor()`  
  - `subscribe()`, `createSubscription()`, `abonnement()`, `cancelSubscription()`, `cancelPayment()`, `downloadReceipt()`
- **Vues** : `revue-ancien/views/author/subscribe.php`, `revue-ancien/views/author/abonnement.php`
- **Routes** : `revue-ancien/routes/web.php` (GET/POST subscribe, GET abonnement, POST abonnement/cancel, POST paiement/cancel, GET paiement/receipt/[id])
