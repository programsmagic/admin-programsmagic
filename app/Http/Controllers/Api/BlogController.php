<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    private $functions;

    public function allPosts()
    {
        return Post::latest()->paginate(10);
    }

    ///post count feature's api
    public function updatePostViewCounts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $post = Post::find($request->get('post_id'));
        $post->view_counts = $post->view_counts + 1;
        $post->save();
        return response()->json($post->view_counts, 200);
    }

    public function getPostByViews()
    {
        $posts = Post::with('category')->take(7)->orderBy('posts.view_counts', 'DESC')->get()->map(function ($data) {
            $data->img = URL::to('/') . $data->img;
            return $data;
        });
        return response()->json($posts);
    }

    public function getAllPosts()
    {
        $post = Post::orderBy('updated_at', 'DESC')->cursor()->filter(function ($data) {
            $data->img = URL::to('/') . $data->img;
            $data->category = $data->category()->first();
            return $data;
        });
        return response()->json($post);
    }

    public function getSinglePost($id)
    {
        if ($id) {
            $post = Cache::remember('user-'.$id, now()->addMinutes(60*24), function () use($id) {
                return Post::find($id);
            });
            $post->img = URL::to('/') . $post->img;
            return response()->json($post);
        }
    }

    public function getPostsByCategory($id)
    {
        if ($id) {
            $category = Category::where('id', $id)->first();
            if ($category != null) {
                $posts = $category->posts()->get()->map(function ($data) {
                    $data->category = $data->category()->first();
                    $data->img = URL::to('/') . $data->img;
                    return $data;
                });
                return response()->json($posts);
            }
            return response()->json([]);
        }
    }

    public function getTagsByPostId($id)
    {
        if ($id) {
            $post = Post::find($id);
            if ($post != null)
                return response()->json($post->tags()->get());
        }
    }

    public function getSlugPost(Request $request)
    {
        $data = $request->all();
        if (isset($data['slug'])) {
            $slug = $data['slug'];
            $post = Cache::remember('post-' . $slug, now()->addMinutes(60 * 24), function () use ($slug) {
                return Post::where('slug', $slug)->with('description', 'tags', 'category', 'user')->first();
            });
            if ($post != null) {
                $post->img = URL::to('/') . $post->img;
                return response()->json($post);
            }
            return response()->json([]);
        }
    }


    public function getCategories()
    {
        $day = 60 * 24;
        $data = Cache::remember('categories', now()->addMinutes($day*2), function (){
            return Category::get()->sortByDesc('post_count');
        });
        return response()->json($data);
    }

    public function getTags()
    {
        $data = Tag::all()->random(10);
        return response()->json($data);
    }

    public function getMostReadedPosts()
    {
        $day = 60 * 24;
        $response = Cache::remember('getMostReadedPosts', now()->addMinutes($day * 2), function () {
            return Post::orderBy('updated_at', 'DESC')->with('category')->get()->random(5)->map(function ($data) {
                   $data->img = URL::to('/') . $data->img;
                  return $data;
                });
        });
        return response()->json($response);
    }

    public function getRecentPosts()
    {
//        $date = Carbon::today()->subDays(7);
        $post = Post::orderBy('id', 'DESC')->with('category')->take(6)->get()->map(function ($data) {
            $data->img = URL::to('/') . $data->img;
            return $data;
        });
        return response()->json($post);
    }

    public function getRelatedPosts(Request $request)
    {
        $input = $request->all();
        if ($input['post_id']) {
            $post = Post::find($input['post_id']);
            $category = $post->category()->first();
            if ($category != null) {
                $postIds = array();
                $posts = $category->posts()->get();
                foreach ($posts as $r) {
                    $postIds[] = $r->id;
                }
                $postIds = array_unique($postIds);
                if (count($postIds) < 5) {
                    $data = Post::select('id')->get()->random(count($postIds) + 1);
                    $randPosts = array();
                    foreach ($data as $r) {
                        $randPosts[] = $r->id;
                    }
                    $postIds = array_merge($postIds, $randPosts);
                }
                $postIds = array_unique($postIds);

                $finalPosts = array();
                for ($i = 0; $i < count($postIds); $i++) {
                    if ($postIds[$i] == $input['post_id']) {
                        continue;
                    }
                    $postData = Post::where('id', $postIds[$i])->with('category')->take(6)->first();
                    $postData->img = URL::to('/') . $postData->img;
                    $finalPosts[] = $postData;
                }
                return response()->json($finalPosts);
            } else {
                return $this->getRecentPosts();
            }
        }
    }

    public function getFeaturedPosts()
    {
        $day = 60 * 24;
        $response = Cache::remember('getFeaturedPosts', now()->addMinutes($day * 2), function () {
            return Post::with('category')->get()->random(3)->map(function ($data) {
                $data->img = URL::to('/') . $data->img;
                return $data;
            });
        });
        return response()->json($response);
    }

}
