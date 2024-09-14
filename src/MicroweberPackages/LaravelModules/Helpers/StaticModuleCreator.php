<?php

namespace MicroweberPackages\LaravelModules\Helpers;

use MicroweberPackages\LaravelModules\LaravelModule;
use MicroweberPackages\LaravelTemplates\LaravelTemplate;
use MicroweberPackages\Utils\System\ClassLoader;
use Nwidart\Modules\Json;

class StaticModuleCreator
{
    public static $modulesCache = [];

    public static function createModule(...$args)
    {


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
        if (!is_dir($path)) {
            return null;
        }
        if (!is_file($manifest)) {
            return null;
        }


        $providersNotFound = false;
//        if (isset($moduleJson['providers']) and is_array($moduleJson['providers'])) {
//            foreach ($moduleJson['providers'] as $provider) {
//                $providerFilename = basename($provider . '.php');
//
//                $provider = $path . DS . 'app/Providers' . DS . $providerFilename;
//
//                if (!is_file($provider)) {
//
//                    $providersNotFound = true;
//                }
//
//
//            }
//        }
//        if ($providersNotFound) {
//            self::$modulesCache[$cacheKey] = false;
//            return null;
//        }

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

//        $allComposer = cache_get('all_composer_autoload', 'composer');
//        $allComposerChanged = false;

        $autoloadNamespaces = $composerAutoloadContent['psr-4'] ?? [];
        $autoloadFiles = $composerAutoloadContent['files'] ?? [];

//        if(isset($allComposer[$composer])){
//            $autoloadNamespaces = $allComposer[$composer]['autoload']['psr-4'] ?? [];
//            $autoloadFiles = $allComposer[$composer]['autoload']['files'] ?? [];
//        }


        $path = dirname($composer);

        if (empty($autoloadNamespaces)) {
            $moduleComposer = Json::make($composer)->getAttributes();
         //   $allComposer[$composer] = $moduleComposer;
            $autoloadNamespaces = $moduleComposer['autoload']['psr-4'] ?? [];
            $autoloadFiles = $moduleComposer['autoload']['files'] ?? [];
         //   $allComposerChanged = true;
        }
//        if ($allComposerChanged) {
//            cache_save( $allComposer, 'all_composer_autoload','composer');
//        }
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

//            spl_autoload_register(function ($class) use ($dirname, $namespace, $autoloadNamespace, $autoloadNamespacePathFull) {
//
//                $prefix = $autoloadNamespace;
//                $base_dir = $autoloadNamespacePathFull;
//                $len = strlen($prefix);
//                if(!str_starts_with($class, $prefix)){
//
//                    return;
//                }
//                $namespace = str_replace('/', '\\', $namespace);
//                $relative_class = substr($class, $len);
//                $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
//                if (is_file($file)) {
//                    require $file;
//                }
//            });


        }

        foreach ($autoloadFiles as $autoloadFile) {
            if (is_file($path . DS . $autoloadFile)) {
                if (str_ends_with($autoloadFile, '.php')) {
                    include_once $path . DS . $autoloadFile;
                } else {
                    continue;
                }
            }
        }
        self::$loadModuleNamespacesPathLoadedCache[$path] = true;
    }


}
