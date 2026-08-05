<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient;

/**
 * Resolved install location for a package.
 */
final class InstallTarget
{
    public function __construct(
        public readonly string $type,
        public readonly string $directory,
        public readonly string $relativePath,
        public readonly string $absolutePath,
        public readonly string $packageName,
    ) {
    }

    /**
     * @return array{
     *     type: string,
     *     directory: string,
     *     relative_path: string,
     *     absolute_path: string,
     *     package_name: string
     * }
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'directory' => $this->directory,
            'relative_path' => $this->relativePath,
            'absolute_path' => $this->absolutePath,
            'package_name' => $this->packageName,
        ];
    }
}
