<?php

namespace App\Http\Controllers\Api;

use App\Events\CommentEvent;
use App\Http\Controllers\Controller;
use App\Manager\CommonMgr;
use App\Models\Comment;
use App\Models\Comments;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    private $functions;
    private $request;

    public function __construct(CommonMgr $cm, Request $request)
    {
        $this->functions = $cm;
        $this->request = $request;
    }

    public function getAllComments()
    {
        if (Gate::allows('isAuthor')) {
            $user = Auth::user();
            return Comment::where('user_id', $user->id)->latest()->get();
        } else if (Gate::allows('isAdmin')) {
            return Comment::latest()->get();
        }
    }

    public function addComment()
    {

        $validator = Validator::make($this->request->all(), [
            'comment' => 'required',
            'post_id' => 'required|exists:posts,id',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();

        $post = Post::where('id', $input['post_id'])->first();
        $input['user_id'] = $post->user_id;
        $row = Comment::create($input);
        event(new CommentEvent($row,'comment-channel-'.$row->post_id));
        return $this->successResponse(self::CODE_OK, self::OPERATION_SUCCESS, $row);
    }

    public function getComments()
    {
        $validator = Validator::make($this->request->all(), [
            'post_id' => 'required|exists:posts,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $rows = Comment::where('post_id', $input['post_id'])->get();

        return $this->successResponse(self::CODE_OK, self::OPERATION_SUCCESS, $rows);
    }
    public function getSingleComment()
    {
        $validator = Validator::make($this->request->all(), [
            'comment_id' => 'required|exists:comments,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $row = Comment::where('id', $input['comment_id'])->first();
        return $this->successResponse(self::CODE_OK, self::OPERATION_SUCCESS, $row);
    }

    public function updateComment()
    {
        $validator = Validator::make($this->request->all(), [
            'comment_id' => 'required|exists:comments,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $row = Comment::find($input['comment_id']);
        $row->update($input);
        return $this->successResponse(self::OPERATION_SUCCESS, self::OPERATION_SUCCESS, $row);
    }

    public function deleteComment()
    {
        $validator = Validator::make($this->request->all(), [
            'comment_id' => 'required|exists:comments,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $row = Comment::find($input['comment_id']);
        $row->delete();
        return $this->successResponse(self::OPERATION_SUCCESS, self::OPERATION_SUCCESS, $row);

    }
}
