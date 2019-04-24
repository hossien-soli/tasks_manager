<?php

use App\Controllers\MainController;

$container['MainController'] = function ($container) {
    return new MainController ($container);
};