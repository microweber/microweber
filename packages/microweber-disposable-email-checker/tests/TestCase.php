<?php

declare(strict_types=1);

namespace MicroweberPackages\DisposableEmailChecker\Tests;

use MicroweberPackages\DisposableEmailChecker\Providers\DisposableEmailCheckerServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            DisposableEmailCheckerServiceProvider::class,
        ];
    }
}