<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Support;

/**
 * Path normalisation helpers that work without Microweber CMS globals.
 */
class PathHelper
{
    public static function ds(): string
    {
        if (defined('DS')) {
            /** @var string $ds */
            $ds = constant('DS');

            return $ds;
        }

        return DIRECTORY_SEPARATOR;
    }

    public static function normalize(string $path, bool $trailingSlash = false): string
    {
        if (function_exists('normalize_path')) {
            /** @var string $normalized */
            $normalized = normalize_path($path, $trailingSlash);

            return $normalized;
        }

        $path = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
        $path = preg_replace('#' . preg_quote(DIRECTORY_SEPARATOR, '#') . '+#', DIRECTORY_SEPARATOR, $path) ?? $path;

        if ($trailingSlash && $path !== '' && !str_ends_with($path, DIRECTORY_SEPARATOR)) {
            $path .= DIRECTORY_SEPARATOR;
        }

        if (!$trailingSlash && str_ends_with($path, DIRECTORY_SEPARATOR) && $path !== DIRECTORY_SEPARATOR) {
            $path = rtrim($path, DIRECTORY_SEPARATOR);
        }

        return $path;
    }

    public static function ensureDirectory(string $dir): bool
    {
        if (is_dir($dir)) {
            return true;
        }

        if (function_exists('mkdir_recursive')) {
            mkdir_recursive($dir);

            return is_dir($dir);
        }

        return mkdir($dir, 0755, true) || is_dir($dir);
    }
}
