<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CheckInController;

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Clients
    Route::resource('clients', ClientController::class);
    
    // Check In
    Route::get('/checkin', [CheckInController::class, 'index'])->name('checkin.index');
    Route::match(['post', 'patch'], '/checkin/{client}', [CheckInController::class, 'update'])->name('checkin.update');
});
