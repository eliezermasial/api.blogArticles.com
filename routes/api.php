<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class,'login'])->name('login');
    Route::middleware('auth:api')->post('logout', [AuthController::class,'logout'])->name('logout');
});