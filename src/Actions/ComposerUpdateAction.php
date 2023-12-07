<?php

namespace AdminUI\AdminUIInstaller\Actions;

use AdminUI\AdminUIInstaller\Facades\Composer;

class ComposerUpdateAction
{
    public function execute()
    {
        $output = Composer::run("update --no-scripts --no-interaction");
        return $output;
    }
}
