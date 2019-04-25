<?php

namespace App\Auth;

use App\Models\Task;
use App\Models\User;

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

    public function canUseEmail ($email)
    {
        $result = User::where('email',$email)->first();
        return is_null($result);
    }

    public function canUseUsername ($username)
    {
        $result = User::where('username',$username)->first();
        return is_null($result);
    }
}