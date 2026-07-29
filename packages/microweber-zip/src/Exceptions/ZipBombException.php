<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Exceptions;

/**
 * Thrown when an archive violates zip-bomb protection limits.
 */
class ZipBombException extends ZipException
{
}
