<?php
   namespace Router;

   use AltoRouter;

   class Router
   {
       private static $router;
      /**
       * @var string $defaultExemple = 'http://localhost:8000/'
       */  
       public static $defaultUri = "http://localhost:8000/";
       
       private static function routing(): AltoRouter{
          if(static::$router == null){
           static::$router = new AltoRouter();
          }
          
          return static::$router;
       }

   
       public static function get(string $route, $target, string $name = '')
       {

           self::routing()->map('GET',$route,$target,$name);
       }

       public static function post(string $route, $target, string $name = '')
       {
          self::routing()->map('POST',$route,$target,$name);
       }

       public static function delete(string $route, $target, string $name = '')
       {
          self::routing()->map('DELETE',$route,$target,$name);
       }

       public static function put(string $route, $target, string $name = '')
       {
          self::routing()->map('PUT',$route,$target,$name);
       }
       
       public static function origin($path)
       {
         self::routing()->setBasePath($path);
       }

       public static function matcher()
       {
           $match = self::routing()->match();
   
          if($match && is_callable($match['target'])){
            call_user_func($match['target'],$match['params']);
          }
          elseif(is_array($match) && is_array($match['target'])){
            $controller = $match['target'][0];
            $method = $match['target'][1];
            $controller = new $controller();
            $controller->$method($match['params']);
          }

          else{
            self::respondNotFound();
          }
       }

       private static function respondNotFound()
       {
           http_response_code(404);
           echo json_encode([
               'status' => 404,
               'message' => 'Route introuvable'
           ]);
       }


      public static function route(string $route, mixed $param = false)
      {
          return $param ?  static::$defaultUri . $route . "/" . $param : static::$defaultUri . $route ;
      }
   }
?>