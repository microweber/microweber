<?php

namespace MicroweberPackages\Event\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use MicroweberPackages\Event\EventManagerServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            EventManagerServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'EventManager' => \MicroweberPackages\Event\EventManagerFacade::class,
        ];
    }
}