<?php

namespace MicroweberPackages\LaravelTemplates\Commands\Migrate;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Collection;
use Nwidart\Modules\Commands\BaseCommand;
use Nwidart\Modules\Contracts\ConfirmableCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\multiselect;

class TemplateMigrateCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'template:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the migrations from the specified template or from all templates.';

    /**
     * The migrator instance.
     */
    protected Migrator $migrator;

    protected Collection $migration_list;

    public function __construct()
    {

        parent::__construct();
        $this->getDefinition()->addOption(new InputOption(
            strtolower(self::ALL),
            'a',
            InputOption::VALUE_NONE,
            'Check all Modules',
        ));

//        $this->getDefinition()->addArgument(new InputArgument(
//            'module',
//            InputArgument::IS_ARRAY,
//            'The name of module will be used.',
//        ));

        if ($this instanceof ConfirmableCommand) {
            $this->configureConfirmable();
        }

        $this->migrator = app('migrator');
        $this->migration_list = collect($this->migrator->paths());
    }

    public function handle()
    {


        if ($this instanceof ConfirmableCommand) {
            if ($this->isProhibited() ||
                !$this->confirmToProceed($this->getConfirmableLabel(), fn() => true)) {
                return 1;
            }
        }

        if (!is_null($info = $this->getInfo())) {
            $this->components->info($info);
        }

        $modules = (array)$this->argument('module');

        foreach ($modules as $module) {
            $this->executeAction($module);
        }
    }

    public function executeAction($name): void
    {
        $module = $this->getModuleModel($name);

        $this->components->twoColumnDetail("Running Migration <fg=cyan;options=bold>{$module->getName()}</> Module");

        $module_path = $module->getPath();

        $paths = $this->migration_list
            ->filter(fn($path) => str_starts_with($path, $module_path));

        $this->call('migrate', array_filter([
            '--path' => $paths->toArray(),
            '--database' => $this->option('database'),
            '--pretend' => $this->option('pretend'),
            '--force' => $this->option('force'),
            '--realpath' => true,
        ]));

//        if ($this->option('seed')) {
//            $this->call('module:seed', ['module' => $module->getName(), '--force' => $this->option('force')]);
//        }

    }

    protected function getModuleModel($name)
    {
        return $name instanceof \Nwidart\Modules\Module
            ? $name
            : $this->laravel['templates']->findOrFail($name);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'asc'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.'],
            ['subpath', null, InputOption::VALUE_OPTIONAL, 'Indicate a subpath to run your migrations from'],
            ['module', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The name of the module to migrate.'],
        ];
    }

    protected function promptForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        $modules = $this->hasOption('direction')
            ? array_keys($this->laravel['templates']->getOrdered($input->hasOption('direction')))
            : array_keys($this->laravel['templates']->all());

        if ($input->getOption(strtolower(self::ALL))) {
            $input->setArgument('module', $modules);

            return;
        }
        if ($input->getOption(strtolower('module'))) {
            $input->setArgument('module', $input->getOption(strtolower('module')));

            return;
        }

        if (!empty($input->getArgument('module'))) {
            return;
        }

        $selected_item = multiselect(
            label: 'Select Modules',
            options: [
                self::ALL,
                ...$modules,
            ],
            required: 'You must select at least one module',
        );

        $input->setArgument(
            'module',
            value: in_array(self::ALL, $selected_item)
                ? $modules
                : $selected_item
        );
    }

    private function configureConfirmable(): void
    {
        $this->getDefinition()
            ->addOption(new InputOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'Force the operation to run without confirmation.',
            ));
    }
}
