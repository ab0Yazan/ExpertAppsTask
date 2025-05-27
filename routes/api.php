<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
});
