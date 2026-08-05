<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Support;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Small filesystem helpers used by the installer (no Laravel dependency).
 */
final class FilesystemHelper
{
    public static function ensureDirectory(string $path, int $mode = 0755): void
    {
        if (!is_dir($path)) {
            mkdir($path, $mode, true);
        }
    }

    public static function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        try {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );

            /** @var SplFileInfo $fileinfo */
            foreach ($files as $fileinfo) {
                $real = $fileinfo->getRealPath();
                if ($real === false) {
                    continue;
                }
                if ($fileinfo->isDir()) {
                    @rmdir($real);
                } else {
                    @unlink($real);
                }
            }
        } catch (\Throwable) {
            // Best-effort cleanup
        }

        @rmdir($dir);
    }

    /**
     * @return list<string> Filenames only (basename)
     */
    public static function listFilenamesRecursive(string $dir): array
    {
        if (!is_dir($dir)) {
            return [];
        }

        $directory = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        $files = [];
        /** @var SplFileInfo $info */
        foreach ($directory as $info) {
            $files[] = $info->getFilename();
        }

        return $files;
    }

    /**
     * Move/rename a directory, falling back to copy+delete across devices.
     */
    public static function moveDirectory(string $from, string $to): bool
    {
        if (!is_dir($from)) {
            return false;
        }

        self::ensureDirectory(dirname($to));

        if (@rename($from, $to)) {
            return true;
        }

        // Cross-device fallback
        self::copyDirectory($from, $to);
        self::removeDirectory($from);

        return is_dir($to);
    }

    public static function copyDirectory(string $from, string $to): void
    {
        self::ensureDirectory($to);

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($from, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        /** @var SplFileInfo $item */
        foreach ($iterator as $item) {
            $subPath = $iterator->getSubPathName();
            $target = $to . DIRECTORY_SEPARATOR . $subPath;
            if ($item->isDir()) {
                self::ensureDirectory($target);
            } else {
                $real = $item->getRealPath();
                if ($real !== false) {
                    self::ensureDirectory(dirname($target));
                    copy($real, $target);
                }
            }
        }
    }

    /**
     * Resolve a path that may be absolute or relative to $base.
     */
    public static function resolvePath(string $path, string $base): string
    {
        if ($path === '') {
            return rtrim($base, '/\\');
        }

        // Absolute (Unix or Windows drive)
        if (str_starts_with($path, '/') || preg_match('#^[A-Za-z]:[\\\\/]#', $path) === 1) {
            return rtrim($path, '/\\');
        }

        return rtrim($base, '/\\') . DIRECTORY_SEPARATOR . trim($path, '/\\');
    }
}
