<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Protected Dashboard (only for authenticated users)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

// Logout
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
