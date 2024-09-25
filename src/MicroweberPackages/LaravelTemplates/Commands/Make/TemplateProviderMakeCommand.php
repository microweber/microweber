<?php

namespace MicroweberPackages\LaravelTemplates\Commands\Make;

use Illuminate\Support\Str;
use MicroweberPackages\LaravelTemplates\Support\TemplateGenerateConfigReader;
use Nwidart\Modules\Commands\Make\GeneratorCommand;
use Nwidart\Modules\Module;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class TemplateProviderMakeCommand extends GeneratorCommand
{
    public function getModuleName(): string
    {
        $module = $this->argument('module') ?: app('templates')->getUsedNow();

        $module = app('templates')->findOrFail($module);

        return $module->getStudlyName();
    }

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'template:make-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service provider class for the specified module.';

    public function getDefaultNamespace(): string
    {
        return ltrim(config('templates.paths.generator.provider.path', 'Providers'), config('templates.paths.app_folder', ''));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The service provider name.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['master', null, InputOption::VALUE_NONE, 'Indicates the master service provider', null],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $stub = $this->option('master') ? 'scaffold/provider' : 'provider';

        /** @var Module $module */
        $module = $this->laravel['templates']->findOrFail($this->getModuleName());

        $stubPathBase = config('templates.stubs.path') ?? __DIR__ . '/../stubs/';
        $stubPathBase = rtrim($stubPathBase, '\\') . '/';
        $stubPathBase = rtrim($stubPathBase, '/') . '/';
        $stub = new Stub( $stub . '.stub',[
            'NAMESPACE' => $this->getClassNamespace($module),
            'CLASS' => $this->getClass(),
            'LOWER_NAME' => $module->getLowerName(),
            'MODULE' => $this->getModuleName(),
            'NAME' => $this->getFileName(),
            'STUDLY_NAME' => $module->getStudlyName(),
            'MODULE_NAMESPACE' => $this->laravel['templates']->config('namespace'),
            'PATH_VIEWS' => TemplateGenerateConfigReader::read('views')->getPath(),
            'PATH_LANG' => TemplateGenerateConfigReader::read('lang')->getPath(),
            'PATH_CONFIG' => TemplateGenerateConfigReader::read('config')->getPath(),
            'MIGRATIONS_PATH' => TemplateGenerateConfigReader::read('migration')->getPath(),
            'FACTORIES_PATH' => TemplateGenerateConfigReader::read('factory')->getPath(),
        ]);
        $stub->setBasePath($stubPathBase);


        return $stub->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['templates']->getModulePath($this->getModuleName());

        $generatorPath = TemplateGenerateConfigReader::read('provider');

        return $path . $generatorPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }

    public function module_namespace(string $module, ?string $path = null): string
    {
        $module_namespace = config('templates.namespace', $this->path_namespace(config('templates.paths.modules'))) . '\\' . ($module);
        $module_namespace .= strlen($path) ? '\\' . $this->path_namespace($path) : '';

        return $this->studly_namespace($module_namespace);
    }

    public function module(?string $name = null): Module
    {
        return $this->laravel['templates']->findOrFail($name ?? $this->getModuleName());
    }
}
