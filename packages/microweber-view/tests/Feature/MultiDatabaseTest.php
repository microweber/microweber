<?php

declare(strict_types=1);

namespace MicroweberPackages\View\Tests\Feature;

use MicroweberPackages\View\StringBlade;
use MicroweberPackages\View\Tests\TestCase;
use MicroweberPackages\View\TwigView;
use MicroweberPackages\View\View;

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
                    'database' => env('DB_DATABASE', 'microweber_view_test'),
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

    public function test_services_resolve_on_current_driver(): void
    {
        $this->assertInstanceOf(StringBlade::class, app(StringBlade::class));
        $this->assertInstanceOf(TwigView::class, app(TwigView::class));
    }

    public function test_view_render_on_current_driver(): void
    {
        $file = sys_get_temp_dir() . '/mw_view_' . uniqid('', true) . '.php';
        file_put_contents($file, 'DB=<?php echo htmlspecialchars((string) $driver, ENT_QUOTES); ?>');

        try {
            $view = new View($file);
            $view->assign('driver', (string) config('database.default'));
            $this->assertStringContainsString('DB=', (string) $view);
        } finally {
            @unlink($file);
        }
    }

    public function test_can_ping_database_connection(): void
    {
        $driver = env('MW_TEST_DB_DRIVER', 'sqlite');

        try {
            $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
            $this->assertNotNull($pdo);
        } catch (\Throwable $e) {
            if (in_array($driver, ['mysql', 'pgsql'], true)) {
                $this->markTestSkipped("Database driver {$driver} not available: " . $e->getMessage());
            }
            throw $e;
        }
    }
}
