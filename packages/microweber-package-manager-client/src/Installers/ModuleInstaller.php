<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Installers;

/**
 * Composer installer for microweber-module packages → Modules/{dir}/
 */
class ModuleInstaller extends BaseInstaller
{
    protected string $folderBase = 'Modules';

    protected string $supportsType = 'microweber-module';
}
