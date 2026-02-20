<?php 

    Router\Router::post('/api/register', function(){
           header("Content-Type: application/json");
           $datas = json_decode(file_get_contents('php://input'), true) ?? [];
           $auth = new Service\AuthService();
           $auth->sign($datas);
    });

    Router\Router::post('/api/login', function(){
           header("Content-Type: application/json");
           $datas = json_decode(file_get_contents('php://input'), true) ?? [];
           $auth = new Service\AuthService();
           $auth->login($datas);
    });
?>