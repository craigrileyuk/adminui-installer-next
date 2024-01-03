<?php

namespace AdminUI\AdminUIInstaller\Actions;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\PermissionServiceProvider;

class PublishResourcesAction
{
    public function execute()
    {
        Artisan::call('vendor:publish', [
            '--tag'      => 'adminui-setup-only',
            '--force'    => true
        ]);
        $output = Artisan::output();

        Artisan::call('vendor:publish', [
            '--tag'      => 'adminui-addons-public',
            '--force'    => true
        ]);
        $output .= Artisan::output();


        Artisan::call('vendor:publish', [
            '--tag' => ['spatie-permission-config', 'spatie-permission-migrations'],
            '--force'    => true
        ]);
        $output .= Artisan::output();

        if (!Schema::hasTable('jobs')) {
            Artisan::call('queue:table');
        }

        Artisan::call('config:clear');
        $output .= Artisan::output();

        return Str::of($output)->explode("\n")->filter(fn ($item) => !empty(trim($item)))->values();
    }
}
