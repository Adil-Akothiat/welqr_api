<?php 
    
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AppLanguagesController;

Route::group(["prefix"=> "v1", "namespace"=> "App\Http\Controllers\API\V1"], function() {
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('/settings/appLanguage', AppLanguagesController::class);
        Route::put('/settings/file/{code}', [AppLanguagesController::class, 'updateFile']);
    });
    Route::get('/settings/files/{code}', [AppLanguagesController::class, 'getTranslationsFiles']);
});