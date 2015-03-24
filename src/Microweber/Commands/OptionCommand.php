<?php namespace Microweber\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Microweber\Controllers\DefaultController;

class OptionCommand extends Command
{
    protected $name = 'microweber:option';
    protected $description = 'Set Microweber option';
    protected $controller;

    public function __construct(DefaultController $controller)
    {
        $this->controller = $controller;
        parent::__construct();
    }

    public function fire()
    {
        $input = array(
            'option_key' => $this->argument('option_key'),
            'option_value' => $this->argument('option_value'),
            'option_group' => $this->argument('option_group')
        );

        $this->info("Setting option...");
        $result = mw()->option_manager->save($input);
        $this->info($result);
    }

    protected function getArguments()
    {
        return [
            ['option_key', InputArgument::REQUIRED, 'The key of the option'],
            ['option_value', InputArgument::REQUIRED, 'The value of the option'],
            ['option_group', InputArgument::REQUIRED, 'The group of the option']
        ];
    }

}