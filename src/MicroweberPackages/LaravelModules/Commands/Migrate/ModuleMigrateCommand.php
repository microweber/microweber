<?php

namespace MicroweberPackages\LaravelModules\Commands\Migrate;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Collection;
use Nwidart\Modules\Commands\BaseCommand;
use Nwidart\Modules\Commands\Database\MigrateCommand;
use Nwidart\Modules\Contracts\ConfirmableCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;

class ModuleMigrateCommand extends MigrateCommand
{
    protected $name = 'module:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the migrations from the specified module or from all modules in Microweber.';

    public function __construct()
    {


        parent::__construct();

        $this->migrator = app('migrator');
        $this->migration_list = collect($this->migrator->paths());
    }

    public function executeAction($name): void
    {


        $module = $this->getModuleModel($name);

        $this->components->twoColumnDetail("Running Migration <fg=cyan;options=bold>{$module->getName()}</> Module");

        $module_path = $module->getPath();

        $paths = $this->migration_list
            ->filter(fn ($path) => str_starts_with($path, $module_path));

        $this->call('migrate', array_filter([
            '--path' => $paths->toArray(),
        //    '--database' => $this->option('database'),
         //   '--pretend' => $this->option('pretend'),
            '--force' => true,
          //  '--realpath' => true,
        ]));

        if ($this->option('seed')) {
            $this->call('module:seed', ['module' => $module->getName(), '--force' => $this->option('force')]);
        }

    }




    /* protected function isProhibited(bool $quiet = false)
     {


             return false;

     }
     private function configureConfirmable(): void
     {
         dd('configureConfirmable');
     }
     public function confirmToProceed($warning = 'Application In Production', $callback = null)
     {
         return true;
     }*/
}
