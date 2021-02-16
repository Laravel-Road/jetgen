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
        //
    }
}