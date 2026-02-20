<?php
      // Configurer les paramètres de session pour une durée de vie plus longue
      if (session_status() === PHP_SESSION_NONE) {
          // Durée de vie de la session : 8 heures (28800 secondes)
          ini_set('session.gc_maxlifetime', 28800);
          
          // Durée de vie du cookie de session : 8 heures
          session_set_cookie_params([
              'lifetime' => 28800, // 8 heures
              'path' => '/',
              'domain' => '',
              'secure' => false, // Mettre à true en production avec HTTPS
              'httponly' => true,
              'samesite' => 'Lax'
          ]);
          
          session_start();
      }
      
      // Rafraîchir la session et le cookie à chaque requête si l'utilisateur est connecté
      if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
          // Initialiser last_activity s'il n'existe pas (pour les sessions existantes)
          if (!isset($_SESSION['last_activity'])) {
              $_SESSION['last_activity'] = time();
          }
          
          // Mettre à jour le timestamp de dernière activité
          $_SESSION['last_activity'] = time();
          
          // Rafraîchir le cookie de session pour prolonger la session
          if (!headers_sent()) {
              setcookie(session_name(), session_id(), time() + 28800, '/', '', false, true);
          }
      }
      
      require dirname(__DIR__) . DIRECTORY_SEPARATOR .'vendor' . DIRECTORY_SEPARATOR .'autoload.php';
      require_once dirname(__DIR__) . DIRECTORY_SEPARATOR .'routes'. DIRECTORY_SEPARATOR .'web.php';
      require_once dirname(__DIR__) . DIRECTORY_SEPARATOR .'routes'. DIRECTORY_SEPARATOR .'api.php';

      // Détecter si c'est une requête JSON/API
      $isJsonRequest = (
          isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false
      ) || (
          isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false
      ) || (
          $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_CONTENT_TYPE']) && strpos($_SERVER['HTTP_CONTENT_TYPE'], 'application/json') !== false
      );
      
      $whoops = new \Whoops\Run();
      
      if ($isJsonRequest) {
          // Pour les requêtes JSON, utiliser un handler JSON
          $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler());
      } else {
          // Pour les autres requêtes, utiliser le handler HTML
          $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
      }
      
      $whoops->register();
      
      $origin = isset($_SERVER['BASE_URI']) ? $_SERVER['BASE_URI'] : '';
      Router\Router::$defaultUri= "http://localhost/Revue-Theologie-Upc/public/";
      Router\Router::origin($origin);
      Router\Router::matcher();
?>
 