<?php

declare(strict_types=1);

use MicroweberPackages\ImageOptimization\Services\ImageOptimizationService;

if (!function_exists('optimized_image_url')) {
    /**
     * Get optimized image URL with optional WebP conversion.
     */
    function optimized_image_url(string $src, ?int $width = null, ?int $height = null, bool $allowWebp = true): string
    {
        return app(ImageOptimizationService::class)->getOptimizedUrl($src, $width, $height, $allowWebp);
    }
}

if (!function_exists('webp_image')) {
    /**
     * Get WebP version of an image if supported.
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
     * @param  array<string, mixed>  $attributes
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
     * @param  array<int|string, int|string>  $sizes
     * @param  array<string, mixed>  $attributes
     */
    function responsive_image(string $src, array $sizes, ?string $alt = null, array $attributes = []): string
    {
        return app(ImageOptimizationService::class)->generateResponsiveImage($src, $sizes, $alt, $attributes);
    }
}

if (!function_exists('webp_supported')) {
    function webp_supported(): bool
    {
        return app(ImageOptimizationService::class)->isWebpSupported();
    }
}

if (!function_exists('webp_enabled')) {
    function webp_enabled(): bool
    {
        return app(ImageOptimizationService::class)->isWebpEnabled();
    }
}

if (!function_exists('lazy_loading_enabled')) {
    function lazy_loading_enabled(): bool
    {
        return app(ImageOptimizationService::class)->isLazyLoadingEnabled();
    }
}

if (!function_exists('client_supports_webp')) {
    function client_supports_webp(): bool
    {
        return app(ImageOptimizationService::class)->clientSupportsWebp();
    }
}

if (!function_exists('clear_webp_cache')) {
    function clear_webp_cache(): int
    {
        return app(ImageOptimizationService::class)->clearWebpCache();
    }
}

if (!function_exists('image_optimization_stats')) {
    /**
     * @return array<string, mixed>
     */
    function image_optimization_stats(): array
    {
        return app(ImageOptimizationService::class)->getStatistics();
    }
}
