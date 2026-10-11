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
        $this->templatesStatuses = $this->getTemplatesStatuses();

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
    public function writeStatus(): void
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
    public function readStatus(): array
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
