<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Email extends Model
{
    use Notifiable;
    protected $fillable = ['email','ip_address','device','location'];
}
