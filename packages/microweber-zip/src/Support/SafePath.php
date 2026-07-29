<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Support;

use MicroweberPackages\Zip\Exceptions\UnsafePathException;

/**
 * Path safety helpers for zip entry names and target destinations.
 *
 * Replaces ad-hoc strpos('..') checks and CMS normalize_path/mkdir_recursive
 * so the package has no CMS coupling.
 */
final class SafePath
{
    /** Characters that are never allowed in archive entry names. */
    private const FORBIDDEN_CHARS = ['*', ':', '?', '"', '<', '>', '|', "\0"];

    /**
     * Normalize slashes and collapse redundant separators.
     */
    public static function normalize(string $path, bool $trailingSlash = false): string
    {
        $path = str_replace('\\', '/', $path);
        $path = (string) preg_replace('#/+#', '/', $path);

        if ($trailingSlash) {
            $path = rtrim($path, '/') . '/';
        } else {
            $path = rtrim($path, '/');
        }

        return $path;
    }

    /**
     * Join directory + file with a single forward slash, then normalize.
     */
    public static function join(string $dir, string $file): string
    {
        if ($dir === '') {
            return self::normalize($file);
        }
        if ($file === '') {
            return self::normalize($dir);
        }

        return self::normalize(rtrim($dir, '/\\') . '/' . ltrim($file, '/\\'));
    }

    /**
     * Validate that an archive entry name is safe to extract under $targetDir.
     *
     * @throws UnsafePathException
     */
    public static function assertSafeEntry(string $entryName, int $maxLength = 512): string
    {
        $entryName = self::normalize($entryName, false);

        if ($entryName === '' || $entryName === '.') {
            throw new UnsafePathException('Empty or invalid archive entry name.');
        }

        if (strlen($entryName) > $maxLength) {
            throw new UnsafePathException(
                sprintf('Archive entry path exceeds max length of %d characters.', $maxLength)
            );
        }

        if (str_contains($entryName, "\0")) {
            throw new UnsafePathException('Archive entry contains a null byte.');
        }

        // Reject newlines and every other control character (C0 range \x01-\x1f
        // plus DEL \x7f). They are invalid in real filenames and enable log /
        // terminal-escape injection when the entry name is later displayed or
        // logged, and can confuse downstream path handling. The raw name is
        // deliberately NOT echoed back — it may carry the offending bytes.
        if (preg_match('/[\x01-\x1f\x7f]/', $entryName) === 1) {
            throw new UnsafePathException('Archive entry contains a control character (e.g. newline or tab).');
        }

        foreach (self::FORBIDDEN_CHARS as $char) {
            if ($char === "\0") {
                continue;
            }
            if (str_contains($entryName, $char)) {
                throw new UnsafePathException(
                    sprintf('Archive entry "%s" contains a forbidden character.', $entryName)
                );
            }
        }

        // Absolute paths (Unix or Windows drive letter)
        if (str_starts_with($entryName, '/') || (bool) preg_match('#^[A-Za-z]:/#', $entryName)) {
            throw new UnsafePathException(
                sprintf('Archive entry "%s" is an absolute path.', $entryName)
            );
        }

        $segments = explode('/', $entryName);
        foreach ($segments as $segment) {
            if ($segment === '..') {
                throw new UnsafePathException(
                    sprintf('Archive entry "%s" contains path traversal.', $entryName)
                );
            }
        }

        return $entryName;
    }

    /**
     * Resolve the absolute target path for an entry and ensure it stays under $targetDir.
     *
     * @throws UnsafePathException
     */
    public static function resolveTarget(string $targetDir, string $entryName): string
    {
        $safeEntry = self::assertSafeEntry($entryName);
        $targetDir = self::normalize($targetDir, true);
        $resolved = self::normalize($targetDir . $safeEntry, false);

        $realBase = realpath(rtrim($targetDir, '/'));
        if ($realBase === false) {
            // Target dir may not exist yet; create parent chain first outside this method.
            $realBase = rtrim($targetDir, '/');
        }

        // Use string prefix check before the file exists; realpath of non-existing returns false.
        $normalizedBase = self::normalize($realBase, true);
        $normalizedResolved = self::normalize($resolved, false);

        if (!str_starts_with($normalizedResolved . '/', $normalizedBase)
            && $normalizedResolved !== rtrim($normalizedBase, '/')
        ) {
            throw new UnsafePathException(
                sprintf('Resolved path "%s" escapes target directory "%s".', $resolved, $targetDir)
            );
        }

        return $resolved;
    }

    /**
     * Recursively create a directory (mkdir -p equivalent).
     *
     * Tolerates races where another process creates the directory between
     * is_dir() and mkdir() (Laravel's error handler would otherwise turn the
     * "File exists" warning into an exception).
     */
    public static function mkdirRecursive(string $pathname, int $mode = 0755): bool
    {
        if ($pathname === '' || is_dir($pathname)) {
            return true;
        }

        try {
            if (@mkdir($pathname, $mode, true)) {
                return true;
            }
        } catch (\Throwable) {
            // fall through to is_dir re-check
        }

        return is_dir($pathname);
    }
}
