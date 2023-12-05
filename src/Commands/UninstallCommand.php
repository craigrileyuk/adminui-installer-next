<?php

namespace AdminUI\AdminUIInstaller\Commands;

use Illuminate\Console\Command;

class UninstallCommand extends Command
{
    protected $signature = 'adminui:uninstall';

    protected $description = 'Remove AdminUI from a Laravel application';

    public function handle()
    {
    }
}
