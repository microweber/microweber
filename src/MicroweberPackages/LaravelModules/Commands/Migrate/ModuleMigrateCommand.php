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
}
