<?php

namespace MicroweberPackages\LaravelModules\Helpers;

use MicroweberPackages\LaravelModules\LaravelModule;
use MicroweberPackages\LaravelTemplates\LaravelTemplate;
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
        //$cacheKey = 'module_' . $name.'_'.$path;
        if (isset(self::$modulesCache[$cacheKey])) {
            return self::$modulesCache[$cacheKey];
        }


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


        if (is_file($composer)) {
            self::registerNamespacesFromComposer($composer, $composerAutoloadContent);
        }
        //$module = new \Nwidart\Modules\Laravel\Module ($app, $name, $path);
        $module = new LaravelModule($app, $name, $path);
        self::$modulesCache[$cacheKey] = $module;

//        if ($moduleJson and !empty($moduleJson) and method_exists($module, 'setJson')) {
//            $module->setJson($path . '/' . 'module.json', $moduleJson);
//
//        }

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

        $autoloadNamespaces = [];
        $autoloadFiles = [];
        if (isset($composerAutoloadContent['psr-4'])) {
            $autoloadNamespaces = $composerAutoloadContent['psr-4'];
        }
        if (isset($composerAutoloadContent['files'])) {
            $autoloadFiles = $composerAutoloadContent['files'];
        }


        $path = dirname($composer);


        if (empty($autoloadNamespaces)) {
            $moduleComposer = Json::make($composer)->getAttributes();
            $autoloadNamespaces = $moduleComposer['autoload']['psr-4'] ?? [];
            if (empty($autoloadFiles)) {
                $autoloadFiles = $moduleComposer['autoload']['files'] ?? [];
            }
        }

        if ($autoloadNamespaces) {
            foreach ($autoloadNamespaces as $autoloadNamespace => $autoloadNamespacePath) {
                $autoloadNamespace = trim($autoloadNamespace, '\\');
                $autoloadNamespacePathFull = ($path . DS . $autoloadNamespacePath);
                $autoloadNamespacePathFull = str_replace(['\\', '/'], [DS, DS], $autoloadNamespacePathFull);
                autoload_add_namespace($autoloadNamespacePathFull, $autoloadNamespace);
            }
        }
        if ($autoloadFiles) {
            foreach ($autoloadFiles as $autoloadFile) {
                if (is_file($path . DS . $autoloadFile)) {
                    include_once $path . DS . $autoloadFile;
                }
            }
        }
    }


}
