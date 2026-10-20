<?php

declare(strict_types=1);

namespace Arcanedev\Html\Exceptions;

use Exception;

/**
 * Class     InvalidHtmlException
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class InvalidHtmlException extends Exception
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public static function onTag(string $tag): static
    {
        return new static(
            "Can't set inner contents on `{$tag}` because it's a void element"
        );
    }
}
