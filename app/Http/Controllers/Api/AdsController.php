<?php

namespace App\Http\Controllers\Api;

use App\Manager\CommonMgr;
use App\Models\Advertisement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdsController extends Controller
{
    private $functions;
    public function __construct(CommonMgr $cm)
    {
        $this->middleware('auth:api');
        $this->functions= $cm;
    }

    public function index()
    {
        $user = Auth::user();
        return $user->ads()->latest()->paginate(5);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'nullable|string',
            'ads' => 'required',
        ]);
        $input = $request->all();
        $user = Auth::user();
        if ($user != null) {
            $input['user_id'] = $user->id;
//            $input['advertisement'] = htmlentities($input['ads']);
            $input['advertisement'] = ($input['ads']);
            Advertisement::create($input);
            return response()->json('success', 200);
        }
        return response()->json('fail', 403);
    }

    public function status(Request $request)
    {
        $this->validate($request, [
            'ads_id' => 'required',
        ]);
        $input = $request->all();
        $ads = Advertisement::find($input['ads_id']);
        if ($ads != null) {
            if ($ads->status == 1) {
                $ads->status = 0;
            }
            if ($ads->status == 0) {
                $ads->status = 1;
            }
            $ads->save();
            return response()->json('success', 200);
        }
        return response()->json('fail', 403);
    }

    public function delete($id=null)
    {
        $ads = Advertisement::find($id);
        if ($ads != null) {
            $ads->delete();
            return response()->json('success', 200);
        }
        return response()->json('fail', 403);
    }


}
