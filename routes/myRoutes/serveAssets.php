<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\DishController;

Route::group(["prefix"=> "v1", "namespace"=> "App\Http\Controllers\API\V1"], function() {
    Route::get('/pdf/{filename}', function($filename) {
        $path = public_path('assets/menu/pdfs/'.$filename);
        if (!file_exists($path)) {
            return Response()->json(['message'=> 'file not found'])->header('Content-Type', 'application/json');
        }
        return Response()->file($path, [
            'Access-Control-Allow-Origin' => 'http://localhost:3000',
        ]);
    });
});