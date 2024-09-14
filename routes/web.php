<?php

use App\Http\Controllers\Auth\AccountController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Auth/Dashboard');
    })->name('dashboard');

    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::patch('/account', [AccountController::class, 'update'])->name('account.update');
    Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy');
    
    Route::get('/settings', function() {
        return Inertia::render('Auth/Settings');
    })->name('settings');
});

Route::middleware(['auth', AdminMiddleware::class])->group(function () {});

require __DIR__.'/auth.php';
