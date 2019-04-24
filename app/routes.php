<?php

$app->get('/','MainController:homeGET')->setName('main.home');
$app->get('/about','MainController:aboutGET')->setName('main.about');

$app->get('/register','AuthController:registerGET')->setName('auth.register');
$app->post('/register','AuthController:registerPOST');