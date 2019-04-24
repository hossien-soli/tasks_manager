<?php

require "../vendor/autoload.php";

use \Slim\App as SlimApp;

define('INC_ROOT',dirname(__DIR__));
session_start();

$app = new SlimApp ([
    'settings' => [
        'displayErrorDetails' => true,
    ],
]);

