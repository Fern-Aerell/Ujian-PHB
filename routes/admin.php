<?php

use App\Http\Controllers\Auth\Admin\ConfigController;
use App\Http\Controllers\Auth\Admin\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\VerifyEmailMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', VerifyEmailMiddleware::class, AdminMiddleware::class])->group(function () {
    
    Route::get('/user/list', [UserController::class, 'list'])->name('user.list');

    Route::get('/user/add', [UserController::class, 'add'])->name('user.add');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/{id}/update', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}/delete', [UserController::class, 'delete'])->name('user.delete');

    Route::get('/config', [ConfigController::class, 'index'])->name('config');
    Route::post('/config/activity_data/update', [ConfigController::class, 'update_activity_data'])->name('config.activity_data.update');
    Route::post('/config/exam_schedule_data/update', [ConfigController::class, 'update_exam_schedule_data'])->name('config.exam_schedule_data.update');
    Route::post('/config/exam_time_date/update', [ConfigController::class, 'update_exam_time_data'])->name('config.exam_time_data.update');
    Route::post('/config/school_data/update', [ConfigController::class, 'update_school_data'])->name('config.school_data.update');
    Route::post('/config/slider_data/update', [ConfigController::class, 'update_slider_data'])->name('config.slider_data.update');
    Route::post('/config/kelas_data/store', [ConfigController::class, 'store_kelas_data'])->name('config.kelas_data.store');
    Route::delete('/config/kelas_data/{bilangan}/delete', [ConfigController::class, 'delete_kelas_data'])->name('config.kelas_data.delete');
    Route::post('/config/kelas_kategori_data/store', [ConfigController::class, 'store_kelas_kategori_data'])->name('config.kelas_kategori_data.store');
    Route::delete('/config/kelas_kategori_data/{kependekan}/delete', [ConfigController::class, 'delete_kelas_kategori_data'])->name('config.kelas_kategori_data.delete');
    Route::post('/config/mapel_data/store', [ConfigController::class, 'store_mapel_data'])->name('config.mapel_data.store');
    Route::delete('/config/mapel_data/{id}/delete', [ConfigController::class, 'delete_mapel_data'])->name('config.mapel_data.delete');
    Route::post('/config/mapel_data/{id}/update', [ConfigController::class, 'update_mapel_data'])->name('config.mapel_data.update');

});