<?php

namespace AdminUI\AdminUIInstaller\Actions;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use AdminUI\AdminUIInstaller\Facades\Install;


class ValidateDownloadAction
{
    public function execute(string $checksum): bool
    {
        $disk = Install::getDisk();
        $zipPath = Install::getZipPath();
        $zipIsValid = !empty($checksum) && $checksum === hash_file('sha256', $disk->path($zipPath));

        if (false === $zipIsValid) {
            throw new \Exception("Could not confirm authenticity of AdminUI package. Aborting");
        }

        return true;
    }
}
