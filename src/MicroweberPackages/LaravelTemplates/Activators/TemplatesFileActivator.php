<?php

namespace MicroweberPackages\LaravelTemplates\Activators;

use Illuminate\Container\Container;
use MicroweberPackages\LaravelTemplates\Contracts\TemplateActivatorInterface;

class TemplatesFileActivator extends \Nwidart\Modules\Activators\FileActivator implements TemplateActivatorInterface
{

    public function __construct(Container $app)
    {

        $this->cache = $app['cache'];
        $this->files = $app['files'];
        $this->config = $app['config'];
        $this->statusesFile = $this->config('statuses-file');

        $this->cacheKey = $this->config('cache-key');
        $this->cacheLifetime = $this->config('cache-lifetime');
        $this->modulesStatuses = $this->getModulesStatuses();
    }

    public function setActiveByName(string $name, bool $status): void
    {
        $this->modulesStatuses [$name] = $status;
        $this->writeJson();
        $this->flushCache();
    }

    private function writeJson(): void
    {


        $this->files->put($this->statusesFile, json_encode($this->modulesStatuses, JSON_PRETTY_PRINT));
    }
    private function getModulesStatuses(): array
    {

        if (! $this->config->get('templates.cache.enabled')) {
            return $this->readJson();
        }

        return $this->cache->store($this->config->get('templates.cache.driver'))->remember($this->cacheKey, $this->cacheLifetime, function () {
            return $this->readJson();
        });
    }
    private function readJson(): array
    {
        if (! $this->files->exists($this->statusesFile)) {
            return [];
        }

        return json_decode($this->files->get($this->statusesFile), true);
    }
    /**
     * Reads a config parameter under the 'activators.file' key
     *
     * @return mixed
     */
    private function config(string $key, $default = null)
    {

        return $this->config->get('templates.activators.file.'.$key, $default);
    }

    /**
     * Flushes the modules activation statuses cache
     */
    private function flushCache(): void
    {
        $this->cache->store($this->config->get('templates.cache.driver'))->forget($this->cacheKey);
    }
}
