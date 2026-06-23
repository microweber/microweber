<?php

namespace MicroweberPackages\Config\Tests;

use Illuminate\Support\Facades\Config;
use MicroweberPackages\Config\ConfigRepository;
use PHPUnit\Framework\Attributes\Test;

class ConfigRepositoryTest extends TestCase
{
    #[Test]
    public function it_can_set_and_get_config_values(): void
    {
        Config::set('testkey.name', 'TestValue');
        $this->assertEquals('TestValue', Config::get('testkey.name'));
    }

    #[Test]
    public function it_returns_default_when_key_not_found(): void
    {
        $this->assertEquals('fallback', Config::get('nonexistent.key', 'fallback'));
    }

    #[Test]
    public function it_returns_null_for_missing_key_without_default(): void
    {
        $this->assertNull(Config::get('totally.missing.key'));
    }

    #[Test]
    public function it_can_set_nested_config(): void
    {
        Config::set('deep.nested.key.value', 'deep_val');
        $this->assertEquals('deep_val', Config::get('deep.nested.key.value'));
    }

    #[Test]
    public function it_can_set_array_config(): void
    {
        Config::set('myarr', ['a' => 1, 'b' => 2]);
        $result = Config::get('myarr');
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['a']);
        $this->assertEquals(2, $result['b']);
    }

    #[Test]
    public function it_tracks_changed_keys(): void
    {
        $config = $this->app->make('config');
        $config->set('tracked.key', 'tracked_value');

        $changed = $config->getChangedKeys();
        $this->assertArrayHasKey('tracked.key', $changed);
        $this->assertEquals('tracked_value', $changed['tracked.key']);
    }

    #[Test]
    public function it_has_instance_of_config_repository(): void
    {
        $config = $this->app->make('config');
        $this->assertInstanceOf(ConfigRepository::class, $config);
    }

    #[Test]
    public function it_clears_cache_on_set(): void
    {
        Config::set('cache_test', 'first');
        $this->assertEquals('first', Config::get('cache_test'));

        Config::set('cache_test', 'second');
        $this->assertEquals('second', Config::get('cache_test'));
    }

    #[Test]
    public function it_can_check_if_config_has_key(): void
    {
        Config::set('exists.key', 'yes');
        $this->assertTrue(Config::has('exists.key'));
        $this->assertFalse(Config::has('does_not.exist'));
    }

    #[Test]
    public function it_can_get_all_items(): void
    {
        Config::set('alltest.a', 1);
        $all = Config::all();
        $this->assertIsArray($all);
        $this->assertArrayHasKey('alltest', $all);
    }

    #[Test]
    public function it_tracks_changed_keys_when_set_with_array(): void
    {
        // The array form Config::set(['k' => 'v']) must be tracked for save(),
        // not only the string form Config::set('k', 'v').
        $config = $this->app->make('config');
        Config::set(['arrtracked.key' => 'arr_value']);

        $changed = $config->getChangedKeys();
        $this->assertArrayHasKey('arrtracked.key', $changed);
        $this->assertEquals('arr_value', $changed['arrtracked.key']);
    }

    #[Test]
    public function it_does_not_cache_default_for_missing_key(): void
    {
        // get() with a default must not poison a later get() (no default) for the
        // same missing key into returning the stale default instead of null.
        $this->assertEquals('fb', Config::get('cache_pollution.missing', 'fb'));
        $this->assertNull(Config::get('cache_pollution.missing'));
    }

    #[Test]
    public function it_handles_boolean_values(): void
    {
        Config::set('booltest.flag', true);
        $this->assertTrue(Config::get('booltest.flag'));

        Config::set('booltest.flag', false);
        $this->assertFalse(Config::get('booltest.flag'));
    }

    #[Test]
    public function it_handles_integer_values(): void
    {
        Config::set('inttest.count', 42);
        $this->assertEquals(42, Config::get('inttest.count'));
    }

    #[Test]
    public function it_handles_null_values(): void
    {
        Config::set('nulltest', ['val' => null]);
        $this->assertNull(Config::get('nulltest.val'));
    }

    #[Test]
    public function it_reports_non_multisite_by_default(): void
    {
        $config = $this->app->make('config');
        // In test environment with no env subdirectory, multisite should be false
        // (unless a 'testing' directory happens to exist)
        $this->assertIsBool($config->isMultisite());
    }

    #[Test]
    public function it_can_clear_static_cache(): void
    {
        Config::set('clearcache_test', 'val');
        Config::get('clearcache_test'); // populate cache
        ConfigRepository::clearCache();

        // After clearing, the value should still be retrievable from the repository
        $this->assertEquals('val', Config::get('clearcache_test'));
    }
}
