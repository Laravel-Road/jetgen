<?php

namespace LaravelRoad\JetGen\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use LaravelRoad\JetGen\Commands\MigrationCommand;

class JetGenServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
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