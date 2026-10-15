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
    public $cache;

    /**
     * Laravel database connection.
     *
     * @var ConnectionInterface
     */
    public $connection;

    /**
     * Laravel config instance.
     *
     * @var Config
     */
    public $config;

    /**
     * Laravel log instance.
     *
     * @var LogManager
     */
    public $logger;

    /**
     * @var string
     */
    public $cacheKey;

    /**
     * @var string
     */
    public $cacheLifetime;

    /**
     * Array of modules activation statuses.
     *
     * @var array
     */
    public $modulesStatuses;

    /**
     * Database table name.
     *
     * @var string
     */
    public $table;

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
    public function writeStatus(): void
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
    public function readStatus(): array
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
            $seeded = $this->seedFromManifests();

            if ($seeded > 0) {
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
    public function getModulesStatuses(): array
    {

        if(function_exists('mw_is_installed') && !mw_is_installed()){
            return [];
        }

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
    public function config(string $key, $default = null)
    {
        return $this->config->get($this->configPrefix . '.activators.database.' . $key, $default);
    }

    /**
     * Flushes the modules activation statuses cache.
     */
    public function flushCache(): void
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
    public function syncModule(array $modules): void
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
                            'path' => $module->getRelativePath()
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
                        'path' => $module->getRelativePath(),
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
    /**
     * Seed system_modules from the on-disk module.json manifests when the table
     * is empty (fresh site / first switch to the database activator).
     *
     * Reads the manifests DIRECTLY — no module factory, no Module objects, no
     * ActivatorInterface resolution — so it is safe to run from this activator's
     * own constructor. (Building a Nwidart Module here would resolve
     * ActivatorInterface, which is mid-construction → a circular dependency.
     * The previous implementation also called an unbound 'modules.factory',
     * which crashed boot outright.)
     *
     * Each module seeds with the current file-activator status when a statuses
     * file exists (so switching activators preserves the enabled set), otherwise
     * with its manifest "active" flag (default enabled) — matching a fresh
     * file-activator install. Returns the number of modules seeded.
     */
    protected function seedFromManifests(): int
    {
        if (empty($this->getTableName())) {
            return 0;
        }

        $modulesPath = $this->app['config']->get($this->configPrefix . '.paths.modules');
        if (!$modulesPath || !is_dir($modulesPath)) {
            return 0;
        }

        $files = new Filesystem();
        $manifests = $files->glob(
            rtrim($modulesPath, '/\\') . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . 'module.json'
        );
        if (!is_array($manifests) || empty($manifests)) {
            return 0;
        }

        // Preserve the current file-activator state when available.
        $fileStatuses = [];
        $statusesFile = $this->app['config']->get($this->configPrefix . '.activators.file.statuses-file');
        if ($statusesFile && is_file($statusesFile)) {
            $decoded = json_decode((string) file_get_contents($statusesFile), true);
            if (is_array($decoded)) {
                $fileStatuses = $decoded;
            }
        }

        $count = 0;
        foreach ($manifests as $manifest) {
            $json = Json::make($manifest);
            $name = $json->get('name');
            if (!$name) {
                continue;
            }

            $status = array_key_exists($name, $fileStatuses)
                ? (bool) $fileStatuses[$name]
                : (bool) $json->get('active', 1);

            $row = SystemModules::getByName($name) ?: new SystemModules();
            $row->fill([
                'name' => $name,
                'alias' => (string) ($json->get('alias') ?: strtolower($name)),
                'description' => (string) $json->get('description', ''),
                'path' => dirname($manifest),
                'version' => (string) $json->get('version', 'dev'),
                'type' => (string) $json->get('type', '1'),
                'priority' => (string) $json->get('priority', '1024'),
                'sort' => (string) $json->get('order', '0'),
                'status' => (int) $status,
            ]);
            $row->save();
            $count++;
        }

        return $count;
    }
}
