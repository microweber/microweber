<?php

use MicroweberPackages\Thumbnailer\ThumbnailGenerator;

if (!function_exists('php_can_use_func')) {
    /**
     * Check if a PHP function is available (not disabled).
     * Polyfill for standalone use outside Microweber.
     */
    function php_can_use_func(string $func_name): bool
    {
        if (!function_exists($func_name)) {
            return false;
        }
        $disabled = ini_get('disable_functions');
        if ($disabled && in_array($func_name, array_map('trim', explode(',', $disabled)))) {
            return false;
        }
        return true;
    }
}

if (!function_exists('thumbnailer_generate')) {
    /**
     * Generate a thumbnail for the given source image path.
     *
     * @param string $srcPath Absolute path to source image
     * @param int $width
     * @param int|null $height
     * @param bool|string|null $crop
     * @return string|null Absolute path to generated thumbnail, or null on failure
     */
    function thumbnailer_generate(string $srcPath, int $width = 200, ?int $height = null, $crop = null): ?string
    {
        return app(ThumbnailGenerator::class)->generate($srcPath, $width, $height, $crop);
    }
}

if (!function_exists('thumbnailer_pixum')) {
    /**
     * Generate a placeholder (pixum) image.
     *
     * @param int $width
     * @param int $height
     * @return string Absolute path to generated pixum
     */
    function thumbnailer_pixum(int $width = 150, int $height = 0): string
    {
        return app(ThumbnailGenerator::class)->pixum($width, $height);
    }
}