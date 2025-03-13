<?php

namespace MicroweberPackages\LaravelModules\Activators;

use Illuminate\Cache\CacheManager;
use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Schema\Builder as Schema;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Log\LogManager;
use Illuminate\Support\Str;
use MicroweberPackages\LaravelModules\Models\SystemModules;
use Nwidart\Modules\Contracts\ActivatorInterface;
use Nwidart\Modules\Json;
use Nwidart\Modules\Module;

class LaravelModulesDatabaseActivator implements ActivatorInterface
{
    /**
     * Configuration prefix.
     *
     * @var string
     */
    public $configPrefix = 'modules';

    /**
     * Application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Laravel cache instance.
     *
     * @var CacheManager
     */
    private $cache;

    /**
     * Laravel database connection.
     *
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * Laravel config instance.
     *
     * @var Config
     */
    private $config;

    /**
     * Laravel log instance.
     *
     * @var LogManager
     */
    private $logger;

    /**
     * @var string
     */
    private $cacheKey;

    /**
     * @var string
     */
    private $cacheLifetime;

    /**
     * Array of modules activation statuses.
     *
     * @var array
     */
    private $modulesStatuses;

    /**
     * Database table name.
     *
     * @var string
     */
    private $table;

    /**
     * Create a new DatabaseActivator instance.
     *
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->cache = $app['cache'];
        $this->connection = $app['db.connection'];
        $this->config = $app['config'];
        $this->logger = $app['log'];
        $this->table = $this->config('table', 'system_modules');

        $this->setModelConnection();


        $this->cacheKey = $this->config('cache-key', 'modules.activations');
        $this->cacheLifetime = $this->config('cache-lifetime', 604800);
        $this->modulesStatuses = $this->getModulesStatuses();


    }

    /**
     * Get the path of the table where statuses are stored.
     *
     * @return string
     */
    public function getTableName(): string
    {
        return $this->table;
    }

    /**
     * {@inheritDoc}
     */
    public function reset(): void
    {
        if (!empty($this->getTableName())) {
            SystemModules::query()->update(['status' => 0]);
        }
        $this->modulesStatuses = [];
        $this->flushCache();
    }

    /**
     * {@inheritDoc}
     */
    public function enable(Module $module): void
    {
        $this->setActive($module, true);
    }

    /**
     * {@inheritDoc}
     */
    public function disable(Module $module): void
    {
        $this->setActive($module, false);
    }

    /**
     * {@inheritDoc}
     */
    public function hasStatus(Module|string $module, bool $status): bool
    {
        $name = $module instanceof Module ? $module->getName() : $module;

        if (!isset($this->modulesStatuses[$name])) {
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
        $this->syncModule([$module]);
    }

    /**
     * {@inheritDoc}
     */
    public function setActiveByName(string $name, bool $status): void
    {
        $this->flushCache();
        $this->modulesStatuses[$name] = $status;
        $this->writeStatus();
        $this->flushCache();
    }

    /**
     * {@inheritDoc}
     */
    public function delete(Module $module): void
    {
        if (!isset($this->modulesStatuses[$module->getName()])) {
            return;
        }
        unset($this->modulesStatuses[$module->getName()]);
        $this->syncModule([$module]);
        $this->writeStatus();
        $this->flushCache();
    }

    /**
     * Writes the activation statuses to the database.
     */
    private function writeStatus(): void
    {
        if (empty($this->getTableName())) {
            return;
        }


        // Reset all modules to inactive
        SystemModules::query()->update(['status' => 0]);

        // Update active modules
        foreach ($this->modulesStatuses as $name => $status) {
            if ($status === true) {
                $moduleActivation = SystemModules::getByName($name);
                if ($moduleActivation) {
                    $moduleActivation->status = $status;
                    $moduleActivation->save();
                }
            }
        }


    }

    /**
     * Reads the database to get the activation statuses.
     *
     * @return array
     */
    private function readStatus(): array
    {
        $statuses = [];
        if (empty($this->getTableName())) {
            return $statuses;
        }


        $modules = SystemModules::all();

        foreach ($modules as $module) {
            $statuses[$module->name] = (bool)$module->status;
        }

        if (empty($statuses)) {
            $all_enabled_modules = $this->scanJson();

            $this->syncModule($all_enabled_modules);

            if (count($all_enabled_modules) > 0) {
                return $this->readStatus();
            }
        }


        return $statuses;
    }

    /**
     * Get modules statuses, either from the cache or from
     * the database if the cache is disabled.
     *
     * @return array
     */
    private function getModulesStatuses(): array
    {

        if (!$this->config->get($this->configPrefix . '.cache.enabled')) {
            return $this->readStatus();
        }

        return $this->cache->store($this->config->get($this->configPrefix . '.cache.driver'))
            ->remember($this->cacheKey, $this->cacheLifetime, function () {
                return $this->readStatus();
            });
    }

    /**
     * Reads a config parameter under the 'activators.database' key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    private function config(string $key, $default = null)
    {
        return $this->config->get($this->configPrefix . '.activators.database.' . $key, $default);
    }

    /**
     * Flushes the modules activation statuses cache.
     */
    private function flushCache(): void
    {
        if (app()->bound('modules')) {
            app('modules')->flushCache();
        }
        if (app()->bound('templates')) {
            app('templates')->flushCache();
        }
        $this->cache->store($this->config->get($this->configPrefix . '.cache.driver'))->forget($this->cacheKey);
    }

    /**
     * Insert module info into database.
     *
     * @param array $modules
     */
    private function syncModule(array $modules): void
    {
        if (empty($this->getTableName())) {
            return;
        }



            foreach ($modules as $key => $module) {
                if ($module instanceof Module) {
                    $moduleData = array_merge(
                        collection_to_array($module->json()->getAttributes()),
                        [
                            'version' => $module->get('version'),
                            'type' => $module->get('type', 1),
                            'path' => $module->getPath()
                        ]
                    );

                    $moduleActivation = SystemModules::getByName($module->getName());
                    if (!$moduleActivation) {
                        $moduleActivation = new SystemModules();
                    }

                    $moduleActivation->fill([
                        'name' => $module->getName(),
                        'alias' => $module->getLowerName(),
                        'description' => $module->getDescription(),
                        'path' => $module->getPath(),
                        'version' => $module->get('version', 'dev'),
                        'type' => $module->get('type', '1'),
                        'priority' => $module->get('priority', '1024'),
                        'sort' => $module->get('order', '0'),
                        'status' => isset($this->modulesStatuses[$module->getName()]) ?
                            $this->modulesStatuses[$module->getName()] : 0
                    ]);

                    $moduleActivation->save();
                }
            }


    }

    protected function setModelConnection(): void
    {
        SystemModules::setConnectionResolver($this->app['db']);
        SystemModules::resolveConnection($this->connection->getName());
    }
    
    /**
     * Scan for module.json files without using the module repository.
     * This prevents recursive loops when the activator is called during module scanning.
     *
     * @return array
     */
    public function scanJson(): array
    {
        $modules = [];
        $paths = $this->app['config']->get($this->configPrefix . '.paths', []);
        
        $files = new Filesystem();
        
        foreach ($paths as $path) {
            $manifests = $files->glob("{$path}/*/module.json");
            
            is_array($manifests) || $manifests = [];
            
            foreach ($manifests as $manifest) {
                $name = Json::make($manifest)->get('name');
                
                if ($name) {
                    $modules[$name] = $this->app->make('modules.factory')->make(
                        $name,
                        dirname($manifest)
                    );
                }
            }
        }
        
        if ($modules) {
            uasort($modules, function (Module $a, Module $b) {
                if ($a->get('priority') === $b->get('priority')) {
                    return 0;
                }
                
                return $a->get('priority') > $b->get('priority') ? 1 : -1;
            });
        }
        
        return $modules;
    }
}
