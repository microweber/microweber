<?php

namespace MicroweberPackages\LaravelModules\Traits;

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use MicroweberPackages\LaravelModules\Helpers\StaticModuleCreator;
use MicroweberPackages\LaravelModules\LaravelModule;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesCacheRepository;
use Nwidart\Modules\Collection;
use Nwidart\Modules\Exceptions\ModuleNotFoundException;
use Nwidart\Modules\Json;
use Nwidart\Modules\Module;

trait ModulesRepositoryTrait
{
    use Macroable;

  // public $configPrefix = 'modules';


    /**
     * @var LaravelModulesCacheRepository
     */
    public $cacheRepository;
    public $memoryCacheDisabled = false;

    public function register(): void
    {
       // $this->configPrefix = 'modules';
        Debugbar::startMeasure('module_register', 'Registering modules');
        $modules = $this->getOrdered();

        foreach ($modules as $module) {
            /** @var LaravelModule $module */
            $module->register();
        }
        Debugbar::stopMeasure('module_register');
    }

    public function setConfigPrefix($prefix)
    {
        $this->configPrefix = $prefix;
    }


    public function getOrderedModules($direction = 'asc'): array
    {
        $modules = $this->allEnabled();

        if ($direction == 'desc') {
            $modules = array_reverse($modules);
        }


        return $modules;
    }
    /**
     * Get list of enabled modules.
     */
    public function allEnabled(): array
    {
        return $this->getByStatus(true);
    }

    /**
     * Get list of disabled modules.
     */
    public function allDisabled(): array
    {
        return $this->getByStatus(false);
    }
    public function getOrdered($direction = 'asc'): array
    {

        return $this->getOrderedModules($direction);
    }

    /**
     * {@inheritDoc}
     */
    public function boot(): void
    {
        Debugbar::startMeasure('module_boot', 'Booting modules');
        $modules = $this->getOrdered();

        foreach ($modules as $module) {
            /** @var LaravelModule $module */

            $module->boot();
        }
        Debugbar::stopMeasure('module_boot');
    }


    protected function createModule(...$args)
    {

        $app = $args[0];
        $name = $args[1];
        $path = $args[2];


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


        $all = $this->all();
        if(isset($all[$name])){
            return $all[$name];
        }


        foreach ($all as $module) {
            /** @var LaravelModule $module */
            if ($module->getLowerName() === strtolower($name)) {
                return $module;
            }
        }

    }

    public function config(string $key, $default = null)
    {

        return $this->config->get($this->configPrefix . '.' . $key, $default);
    }


    public function all(): array
    {


        $enabledCache = $this->config[$this->configPrefix]['cache']['enabled'] ?? false;

        if ($this->memoryCacheDisabled) {
            $enabledCache = false;
        }

        if (!$enabledCache) {
            return $this->scan();
        }
        // return $this->scan();
        $allCached = $this->cacheRepository->all();
        if ($allCached) {
            return $this->cacheRepository->all();
        }


        if (!empty(self::$cachedModules)) {
            //    return self::$cachedModules;
        }
        start_measure('all', 'all');

        $cachedJsonFileForAllModules = [];
        $hasCached = $this->getCached();

        stop_measure('all');
        return $this->formatCached($hasCached);
    }


    public static $cachedModules = [];

    protected function formatCached($cached)
    {
//        $cacheAll = $this->cacheRepository->all();
//
//        if (!empty($cacheAll)) {
//
//            return $cacheAll;
//        }


        start_measure('creating_modules', 'creating_modules');
        $modules = [];
        foreach ($cached as $name => $module) {


            $cache = $this->cacheRepository->get($name);
            if ($cache) {
                $modules[$name] = $cache;
                continue;
            }

//            if (isset(self::$cachedModules[$name])) {
//
//
//                $modules[$name] = self::$cachedModules[$name];
//
//            } else {


            $path = $module['path'];
            $moduleJsonFileContent = $module;
            $composerAutoloadContent = [];
            if (isset($module['composer']) and isset($module['composer']['autoload']) and !empty($module['composer']['autoload'])) {
                $composerAutoloadContent = $module['composer']['autoload'];
            }
            if (isset($module['composer']) and isset($module['composer']['autoload-dev']) and !empty($module['composer']['autoload-dev'])) {
                $composerAutoloadContent = array_merge_recursive($composerAutoloadContent, $module['composer']['autoload-dev']);
            }


            $moduleCreate = $this->createModule($this->app, $name, $path, $moduleJsonFileContent, $composerAutoloadContent);
            if ($moduleCreate) {

                $modules[$name] = $moduleCreate;
                $this->cacheRepository->set($name, $moduleCreate);
                //self::$cachedModules[$name] = $moduleCreate;
            }
            //  }

        }
        //    self::$cachedModules = $modules;
        stop_measure('creating_modules');
        return $modules;
    }

    public function getFiles(): Filesystem
    {

        return $this->files;
    }

    public function toCollection(): Collection
    {

        return new Collection($this->scan());
    }


    public function getCached()
    {


        return $this->cache->store($this->config->get($this->configPrefix . '.cache.driver'))
            ->remember($this->config('cache.key'), $this->config('cache.lifetime'), function () {
                $arr = [];
                $modules = $this->toCollection();

                foreach ($modules as $key => $module) {
                    $moduleArr = $module->json('module.json')->getAttributes();

                    $composerJson = $module->json('composer.json')->getAttributes();

                    $moduleArr['composer'] = $composerJson;
                    $moduleArr['path'] = $module->getPath();
                    $arr[$key] = $moduleArr;
                }

                return $arr;
            });

    }

    public $scanMemory = [];

    public function scan()
    {
        if ($this->scanMemory) {

            //    return $this->scanMemory;
        }
$this->flushCache();
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


        if ($modules) {
            $direction = 'asc';
            uasort($modules, function (Module $a, Module $b) use ($direction) {
                if ($a->get('priority') === $b->get('priority')) {
                    return 0;
                }

                if ($direction === 'desc') {
                    return $a->get('priority') < $b->get('priority') ? 1 : -1;
                }

                return $a->get('priority') > $b->get('priority') ? 1 : -1;
            });
        }

        //$this->scanMemory = $modules;
        return $modules;
    }

    public function findOrFail(string $name)
    {
        $module = $this->find($name);

        if ($module !== null) {
            return $module;
        }

        $this->flushCache();
        $this->memoryCacheDisabled = true;
        //  $hasCached = $this->getCached();
        $module = $this->find($name);
        if ($module) {
            return $module;
        }


        throw new ModuleNotFoundException("Module [{$name}] does not exist!");
    }

    public function getModulePath($module)
    {
        try {
            return $this->findOrFail($module)->getPath() . '/';
        } catch (ModuleNotFoundException $e) {
            return $this->getPath() . '/' . Str::studly($module) . '/';
        }
    }

    public function flushCache()
    {

        self::$cachedModules = [];
        $this->scanMemory = [];
        $this->cache->store($this->config->get($this->configPrefix . '.cache.driver'))->forget($this->config('cache.key'));
        $this->cacheRepository->flush();
    }

}
