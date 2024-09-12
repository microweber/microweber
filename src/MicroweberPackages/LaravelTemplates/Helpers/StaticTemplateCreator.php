<?php

namespace MicroweberPackages\LaravelTemplates\Helpers;

use MicroweberPackages\LaravelModules\Helpers\StaticModuleCreator;
use MicroweberPackages\LaravelTemplates\LaravelTemplate;

class StaticTemplateCreator extends StaticModuleCreator
{


    public static function createModule(...$args)
    {

        $app = $args[0];
        $name = $args[1];
        $path = $args[2];

        $cacheKey = 'template_' . $name . '_' . $path;
        if (isset(self::$modulesCache[$cacheKey])) {
            return self::$modulesCache[$cacheKey];
        }

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
            self::registerNamespacesFromComposer($composer);
        }

        $module = new LaravelTemplate($app, $name, $path);
        self::$modulesCache[$cacheKey] = $module;
        return $module;
    }


}
