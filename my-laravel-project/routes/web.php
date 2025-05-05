<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;

// Guest Routes (User)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('Admin.dashboard');
    })->name('admin.dashboard');

   
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});

// Admin Routes
Route::prefix('admin')->group(function () {

    // Admin Guest Routes
    Route::middleware(['guest:admin'])->group(function () {
        Route::get('/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'adminLogin'])->name('admin.authenticate');
    });

    // Admin Authenticated Routes
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', function () {
            return view('Admin.dashboard');
        })->name('admin.dashboard');

        Route::post('/logout', function () {
            auth('admin')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route('admin.login');
        })->name('admin.logout');
    });

});
