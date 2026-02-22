# Revue de Théologie UPC — Refactorisation

Projet de refactorisation du site (design + dynamisation) dans ce dossier.  
Référence : [Plan_refactorisation.md](../Plan_refactorisation.md) et [Plan_travail.md](../Plan_travail.md).

## Phase 1 — Terminée

- Structure MVC (config, controllers, models, views, public, router, routes, service, includes).
- Configuration : `config/config.php` (BASE_PATH, BASE_URL, **base de données**).
- **Base de données** : utiliser **uniquement** la base dans laquelle vous avez importé **`frontend/revue_theologie_2.sql`** (ne pas utiliser l’ancienne base pour éviter toute erreur). Modifier `DB_NAME` dans `config/config.php` si le nom de votre base est différent de `revue`.
- Point d’entrée : `public/index.php` + `public/.htaccess`.
- Assets : `public/css/`, `public/js/`, `public/images/` (copiés depuis `frontend/`).
- Layouts : `views/layouts/header.php`, `footer.php`, `main.php`.

## Lancer le site (Laragon)

1. Vérifier que la base de données existe et que `frontend/revue_theologie_2.sql` a été importé.
2. Adapter si besoin `config/config.php` : `DB_NAME`, `DB_USER`, `DB_PASS`.
3. Adapter si besoin `public/.htaccess` : `RewriteBase` doit correspondre au chemin sous lequel le site est servi (ex. `/Recreation-revu-theologie/revue-theologie-upc-html/public/`).
4. Ouvrir dans le navigateur :  
   `http://localhost/Recreation-revu-theologie/revue-theologie-upc-html/public/`  
   (ou l’URL de votre vhost si vous en avez un).

Vous devez voir la page d’accueil avec le message « Phase 1 — Infrastructure en place ».

## Suite

Passer à la **Phase 2** (pages publiques) selon [Plan_travail.md](../Plan_travail.md).
