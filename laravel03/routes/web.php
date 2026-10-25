<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/post', [PostController::class, 'index'])->name('post.index');
route::get('/post/create', [PostController::class, 'create'])->name('post.create');
route::post('/post', [PostController::class, 'store'])->name('post.store');
// route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');
route::get('/post/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
route::put('/post/{id}', [PostController::class, 'update'])->name('post.update');
route::delete('/post/{id}', [PostController::class, 'destroy'])->name('post.destroy');