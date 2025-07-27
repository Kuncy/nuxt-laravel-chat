<?php

use App\Http\Controllers\Spa\ContactController;
use App\Services\RouteService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


