<?php

namespace MicroweberPackages\AppBootstrapCache\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Base test case for the bootstrap cache package.
 *
 * When used standalone with Orchestra Testbench, override this to extend
 * Orchestra\Testbench\TestCase. In the CMS monorepo the tests run directly
 * against PHPUnit since all dependencies are already autoloaded.
 */
abstract class TestCase extends PHPUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }
}