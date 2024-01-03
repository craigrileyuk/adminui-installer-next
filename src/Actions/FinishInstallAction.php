<?php

namespace AdminUI\AdminUIInstaller\Actions;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;


class FinishInstallAction
{
    public function execute()
    {
        $updateVersionAction = app(UpdateVersionEntryAction::class);

        if (Storage::exists('media') === false) {
            Storage::makeDirectory('media');
        }

        Artisan::call('storage:link');
    }
}
