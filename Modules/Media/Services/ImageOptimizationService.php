<?php

namespace Modules\Media\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Modules\Media\Models\Media;

class ImageOptimizationService
{
    /**
     * @var ImageManager|null
     */
    protected $imageManager;

    /**
     * @var array
     */
    protected $config;

    /**
     * Supported image formats for conversion.
     */
    protected const SUPPORTED_FORMATS = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->config = $this->getConfig();
        
        // Initialize ImageManager only if WebP is supported
        if ($this->isWebpSupported()) {
            try {
                $driver = $this->config['driver'] ?? \Intervention\Image\Drivers\Gd\Driver::class;
                $this->imageManager = new ImageManager($driver);
            } catch (\Exception $e) {
                $this->imageManager = null;
            }
        }
    }

    /**
     * Get configuration with fallbacks.
     */
    protected function getConfig(): array
    {
        // Try to get from Laravel config, otherwise use defaults
        try {
            return config('media.optimization', $this->getDefaultConfig());
        } catch (\Exception $e) {
            return $this->getDefaultConfig();
        }
    }

    /**
     * Get default configuration.
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
            'driver' => \Intervention\Image\Drivers\Gd\Driver::class,
        ];
    }

    /**
     * Convert an image to WebP format.
     *
     * @param string $sourcePath Path to the source image
     * @param array $options Conversion options (quality, width, height, crop)
     * @return array|null Returns array with path and metadata on success, null on failure
     */
    public function convertToWebp(string $sourcePath, array $options = []): ?array
    {
        if (!$this->isWebpSupported()) {
            return null;
        }

        if (!$this->isValidImageFile($sourcePath)) {
            return null;
        }

        try {
            $fullPath = $this->resolveFullPath($sourcePath);
            
            if (!file_exists($fullPath)) {
                return null;
            }

            $image = $this->imageManager->read($fullPath);
            
            // Apply resize if dimensions provided
            if (!empty($options['width']) || !empty($options['height'])) {
                $image = $this->resizeImage($image, $options);
            }

            // Generate WebP path
            $webpPath = $this->generateWebpPath($sourcePath);
            $webpFullPath = $this->resolveFullPath($webpPath);
            
            // Ensure directory exists
            $webpDir = dirname($webpFullPath);
            if (!is_dir($webpDir)) {
                mkdir($webpDir, 0755, true);
            }

            // Get quality setting
            $quality = $options['quality'] ?? $this->config['webp_quality'] ?? 85;

            // Encode and save WebP
            $encoded = $image->encode(new \Intervention\Image\Encoders\WebpEncoder(quality: $quality));
            file_put_contents($webpFullPath, $encoded);

            // Calculate size savings
            $originalSize = filesize($fullPath);
            $webpSize = filesize($webpFullPath);
            $savings = $originalSize - $webpSize;
            $savingsPercent = $originalSize > 0 ? round(($savings / $originalSize) * 100, 2) : 0;

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

        } catch (\Exception $e) {
            \Log::error('WebP conversion failed', [
                'source' => $sourcePath,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get or create WebP version of an image.
     * Returns WebP path if conversion is successful, otherwise returns original path.
     *
     * @param string $sourcePath Path to the source image
     * @param array $options Conversion options
     * @return string Path to the image (WebP or original)
     */
    public function getWebpOrOriginal(string $sourcePath, array $options = []): string
    {
        // Check if WebP is enabled and supported
        if (!$this->isWebpEnabled() || !$this->isWebpSupported()) {
            return $sourcePath;
        }

        // Check if browser supports WebP
        if (!$this->clientSupportsWebp()) {
            return $sourcePath;
        }

        // Check if already WebP
        if ($this->isWebpFile($sourcePath)) {
            return $sourcePath;
        }

        // Generate WebP path
        $webpPath = $this->generateWebpPath($sourcePath);
        $webpFullPath = $this->resolveFullPath($webpPath);

        // Check if WebP already exists and is fresh
        $fullSourcePath = $this->resolveFullPath($sourcePath);
        if (file_exists($webpFullPath) && file_exists($fullSourcePath)) {
            if (filemtime($webpFullPath) >= filemtime($fullSourcePath)) {
                return $webpPath;
            }
        }

        // Convert to WebP
        $result = $this->convertToWebp($sourcePath, $options);
        
        return $result ? $result['path'] : $sourcePath;
    }

    /**
     * Generate lazy loading HTML attributes.
     *
     * @param string $src Image source URL
     * @param string|null $alt Alt text
     * @param array $attributes Additional HTML attributes
     * @return string HTML img tag
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

        // Add width and height if provided
        if (!empty($attributes['width'])) {
            $attrs['width'] = $attributes['width'];
        }
        if (!empty($attributes['height'])) {
            $attrs['height'] = $attributes['height'];
        }

        // Add class for lazy loading
        $existingClass = $attrs['class'] ?? '';
        $attrs['class'] = trim($existingClass . ' mw-lazy-image');

        // Build HTML attributes string
        $attrString = $this->buildAttributesString($attrs);

        return '<img ' . $attrString . ' />';
    }

    /**
     * Generate responsive image HTML with srcset and sizes.
     *
     * @param string $src Original image source
     * @param array $sizes Array of sizes [width => max_width_in_px]
     * @param string|null $alt Alt text
     * @param array $attributes Additional attributes
     * @return string HTML picture element or img tag
     */
    public function generateResponsiveImage(string $src, array $sizes, ?string $alt = null, array $attributes = []): string
    {
        if (empty($sizes)) {
            return $this->generateLazyImage($src, $alt, $attributes);
        }

        // Sort sizes by width ascending
        ksort($sizes);

        // Generate srcset
        $srcset = [];
        foreach ($sizes as $width => $maxWidth) {
            $resizedSrc = $this->getResizedImageUrl($src, $width);
            $srcset[] = $resizedSrc . ' ' . $width . 'w';
        }

        // Generate sizes attribute
        $sizesAttr = [];
        foreach ($sizes as $width => $maxWidth) {
            if (is_numeric($maxWidth)) {
                $sizesAttr[] = '(max-width: ' . $maxWidth . 'px) ' . $width . 'px';
            } else {
                $sizesAttr[] = $maxWidth . ' ' . $width . 'px';
            }
        }
        $sizesAttr[] = end($sizes) . 'px'; // Default size

        // Merge attributes
        $attrs = array_merge([
            'src' => $src,
            'srcset' => implode(', ', $srcset),
            'sizes' => implode(', ', $sizesAttr),
            'alt' => $alt ?? '',
            'loading' => 'lazy',
            'decoding' => 'async',
        ], $attributes);

        $attrString = $this->buildAttributesString($attrs);

        return '<img ' . $attrString . ' />';
    }

    /**
     * Get optimized image URL with optional WebP conversion.
     *
     * @param string $src Image source URL
     * @param int|null $width Optional width for resizing
     * @param int|null $height Optional height for resizing
     * @param bool $allowWebp Whether to allow WebP conversion
     * @return string Optimized image URL
     */
    public function getOptimizedUrl(string $src, ?int $width = null, ?int $height = null, bool $allowWebp = true): string
    {
        // First apply resize if dimensions provided
        if ($width || $height) {
            $src = $this->getResizedImageUrl($src, $width, $height);
        }

        // Then convert to WebP if enabled
        if ($allowWebp && $this->isWebpEnabled()) {
            $src = $this->getWebpOrOriginal($src);
        }

        return $src;
    }

    /**
     * Check if WebP is supported by the server.
     *
     * @return bool
     */
    public function isWebpSupported(): bool
    {
        return function_exists('imagewebp') || class_exists('Imagick');
    }

    /**
     * Check if WebP optimization is enabled.
     *
     * @return bool
     */
    public function isWebpEnabled(): bool
    {
        return $this->config['webp_enabled'] ?? true;
    }

    /**
     * Check if lazy loading is enabled.
     *
     * @return bool
     */
    public function isLazyLoadingEnabled(): bool
    {
        return $this->config['lazy_loading_enabled'] ?? true;
    }

    /**
     * Check if client browser supports WebP.
     *
     * @return bool
     */
    public function clientSupportsWebp(): bool
    {
        $acceptHeader = request()->header('Accept', '');
        return str_contains($acceptHeader, 'image/webp');
    }

    /**
     * Clear all generated WebP images from cache.
     *
     * @return int Number of files deleted
     */
    public function clearWebpCache(): int
    {
        $cachePath = storage_path('app/public/cache/webp');
        if (!is_dir($cachePath)) {
            return 0;
        }

        $count = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($cachePath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'webp') {
                unlink($file->getPathname());
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get optimization statistics.
     *
     * @return array Statistics about WebP cache
     */
    public function getStatistics(): array
    {
        $cachePath = storage_path('app/public/cache/webp');
        
        if (!is_dir($cachePath)) {
            return [
                'total_files' => 0,
                'total_size' => 0,
                'total_size_human' => $this->humanFileSize(0),
                'enabled' => $this->isWebpEnabled(),
                'supported' => $this->isWebpSupported(),
            ];
        }

        $totalSize = 0;
        $fileCount = 0;

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($cachePath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

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
        ];
    }

    /**
     * Check if file is a valid image.
     *
     * @param string $path
     * @return bool
     */
    protected function isValidImageFile(string $path): bool
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($extension, self::SUPPORTED_FORMATS);
    }

    /**
     * Check if file is already WebP.
     *
     * @param string $path
     * @return bool
     */
    protected function isWebpFile(string $path): bool
    {
        return strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'webp';
    }

    /**
     * Generate WebP file path.
     *
     * @param string $sourcePath
     * @return string
     */
    protected function generateWebpPath(string $sourcePath): string
    {
        $directory = dirname($sourcePath);
        $filename = pathinfo($sourcePath, PATHINFO_FILENAME);
        
        // Store in cache/webp directory
        return 'cache/webp/' . md5($sourcePath) . '/' . $filename . '.webp';
    }

    /**
     * Resize image based on options.
     *
     * @param mixed $image Intervention Image instance
     * @param array $options
     * @return mixed
     */
    protected function resizeImage($image, array $options)
    {
        $width = $options['width'] ?? null;
        $height = $options['height'] ?? null;
        $crop = $options['crop'] ?? false;

        if ($width && $height) {
            if ($crop) {
                return $image->cover($width, $height);
            } else {
                return $image->scaleDown($width, $height);
            }
        } elseif ($width) {
            return $image->scaleDown(width: $width);
        } elseif ($height) {
            return $image->scaleDown(height: $height);
        }

        return $image;
    }

    /**
     * Get resized image URL using existing thumbnail functionality.
     *
     * @param string $src
     * @param int|null $width
     * @param int|null $height
     * @return string
     */
    protected function getResizedImageUrl(string $src, ?int $width = null, ?int $height = null): string
    {
        // Use existing thumbnail function if available
        if (function_exists('thumbnail')) {
            return thumbnail($src, $width, $height);
        }

        return $src;
    }

    /**
     * Resolve full filesystem path from relative path.
     *
     * @param string $path
     * @return string
     */
    protected function resolveFullPath(string $path): string
    {
        // If path starts with http, extract relative path
        if (str_starts_with($path, 'http')) {
            $path = parse_url($path, PHP_URL_PATH);
        }

        // Check if already absolute
        if (file_exists($path)) {
            return $path;
        }

        // Try public path
        $publicPath = public_path($path);
        if (file_exists($publicPath)) {
            return $publicPath;
        }

        // Try storage path
        $storagePath = storage_path('app/public/' . ltrim($path, '/'));
        if (file_exists($storagePath)) {
            return $storagePath;
        }

        return $path;
    }

    /**
     * Get URL from path.
     *
     * @param string $path
     * @return string
     */
    protected function getUrlFromPath(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    /**
     * Get placeholder URL for lazy loading.
     *
     * @return string
     */
    protected function getPlaceholderUrl(): string
    {
        return $this->config['placeholder_url'] ?? 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E';
    }

    /**
     * Build HTML attributes string.
     *
     * @param array $attributes
     * @return string
     */
    protected function buildAttributesString(array $attributes): string
    {
        $attrs = [];
        foreach ($attributes as $key => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $attrs[] = $key;
                }
            } elseif ($value !== null && $value !== '') {
                $attrs[] = $key . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
            }
        }
        return implode(' ', $attrs);
    }

    /**
     * Convert bytes to human-readable format.
     *
     * @param int $bytes
     * @return string
     */
    protected function humanFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $unitIndex = 0;

        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }

        return round($bytes, 2) . ' ' . $units[$unitIndex];
    }
}
