<?php

namespace MicroweberPackages\LaravelModules\Repositories;

use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Traits\Macroable;
use MicroweberPackages\LaravelModules\Helpers\StaticModuleCreator;
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


    public function __construct(Container $app, $path = null)
    {
        $this->app = $app;
        $this->path = $path;
        $this->url = $app['url'];
        $this->config = $app['config'];
        $this->files = $app['files'];
        $this->cache = $app['cache'];


    }


    protected function createModule(...$args)
    {

        return StaticModuleCreator::createModule(...$args);
//        $app = $args[0];
//        $name = $args[1];
//        $path = $args[2];
//
//
//         start_measure('module_create_' . $name, 'Create module ' . $name);
//
//
//
//
//        $manifest = $path . DS . 'module.json';
//        $composer = $path . DS . 'composer.json';
//        if (!$path) {
//            return null;
//        }
//        if (!is_dir($path)) {
//            return null;
//        }
//        if (!is_file($manifest)) {
//            return null;
//        }
//
//        if (is_file($composer)) {
//            self::registerNamespacesFromComposer($composer);
//        }
//
//        $module = new \Nwidart\Modules\Laravel\Module ($app, $name, $path);
//     //   $this->memory[$name] = $module;
//        stop_measure('module_create_' . $name);
//        return $module;
    }

    public static $registeredComposerFiles = [];

    public static function registerNamespacesFromComposer($composer)
    {
        if (in_array($composer, self::$registeredComposerFiles)) {
            return;
        }
        self::$registeredComposerFiles[] = $composer;


        $path = dirname($composer);
        $moduleComposer = Json::make($composer)->getAttributes();


        $autoloadNamespaces = $moduleComposer['autoload']['psr-4'] ?? [];
        $autoloadFiles = $moduleComposer['autoload']['files'] ?? [];

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


    public function find(string $name)
    {


        foreach ($this->all() as $module) {
            /** @var \Nwidart\Modules\Laravel\Module $module */
            if ($module->getLowerName() === strtolower($name)) {
                return $module;
            }
        }

    }

    public function config(string $key, $default = null)
    {

        return $this->config->get('modules.' . $key, $default);
    }

    public $memory = [];

    public function all(): array
    {
        $enabledCache = $this->config['modules']['cache']['enabled'] ?? false;

        if (!$enabledCache) {

            return $this->scan();
        }


        return $this->formatCached($this->getCached());
    }


    protected function formatCached($cached)
    {

        $modules = [];

        foreach ($cached as $name => $module) {


            $path = $module['path'];

            $modules[$name] = $this->createModule($this->app, $name, $path);
            //   $this->memory[$name] = $modules[$name];

//            if (isset($this->memory[$name])) {
//                $modules[$name] = $this->memory[$name];
//
//            } else {
//
//                $path = $module['path'];
//
//                $modules[$name] = $this->createModule($this->app, $name, $path);
//                $this->memory[$name] = $modules[$name];
//            }
        }
        return $modules;
    }

    public function getFiles(): Filesystem
    {

        return $this->files;
    }

    public function getCached()
    {
        return $this->cache->store($this->config->get('modules.cache.driver'))->remember($this->config('cache.key'), $this->config('cache.lifetime'), function () {
            return $this->toCollection()->toArray();
        });
    }

    public $scanMemory = [];

    public function scan()
    {
        if ($this->scanMemory) {
            return $this->scanMemory;
        }

        $paths = $this->getScanPaths();

        $modules = [];

        if (!$this->getFiles()) {
            return [];
        }


        foreach ($paths as $key => $path) {

            $manifests = $this->getFiles()->glob("{$path}/module.json");

            is_array($manifests) || $manifests = [];

            foreach ($manifests as $manifest) {


                $name = Json::make($manifest)->get('name');

                $modules[$name] = $this->createModule($this->app, $name, dirname($manifest));
            }
        }
        $this->scanMemory = $modules;
        return $modules;
    }
}
