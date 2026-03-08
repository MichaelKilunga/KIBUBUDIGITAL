<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PaymentController::class, 'index'])->name('home');
Route::post('/log-intent', [PaymentController::class, 'logIntent'])->name('log.intent');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Language Switch Route
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'sw'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

// Protected Admin Routes
Route::middleware(['auth'])->prefix('admin/kibubu')->group(function () {
    Route::get('/', [PaymentController::class, 'admin'])->name('admin.dashboard');
    Route::post('/settings', [PaymentController::class, 'saveSettings'])->name('admin.settings.save');
});
