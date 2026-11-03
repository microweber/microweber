<?php

declare(strict_types=1);

namespace MicroweberPackages\FormBuilder\Tests;

use MicroweberPackages\FormBuilder\Providers\FormBuilderServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            FormBuilderServiceProvider::class,
        ];
    }
}
