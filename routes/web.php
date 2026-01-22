<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Main route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Payment  route
Route::post('/payment', [HomeController::class, 'payment'])->name('payment.process');
