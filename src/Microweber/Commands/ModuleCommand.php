<?php

namespace Microweber\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Microweber\Controllers\DefaultController;


// php artisan microweber:module shop 1 --env=localhost
// php artisan microweber:module shop 0 --env=localhost
class ModuleCommand extends Command
{
    protected $name = 'microweber:module';
    protected $description = 'Install or uninstall module';
    protected $controller;

    public function __construct(DefaultController $controller)
    {
        $this->controller = $controller;
        parent::__construct();
    }

    public function fire()
    {
        $input = array(
            'module' => $this->argument('module'),
            'install' => $this->argument('install'),
         );

        $this->info('Setting module...');

        if($input['install']){
            mw()->modules->set_installed(array('for_module'=>$input['module']));
            $this->info($input['module'].  ' is installed');

        } else {

            mw()->modules->uninstall(array('for_module'=>$input['module']));
            $this->info($input['module'].  ' is uninstalled');

        }

   //     $result = mw()->option_manager->save($input);
    }

    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The module type'],
            ['install', InputArgument::REQUIRED, 'Should module be installed , 1 for install 0 for uninstall'],
         ];
    }
}
