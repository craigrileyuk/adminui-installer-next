<?php

namespace AdminUI\AdminUIInstaller\Actions;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;


class RunMigrationsAction
{
    public function execute()
    {
        Artisan::call('migrate:fresh');
        $output = Artisan::output();

        return Str::of($output)->explode("\n")->filter(fn ($item) => !empty(trim($item)))->values();
    }
}
