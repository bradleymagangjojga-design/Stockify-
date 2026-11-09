<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SearchController;
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

// ============================================================
// PUBLIC LANDING PAGE
// ============================================================
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('landing');
})->name('landing');

// ============================================================
// PUBLIC LOCALE SWITCHER
// ============================================================
Route::post('/locale', [SettingController::class, 'updateLocale'])->name('locale.switch');

// ============================================================
// AUTH (guest only)
// ============================================================
Route::get('/login',   [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',  [AuthController::class, 'login'])->name('login.attempt');

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.attempt');

Route::get('/forgot-password',        [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password',       [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password',        [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/auth/google',          [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

// ============================================================
// AUTHENTICATED ROUTES
// ============================================================
Route::middleware('auth')->group(function () {

    // DASHBOARD
    Route::get('/dashboard',  [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/search', [SearchController::class, 'search'])->name('api.search');

    // PROFILE
    Route::get('/profile',           [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile',           [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',  [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar',   [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');

    // NOTIFICATIONS
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',               [NotificationController::class, 'index'])->name('index');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
        Route::post('/reset',         [NotificationController::class, 'reset'])->name('reset');
        Route::post('/{id}/read',     [NotificationController::class, 'markAsRead'])->name('read');
    });

    // HELP
    Route::get('/help', fn () => view('help.index'))->name('help.index');

    // ============================================================
    // PRODUCTS
    // ============================================================
    Route::middleware('role:Admin,Manajer Gudang')->group(function () {
        Route::resource('products', ProductController::class)->except(['show']);
    });

    Route::middleware('role:Admin,Manajer Gudang,Staff Gudang')->group(function () {
        Route::get('products/{product}',        [ProductController::class, 'show'])->name('products.show');
        Route::get('products/print/{product}',  [ProductController::class, 'print'])->name('products.print');
    });

    // ============================================================
    // CATEGORIES
    // ============================================================
    Route::middleware('role:Admin')->group(function () {
        Route::get('categories/print/{category}', [CategoryController::class, 'print'])->name('categories.print');
        Route::resource('categories', CategoryController::class);
    });

    // ============================================================
    // SUPPLIERS
    // ============================================================
    Route::middleware('role:Admin,Manajer Gudang')->group(function () {
        Route::get('suppliers/export',           [SupplierController::class, 'export'])->name('suppliers.export');
        Route::get('suppliers/print/{supplier}', [SupplierController::class, 'print'])->name('suppliers.print');
        Route::resource('suppliers', SupplierController::class);
    });

    // ============================================================
    // USERS
    // ============================================================
    Route::middleware('role:Admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    // ============================================================
    // STOCK
    // ============================================================
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/',          [StockController::class, 'index'])->name('index');
        Route::get('/incoming',  [StockController::class, 'incoming'])->name('incoming');
        Route::post('/incoming', [StockController::class, 'storeIncoming'])->name('incoming.store');
        Route::get('/outgoing',  [StockController::class, 'outgoing'])->name('outgoing');
        Route::post('/outgoing', [StockController::class, 'storeOutgoing'])->name('outgoing.store');
    });

    // ============================================================
    // STOCK OPNAME
    // ============================================================
    Route::prefix('opname')->name('opname.')->group(function () {
        Route::get('/',              [OpnameController::class, 'index'])->name('index');
        Route::post('/',             [OpnameController::class, 'store'])->name('store');
        Route::get('/{opname}/print', [OpnameController::class, 'print'])->name('print');
        Route::delete('/{opname}',   [OpnameController::class, 'destroy'])->name('destroy'); // ✅ NEW
    });

    // ============================================================
    // REPORTS (Admin + Manager only)
    // ============================================================
    Route::middleware('role:Admin,Manajer Gudang')
        ->prefix('reports')
        ->name('reports.')
        ->group(function () {
            Route::get('/',      [ReportController::class, 'index'])->name('index');
            Route::get('/stock', [ReportController::class, 'stock'])->name('stock');

            // Print route MUST come before wildcard product route
            Route::get('/stock/print/{product}',   [ReportController::class, 'printProduct'])->name('stock.print');
            Route::get('/stock/product/{product}', [ReportController::class, 'productStock'])->name('stock.product');

            Route::get('/categories', [ReportController::class, 'categories'])->name('categories');
            Route::get('/export',     [ReportController::class, 'export'])->name('export');
        });

    // ============================================================
    // TRANSACTIONS & ACTIVITIES (everyone)
    // ============================================================
    Route::middleware('role:Admin,Manajer Gudang,Staff Gudang')
        ->prefix('reports')
        ->name('reports.')
        ->group(function () {
            Route::get('/transactions', [ReportController::class, 'transactions'])->name('transactions');

            // Print route MUST come before wildcard transaction route
            Route::get('/transactions/print/{transaction}', [ReportController::class, 'printTransaction'])->name('transactions.print');
            Route::get('/transactions/{transaction}',       [ReportController::class, 'transactionShow'])->name('transactions.show');

            Route::get('/activities',     [ReportController::class, 'activities'])->name('activities');
            Route::get('/all-activities', [ReportController::class, 'allActivities'])->name('activities.all');
        });

    // ============================================================
    // SETTINGS
    // ============================================================
    Route::middleware('role:Admin,Manajer Gudang,Staff Gudang')
        ->prefix('settings')
        ->name('settings.')
        ->group(function () {
            Route::get('/',               [SettingController::class, 'index'])->name('index');
            Route::post('/locale',        [SettingController::class, 'updateLocale'])->name('locale');
            Route::post('/appearance',    [SettingController::class, 'saveAppearance'])->name('appearance');
            Route::post('/notifications', [SettingController::class, 'saveNotifications'])->name('notifications');

            Route::middleware('role:Admin')->group(function () {
                Route::post('/general',   [SettingController::class, 'saveGeneral'])->name('general');
                Route::post('/inventory', [SettingController::class, 'saveInventory'])->name('inventory');
                Route::post('/security',  [SettingController::class, 'saveSecurity'])->name('security');
            });
        });

});