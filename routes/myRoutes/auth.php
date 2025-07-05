<?php 
    
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;


Route::group(["prefix"=> "v1", "namespace"=> "App\Http\Controllers\API\V1"], function() {
    Route::post('user/register', [AuthController::class, 'register']);
    Route::post('user/login', [AuthController::class, 'login']);
    Route::post('user/forgotPassword', [AuthController::class, 'forgotPassword']);
    Route::post('user/resetPassword', [AuthController::class, 'resetPassword']);
    Route::post('user/googleauth', [AuthController::class, 'googleAuth']);
});