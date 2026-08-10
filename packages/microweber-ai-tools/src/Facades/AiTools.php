<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\AiTools\Contracts\ToolInterface;
use MicroweberPackages\AiTools\Contracts\ToolRegistryInterface;

/**
 * @method static void register(string $toolClass)
 * @method static void registerInstance(ToolInterface $tool)
 * @method static void registerFactory(string $name, callable $factory)
 * @method static void unregister(string $toolName)
 * @method static ToolInterface|null get(string $name)
 * @method static ToolInterface|null make(string $name, array<string, mixed> $dependencies = [])
 * @method static array<string, ToolInterface> all()
 * @method static array<string, ToolInterface> getByDomain(string $domain)
 * @method static bool has(string $name)
 * @method static list<string> names()
 * @method static void registerMany(list<class-string<ToolInterface>> $toolClasses)
 * @method static void clear()
 * @method static void registerAlias(string $alias, string $toolName)
 * @method static array<string, string> getAliases()
 * @method static array<string, ToolInterface> getByPermission(string $permission)
 * @method static array<string, ToolInterface> getAuthorized()
 * @method static int count()
 * @method static list<string> getDomains()
 * @method static class-string<ToolInterface>|null getClass(string $name)
 *
 * @see ToolRegistryInterface
 */
class AiTools extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ToolRegistryInterface::class;
    }
}
