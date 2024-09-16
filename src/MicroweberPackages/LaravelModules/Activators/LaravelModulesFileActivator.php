<?php

namespace MicroweberPackages\LaravelModules\Activators;

use Illuminate\Cache\CacheManager;
use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Nwidart\Modules\Activators\FileActivator;

class LaravelModulesFileActivator extends FileActivator
{

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


    public function setActiveByName(string $name, bool $status): void
    {
        parent::setActiveByName($name, $status);

        $this->flushCache();
    }
    private function flushCache(): void
    {

        $this->cache->store($this->config->get('modules.cache.driver'))->forget($this->cacheKey);
    }
}
