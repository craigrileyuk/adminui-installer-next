<?php

use Illuminate\Support\Facades\Route;
use AdminUI\AdminUIInstaller\Controllers\UtilsController;
use AdminUI\AdminUIInstaller\Controllers\UpdateController;
use AdminUI\AdminUIInstaller\Controllers\RegisterController;

Route::post('/install-adminui/clear-cache',     [UtilsController::class, 'clearCache'])->name('adminui.installer.clear-cache');
Route::get('/install-adminui/register',         [RegisterController::class, 'index'])->name('adminui.installer.register');
Route::post('/install-adminui/register',        [RegisterController::class, 'store'])->name('adminui.installer.register.store');

Route::get('/update-adminui/check',             [UpdateController::class, 'check'])->name('adminui.update.check');
Route::get('/update-adminui/refresh',           [UpdateController::class, 'refresh'])->name('adminui.update.refresh');
Route::post('/update-adminui',                  [UpdateController::class, 'updateSystem'])->name('adminui.update.install');
