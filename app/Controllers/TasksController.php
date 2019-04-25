<?php

namespace App\Controllers;

use App\Util\Validator;
use App\Models\Task;
use App\Auth\Gate;

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

    public function destroy ($request,$response)
    {
        $taskId = $request->getParsedBodyParam('taskId');
        if ($this->gate->canModifyTask($taskId)) {
            Task::destroy($taskId);
            $this->flash->addMessage('info','The task successfully removed !');
        }
        else
            $this->flash->addMessage('error','This task doesn\'t belongs to you !');

        return $response->withRedirect($this->router->pathFor('auth.dashboard'));
    }

    public function complateTask ($request,$response)
    {
        $taskId = $request->getAttribute('taskId');
        if ($this->gate->canModifyTask($taskId)) {
            $task = Task::find($taskId);
            $task->complated = true;
            $task->save();
            $this->flash->addMessage('info','Process successful !');
        }
        else
            $this->flash->addMessage('error','This task doesn\'t belongs to you !');

        return $response->withRedirect($this->router->pathFor('auth.dashboard'));
    }
}