<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureUserHasSettings;

// === Authentication Routes ===
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// === Protected Routes ===
Route::middleware(['auth', EnsureUserHasSettings::class])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Transactions
    Route::resource('transacciones', TransactionsController::class)->only(['index', 'create', 'store', 'update', 'destroy'])->names([
        'index' => 'transacciones.index',   // ✅ Cambiado aquí
        'create' => 'transacciones.create',
        'store' => 'transacciones.store',
        'update' => 'transacciones.update',
        'destroy' => 'transacciones.destroy',
    ]);

    // Reports
    Route::get('/reportes', [ReportsController::class, 'index'])->name('reportes.index');

    // Profile
    Route::get('/perfil', [ProfileController::class, 'profile'])->name('perfil');
    Route::post('/perfil', [ProfileController::class, 'updateProfile'])->name('perfil.update');
});

// === API Routes for AJAX ===
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/transacciones', [TransactionsController::class, 'index'])->name('api.transacciones.index');
    Route::get('/transactions', [TransactionsController::class, 'getTransactions']);
    Route::get('/categories', [TransactionsController::class, 'getCategories']);
    Route::get('/monthly-summary', [ReportsController::class, 'monthlySummary']);
});

// === Rutas de Fallback ===
Route::fallback(function () {
    /** @var \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard $auth */
    $auth = auth();
    return $auth->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->middleware('auth');