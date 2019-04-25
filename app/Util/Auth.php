<?php

namespace App\Util;

use App\Models\User;

class Auth
{
    protected $container;
    protected $session_key;

    public function __construct ($container)
    {
        $this->container = $container;
        $this->session_key = 'logged_in_id';
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
            $_SESSION[$this->session_key] = $user->id;

        return $password_is_correct;
    }

    public function check ()
    {
        return isset($_SESSION[$this->session_key]);
    }

    public function user ()
    {
        if ($this->check())
            return User::find($_SESSION[$this->session_key]);
        else
            return null;
    }

    public function logout ()
    {
        if ($this->check())
            unset($_SESSION[$this->session_key]);
    }
}