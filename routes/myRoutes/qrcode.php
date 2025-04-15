<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\QrcodeController;

# test commit changes
Route::group(["prefix"=> "v1", "namespace"=> "App\Http\Controllers\API\V1"], function() {

    Route::middleware('auth:sanctum')->group(function () {
        // qrcode
        Route::post('/qrcode/create', [QrcodeController::class, 'create']);
        Route::post('/qrcode/unique', [QrcodeController::class, 'isUnique']);
        Route::get('/qrcode/{id}', [QrcodeController::class, 'getQrcode']);
    });
});