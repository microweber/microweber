<?php

namespace MicroweberPackages\LaravelModules\Repositories;

use Nwidart\Modules\FileRepository;
use Nwidart\Modules\Json;

class LaravelModulesFileRepository extends FileRepository
{

    protected function createModule(...$args)
    {
        $app = $args[0];
        $name = $args[1];
        $path = $args[2];

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


        $moduleManifest = Json::make($manifest)->getAttributes();


        if(is_file($composer)) {
            $moduleComposer = Json::make($composer)->getAttributes();


            $autoloadNamespaces = $moduleComposer['autoload']['psr-4'] ?? [];
            $autoloadFiles = $moduleComposer['autoload']['files'] ?? [];

            if ($autoloadNamespaces) {
                foreach ($autoloadNamespaces as $autoloadNamespace => $autoloadNamespacePath) {
                    $autoloadNamespacePathFull = normalize_path($path . DS . $autoloadNamespacePath);
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

        $module = new \Nwidart\Modules\Laravel\Module ($app, $moduleManifest['name'], $path);

        return $module;
    }
}
