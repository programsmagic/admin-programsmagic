<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id','name', 'email', 'post_id', 'comment', 'parent_id', 'status'];
}
