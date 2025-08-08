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
    Route::get('getAll', 'index')->name('article.index');
    Route::middleware('auth:api')->post('create', 'store')->name('article.create');
    Route::middleware('auth:api')->post('show/{article}', 'show')->name('article.show');
    Route::middleware('auth:api')->put('update/{article}', 'update')->name('article.update');
    Route::middleware('auth:api')->delete('delete/{article}', 'destroy')->name('article.delete');
});

