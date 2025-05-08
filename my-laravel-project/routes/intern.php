<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;

Route::middleware(['guest:user'])->name('intern.')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'showLogin')->name('login');
        Route::post('/login', 'login');
        Route::get('/register', 'showRegister')->name('register');
        Route::post('/register', 'register');
    });
});

/*
|--------------------------------------------------------------------------
| Authenticated User (Intern) Routes
|--------------------------------------------------------------------------
*/
Route::middleware("auth:user")->group(function () {
    // Intern Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    
    // Chat System
    Route::controller(ChatController::class)->prefix('chat')->name('chat.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/send', 'sendMessage')->name('send');
        Route::get('/messages/{admin}', 'getMessages')->name('messages');
        Route::post('/mark-read', 'markAsRead')->name('mark-read');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
