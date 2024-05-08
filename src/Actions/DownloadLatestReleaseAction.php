<?php

namespace AdminUI\AdminUIInstaller\Actions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use AdminUI\AdminUIInstaller\Facades\Json;
use AdminUI\AdminUIInstaller\Facades\Install;

class DownloadLatestReleaseAction
{
    protected string $key;

    public function __construct()
    {
        $this->key = config('adminui-installer.licence');
    }

    public function execute()
    {
        $zipPath = Install::getZipPath();
        $releaseDetails = Json::getField('releaseDetails');

        if (empty($releaseDetails) || empty($releaseDetails['url'])) {
            throw new \Exception("Couldn't find valid release URL for download");
        }
        $url = $releaseDetails['url'];

        $response = Http::accept('application/octet-stream')->withToken($this->key)->get($url);
        if (true === $response->successful()) {
            $disk = Install::getDisk();
            $disk->put($zipPath, $response->body());
            $stats = Install::getDownloadStats($response);
            Json::setField('downloadStats', $stats);
        } else {
            throw new \Exception("Unable to download install file from AdminUI Server. Aborting install");
        }
    }
}
