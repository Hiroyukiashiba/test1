<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

Route::apiResource('posts', PostController::class);
Route::patch('posts/{post}/complete', [PostController::class, 'complete']);
