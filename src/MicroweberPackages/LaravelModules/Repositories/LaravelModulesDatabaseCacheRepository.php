<?php

namespace MicroweberPackages\LaravelModules\Repositories;


use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Traits\Macroable;
use Nwidart\Modules\Collection;
use Nwidart\Modules\Json;

class LaravelModulesDatabaseCacheRepository extends AbstractLaravelModulesRepository
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

    public function config(string $key, $default = null)
    {

        return $this->config->get('modules.' . $key, $default);
    }

    public function getFiles(): Filesystem
    {

        return $this->files;
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
                $providers = $module['providers'];

                if(empty($providers)){
                    continue;
                }

                $moduleJson['providers'] = $providers;
                $modulePath = $path;

                $providersFound = $this->providersFromModuleJsonExists($modulePath,$moduleJson);
               if(!$providersFound){
                     continue;
               }



                $modules[$name] = $this->createModule($this->app, $name, $path);
                self::$cachedModules[$name] = $modules[$name];
            }

        }
        return $modules;
    }

    public static $modulesCollectionCache = [];
    public function toCollection(): Collection
    {

        if(!empty(self::$modulesCollectionCache)){

            return self::$modulesCollectionCache;
        }


        $modules = [];
        $modulesFromDatabase = app()->module_repository->getModulesByType('laravel-module');

        if ($modulesFromDatabase) {
            foreach ($modulesFromDatabase as $module) {
                $moduleItem = [];

                if (isset($module['settings']) and is_array($module['settings'])) {
                    $composerAutoload = $module['settings']['composer_autoload'] ?? null;
                    $moduleJson = $module['settings']['module_json'] ?? null;
                    if (!$moduleJson) {
                        continue;
                    }
                    $modulePath = null;
                    $modulePathFull = $module['settings']['module_path'] ?? null;
                    $modulePathRelativeFromBasePath = $module['settings']['module_path_relative'] ?? null;
                    $name = $moduleJson['name'] ?? null;

                    if ($modulePathRelativeFromBasePath) {
                        $modulePathRelative = base_path($modulePathRelativeFromBasePath);
                        if (is_dir($modulePathRelative)) {
                            $modulePath = $modulePathRelative;
                        }
                    }
                    if (!$modulePath and $modulePathFull) {
                        if (is_dir($modulePathFull)) {
                            $modulePath = $modulePathFull;
                        }
                    }

                    if (!$modulePath) {
                        continue;
                    }
                    if (!$name) {
                        continue;
                    }

                    $providersNotFound = $this->providersFromModuleJsonExists($modulePath,$moduleJson);

                    if (!$providersNotFound) {
                        continue;
                    }


                    $module = $this->createModule($this->app, $name, $modulePath, $moduleJson, $composerAutoload);
                    if ($module) {
                        $modules[$name] = $module;
                    }

                }
                // $modules[$module->name] = $module->toArray();
            }
        }

        self::$modulesCollectionCache = new Collection($modules);
        return self::$modulesCollectionCache;
    }


    public function getCached()
    {





        return $this->cache->store($this->config->get('modules.cache.driver'))->remember($this->config('cache.key'), $this->config('cache.lifetime'), function () {
            return $this->toCollection()->toArray();
        });
    }


    public function providersFromModuleJsonExists($modulePath, $moduleJson)
    {
        $providersNotFound = false;
        if (isset($moduleJson['providers']) and is_array($moduleJson['providers'])) {
            foreach ($moduleJson['providers'] as $provider) {
                $providerFilename = basename($provider . '.php');

                $provider = $modulePath . DS . 'app/Providers' . DS . $providerFilename;

                if (!is_file($provider)) {

                    $providersNotFound = true;
                }


            }
        }

        return !$providersNotFound;
    }

}
