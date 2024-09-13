<?php

use App\Http\Controllers\EAndDTestController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/dashboard');
    // OLD :
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::middleware(['auth', AdminMiddleware::class])->group(function () {

    Route::get('/e&dtest', [EAndDTestController::class, 'index'])->name('e&dtest');
    Route::patch('/e&dtest/encrypt', [EAndDTestController::class, 'encrypt'])->name('e&dtest.encrypt');
    Route::patch('/e&dtest/decrypt', [EAndDTestController::class, 'decrypt'])->name('e&dtest.decrypt');
    
});

require __DIR__.'/auth.php';
