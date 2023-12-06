<?php

namespace AdminUI\AdminUIInstaller\Facades;

use AdminUI\AdminUIInstaller\Services\JsonService;
use Illuminate\Support\Facades\Facade;

class Json extends Facade
{
    protected static function getFacadeAccessor()
    {
        return JsonService::class;
    }
}
