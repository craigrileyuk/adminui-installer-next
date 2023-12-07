<?php

namespace AdminUI\AdminUIInstaller\Controllers;

use AdminUI\AdminUIInstaller\Actions\SaveLicenceKeyAction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AdminUI\AdminUIInstaller\Facades\Json;

class InstallController extends Controller
{
    public function index()
    {
        return view('adminui-installer::index', [
            'status' => Json::get()
        ]);
    }

    public function saveKey(Request $request, SaveLicenceKeyAction $action)
    {
        $validated = $request->validate([
            'licence_key' => ['required', 'string']
        ]);

        $action->execute($validated['licence_key']);

        Json::setField(field: "saveKey", data: true);

        return response()->json(
            [
                'status' => Json::get()
            ]
        );
    }
}
