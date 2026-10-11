<?php

namespace MicroweberPackages\LaravelModules\Activators;

use Illuminate\Cache\CacheManager;
use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Nwidart\Modules\Activators\FileActivator;
use Nwidart\Modules\Module;

class LaravelModulesFileActivator extends FileActivator
{

    public $configPrefix = 'modules';

    /**
     * Laravel cache instance
     *
     * @var CacheManager
     */
    private $cache;

    /**
     * Laravel Filesystem instance
     *
     * @var Filesystem
     */
    private $files;

    /**
     * Laravel config instance
     *
     * @var Config
     */
    private $config;

    /**
     * @var string
     */
    private $cacheKey;

    /**
     * @var string
     */
    private $cacheLifetime;

    /**
     * Array of modules activation statuses
     *
     * @var array
     */
    private $modulesStatuses;

    /**
     * File used to store activation statuses
     *
     * @var string
     */
    private $statusesFile;

    public function __construct(Container $app)
    {
        $this->cache = $app['cache'];
        $this->files = $app['files'];
        $this->config = app()->config;
        $this->statusesFile = $this->config('statuses-file');
        $this->cacheKey = $this->config('cache-key');
        $this->cacheLifetime = $this->config('cache-lifetime');
        $this->modulesStatuses = $this->getModulesStatuses();

    }

    /**
     * Get the path of the file where statuses are stored
     */
    public function getStatusesFilePath(): string
    {
        return $this->statusesFile;
    }

    /**
     * {@inheritDoc}
     */
    public function reset(): void
    {
        if ($this->files->exists($this->statusesFile)) {
            $this->files->delete($this->statusesFile);
        }
        $this->modulesStatuses = [];
        $this->flushCache();
    }

    /**
     * {@inheritDoc}
     */
    public function enable(Module $module): void
    {
        $this->setActiveByName($module->getName(), true);
    }

    /**
     * {@inheritDoc}
     */
    public function disable(Module $module): void
    {
        $this->setActiveByName($module->getName(), false);
    }

    /**
     * {@inheritDoc}
     */
    public function hasStatus(Module|string $module, bool $status): bool
    {
        $name = $module instanceof Module ? $module->getName() : $module;

        if (! isset($this->modulesStatuses[$name])) {
            return $status === false;
        }

        return $this->modulesStatuses[$name] === $status;
    }

    /**
     * {@inheritDoc}
     */
    public function setActive(Module $module, bool $active): void
    {
        $this->setActiveByName($module->getName(), $active);
    }

    /**
     * {@inheritDoc}
     */
    public function setActiveByName(string $name, bool $status): void
    {
        $this->flushCache();
        $this->modulesStatuses[$name] = $status;
        $this->writeJson();
        $this->flushCache();
    }

    /**
     * {@inheritDoc}
     */
    public function delete(Module $module): void
    {
        if (! isset($this->modulesStatuses[$module->getName()])) {
            return;
        }
        unset($this->modulesStatuses[$module->getName()]);
        $this->writeJson();
        $this->flushCache();
    }

    /**
     * Writes the activation statuses in a file, as json
     */
    private function writeJson(): void
    {


        $this->files->put($this->statusesFile, json_encode($this->modulesStatuses, JSON_PRETTY_PRINT));
    }

    /**
     * Reads the json file that contains the activation statuses.
     *
     * @throws FileNotFoundException
     */
    private function readJson(): array
    {
        if (! $this->files->exists($this->statusesFile)) {
            return [];
        }
        return json_decode($this->files->get($this->statusesFile), true);
    }

    /**
     * Get modules statuses, either from the cache or from
     * the json statuses file if the cache is disabled.
     *
     * @throws FileNotFoundException
     */

    static $jsonMemoryCache = [];
    private function getModulesStatuses(): array
    {
        if(self::$jsonMemoryCache){

            return self::$jsonMemoryCache;
        }

        self::$jsonMemoryCache = $this->readJson();
        return self::$jsonMemoryCache;
//        if (! $this->config->get($this->configPrefix.'.cache.enabled')) {
//            return $this->readJson();
//        }
//
//        return $this->cache->store($this->config->get($this->configPrefix.'.cache.driver'))->remember($this->cacheKey, $this->cacheLifetime, function () {
//
//            return $this->readJson();
//        });
    }

    /**
     * Reads a config parameter under the 'activators.file' key
     *
     * @return mixed
     */
    private function config(string $key, $default = null)
    {
        return $this->config->get($this->configPrefix.'.activators.file.'.$key, $default);
    }



    private function flushCache(): void
    {

        if(app()->bound('modules')) {
            app('modules')->flushCache();
        }
        if(app()->bound('templates')) {
            app('templates')->flushCache();
        }
        $this->cache->store($this->config->get($this->configPrefix.'.cache.driver'))->forget($this->cacheKey);
    }
}
