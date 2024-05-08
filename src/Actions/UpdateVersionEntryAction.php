<?php

namespace AdminUI\AdminUIInstaller\Actions;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;


class UpdateVersionEntryAction
{
    public function execute(string $version = "v0.0.1")
    {
        return \AdminUI\AdminUI\Models\Configuration::updateOrCreate(
            ['name' => 'installed_version'],
            ['section'  => 'private', 'type' => 'text', 'label' => 'Installed Version', 'value' => $version],
        );
    }
}
