<?php

use MicroweberPackages\Filesystem\Facades\MwFilesystem;

/**
 * Global helper functions for the microweber-filesystem package.
 *
 * These are thin back-compat wrappers around FilesystemService — the SINGLE
 * source of truth. No filesystem logic is implemented here: every function
 * resolves the service (the container singleton when the app is booted, else a
 * container-free standalone instance so the helpers still work during very
 * early boot) and delegates to it.
 */

use MicroweberPackages\Filesystem\FilesystemService;

if (!function_exists('mw_filesystem')) {
    /**
     * Resolve the shared FilesystemService.
     *
     * Returns the FilesystemService container singleton when the app is booted,
     * otherwise a lazily-created standalone instance (FilesystemService has no
     * container dependencies, so it is safe to construct directly). Once the
     * container is up, the singleton is always returned.
     */
    function mw_filesystem(): FilesystemService
    {
        if (function_exists('app') && app()->bound(\MicroweberPackages\Filesystem\FilesystemService::class)) {
            return MwFilesystem::getFacadeRoot();
        }

        static $standalone = null;

        return $standalone ??= new FilesystemService();
    }
}

if (!function_exists('normalize_path')) {
    function normalize_path(string $path, bool $slashIt = true): string
    {
        return mw_filesystem()->normalizePath($path, $slashIt);
    }
}

if (!function_exists('reduce_double_slashes')) {
    function reduce_double_slashes(string $str): string
    {
        return mw_filesystem()->reduceDoubleSlashes($str);
    }
}

if (!function_exists('get_file_extension')) {
    function get_file_extension(string $pathToFile): string
    {
        return mw_filesystem()->getFileExtension($pathToFile);
    }
}

if (!function_exists('no_ext')) {
    function no_ext(string $filename): string
    {
        return mw_filesystem()->noExt($filename);
    }
}

if (!function_exists('file_size_nice')) {
    function file_size_nice(int|float $size): string
    {
        return mw_filesystem()->fileSizeNice($size);
    }
}

if (!function_exists('mkdir_recursive')) {
    function mkdir_recursive(string $pathname): bool
    {
        return mw_filesystem()->mkdirRecursive($pathname);
    }
}

if (!function_exists('rmdir_recursive')) {
    function rmdir_recursive(string $directory, bool $empty = true): bool
    {
        return mw_filesystem()->removeDirRecursive($directory, $empty);
    }
}

if (!function_exists('rglob')) {
    function rglob(string $pattern = '*', int $flags = 0, string $path = ''): array|false
    {
        return mw_filesystem()->rglob($pattern, $flags, $path);
    }
}

if (!function_exists('directory_map')) {
    function directory_map(
        string $sourceDir,
        int $directoryDepth = 0,
        bool $hidden = false,
        bool $fullPath = false
    ): array|false {
        return mw_filesystem()->directoryMap($sourceDir, $directoryDepth, $hidden, $fullPath);
    }
}
