<?php

namespace AdminUI\AdminUIInstaller\Services;

use Illuminate\Support\Facades\Schema;

class JsonService
{
    protected string $file;

    public function __construct()
    {
        $this->file = config('adminui-installer.root') . '/resources/status.json';
    }

    public function get()
    {
        $string = file_get_contents($this->file);
        return json_decode($string, true);
    }
}
