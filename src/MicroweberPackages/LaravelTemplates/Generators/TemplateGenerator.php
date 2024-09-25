<?php

namespace MicroweberPackages\LaravelTemplates\Generators;

use Illuminate\Config\Repository as Config;
use Illuminate\Console\Command as Console;
use Illuminate\Filesystem\Filesystem;
use MicroweberPackages\Cache\CacheFileHandler\Facades\Cache;
use MicroweberPackages\LaravelTemplates\Contracts\TemplateActivatorInterface;
use MicroweberPackages\LaravelTemplates\Repositories\LaravelTemplatesFileRepository;
use MicroweberPackages\LaravelTemplates\Support\TemplateGenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\PathNamespace;

class TemplateGenerator extends \Nwidart\Modules\Generators\ModuleGenerator
{
    use PathNamespace;

    /**
     * The module name will created.
     *
     * @var string
     */
    protected $name;

    /**
     * The laravel config instance.
     *
     * @var Config
     */
    protected $config;

    /**
     * The laravel filesystem instance.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * The laravel console instance.
     *
     * @var Console
     */
    protected $console;

    /**
     * The laravel component Factory instance.
     *
     * @var \Illuminate\Console\View\Components\Factory
     */
    protected $component;

    /**
     * The activator instance
     *
     * @var TemplateActivatorInterface
     */
    protected $activator;

    /**
     * The module instance.
     *
     * @var \Nwidart\Modules\Module
     */
    protected $module;

    /**
     * Force status.
     *
     * @var bool
     */
    protected $force = false;

    /**
     * set default module type.
     *
     * @var string
     */
    protected $type = 'web';

    /**
     * Enables the module.
     *
     * @var bool
     */
    protected $isActive = false;

    /**
     * Module author
     */
    protected array $author = [
        'name', 'email',
    ];

    /**
     * Vendor name
     */
    protected ?string $vendor = null;

    /**
     * The constructor.
     */
    public function __construct(
        $name,
        ?LaravelTemplatesFileRepository $module = null,
        ?Config $config = null,
        ?Filesystem $filesystem = null,
        ?Console $console = null,
        ?TemplateActivatorInterface $activator = null
    )
    {
        $this->name = $name;
        $this->config = $config;
        $this->filesystem = $filesystem;
        $this->console = $console;
        $this->module = $module;
        $this->activator = $activator;

    }

    public function setTemplatesActivator(TemplateActivatorInterface $activator)
    {

        $this->activator = $activator;

        return $this;
    }

    /**
     * Generate the folders.
     */
    public function generateFolders()
    {

        foreach ($this->getFolders() as $key => $folder) {
            $folder = TemplateGenerateConfigReader::read($key);

            if ($folder->generate() === false) {
                continue;
            }

            $path = $this->module->getModulePath($this->getName()) . '/' . $folder->getPath();

            $this->filesystem->ensureDirectoryExists($path, 0755, true);
            if (config('templates.stubs.gitkeep')) {
                $this->generateGitKeep($path);
            }
        }
    }

    public function generate(): int
    {

        $name = $this->getName();

        if ($this->module->has($name)) {
            if ($this->force) {
                $this->module->delete($name);
            } else {
                $this->component->error("Template [{$name}] already exists!");

                return E_ERROR;
            }
        }
        $this->component->info("Creating template: [$name]");

        $this->generateFolders();

        $this->generateModuleJsonFile();

        // $this->module->config('cache.enabled', false);
        // $all = $this->module->all();

        // $path = $this->module->getModulePath($this->getName());

        if ($this->type !== 'plain') {
            $this->generateFiles();
            $this->generateResources();
        }
//
//        if ($this->type === 'plain') {
//            $this->cleanModuleJsonFile();
//        }

        $this->activator->setActiveByName($name, $this->isActive);
        $this->console->newLine(1);

        $this->component->info("Template [{$name}] created successfully.");

        cache()->flush();

        return 0;
    }

    protected function getStubContents($stub)
    {

        $stubPathBase = config('templates.stubs.path') ?? __DIR__ . '/../stubs/';
        $stubPathBase = rtrim($stubPathBase, '\\') . '/';
        $stubPathBase = rtrim($stubPathBase, '/') . '/';
        $stub = new Stub('/' . $stub . '.stub', $this->getReplacement($stub));
        $stub->setBasePath($stubPathBase);

        return $stub->render();

    }

    /**
     * Generate the module.json file
     */
    private function generateModuleJsonFile()
    {
        $path = $this->module->getModulePath($this->getName()) . 'module.json';

        $this->component->task("Generating file $path", function () use ($path) {
            if (!$this->filesystem->isDirectory($dir = dirname($path))) {
                $this->filesystem->makeDirectory($dir, 0775, true);
            }

            $this->filesystem->put($path, $this->getStubContents('json'));
        });
    }

    /**
     * Remove the default service provider that was added in the module.json file
     * This is needed when a --plain module was created
     */
    private function cleanModuleJsonFile()
    {
        $path = $this->module->getModulePath($this->getName()) . 'module.json';

        $content = $this->filesystem->get($path);
        $namespace = $this->getModuleNamespaceReplacement();
        $studlyName = $this->getStudlyNameReplacement();

        $provider = '"' . $namespace . '\\\\' . $studlyName . '\\\\Providers\\\\' . $studlyName . 'ServiceProvider"';

        $content = str_replace($provider, '', $content);

        $this->filesystem->put($path, $content);
    }


    public function generateResources()
    {

        $providerGenerator = TemplateGenerateConfigReader::read('provider');

        if ($providerGenerator->generate() === true) {
            $this->console->call('template:make-provider', [
                'name' => $this->getName() . 'ServiceProvider',
                'module' => $this->getName(),
                '--master' => true,
            ]);
        } else {
            // delete register ServiceProvider on module.json
            $path = $this->module->getModulePath($this->getName()) . DIRECTORY_SEPARATOR . 'module.json';
            $module_file = $this->filesystem->get($path);
            $this->filesystem->put(
                $path,
                preg_replace('/"providers": \[.*?\],/s', '"providers": [ ],', $module_file)
            );
        }


    }

}
