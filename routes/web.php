<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\HealthController;

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dev-only quick login (only available in local environment)
if (app()->environment('local')) {
    Route::post('/dev-login', [AuthController::class, 'devLogin'])->name('dev.login');
}

// Health check
Route::get('/health', HealthController::class);

// Admin & Coach routes
Route::middleware(['auth', 'role:admin,coach'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Clients
    Route::resource('clients', ClientController::class);

    // Appointments
    Route::resource('appointments', AppointmentController::class);

    // Check In
    Route::get('/checkin', [CheckInController::class, 'index'])->name('checkin.index');
    Route::match(['post', 'patch'], '/checkin/{client}', [CheckInController::class, 'update'])->name('checkin.update');
});

// Admin-only routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class)->except(['show']);
});

// Client portal routes
Route::middleware(['auth', 'role:client'])->prefix('portal')->group(function () {
    Route::get('/', [ClientPortalController::class, 'dashboard'])->name('portal.dashboard');
    Route::get('/appointments', [ClientPortalController::class, 'appointments'])->name('portal.appointments');
    Route::get('/profile', [ClientPortalController::class, 'profile'])->name('portal.profile');
});
