<?php

namespace MicroweberPackages\Package;


class PackageManagerException extends \Exception
{
    public static function moduleTypeIsRequired(): self
    {
        return new static('This module does not have a type');
    }
}
