<?php

return [
    'app' => [
        'name' => 'tasks_manager',
        'title' => 'Tasks Manager',
        'url' => 'http://your-hostname',

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


    'mail' => [
        'smtp_auth' => true,
        'smtp_secure' => 'tls',
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'your-mail-login-username',
        'password' => 'your-mail-login-password',
        'html' => true,
    ],
];