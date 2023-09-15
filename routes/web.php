<?php

use App\Http\Controllers\ {
  CategoryController,
  PostController,
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});

Route::resource('categories', CategoryController::class);
Route::resource('posts', PostController::class);
