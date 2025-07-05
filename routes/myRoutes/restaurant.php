<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\{ RestaurantController, RestaurantLanguageController, AddressController, OpeningTimeController, SocialNetworksController, WifiController };
use App\Http\Controllers\TestController;

# test commit changes
Route::group(["prefix"=> "v1", "namespace"=> "App\Http\Controllers\API\V1"], function() {

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('/restaurants', RestaurantController::class);
        Route::put('/setActiveRestaurant/{id}', [RestaurantController::class, 'setActive']);
        Route::put('/setVisibleRestaurant/{id}', [RestaurantController::class, 'setVisible']);
        Route::get('/user/restaurants/{user_id}', [RestaurantController::class,'getRestaurantsByUser']);
        Route::post('restaurantCover', [RestaurantController::class, 'createCover']);
        Route::get('restaurantCover/{id}', [RestaurantController::class, 'getCovers']);
        Route::delete('restaurantCover/{id}', [RestaurantController::class, 'deleteCover']);
        // restaurant language
        Route::apiResource('/restaurantLanguage', RestaurantLanguageController::class);
        // restaurant address
        Route::apiResource('/restaurantAddress', AddressController::class);
        // restaurant opening times
        Route::apiResource('/restaurantOpeningTimes', OpeningTimeController::class);
        // restaurant social networks
        Route::apiResource('/restaurantSocialNetworks', SocialNetworksController::class);
        // restaurant wifi
        Route::apiResource('/restaurantWifi', WifiController::class);
        
    });
});