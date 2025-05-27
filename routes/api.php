<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', RegisterController::class);
});
