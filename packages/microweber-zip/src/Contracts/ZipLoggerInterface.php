<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Contracts;

/**
 * Minimal logger contract used during extraction.
 *
 * The CMS Restore logger satisfies this via an adapter that calls
 * static setLogInfo() methods on the RestoreLogger class.
 */
interface ZipLoggerInterface
{
    public function info(string $message): void;
}
