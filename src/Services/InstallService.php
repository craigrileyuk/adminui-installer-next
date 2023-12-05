<?php

namespace AdminUI\AdminUIInstaller\Services;

class InstallService
{
    public function isInstalled()
    {
        return class_exists('\AdminUI\AdminUI\Provider');
    }
}
