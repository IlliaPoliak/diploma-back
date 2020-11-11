<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;

use Illuminate\Http\Request;
use App\Http\Requests\MainRequest;
use App\Models\MainModel;
use Response;
use Validator;
use Illuminate\Pagination\Paginator;

class MainController extends ApiController
{


    public function createUser(Request $request) {
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
        $perPage = request()->input('perPage') ?? 2;
        $page = request()->input('page') ?? 1;
        $sort = request()->input('sortByName') ?? 'asc';

        return response()->json(MainModel::orderBy('name', $sort)->paginate($perPage, ['*'], 'page', $page),200);
    }


    public function getUserById ($id){
        $user = MainModel::find($id);

        if (is_null($user)){
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
        return response()->json($user, 200);
    }


    public function updateUserById ($id, Request $request){
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
