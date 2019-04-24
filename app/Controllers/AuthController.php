<?php

namespace App\Controllers;

class AuthController extends Controller
{
    public function registerGET ($request,$response)
    {
        return $this->view->render($response,'auth/register.twig',[
            'title' => 'Register',
        ]);
    }

    public function registerPOST ($request,$response)
    {
        $form_data = $request->getParsedBody();
        
    }
}