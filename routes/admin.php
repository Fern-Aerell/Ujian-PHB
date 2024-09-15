<?php

use App\Http\Controllers\Auth\Admin\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    
    Route::get('/user/list', [UserController::class, 'list'])->name('user.list');

});