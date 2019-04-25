<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    protected $table = 'users';
    protected $fillable = ['name','email','username','password','activehash','imagepath'];

    public function tasks ()
    {
        return $this->hasMany(Task::class);
    }
}