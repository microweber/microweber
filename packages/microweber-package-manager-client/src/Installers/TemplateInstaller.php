<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Installers;

/**
 * Composer installer for microweber-template packages → Templates/{dir}/
 */
class TemplateInstaller extends BaseInstaller
{
    protected string $folderBase = 'Templates';

    protected string $supportsType = 'microweber-template';
}
