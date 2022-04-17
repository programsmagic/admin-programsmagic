<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{

    protected $fillable = ['descriptionable_type' , 'descriptionable_id', 'description'];

    public function post()
    {
        return $this->morphTo('descriptionable');
    }
}
