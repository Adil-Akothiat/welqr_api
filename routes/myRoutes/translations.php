<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\TranslationController;

# test commit changes
Route::group(["prefix"=> "v1", "namespace"=> "App\Http\Controllers\API\V1"], function() {
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('/settings/translations', TranslationController::class);
    });
});