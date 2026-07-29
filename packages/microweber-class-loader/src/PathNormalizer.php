<?php

declare(strict_types=1);

namespace MicroweberPackages\ClassLoader;

/**
 * Normalizes filesystem paths so equivalent path strings compare equal.
 *
 * Handles mixed separators, trailing slashes, redundant segments, and
 * realpath resolution when the path already exists on disk.
 */
final class PathNormalizer
{
    /**
     * Normalize a path for comparison and storage.
     *
     * Always uses forward slashes as the canonical separator so the same path
     * presented with "\" or "/" (and with/without a trailing slash) maps to
     * one key. When the path exists, realpath() is preferred.
     */
    public function normalize(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return '';
        }

        // Prefer realpath for existing filesystem entries.
        $real = @realpath($path);
        if ($real !== false) {
            return $this->canonicalize($real);
        }

        return $this->canonicalize($path);
    }

    /**
     * Whether two path strings refer to the same location after normalization.
     */
    public function equals(string $a, string $b): bool
    {
        return $this->normalize($a) === $this->normalize($b);
    }

    /**
     * Collapse separators and "." / ".." segments; use "/" as canonical separator.
     */
    public function canonicalize(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#/+#', '/', $path) ?? $path;

        $isUnc = str_starts_with($path, '//');
        $drive = '';
        if (preg_match('#^([A-Za-z]:)(/.*)?$#', $path, $matches) === 1) {
            $drive = strtoupper($matches[1]);
            $path = $matches[2] ?? '/';
        }

        $isAbsolute = str_starts_with($path, '/');
        $parts = explode('/', $path);
        $stack = [];

        foreach ($parts as $part) {
            if ($part === '' || $part === '.') {
                continue;
            }
            if ($part === '..') {
                if ($stack !== [] && end($stack) !== '..') {
                    array_pop($stack);
                } elseif (!$isAbsolute && $drive === '') {
                    $stack[] = '..';
                }
                continue;
            }
            $stack[] = $part;
        }

        $normalized = implode('/', $stack);

        if ($drive !== '') {
            return $drive . ($normalized !== '' ? '/' . $normalized : '');
        }

        if ($isUnc) {
            return '//' . $normalized;
        }

        if ($isAbsolute) {
            return '/' . $normalized;
        }

        return $normalized;
    }

    /**
     * Convert a normalized path to the OS-native separator for filesystem calls.
     */
    public function toOsPath(string $normalizedPath): string
    {
        if (DIRECTORY_SEPARATOR === '/') {
            return $normalizedPath;
        }

        return str_replace('/', DIRECTORY_SEPARATOR, $normalizedPath);
    }
}
