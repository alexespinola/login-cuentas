<?php

use Illuminate\Support\Facades\Route;
use loginCuentas\Http\Controllers\LoginController;
use loginCuentas\Http\Controllers\UsersController;


Route::group(['middleware' => ['web']], function() 
{

  Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [LoginController::class, 'login']);
  Route::get('/logout', [LoginController::class, 'logout']);
  Route::get('/authorize', [LoginController::class, 'authorize2']);

  Route::get('/users', [UsersController::class, 'index']);
  Route::post('/users', [UsersController::class, 'store']);
  Route::get('/users-sync', [UsersController::class, 'syncUser']);

});

