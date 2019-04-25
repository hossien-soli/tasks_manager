<?php

$app->get('/','MainController:homeGET')->setName('main.home');
$app->get('/about','MainController:aboutGET')->setName('main.about');

$app->get('/register','AuthController:registerGET')->setName('auth.register');
$app->post('/register','AuthController:registerPOST');

$app->group('/account',function ($app) {
    $app->get('/login','AuthController:loginGET')->setName('auth.login');
    $app->post('/login','AuthController:loginPOST');

    $app->get('/dashboard','AuthController:dashboardGET')->setName('auth.dashboard');
    $app->get('/logout','AuthController:logoutGET')->setName('auth.logout');
});

$app->get('/test',function () {
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
});