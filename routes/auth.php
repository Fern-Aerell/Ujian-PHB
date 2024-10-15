<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\AccountController;
use App\Http\Middleware\VerifyEmailMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function () {

    Route::get('verify-email', [VerifyEmailController::class, 'index'])->name('verification.index');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, 'verify'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('verify-email/send', [VerifyEmailController::class, 'send'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

});

Route::middleware(['auth', VerifyEmailMiddleware::class])->group(function () {

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('/dashboard', function () {
        return Inertia::render('Auth/Dashboard');
    })->name('dashboard');

    Route::get('/activity', function () {
        return Inertia::render('Auth/Activity');
    })->name('activity');

    Route::get('/dev', function () {
        return Inertia::render('Auth/Dev');
    })->name('dev');

    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::patch('/account', [AccountController::class, 'update'])->name('account.update');
    Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy');
});
