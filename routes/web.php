<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MotherAccountController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\ExportController;

// --- Auth Routes ---
Route::get('/', fn() => redirect('/login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Authenticated Routes ---
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Mother Accounts
    Route::resource('mothers', MotherAccountController::class);
    Route::post('/mothers/{mother}/archive', [MotherAccountController::class, 'archive'])->name('mothers.archive')
        ->middleware('role:admin,manager');

    // Accounts
    Route::resource('accounts', AccountController::class)->except(['edit', 'update']);
    Route::get('/accounts/{account}/transfer', [AccountController::class, 'transferForm'])->name('accounts.transfer.form')
        ->middleware('role:admin,manager');
    Route::post('/accounts/{account}/transfer', [AccountController::class, 'transfer'])->name('accounts.transfer')
        ->middleware('role:admin,manager');
    Route::post('/accounts/{account}/extend', [AccountController::class, 'extendPlan'])->name('accounts.extend')
        ->middleware('role:admin,manager');

    // Buyers
    Route::resource('buyers', BuyerController::class);

    // Orders
    Route::resource('orders', OrderController::class);

    // Alerts
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::post('/alerts/{alert}/resolve', [AlertController::class, 'resolve'])->name('alerts.resolve')
        ->middleware('role:admin,manager');
    Route::post('/alerts/resolve-all', [AlertController::class, 'resolveAll'])->name('alerts.resolve.all')
        ->middleware('role:admin,manager');

    // Exports
    Route::get('/exports', [ExportController::class, 'index'])->name('exports.index');
    Route::get('/exports/download', [ExportController::class, 'export'])->name('exports.download');
});
