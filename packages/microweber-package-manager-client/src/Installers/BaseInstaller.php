<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Installers;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use MicroweberPackages\PackageManagerClient\InstallDirDetector;

/**
 * Composer LibraryInstaller bridge that uses InstallDirDetector.
 *
 * Usable when running full Composer installs (not only the zip client).
 */
class BaseInstaller extends LibraryInstaller
{
    protected string $folderBase = '';

    protected string $supportsType = '';

    public function getInstallPath(PackageInterface $package): string
    {
        $extra = $package->getExtra();
        $targetDir = $package->getTargetDir();

        $meta = [
            'name' => $package->getPrettyName(),
            'type' => $package->getType(),
            'target-dir' => $targetDir ?: '',
            'extra' => $extra,
        ];

        $detector = new InstallDirDetector([
            'base_path' => getcwd() ?: '.',
            'modules_path' => $this->folderBase !== '' ? rtrim($this->folderBase, '/\\') : 'Modules',
            'templates_path' => 'Templates',
        ]);

        // Prefer explicit folder_base + resolved directory for composer installers API
        $directory = $detector->resolveDirectory($meta);
        if ($directory === '') {
            throw new \InvalidArgumentException(
                'Unable to determine the install folder for ' . $package->getPrettyName()
            );
        }

        $directory = $this->sanitizePath($directory);

        return rtrim($this->folderBase, '/\\') . '/' . $directory;
    }

    public function supports(string $packageType): bool
    {
        return $this->supportsType === $packageType;
    }

    protected function sanitizePath(string $folder): string
    {
        $folder = str_replace(['\\', "\0"], ['/', ''], $folder);
        $folder = str_replace('..', '', $folder);

        return trim($folder, '/');
    }
}
