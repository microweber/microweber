<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Installers;

/**
 * Composer installer for generic nwidart / laravel-module packages → Modules/{dir}/
 */
class NwidartModuleInstaller extends BaseInstaller
{
    protected string $folderBase = 'Modules';

    protected string $supportsType = 'laravel-module';

    public function supports(string $packageType): bool
    {
        return in_array($packageType, ['laravel-module', 'nwidart-module'], true);
    }
}
