<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\RedirectIfAuthenticateMiddleware;

$app->get('/','MainController:homeGET')->setName('main.home');
$app->get('/about','MainController:aboutGET')->setName('main.about');

$app->group('',function ($app) {
    $app->get('/register','AuthController:registerGET')->setName('auth.register');
    $app->post('/register','AuthController:registerPOST');
})->add(new RedirectIfAuthenticateMiddleware ($app->getContainer()));

$app->group('/account',function ($app) {
    $app->group('',function ($app) {
        $app->get('/login','AuthController:loginGET')->setName('auth.login');
        $app->post('/login','AuthController:loginPOST');
    })->add(new RedirectIfAuthenticateMiddleware ($app->getContainer()));

    $app->group('',function ($app) {
        $app->get('/dashboard','AuthController:dashboardGET')->setName('auth.dashboard');
        $app->post('/logout','AuthController:logoutPOST')->setName('auth.logout');
    })->add(new AuthMiddleware ($app->getContainer()));
});

$app->group('/tasks',function ($app) {
    $app->post('/','TasksController:store')->setName('tasks.store');
    $app->post('/delete','TasksController:destroy')->setName('tasks.destroy');
})->add(new AuthMiddleware ($app->getContainer()));

$app->get('/test',function () {
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
});