<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\PlansController;

# test commit changes
Route::group(["prefix"=> "v1", "namespace"=> "App\Http\Controllers\API\V1"], function() {
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('/plans', PlansController::class);
    });
});