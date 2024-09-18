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

class LaravelModulesCacheRepository
{
    public $cache = [];



    public function flushCache()
    {
        $this->cache = [];
    }


    public function set($name, $module)
    {

        $this->cache[$name] = $module;
    }

    public function get($name)
    {

        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }
    }

    public function all()
    {

        return $this->cache;
    }

    public function flush()
    {
        $this->cache = [];
    }
}
