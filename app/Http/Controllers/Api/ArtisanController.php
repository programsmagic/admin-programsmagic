<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\OptimizeImages;
use App\Models\Post;
use Buglinjo\LaravelWebp\Facades\Webp;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use WebPConvert\WebPConvert;

class ArtisanController extends Controller
{
    public function setup()
    {
        \Artisan::call('migrate');
        \Artisan::call('passport:install');
        \Artisan::call('cache:clear');
        \Artisan::call('route:clear');
    }

    public function changeImageToWebp()
    {
//        $rows = Post::all();
//        foreach ($rows as $row){
//            $source = public_path().$row->img;
//            $newFile = '/img/posts/'.time().'.webp';
//            $destination = public_path() .'/'. $newFile;
////            $options = [];
//            try {
//                WebPConvert::convert($source, $destination);
//                $row->img = $newFile;
//            }catch (\Exception $e){
//                dump($e->getMessage());
//            }
//            $row->save();
//        }
        OptimizeImages::dispatch();
        return 1;
    }

    }
