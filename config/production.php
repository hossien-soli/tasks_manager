<?php

return [
    'app' => [
        'name' => 'tasks_manager',
        'title' => 'Tasks Manager',
        'url' => 'http://localhost/Projects/tasks_manager/public',

        'hash' => [
            'algo' => PASSWORD_BCRYPT,
            'cost' => 10,
        ],
    ],

    'db' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'tasks_manager',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ],
];