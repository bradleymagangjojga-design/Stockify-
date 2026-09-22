<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ------------------------------------------------------------
// AUTH (guest only)
// ------------------------------------------------------------
Route::get('/login',   [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',  [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ------------------------------------------------------------
// AUTHENTICATED ROUTES
// ------------------------------------------------------------
Route::middleware('auth')->group(function () {

    // Dashboard — everyone
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products — Admin + Manager
    Route::middleware('role:Admin,Manajer Gudang')->group(function () {
        Route::resource('products', ProductController::class)->except(['show']);
    });

    // Categories — Admin only
    Route::middleware('role:Admin')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
    });

    // Suppliers — Admin + Manager
    Route::middleware('role:Admin,Manajer Gudang')->group(function () {
        Route::resource('suppliers', SupplierController::class)->except(['show']);
    });

    // Users — Admin only
    Route::middleware('role:Admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    // Stock — all authenticated users
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/',          [StockController::class, 'index'])->name('index');
        Route::get('/incoming',  [StockController::class, 'incoming'])->name('incoming');
        Route::post('/incoming', [StockController::class, 'storeIncoming'])->name('incoming.store');
        Route::get('/outgoing',  [StockController::class, 'outgoing'])->name('outgoing');
        Route::post('/outgoing', [StockController::class, 'storeOutgoing'])->name('outgoing.store');
    });

    // Stock Opname — all authenticated users
    Route::get('/opname',  [OpnameController::class, 'index'])->name('opname.index');
    Route::post('/opname', [OpnameController::class, 'store'])->name('opname.store');

    // Reports — Admin + Manager
    Route::middleware('role:Admin,Manajer Gudang')
        ->prefix('reports')
        ->name('reports.')
        ->group(function () {
            Route::get('/',             [ReportController::class, 'index'])->name('index');
            Route::get('/stock',        [ReportController::class, 'stock'])->name('stock');
            Route::get('/transactions', [ReportController::class, 'transactions'])->name('transactions');
            Route::get('/activities',   [ReportController::class, 'activities'])->name('activities');
        });

    // Settings — Admin only
    Route::middleware('role:Admin')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    });
});