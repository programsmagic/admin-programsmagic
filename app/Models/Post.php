<?php

namespace App\Models;


use App\Models\Description;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Post extends Model
{
    use Notifiable;
    const PUBLISHED = 'published';
    const DRAFT = 'draft';
    protected $fillable = [
        'user_id',
        'title',
        'short_description',
        'slug',
        'img',
        'status',
        'view_counts',
    ];
    protected $appends = ['category_name'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->morphToMany(Category::class, 'categoriable');
    }

    public function description()
    {
        return $this->morphMany(Description::class, 'descriptionable');
    }


    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

//    public function getTagsAttribute()
//    {
//        $row = $this->tags()->get();
//        if (!$row) {
//            return null;
//        }
//        return $row;
//    }
//
    public function getCategoryNameAttribute()
    {
        $row = $this->category()->first();
        if ($row!=null) {
            return $row->category;
        }
        return 'undefined';
    }
//
//    public function getDescriptionAttribute()
//    {
//        $row = $this->description()->first();
//        if (!$row) {
//            return null;
//        }
//        return $row->description;
//    }



}
