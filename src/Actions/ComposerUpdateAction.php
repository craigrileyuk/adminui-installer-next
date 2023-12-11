<?php

namespace AdminUI\AdminUIInstaller\Actions;

use Illuminate\Support\Str;
use AdminUI\AdminUIInstaller\Facades\Json;
use AdminUI\AdminUIInstaller\Facades\Composer;

class ComposerUpdateAction
{
    public function execute()
    {
        $output = Composer::run("update --no-scripts --no-interaction");
        Json::setField(field: "composerLog", data: Str::replace("\n", "", $output));
    }
}
