<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Tests;

use MicroweberPackages\AiTools\Providers\AiToolsServiceProvider;

/**
 * Base test case for the ai-tools package.
 *
 * Prefer the monorepo CMS test harness when available so registry + config
 * boot the same way as production. Fall back to Orchestra Testbench for
 * true standalone package installs.
 */
if (class_exists(\Tests\TestCase::class)) {
    abstract class TestCase extends \Tests\TestCase
    {
        protected function setUp(): void
        {
            parent::setUp();

            if (!$this->app->getProvider(AiToolsServiceProvider::class)) {
                $this->app->register(AiToolsServiceProvider::class);
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
                AiToolsServiceProvider::class,
            ];
        }

        protected function getEnvironmentSetUp($app): void
        {
            $app['config']->set('ai-tools.enabled', true);
            $app['config']->set('ai-tools.tools', [
                \MicroweberPackages\AiTools\Tools\External\AmazonScraperTool::class,
                \MicroweberPackages\AiTools\Tools\External\GoogleTrendsTool::class,
                \MicroweberPackages\AiTools\Tools\External\SupadataTool::class,
            ]);
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
