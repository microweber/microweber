<?php

namespace MicroweberPackages\Core\Services;

class ServiceLoader
{
    public function __construct()
    {

    }
    public function registerModuleFromConfig($config)
    {


        if (isset($config['settings']) and $config['settings'] and isset($config['settings']['autoload_namespace']) and is_array($config['settings']['autoload_namespace']) and !empty($config['settings']['autoload_namespace'])) {
            foreach ($config['settings']['autoload_namespace'] as $namespace_item) {
                if (isset($namespace_item['path']) and isset($namespace_item['namespace'])) {
                    $path = normalize_path($namespace_item['path'], 1);
                    $namespace = $namespace_item['namespace'];
                    if ($path and is_dir($path)) {
                        autoload_add_namespace($path, $namespace);
                    }
                }
            }
        }


        $loadProviders = [];
        if (is_array($config['settings']['service_provider'])) {
            foreach ($config['settings']['service_provider'] as $serviceProvider) {
                $loadProviders[] = $serviceProvider;
            }
        } else {
            $loadProviders[] = $config['settings']['service_provider'];
        }

        foreach ($loadProviders as $loadProvider) {
            if (class_exists($loadProvider)) {
                app()->register($loadProvider);
            }
        }
    }
}
