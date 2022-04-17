<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Email;
use App\Models\Post;
use App\Notifications\ContactUs;
use App\Notifications\ThanksNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    protected $searchText;

    public function getRadomeAd()
    {
        $result = Advertisement::all()->random(1);
        return response()->json($result, 200);
    }

    public function getTop2Post(){
        $post = Post::with('category')->whereIn('id',[54,135])->get()->map(function ($data) {
            $data->img = URL::to('/') . $data->img;
            return $data;
        });
        return response()->json($post);
    }

    public function saveSubscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

         $email = Email::where('email', $request->email)->first();
        if ($email!=null){
            return response()->json(1, 200);
        }
        try {
            $newRow = new Email([
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'location' => $request->ip(),
                'device' => $request->header('User-Agent'),
            ]);
            $newRow->save();
            $newRow->notify(new ThanksNotification());
        }catch (\Exception $e){
            return response()->json($e->getMessage());
        }
        return response()->json(1, 200);
    }
    public function contactUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns',
            'subject' => 'required',
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        try {
            Notification::route('mail', 'prashant@programsmagic.com')
              ->notify(new ContactUs($request->all()));
            return $this->successResponse(self::CODE_OK,'Successfully Submitted!!');
        }catch (\Exception $e){
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR,'Something Wrong!, Please Try again later',$e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $input = $request->all();
        if (isset($input['searchText']) && !empty($input['searchText'])) {
            $this->searchText = $input['searchText'];

            $post = Post::with(['category' => function ($query) {
                $query->where('category', 'like', '%' . $this->searchText . '%');
            }])
                ->where('title', 'like', '%' . $this->searchText . '%')
                ->orWhere('short_description', 'like', '%' . $this->searchText . '%')
                ->get();
        } else {
            $post = Post::with('category')->get()->random(5);
        }
        return response()->json(['data' => $post], 200);
    }
}
