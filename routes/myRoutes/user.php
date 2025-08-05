<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\UserSettingsController;

# test commit changes
Route::group(["prefix"=> "v1", "namespace"=> "App\Http\Controllers\API\V1"], function() {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'show']);
        Route::post('user/logout', [AuthController::class, 'logout']);
        Route::post('user/update', [AuthController::class, 'update']);
        Route::get('users', [AuthController::class, 'getUsers']);
        Route::get('userByEmail/{email}', [AuthController::class, 'isUserExistsByEmail']);
        Route::put('userSettings/{id}', [UserSettingsController::class, 'changeUserSettings']);
    });
});