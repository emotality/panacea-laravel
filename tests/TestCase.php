<?php

namespace Emotality\Panacea\Tests;

use Emotality\Panacea\PanaceaMobileServiceProvider;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            PanaceaMobileServiceProvider::class,
        ];
    }
}
