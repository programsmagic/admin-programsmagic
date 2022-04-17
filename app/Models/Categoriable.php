<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoriable extends Model
{
    protected $fillable = [
        'categoriable_type',
        'categoriable_id',
        'category_id',
    ];
    public $timestamps = false;
}
