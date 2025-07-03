<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\LoginController;

// Ruta pública para inicio de sesión
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('transacciones', TransactionsController::class);
    Route::get('reportes', [ReportsController::class, 'index'])->name('reportes');
    Route::get('perfil', [ReportsController::class, 'profile'])->name('perfil');
});