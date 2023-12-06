<?php

namespace AdminUI\AdminUIInstaller;

use AdminUI\AdminUIInstaller\Actions\CheckDatabaseConnectionAction;
use AdminUI\AdminUIInstaller\Actions\GetLatestReleaseAction;
use AdminUI\AdminUIInstaller\Actions\TestAction;
use AdminUI\AdminUIInstaller\Facades\Composer as FacadesComposer;
use AdminUI\AdminUIInstaller\Facades\Json;
use Illuminate\Support\Composer;
use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    public string $root;

    public function register()
    {
        $this->root = dirname(__FILE__, 2);

        config()->set('adminui-installer.root', $this->root);
        $this->mergeConfigFrom($this->root . '/config/adminui-installer.php', 'adminui-installer');

        $this->app->singleton(\AdminUI\AdminUIInstaller\Facades\Install::class, fn () => new \AdminUI\AdminUIInstaller\Services\InstallService);
        $this->app->singleton(\AdminUI\AdminUIInstaller\Facades\Composer::class, fn () => new \AdminUI\AdminUIInstaller\Services\ComposerService);
        $this->app->singleton(\AdminUI\AdminUIInstaller\Facades\Json::class, fn () => new \AdminUI\AdminUIInstaller\Services\JsonService);


        if ($this->app->runningInConsole()) {
            $this->commands([
                \AdminUI\AdminUIInstaller\Commands\InstallCommand::class,
                \AdminUI\AdminUIInstaller\Commands\UninstallCommand::class,
                \AdminUI\AdminUIInstaller\Commands\UpdateCommand::class
            ]);
        }
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'adminui-installer');
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        /* $output = FacadesComposer::run("update --no-scripts --no-interaction");
        dd($output); */
        dd(Json::get());
    }
}
