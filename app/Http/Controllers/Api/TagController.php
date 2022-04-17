<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TagController extends Controller


{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Tag[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        if (Gate::allows('isAdmin') || Gate::allows('isAuthor')) {
            return Tag::all();
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
        Gate::authorize('isAdmin');
        $this->validate($request, [
            'tag' => 'required|string|max:191',
        ]);
        $data = Tag::create([
            'tag' => $request->tag,
        ]);
        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Gate::authorize('isAdmin');
        $row = Tag::find($id);
        $row->delete();
        return response()->json(['message' => 'Tag Deleted'], 200);
    }
}
