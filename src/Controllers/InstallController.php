<?php

namespace AdminUI\AdminUIInstaller\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InstallController extends Controller
{
    public function index()
    {
        return view('adminui-installer::index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'licence_key' => ['required', 'string']
        ]);
    }
}
