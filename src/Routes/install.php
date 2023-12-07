<?php

use AdminUI\AdminUIInstaller\Controllers\InstallController;
use Illuminate\Support\Facades\Route;


Route::prefix('install-adminui')->as('adminui.installer.')->group(function () {
    Route::post('saveKey', [InstallController::class, 'saveKey'])->name('save-key');
    Route::post('downloadRelease', [InstallController::class, 'downloadRelease'])->name('download-release');

    Route::post('composer', [InstallController::class, 'updateComposer'])->name('dependencies');
});
