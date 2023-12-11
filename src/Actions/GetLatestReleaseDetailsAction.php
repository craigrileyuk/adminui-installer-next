<?php

namespace AdminUI\AdminUIInstaller\Actions;

use Illuminate\Support\Facades\Http;
use AdminUI\AdminUIInstaller\Facades\Json;

class GetLatestReleaseDetailsAction
{
    protected string $key;

    public function __construct()
    {
        $this->key = env('ADMINUI_LICENCE_KEY');
    }

    public function execute()
    {
        $response = Http::acceptJson()->withToken($this->key)->get(config('adminui-installer.version_endpoint'));
        $response->onError(function () use ($response) {
            if ($response->status() === 401) {
                throw new \Exception("This licence key is invalid. Check your credentials and try again");
            } else if ($response->status() === 403) {
                throw new \Exception("There was a problem with your account. Please contact AdminUI Support.");
            } else {
                throw new \Exception("Unable to fetch release information from AdminUI Server. Aborting");
            }
        });

        $releaseDetails = $response->json();
        Json::setField('releaseDetails', $releaseDetails);
    }
}
