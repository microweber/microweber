<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Support;

use MicroweberPackages\PackageManagerClient\Exceptions\PackageManagerException;
use MicroweberPackages\Zip\Unzip;

/**
 * ZIP extractor for downloaded marketplace packages.
 *
 * Delegates to the hardened microweber-packages/zip {@see Unzip} so untrusted
 * archives get path-traversal protection (SafePath), zip-bomb limits, and
 * symlink / control-character rejection. A raw ZipArchive::extractTo() writes
 * the whole archive at once and does NOT guard against `../` / absolute-path
 * entries escaping the destination — arbitrary file write → RCE, since installed
 * packages contain executable PHP.
 */
final class ZipExtractor
{
    /**
     * Extract an archive into $destination. Returns list of extracted basenames.
     *
     * @return list<string>
     */
    public function extract(string $archivePath, string $destination, bool $deleteArchive = false): array
    {
        if (!is_file($archivePath)) {
            throw PackageManagerException::extractFailed($archivePath);
        }

        FilesystemHelper::ensureDirectory($destination);

        // Unzip enforces the security guards and skips any unsafe entry; a fatal
        // problem (bad archive, bomb, traversal on the whole thing) returns false
        // or an ['error' => ...] shape.
        $result = (new Unzip())->extract($archivePath, $destination, true);

        if ($result === false || (is_array($result) && isset($result['error']))) {
            throw PackageManagerException::extractFailed($archivePath);
        }

        if ($deleteArchive) {
            @unlink($archivePath);
        }

        $names = [];
        foreach ((array) $result as $path) {
            if (is_string($path) && $path !== '') {
                $names[] = basename($path);
            }
        }

        return array_values(array_unique(array_filter($names, static fn (string $n): bool => $n !== '')));
    }
}
