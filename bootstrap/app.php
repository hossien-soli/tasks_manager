<?php

require "../vendor/autoload.php";

use \Slim\App as SlimApp;
use \Slim\Views\Twig;
use \Slim\Views\TwigExtension;

use \App\Util\Config;

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

    return $view;
};

$container['config'] = function ($container) {
    $app_mode = file_get_contents(INC_ROOT . '/mode');
    $config_file = sprintf(INC_ROOT . '/config/%s.php',$app_mode);
    return new Config ($config_file);
};


require INC_ROOT . '/app/controllers.php';
require INC_ROOT . '/app/routes.php';