<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Support;

use MicroweberPackages\Zip\Contracts\FileAllowanceCheckerInterface;

/**
 * FileAllowanceChecker that delegates to microweber-filesystem when available.
 *
 * Falls back to a simple dangerous-extension denylist so the package remains
 * usable in standalone Laravel apps without the filesystem package.
 */
final class FilesystemAllowanceChecker implements FileAllowanceCheckerInterface
{
    /** @var list<string> */
    private const DANGEROUS_EXTENSIONS = [
        'php', 'php3', 'php4', 'php5', 'php7', 'php8', 'phtml', 'phar',
        'exe', 'bat', 'cmd', 'com', 'msi', 'scr',
        'sh', 'bash', 'cgi', 'pl', 'py', 'rb',
        'htaccess', 'htpasswd',
    ];

    public function isAllowed(string $entryName): bool
    {
        // Prefer the shared filesystem service when the CMS (or host) provides it.
        if (function_exists('mw_filesystem')) {
            return mw_filesystem()->isAllowedFile($entryName);
        }

        $ext = strtolower(pathinfo($entryName, PATHINFO_EXTENSION));
        if ($ext === '') {
            // Directory entries and extension-less files are allowed.
            return true;
        }

        return !in_array($ext, self::DANGEROUS_EXTENSIONS, true);
    }
}
