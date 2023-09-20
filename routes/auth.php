<?php

use App\Http\Controllers\Auth\ {
  AuthenticatedSessionController,
};
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
  Route::controller(AuthenticatedSessionController::class)->prefix('/login')->group(function () {
    Route::get( '/', 'create')->name('login');
    Route::post('/', 'store');
  });
});

Route::middleware('auth')->group(function () {
  Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
