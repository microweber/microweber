<?php

declare(strict_types=1);

namespace Arcanedev\Html\Exceptions;

use Exception;

/**
 * Class     MissingTagException
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MissingTagException extends Exception
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public static function onClass(string $className): static
    {
        return new static(
            "Class {$className} has no `\$tag` property or empty."
        );
    }
}
