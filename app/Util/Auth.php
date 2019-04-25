<?php

namespace App\Util;

use App\Models\User;

class Auth
{
    protected $container;

    public function __construct ($container)
    {
        $this->container = $container;
    }

    public function attempt ($username_or_email,$password)
    {
        $user = User::where(function ($query) use ($username_or_email) {
            $query->where('username',$username_or_email)
                    ->orWhere('email',$username_or_email);
        })->first();

        if (!$user)
            return false;
        
        $user_hashed_password = $user->password;
        $hash = new Hash ($this->container->get('config'));
        $password_is_correct = $hash->check($password,$user_hashed_password);

        if ($password_is_correct)
            $_SESSION['logged_in_id'] = $user->id;

        return $password_is_correct;
    }
}