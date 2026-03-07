<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\AiTools\Contracts\ToolRegistryInterface;

/**
 * @method static void register(string $toolClass)
 * @method static void unregister(string $toolName)
 * @method static \MicroweberPackages\AiTools\Contracts\ToolInterface|null get(string $name)
 * @method static array all()
 * @method static array getByDomain(string $domain)
 * @method static bool has(string $name)
 * @method static array names()
 * @method static void registerMany(array $toolClasses)
 * @method static void clear()
 * @method static int count()
 * @method static array getDomains()
 * @method static array getAuthorized()
 *
 * @see \MicroweberPackages\AiTools\Contracts\ToolRegistryInterface
 */
class AiTools extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return ToolRegistryInterface::class;
    }
}
