<?php

namespace AdminUI\AdminUIInstaller\Facades;

use AdminUI\AdminUIInstaller\Services\InstallService;
use Illuminate\Support\Facades\Facade;

class Install extends Facade
{
    protected static function getFacadeAccessor()
    {
        return InstallService::class;
    }
}
