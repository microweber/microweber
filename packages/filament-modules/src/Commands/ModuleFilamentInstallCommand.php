<?php

namespace Coolsam\Modules\Commands;

use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Console\Command;
use Illuminate\Console\Concerns\PromptsForMissingInput;
use Nwidart\Modules\Exceptions\ModuleNotFoundException;
use Nwidart\Modules\Facades\Module;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use function Laravel\Prompts\confirm;

class ModuleFilamentInstallCommand extends Command implements \Illuminate\Contracts\Console\PromptsForMissingInput
{
    use CanManipulateFiles;
    use PromptsForMissingInput;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:filament:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Filament Support to a Module';

    private bool $cluster;

    private string $moduleName;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->moduleName = $this->argument('module');
        if (! $this->option('cluster')) {
            $this->cluster = confirm('Do you want to organize your code into filament clusters?', true);
        }
        // Ensure the Filament directories exist
        $this->ensureFilamentDirectoriesExist();
        // Create Filament Plugin
        $this->createDefaultFilamentPlugin();

        if ($this->cluster && confirm('Would you like to create a default Cluster for the module?', true)) {
            $this->createDefaultFilamentCluster();
        }
    }

    protected function getArguments(): array
    {
        return [
            ['module', InputArgument::REQUIRED, 'The name of the module in which to install filament support'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['cluster', 'C', InputOption::VALUE_NONE],
        ];
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'module' => [
                'What is the name of the module?',
                'e.g AccessControl, Blog, etc.',
            ],
        ];
    }

    protected function getModule(): \Nwidart\Modules\Module
    {
        try {
            return Module::findOrFail($this->moduleName);
        } catch (ModuleNotFoundException | \Throwable $exception) {
            if (confirm("Module $this->moduleName does not exist. Would you like to generate it?", true)) {
                $this->call('module:make', ['name' => [$this->moduleName]]);

                return $this->getModule();
            }
            $this->error($exception->getMessage());
            exit(1);
        }
    }

    private function ensureFilamentDirectoriesExist(): void
    {
        if (! is_dir($dir = $this->getModule()->appPath('Filament'))) {
            $this->makeDirectory($dir);
        }

        if ($this->cluster) {
            $dir = $this->getModule()->appPath('Filament/Clusters');
            if (! is_dir($dir = $this->getModule()->appPath('Filament/Clusters'))) {
                $this->makeDirectory($dir);
            }

        } else {
            if (! is_dir($dir = $this->getModule()->appPath('Filament/Pages'))) {
                $this->makeDirectory($dir);
            }

            if (! is_dir($dir = $this->getModule()->appPath('Filament/Resources'))) {
                $this->makeDirectory($dir);
            }

            if (! is_dir($dir = $this->getModule()->appPath('Filament/Widgets'))) {
                $this->makeDirectory($dir);
            }
        }
    }

    private function makeDirectory(string $dir): void
    {
        if (! mkdir($dir, 0755, true) && ! is_dir($dir)) {
            $this->error(sprintf('Directory "%s" was not created', $dir));
            exit(1);
        }
    }

    protected function createDefaultFilamentPlugin(): void
    {
        $module = $this->getModule();
        $this->call('module:make:filament-plugin', [
            'name' => $module->getStudlyName() . 'Plugin',
            'module' => $module->getStudlyName(),
        ]);
    }

    protected function createDefaultFilamentCluster(): void
    {
        $module = $this->getModule();
        $this->call('module:make:filament-cluster', [
            'name' => $module->getStudlyName(),
            'module' => $module->getStudlyName(),
        ]);
    }
}
