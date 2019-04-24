<?php

use App\Controllers\MainController;
use App\Controllers\AuthController;

$container['MainController'] = function ($container) {
    return new MainController ($container);
};

$container['AuthController'] = function ($container) {
    return new AuthController ($container);
};