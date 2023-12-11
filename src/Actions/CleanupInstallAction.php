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
    }
}
