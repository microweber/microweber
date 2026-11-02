<?php

namespace MicroweberPackages\LaravelTemplates\Activators;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use MicroweberPackages\LaravelModules\Activators\LaravelModulesDatabaseActivator;
use MicroweberPackages\LaravelTemplates\Models\SystemTemplates;
use MicroweberPackages\LaravelTemplates\Contracts\TemplateActivatorInterface;
use Nwidart\Modules\Module;
use Illuminate\Support\Str;
use MicroweberPackages\LaravelModules\Models\SystemModules;
use Nwidart\Modules\Contracts\ActivatorInterface;
use Nwidart\Modules\Json;



class LaravelTemplatesDatabaseActivator extends LaravelModulesDatabaseActivator implements TemplateActivatorInterface
{
    /**
     * Configuration prefix.
     *
     * @var string
     */
    public $configPrefix = 'templates';

    /**
     * Array of templates activation statuses.
     *
     * @var array
     */
    public $templatesStatuses;

    /**
     * Whether $templatesStatuses has been loaded yet. Loading is DEFERRED out of
     * the constructor for the same reason as the modules activator — see the
     * $statusesLoaded docblock there — so constructing this activator performs no
     * DB / manifest work and cannot re-enter a half-built ActivatorInterface.
     *
     * @var bool
     */
    protected $templatesStatusesLoaded = false;

    /**
     * Create a new DatabaseActivator instance.
     *
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        parent::__construct($app);

        $this->table = $this->config('table', 'system_templates');
        $this->setModelConnection();
        $this->cacheKey = $this->config('cache-key', 'templates.activations');

        // Statuses load lazily on first use (see ensureTemplatesStatusesLoaded()).
    }

    /**
     * Load the template activation statuses on first use. Deferred out of the
     * constructor so building the activator does no DB / manifest / module-factory
     * work (the source of the old circular dependency). Safe to call repeatedly.
     */
    protected function ensureTemplatesStatusesLoaded(): void
    {
        if ($this->templatesStatusesLoaded) {
            return;
        }

        $this->templatesStatuses = $this->getTemplatesStatuses();
        $this->templatesStatusesLoaded = true;
    }

    /**
     * {@inheritDoc}
     */
    public function reset(): void
    {
        if (!empty($this->getTableName())) {
            SystemTemplates::query()->update(['status' => 0]);
        }
        $this->templatesStatuses = [];
        $this->templatesStatusesLoaded = true;
        $this->flushCache();
    }

    /**
     * {@inheritDoc}
     */
    public function hasStatus(Module|string $module, bool $status): bool
    {
        $this->ensureTemplatesStatusesLoaded();

        $name = $module instanceof Module ? $module->getName() : $module;

        if (!isset($this->templatesStatuses[$name])) {
            return $status === false;
        }

        return $this->templatesStatuses[$name] === $status;
    }

    /**
     * {@inheritDoc}
     */
    public function setActive(Module $module, bool $active): void
    {
        $this->setActiveByName($module->getName(), $active);
        $this->syncTemplate([$module]);
    }

    /**
     * {@inheritDoc}
     */
    public function setActiveByName(string $name, bool $status): void
    {
        $this->ensureTemplatesStatusesLoaded();
        $this->flushCache();
        $this->templatesStatuses[$name] = $status;
        $this->writeStatus();
        $this->flushCache();
    }

    /**
     * {@inheritDoc}
     */
    public function delete(Module $module): void
    {
        $this->ensureTemplatesStatusesLoaded();
        if (!isset($this->templatesStatuses[$module->getName()])) {
            return;
        }
        unset($this->templatesStatuses[$module->getName()]);
        $this->syncTemplate([$module]);
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

        $this->ensureTemplatesStatusesLoaded();

        // Reset all templates to inactive
        SystemTemplates::query()->update(['status' => 0]);

        // Update active templates
        foreach ((array) $this->templatesStatuses as $name => $status) {
            if ($status === true) {
                $templateActivation = SystemTemplates::getByName($name);
                if ($templateActivation) {
                    $templateActivation->status = $status;
                    $templateActivation->save();
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

        // Resilience: if the status table isn't present yet (a fresh/partial
        // install, or an install-order gap under the per-site database activator),
        // don't 500 — fall back to the on-disk manifest defaults so templates
        // still load. The table is normally created by its migration.
        if (!$this->statusTableExists()) {
            return $this->manifestStatuses();
        }

        $templates = SystemTemplates::all();

        foreach ($templates as $template) {
            $statuses[$template->name] = (bool)$template->status;
        }

        // Seed the table for any on-disk template that has no row yet. The table
        // used to self-seed only when COMPLETELY empty, so a bundled template
        // shipped AFTER the first seed never got a row — and an absent template
        // is treated as disabled. On a database-activator (multisite) site that
        // silently disabled every newly-added template. seedFromManifests() is
        // insert-only, so existing rows (an admin's own enable/disable choices)
        // are preserved; only the missing templates are added, with their
        // manifest "active" default. Covers both the empty-table (fresh install)
        // and partial-table (template added later) cases in one pass.
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
     * Get templates statuses, either from the cache or from
     * the database if the cache is disabled.
     *
     * @return array
     */
    public function getTemplatesStatuses(): array
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
     * Insert template info into database.
     *
     * @param array $templates
     */
    public function syncTemplate(array $templates): void
    {
        if (empty($this->getTableName())) {
            return;
        }

        $this->ensureTemplatesStatusesLoaded();

        foreach ($templates as $key => $template) {
            if ($template instanceof Module) {
                $templateData = array_merge(
                    collection_to_array($template->json()->getAttributes()),
                    [
                        'version' => $template->get('version'),
                        'type' => $template->get('type', 1),
                        'path' => $template->getRelativePath()
                    ]
                );

                $templateActivation = SystemTemplates::getByName($template->getName());
                if (!$templateActivation) {
                    $templateActivation = new SystemTemplates();
                }

                $templateActivation->fill([
                    'name' => $template->getName(),
                    'alias' => $template->getLowerName(),
                    'description' => $template->getDescription(),
                    'path' => $template->getRelativePath(),
                    'version' => $template->get('version', 'dev'),
                    'type' => $template->get('type', '1'),
                    'priority' => $template->get('priority', '1024'),
                    'sort' => $template->get('order', '0'),
                    'status' => isset($this->templatesStatuses[$template->getName()]) ?
                        $this->templatesStatuses[$template->getName()] : 0
                ]);

                $templateActivation->save();
            }
        }
    }

    protected function setModelConnection(): void
    {
        SystemTemplates::setConnectionResolver($this->app['db']);
        SystemTemplates::resolveConnection($this->connection->getName());
    }

    /**
     * Scan for module.json files without using the template repository.
     * This prevents recursive loops when the activator is called during template scanning.
     *
     * @return array
     */
    /**
     * Seed system_templates from the on-disk template manifests when the table
     * is empty. Reads manifests DIRECTLY — no template factory, no Module
     * objects, no ActivatorInterface resolution — so it is safe to run from this
     * activator's own constructor. (The previous version called an unbound
     * 'templates.factory', which crashed boot, and seeded every template as
     * status=0.) Preserves the current file-activator state when a statuses file
     * exists, otherwise uses the manifest "active" flag (default enabled).
     * Returns the number of templates seeded.
     */
    /**
     * Whether the status table physically exists (guards against a fresh/partial
     * install where the activator runs before its table is created).
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

    // manifestStatuses(), manifestRoots() and manifestFiles() are inherited from
    // LaravelModulesDatabaseActivator — they are configPrefix-aware ('templates'
    // here), so they already walk the primary Templates root plus the extra
    // drop-in roots (ExtraTemplates/…). Only seedFromManifests() is overridden,
    // because it persists rows through the SystemTemplates model.

    protected function seedFromManifests(): int
    {
        if (empty($this->getTableName())) {
            return 0;
        }

        // All template roots (primary + extra drop-in roots like ExtraTemplates/).
        $manifests = $this->manifestFiles();
        if (empty($manifests)) {
            return 0;
        }

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
            // enable/disable choice (and any metadata) is preserved. Only
            // templates with no row yet are seeded — this makes it safe to call on
            // a partially-populated table to back-fill templates added after the
            // first seed, not just on a completely empty one.
            if (SystemTemplates::getByName($name)) {
                continue;
            }

            $status = array_key_exists($name, $fileStatuses)
                ? (bool) $fileStatuses[$name]
                : (bool) $json->get('active', 1);

            $row = new SystemTemplates();
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
