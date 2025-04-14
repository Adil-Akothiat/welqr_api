<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\{ AuthController, SessionController, RestaurantController, QrcodeController, RestaurantLanguageController, AddressController, OpeningTimeController, SocialNetworksController, WifiController, MenuController };
use App\Http\Controllers\TestController;
# test commit changes
Route::group(["prefix"=> "v1", "namespace"=> "App\Http\Controllers\API\V1"], function() {
    Route::post('user/register', [AuthController::class, 'register']);
    Route::post('user/login', [AuthController::class, 'login']);
    Route::post('user/forgotPassword', [AuthController::class, 'forgotPassword']);
    Route::post('user/resetPassword', [AuthController::class, 'resetPassword']);
    Route::post('user/googleauth', [AuthController::class, 'googleAuth']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'show']);
        Route::post('user/logout', [AuthController::class, 'logout']);
        Route::post('user/update', [AuthController::class, 'update']);
        Route::get('users', [AuthController::class, 'getUsers']);
        Route::post('/restaurants', [RestaurantController::class, "store"]);
        Route::post('/restaurants/{id}', [RestaurantController::class, "update"]);
        Route::get('/restaurants', [RestaurantController::class, "index"]);
        Route::get('/restaurants/{id}', [RestaurantController::class, "show"]);
        Route::delete('/restaurants/{id}', [RestaurantController::class, "destroy"]);
        Route::get('/user/restaurants/{user_id}', [RestaurantController::class, 'getRestaurantsByUser']);
        Route::post('restaurantCover', [RestaurantController::class, 'createCover']);
        Route::get('restaurantCover/{id}', [RestaurantController::class, 'getCovers']);
        Route::delete('restaurantCover/{id}', [RestaurantController::class, 'deleteCover']);
        // qrcode
        Route::post('/qrcode/create', [QrcodeController::class, 'create']);
        Route::post('/qrcode/unique', [QrcodeController::class, 'isUnique']);
        Route::get('/qrcode/{id}', [QrcodeController::class, 'getQrcode']);
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
        // menu
        Route::apiResource('/menu', MenuController::class);
        Route::put('/menuOrder/{curr}/{swp}', [MenuController::class, 'orderMenu']);
    });
});

Route::get('/test', [TestController::class, 'test']);