<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('posts', PostController::class);
Route::patch('posts/{post}/complete', [PostController::class, 'complete'])->name('posts.complete');

Route::get('/api-demo', fn() => view('api-demo'))->name('api-demo');
