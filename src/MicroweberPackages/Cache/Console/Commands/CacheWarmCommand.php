<?php

declare(strict_types=1);

namespace MicroweberPackages\Cache\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\Cache\Services\PageCacheService;
use Illuminate\Support\Facades\Log;

/**
 * Cache Warm Command
 * 
 * Warms the page cache by pre-fetching specified URLs.
 * Can be scheduled to run periodically to keep cache fresh.
 * 
 * Usage:
 *   php artisan cache:warm --urls=/,/about,/products
 *   php artisan cache:warm --sitemap
 *   php artisan cache:warm --all
 * 
 * @package MicroweberPackages\Cache\Console\Commands
 */
class CacheWarmCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warm
                            {--urls= : Comma-separated list of URLs to warm}
                            {--sitemap : Warm all URLs from sitemap}
                            {--all : Warm all public pages}
                            {--chunk=10 : Number of URLs to process at once}
                            {--timeout=30 : Request timeout in seconds}
                            {--concurrent : Use concurrent requests}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm the page cache by pre-fetching URLs';

    /**
     * Execute the console command.
     */
    public function handle(PageCacheService $cacheService): int
    {
        $urls = $this->getUrls();
        
        if (empty($urls)) {
            $this->warn('No URLs to warm. Use --urls, --sitemap, or --all options.');
            return self::SUCCESS;
        }

        $this->info('Starting cache warming...');
        $this->info('URLs to warm: ' . count($urls));

        $chunkSize = (int) $this->option('chunk');
        $timeout = (int) $this->option('timeout');
        $concurrent = $this->option('concurrent');

        $results = [
            'success' => [],
            'failed' => [],
        ];

        $chunks = array_chunk($urls, $chunkSize);
        $totalChunks = count($chunks);

        foreach ($chunks as $index => $chunk) {
            $this->info("Processing chunk " . ($index + 1) . "/{$totalChunks}...");
            
            if ($concurrent && function_exists('curl_multi_init')) {
                $chunkResults = $this->warmConcurrently($chunk, $timeout);
            } else {
                $chunkResults = $this->warmSequentially($chunk, $timeout);
            }

            $results['success'] = array_merge($results['success'], $chunkResults['success']);
            $results['failed'] = array_merge($results['failed'], $chunkResults['failed']);
        }

        $this->displayResults($results);
        $this->logResults($results);

        return count($results['failed']) > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Get URLs to warm based on command options
     */
    protected function getUrls(): array
    {
        $urls = [];

        // Direct URL list
        if ($this->option('urls')) {
            $urls = array_merge($urls, explode(',', $this->option('urls')));
        }

        // From sitemap
        if ($this->option('sitemap')) {
            $urls = array_merge($urls, $this->getUrlsFromSitemap());
        }

        // All public pages
        if ($this->option('all')) {
            $urls = array_merge($urls, $this->getAllPublicUrls());
        }

        // Normalize URLs
        $urls = array_map(function ($url) {
            return $this->normalizeUrl(trim($url));
        }, $urls);

        // Remove duplicates and empty values
        return array_values(array_unique(array_filter($urls)));
    }

    /**
     * Get URLs from sitemap
     */
    protected function getUrlsFromSitemap(): array
    {
        $urls = [];
        $sitemapPath = public_path('sitemap.xml');

        if (!file_exists($sitemapPath)) {
            $this->warn('Sitemap file not found: ' . $sitemapPath);
            return $urls;
        }

        try {
            $xml = simplexml_load_file($sitemapPath);
            
            if ($xml === false) {
                $this->error('Failed to parse sitemap XML');
                return $urls;
            }

            foreach ($xml->url as $url) {
                $urls[] = (string) $url->loc;
            }
        } catch (\Exception $e) {
            $this->error('Error reading sitemap: ' . $e->getMessage());
        }

        return $urls;
    }

    /**
     * Get all public URLs from content
     */
    protected function getAllPublicUrls(): array
    {
        $urls = [];
        $baseUrl = config('app.url');

        try {
            // Get all published pages
            $pages = get_content([
                'content_type' => 'page',
                'subtype' => 'static',
                'is_active' => 1,
                'limit' => 1000,
            ]);

            foreach ($pages as $page) {
                if (!empty($page['url'])) {
                    $urls[] = $baseUrl . '/' . ltrim($page['url'], '/');
                }
            }

            // Get all published posts
            $posts = get_content([
                'content_type' => 'post',
                'is_active' => 1,
                'limit' => 1000,
            ]);

            foreach ($posts as $post) {
                if (!empty($post['url'])) {
                    $urls[] = $baseUrl . '/' . ltrim($post['url'], '/');
                }
            }
        } catch (\Exception $e) {
            $this->error('Error fetching content URLs: ' . $e->getMessage());
        }

        return $urls;
    }

    /**
     * Normalize URL
     */
    protected function normalizeUrl(string $url): string
    {
        // If URL doesn't have scheme, prepend base URL
        if (!preg_match('/^https?:\/\//', $url)) {
            $url = config('app.url') . '/' . ltrim($url, '/');
        }

        // Remove trailing slash for consistency
        $url = rtrim($url, '/');

        return $url;
    }

    /**
     * Warm cache sequentially
     */
    protected function warmSequentially(array $urls, int $timeout): array
    {
        $results = [
            'success' => [],
            'failed' => [],
        ];

        foreach ($urls as $url) {
            $progress = $this->output->createProgressBar(count($urls));
            $progress->start();

            if ($this->warmUrl($url, $timeout)) {
                $results['success'][] = $url;
            } else {
                $results['failed'][] = [
                    'url' => $url,
                    'error' => 'HTTP error',
                ];
            }

            $progress->advance();
            
            // Small delay to prevent overwhelming the server
            usleep(100000); // 100ms
        }

        $progress->finish();
        $this->output->newLine();

        return $results;
    }

    /**
     * Warm cache concurrently using curl_multi
     */
    protected function warmConcurrently(array $urls, int $timeout): array
    {
        $results = [
            'success' => [],
            'failed' => [],
        ];

        $mh = curl_multi_init();
        $curlHandles = [];

        foreach ($urls as $i => $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_USERAGENT, 'MicroweberCacheWarmer/1.0');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            
            curl_multi_add_handle($mh, $ch);
            $curlHandles[$i] = [
                'handle' => $ch,
                'url' => $url,
            ];
        }

        $running = null;
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);

        foreach ($curlHandles as $i => $info) {
            $ch = $info['handle'];
            $url = $info['url'];
            
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            
            if ($error) {
                $results['failed'][] = [
                    'url' => $url,
                    'error' => $error,
                ];
            } elseif ($httpCode === 200) {
                $results['success'][] = $url;
            } else {
                $results['failed'][] = [
                    'url' => $url,
                    'error' => "HTTP {$httpCode}",
                ];
            }
            
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }

        curl_multi_close($mh);

        return $results;
    }

    /**
     * Warm a single URL
     */
    protected function warmUrl(string $url, int $timeout): bool
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_USERAGENT, 'MicroweberCacheWarmer/1.0');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $httpCode === 200;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Display results
     */
    protected function displayResults(array $results): void
    {
        $this->output->newLine();
        
        $successCount = count($results['success']);
        $failedCount = count($results['failed']);
        
        $this->info("Cache warming completed!");
        $this->info("  Success: {$successCount}");
        $this->warn("  Failed: {$failedCount}");

        if ($failedCount > 0) {
            $this->output->newLine();
            $this->error('Failed URLs:');
            foreach ($results['failed'] as $failed) {
                $this->error("  - {$failed['url']}: {$failed['error']}");
            }
        }
    }

    /**
     * Log results
     */
    protected function logResults(array $results): void
    {
        Log::info('Cache warming completed', [
            'success_count' => count($results['success']),
            'failed_count' => count($results['failed']),
            'failed_urls' => array_map(fn($f) => $f['url'], $results['failed']),
        ]);
    }
}
