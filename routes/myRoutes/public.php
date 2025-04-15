<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\PublicController;

# test commit changes
Route::group(["prefix"=> "v1", "namespace"=> "App\Http\Controllers\API\V1"], function() {
    Route::apiResource('/public', PublicController::class);
});