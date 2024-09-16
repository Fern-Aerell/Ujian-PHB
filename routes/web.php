<?php

use App\Http\Middleware\IfNoUserAdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Admin\UserController;
use App\Http\Middleware\NoUserAdminMiddleware;

Route::middleware([IfNoUserAdminMiddleware::class])->group(function () {

    Route::get('/', function () {
        return redirect('/dashboard');
    });

});

Route::middleware(NoUserAdminMiddleware::class)->group(function () {

    Route::get('user/add/admin', [UserController::class, 'addAdmin'])->name('user.admin.create');
    Route::post('user/store/admin', [UserController::class, 'store'])->name('user.admin.store');
    
});

require __DIR__.'/guest.php';
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';