<?php

use Illuminate\Http\Request;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\Auth\AuthController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::prefix('/users')->group(function (){
    Route::get('/all', [MainController::class, 'getAllUsers']);
    Route::get('/search', [MainController::class, 'searchUsersByName']);
    Route::get('/{id}', [MainController::class, 'getUserById'])->where(['id' => '[0-9]+']);

    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('/add', [MainController::class, 'createUser']);
        Route::delete('/{id}', [MainController::class, 'deleteUserById'])->where(['id' => '[0-9]+']);
        Route::put('/{id}', [MainController::class, 'updateUserById'])->where(['id' => '[0-9]+']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
});