<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\{ DefaultMenuController, DefaultDishController };
use App\Http\Controllers\TestController;

# test commit changes
Route::group(["prefix"=> "v1", "namespace"=> "App\Http\Controllers\API\V1"], function() {
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('/defaultMenus', DefaultMenuController::class);
        Route::apiResource('/defaultDishes', DefaultDishController::class);
    });
});