<?php

declare(strict_types=1);

namespace MicroweberPackages\Cache\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use MicroweberPackages\Cache\Services\PageCacheService;
use MicroweberPackages\Cache\Services\FragmentCacheService;

/**
 * Cache Stats Command
 * 
 * Displays cache statistics and health information.
 * 
 * Usage:
 *   php artisan cache:stats
 *   php artisan cache:stats --type=page
 *   php artisan cache:stats --type=fragment
 * 
 * @package MicroweberPackages\Cache\Console\Commands
 */
class CacheStatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:stats
                            {--type= : Show stats for specific type (page, fragment, all)}
                            {--detailed : Show detailed statistics}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display cache statistics and health information';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->displayGeneralInfo();
        
        $type = $this->option('type') ?? 'all';
        
        if (in_array($type, ['page', 'all'], true)) {
            $this->displayPageCacheStats();
        }
        
        if (in_array($type, ['fragment', 'all'], true)) {
            $this->displayFragmentCacheStats();
        }
        
        if ($this->option('detailed')) {
            $this->displayDetailedStats();
        }

        return self::SUCCESS;
    }

    /**
     * Display general cache information
     */
    protected function displayGeneralInfo(): void
    {
        $this->info('Cache System Information');
        $this->info(str_repeat('=', 50));
        
        $driver = config('cache.default');
        $store = get_class(Cache::store());
        $prefix = config('cache.prefix');
        
        $this->table(
            ['Setting', 'Value'],
            [
                ['Default Driver', $driver],
                ['Cache Store', $store],
                ['Key Prefix', $prefix],
                ['TTL', config('cache.ttl', 3600) . ' seconds'],
            ]
        );
        
        $this->newLine();
    }

    /**
     * Display page cache statistics
     */
    protected function displayPageCacheStats(): void
    {
        $this->info('Page Cache Statistics');
        $this->info(str_repeat('-', 50));
        
        $service = app(PageCacheService::class);
        $stats = $service->getStats();
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Enabled', $stats['enabled'] ? 'Yes' : 'No'],
                ['Driver', $stats['driver']],
                ['TTL', $stats['ttl'] . ' seconds'],
                ['Cache Hits', $stats['hits']],
                ['Cache Misses', $stats['misses']],
                ['Writes', $stats['writes']],
                ['Deletes', $stats['deletes']],
            ]
        );
        
        if (!empty($stats['excluded_patterns'])) {
            $this->info('Excluded URL Patterns:');
            foreach ($stats['excluded_patterns'] as $pattern) {
                $this->line("  - {$pattern}");
            }
        }
        
        $this->newLine();
    }

    /**
     * Display fragment cache statistics
     */
    protected function displayFragmentCacheStats(): void
    {
        $this->info('Fragment Cache Statistics');
        $this->info(str_repeat('-', 50));
        
        $service = app(FragmentCacheService::class);
        $stats = $service->getStats();
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Enabled', $stats['enabled'] ? 'Yes' : 'No'],
                ['Driver', $stats['driver']],
                ['Default TTL', $stats['ttl'] . ' seconds'],
                ['Cache Hits', $stats['hits']],
                ['Cache Misses', $stats['misses']],
                ['Writes', $stats['writes']],
                ['Deletes', $stats['deletes']],
                ['Active Keys', $stats['active_keys']],
            ]
        );
        
        if (!empty($stats['active_keys'])) {
            $this->info('Active Cache Keys:');
            foreach ($stats['active_keys'] as $key) {
                $this->line("  - {$key}");
            }
        }
        
        $this->newLine();
    }

    /**
     * Display detailed cache statistics
     */
    protected function displayDetailedStats(): void
    {
        $this->info('Detailed Cache Information');
        $this->info(str_repeat('-', 50));
        
        // Check cache health
        $healthy = $this->checkCacheHealth();
        
        $this->table(
            ['Check', 'Status'],
            [
                ['Cache Connection', $healthy ? 'Healthy' : 'Unhealthy'],
                ['Tag Support', $this->checkTagSupport() ? 'Yes' : 'No'],
                ['Lock Support', $this->checkLockSupport() ? 'Yes' : 'No'],
            ]
        );
        
        // Show Redis info if using Redis
        if (config('cache.default') === 'redis') {
            $this->displayRedisInfo();
        }
        
        $this->newLine();
    }

    /**
     * Check cache health
     */
    protected function checkCacheHealth(): bool
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
     * Check if cache driver supports tagging
     */
    protected function checkTagSupport(): bool
    {
        $driver = config('cache.default');
        return in_array($driver, ['redis', 'memcached', 'array'], true);
    }

    /**
     * Check if cache driver supports locking
     */
    protected function checkLockSupport(): bool
    {
        try {
            return Cache::store()->getStore() instanceof \Illuminate\Contracts\Cache\LockProvider;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Display Redis-specific information
     */
    protected function displayRedisInfo(): void
    {
        $this->info('Redis Cache Information');
        $this->info(str_repeat('-', 50));
        
        try {
            $store = Cache::store();
            
            if ($store instanceof \Illuminate\Cache\RedisStore) {
                $connection = $store->connection();
                $info = $connection->info();
                
                if (isset($info['Server'])) {
                    $this->table(
                        ['Redis Info', 'Value'],
                        [
                            ['Version', $info['Server']['redis_version'] ?? 'N/A'],
                            ['Mode', $info['Server']['redis_mode'] ?? 'N/A'],
                        ]
                    );
                }
                
                if (isset($info['Memory'])) {
                    $this->table(
                        ['Memory', 'Value'],
                        [
                            ['Used Memory', $info['Memory']['used_memory_human'] ?? 'N/A'],
                            ['Peak Memory', $info['Memory']['used_memory_peak_human'] ?? 'N/A'],
                        ]
                    );
                }
                
                if (isset($info['Stats'])) {
                    $this->table(
                        ['Statistics', 'Value'],
                        [
                            ['Keyspace Hits', $info['Stats']['keyspace_hits'] ?? 'N/A'],
                            ['Keyspace Misses', $info['Stats']['keyspace_misses'] ?? 'N/A'],
                            ['Total Commands', $info['Stats']['total_commands_processed'] ?? 'N/A'],
                        ]
                    );
                }
                
                if (isset($info['Keyspace'])) {
                    $this->info('Keyspace Databases:');
                    foreach ($info['Keyspace'] as $db => $data) {
                        $this->line("  {$db}: {$data['keys']} keys, {$data['expires']} expires");
                    }
                }
            }
        } catch (\Exception $e) {
            $this->warn('Failed to retrieve Redis info: ' . $e->getMessage());
        }
    }
}
