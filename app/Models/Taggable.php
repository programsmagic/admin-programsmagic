<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taggable extends Model
{
    protected $fillable = ['taggable_type','taggable_id','tag_id'];
    public $timestamps = false;
}
