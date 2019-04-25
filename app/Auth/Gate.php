<?php

namespace App\Auth;

use App\Models\Task;

class Gate
{
    protected $container;

    public function __construct ($container)
    {
        $this->container = $container;
    }

    public function canModifyTask ($taskId)
    {
        $taskUserId = Task::find($taskId)->user->id;
        return $this->container->auth->user()->id == $taskUserId;
    }
}