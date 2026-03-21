<?php

namespace Tests\Feature;

use App\Providers\CacheServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class CacheConfigurationTest extends TestCase
{
    /**
     * Test that cache configuration is properly loaded.
     */
    public function test_cache_configuration_is_loaded(): void
    {
        $this->assertNotNull(config('cache.default'));
        $this->assertIsArray(config('cache.stores'));
        $this->assertArrayHasKey('redis', config('cache.stores'));
        $this->assertArrayHasKey('file', config('cache.stores'));
        $this->assertArrayHasKey('array', config('cache.stores'));
        $this->assertArrayHasKey('database', config('cache.stores'));
    }

    /**
     * Test that Redis cache configuration exists.
     */
    public function test_redis_cache_configuration_exists(): void
    {
        $redisConfig = config('cache.stores.redis');
        $this->assertIsArray($redisConfig);
        $this->assertEquals('redis', $redisConfig['driver']);
        $this->assertNotNull($redisConfig['connection']);
    }

    /**
     * Test that Redis database configuration exists.
     */
    public function test_redis_database_configuration_exists(): void
    {
        $redisConfig = config('database.redis');
        $this->assertIsArray($redisConfig);
        $this->assertArrayHasKey('default', $redisConfig);
        $this->assertArrayHasKey('cache', $redisConfig);
        $this->assertArrayHasKey('session', $redisConfig);
        $this->assertArrayHasKey('queue', $redisConfig);
    }

    /**
     * Test that Redis configuration has proper structure.
     */
    public function test_redis_configuration_has_proper_structure(): void
    {
        $defaultConfig = config('database.redis.default');
        $this->assertIsArray($defaultConfig);
        $this->assertArrayHasKey('host', $defaultConfig);
        $this->assertArrayHasKey('port', $defaultConfig);
        $this->assertArrayHasKey('database', $defaultConfig);

        $cacheConfig = config('database.redis.cache');
        $this->assertIsArray($cacheConfig);
        $this->assertArrayHasKey('database', $cacheConfig);

        $sessionConfig = config('database.redis.session');
        $this->assertIsArray($sessionConfig);
        $this->assertArrayHasKey('database', $sessionConfig);

        $queueConfig = config('database.redis.queue');
        $this->assertIsArray($queueConfig);
        $this->assertArrayHasKey('database', $queueConfig);
    }

    /**
     * Test that cache TTL configuration exists.
     */
    public function test_cache_ttl_configuration_exists(): void
    {
        $this->assertNotNull(config('cache.ttl'));
        $this->assertIsInt(config('cache.ttl'));
    }

    /**
     * Test that cache lock configuration exists.
     */
    public function test_cache_lock_configuration_exists(): void
    {
        $this->assertNotNull(config('cache.lock'));
        $this->assertIsArray(config('cache.lock'));
        $this->assertArrayHasKey('prefix', config('cache.lock'));
        $this->assertArrayHasKey('ttl', config('cache.lock'));
    }

    /**
     * Test that cache prefix is configured.
     */
    public function test_cache_prefix_is_configured(): void
    {
        $prefix = config('cache.prefix');
        $this->assertNotNull($prefix);
        $this->assertIsString($prefix);
    }

    /**
     * Test that array cache driver works.
     */
    public function test_array_cache_driver_works(): void
    {
        Config::set('cache.default', 'array');
        Cache::store()->flush();

        Cache::put('test_key', 'test_value', 60);
        $this->assertEquals('test_value', Cache::get('test_key'));

        Cache::forget('test_key');
        $this->assertNull(Cache::get('test_key'));
    }

    /**
     * Test cache remember functionality.
     */
    public function test_cache_remember_functionality(): void
    {
        Config::set('cache.default', 'array');
        Cache::store()->flush();

        $called = false;
        $value = Cache::remember('remember_test', 60, function () use (&$called) {
            $called = true;

            return 'remembered_value';
        });

        $this->assertTrue($called);
        $this->assertEquals('remembered_value', $value);

        // Second call should not invoke the callback
        $called = false;
        $value = Cache::remember('remember_test', 60, function () use (&$called) {
            $called = true;

            return 'different_value';
        });

        $this->assertFalse($called);
        $this->assertEquals('remembered_value', $value);
    }

    /**
     * Test cache tags functionality if supported.
     */
    public function test_cache_tags_functionality(): void
    {
        // Only test if the current driver supports tagging
        $store = Cache::store();

        if (! method_exists($store, 'tags')) {
            $this->markTestSkipped('Current cache driver does not support tagging');
        }

        Cache::tags(['test_tag'])->put('tagged_key', 'tagged_value', 60);
        $this->assertEquals('tagged_value', Cache::tags(['test_tag'])->get('tagged_key'));

        Cache::tags(['test_tag'])->flush();
        $this->assertNull(Cache::tags(['test_tag'])->get('tagged_key'));
    }

    /**
     * Test CacheServiceProvider health check method.
     */
    public function test_cache_service_provider_health_check_exists(): void
    {
        $this->assertTrue(method_exists(CacheServiceProvider::class, 'isHealthy'));
        $this->assertTrue(method_exists(CacheServiceProvider::class, 'getStatus'));
    }

    /**
     * Test CacheServiceProvider status structure.
     */
    public function test_cache_service_provider_status_structure(): void
    {
        $status = CacheServiceProvider::getStatus();

        $this->assertIsArray($status);
        $this->assertArrayHasKey('driver', $status);
        $this->assertArrayHasKey('healthy', $status);
        $this->assertArrayHasKey('prefix', $status);
        $this->assertArrayHasKey('ttl', $status);
    }

    /**
     * Test that environment variables are properly referenced.
     */
    public function test_environment_variables_are_referenced(): void
    {
        // Check that env() calls exist in config files
        $cacheConfig = file_get_contents(config_path('cache.php'));
        $this->assertStringContainsString("env('CACHE_STORE')", $cacheConfig);
        $this->assertStringContainsString("env('CACHE_DRIVER')", $cacheConfig);
        $this->assertStringContainsString("env('CACHE_TTL", $cacheConfig);
        // CACHE_PREFIX is used through env('APP_NAME') in the default
        $this->assertStringContainsString("env('APP_NAME'", $cacheConfig);

        $databaseConfig = file_get_contents(config_path('database.php'));
        $this->assertStringContainsString("env('REDIS_HOST'", $databaseConfig);
        $this->assertStringContainsString("env('REDIS_PORT'", $databaseConfig);
        $this->assertStringContainsString("env('REDIS_PASSWORD'", $databaseConfig);
    }

    /**
     * Test cache increment and decrement.
     */
    public function test_cache_increment_and_decrement(): void
    {
        Config::set('cache.default', 'array');
        Cache::store()->flush();

        Cache::put('counter', 0, 60);

        Cache::increment('counter');
        $this->assertEquals(1, Cache::get('counter'));

        Cache::increment('counter', 5);
        $this->assertEquals(6, Cache::get('counter'));

        Cache::decrement('counter');
        $this->assertEquals(5, Cache::get('counter'));

        Cache::decrement('counter', 3);
        $this->assertEquals(2, Cache::get('counter'));
    }

    /**
     * Test cache forever functionality.
     */
    public function test_cache_forever_functionality(): void
    {
        Config::set('cache.default', 'array');
        Cache::store()->flush();

        Cache::forever('forever_key', 'forever_value');
        $this->assertEquals('forever_value', Cache::get('forever_key'));

        // After flush, it should be gone
        Cache::flush();
        $this->assertNull(Cache::get('forever_key'));
    }

    /**
     * Test that fallback cache configuration exists.
     */
    public function test_fallback_cache_configuration_exists(): void
    {
        // In production, if Redis fails, it should fall back to file
        $this->assertArrayHasKey('file', config('cache.stores'));

        $fileConfig = config('cache.stores.file');
        $this->assertIsArray($fileConfig);
        $this->assertEquals('file', $fileConfig['driver']);
        $this->assertArrayHasKey('path', $fileConfig);
    }

    /**
     * Test that multiple cache stores can be used.
     */
    public function test_multiple_cache_stores_can_be_used(): void
    {
        Config::set('cache.default', 'array');

        // Access different stores
        $arrayStore = Cache::store('array');
        $this->assertInstanceOf(\Illuminate\Cache\Repository::class, $arrayStore);

        // File store should also be accessible
        $fileStore = Cache::store('file');
        $this->assertInstanceOf(\Illuminate\Cache\Repository::class, $fileStore);
    }

    /**
     * Test that Redis-specific cache store exists.
     */
    public function test_redis_specific_cache_store_exists(): void
    {
        $this->assertArrayHasKey('redis_session', config('cache.stores'));

        $sessionStore = config('cache.stores.redis_session');
        $this->assertEquals('redis', $sessionStore['driver']);
    }
}
