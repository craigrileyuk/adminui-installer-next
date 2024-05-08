<?php

namespace AdminUI\AdminUIInstaller\Controllers;

use AdminUI\AdminUIInstaller\Actions\CleanupInstallAction;
use Parsedown;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use AdminUI\AdminUIInstaller\Traits\SlimJsonResponse;
use AdminUI\AdminUIInstaller\Actions\RunMigrationsAction;
use AdminUI\AdminUIInstaller\Actions\ComposerUpdateAction;
use AdminUI\AdminUIInstaller\Actions\DownloadLatestReleaseAction;
use AdminUI\AdminUIInstaller\Actions\PublishResourcesAction;
use AdminUI\AdminUIInstaller\Actions\SeedDatabaseUpdateAction;
use AdminUI\AdminUIInstaller\Actions\GetLatestReleaseDetailsAction;
use AdminUI\AdminUIInstaller\Actions\MaintenanceModeEnterAction;
use AdminUI\AdminUIInstaller\Actions\UnpackReleaseAction;
use AdminUI\AdminUIInstaller\Actions\UpdateVersionEntryAction;
use AdminUI\AdminUIInstaller\Actions\ValidateDownloadAction;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    use SlimJsonResponse;
    /**
     * Check for an available update for AdminUI
     */
    public function check(GetLatestReleaseDetailsAction $releaseAction)
    {
        $installedVersion = \AdminUI\AdminUI\Models\Configuration::where('name', 'installed_version')->firstOrCreate(
            ['name'  => 'installed_version'],
            [
                'label' => 'Installed Version',
                'value' => 'v0.0.1',
                'section' => 'private',
                'type'  => 'text'
            ]
        );

        if (file_exists(base_path('packages/adminui')) === false) {
            throw new \Exception('Can\'t update this copy of AdminUI since it appears to be outside the packages folder');
        } else if (file_exists(base_path('packages/adminui/.git')) === true) {
            throw new \Exception('Can\'t update this copy of AdminUI since it is under version control');
        }

        $updateDetails = $releaseAction->execute();

        // Check if update is available
        $updateIsAvailable = version_compare(trim($updateDetails['version'], "v \n\r\t\v\0"), trim($installedVersion->value, "v \n\r\t\v\0"), '>');

        if (true === $updateIsAvailable) {
            // Calculate if this is a major update for the purpose of warning the user
            $availableMajor = $this->getMajor($updateDetails['version']);
            $installedMajor = $this->getMajor($installedVersion->value);
            $isMajor = $availableMajor > $installedMajor;
            // Parse the .md format changelog into HTML
            $Parsedown = new Parsedown();
            $updateDetails['changelog'] = $Parsedown->text($updateDetails['changelog']);

            return $this->sendSuccess(['update' => $updateDetails, 'message' => 'There is a new version of AdminUI available!', 'isMajor' => $isMajor]);
        } else {
            throw new \Exception("You are already using the latest version of AdminUI");
        }
    }

    /**
     * Refresh the AdminUI website
     */
    public function refresh(
        RunMigrationsAction $migrationsAction,
        SeedDatabaseUpdateAction $dbUpdateAction,
        ComposerUpdateAction $composerUpdateAction
    ) {
        $migration = $migrationsAction->execute(update: true);
        $seed = $dbUpdateAction->execute();
        Artisan::call('vendor:publish', [
            '--tag'      => 'adminui-public',
            '--force'    => true
        ]);

        $composer = $composerUpdateAction->execute();

        Artisan::call('optimize:clear');

        return $this->sendSuccess("Site refreshed");
    }

    /**
     * Update AdminUI
     */
    public function update(
        Request $request,
        CleanupInstallAction $cleanupAction,
        DownloadLatestReleaseAction $downloadAction,
        ValidateDownloadAction $validateDownloadAction,
        MaintenanceModeEnterAction $downAction,
        UnpackReleaseAction $unpackAction,
        ComposerUpdateAction $composerAction,
        SeedDatabaseUpdateAction $seedAction,
        UpdateVersionEntryAction $versionAction
    ) {
        $log = [];
        $isMaintenance = App::isDownForMaintenance() === true;
        $validated = $request->validate([
            'url'   => ['required', 'url'],
            'version' => ['required', 'string'],
            'shasum'    => ['required', 'string']
        ]);

        try {
            $cleanupAction->execute();
            $downloadAction->execute();
            $isValid = $validateDownloadAction->execute(checksum: $validated['shasum']);
        } catch (\Exception $err) {
            return $this->sendFailed($err->getMessage(), $log);
        }

        // User could be in maintenance bypass mode, in which case, leave as is
        if (!$isMaintenance) {
            $bypassKey = $downAction->execute();
            $log[] = "Maintenance mode enabled";
            $log[] = "Bypass route is: " . config('app.url') . "/" . $bypassKey;
        }

        try {
            $unpackAction->execute();
            $composerAction->execute();
            $log[] = "Update dependencies";
            $seedAction->execute();
            Artisan::call('vendor:publish', [
                '--tag'      => 'adminui-public',
                '--force'    => true
            ]);
            Artisan::call('optimize:clear');
            Artisan::call('optimize');
        } catch (\Exception $err) {
            return $this->sendFailed($err->getMessage(), $log);
        }

        $versionAction->execute(version: $validated['version']);

        if (!$isMaintenance) {
            Artisan::call('up');
            $log[] = "Maintenance mode disabled";
        }

        return $this->sendSuccess(log: $log);
    }

    /**
     * getMajor - Extract the MAJOR version number from a semantic versioning string
     *
     * @param  string $version
     * @return int
     */
    private function getMajor(string $version): int
    {
        preg_match('/v?(\d+)\.(\d+)/', $version, $matches);
        return intval($matches[1] ?? 0);
    }
}
