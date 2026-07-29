<?php

declare(strict_types=1);

namespace MicroweberPackages\ClassLoader\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\ClassLoader\ClassLoader;

/**
 * @method static bool load(string $class)
 * @method static string|null resolve(string $class)
 * @method static string normalizeClass(string $class)
 * @method static ClassLoader register(bool $prepend = false)
 * @method static ClassLoader unregister()
 * @method static bool isRegistered()
 * @method static ClassLoader addDirectories(string|list<string> $directories)
 * @method static ClassLoader removeDirectories(string|list<string>|null $directories = null)
 * @method static list<string> getDirectories()
 * @method static ClassLoader addNamespace(string $namespace, string $path)
 * @method static ClassLoader removeNamespace(string $namespace, string|null $path = null)
 * @method static array<string, list<string>> getNamespaces()
 * @method static ClassLoader clearCache()
 * @method static ClassLoader reset()
 * @method static array<string, mixed> getStatistics()
 * @method static array{ok: bool, path_dedup: bool, class_normalize: bool, details: array<string, mixed>} selfTest()
 *
 * @see ClassLoader
 */
class ClassLoaderFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ClassLoader::class;
    }
}
