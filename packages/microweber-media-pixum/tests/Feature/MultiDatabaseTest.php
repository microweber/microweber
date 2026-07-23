<?php

namespace MicroweberPackages\MediaPixum\Tests\Feature;

use MicroweberPackages\MediaPixum\PixumGenerator;
use MicroweberPackages\MediaPixum\Tests\TestCase;

/**
 * Verify pixum generation works regardless of database driver.
 *
 * Pixum itself does not use a database, but this test ensures the
 * package boots and serves images on all three supported drivers.
 */
class MultiDatabaseTest extends TestCase
{
    protected function tearDown(): void
    {
        $cachePath = app(PixumGenerator::class)->getCachePath();
        if (is_dir($cachePath)) {
            $files = glob($cachePath . '/*.png');
            if ($files) {
                foreach ($files as $f) {
                    @unlink($f);
                }
            }
            @rmdir($cachePath);
        }
        parent::tearDown();
    }

    /**
     * @dataProvider databaseDriverProvider
     */
    public function test_pixum_works_on_database_driver(string $driver, array $config): void
    {
        if ($driver === 'pgsql' && !extension_loaded('pgsql') && !extension_loaded('pdo_pgsql')) {
            $this->markTestSkipped('pdo_pgsql extension not available');
        }
        if ($driver === 'mysql' && !extension_loaded('pdo_mysql')) {
            $this->markTestSkipped('pdo_mysql extension not available');
        }

        // The pixum package does not need the DB, but we verify it boots correctly
        $this->app['config']->set('database.default', $driver);
        $this->app['config']->set("database.connections.{$driver}", $config);

        $generator = app(PixumGenerator::class);
        $path = $generator->generate(10, 10);

        $this->assertFileExists($path);
    }

    /**
     * @return array<string, array{0: string, 1: array<string, mixed>}>
     */
    public static function databaseDriverProvider(): array
    {
        return [
            'sqlite' => [
                'sqlite',
                ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => ''],
            ],
            'mysql' => [
                'mysql',
                [
                    'driver' => 'mysql',
                    'host' => '127.0.0.1',
                    'port' => '3306',
                    'database' => 'test_pixum',
                    'username' => 'root',
                    'password' => 'root',
                ],
            ],
            'pgsql' => [
                'pgsql',
                [
                    'driver' => 'pgsql',
                    'host' => '127.0.0.1',
                    'port' => '5432',
                    'database' => 'test_pixum',
                    'username' => 'postgres',
                    'password' => 'postgres',
                ],
            ],
        ];
    }
}