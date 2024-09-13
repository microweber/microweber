<?php

namespace MicroweberPackages\Utils\System;

class ClassLoader
{
    /**
     * The registered directories.
     *
     * @var array
     */
    protected static $directories = [];

    /**
     * Indicates if a ClassLoader has been registered.
     *
     * @var bool
     */
    protected static $registered = false;

    /**
     * Load the given class file.
     *
     * @param  string  $class
     * @return bool
     */
    public static function load($class)
    {
        $class = static::normalizeClass($class);

        foreach (static::$directories as $directory) {
            if (is_file($path = $directory.DIRECTORY_SEPARATOR.$class)) {
                require_once $path;

                return true;
            }
        }

        return false;
    }

    /**
     * Get the normal file name for a class.
     *
     * @param  string  $class
     * @return string
     */
    public static function normalizeClass($class)
    {
        if ($class[0] == '\\') {
            $class = substr($class, 1);
        }

        return str_replace(['\\', '_'], DIRECTORY_SEPARATOR, $class).'.php';
    }

    /**
     * Register the given class loader on the auto-loader stack.
     *
     * @return void
     */
    public static function register()
    {
        if (! static::$registered) {
            static::$registered = spl_autoload_register([static::class, 'load']);
        }
    }

    /**
     * Add directories to the class loader.
     *
     * @param  string|array  $directories
     * @return void
     */
    public static function addDirectories($directories)
    {
        static::$directories = array_unique(array_merge(static::$directories, (array) $directories));
    }

    /**
     * Remove directories from the class loader.
     *
     * @param  string|array  $directories
     * @return void
     */
    public static function removeDirectories($directories = null)
    {
        if (is_null($directories)) {
            static::$directories = [];
        } else {
            static::$directories = array_diff(static::$directories, (array) $directories);
        }
    }

    /**
     * Gets all the directories registered with the loader.
     *
     * @return array
     */
    public static function getDirectories()
    {
        return static::$directories;
    }
}
