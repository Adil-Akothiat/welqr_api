<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\RestaurantUserController;

# test commit changes
Route::group(["prefix"=> "v1", "namespace"=> "App\Http\Controllers\API\V1"], function() {

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('/restaurantMember', RestaurantUserController::class);
        Route::get('/getOwnerMembers', [RestaurantUserController::class, 'getOwnerMembers']);
        Route::get('/restaurantsInvitedTo/{memberId}', [RestaurantUserController::class, 'restaurantsInvitedTo']);
    });
});