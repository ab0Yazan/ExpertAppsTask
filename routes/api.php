<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CategoryListController;

use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('login', LoginController::class);
    Route::post('register', RegisterController::class);
});


Route::prefix('lookups')->group(function () {
    Route::get('categories', CategoryListController::class);
});

Route::prefix('ticket')->middleware('auth:sanctum')->group(function () {
    Route::get('', [\App\Http\Controllers\TicketController::class, 'filter']);
    Route::post('', [\App\Http\Controllers\TicketController::class, 'store']);
    Route::put('{ticket}', [\App\Http\Controllers\TicketController::class, 'update']);
});
