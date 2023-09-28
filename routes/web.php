<?php

use App\Http\Controllers\ {
  CategoryController,
  CommentController,
  PostController,
  UserController,
};
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
  Route::controller(PostController::class)->prefix('/posts')->name('posts.')->group(function () {    
    Route::patch('/like', 'like')->name('like');
    Route::patch('/unlike', 'unlike')->name('unlike');
  });

  Route::resource('posts', PostController::class);

  Route::resource('categories', CategoryController::class);

  Route::patch('/comments/{comment?}', [CommentController::class, 'update'])->name('comments.update');
  Route::resource('comments', CommentController::class)->only(['store', 'destroy']);
});
