<?php

use App\Http\Controllers\ {
  CategoryController,
  PostController,
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});

Route::get('/posts/my-posts', [PostController::class, 'myPosts'])->name('posts.my-posts');
Route::resource('posts', PostController::class);
Route::resource('categories', CategoryController::class);
