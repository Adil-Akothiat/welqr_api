<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;

# test commit changes
Route::group(["prefix"=> "v1", "namespace"=> "App\Http\Controllers\API\V1"], function() {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'show']);
        Route::post('user/logout', [AuthController::class, 'logout']);
        Route::post('user/update', [AuthController::class, 'update']);
        Route::get('users', [AuthController::class, 'getUsers']);
    });
});