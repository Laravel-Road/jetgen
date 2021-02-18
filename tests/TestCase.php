<?php

namespace LaravelRoad\JetGen\Tests;

use LaravelRoad\JetGen\Providers\JetGenServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            JetGenServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $configs = $this->configs();

        $app['config']->set('jetgen.blueprint_types', $configs['blueprint_types']);
        $app['config']->set('jetgen.string_types', $configs['string_types']);
        $app['config']->set('jetgen.integer_types', $configs['integer_types']);
        $app['config']->set('jetgen.float_types', $configs['float_types']);
        $app['config']->set('jetgen.date_types', $configs['date_types']);
        $app['config']->set('jetgen.foreign_types', $configs['foreign_types']);
    }

    protected function configs(): array
    {
        return require __DIR__ . '/../config/jetgen.php';
    }
}