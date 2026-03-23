<?php

use Modules\Media\Services\ImageOptimizationService;

if (!function_exists('optimized_image_url')) {
    /**
     * Get optimized image URL with optional WebP conversion.
     *
     * @param string $src Image source URL
     * @param int|null $width Optional width for resizing
     * @param int|null $height Optional height for resizing
     * @param bool $allowWebp Whether to allow WebP conversion
     * @return string Optimized image URL
     */
    function optimized_image_url(string $src, ?int $width = null, ?int $height = null, bool $allowWebp = true): string
    {
        return app(ImageOptimizationService::class)->getOptimizedUrl($src, $width, $height, $allowWebp);
    }
}

if (!function_exists('webp_image')) {
    /**
     * Get WebP version of an image if supported.
     *
     * @param string $src Image source URL
     * @return string WebP or original image URL
     */
    function webp_image(string $src): string
    {
        return app(ImageOptimizationService::class)->getWebpOrOriginal($src);
    }
}

if (!function_exists('lazy_image')) {
    /**
     * Generate lazy loading image HTML.
     *
     * @param string $src Image source URL
     * @param string|null $alt Alt text
     * @param array $attributes Additional HTML attributes
     * @return string HTML img tag
     */
    function lazy_image(string $src, ?string $alt = null, array $attributes = []): string
    {
        return app(ImageOptimizationService::class)->generateLazyImage($src, $alt, $attributes);
    }
}

if (!function_exists('responsive_image')) {
    /**
     * Generate responsive image HTML with srcset.
     *
     * @param string $src Original image source
     * @param array $sizes Array of sizes [width => max_width_in_px]
     * @param string|null $alt Alt text
     * @param array $attributes Additional attributes
     * @return string HTML img tag
     */
    function responsive_image(string $src, array $sizes, ?string $alt = null, array $attributes = []): string
    {
        return app(ImageOptimizationService::class)->generateResponsiveImage($src, $sizes, $alt, $attributes);
    }
}

if (!function_exists('webp_supported')) {
    /**
     * Check if WebP is supported on the server.
     *
     * @return bool
     */
    function webp_supported(): bool
    {
        return app(ImageOptimizationService::class)->isWebpSupported();
    }
}

if (!function_exists('webp_enabled')) {
    /**
     * Check if WebP optimization is enabled.
     *
     * @return bool
     */
    function webp_enabled(): bool
    {
        return app(ImageOptimizationService::class)->isWebpEnabled();
    }
}

if (!function_exists('lazy_loading_enabled')) {
    /**
     * Check if lazy loading is enabled.
     *
     * @return bool
     */
    function lazy_loading_enabled(): bool
    {
        return app(ImageOptimizationService::class)->isLazyLoadingEnabled();
    }
}

if (!function_exists('client_supports_webp')) {
    /**
     * Check if client browser supports WebP.
     *
     * @return bool
     */
    function client_supports_webp(): bool
    {
        return app(ImageOptimizationService::class)->clientSupportsWebp();
    }
}

if (!function_exists('clear_webp_cache')) {
    /**
     * Clear all generated WebP images from cache.
     *
     * @return int Number of files deleted
     */
    function clear_webp_cache(): int
    {
        return app(ImageOptimizationService::class)->clearWebpCache();
    }
}

if (!function_exists('image_optimization_stats')) {
    /**
     * Get image optimization statistics.
     *
     * @return array Statistics about WebP cache
     */
    function image_optimization_stats(): array
    {
        return app(ImageOptimizationService::class)->getStatistics();
    }
}
