<?php namespace Microweber\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Microweber\Providers\UpdateManager;

class UpdateCommand extends Command {
    protected $name = 'microweber:update';
    protected $description = 'Updates Microweber';
    protected $installer;

    public function __construct(UpdateManager $installer) {
        $this->installer = $installer;

        parent::__construct();
    }

    public function fire() {
        $this->info("Checking for update...");

        $check = $this->installer->check(true);
        if (!$check){
            $this->info("Everything is up to date");

            return;
        }

        if (is_array($check) and !empty($check) and isset($check['count'])){
            $this->info("There are {$check['count']} new updates");
            $this->info("Installing...");
            $check = $this->installer->apply_updates($check);
            $this->info("Updates are installed");

        }
    }
}