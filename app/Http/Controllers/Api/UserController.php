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
    
    public function getAllUsers (Request $request) {
        $perPage = request()->input('perPage') ?? 10;
        $page = request()->input('page') ?? 1;
        $sort = request()->input('sortByName') ?? 'asc';
        $search = request()->input('search') ?? '';

        $result = User::orderBy('name', $sort)
                    ->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($result,200);
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
            'email' => 'required|email|min:1|max:50',
            'role' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user->update($request->all());

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
}
