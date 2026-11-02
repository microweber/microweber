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
     * Whether $modulesStatuses has been loaded yet. Loading is DEFERRED out of
     * the constructor (see ensureStatusesLoaded()) so building this activator
     * never reads the database or the module manifests. That is what keeps the
     * ActivatorInterface singleton cheap to construct and breaks the historical
     * circular dependency: a Nwidart Module resolves ActivatorInterface in its
     * own constructor, so any status computation that touched the module factory
     * while THIS object was still being built re-entered a half-constructed
     * singleton (and hit an unbound 'modules.factory' early in boot). Deferring
     * the load means it runs on first access, after the container is booted.
     *
     * @var bool
     */
    protected $statusesLoaded = false;

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

        // NOTE: statuses are intentionally NOT loaded here — see
        // ensureStatusesLoaded() and the $statusesLoaded docblock. Loading lazily
        // is what removes the circular modules.factory dependency.
    }

    /**
     * Load the activation statuses on first use. Deferred out of the constructor
     * so constructing the activator performs no DB / manifest / module-factory
     * work (the source of the old circular dependency). Safe to call repeatedly.
     */
    protected function ensureStatusesLoaded(): void
    {
        if ($this->statusesLoaded) {
            return;
        }

        $this->modulesStatuses = $this->getModulesStatuses();
        $this->statusesLoaded = true;
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
        $this->statusesLoaded = true;
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
        $this->ensureStatusesLoaded();

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
        $this->ensureStatusesLoaded();
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
        $this->ensureStatusesLoaded();
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

        $this->ensureStatusesLoaded();

        // Reset all modules to inactive
        SystemModules::query()->update(['status' => 0]);

        // Update active modules
        foreach ((array) $this->modulesStatuses as $name => $status) {
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

        // Resilience: if the status table isn't present (fresh/partial install, or
        // an install-order gap under the per-site database activator), fall back
        // to the on-disk manifest defaults instead of 500-ing.
        if (!$this->statusTableExists()) {
            return $this->manifestStatuses();
        }


        $modules = SystemModules::all();

        foreach ($modules as $module) {
            $statuses[$module->name] = (bool)$module->status;
        }

        // Seed the table for any on-disk module that has no row yet. The table
        // used to self-seed only when COMPLETELY empty, so a bundled module
        // shipped AFTER the first seed never got a row — and an absent module is
        // treated as disabled (see hasStatus()). On a database-activator
        // (multisite) site that silently disabled every newly-added module.
        // seedFromManifests() is insert-only, so existing rows (an admin's own
        // enable/disable choices) are preserved; only the missing modules are
        // added, with their manifest "active" default (enabled). Covers both the
        // empty-table (fresh install) and partial-table (module added later)
        // cases in one pass.
        $onDisk = array_keys($this->manifestStatuses());
        $missing = array_diff($onDisk, array_keys($statuses));

        if (!empty($missing)) {
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

        $this->ensureStatusesLoaded();



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
     * Seed system_modules from the on-disk module.json manifests for any module
     * that has no row yet (fresh site, first switch to the database activator, or
     * a module shipped after the first seed).
     *
     * Reads the manifests DIRECTLY — no module factory, no Module objects, no
     * ActivatorInterface resolution. Status loading is already deferred out of the
     * constructor (ensureStatusesLoaded()), so this runs after the container is
     * booted; reading manifests directly keeps it independent of the module
     * factory as a second line of defence. Historically the inverse was true:
     * building a Nwidart Module here resolved ActivatorInterface while this very
     * singleton was still constructing → a circular dependency, and early in boot
     * 'modules.factory' was not even bound yet → the boot crashed outright.
     *
     * Each module seeds with the current file-activator status when a statuses
     * file exists (so switching activators preserves the enabled set), otherwise
     * with its manifest "active" flag (default enabled) — matching a fresh
     * file-activator install. Returns the number of modules seeded.
     */
    /**
     * Whether the status table physically exists (guards a fresh/partial install
     * where the activator runs before its table is created).
     */
    protected function statusTableExists(): bool
    {
        if (empty($this->getTableName())) {
            return false;
        }
        try {
            return \Illuminate\Support\Facades\Schema::connection($this->connection->getName())
                ->hasTable($this->getTableName());
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * On-disk default statuses from the module manifests (name => active flag,
     * default enabled). Safe fallback when the status table is unavailable.
     *
     * @return array<string, bool>
     */
    protected function manifestStatuses(): array
    {
        $statuses = [];
        foreach ($this->manifestFiles() as $manifest) {
            $json = Json::make($manifest);
            $name = $json->get('name');
            if ($name) {
                $statuses[$name] = (bool) $json->get('active', 1);
            }
        }

        return $statuses;
    }

    /**
     * Every root that may hold a manifest: the primary modules/templates root
     * PLUS the extra drop-in scan roots (ExtraModules/…, ExtraTemplates/…). This
     * mirrors the folders the scanner walks, so manifest-based seeding covers
     * every discoverable module/template — not only those under the primary root
     * — while still reading straight from disk: no scanner, no module factory, no
     * Module objects, hence no re-entrancy into this activator. configPrefix is
     * 'modules' or 'templates', so one implementation serves both.
     *
     * @return array<int, string>
     */
    protected function manifestRoots(): array
    {
        $roots = [];

        $primary = $this->app['config']->get($this->configPrefix . '.paths.modules');
        if ($primary) {
            $roots[] = $primary;
        }

        if ($this->app['config']->get($this->configPrefix . '.scan.enabled')) {
            foreach ((array) $this->app['config']->get($this->configPrefix . '.scan.paths', []) as $extra) {
                if ($extra) {
                    $roots[] = $extra;
                }
            }
        }

        return array_values(array_filter(array_unique($roots), 'is_dir'));
    }

    /**
     * module.json manifest file paths across every root.
     *
     * @return array<int, string>
     */
    protected function manifestFiles(): array
    {
        $files = new Filesystem();
        $manifests = [];

        foreach ($this->manifestRoots() as $root) {
            $found = $files->glob(
                rtrim($root, '/\\') . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . 'module.json'
            );
            if (is_array($found)) {
                $manifests = array_merge($manifests, $found);
            }
        }

        return $manifests;
    }

    protected function seedFromManifests(): int
    {
        if (empty($this->getTableName())) {
            return 0;
        }

        // All roots (primary + extra drop-in roots like ExtraModules/).
        $manifests = $this->manifestFiles();
        if (empty($manifests)) {
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

            // Insert-only: never overwrite an existing row, so an admin's own
            // enable/disable choice (and any metadata) is preserved. Only modules
            // with no row yet are seeded — this is what makes it safe to call on a
            // partially-populated table to back-fill modules added after the first
            // seed, not just on a completely empty one.
            if (SystemModules::getByName($name)) {
                continue;
            }

            $status = array_key_exists($name, $fileStatuses)
                ? (bool) $fileStatuses[$name]
                : (bool) $json->get('active', 1);

            $row = new SystemModules();
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
