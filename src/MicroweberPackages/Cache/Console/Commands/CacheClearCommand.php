<?php

declare(strict_types=1);

namespace MicroweberPackages\Cache\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\Cache\Services\PageCacheService;
use MicroweberPackages\Cache\Services\FragmentCacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

/**
 * Cache Clear Command
 * 
 * Clears page and fragment caches selectively or completely.
 * 
 * Usage:
 *   php artisan cache:clear-page
 *   php artisan cache:clear-page --tag=content
 *   php artisan cache:clear-page --type=fragment
 *   php artisan cache:clear-page --all
 * 
 * @package MicroweberPackages\Cache\Console\Commands
 */
class CacheClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-page
                            {--tag= : Clear cache by specific tag}
                            {--type=page : Cache type (page, fragment, all)}
                            {--all : Clear all page and fragment caches}
                            {--keep-views : Keep compiled view cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear page and fragment caches';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting cache clearing process...');

        $success = true;

        // Clear page cache
        if ($this->shouldClear('page')) {
            $this->clearPageCache();
        }

        // Clear fragment cache
        if ($this->shouldClear('fragment')) {
            $this->clearFragmentCache();
        }

        // Clear all caches
        if ($this->option('all')) {
            $this->clearAllCaches();
        }

        $this->info('Cache clearing completed!');

        return $success ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Check if a cache type should be cleared
     */
    protected function shouldClear(string $type): bool
    {
        $requestedType = $this->option('type');
        
        return $requestedType === $type || $requestedType === 'all' || $this->option('all');
    }

    /**
     * Clear page cache
     */
    protected function clearPageCache(): void
    {
        $this->info('Clearing page cache...');

        try {
            $tag = $this->option('tag');
            
            if ($tag) {
                Cache::tags([$tag])->flush();
                $this->info("Cleared cache with tag: {$tag}");
            } else {
                Cache::tags(['page'])->flush();
                $this->info('Cleared all page caches');
            }
        } catch (\Exception $e) {
            $this->error('Failed to clear page cache: ' . $e->getMessage());
        }
    }

    /**
     * Clear fragment cache
     */
    protected function clearFragmentCache(): void
    {
        $this->info('Clearing fragment cache...');

        try {
            $tag = $this->option('tag');
            
            if ($tag) {
                Cache::tags(['fragment', $tag])->flush();
                $this->info("Cleared fragment cache with tag: {$tag}");
            } else {
                Cache::tags(['fragment'])->flush();
                $this->info('Cleared all fragment caches');
            }
        } catch (\Exception $e) {
            $this->error('Failed to clear fragment cache: ' . $e->getMessage());
        }
    }

    /**
     * Clear all caches including Laravel's
     */
    protected function clearAllCaches(): void
    {
        $this->info('Clearing all caches...');

        // Clear page and fragment caches
        $this->clearPageCache();
        $this->clearFragmentCache();

        // Clear Laravel caches
        Artisan::call('cache:clear');
        $this->info('Cleared application cache');

        if (!$this->option('keep-views')) {
            Artisan::call('view:clear');
            $this->info('Cleared compiled views');
        }

        Artisan::call('config:clear');
        $this->info('Cleared configuration cache');

        Artisan::call('route:clear');
        $this->info('Cleared route cache');
    }
}
