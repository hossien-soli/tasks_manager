<?php

namespace App\Controllers;

use App\Util\Validator;

class TasksController extends Controller
{
    public function store ($request,$response)
    {
        $validator = new Validator;
        $validation = $validator->make($request->getParsedBody(),[
            'body' => 'required|min:5',
        ]);
        $validation->validate();
        if ($validation->fails()) {
            $errors = $validation->errors();
            $_SESSION['errors'] = $errors;
            $this->flash->addMessage('error','Some error are found !<br/>Please check the form.');
            return $response->withRedirect($this->router->pathFor('auth.dashboard'));
        }
        else {
            $this->auth->user()->tasks()->create([
                'body' => $request->getParsedBodyParam('body'),
            ]);

            $this->flash->addMessage('info','New task successfully added !');
            return $response->withRedirect($this->router->pathFor('auth.dashboard'));
        }
    }
}