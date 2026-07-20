<?php

declare(strict_types=1);

namespace MicroweberPackages\SvgIcons\Tests;

use BladeUI\Icons\BladeIconsServiceProvider;
use MicroweberPackages\SvgIcons\SvgIconsServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            BladeIconsServiceProvider::class,
            SvgIconsServiceProvider::class,
        ];
    }
}
