<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Tests;

/**
 * Static logger stub used by ZipArchiveExtractorTest for ClassZipLogger coverage.
 */
final class FakeStaticZipLogger
{
    /** @var list<string> */
    public static array $logs = [];

    public static function setLogInfo(string $message): void
    {
        self::$logs[] = $message;
    }

    public static function reset(): void
    {
        self::$logs = [];
    }
}
