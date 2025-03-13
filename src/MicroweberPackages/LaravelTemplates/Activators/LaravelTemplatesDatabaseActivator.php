<?php

namespace MicroweberPackages\LaravelTemplates\Activators;

use Illuminate\Cache\CacheManager;
use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Schema\Builder as Schema;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Log\LogManager;
use Illuminate\Support\Str;
use MicroweberPackages\LaravelTemplates\Models\SystemTemplates;
use MicroweberPackages\LaravelTemplates\Contracts\TemplateActivatorInterface;
use Nwidart\Modules\Json;
use Nwidart\Modules\Module;

class LaravelTemplatesDatabaseActivator implements TemplateActivatorInterface
{
    /**
     * Configuration prefix.
     *
     * @var string
     */
    public $configPrefix = 'templates';

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
     * Array of templates activation statuses.
     *
     * @var array
     */
    private $templatesStatuses;

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
        $this->table = $this->config('table', 'system_templates');

        $this->setModelConnection();

        $this->cacheKey = $this->config('cache-key', 'templates.activations');
        $this->cacheLifetime = $this->config('cache-lifetime', 604800);
        $this->templatesStatuses = $this->getTemplatesStatuses();
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
            SystemTemplates::query()->update(['status' => 0]);
        }
        $this->templatesStatuses = [];
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
    private function writeStatus(): void
    {
        if (empty($this->getTableName())) {
            return;
        }

        // Reset all templates to inactive
        SystemTemplates::query()->update(['status' => 0]);

        // Update active templates
        foreach ($this->templatesStatuses as $name => $status) {
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
    private function readStatus(): array
    {
        $statuses = [];
        if (empty($this->getTableName())) {
            return $statuses;
        }

        $templates = SystemTemplates::all();

        foreach ($templates as $template) {
            $statuses[$template->name] = (bool)$template->status;
        }

        if (empty($statuses)) {
            $all_enabled_templates = $this->scanJson();
            $this->syncTemplate($all_enabled_templates);

            if (count($all_enabled_templates) > 0) {
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
    private function getTemplatesStatuses(): array
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
     * Flushes the templates activation statuses cache.
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
     * Insert template info into database.
     *
     * @param array $templates
     */
    private function syncTemplate(array $templates): void
    {
        if (empty($this->getTableName())) {
            return;
        }

        $this->connection->transaction(function () use ($templates) {
            foreach ($templates as $key => $template) {
                if ($template instanceof Module) {
                    $templateData = array_merge(
                        collection_to_array($template->json()->getAttributes()),
                        [
                            'version' => $template->get('version'),
                            'type' => $template->get('type', 1),
                            'path' => $template->getPath()
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
                        'path' => $template->getPath(),
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
        });
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
    public function scanJson(): array
    {
        $templates = [];
        $paths = $this->app['config']->get($this->configPrefix . '.paths', []);
        
        $files = new Filesystem();
        
        foreach ($paths as $path) {
            $manifests = $files->glob("{$path}/*/module.json");
            
            is_array($manifests) || $manifests = [];
            
            foreach ($manifests as $manifest) {
                $name = Json::make($manifest)->get('name');
                
                if ($name) {
                    $templates[$name] = $this->app->make('templates.factory')->make(
                        $name,
                        dirname($manifest)
                    );
                }
            }
        }
        
        if ($templates) {
            uasort($templates, function (Module $a, Module $b) {
                if ($a->get('priority') === $b->get('priority')) {
                    return 0;
                }
                
                return $a->get('priority') > $b->get('priority') ? 1 : -1;
            });
        }
        
        return $templates;
    }
}
