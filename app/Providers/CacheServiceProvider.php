<?php

namespace App\Providers;

use Illuminate\Cache\CacheManager;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register cache fallback configuration
        $this->app->singleton('cache.fallback', function ($app) {
            return $app['config']['cache.fallback'] ?? 'file';
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureCache();
        $this->registerCacheMacros();
        $this->setupCacheFallback();
    }

    /**
     * Configure cache settings for production.
     */
    protected function configureCache(): void
    {
        $config = $this->app['config'];

        // Set production cache settings
        if ($this->app->environment('production')) {
            // Ensure Redis is properly configured
            if ($config->get('cache.default') === 'redis') {
                $this->validateRedisConnection();
            }

            // Configure cache TTL from environment
            $ttl = $config->get('cache.ttl', 3600);
            $config->set('cache.ttl', $ttl);

            // Configure lock settings
            $lockPrefix = $config->get('cache.lock.prefix', 'lock_');
            $lockTtl = $config->get('cache.lock.ttl', 60);
            $config->set('cache.lock.prefix', $lockPrefix);
            $config->set('cache.lock.ttl', $lockTtl);
        }
    }

    /**
     * Register custom cache macros.
     */
    protected function registerCacheMacros(): void
    {
        // Remember or throw macro - throws exception if cache fails
        Cache::macro('rememberOrThrow', function (string $key, $ttl, \Closure $callback) {
            $value = Cache::get($key);

            if (! is_null($value)) {
                return $value;
            }

            $value = $callback();

            if (! Cache::put($key, $value, $ttl)) {
                throw new \RuntimeException("Failed to store value in cache for key: {$key}");
            }

            return $value;
        });

        // Remember forever or throw macro
        Cache::macro('rememberForeverOrThrow', function (string $key, \Closure $callback) {
            $value = Cache::get($key);

            if (! is_null($value)) {
                return $value;
            }

            $value = $callback();

            if (! Cache::forever($key, $value)) {
                throw new \RuntimeException("Failed to store value in cache forever for key: {$key}");
            }

            return $value;
        });

        // Safe get macro with fallback on exception
        Cache::macro('safeGet', function (string $key, $default = null) {
            try {
                return Cache::get($key, $default);
            } catch (\Exception $e) {
                Log::warning('Cache get failed', ['key' => $key, 'error' => $e->getMessage()]);
                return $default;
            }
        });

        // Safe remember macro with fallback on exception
        Cache::macro('safeRemember', function (string $key, $ttl, \Closure $callback, $fallback = null) {
            try {
                return Cache::remember($key, $ttl, $callback);
            } catch (\Exception $e) {
                Log::warning('Cache remember failed', ['key' => $key, 'error' => $e->getMessage()]);
                return is_callable($fallback) ? $fallback() : $fallback;
            }
        });

        // Flush tagged cache with error handling
        Cache::macro('safeFlushTags', function (array $tags) {
            try {
                return Cache::tags($tags)->flush();
            } catch (\Exception $e) {
                Log::warning('Cache tag flush failed', ['tags' => $tags, 'error' => $e->getMessage()]);
                return false;
            }
        });
    }

    /**
     * Setup cache fallback mechanism.
     */
    protected function setupCacheFallback(): void
    {
        if (! $this->app->environment('production')) {
            return;
        }

        $cacheManager = $this->app->make(CacheManager::class);

        // Listen for cache failures
        $this->app['events']->listen('cache:miss', function ($key) {
            Log::debug('Cache miss', ['key' => $key]);
        });

        // Monitor Redis connection health
        if ($this->app['config']->get('cache.default') === 'redis') {
            $this->app->terminating(function () {
                $this->logCacheStats();
            });
        }
    }

    /**
     * Validate Redis connection configuration.
     */
    protected function validateRedisConnection(): bool
    {
        try {
            $redisConfig = $this->app['config']->get('database.redis');

            if (empty($redisConfig)) {
                Log::warning('Redis configuration is missing');
                return false;
            }

            $defaultConfig = $redisConfig['default'] ?? [];

            if (empty($defaultConfig['host'])) {
                Log::warning('Redis host is not configured');
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to validate Redis configuration', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Log cache statistics.
     */
    protected function logCacheStats(): void
    {
        try {
            $store = Cache::store();

            // For Redis store, try to get connection through the Redis manager
            if ($store instanceof \Illuminate\Cache\RedisStore) {
                $connection = $store->connection();
                $info = $connection->info();

                Log::debug('Redis cache stats', [
                    'connected_clients' => $info['Clients']['connected_clients'] ?? 'N/A',
                    'used_memory_human' => $info['Memory']['used_memory_human'] ?? 'N/A',
                    'keyspace_hits' => $info['Stats']['keyspace_hits'] ?? 'N/A',
                    'keyspace_misses' => $info['Stats']['keyspace_misses'] ?? 'N/A',
                ]);
            }
        } catch (\Exception $e) {
            // Silently fail - this is just for monitoring
        }
    }

    /**
     * Check if cache is healthy.
     */
    public static function isHealthy(): bool
    {
        try {
            $testKey = 'cache_health_check_' . uniqid();
            $testValue = 'test_' . time();

            Cache::put($testKey, $testValue, 10);
            $retrieved = Cache::get($testKey);
            Cache::forget($testKey);

            return $retrieved === $testValue;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get cache store status.
     */
    public static function getStatus(): array
    {
        $status = [
            'driver' => config('cache.default'),
            'healthy' => false,
            'store' => null,
            'prefix' => config('cache.prefix'),
            'ttl' => config('cache.ttl'),
        ];

        try {
            $store = Cache::store();
            $status['store'] = get_class($store);
            $status['healthy'] = self::isHealthy();

            if ($status['driver'] === 'redis') {
                $redisConfig = config('database.redis.default');
                $status['redis'] = [
                    'host' => $redisConfig['host'] ?? 'N/A',
                    'port' => $redisConfig['port'] ?? 'N/A',
                    'database' => $redisConfig['database'] ?? 'N/A',
                ];
            }
        } catch (\Exception $e) {
            $status['error'] = $e->getMessage();
        }

        return $status;
    }
}
