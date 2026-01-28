<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index'); // Placeholder until UserController created
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/infos', [\App\Http\Controllers\InfoController::class, 'index'])->name('infos.index');

    // Admin Only
    Route::middleware('role:admin')->group(function () {
        // User CRUD
        Route::get('/users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
        
        // Info Management
        Route::post('/infos', [\App\Http\Controllers\InfoController::class, 'store'])->name('infos.store');
        Route::put('/infos/{info}', [\App\Http\Controllers\InfoController::class, 'update'])->name('infos.update');
        Route::delete('/infos/{info}', [\App\Http\Controllers\InfoController::class, 'destroy'])->name('infos.destroy');
    });
});
