<?php  
  namespace App;
  
  class App
  {
      public static function view(string $view,array $loading = [])
      {
          extract($loading);
          $viewPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $view) . ".php";
          
          // Normaliser le chemin pour Windows
          $viewPath = realpath($viewPath) ?: $viewPath;
          
          if (!file_exists($viewPath)) {
              throw new \Exception("Vue introuvable : {$viewPath}");
          }
          
          require $viewPath;
      }
      public function jsonResponse($array = [])
      {
          header('Content-Type: application/json; charset=utf-8');
          if($array !== null && is_array($array))
          {
              http_response_code($array['status']);
              echo json_encode($array);
          }
          die();
      }
      
  }
?>