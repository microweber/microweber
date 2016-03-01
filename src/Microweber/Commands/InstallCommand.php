<?php

namespace Microweber\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Microweber\Controllers\InstallController;

class InstallCommand extends Command
{
    protected $name = 'microweber:install';
    protected $description = 'Installs Microweber (duh)';
    protected $installer;

    public function __construct(InstallController $installer)
    {
        $this->installer = $installer;
        parent::__construct();
    }

    public function fire()
    {
        $input = array(
            'make_install' => true,
            'db_host' => $this->argument('db_host'),
            'db_name' => $this->argument('db_name'),
            'db_user' => $this->argument('db_user'),
            'db_pass' => $this->argument('db_pass'),
            'db_driver' => $this->argument('db_driver'),
            'table_prefix' => $this->option('prefix'),
            'admin_email' => $this->argument('email'),
            'admin_username' => $this->argument('username'),
            'admin_password' => $this->argument('password'),
            'with_default_content' => $this->option('default-content'),
            'default_template' => $this->option('template'),

        );

        $this->info('Installing Microweber...');
        $result = $this->installer->index($input);
        $this->info($result);
    }

    protected function getArguments()
    {
        return [
            ['email', InputArgument::REQUIRED, 'Admin account email'],
            ['username', InputArgument::REQUIRED, 'Admin account username'],
            ['password', InputArgument::REQUIRED, 'Admin account password'],
            ['db_host', InputArgument::REQUIRED, 'Database host address'],
            ['db_name', InputArgument::REQUIRED, 'Database schema name'],
            ['db_user', InputArgument::REQUIRED, 'Database username'],
            ['db_pass', InputArgument::OPTIONAL, 'Database password'],
            ['db_driver', InputArgument::OPTIONAL, 'Database driver'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['prefix', 'p', InputOption::VALUE_OPTIONAL, 'Database tables prefix'],
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Set default template name'],
            ['default-content', 'd', InputOption::VALUE_OPTIONAL, 'Install default content'],
        ];
    }
}
