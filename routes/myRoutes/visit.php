<?php 
    
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\VisitController;

Route::group(["prefix"=> "v1", "namespace"=> "App\Http\Controllers\API\V1"], function() {
    Route::post('/visits', [VisitController::class, 'create']);
    Route::get('/visits/{id}', [VisitController::class, 'getVisitByQrcodeId']);
});