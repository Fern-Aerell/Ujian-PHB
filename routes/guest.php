<?php

use App\Http\Controllers\Auth\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Middleware\IfNoUserAdminMiddleware;
use App\Http\Middleware\NoUserAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest', IfNoUserAdminMiddleware::class])->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');

});

Route::middleware(['guest', NoUserAdminMiddleware::class])->group(function () {
    Route::get('user/add/admin', [UserController::class, 'addAdmin'])->name('user.admin.create');
    Route::post('user/store/admin', [UserController::class, 'store'])->name('user.admin.store');
});
