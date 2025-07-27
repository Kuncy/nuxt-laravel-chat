<?php

use App\Http\Controllers\Spa\ContactController;
use App\Services\RouteService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'contacts'], static function () {
        RouteService::createCrudRoutes('contact', ContactController::class);
    });

});
