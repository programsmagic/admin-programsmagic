<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FirebaseToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FirebaseController extends Controller
{
   public function uploadFirebaseToken(Request $request){
       $validator = Validator::make($request->all(),[
           'token'=>'required'
       ]);
       if ($validator->fails()){
           return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST,$validator->errors());
       }
       $input = $request->all();
       $row = FirebaseToken::create($input);
       return $this->successResponse(self::CODE_OK,self::OPERATION_SUCCESS,$row);
   }
}
