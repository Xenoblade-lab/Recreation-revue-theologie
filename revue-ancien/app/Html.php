<?php 
   namespace App;

   class Html{
        public static function class(string $url){
            return $_SERVER['REQUEST_URI'] === $url ? "active" : "";
        }
   } 
?>