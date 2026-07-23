<?php

/**
 * Pixum helper functions — loaded via Composer autoload files.
 *
 * These provide backward compatibility with the original Microweber
 * pixum functions while delegating to the standalone package.
 */

if (!function_exists('pixum')) {
    /**
     * Generate a pixum placeholder image and return its URL.
     *
     * @param int $width
     * @param int|false $height
     * @return string URL to the pixum image
     */
    function pixum(int $width = 200, $height = false): string
    {
        if ($height === false || $height <= 0) {
            $height = $width;
        }

        return app(\MicroweberPackages\MediaPixum\PixumGenerator::class)->url($width, (int) $height);
    }
}

if (!function_exists('pixum_path')) {
    /**
     * Generate a pixum placeholder image and return its filesystem path.
     *
     * @param int $width
     * @param int|false $height
     * @return string Absolute path to the pixum image
     */
    function pixum_path(int $width = 200, $height = false): string
    {
        if ($height === false || $height <= 0) {
            $height = $width;
        }

        return app(\MicroweberPackages\MediaPixum\PixumGenerator::class)->generate($width, (int) $height);
    }
}