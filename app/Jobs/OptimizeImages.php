<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use WebPConvert\WebPConvert;

class OptimizeImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rows = Post::all();
        foreach ($rows as $row){
            $source = public_path().$row->img;
            $newFile = '/img/posts/'.time().'.webp';
            $destination = public_path() .'/'. $newFile;
            $options = [];
            try {
                WebPConvert::convert($source, $destination, $options);
                $row->img = $newFile;
            }catch (\Exception $e){
            }
            $row->save();
        }
    }
}
