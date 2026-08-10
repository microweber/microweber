<?php

namespace MicroweberPackages\PhpQuery\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use MicroweberPackages\PhpQuery\Providers\PhpQueryServiceProvider;
use MicroweberPackages\PhpQuery\PhpQuery;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            PhpQueryServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'PhpQuery' => \MicroweberPackages\PhpQuery\Facades\PhpQuery::class,
        ];
    }

    protected function tearDown(): void
    {
        // Clean up documents to prevent memory leaks
        if (class_exists(PhpQuery::class, false)) {
            PhpQuery::unloadDocuments();
        }

        parent::tearDown();
    }
}