<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\History;
use Response;
use Validator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class HistoryController extends ApiController
{
    public function getAllHistories(Request $request){
        $perPage = request()->input('perPage') ?? 10;
        $page = request()->input('page') ?? 1;

        $histories = History::paginate($perPage, ['*'], 'page', $page);
        
        return response()->json($histories, 200);
    }

    public function getUserHistory($user_id){
        $user = User::find($user_id);
       
        if (is_null($user)){
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        } else {
            $history = $user->history;
        }
        return response()->json($history, 200);
    }

    public function addToHistory(Request $request){

        $rules = [
            'user_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'data' => 'required|json',
            'array' => 'required',
            'comment' => 'required|string|max:255'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::find($request->input('user_id'));

        if (is_null($user)){
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        } else {
            $lastId = History::latest()->first();

            if (is_null($lastId)){
                $lastId = 1;
            } else {
                $lastId = $lastId->id + 1;
            }
            
            $arrayUrl = "data/$lastId.txt";

            $history = History::create([
                'user_id' => $request->input('user_id'),
                'title' => $request->input('title'),
                'data' => $request->input('data'),
                'array' => $request->input('array'),
                'comment' => $request->input('comment'),
                'array_url' => $arrayUrl
            ]);

            Storage::disk('local')->put($arrayUrl, $request->input('array'));
        }
        
        return response()->json([
            'status' => 'success',
            'history' => $history
        ], 201);
    }

    public function deleteFromHistory($id){
        $history = History::find($id);

        if (is_null($history)){
            return response()->json(['status' => 'error', 'message' => 'History not found'], 404);
        }
        $history->delete();

        return response()->json(['status' => 'success'], 202);
    }

    public function clearHistory(Request $request){
        $rules = [ 'user_id' => 'required|integer' ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::find($request->input('user_id'));

        if (is_null($user)){
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        } else {
            History::where('user_id', $request->input('user_id'))->delete();
        }
        
        return response()->json(['status' => 'success'], 202);
    }
}
