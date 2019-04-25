<?php

$app->get('/','MainController:homeGET')->setName('main.home');
$app->get('/about','MainController:aboutGET')->setName('main.about');

$app->get('/register','AuthController:registerGET')->setName('auth.register');
$app->post('/register','AuthController:registerPOST');

$app->group('/account',function ($app) {
    $app->get('/login','AuthController:loginGET')->setName('auth.login');
});