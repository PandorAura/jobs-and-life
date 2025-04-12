<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

Route::get('/', [MainController::class, 'index'])->name('welcome');
Route::post('/login', [MainController::class, 'login'])->name('login');
Route::post('/register', [MainController::class, 'register'])->name('register');
Route::post('/logout', [MainController::class, 'logout'])->name('logout');
