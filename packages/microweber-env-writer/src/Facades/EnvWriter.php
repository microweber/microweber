<?php

namespace MicroweberPackages\EnvWriter\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\EnvWriter\EnvWriter as EnvWriterClass;

/**
 * @method static bool save(array $values, string $envFilePath)
 * @method static string formatValue(mixed $value)
 * @method static array read(string $envFilePath)
 *
 * @see \MicroweberPackages\EnvWriter\EnvWriter
 */
class EnvWriter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return EnvWriterClass::class;
    }
}