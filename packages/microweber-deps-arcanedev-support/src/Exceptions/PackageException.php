<?php

declare(strict_types=1);

namespace Arcanedev\Support\Exceptions;

use Exception;

/**
 * Class     PackageException
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PackageException extends Exception
{
    public static function unspecifiedName(): self
    {
        return new static('You must specify the vendor/package name.');
    }
}
