<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = ['user_id', 'title', 'advertisement', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
