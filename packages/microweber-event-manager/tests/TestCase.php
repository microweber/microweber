<?php

namespace MicroweberPackages\Event\Tests;

use MicroweberPackages\Event\EventManagerFacade;
use MicroweberPackages\Event\EventManagerServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            EventManagerServiceProvider::class,
        ];
    }

    /**
     * @return array<string, class-string>
     */
    protected function getPackageAliases($app): array
    {
        return [
            'EventManager' => EventManagerFacade::class,
        ];
    }
}