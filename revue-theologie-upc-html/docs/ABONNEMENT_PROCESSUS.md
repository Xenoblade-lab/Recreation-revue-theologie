# Processus d'abonnement — Revue (ancien projet → nouveau)

Ce document décrit le fonctionnement de l'abonnement dans l'ancien projet (`revue-ancien`) et sert de référence pour l’implémentation ou l’alignement dans le nouveau projet (`revue-theologie-upc-html`).

---

## 1. Rôles et accès

- **`user`** : utilisateur inscrit, pas encore auteur (pas d’abonnement actif ou abonnement résilié).
- **`auteur`** : utilisateur avec abonnement actif (ou rôle mis à jour après souscription).

L’abonnement est ce qui « donne » le statut auteur : après souscription réussie, le rôle en base passe de `user` à `auteur`.

---

## 2. Comportement pour un simple **user** (pas auteur)

| Action | Comportement |
|--------|--------------|
| Accède à `/author` (ou toute page auteur sauf S’abonner) | **Redirection vers `/author/subscribe`** (page S’abonner). |
| Accède à `/author/subscribe` | **Accès autorisé** : voit la page S’abonner (tarifs, choix de région, paiement). |
| Valide un abonnement (paiement) | Création paiement + abonnement, **rôle → `auteur`**, redirection vers `/author`. |
| Auteur résilie (plus d’abonnement actif) | **Rôle → `user`** ; à la prochaine visite sur `/author`, redirection vers `/author/subscribe`. |

En résumé : sans abonnement actif, un **user** ne peut pas utiliser l’espace auteur ; il est renvoyé sur S’abonner. Après souscription, il devient auteur.

**Lecture des articles sur le site public :** un simple user peut uniquement **lire le résumé** des articles ; il ne peut **pas** lire l'article en entier ni **télécharger le PDF**. Seuls les **auteurs** (rôle auteur / abonnement actif) peuvent consulter le contenu complet et télécharger le PDF.

| Utilisateur | Résumé | Article en entier | Téléchargement PDF |
|-------------|--------|--------------------|---------------------|
| Non connecté / simple user | Oui | Non | Non |
| Auteur (abonnement actif) | Oui | Oui | Oui |

À implémenter dans le nouveau projet : vérifier rôle ou abonnement actif avant d'afficher le contenu complet et avant d'autoriser le téléchargement (page détail article + route download).

---

## 3. Contrôle d’accès côté serveur (ancien projet)

- **`requireAuthorOrSubscribe()`**  
  - Utilisateur connecté (et pas admin).  
  - Utilisé pour : **GET/POST `/author/subscribe`** (page S’abonner et soumission du formulaire).  
  - Un **user** peut donc voir et soumettre la page S’abonner.

- **`requireAuthor()`**  
  - Utilisateur connecté + soit **rôle auteur** en session, soit **abonnement actif** en base.  
  - Utilisé pour : dashboard auteur, abonnement, articles, profil, etc.  
  - Si **user** sans abonnement actif → **redirection vers `/author/subscribe`**.

---

## 4. Page « S’abonner » (`/author/subscribe` — GET)

- Titre type : « Devenez Auteur ».
- **Choix de la région** (3 cartes) :
  - **Afrique** : 25 $
  - **Europe** : 30 $
  - **Amérique** : 35 $
- Durée : **1 an** (fixe).
- Bouton « S’abonner » activé après sélection d’une région.

---

## 5. Modal « Choisir un moyen de paiement »

- Ouverture après clic sur « S’abonner ».
- **Résumé** : région, durée (1 an), total en $.
- **4 moyens** : Orange Money, M-Pesa, Airtel Money, Paiement bancaire.
- Selon le moyen :
  - **Mobile Money** : champ « Numéro de téléphone ».
  - **Bancaire** : numéro de carte, date d’expiration, CVC, nom sur la carte.
- Bouton « Valider le paiement ».

---

## 6. Traitement côté serveur (POST `/author/subscribe`)

- Vérifications : moyen de paiement, région, et selon le cas téléphone ou champs carte.
- **Tarifs** : Afrique 25 $, Europe 30 $, Amérique 35 $.
- En **une transaction** :
  1. Création d’un **paiement** : table `paiements` (utilisateur, montant, moyen, statut `en_attente`).
  2. **Simulation** : le paiement est immédiatement passé à `valide` et `date_paiement` renseignée (pas d’API réelle).
  3. Création d’un **abonnement** : table `abonnements` (utilisateur, `date_debut` = aujourd’hui, `date_fin` = +1 an, statut `actif`).
  4. Mise à jour du **rôle** : `users.role` = `auteur`.
  5. Mise à jour de la **session** : `user_role` et `active_role` = `auteur`.
- Réponse JSON : `success`, `message`, `redirect` vers `/author`.

---

## 7. Page « Abonnement & Paiements » (`/author/abonnement` — GET)

- **Si abonnement actif** : badge statut, dates début/fin, jours restants, boutons « Renouveler » (si ≤ 30 jours) et « Résilier ».
- **Si expiré** : message + bouton « Renouveler l’abonnement ».
- **Si en attente** : message « En attente de validation ».
- **Si aucun abonnement** : message + bouton « S’abonner maintenant ».
- **Historique des paiements** : tableau (date, montant, moyen, statut) avec :
  - Détails (modal),
  - Téléchargement reçu (HTML),
  - Annulation si statut `en_attente`.

---

## 8. Résiliation (POST `/author/abonnement/cancel`)

- Corps JSON : `{ "abonnement_id": id }`.
- Vérifications : abonnement appartient à l’utilisateur et statut `actif`.
- Mise à jour : `abonnements.statut` = `expire`.
- Si plus aucun abonnement actif : **rôle repassé à `user`**, session mise à jour.
- Réponse JSON : `success` / `error`.

**Note (nouveau projet)** : la colonne `statut` de `abonnements` est un ENUM `('en_attente','actif','refuse','expire')`. Il n’y a pas de valeur `annule` ; on utilise donc **`expire`** pour la résiliation.

---

## 9. Annulation d’un paiement (POST `/author/paiement/cancel`)

- Pour un paiement en statut **`en_attente`** uniquement.
- Mise à jour : `paiements.statut` = `refuse`.

---

## 10. Reçu (GET `/author/paiement/receipt/[id]`)

- Génération d’un HTML de reçu.
- Enregistrement du chemin en base (`recu_path`).
- Téléchargement du fichier.

---

## 11. Synthèse pour le nouveau projet

| Étape | Ancien | À avoir dans le nouveau |
|-------|--------|--------------------------|
| Qui peut s’abonner | Utilisateur connecté (rôle `user` ou déjà `auteur`) | Idem : utilisateur connecté (éventuellement rôle `user` pour « devenir auteur »). |
| Choix formule | Région (Afrique / Europe / Amérique) → tarif 25 / 30 / 35 $, durée 1 an | Formules possibles : région + durée 1 an (ou équivalent). |
| Paiement | Modal avec 4 moyens (Orange, M-Pesa, Airtel, bancaire) ; formulaire téléphone ou carte ; **simulation** (paiement mis `valide` immédiatement). | Même UX possible ; côté serveur : simulation ou paiement en attente + validation admin. |
| Après paiement | 1 paiement + 1 abonnement (1 an, `actif`) + rôle `auteur` + redirection dashboard. | Même enchaînement : créer paiement, créer/activer abonnement, mettre à jour rôle auteur si besoin. |
| Page abonnement | Statut abonnement + historique paiements + résilier + reçu. | Page abonnement avec historique, résilier, reçu. |
| Résiliation | POST JSON, abonnement → `expire`, rôle → `user` si plus d’abonnement actif. | Utiliser `expire` pour le statut ; retirer le rôle auteur si plus d’abonnement actif. |
| User sans abonnement va sur `/author` | Redirection vers `/author/subscribe`. | À reproduire : contrôle d’accès auteur basé sur rôle ou abonnement actif, sinon redirection vers S’abonner. |

---

## Fichiers de référence (ancien projet)

- `revue-ancien/controllers/AuthorController.php` : `requireAuthorOrSubscribe()`, `requireAuthor()`, `subscribe()`, `createSubscription()`, `abonnement()`, `cancelSubscription()`, `cancelPayment()`, `downloadReceipt()`.
- `revue-ancien/views/author/subscribe.php` : page S’abonner (tarifs, modal paiement).
- `revue-ancien/views/author/abonnement.php` : page Abonnement & Paiements (statut, historique, résilier, reçu).
- `revue-ancien/routes/web.php` : routes `/author/subscribe`, `/author/abonnement`, `/author/abonnement/cancel`, `/author/paiement/cancel`, `/author/paiement/receipt/[id]`.
