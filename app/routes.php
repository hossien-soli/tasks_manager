<?php

$app->get('/','MainController:homeGET')->setName('main.home');
$app->get('/about','MainController:aboutGET')->setName('main.about');