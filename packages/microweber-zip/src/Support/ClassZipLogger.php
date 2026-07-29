<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Support;

use MicroweberPackages\Zip\Contracts\ZipLoggerInterface;

/**
 * Adapter that forwards info() calls to a static logger class method.
 *
 * Used for CMS compatibility with RestoreLogger::setLogInfo().
 *
 * @phpstan-type StaticLogger class-string
 */
final class ClassZipLogger implements ZipLoggerInterface
{
    /**
     * @param class-string $class
     */
    public function __construct(
        private readonly string $class,
        private readonly string $method = 'setLogInfo',
    ) {
    }

    public function info(string $message): void
    {
        if (!class_exists($this->class)) {
            return;
        }

        if (!method_exists($this->class, $this->method)) {
            return;
        }

        ($this->class)::{$this->method}($message);
    }
}
