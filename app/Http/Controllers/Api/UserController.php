<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Response;
use Validator;
use Illuminate\Pagination\Paginator;

class UserController extends ApiController
{

    // В юзере этот функционал не нужен!
    public function createUser(Request $request) {
        $rules = [
            'name' => 'required|min:2|max:50',
            'email' => 'required|email|string|max:50|unique:users',
            'password' => 'required|string|min:6|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create($request->all());

        return response()->json(['status' => 'success'], 201);
    }


    public function getAllUsers (Request $request) {
        $perPage = request()->input('perPage') ?? 2;
        $page = request()->input('page') ?? 1;
        $sort = request()->input('sortByName') ?? 'asc';

        return response()->json(User::orderBy('name', $sort)->paginate($perPage, ['*'], 'page', $page),200);
    }


    public function getUserById ($id){
        $user = User::find($id);

        if (is_null($user)){
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
        return response()->json($user, 200);
    }


    public function updateUserById ($id, Request $request){
        $user = User::find($id);
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

        User::find($id)->update($request->all());

        return response()->json(['status' => 'success'], 202);
    }


    public function deleteUserById ($id){ 
        $user = User::find($id);

        if (is_null($user)){
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
        $user = User::find($id)->delete();

        return response()->json(['status' => 'success'], 202);
    }

    
    public function searchUsersByName (Request $request){
        $text = $request->input('text');

        return response()->json(User::where('name', 'like', "%$text%")->get(), 200);
    }
}
