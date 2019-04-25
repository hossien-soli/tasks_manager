<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Task extends Eloquent
{
    protected $table = 'tasks';
    protected $fillable = ['title','slug','body'];

    public function user ()
    {
        return $this->belongsTo(User::class);
    }
}