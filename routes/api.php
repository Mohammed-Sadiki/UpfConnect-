<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ApiController;

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [ApiController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [ApiController::class, 'logout']);
        
        Route::get('/users', [ApiController::class, 'users']);
        Route::get('/users/{id}', [ApiController::class, 'userProfile']);
        
        Route::get('/posts', [ApiController::class, 'posts']);
        Route::post('/posts', [ApiController::class, 'storePost']);
        Route::get('/posts/{id}', [ApiController::class, 'postDetails']);
        
        Route::get('/events', [ApiController::class, 'events']);
        
        Route::get('/notifications', [ApiController::class, 'notifications']);
        
        Route::get('/user', function (Request $request) {
            return response()->json([
                'success' => true,
                'data' => $request->user()->load('profile')
            ]);
        });
    });
});
