<?php

namespace AdminUI\AdminUIInstaller\Services;

use Illuminate\Support\Facades\Schema;

class InstallService
{
    public function isInstalled(): bool
    {
        return class_exists('\AdminUI\AdminUI\Provider');
    }

    /**
     * checkIfMigrated - verify that AdminUI migration has been run
     */
    public function isMigrated(): bool
    {
        return Schema::hasTable('permissions') && Schema::hasTable('admins');
    }
}
