<?php

namespace App;

use App\Models\Advertisement;
use App\Models\Post;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'bio', 'photo', 'type',
    ];
    public $appends = ['img'];

    public function post()
    {
        return $this->hasMany(Post::class);
    }
    public function ads()
    {
        return $this->hasMany(Advertisement::class,'user_id');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getImgAttribute()
    {
     return URL::to('/').'/img/profile/'.$this->photo;
    }
}
