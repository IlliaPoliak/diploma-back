<?php

use Illuminate\Http\Request;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;



Route::prefix('/auth')->group(function (){
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::prefix('/users')->group(function (){
    Route::get('/all', [UserController::class, 'getAllUsers']);
    Route::get('/search', [UserController::class, 'searchUsersByName']);
    Route::get('/{id}', [UserController::class, 'getUserById'])->where(['id' => '[0-9]+']);

    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('/add', [UserController::class, 'createUser']);
        Route::delete('/{id}', [UserController::class, 'deleteUserById'])->where(['id' => '[0-9]+']);
        Route::put('/{id}', [UserController::class, 'updateUserById'])->where(['id' => '[0-9]+']);
    });
});