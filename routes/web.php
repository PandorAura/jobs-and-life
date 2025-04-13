<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/dashboard/get-advice', [DashboardController::class, 'getAdvice'])->name('dashboard.advice');
