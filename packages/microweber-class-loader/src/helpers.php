<?php

declare(strict_types=1);

use MicroweberPackages\ClassLoader\ClassLoader;

if (!function_exists('mw_class_loader')) {
    /**
     * Resolve the shared ClassLoader service.
     */
    function mw_class_loader(): ClassLoader
    {
        try {
            return app(ClassLoader::class);
        } catch (Throwable) {
            return new ClassLoader();
        }
    }
}

if (!function_exists('class_loader_add_directories')) {
    /**
     * @param  string|list<string>  $directories
     */
    function class_loader_add_directories(string|array $directories): ClassLoader
    {
        return mw_class_loader()->addDirectories($directories);
    }
}

if (!function_exists('class_loader_add_namespace')) {
    function class_loader_add_namespace(string $namespace, string $path): ClassLoader
    {
        return mw_class_loader()->addNamespace($namespace, $path);
    }
}

if (!function_exists('class_loader_stats')) {
    /**
     * @return array<string, mixed>
     */
    function class_loader_stats(): array
    {
        return mw_class_loader()->getStatistics();
    }
}

if (!function_exists('class_loader_resolve')) {
    function class_loader_resolve(string $class): ?string
    {
        return mw_class_loader()->resolve($class);
    }
}
