<?php

namespace AdminUI\AdminUIInstaller\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use AdminUI\AdminUIInstaller\Traits\SlimJsonResponse;

class UtilsController extends Controller
{
    use SlimJsonResponse;

    public function clearCache()
    {
        Artisan::call('optimize:clear');
        Artisan::call('optimize');

        return $this->sendSuccess();
    }
}
