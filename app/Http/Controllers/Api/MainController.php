<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;

use Illuminate\Http\Request;
use App\Http\Requests\MainRequest;
use App\Models\MainModel;
use Response;
use Validator;

class MainController extends ApiController
{
    public function getToken() {

        $token = csrf_token();

        return response()->json(['status' => 'success', 'token' => $token], 200);
    }

    public function createUser(Request $request) {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
        }

        $rules = [
            'name' => 'required|min:2|max:50',
            'email' => 'required|string|min:1|max:50|unique:main_models'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = MainModel::create($request->all());

        return response()->json(['status' => 'success'], 201);
    }

    public function getAllUsers (Request $request) {

        return response()->json(MainModel::get(), 200);
    }

    public function getUserById ($id){
        $user = MainModel::find($id);

        if (is_null($user)){
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
        return response()->json($user, 200);
    }

    public function updateUserById ($id, Request $request){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
        }

        $user = MainModel::find($id);
        if (is_null($user)){
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $rules = [
            'name' => 'required|min:2|max:50',
            'email' => 'required|string|min:1|max:50|unique:main_models'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        MainModel::find($id)->update($request->all());

        return response()->json(['status' => 'success'], 202);
    }

    public function deleteUserById ($id){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
        }
        
        $user = MainModel::find($id);

        if (is_null($user)){
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
        $user = MainModel::find($id)->delete();

        return response()->json(['status' => 'success'], 202);
    }

    public function searchUsersByName (Request $request){

        $text = $request->input('text');

        return response()->json(MainModel::where('name', 'like', "%$text%")->get(), 200);
    }
}
