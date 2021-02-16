<?php

namespace LaravelRoad\JetGen\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use LaravelRoad\JetGen\Commands\MigrationCommand;

class JetGenServiceProvider extends ServiceProvider implements DeferrableProvider
{
    protected const ROOT_PATH = __DIR__ . '/../../';

    public function boot()
    {
        $this->publishes([
            self::ROOT_PATH.'/config/jetgen.php' => config_path('jetgen.php'),
        ], 'jetgen');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::ROOT_PATH.'/config/jetgen.php',
            'jetgen'
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                MigrationCommand::class,
            ]);
        }
    }

    public function provides()
    {
        return [
            MigrationCommand::class,
        ];
    }
}