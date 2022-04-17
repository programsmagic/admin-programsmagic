<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
   protected $guarded = [];
   protected $appends = ['post_count'];

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'categoriable');
    }

    public function getPostCountAttribute(){
        return $this->posts()->count();
    }
}
