<?php

$app->get('/',function ($request,$response) {
    return $this->view->render($response,'main/home.twig',[
        'title' => 'Tasks Manager | Home',
    ]);
});