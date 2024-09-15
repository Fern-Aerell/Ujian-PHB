<?php

use App\Http\Controllers\Auth\Admin\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\IfNoUserAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', AdminMiddleware::class, IfNoUserAdminMiddleware::class])->group(function () {
    
    Route::get('/user/list', [UserController::class, 'list'])->name('user.list');

    Route::get('/user/add', [UserController::class, 'add'])->name('user.add');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/{id}/update', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}/delete', [UserController::class, 'delete'])->name('user.delete');

});