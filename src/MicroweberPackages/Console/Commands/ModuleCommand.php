<?php

namespace MicroweberPackages\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\App\Http\Controllers\FrontendController;
use Symfony\Component\Console\Input\InputArgument;


// php artisan microweber:module shop 1 --env=localhost
// php artisan microweber:module shop 0 --env=localhost
class ModuleCommand extends Command
{
    protected $name = 'microweber:module';
    protected $description = 'Install or uninstall module';
    protected $controller;

    public function __construct(FrontendController $controller)
    {
        $this->controller = $controller;
        parent::__construct();
    }

    public function handle()
    {
        $input = array(
            'module' => $this->argument('module'),
            'module_action' => $this->argument('module_action'),
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

    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The module type'],
            ['module_action', InputArgument::REQUIRED, 'Should module be installed , install or uninstall'],
        ];
    }
}
