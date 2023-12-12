<?php

use AdminUI\AdminUIInstaller\Controllers\InstallController;
use Illuminate\Support\Facades\Route;


Route::prefix('install-adminui')->as('adminui.installer.')->group(function () {
    Route::post('save-key', [InstallController::class, 'saveKey'])->name('save-key');
    Route::post('release-details', [InstallController::class, 'getLatestReleaseDetails'])->name('release-details');
    Route::post('download-release', [InstallController::class, 'downloadRelease'])->name('download-release');
    Route::post('unpack-release', [InstallController::class, 'unpackRelease'])->name('unpack-release');
    Route::post('prepare-dependencies', [InstallController::class, 'prepareDependencies'])->name('prepare-dependencies');
    Route::post('dependencies', [InstallController::class, 'updateDependencies'])->name('dependencies');
    Route::post('setup-permissions', [InstallController::class, 'setupPermissions'])->name('setup-permissions');
});
