<?php

namespace MicroweberPackages\LaravelTemplates\Support;

use Nwidart\Modules\Support\Config\GeneratorPath;
use Nwidart\Modules\Traits\PathNamespace;

class TemplateGeneratorPath extends GeneratorPath
{
    use PathNamespace;

    private $path;

    private $generate;

    private $namespace;


    public function __construct($config)
    {
        if (is_array($config)) {

            $this->path = $config['path'];
            $this->generate = $config['generate'];
            $this->namespace = $config['namespace'] ??   config('templates.namespace', 'Templates');

            return;
        }

        $this->path = $config;
        $this->generate = (bool)$config;
        $this->namespace = config('templates.namespace', 'Templates');
    }
    public function module_namespace(string $module, ?string $path = null): string
    {
        $module_namespace = config('templates.namespace', $this->path_namespace(config('templates.paths.modules'))).'\\'.($module);
        $module_namespace .= strlen($path) ? '\\'.$this->path_namespace($path) : '';

        return $this->studly_namespace($module_namespace);
    }
    public function getPath()
    {
        return $this->path;
    }

    public function generate(): bool
    {
        return $this->generate;
    }

    public function getNamespace()
    {
        return $this->studly_namespace($this->namespace);
    }



}
