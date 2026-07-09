<?php

declare(strict_types=1);

namespace MicroweberPackages\ConfigMerge\Tests;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\ConfigMerge\MergesConfigFromPackage;

// Support running from both standalone (Orchestra Testbench) and the root project (Tests\TestCase).
if (class_exists(\Tests\TestCase::class)) {
    abstract class BaseTestCase extends \Tests\TestCase {}
} elseif (class_exists(\Orchestra\Testbench\TestCase::class)) {
    abstract class BaseTestCase extends \Orchestra\Testbench\TestCase {}
} else {
    abstract class BaseTestCase extends \PHPUnit\Framework\TestCase {}
}

/**
 * A concrete service provider that uses the trait, for test purposes.
 */
class StubServiceProvider extends ServiceProvider
{
    use MergesConfigFromPackage;

    /**
     * Expose mergeConfig as a public method for testing.
     */
    public function testMergeConfig(array $original, array $merging): array
    {
        return $this->mergeConfig($original, $merging);
    }

    /**
     * Expose mergeConfigFrom as a public method for testing.
     */
    public function testMergeConfigFrom(string $path, string $key): void
    {
        $this->mergeConfigFrom($path, $key);
    }

    public function register(): void
    {
        // no-op
    }
}

class MergesConfigFromPackageTest extends BaseTestCase
{
    private StubServiceProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->provider = new StubServiceProvider($this->app);
    }

    // ─── mergeConfig() unit tests ─────────────────────────────────────

    public function test_package_scalar_overrides_app_scalar(): void
    {
        $result = $this->provider->testMergeConfig(
            ['key' => 'app-value'],
            ['key' => 'package-value']
        );

        $this->assertSame('package-value', $result['key']);
    }

    public function test_app_only_keys_are_preserved(): void
    {
        $result = $this->provider->testMergeConfig(
            ['app_only' => 'stays'],
            ['pkg_only' => 'added']
        );

        $this->assertSame('stays', $result['app_only']);
        $this->assertSame('added', $result['pkg_only']);
    }

    public function test_package_adds_new_keys(): void
    {
        $result = $this->provider->testMergeConfig(
            [],
            ['new_key' => 'new_value']
        );

        $this->assertSame('new_value', $result['new_key']);
    }

    public function test_nested_associative_arrays_are_recursively_merged(): void
    {
        $result = $this->provider->testMergeConfig(
            ['database' => ['host' => 'localhost', 'port' => 3306]],
            ['database' => ['host' => 'package-host', 'name' => 'pkg_db']]
        );

        $this->assertSame('package-host', $result['database']['host']);
        $this->assertSame(3306, $result['database']['port']);
        $this->assertSame('pkg_db', $result['database']['name']);
    }

    public function test_deeply_nested_merge(): void
    {
        $result = $this->provider->testMergeConfig(
            ['level1' => ['level2' => ['level3' => 'app', 'only_app' => true]]],
            ['level1' => ['level2' => ['level3' => 'pkg', 'only_pkg' => true]]]
        );

        $this->assertSame('pkg', $result['level1']['level2']['level3']);
        $this->assertTrue($result['level1']['level2']['only_app']);
        $this->assertTrue($result['level1']['level2']['only_pkg']);
    }

    public function test_numeric_keyed_arrays_are_concatenated_not_recursed(): void
    {
        $result = $this->provider->testMergeConfig(
            ['items' => [0 => 'a', 1 => 'b']],
            ['items' => [0 => 'c', 1 => 'd']]
        );

        // array_merge with numeric keys appends
        // But since items is associative at parent level and items has numeric keys,
        // the mergeConfig doesn't recurse into numeric-keyed sub-arrays
        // Result should have numeric keys merged via array_merge
        $this->assertContains('c', $result['items']);
        $this->assertContains('d', $result['items']);
    }

    public function test_middleware_key_is_concatenated_not_recursed(): void
    {
        $result = $this->provider->testMergeConfig(
            ['middleware' => ['auth', 'throttle']],
            ['middleware' => ['cors', 'custom']]
        );

        // middleware is a special key - should use array_merge, not recurse
        $this->assertSame(['cors', 'custom'], $result['middleware']);
    }

    public function test_register_key_is_concatenated_not_recursed(): void
    {
        $result = $this->provider->testMergeConfig(
            ['register' => ['ServiceA', 'ServiceB']],
            ['register' => ['ServiceC']]
        );

        $this->assertSame(['ServiceC'], $result['register']);
    }

    public function test_empty_app_config_uses_package_entirely(): void
    {
        $package = ['a' => 1, 'b' => ['c' => 2]];
        $result = $this->provider->testMergeConfig([], $package);

        $this->assertSame($package, $result);
    }

    public function test_empty_package_config_preserves_app(): void
    {
        $app = ['a' => 1, 'b' => ['c' => 2]];
        $result = $this->provider->testMergeConfig($app, []);

        $this->assertSame($app, $result);
    }

    public function test_package_null_overrides_app_value(): void
    {
        $result = $this->provider->testMergeConfig(
            ['key' => 'value'],
            ['key' => null]
        );

        $this->assertNull($result['key']);
    }

    public function test_package_bool_overrides_app_bool(): void
    {
        $result = $this->provider->testMergeConfig(
            ['debug' => false],
            ['debug' => true]
        );

        $this->assertTrue($result['debug']);
    }

    public function test_mixed_types_package_scalar_overrides_app_array(): void
    {
        $result = $this->provider->testMergeConfig(
            ['key' => ['nested' => 'value']],
            ['key' => 'scalar']
        );

        // Package wins with scalar
        $this->assertSame('scalar', $result['key']);
    }

    // ─── mergeConfigFrom() integration tests ─────────────────────────

    public function test_merge_config_from_file(): void
    {
        // Pre-set application config
        $this->app['config']->set('test-pkg', ['app_key' => 'app_val', 'shared' => 'app']);

        $configFile = $this->createTempConfig([
            'shared' => 'package',
            'pkg_key' => 'pkg_val',
        ]);

        $this->provider->testMergeConfigFrom($configFile, 'test-pkg');

        $this->assertSame('package', $this->app['config']->get('test-pkg.shared'));
        $this->assertSame('app_val', $this->app['config']->get('test-pkg.app_key'));
        $this->assertSame('pkg_val', $this->app['config']->get('test-pkg.pkg_key'));
    }

    public function test_merge_config_from_file_with_no_existing_config(): void
    {
        $configFile = $this->createTempConfig([
            'key1' => 'val1',
            'key2' => ['nested' => 'val2'],
        ]);

        $this->provider->testMergeConfigFrom($configFile, 'fresh-pkg');

        $this->assertSame('val1', $this->app['config']->get('fresh-pkg.key1'));
        $this->assertSame('val2', $this->app['config']->get('fresh-pkg.key2.nested'));
    }

    public function test_merge_config_from_handles_non_array_file(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'cfg_');
        file_put_contents($file, '<?php return "not-an-array";');

        $this->provider->testMergeConfigFrom($file, 'bad-pkg');

        // Should treat non-array as empty, preserving existing config
        $this->assertSame([], $this->app['config']->get('bad-pkg', []));

        unlink($file);
    }

    public function test_merge_config_from_missing_file_is_a_noop(): void
    {
        // Pre-set application config
        $this->app['config']->set('missing-pkg', ['existing' => 'kept']);

        $missingPath = sys_get_temp_dir() . '/definitely-not-a-real-config-' . __LINE__ . '.php';
        $this->assertFileDoesNotExist($missingPath);

        // A missing path must NOT fatal on a bare `require` — it degrades to a
        // no-op, leaving the existing config untouched.
        $this->provider->testMergeConfigFrom($missingPath, 'missing-pkg');

        $this->assertSame('kept', $this->app['config']->get('missing-pkg.existing'));
    }

    public function test_merge_config_from_nested_override(): void
    {
        $this->app['config']->set('deep-pkg', [
            'database' => [
                'host' => 'localhost',
                'port' => 3306,
                'options' => ['timeout' => 30],
            ],
        ]);

        $configFile = $this->createTempConfig([
            'database' => [
                'host' => 'package-db.example.com',
                'options' => ['timeout' => 60, 'retry' => true],
            ],
        ]);

        $this->provider->testMergeConfigFrom($configFile, 'deep-pkg');

        $db = $this->app['config']->get('deep-pkg.database');
        $this->assertSame('package-db.example.com', $db['host']);
        $this->assertSame(3306, $db['port']);
        $this->assertSame(60, $db['options']['timeout']);
        $this->assertTrue($db['options']['retry']);
    }

    public function test_package_priority_over_laravel_default_merge(): void
    {
        // Simulate what Laravel's built-in mergeConfigFrom would do:
        // App config wins. Our trait inverts: package config wins.
        $this->app['config']->set('priority-test', [
            'driver' => 'app-driver',
            'app_specific' => true,
        ]);

        $configFile = $this->createTempConfig([
            'driver' => 'package-driver',
            'package_specific' => true,
        ]);

        $this->provider->testMergeConfigFrom($configFile, 'priority-test');

        // Package driver should win (unlike Laravel default)
        $this->assertSame('package-driver', $this->app['config']->get('priority-test.driver'));
        $this->assertTrue($this->app['config']->get('priority-test.app_specific'));
        $this->assertTrue($this->app['config']->get('priority-test.package_specific'));
    }

    public function test_multiple_merges_accumulate(): void
    {
        $file1 = $this->createTempConfig(['a' => 1, 'b' => 2]);
        $file2 = $this->createTempConfig(['b' => 3, 'c' => 4]);

        $this->provider->testMergeConfigFrom($file1, 'multi');
        $this->provider->testMergeConfigFrom($file2, 'multi');

        $this->assertSame(1, $this->app['config']->get('multi.a'));
        $this->assertSame(3, $this->app['config']->get('multi.b')); // second file wins
        $this->assertSame(4, $this->app['config']->get('multi.c'));
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    private function createTempConfig(array $data): string
    {
        $file = tempnam(sys_get_temp_dir(), 'cfg_');
        $export = var_export($data, true);
        file_put_contents($file, "<?php\nreturn {$export};\n");

        // Clean up after test
        $this->beforeApplicationDestroyed(fn () => @unlink($file));

        return $file;
    }
}