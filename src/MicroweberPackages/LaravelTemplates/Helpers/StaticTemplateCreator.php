<?php

namespace MicroweberPackages\LaravelTemplates\Helpers;

use MicroweberPackages\LaravelModules\Helpers\StaticModuleCreator;
use MicroweberPackages\LaravelModules\LaravelModule;
use MicroweberPackages\LaravelTemplates\LaravelTemplate;

class StaticTemplateCreator extends StaticModuleCreator
{


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
        $module = new LaravelTemplate($app, $name, $path);
        //    self::$modulesCache[$cacheKey] = $module;

        if ($moduleJson and !empty($moduleJson) and method_exists($module, 'setJsonCacheData')) {
            $module->setJsonCacheData($moduleJson);

        }


        return $module;
    }


}
