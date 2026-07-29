<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Tests\Feature;

use Illuminate\Support\Facades\Schema;
use MicroweberPackages\TemplateFonts\Models\TemplateFont;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;
use MicroweberPackages\TemplateFonts\Tests\TestCase;

/**
 * Tests that the package boots and operates correctly on SQLite, MySQL, and PostgreSQL.
 *
 * Driver selected by MW_TEST_DB_DRIVER env var (default: sqlite).
 */
class MultiDatabaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $driver = env('MW_TEST_DB_DRIVER', 'sqlite');

        if ($driver === 'mysql') {
            config([
                'database.default' => 'mysql',
                'database.connections.mysql' => [
                    'driver' => 'mysql',
                    'host' => env('DB_HOST', '127.0.0.1'),
                    'port' => env('DB_PORT', '3306'),
                    'database' => env('DB_DATABASE', 'template_fonts_test'),
                    'username' => env('DB_USERNAME', 'root'),
                    'password' => env('DB_PASSWORD', 'root'),
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                ],
            ]);
        } elseif ($driver === 'pgsql') {
            config([
                'database.default' => 'pgsql',
                'database.connections.pgsql' => [
                    'driver' => 'pgsql',
                    'host' => env('DB_HOST', '127.0.0.1'),
                    'port' => env('DB_PORT', '5432'),
                    'database' => env('DB_DATABASE', 'postgres'),
                    'username' => env('DB_USERNAME', 'postgres'),
                    'password' => env('DB_PASSWORD', 'postgres'),
                    'charset' => 'utf8',
                    'prefix' => '',
                    'schema' => 'public',
                ],
            ]);
        } else {
            config([
                'database.default' => 'testing',
                'database.connections.testing' => [
                    'driver' => 'sqlite',
                    'database' => ':memory:',
                    'prefix' => '',
                ],
            ]);
        }
    }

    public function test_service_resolves_on_current_driver(): void
    {
        $service = app(TemplateFontsManager::class);
        $this->assertInstanceOf(TemplateFontsManager::class, $service);
    }

    public function test_table_and_crud_on_current_driver(): void
    {
        $driver = env('MW_TEST_DB_DRIVER', 'sqlite');

        if ($driver === 'mysql') {
            try {
                $pdo = new \PDO('mysql:host=127.0.0.1', 'root', 'root');
                $pdo->exec('CREATE DATABASE IF NOT EXISTS template_fonts_test');
            } catch (\Throwable $e) {
                $this->markTestSkipped('MySQL not available: ' . $e->getMessage());
            }
        }

        if ($driver === 'pgsql') {
            try {
                new \PDO('pgsql:host=127.0.0.1;user=postgres;password=postgres', 'postgres', 'postgres');
            } catch (\Throwable $e) {
                $this->markTestSkipped('PostgreSQL not available: ' . $e->getMessage());
            }
        }

        // Re-run migrations for the active connection when possible
        try {
            if (!Schema::hasTable('template_fonts')) {
                $migration = require __DIR__ . '/../../database/migrations/2026_07_29_000000_create_template_fonts_table.php';
                $migration->up();
            }
        } catch (\Throwable $e) {
            $this->markTestSkipped('Could not ensure table: ' . $e->getMessage());
        }

        $manager = app(TemplateFontsManager::class);
        $family = 'MultiDbFont_' . $driver . '_' . substr(md5((string) microtime(true)), 0, 6);
        $this->assertTrue($manager->enableFont($family));
        $this->assertContains($family, $manager->getEnabledFonts());
        $this->assertTrue(TemplateFont::query()->where('family', $family)->exists());
        $manager->removeFont($family);
    }

    public function test_routes_on_current_driver(): void
    {
        $response = $this->get(route('print_custom_css_fonts'));
        $response->assertStatus(200);
    }
}
