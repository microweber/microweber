<?php

namespace MicroweberPackages\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\App\Http\Controllers\FrontendController;
use Symfony\Component\Console\Input\InputArgument;

// php artisan microweber:module --module=shop --module_action=1 --env=localhost
// php artisan microweber:module --module=shop --module_action=0 --env=localhost

class ModuleCommand extends Command
{
    protected $name = 'microweber:module';
    protected $description = 'Install or uninstall module';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'microweber:module {--module=} {--module_action=}';

    public function handle()
    {
        $input = array(
            'module' => $this->option('module'),
            'module_action' => $this->option('module_action'),
        );

        $this->info('Setting module...');

        if (isset($input['module_action'])) {

            if (trim($input['module_action']) == 'install' or intval($input['module_action']) == 1) {
                mw()->module_manager->set_installed(array('for_module' => $input['module']));
                $this->info($input['module'] . ' is installed');
            } else if (trim($input['module_action']) == 'uninstall' or intval($input['module_action']) == 0) {
                mw()->module_manager->uninstall(array('for_module' => $input['module']));
                $this->info($input['module'] . ' is uninstalled');
            }

            $this->info('Reloading modules...');

            mw()->module_manager->scan(['reload_modules'=>1,'scan'=>1]);
        }
    }
}
