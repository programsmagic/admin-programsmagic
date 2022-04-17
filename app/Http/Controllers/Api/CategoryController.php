<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $rows=  Category::all();
        if ($rows){
            $response['success'] = 1;
            $response['data'] = $rows;
        }
            return response()->json($response,200);
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
            'category' => 'required|string|max:191',
        ]);
        $data = Category::create([
            'category' => $request->category,
        ]);
        return $data;
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

        $user = Category::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User Deleted'], 200);
    }
}
