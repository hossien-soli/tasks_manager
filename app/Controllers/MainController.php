<?php

namespace App\Controllers;

class MainController extends Controller
{
    public function homeGET ($request,$response)
    {
        return $this->view->render($response,'main/home.twig',[
            'title' => 'Tasks Manager | Home',
        ]);
    }

    public function aboutGET ($request,$response)
    {
        return $this->view->render($response,'main/about.twig',[
            'title' => 'Tasks Manager | About',
        ]);
    }
}