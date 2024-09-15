<?php

use App\Http\Middleware\IfNoUserAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([IfNoUserAdminMiddleware::class])->group(function () {

    Route::get('/', function () {
        return redirect('/dashboard');
    });
    
    
});

require __DIR__.'/guest.php';
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';