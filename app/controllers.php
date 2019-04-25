<?php

use App\Controllers\MainController;
use App\Controllers\AuthController;
use App\Controllers\TasksController;

$container['MainController'] = function ($container) {
    return new MainController ($container);
};

$container['AuthController'] = function ($container) {
    return new AuthController ($container);
};

$container['TasksController'] = function ($container) {
    return new TasksController ($container);
};