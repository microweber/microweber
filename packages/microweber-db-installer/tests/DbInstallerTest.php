<?php

namespace MicroweberPackages\DbInstaller\Tests;

use MicroweberPackages\DbInstaller\DbInstaller;

/**
 * Smoke tests for the installer's public surface.
 *
 * The full run()/createSchema() path is exercised by the application
 * integration tests (it consumes the app-specific MicroweberPackages\Install\
 * Schema\* providers and the database table builder), so here we only pin the
 * package's own contract: the class is constructable and exposes the expected
 * orchestration API.
 */
class DbInstallerTest extends TestCase
{
    public function test_installer_is_constructable(): void
    {
        $installer = new DbInstaller();
        $this->assertInstanceOf(DbInstaller::class, $installer);
    }

    public function test_installer_exposes_orchestration_api(): void
    {
        $installer = new DbInstaller();

        foreach (['run', 'createSchema', 'seed', 'getSystemSchemas', 'setSystemSchemas', 'log'] as $method) {
            $this->assertTrue(
                method_exists($installer, $method),
                "DbInstaller must expose {$method}()"
            );
        }
    }

    public function test_is_schema_agnostic_until_schemas_are_injected(): void
    {
        $installer = new DbInstaller();

        // No app-provided schemas by default — the package never reaches back
        // into the application for schema data.
        $this->assertSame([], $installer->getSystemSchemas());

        $schemas = [new \stdClass(), new \stdClass()];
        $returned = $installer->setSystemSchemas($schemas);

        $this->assertSame($installer, $returned, 'setSystemSchemas() is fluent');
        $this->assertSame($schemas, $installer->getSystemSchemas());
    }

    public function test_logger_property_is_settable(): void
    {
        $installer = new DbInstaller();
        $installer->logger = new class {
            public array $messages = [];
            public function log($text): void { $this->messages[] = $text; }
        };

        $installer->log('hello');
        $this->assertSame(['hello'], $installer->logger->messages);
    }
}
