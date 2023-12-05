<?php

namespace AdminUI\AdminUIInstaller\Actions;

use Illuminate\Support\Composer;

class TestAction
{
    public function __construct(public Composer $composer)
    {
        $this->composer->setWorkingPath(base_path());
    }

    public function execute()
    {
        return $this->composer->getVersion();
    }
}
