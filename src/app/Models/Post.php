<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'body', 'is_completed'];

    protected $casts = ['is_completed' => 'boolean'];
}
