<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\InternController;

Route::middleware(['guest:admin'])->name('admin.')->group(function () {
    Route::prefix('admin')->controller(AuthController::class)->group(function () {
        Route::get('/login', 'showAdminLogin')->name('login');
        Route::post('/login', 'adminLogin')->name('authenticate');
    });
});
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth:admin'])->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Intern Management
    Route::controller(InternController::class)->prefix('interns')->name('interns.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{intern}/edit', 'edit')->name('edit');
        Route::put('/{intern}', 'update')->name('update');
        Route::delete('/{intern}', 'destroy')->name('destroy');
    });

    /*
    // Chat System
    Route::controller(ChatController::class)->prefix('chat')->name('chat.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/send', 'sendMessage')->name('send');
        Route::get('/messages/{user}', 'getMessages')->name('messages');
        Route::post('/mark-read', 'markAsRead')->name('mark-read');
    });*/
    
    Route::controller(CommentController::class)->group(function () {
        Route::get('/tasks/{task}/comments', 'index')->name('comments.index');
        Route::post('/tasks/{task}/comments', 'store')->name('comments.store');
        Route::put('/comments/{comment}', 'update')->name('comments.update');
        Route::delete('/comments/{comment}', 'destroy')->name('comments.destroy');
    });

    // Admin Logout
    Route::post('/logout', [AuthController::class, 'adminLogout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth:admin'])->name('admin.')->group(function () {
    // Admin Management
    Route::controller(AdminController::class)->prefix('admins')->name('admins.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{admin}/edit', 'edit')->name('edit');
        Route::put('/{admin}', 'update')->name('update');
        Route::delete('/{admin}', 'destroy')->name('destroy');
    });

    // Delete User
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('delete-user');


    // Role Management
    Route::controller(RoleController::class)->prefix('roles')->name('roles.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{role}/edit', 'edit')->name('edit');
        Route::put('/{role}', 'update')->name('update');
        Route::delete('/{role}', 'destroy')->name('destroy');
    });

    // Permission Management
    Route::controller(PermissionController::class)->prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{permission}/edit', 'edit')->name('edit');
        Route::put('/{permission}', 'update')->name('update');
        Route::delete('/{permission}', 'destroy')->name('destroy');
    });

    Route::controller(TaskController::class)->group(function () {
        Route::get('/tasks', 'index')->name('tasks.index');
        Route::get('/tasks/create', 'create')->name('tasks.create');
        Route::post('/tasks', 'store')->name('tasks.store');
        Route::get('/tasks/{task}/edit', 'edit')->name('tasks.edit');
        Route::put('/tasks/{task}', 'update')->name('tasks.update');
        Route::delete('/tasks/{task}', 'destroy')->name('tasks.destroy');
        Route::post('/tasks/{task}/assign-intern', 'assignIntern')->name('tasks.assign-intern');
        Route::delete('/tasks/{task}/interns/{intern}', 'detachIntern')->name('tasks.detach-intern');
    });
    
});
