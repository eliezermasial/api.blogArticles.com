<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;


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

Route::prefix('category')->controller(CategoryController::class)->group(function ()
{
    Route::get('getAll', 'index')->name('category.index');
    Route::post('show/{category}', 'show')->name('category.show');
    Route::middleware(['auth:api', 'admin'])->post('create', 'store')->name('category.create');
    Route::middleware(['auth:api', 'admin'])->put('update/{category}', 'update')->name('category.update');
    Route::middleware(['auth:api', 'admin'])->delete('delete/{category}', 'destroy')->name('category.delete');
});

