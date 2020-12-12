<?php

use Illuminate\Http\Request;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\HistoryController;
use App\Http\Controllers\Api\AuthController;


Route::prefix('/auth')->group(function (){
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::get('/me', [AuthController::class, 'me']);
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
});

Route::prefix('/users')->group(function (){
    Route::get('/all', [UserController::class, 'getAllUsers']);
    Route::get('/{id}', [UserController::class, 'getUserById'])->where(['id' => '[0-9]+']);

    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('/add', [UserController::class, 'createUser']);
        Route::delete('/{id}', [UserController::class, 'deleteUserById'])->where(['id' => '[0-9]+']);
        Route::put('/{id}', [UserController::class, 'updateUserById'])->where(['id' => '[0-9]+']);
    });
});

Route::prefix('/history')->group(function (){
    Route::get('/{user_id}', [HistoryController::class, 'getUserHistory'])->where(['user_id' => '[0-9]+']);
    Route::get('/all', [HistoryController::class, 'getAllHistories']);

    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('/add', [HistoryController::class, 'addToHistory']);
        Route::delete('/{id}', [HistoryController::class, 'deleteFromHistory'])->where(['id' => '[0-9]+']);
        Route::post('/clear', [HistoryController::class, 'clearHistory']);
    });
});