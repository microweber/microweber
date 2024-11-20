<?php

namespace MicroweberPackages\LaravelModules\Helpers;

use MicroweberPackages\LaravelModules\LaravelModule;
use MicroweberPackages\LaravelTemplates\LaravelTemplate;
use MicroweberPackages\Utils\System\ClassLoader;
use Nwidart\Modules\Json;

class StaticModuleCreator
{
    public static $modulesCache = [];
    public static $loadedFilesFromComposerCache = [];

    public static function createModule(...$args)
    {

        if (empty(self::$loadedFilesFromComposerCache)) {
            $loadedFilesFromComposer = get_included_files();
            self::$loadedFilesFromComposerCache = $loadedFilesFromComposer;
        }


        $app = $args[0];
        $name = $args[1];
        $path = $args[2];
        $moduleJson = $args[3] ?? null;
        $composerAutoloadContent = $args[4] ?? [];


        $cacheKey = $name;
        $cacheKey = 'module_' . $name . '_' . $path;
//        if (isset(self::$modulesCache[$cacheKey])) {
//            return self::$modulesCache[$cacheKey];
//        }


        start_measure('module_create_' . $name, 'Create module ' . $name);


        $manifest = $path . DS . 'module.json';
        $composer = $path . DS . 'composer.json';
        if (!$path) {
            return null;
        }

        if (!is_file($manifest)) {
            return null;
        }


        if (is_file($composer)) {

            self::registerNamespacesFromComposer($composer, $composerAutoloadContent);
        }

        //$module = new \Nwidart\Modules\Laravel\Module ($app, $name, $path);
        $module = new LaravelModule($app, $name, $path);
        //    self::$modulesCache[$cacheKey] = $module;

        if ($moduleJson and !empty($moduleJson) and method_exists($module, 'setJsonCacheData')) {
            $module->setJsonCacheData($moduleJson);

        }

        stop_measure('module_create_' . $name);

        return $module;
    }

    public static $registeredComposerFiles = [];

    public static function registerNamespacesFromComposer($composer, $composerAutoloadContent = [])
    {
        if (in_array($composer, self::$registeredComposerFiles)) {
            return;
        }
        self::$registeredComposerFiles[] = $composer;


        $autoloadNamespaces = $composerAutoloadContent['psr-4'] ?? [];
        $autoloadFiles = $composerAutoloadContent['files'] ?? [];


        $path = dirname($composer);

        if (empty($autoloadNamespaces)) {
            $moduleComposer = Json::make($composer)->getAttributes();
            $autoloadNamespaces = $moduleComposer['autoload']['psr-4'] ?? [];
            $autoloadFiles = $moduleComposer['autoload']['files'] ?? [];
        }

        self::loadModuleNamespaces($path, $autoloadNamespaces, $autoloadFiles);
    }


    public static $loadModuleNamespacesPathLoadedCache = [];

    public static function loadModuleNamespaces($path, $autoloadNamespaces = [], $autoloadFiles = [])
    {
        if (isset(self::$loadModuleNamespacesPathLoadedCache[$path])) {

            return;
        }
        foreach ($autoloadNamespaces as $autoloadNamespace => $autoloadNamespacePath) {
            $autoloadNamespace = trim($autoloadNamespace, '\\');
            $autoloadNamespacePathFull = str_replace(['\\', '/'], [DS, DS], $path . DS . $autoloadNamespacePath);
            //  autoload_add_namespace($autoloadNamespacePathFull, $autoloadNamespace);

            $dirname = $autoloadNamespacePathFull;
            $namespace = $autoloadNamespace;

            SplClassLoader::addNamespace($namespace, $dirname);


        }


        foreach ($autoloadFiles as $autoloadFile) {
            $filePath = $path . DS . $autoloadFile;
            $filePathDS = str_replace(['\\', '/'], [DS, DS], $filePath);

            if (is_file($filePath)) {
                if (str_ends_with($autoloadFile, '.php')) {
                    if(in_array($filePathDS, self::$loadedFilesFromComposerCache)){
                        continue;
                    }

                    include_once $path . DS . $autoloadFile;
                } else {
                    continue;
                }
            }
        }
        self::$loadModuleNamespacesPathLoadedCache[$path] = true;
    }


}
