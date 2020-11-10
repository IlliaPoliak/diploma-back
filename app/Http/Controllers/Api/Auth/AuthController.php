<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Tymon\JWTAuth\JWTAuth;
use App\Models\User;


class AuthController extends ApiController
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' =>
    //         ['login', 'register']
    //     ]);
    // }

    public function login(Request $request)
    {
        $credentials = $request->only(['name', 'email', 'password']);
        $token = auth()->attempt($credentials);
        // if(!$token){
        //     return response()->json(['status' => 'error', 'message' => 'Wrong login or password'], 401);
        // }
        return response()->json(['token' => $token], 200);
    }

    public function register(Request $request, JWTAuth $JWTAuth)
    {
        $user = new User($request->all());
        $user->save();

        $token = $JWTAuth->fromUser($user);

        return response()->json([
            'status' => 'ok',
            'token' => $token
        ], 201);
    }

    public function refresh() 
    {
        try {
            $token = auth()->refresh();
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 401);
        }
        return response()->json(['token' => $token], 200);
    }
}
