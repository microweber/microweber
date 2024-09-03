<?php

namespace MicroweberPackages\LaravelTemplates\Repositories;

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesFileRepository;
use MicroweberPackages\LaravelTemplates\LaravelTemplate;
use Nwidart\Modules\Exceptions\ModuleNotFoundException;
use Nwidart\Modules\FileRepository;
use Nwidart\Modules\Json;

class LaravelTemplatesFileRepository extends LaravelModulesFileRepository
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
    public function all(): array
    {
        if (! $this->config('cache.enabled')) {

            return $this->scan();
        }
        //return $this->scan();
        return $this->formatCached($this->getCached());
    }
    protected function formatCached($cached)
    {
        $modules = [];

        foreach ($cached as $name => $module) {
            $path = $module['path'];

            $modules[$name] = $this->createModule($this->app, $name, $path);
        }

        return $modules;
    }

    public function getCached()
    {
        return $this->cache->store($this->config->get('templates.cache.driver'))->remember($this->config('cache.key'), $this->config('cache.lifetime'), function () {
            return $this->toCollection()->toArray();
        });
    }
    public function getUsedStoragePath(): string
    {
        $directory = storage_path('app/modules');
        if ($this->getFiles()->exists($directory) === false) {
            $this->getFiles()->makeDirectory($directory, 0777, true);
        }

        $path = storage_path('app/modules/templates.used');
        if (! $this->getFiles()->exists($path)) {
            $this->getFiles()->put($path, '');
        }

        return $path;
    }
    public function getAssetsPath(): string
    {
        return $this->config('paths.assets');
    }
    public function config(string $key, $default = null)
    {

        return config()->get('templates.'.$key, $default);
    }



    public function scan()
    {
        $paths = $this->getScanPaths();

        $modules = [];

        foreach ($paths as $key => $path) {
            $manifests = $this->getFiles()->glob("{$path}/module.json");

            is_array($manifests) || $manifests = [];

            foreach ($manifests as $manifest) {
                $name = Json::make($manifest)->get('name');

                $modules[$name] = $this->createModule($this->app, $name, dirname($manifest));
            }
        }

        return $modules;
    }
//    public function getScanPaths(): array
//    {
//        $paths = $this->paths;
//
//        $paths[] = $this->getPath();
//
//        if ($this->config('scan.enabled')) {
//            $paths = array_merge($paths, $this->config('scan.paths'));
//        }
//
//        $paths = array_map(function ($path) {
//            return Str::endsWith($path, '/*') ? $path : Str::finish($path, '/*');
//        }, $paths);
//
//        return $paths;
//    }


//    public function config(string $key, $default = null)
//    {
//        dd($this->getConfig());
//        return $this->app->config->get('templates.'.$key, $default);
//    }
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

        $module = new LaravelTemplate($app, $moduleManifest['name'], $path);

        return $module;
    }

    public function findOrFail(string $name)
    {
        $module = $this->find($name);

        if ($module !== null) {
            return $module;
        }

        throw new ModuleNotFoundException("Module [{$name}] does not exist!");
    }
}
