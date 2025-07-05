<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AccountController;
use App\Http\Middleware\EnsureUserHasSettings;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ==================== Authentication Routes ====================
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// ==================== Protected Routes ====================
Route::middleware(['auth', EnsureUserHasSettings::class])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Transactions
    Route::resource('transacciones', TransactionsController::class)
        ->except(['show'])
        ->names([
            'index' => 'transacciones.index',
            'create' => 'transacciones.create',
            'store' => 'transacciones.store',
            'edit' => 'transacciones.edit',
            'update' => 'transacciones.update',
            'destroy' => 'transacciones.destroy'
        ]);

    // Reports
    Route::get('/reportes', [ReportsController::class, 'index'])->name('reportes.index');

    // Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/perfil', 'profile')->name('perfil');
        Route::post('/perfil', 'updateProfile')->name('perfil.update');
    });

    // Categories
    Route::controller(CategoryController::class)->group(function () {
        Route::post('/categories', 'store')->name('categories.store');
        Route::put('/categories/{category}', 'update')->name('categories.update');
        Route::delete('/categories/{category}', 'destroy')->name('categories.destroy');
    });

    // Accounts
    Route::controller(AccountController::class)->group(function () {
        Route::post('/accounts', 'store')->name('accounts.store');
        Route::put('/accounts/{account}', 'update')->name('accounts.update');
        Route::delete('/accounts/{account}', 'destroy')->name('accounts.destroy');
    });

    // ==================== API Routes ====================
    Route::prefix('api')->name('api.')->group(function () {
        // Transactions API
        Route::controller(TransactionsController::class)->group(function () {
            Route::get('/transacciones', 'index')->name('transacciones.index');
            Route::get('/transactions', 'getTransactions')->name('transactions.list');
        });

        // Categories API
        Route::get('/categories', [CategoryController::class, 'getCategories'])
            ->name('categories.list');

        // Accounts API
        Route::get('/accounts', [AccountController::class, 'getAccounts'])
            ->name('accounts.list');

        // Reports API
        Route::get('/monthly-summary', [ReportsController::class, 'monthlySummary'])
            ->name('monthly.summary');
    });
});


// === Rutas de Fallback ===
Route::fallback(function () {
    /** @var \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard $auth */
    $auth = auth();
    return $auth->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->middleware('auth');