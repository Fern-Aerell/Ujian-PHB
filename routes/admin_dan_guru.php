<?php

use App\Http\Controllers\Auth\SoalController;
use App\Http\Controllers\ImageController;
use App\Http\Middleware\AdminDanGuruMiddleware;
use App\Http\Middleware\VerifyEmailMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', VerifyEmailMiddleware::class, AdminDanGuruMiddleware::class])->group(function () {

    Route::get('/soal', [SoalController::class, 'index'])->name('soal');
    Route::get('/soal/tambah', [SoalController::class, 'tambahIndex'])->name('soal.tambah.index');
    Route::get('/soal/{id}/edit', [SoalController::class, 'editIndex'])->name('soal.edit.index');

    Route::post('/soal/tambah', [SoalController::class, 'tambah'])->name('soal.tambah');
    Route::post('/soal/{id}/edit', [SoalController::class, 'edit'])->name('soal.edit');
    Route::delete('/soal/{id}/hapus', [SoalController::class, 'hapus'])->name('soal.hapus');

    Route::post('/image/upload', [ImageController::class, 'upload'])->name('image.upload');
    
});
