<?php

namespace AdminUI\AdminUIInstaller\Facades;

use AdminUI\AdminUIInstaller\Services\JsonService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array get()
 * @method static void set(array $data)
 * @method static mixed getField(string $key)
 * @method static void setField(string $key, mixed $data)
 * @see AdminUI\AdminUIInstaller\Services\JsonService
 */
class Json extends Facade
{
    protected static function getFacadeAccessor()
    {
        return JsonService::class;
    }
}
