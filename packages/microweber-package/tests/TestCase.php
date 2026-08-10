<?php

declare(strict_types=1);

namespace MicroweberPackages\Package\Tests;

use MicroweberPackages\Package\Tests\Fixtures\ExamplePackageServiceProvider;

/**
 * Base test case for the package loader.
 *
 * Prefers the monorepo CMS harness when available; falls back to Orchestra
 * Testbench for true standalone installs.
 */
if (class_exists(\Tests\TestCase::class)) {
    abstract class TestCase extends \Tests\TestCase
    {
        protected function setUp(): void
        {
            parent::setUp();

            if (! $this->app->getProvider(ExamplePackageServiceProvider::class)) {
                $this->app->register(ExamplePackageServiceProvider::class);
            }
        }
    }
} elseif (class_exists(\Orchestra\Testbench\TestCase::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        /**
         * @return list<class-string>
         */
        protected function getPackageProviders($app): array
        {
            return [
                ExamplePackageServiceProvider::class,
            ];
        }
    }
} else {
    abstract class TestCase extends \PHPUnit\Framework\TestCase
    {
        protected function setUp(): void
        {
            parent::setUp();
            $this->markTestSkipped('Laravel test harness (Tests\\TestCase or Orchestra) is required');
        }
    }
}
