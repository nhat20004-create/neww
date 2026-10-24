<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', [HomeController::class, 'index']);
Route::get('/new/{new}', [HomeController::class, 'new']);

Route::get('/bootstrap', [HomeController::class, 'bootstrap']);
Route::post('/bootstrap', [HomeController::class, 'create']);

Route::get('/posts/{id}', [PostController::class, 'show']);