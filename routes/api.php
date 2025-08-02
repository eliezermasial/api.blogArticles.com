<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;

Route::prefix('auth')->controller(AuthController::class)->group(function ()
{
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register')->name('register');
    Route::middleware('auth:api')->post('logout', 'logout')->name('logout');
});


Route::prefix('article')->controller(ArticleController::class)->group(function () 
{
    Route::middleware('auth:api')->get('getAll', 'index')->name('index');
    Route::middleware('auth:api')->post('create', 'create')->name('create');
});

