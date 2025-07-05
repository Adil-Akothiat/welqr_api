<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

Route::get('/test/{id}', [TestController::class, 'test']);

require __DIR__.'/myRoutes/auth.php';
require __DIR__.'/myRoutes/user.php';
require __DIR__.'/myRoutes/restaurant.php';
require __DIR__.'/myRoutes/qrcode.php';
require __DIR__.'/myRoutes/menu.php';
require __DIR__.'/myRoutes/dish.php';
require __DIR__.'/myRoutes/public.php';
require __DIR__.'/myRoutes/serveAssets.php';
require __DIR__.'/myRoutes/appLanguages.php';
require __DIR__.'/myRoutes/translations.php';
require __DIR__.'/myRoutes/visit.php';
require __DIR__.'/myRoutes/plans.php';
require __DIR__.'/myRoutes/template.php';

Route::fallback(function() {
    return Response('Get out of here!', 404)->header('Content-Type', 'text/plain');
});