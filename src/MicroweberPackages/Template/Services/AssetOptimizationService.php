<?php

namespace MicroweberPackages\Template\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use MicroweberPackages\Utils\ThirdPartyLibs\JShrink\Minifier;
use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * Service for optimizing frontend assets.
 *
 * Handles minification, CDN integration, versioning, and caching
 * of CSS and JavaScript assets.
 */
class AssetOptimizationService
{
    /**
     * Cache key prefix for minified assets
     */
    protected const CACHE_PREFIX = 'asset_minified_';

    /**
     * Cache TTL for minified assets (7 days in seconds)
     */
    protected const CACHE_TTL = 604800;

    /**
     * @var array Configuration settings
     */
    protected array $config;

    /**
     * @var bool Whether asset optimization is enabled
     */
    protected bool $enabled;

    /**
     * @var string Base CDN URL
     */
    protected ?string $cdnUrl;

    /**
     * @var bool Whether to use CDN for assets
     */
    protected bool $useCdn;

    /**
     * @var bool Whether to minify CSS
     */
    protected bool $minifyCss;

    /**
     * @var bool Whether to minify JavaScript
     */
    protected bool $minifyJs;

    /**
     * @var bool Whether to version assets
     */
    protected bool $versionAssets;

    /**
     * @var string Path to store minified assets
     */
    protected string $minifiedPath;

    /**
     * @var string URL to access minified assets
     */
    protected string $minifiedUrl;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->config = config('assets', []);
        $this->enabled = $this->config['enabled'] ?? true;
        $this->cdnUrl = $this->config['cdn_url'] ?? null;
        $this->useCdn = $this->config['use_cdn'] ?? false;
        $this->minifyCss = $this->config['minify_css'] ?? true;
        $this->minifyJs = $this->config['minify_js'] ?? true;
        $this->versionAssets = $this->config['version_assets'] ?? true;
        $this->minifiedPath = $this->config['minified_path'] ?? storage_path('app/public/assets/minified');
        $this->minifiedUrl = $this->config['minified_url'] ?? '/storage/assets/minified';

        $this->ensureMinifiedDirectoryExists();
    }

    /**
     * Process an asset URL - applies CDN, minification, and versioning
     *
     * @param string $url Original asset URL
     * @param string $type Asset type ('css' or 'js')
     * @return string Optimized asset URL
     */
    public function processAsset(string $url, string $type): string
    {
        if (!$this->enabled) {
            return $this->applyCdnIfEnabled($url);
        }

        // Skip external URLs for minification but still apply CDN if configured
        if ($this->isExternalUrl($url)) {
            return $this->applyCdnIfEnabled($url);
        }

        // Determine if we should minify this type
        $shouldMinify = ($type === 'css' && $this->minifyCss) || ($type === 'js' && $this->minifyJs);

        if (!$shouldMinify) {
            return $this->applyCdnIfEnabled($url);
        }

        // Try to get minified version
        $minifiedUrl = $this->getMinifiedUrl($url, $type);

        return $this->applyCdnIfEnabled($minifiedUrl);
    }

    /**
     * Process multiple assets at once
     *
     * @param array $urls Array of asset URLs
     * @param string $type Asset type ('css' or 'js')
     * @return array Array of optimized URLs
     */
    public function processAssets(array $urls, string $type): array
    {
        return array_map(fn ($url) => $this->processAsset($url, $type), $urls);
    }

    /**
     * Get or create minified URL for an asset
     *
     * @param string $originalUrl Original asset URL
     * @param string $type Asset type
     * @return string Minified asset URL
     */
    protected function getMinifiedUrl(string $originalUrl, string $type): string
    {
        $localPath = $this->urlToPath($originalUrl);

        if (!File::exists($localPath)) {
            Log::warning('Asset file not found for minification', ['url' => $originalUrl, 'path' => $localPath]);
            return $originalUrl;
        }

        // Generate cache key based on file path and modification time
        $fileMtime = File::lastModified($localPath);
        $cacheKey = self::CACHE_PREFIX . md5($localPath . $fileMtime);

        // Check if minified version exists in cache
        $minifiedPath = Cache::get($cacheKey);

        if ($minifiedPath && File::exists($minifiedPath)) {
            return $this->pathToUrl($minifiedPath);
        }

        // Generate minified version
        $minifiedPath = $this->generateMinifiedFile($localPath, $type);

        if ($minifiedPath) {
            Cache::put($cacheKey, $minifiedPath, self::CACHE_TTL);
            return $this->pathToUrl($minifiedPath);
        }

        return $originalUrl;
    }

    /**
     * Generate minified version of a file
     *
     * @param string $sourcePath Source file path
     * @param string $type Asset type ('css' or 'js')
     * @return string|null Path to minified file or null on failure
     */
    protected function generateMinifiedFile(string $sourcePath, string $type): ?string
    {
        try {
            $content = File::get($sourcePath);

            if ($type === 'css') {
                $minified = $this->minifyCss($content);
            } elseif ($type === 'js') {
                $minified = $this->minifyJs($content);
            } else {
                return null;
            }

            // Generate minified filename with hash
            $hash = md5($sourcePath . File::lastModified($sourcePath));
            $filename = pathinfo($sourcePath, PATHINFO_FILENAME);
            $minifiedFilename = "{$filename}.{$hash}.min.{$type}";
            $minifiedPath = $this->minifiedPath . '/' . $minifiedFilename;

            // Ensure directory exists
            $directory = dirname($minifiedPath);
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            File::put($minifiedPath, $minified);

            return $minifiedPath;
        } catch (\Exception $e) {
            Log::error('Failed to minify asset', [
                'path' => $sourcePath,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Minify CSS content
     *
     * @param string $css CSS content
     * @return string Minified CSS
     */
    protected function minifyCss(string $css): string
    {
        // Remove CSS comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);

        // Backup values within single or double quotes
        preg_match_all('/(\'[^\']*?\'|"[^"]*?")/ims', $css, $hit, PREG_PATTERN_ORDER);
        for ($i = 0; $i < count($hit[1]); $i++) {
            $css = str_replace($hit[1][$i], '##########' . $i . '##########', $css);
        }

        // Remove trailing semicolon of selector's last property
        $css = preg_replace('/;[\s\r\n\t]*?}[\s\r\n\t]*/ims', "}\r\n", $css);

        // Remove any whitespace between semicolon and property-name
        $css = preg_replace('/;[\s\r\n\t]*?([\r\n]?[^\s\r\n\t])/ims', ';$1', $css);

        // Remove any whitespace surrounding property-colon
        $css = preg_replace('/[\s\r\n\t]*:[\s\r\n\t]*?([^\s\r\n\t])/ims', ':$1', $css);

        // Remove any whitespace surrounding selector-comma
        $css = preg_replace('/[\s\r\n\t]*,[\s\r\n\t]*?([^\s\r\n\t])/ims', ',$1', $css);

        // Remove any whitespace surrounding opening parenthesis
        $css = preg_replace('/[\s\r\n\t]*{[\s\r\n\t]*?([^\s\r\n\t])/ims', '{$1', $css);

        // Remove any whitespace between numbers and units
        $css = preg_replace('/([\d\.]+)[\s\r\n\t]+(px|em|pt|%)/ims', '$1$2', $css);

        // Shorten zero-values
        $css = preg_replace('/([^\d\.]0)(px|em|pt|%)/ims', '$1', $css);

        // Constrain multiple whitespaces
        $css = preg_replace('/\p{Zs}+/ims', ' ', $css);

        // Remove newlines
        $css = str_replace(["\r\n", "\r", "\n"], '', $css);

        // Restore backupped values within single or double quotes
        for ($i = 0; $i < count($hit[1]); $i++) {
            $css = str_replace('##########' . $i . '##########', $hit[1][$i], $css);
        }

        return trim($css);
    }

    /**
     * Minify JavaScript content
     *
     * @param string $js JavaScript content
     * @return string Minified JavaScript
     */
    protected function minifyJs(string $js): string
    {
        try {
            return Minifier::minify($js, ['flaggedComments' => false]);
        } catch (\Exception $e) {
            Log::warning('JS minification failed, returning original', ['error' => $e->getMessage()]);
            return $js;
        }
    }

    /**
     * Apply CDN URL to asset if CDN is enabled
     *
     * @param string $url Asset URL
     * @return string URL with CDN applied if configured
     */
    protected function applyCdnIfEnabled(string $url): string
    {
        if (!$this->useCdn || !$this->cdnUrl || $this->isExternalUrl($url)) {
            return $url;
        }

        // Parse the URL to get the path
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'] ?? $url;

        // Remove leading slash for concatenation
        $path = ltrim($path, '/');

        return rtrim($this->cdnUrl, '/') . '/' . $path;
    }

    /**
     * Check if URL is external
     *
     * @param string $url URL to check
     * @return bool True if external URL
     */
    protected function isExternalUrl(string $url): bool
    {
        return str_starts_with($url, 'http://') ||
               str_starts_with($url, 'https://') ||
               str_starts_with($url, '//');
    }

    /**
     * Convert public URL to local file path
     *
     * @param string $url Public URL
     * @return string Local file path
     */
    protected function urlToPath(string $url): string
    {
        // Remove query string and hash
        $url = strtok($url, '?');
        $url = strtok($url, '#');

        // Handle different URL patterns
        if (str_starts_with($url, '/')) {
            return public_path(ltrim($url, '/'));
        }

        // Handle full URLs
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['path'])) {
            $path = ltrim($parsedUrl['path'], '/');

            // Check if it's a public asset
            if (str_starts_with($path, 'vendor/')) {
                return public_path($path);
            }

            if (str_starts_with($path, 'modules/')) {
                return base_path($path);
            }

            if (str_starts_with($path, 'templates/')) {
                return base_path($path);
            }

            return public_path($path);
        }

        return $url;
    }

    /**
     * Convert local file path to public URL
     *
     * @param string $path Local file path
     * @return string Public URL
     */
    protected function pathToUrl(string $path): string
    {
        // Check if path is within minified directory
        if (str_starts_with($path, $this->minifiedPath)) {
            $relativePath = substr($path, strlen($this->minifiedPath));
            return $this->minifiedUrl . $relativePath;
        }

        // Check if path is within public directory
        $publicPath = public_path();
        if (str_starts_with($path, $publicPath)) {
            $relativePath = substr($path, strlen($publicPath));
            return '/' . ltrim($relativePath, '/');
        }

        return $path;
    }

    /**
     * Ensure minified directory exists
     *
     * @return void
     */
    protected function ensureMinifiedDirectoryExists(): void
    {
        if (!File::isDirectory($this->minifiedPath)) {
            File::makeDirectory($this->minifiedPath, 0755, true);
        }
    }

    /**
     * Clear all minified assets cache
     *
     * @return void
     */
    public function clearCache(): void
    {
        // Clear cache entries
        Cache::flush();

        // Clear minified files
        if (File::isDirectory($this->minifiedPath)) {
            File::cleanDirectory($this->minifiedPath);
        }

        Log::info('Asset optimization cache cleared');
    }

    /**
     * Get CDN URL
     *
     * @return string|null
     */
    public function getCdnUrl(): ?string
    {
        return $this->cdnUrl;
    }

    /**
     * Check if CDN is enabled
     *
     * @return bool
     */
    public function isCdnEnabled(): bool
    {
        return $this->useCdn && !empty($this->cdnUrl);
    }

    /**
     * Check if asset optimization is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Get minification statistics
     *
     * @return array Statistics about minified assets
     */
    public function getStatistics(): array
    {
        $stats = [
            'minified_path' => $this->minifiedPath,
            'minified_url' => $this->minifiedUrl,
            'cdn_enabled' => $this->isCdnEnabled(),
            'cdn_url' => $this->cdnUrl,
            'minify_css' => $this->minifyCss,
            'minify_js' => $this->minifyJs,
            'version_assets' => $this->versionAssets,
            'files_count' => 0,
            'total_size' => 0,
        ];

        if (File::isDirectory($this->minifiedPath)) {
            $files = File::allFiles($this->minifiedPath);
            $stats['files_count'] = count($files);

            foreach ($files as $file) {
                $stats['total_size'] += $file->getSize();
            }
        }

        return $stats;
    }

    /**
     * Clear minified assets for a specific file
     *
     * @param string $originalUrl Original asset URL
     * @return void
     */
    public function clearAssetCache(string $originalUrl): void
    {
        $localPath = $this->urlToPath($originalUrl);

        if (!File::exists($localPath)) {
            return;
        }

        $filename = pathinfo($localPath, PATHINFO_FILENAME);
        $extension = pathinfo($localPath, PATHINFO_EXTENSION);

        // Find and delete all minified versions of this file
        if (File::isDirectory($this->minifiedPath)) {
            $pattern = $this->minifiedPath . '/' . $filename . '.*.min.' . $extension;
            $files = glob($pattern);

            foreach ($files as $file) {
                File::delete($file);
            }
        }

        // Clear cache entries
        $fileMtime = File::lastModified($localPath);
        $cacheKey = self::CACHE_PREFIX . md5($localPath . $fileMtime);
        Cache::forget($cacheKey);
    }
}
