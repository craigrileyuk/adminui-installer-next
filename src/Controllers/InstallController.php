<?php

namespace AdminUI\AdminUIInstaller\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AdminUI\AdminUIInstaller\Facades\Json;
use AdminUI\AdminUIInstaller\Facades\Install;
use AdminUI\AdminUIInstaller\Actions\UnpackReleaseAction;
use AdminUI\AdminUIInstaller\Actions\ComposerUpdateAction;
use AdminUI\AdminUIInstaller\Actions\SaveLicenceKeyAction;
use AdminUI\AdminUIInstaller\Actions\GetLatestReleaseAction;
use AdminUI\AdminUIInstaller\Actions\DownloadLatestReleaseAction;
use AdminUI\AdminUIInstaller\Actions\CheckDatabaseConnectionAction;
use AdminUI\AdminUIInstaller\Actions\CreateLocalComposerFileAction;
use AdminUI\AdminUIInstaller\Actions\GetLatestReleaseDetailsAction;
use AdminUI\AdminUIInstaller\Actions\UpdateComposerFileAction;

class InstallController extends Controller
{
    public function index(CheckDatabaseConnectionAction $checkDb)
    {
        $hasDbConnection = $checkDb->execute();
        Install::getZipPath();

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

        sleep(5);

        $action->execute($validated['licence_key']);

        Json::setField(field: "saveKey", data: true);

        return response()->json(
            [
                'status' => Json::get()
            ]
        );
    }

    public function getLatestReleaseDetails(GetLatestReleaseDetailsAction $action)
    {
        $action->execute();

        sleep(5);

        Json::setField('getLatestReleaseDetails', true);

        return response()->json(
            [
                'status' => Json::get()
            ]
        );
    }

    public function downloadRelease(DownloadLatestReleaseAction $action)
    {
        $action->execute();

        sleep(5);

        Json::setField('downloadRelease', true);

        return response()->json(
            [
                'status' => Json::get()
            ]
        );
    }

    public function unpackRelease(UnpackReleaseAction $action)
    {
        $action->execute();

        Json::setField(field: 'unpackRelease', data: true);

        return response()->json(
            [
                'status' => Json::get()
            ]
        );
    }

    public function prepareDependencies(CreateLocalComposerFileAction $localAction, UpdateComposerFileAction $composerFileAction)
    {
        $localAction->execute();
        $composerFileAction->execute();
        Json::setField(field: 'prepareDependencies', data: true);

        return response()->json(
            [
                'status' => Json::get()
            ]
        );
    }

    public function updateDependencies(ComposerUpdateAction $action)
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
