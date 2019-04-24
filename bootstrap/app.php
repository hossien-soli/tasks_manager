<?php

require "../vendor/autoload.php";

use \Slim\App as SlimApp;
use \Slim\Views\Twig;
use \Slim\Views\TwigExtension;
use \Slim\Flash\Messages as FlashMessages;

use \App\Util\Config;
use \App\Middleware\ValidationErrorsMiddleware;
use \App\Middleware\OldInputMiddleware;

define('INC_ROOT',dirname(__DIR__));
session_start();

$app = new SlimApp ([
    'settings' => [
        'displayErrorDetails' => true,
    ],
]);

$container = $app->getContainer();

$container['view'] = function ($container) {
    $views_directory = INC_ROOT . '/resources/views/';
    $view = new Twig ($views_directory,[
        'cache' => false,
    ]);

    $router = $container->get('router');
    $uri = $container->get('request')->getUri();
    $view->addExtension(new TwigExtension ($router,$uri));

    $view->getEnvironment()->addGlobal('flash',$container->get('flash'));

    return $view;
};

$container['config'] = function ($container) {
    $app_mode = file_get_contents(INC_ROOT . '/mode');
    $config_file = sprintf(INC_ROOT . '/config/%s.php',$app_mode);
    return new Config ($config_file);
};

$container['flash'] = function ($container) {
    return new FlashMessages;  
};

$users_directory = INC_ROOT . '/public/users';
if (!file_exists($users_directory))
    mkdir($users_directory);
$users_profile_picture_directory = INC_ROOT . '/public/users/profile_pictures';
if (!file_exists($users_profile_picture_directory))
    mkdir($users_profile_picture_directory);
$container['users_profile_picture_directory'] = [
    'local' => $users_profile_picture_directory,
    'public' => '/users/profile_pictures',
];

// add global middleware
$app->add(new ValidationErrorsMiddleware ($app->getContainer()));
$app->add(new OldInputMiddleware ($app->getContainer()));


require INC_ROOT . '/app/database.php';
require INC_ROOT . '/app/controllers.php';
require INC_ROOT . '/app/routes.php';