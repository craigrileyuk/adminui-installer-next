<?php

namespace AdminUI\AdminUIInstaller\Actions;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use AdminUI\AdminUIInstaller\Facades\Json;

class MaintenanceModeEnterAction
{
    public function execute(): string
    {
        $uuid = Str::uuid();
        Artisan::call('down', [
            '--render' => 'adminui-installer::maintenance',
            '--secret' => $uuid
        ]);
        return $uuid;
    }
}
