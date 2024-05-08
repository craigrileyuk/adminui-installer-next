<?php

namespace AdminUI\AdminUIInstaller\Actions;

use AdminUI\AdminUIInstaller\Facades\Install;
use Illuminate\Support\Composer;

class CleanupInstallAction
{
    public function execute()
    {
        $disk = Install::getDisk();
        $zipPath = Install::getZipPath();
        $extractPath = Install::getExtractPath();

        if ($disk->exists($zipPath)) {
            $disk->delete($zipPath);
        }

        if ($disk->exists($extractPath)) {
            $disk->deleteDirectory($extractPath);
        }
    }
}
