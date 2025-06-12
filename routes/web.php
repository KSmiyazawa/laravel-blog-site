<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PostController;

// Welcome page
Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

// Blog routes
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create')->middleware('auth');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store')->middleware('auth');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/{post:slug}/edit', [PostController::class, 'edit'])->name('posts.edit')->middleware('auth');
Route::put('/posts/{post:slug}', [PostController::class, 'update'])->name('posts.update')->middleware('auth');
Route::delete('/posts/{post:slug}', [PostController::class, 'destroy'])->name('posts.destroy')->middleware('auth');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
