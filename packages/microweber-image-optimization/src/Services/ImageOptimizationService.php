<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Services;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Throwable;

/**
 * Standalone image optimization service: WebP conversion, lazy/responsive HTML,
 * and WebP cache management. Framework-agnostic beyond Laravel helpers.
 */
class ImageOptimizationService
{
    protected ?ImageManager $imageManager = null;

    /** @var array<string, mixed> */
    protected array $config;

    /**
     * @param  array<string, mixed>|null  $config  Optional config override (uses package config when null)
     * @param  ImageManager|null  $imageManager  Optional pre-built manager (for testing)
     * @param  callable|null  $resizeResolver  Optional (string $src, ?int $w, ?int $h): string
     */
    public function __construct(
        ?array $config = null,
        ?ImageManager $imageManager = null,
        protected mixed $resizeResolver = null,
    ) {
        $this->config = $config ?? $this->loadConfig();

        if ($imageManager !== null) {
            $this->imageManager = $imageManager;
        } elseif ($this->isWebpSupported()) {
            $this->imageManager = $this->createImageManager();
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function loadConfig(): array
    {
        try {
            /** @var array<string, mixed>|null $package */
            $package = config('image-optimization');
            if (is_array($package) && $package !== []) {
                return array_merge($this->getDefaultConfig(), $package);
            }

            // Backwards compatibility with CMS media.optimization key
            /** @var array<string, mixed>|null $legacy */
            $legacy = config('media.optimization');
            if (is_array($legacy) && $legacy !== []) {
                return array_merge($this->getDefaultConfig(), $legacy);
            }
        } catch (Throwable) {
            // fall through to defaults
        }

        return $this->getDefaultConfig();
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaultConfig(): array
    {
        return [
            'webp_enabled' => true,
            'webp_quality' => 85,
            'lazy_loading_enabled' => true,
            'placeholder_url' => 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E',
            'webp_cache' => true,
            'webp_cache_ttl' => 604800,
            'cache_path' => 'cache/webp',
            'disk' => 'public',
            'driver' => \Intervention\Image\Drivers\Gd\Driver::class,
            'supported_formats' => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'],
        ];
    }

    protected function createImageManager(): ?ImageManager
    {
        try {
            $driver = $this->config['driver'] ?? \Intervention\Image\Drivers\Gd\Driver::class;
            if (!is_string($driver) && !($driver instanceof \Intervention\Image\Interfaces\DriverInterface)) {
                $driver = \Intervention\Image\Drivers\Gd\Driver::class;
            }

            /** @var class-string<\Intervention\Image\Interfaces\DriverInterface>|\Intervention\Image\Interfaces\DriverInterface $driver */
            return new ImageManager($driver);
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Convert an image to WebP format.
     *
     * @param  array<string, mixed>  $options  quality, width, height, crop
     * @return array<string, mixed>|null
     */
    public function convertToWebp(string $sourcePath, array $options = []): ?array
    {
        if (!$this->isWebpSupported() || $this->imageManager === null) {
            return null;
        }

        if (!$this->isValidImageFile($sourcePath)) {
            return null;
        }

        try {
            $fullPath = $this->resolveFullPath($sourcePath);

            if (!is_file($fullPath)) {
                return null;
            }

            $image = $this->imageManager->read($fullPath);

            if (!empty($options['width']) || !empty($options['height'])) {
                $image = $this->resizeImage($image, $options);
            }

            $webpPath = $this->generateWebpPath($sourcePath);
            $webpFullPath = $this->resolveWritePath($webpPath);

            $webpDir = dirname($webpFullPath);
            if (!is_dir($webpDir)) {
                mkdir($webpDir, 0755, true);
            }

            $quality = (int) ($options['quality'] ?? $this->config['webp_quality'] ?? 85);

            $encoded = $image->encode(new \Intervention\Image\Encoders\WebpEncoder(quality: $quality));
            file_put_contents($webpFullPath, (string) $encoded);

            $originalSize = (int) filesize($fullPath);
            $webpSize = (int) filesize($webpFullPath);
            $savings = $originalSize - $webpSize;
            $savingsPercent = $originalSize > 0 ? round(($savings / $originalSize) * 100, 2) : 0.0;

            return [
                'path' => $webpPath,
                'full_path' => $webpFullPath,
                'url' => $this->getUrlFromPath($webpPath),
                'original_size' => $originalSize,
                'webp_size' => $webpSize,
                'savings' => $savings,
                'savings_percent' => $savingsPercent,
                'quality' => $quality,
                'width' => $image->width(),
                'height' => $image->height(),
            ];
        } catch (Throwable $e) {
            Log::error('WebP conversion failed', [
                'source' => $sourcePath,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Get or create WebP version of an image.
     *
     * @param  array<string, mixed>  $options
     */
    public function getWebpOrOriginal(string $sourcePath, array $options = []): string
    {
        if (!$this->isWebpEnabled() || !$this->isWebpSupported()) {
            return $sourcePath;
        }

        if (!$this->clientSupportsWebp()) {
            return $sourcePath;
        }

        if ($this->isWebpFile($sourcePath)) {
            return $sourcePath;
        }

        if (!$this->isValidImageFile($sourcePath)) {
            return $sourcePath;
        }

        $webpPath = $this->generateWebpPath($sourcePath);
        $webpFullPath = $this->resolveWritePath($webpPath);
        $fullSourcePath = $this->resolveFullPath($sourcePath);

        if (is_file($webpFullPath) && is_file($fullSourcePath)) {
            if (filemtime($webpFullPath) >= filemtime($fullSourcePath)) {
                return $webpPath;
            }
        }

        $result = $this->convertToWebp($sourcePath, $options);

        return $result !== null ? (string) ($result['path'] ?? $sourcePath) : $sourcePath;
    }

    /**
     * Generate lazy loading HTML img tag.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function generateLazyImage(string $src, ?string $alt = null, array $attributes = []): string
    {
        $attrs = array_merge([
            'src' => $this->getPlaceholderUrl(),
            'data-src' => $src,
            'alt' => $alt ?? '',
            'loading' => 'lazy',
            'decoding' => 'async',
        ], $attributes);

        if (!empty($attributes['width'])) {
            $attrs['width'] = $attributes['width'];
        }
        if (!empty($attributes['height'])) {
            $attrs['height'] = $attributes['height'];
        }

        $existingClass = isset($attrs['class']) ? (string) $attrs['class'] : '';
        $attrs['class'] = trim($existingClass . ' mw-lazy-image');

        return '<img ' . $this->buildAttributesString($attrs) . ' />';
    }

    /**
     * Generate responsive image HTML with srcset and sizes.
     *
     * @param  array<int|string, int|string>  $sizes  [width => max_width_in_px or media expression]
     * @param  array<string, mixed>  $attributes
     */
    public function generateResponsiveImage(string $src, array $sizes, ?string $alt = null, array $attributes = []): string
    {
        if ($sizes === []) {
            return $this->generateLazyImage($src, $alt, $attributes);
        }

        ksort($sizes);

        $srcset = [];
        foreach ($sizes as $width => $maxWidth) {
            $widthInt = (int) $width;
            $resizedSrc = $this->getResizedImageUrl($src, $widthInt);
            $srcset[] = $resizedSrc . ' ' . $widthInt . 'w';
        }

        $sizesAttr = [];
        foreach ($sizes as $width => $maxWidth) {
            if (is_numeric($maxWidth)) {
                $sizesAttr[] = '(max-width: ' . $maxWidth . 'px) ' . $width . 'px';
            } else {
                $sizesAttr[] = $maxWidth . ' ' . $width . 'px';
            }
        }

        $lastSize = end($sizes);
        $sizesAttr[] = (is_numeric($lastSize) ? $lastSize : (string) $lastSize) . 'px';

        $attrs = array_merge([
            'src' => $src,
            'srcset' => implode(', ', $srcset),
            'sizes' => implode(', ', $sizesAttr),
            'alt' => $alt ?? '',
            'loading' => 'lazy',
            'decoding' => 'async',
        ], $attributes);

        return '<img ' . $this->buildAttributesString($attrs) . ' />';
    }

    /**
     * Get optimized image URL with optional WebP conversion and resize.
     */
    public function getOptimizedUrl(string $src, ?int $width = null, ?int $height = null, bool $allowWebp = true): string
    {
        if ($width || $height) {
            $src = $this->getResizedImageUrl($src, $width, $height);
        }

        if ($allowWebp && $this->isWebpEnabled()) {
            $src = $this->getWebpOrOriginal($src);
        }

        return $src;
    }

    public function isWebpSupported(): bool
    {
        return function_exists('imagewebp') || class_exists(\Imagick::class);
    }

    public function isWebpEnabled(): bool
    {
        return (bool) ($this->config['webp_enabled'] ?? true);
    }

    public function isLazyLoadingEnabled(): bool
    {
        return (bool) ($this->config['lazy_loading_enabled'] ?? true);
    }

    public function clientSupportsWebp(): bool
    {
        try {
            $acceptHeader = (string) request()->header('Accept', '');

            return str_contains($acceptHeader, 'image/webp');
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Clear all generated WebP images from cache.
     */
    public function clearWebpCache(): int
    {
        $cachePath = $this->getCacheFullPath();
        if (!is_dir($cachePath)) {
            return 0;
        }

        $count = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($cachePath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($file->isFile() && strtolower($file->getExtension()) === 'webp') {
                @unlink($file->getPathname());
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get optimization statistics about the WebP cache.
     *
     * @return array{
     *     total_files: int,
     *     total_size: int,
     *     total_size_human: string,
     *     enabled: bool,
     *     supported: bool,
     *     cache_path: string
     * }
     */
    public function getStatistics(): array
    {
        $cachePath = $this->getCacheFullPath();

        if (!is_dir($cachePath)) {
            return [
                'total_files' => 0,
                'total_size' => 0,
                'total_size_human' => $this->humanFileSize(0),
                'enabled' => $this->isWebpEnabled(),
                'supported' => $this->isWebpSupported(),
                'cache_path' => $cachePath,
            ];
        }

        $totalSize = 0;
        $fileCount = 0;

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($cachePath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $totalSize += $file->getSize();
                $fileCount++;
            }
        }

        return [
            'total_files' => $fileCount,
            'total_size' => $totalSize,
            'total_size_human' => $this->humanFileSize($totalSize),
            'enabled' => $this->isWebpEnabled(),
            'supported' => $this->isWebpSupported(),
            'cache_path' => $cachePath,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    public function getCacheFullPath(): string
    {
        $relative = ltrim((string) ($this->config['cache_path'] ?? 'cache/webp'), '/');

        return storage_path('app/public/' . $relative);
    }

    protected function isValidImageFile(string $path): bool
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        /** @var list<string> $formats */
        $formats = $this->config['supported_formats'] ?? ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'];

        return in_array($extension, $formats, true);
    }

    protected function isWebpFile(string $path): bool
    {
        return strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'webp';
    }

    protected function generateWebpPath(string $sourcePath): string
    {
        $filename = pathinfo($sourcePath, PATHINFO_FILENAME);
        $cacheRelative = ltrim((string) ($this->config['cache_path'] ?? 'cache/webp'), '/');

        return $cacheRelative . '/' . md5($sourcePath) . '/' . $filename . '.webp';
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function resizeImage(ImageInterface $image, array $options): ImageInterface
    {
        $width = isset($options['width']) ? (int) $options['width'] : null;
        $height = isset($options['height']) ? (int) $options['height'] : null;
        $crop = (bool) ($options['crop'] ?? false);

        if ($width && $height) {
            if ($crop) {
                return $image->cover($width, $height);
            }

            return $image->scaleDown($width, $height);
        }

        if ($width) {
            return $image->scaleDown(width: $width);
        }

        if ($height) {
            return $image->scaleDown(height: $height);
        }

        return $image;
    }

    /**
     * Get resized image URL via optional resolver, host helpers, or original.
     */
    protected function getResizedImageUrl(string $src, ?int $width = null, ?int $height = null): string
    {
        if (is_callable($this->resizeResolver)) {
            /** @var string $resolved */
            $resolved = ($this->resizeResolver)($src, $width, $height);

            return $resolved;
        }

        // CMS helper
        if (function_exists('thumbnail')) {
            return (string) (thumbnail($src, $width, $height) ?? $src);
        }

        // Standalone thumbnailer package helper
        if (function_exists('thumbnailer_generate') && is_file($src)) {
            $path = thumbnailer_generate($src, $width ?? 200, $height);
            if (is_string($path) && $path !== '') {
                return $path;
            }
        }

        return $src;
    }

    public function resolveFullPath(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $parsed = parse_url($path, PHP_URL_PATH);
            $path = is_string($parsed) ? $parsed : $path;
        }

        if (is_file($path)) {
            return $path;
        }

        $publicPath = public_path(ltrim($path, '/'));
        if (is_file($publicPath)) {
            return $publicPath;
        }

        $storagePath = storage_path('app/public/' . ltrim($path, '/'));
        if (is_file($storagePath)) {
            return $storagePath;
        }

        return $path;
    }

    /**
     * Resolve a relative storage path for writing (always under the public disk root).
     */
    public function resolveWritePath(string $relativePath): string
    {
        // Absolute paths that already exist (or absolute destinations) are kept as-is
        if (str_starts_with($relativePath, DIRECTORY_SEPARATOR) || preg_match('#^[A-Za-z]:[\\\\/]#', $relativePath) === 1) {
            return $relativePath;
        }

        return storage_path('app/public/' . ltrim($relativePath, '/'));
    }

    protected function getUrlFromPath(string $path): string
    {
        try {
            $diskName = (string) ($this->config['disk'] ?? 'public');
            /** @var Filesystem $disk */
            $disk = Storage::disk($diskName);

            // url() exists on public/local disks
            if (method_exists($disk, 'url')) {
                return (string) $disk->url($path);
            }
        } catch (Throwable) {
            // fall through
        }

        return '/storage/' . ltrim($path, '/');
    }

    protected function getPlaceholderUrl(): string
    {
        return (string) ($this->config['placeholder_url'] ?? 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E');
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    protected function buildAttributesString(array $attributes): string
    {
        $attrs = [];
        foreach ($attributes as $key => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $attrs[] = (string) $key;
                }
            } elseif ($value !== null && $value !== '') {
                $attrs[] = $key . '="' . htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') . '"';
            }
        }

        return implode(' ', $attrs);
    }

    protected function humanFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $unitIndex = 0;
        $size = (float) $bytes;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }
}
