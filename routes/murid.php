<?php

use App\Http\Controllers\Auth\ActivityController;
use App\Http\Middleware\MuridMiddleware;
use App\Http\Middleware\VerifyEmailMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', VerifyEmailMiddleware::class, MuridMiddleware::class])->group(function () {
    Route::get('/do/activity/{id}', [ActivityController::class, 'doIndex'])->name('do.activity');
    Route::post('/do/activity/{id}/finish', [ActivityController::class, 'doFinish'])->name('do.activity.finish');
});
