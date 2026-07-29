<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Exceptions;

/**
 * Thrown when an archive entry path is unsafe (traversal, null bytes, etc.).
 */
class UnsafePathException extends ZipException
{
}
