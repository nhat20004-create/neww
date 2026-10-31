<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('blog');
});

Route::get('/home', [HomeController::class, 'index']);
Route::get('/new/{new}', [HomeController::class, 'new']);
Route::get('/bootstrap', [HomeController::class, 'bootstrap']);
Route::post('/bootstrap', [HomeController::class, 'create']);

Route::get('/blog', [PostController::class, 'index'])->name('blog');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show')->whereNumber('id');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    // Quên mật khẩu / đặt lại mật khẩu (tên route password.reset bắt buộc cho mail reset)
    Route::get('/forgot-password', [PasswordController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendLink'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/account/password', [PasswordController::class, 'showChange'])->name('password.change');
    Route::put('/account/password', [PasswordController::class, 'change'])->name('password.change.update');

    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit')->whereNumber('id');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update')->whereNumber('id');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy')->whereNumber('id');
});
