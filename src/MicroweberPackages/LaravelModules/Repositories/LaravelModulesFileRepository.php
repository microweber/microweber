<?php

namespace MicroweberPackages\LaravelModules\Repositories;

use Arcanedev\Html\Elements\P;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Traits\Macroable;
use MicroweberPackages\Cache\CacheFileHandler\Facades\Cache;
use MicroweberPackages\LaravelModules\Helpers\StaticModuleCreator;
use MicroweberPackages\LaravelModules\LaravelModule;
use MicroweberPackages\LaravelModules\Traits\ModulesRepositoryTrait;
use Nwidart\Modules\Collection;
use Nwidart\Modules\FileRepository;
use Nwidart\Modules\Json;
use Nwidart\Modules\Module;
use Nwidart\Modules\Process\Installer;
use Nwidart\Modules\Process\Updater;

class LaravelModulesFileRepository extends FileRepository
{

    use ModulesRepositoryTrait;

    public $configPrefix = 'modules';


    public function register(): void
    {
        //  Debugbar::startMeasure('module_register', 'Registering modules');
        $modules = $this->getOrdered();

        foreach ($modules as $module) {
            /** @var LaravelModule $module */
            $module->register();
        }
        //   Debugbar::stopMeasure('module_register');
    }

    public function getOrderedModules($direction = 'asc'): array
    {
        $modules = $this->allEnabled();

        if ($direction == 'desc') {
            $modules = array_reverse($modules);
        }


        return $modules;
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
        if (!$enabledCache) {
            return $this->scan();
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
        $cacheAll = $this->cacheRepository->all();

        if (!empty($cacheAll)) {

            return $cacheAll;
        }


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

//    public function enable($name)
//    {
//
//        $this->flushCache();
//        parent::enable($name);
//    }
//
//    /**
//     * {@inheritDoc}
//     */
//    public function delete(string $name): bool
//    {
//        $this->flushCache();
//        return parent::delete($name);
//    }
//
//    /**
//     * Update dependencies for the specified module.
//     *
//     * @param string $module
//     */
//    public function update($module)
//    {
//        $this->flushCache();
//        parent::update($module);
//    }
//
//    /**
//     * Install the specified module.
//     *
//     * @param string $name
//     * @param string $version
//     * @param string $type
//     * @param bool $subtree
//     * @return \Symfony\Component\Process\Process
//     */
//    public function install($name, $version = 'dev-master', $type = 'composer', $subtree = false)
//    {
//
//        $this->flushCache();
//        return parent::install($name, $version, $type, $subtree);
//    }

    public function flushCache()
    {
        self::$cachedModules = [];
        $this->scanMemory = [];
        $this->cache->store($this->config->get($this->configPrefix . '.cache.driver'))->forget($this->config('cache.key'));
        $this->cacheRepository->flush();
    }
}
