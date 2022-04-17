<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function posts(){
        return Post::all();
    }
}
