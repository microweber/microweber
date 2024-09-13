<?php

namespace MicroweberPackages\LaravelModules\Repositories;

use Barryvdh\Debugbar\Facades\Debugbar;
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

    public function register(): void
    {
      //  Debugbar::startMeasure('module_register', 'Registering modules');
        $modules = $this->getOrdered();

        foreach ($modules as $module) {
            $module->register();
        }
     //   Debugbar::stopMeasure('module_register');
    }

    /**
     * {@inheritDoc}
     */
    public function boot(): void
    {
       // Debugbar::startMeasure('module_boot', 'Booting modules');
        $modules = $this->getOrdered();

        foreach ($modules as $module) {
            $module->boot();
        }
       // Debugbar::stopMeasure('module_boot');
    }


    protected function createModule(...$args)
    {

        $app = $args[0];
        $name = $args[1];
        $path = $args[2];
        if (isset(self::$cachedModules[$name])) {

            return self::$cachedModules[$name];
        }


        return StaticModuleCreator::createModule(...$args);

    }



    public function getByStatus($status): array
    {
        $modules = [];
        $all = $this->all();
        /** @var \Nwidart\Modules\Laravel\Module $module */
        foreach ($all as $name => $module) {
            if ($module->isStatus($status)) {
                $modules[$name] = $module;
            }
        }

        return $modules;
    }


    public function find(string $name)
    {
        if (isset(self::$cachedModules[$name])) {

            return self::$cachedModules[$name];
        }


        $all = $this->all();
        foreach ($all as $module) {
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


    public function all(): array
    {
        $enabledCache = $this->config['modules']['cache']['enabled'] ?? false;

        if (!$enabledCache) {
            return $this->scan();
        }

        return $this->formatCached($this->getCached());
    }


    public static $cachedModules = [];

    protected function formatCached($cached)
    {

        $modules = [];

        foreach ($cached as $name => $module) {

            if (isset(self::$cachedModules[$name])) {
                $module = self::$cachedModules[$name];
                $modules[$name] = $module;
            } else {
                $path = $module['path'];
                $modules[$name] = $this->createModule($this->app, $name, $path);
                self::$cachedModules[$name] = $modules[$name];
            }

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
