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

    public function get(): array
    {
        $string = file_get_contents($this->file);
        return json_decode($string, true);
    }

    public function set(array $json): void
    {
        $string = json_encode($json, JSON_PRETTY_PRINT);
        file_put_contents($this->file, $string);
    }

    public function getField(string $field): mixed
    {
        $array = $this->get();
        return $array[$field];
    }

    public function setField(string $field, mixed $data): void
    {
        $array = $this->get();
        $array[$field] = $data;
        $this->set($array);
    }
}
