<?php

namespace AdminUI\AdminUIInstaller\Actions;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;


class SeedDatabaseAction
{
    public function execute()
    {
        $dbSeeder = new \AdminUI\AdminUI\Database\Seeds\DatabaseSeeder;
        $dbSeeder->run();
    }
}
