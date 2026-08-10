<?php

declare(strict_types=1);

namespace MicroweberPackages\ClassLoader\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\ClassLoader\ClassLoaderService;

/**
 * ClassLoader facade — greppable public API for the class loader package.
 *
 * @method static bool load(string $class)
 * @method static string|null resolve(string $class)
 * @method static string normalizeClass(string $class)
 * @method static ClassLoaderService register(bool $prepend = false)
 * @method static ClassLoaderService unregister()
 * @method static bool isRegistered()
 * @method static ClassLoaderService addDirectories(string|list<string> $directories)
 * @method static ClassLoaderService removeDirectories(string|list<string>|null $directories = null)
 * @method static list<string> getDirectories()
 * @method static ClassLoaderService addNamespace(string $namespace, string $path)
 * @method static ClassLoaderService removeNamespace(string $namespace, string|null $path = null)
 * @method static array<string, list<string>> getNamespaces()
 * @method static ClassLoaderService clearCache()
 * @method static ClassLoaderService reset()
 * @method static array<string, mixed> getStatistics()
 * @method static array{ok: bool, path_dedup: bool, class_normalize: bool, details: array<string, mixed>} selfTest()
 *
 * @see \MicroweberPackages\ClassLoader\ClassLoaderService
 * @mixin \MicroweberPackages\ClassLoader\ClassLoaderService
 */
class ClassLoader extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ClassLoaderService::class;
    }
}
