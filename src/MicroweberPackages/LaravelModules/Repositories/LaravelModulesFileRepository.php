<?php

namespace MicroweberPackages\LaravelModules\Repositories;

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Traits\Macroable;
use Nwidart\Modules\FileRepository;
use Nwidart\Modules\Json;

class LaravelModulesFileRepository extends FileRepository
{
    use Macroable;

    /**
     * Application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application|\Laravel\Lumen\Application
     */
    protected $app;

    /**
     * The module path.
     *
     * @var string|null
     */
    protected $path;

    /**
     * The scanned paths.
     *
     * @var array
     */
    protected $paths = [];

    /**
     * @var string
     */
    protected $stubPath;

    /**
     * @var UrlGenerator
     */
    private $url;

    /**
     * @var ConfigRepository
     */
    private $config;

    /**
     * @var Filesystem
     */
    private $files;

    /**
     * @var CacheManager
     */
    private $cache;

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

    public function find(string $name)
    {
        foreach ($this->all() as $module) {
            if ($module->getLowerName() === strtolower($name)) {
                return $module;
            }
        }

    }
}
