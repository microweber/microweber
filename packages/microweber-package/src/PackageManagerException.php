<?php

declare(strict_types=1);

namespace MicroweberPackages\Package;

use Exception;

/**
 * Exceptions raised while configuring Microweber packages / modules.
 */
class PackageManagerException extends Exception
{
    public static function packageNameIsRequired(): self
    {
        return new self('This package does not have a name. Call $package->name(...) in configurePackage().');
    }

    public static function moduleTypeIsRequired(): self
    {
        return new self('This module does not have a type. Call $module->type(...) in configureModule().');
    }
}
