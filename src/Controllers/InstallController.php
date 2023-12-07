<?php

namespace AdminUI\AdminUIInstaller\Controllers;

use AdminUI\AdminUIInstaller\Actions\CheckDatabaseConnectionAction;
use AdminUI\AdminUIInstaller\Actions\ComposerUpdateAction;
use AdminUI\AdminUIInstaller\Actions\GetLatestReleaseAction;
use AdminUI\AdminUIInstaller\Actions\SaveLicenceKeyAction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AdminUI\AdminUIInstaller\Facades\Json;

class InstallController extends Controller
{
    public function index(CheckDatabaseConnectionAction $checkDb)
    {
        $hasDbConnection = $checkDb->execute();

        // if no database connection
        if (false === $hasDbConnection) {
            return view('adminui-installer::no-database');
        }

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

    public function downloadRelease(GetLatestReleaseAction $latestReleaseAction)
    {
        $details = $latestReleaseAction->execute();

        Json::setField('downloadRelease', true);
        Json::setField('releaseDetails', $details);

        return response()->json(
            [
                'status' => Json::get()
            ]
        );
    }

    public function updateComposer(ComposerUpdateAction $action)
    {
        $action->execute();

        Json::setField(field: "dependencies", data: true);

        return response()->json(
            [
                'status' => Json::get()
            ]
        );
    }
}
