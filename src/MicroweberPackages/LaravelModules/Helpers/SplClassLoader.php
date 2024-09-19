<?php

namespace MicroweberPackages\LaravelModules\Helpers;

use MicroweberPackages\LaravelModules\LaravelModule;
use MicroweberPackages\LaravelTemplates\LaravelTemplate;
use MicroweberPackages\Utils\System\ClassLoader;
use Nwidart\Modules\Json;

class SplClassLoader
{

    public static $namespaces = [];

    public static function addNamespace($namespace, $path)
    {
        self::$namespaces[$namespace][] = $path;
    }

    public static $notFoundClasses = [];
    public static $foundClasses = [];


    public static function autoloadClass($class): bool
    {
        if (empty(self::$namespaces) || isset(self::$notFoundClasses[$class])) {
            return false;
        }

        if (isset(self::$foundClasses[$class])) {
            require_once(self::$foundClasses[$class]);
            return true;
        }

        $segments = explode('\\', $class);
        $originalClass = $class;

        while (!empty($segments)) {
            $namespace = implode('\\', $segments);
            if (isset(self::$namespaces[$namespace])) {
                foreach (self::$namespaces[$namespace] as $path) {
                    $relativeClass = str_replace($namespace . '\\', '', $originalClass);
                    $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass);
                    $file = rtrim($path, '\\/') . DIRECTORY_SEPARATOR . rtrim($classFile, '\\/') . '.php';
                    if (is_file($file)) {
                        self::$foundClasses[$class] = $file;
                        require_once($file);
                        return true;
                    }
                }
            }
            array_pop($segments);
        }

       self::$notFoundClasses[$class] = true;
        return false;
    }



}
