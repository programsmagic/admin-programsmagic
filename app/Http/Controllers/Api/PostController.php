<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoriable;
use App\Models\Description;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Taggable;
use App\Manager\CommonMgr;
use Buglinjo\LaravelWebp\Webp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    private $functions;
    public function __construct(CommonMgr $cm)
    {
        // $this->middleware('auth:api');
        $this->functions= $cm;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        if (Gate::allows('isAuthor')) {
            $user = Auth::user();
            return Post::where('user_id',$user->id)->latest()->paginate(10);
        } else if (Gate::allows('isAdmin')) {
            return Post::latest()->paginate(10);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        Gate::authorize('isAdminOrIsAuthor');
        $this->validate($request, [
            'title' => 'required',
            'category' => 'required',
            'description' => 'required|string',
            'shortDescription' => 'string',
            'tags' => 'required',
        ]);
        $input = $request->all();
        $user = Auth::user();

        $input['user_id'] = $user->id;
        if ($request->photo) {
            $name = time() . '.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            Image::make($request->photo)->save(public_path('/img/post/') . $name);
//            Webp::make($request->photo)->save(public_path('/img/post/') . $name);

            $input['img'] = '/img/post/' . $name;
        }

        $input['slug'] = $this->functions->createSlug($input['title']);

        try {

            $post = Post::create($input);

            $post->category()->attach($input['category']);
//            Categoriable::create([
//                'categoriable_type' => Post::class,
//                'categoriable_id' => $post->id,
//                'category_id' => $input['category']
//            ]);

            Description::create([
                'descriptionable_type' => Post::class,
                'descriptionable_id' => $post->id,
                'description' => $input['description']
            ]);

            foreach ($input['tags'] as $tag) {
                $row = Tag::create([
                    'tag' => $tag['text'],
                ]);
                   $post->tags()->attach($row->id);
//                Taggable::create([
//                    'taggable_type' => Post::class,
//                    'taggable_id' => $post->id,
//                    'tag_id' => $row->id
//                ]);
            }

            $response['success'] = 1;
            $response['message'] = 'Operation successful.';
        } catch (\Exception $e) {
            $response['success'] = 0;
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $row = Post::find($id);
        if ($row) {
            $row->category = $row->category()->first();
            $row->tags = $row->tags()->get();
            $row->description = $row->description()->first()->description;
            $response['success'] = 1;
            $response['data'] = $row;
        } else {
            $response['success'] = 0;
            $response['message'] = 'Id invalid';
        }
        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function postUpdate(Request $request)
    {
        Gate::authorize('isAdminOrIsAuthor');
        $this->validate($request, [
            'id' => 'required',
            'title' => 'required',
            'category' => 'required',
            'description' => 'required|string',
            'shortDescription' => 'nullable|string',
            'tags' => 'required',
        ]);
        $input = $request->all();
        $user = Auth::user();
        $post = Post::find($input['id']);
//        $input['user_id'] = $user->id;
        if (!empty($request->photo)) {
            $name = time() . '.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            Image::make($request->photo)->save(public_path('/img/post/') . $name);
            $input['img'] = '/img/post/' . $name;
        }
        try {
            $post->update($input);

           $post->category()->detach();
            $post->category()->attach($input['category']);
//            Categoriable::create([
//                'categoriable_type' => Post::class,
//                'categoriable_id' => $post->id,
//                'category_id' => $input['category']
//            ]);

            $post->description()->delete();
            Description::create([
                'descriptionable_type' => Post::class,
                'descriptionable_id' => $post->id,
                'description' => $input['description']
            ]);

            $post->tags()->delete();
            foreach ($input['tags'] as $tag) {
                $row = Tag::create([
                    'tag' => $tag['text'],
                ]);
                $post->tags()->attach($row->id);
//                Taggable::create([
//                    'taggable_type' => Post::class,
//                    'taggable_id' => $post->id,
//                    'tag_id' => $row->id
//                ]);
            }
            $response['success'] = 1;
            $response['message'] = 'Operation successful.';
        } catch (\Exception $e) {
            $response['success'] = 0;
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('isAdminOrIsAuthor');

        $post = Post::findOrFail($id);
        $post->description()->delete();
        $post->category()->detach();
        $post->tags()->detach();
        $post->delete();
        return response()->json(['message' => 'Post Deleted'], 200);
    }
}
