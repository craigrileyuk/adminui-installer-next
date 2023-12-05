<?php

namespace AdminUI\AdminUIInstaller\Controllers;

use Illuminate\Routing\Controller;

class InstallController extends Controller
{
    public function index()
    {

        return view('adminui-installer::index');
    }
}
