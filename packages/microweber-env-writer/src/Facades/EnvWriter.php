<?php

namespace MicroweberPackages\EnvWriter\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\EnvWriter\EnvWriterService;

/**
 * EnvWriter facade — greppable public API for .env file writing.
 *
 * @method static bool save(array $values, string $envFilePath)
 * @method static string formatValue(mixed $value)
 * @method static array read(string $envFilePath)
 *
 * @see \MicroweberPackages\EnvWriter\EnvWriterService
 * @mixin \MicroweberPackages\EnvWriter\EnvWriterService
 */
class EnvWriter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return EnvWriterService::class;
    }
}
