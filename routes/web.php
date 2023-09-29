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

  Route::controller(CommentController::class)->prefix('/comments')->name('comments.')->group(function () {
    Route::post('/', 'store')->name('store');
    Route::patch('/{comment?}', 'update')->name('update');
    Route::delete('/{comment?}', 'destroy')->name('destroy');
  });
});
