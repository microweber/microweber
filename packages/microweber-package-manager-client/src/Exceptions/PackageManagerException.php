<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Exceptions;

use Exception;

class PackageManagerException extends Exception
{
    public static function packageNotFound(string $name): self
    {
        return new self(sprintf('Package not found: %s', $name));
    }

    public static function cannotDetermineInstallDir(string $packageName): self
    {
        return new self(sprintf(
            'Unable to determine install directory for package "%s"',
            $packageName
        ));
    }

    public static function downloadFailed(string $url, string $reason = ''): self
    {
        $msg = sprintf('Failed to download package from %s', $url);
        if ($reason !== '') {
            $msg .= ': ' . $reason;
        }

        return new self($msg);
    }

    public static function extractFailed(string $archivePath): self
    {
        return new self(sprintf('Failed to extract package archive: %s', $archivePath));
    }

    public static function invalidPackageType(string $type): self
    {
        return new self(sprintf('Unsupported package type: %s', $type));
    }
}
