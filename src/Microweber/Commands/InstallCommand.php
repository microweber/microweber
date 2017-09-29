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
        /*
         *
        // you can now do
        php artisan microweber:install


        // or you can also install from env with:

        export DB_HOST=localhost
        export DB_USER=user
        export DB_PASS=pass
        export DB_ENGINE=sqlite
        export DB_NAME=storage/database.sqlite
        export DB_PREFIX=mw_
        export DB_PORT=

        php artisan microweber:install

        */


        $input = array(
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
            'config_only' => $this->option('config_only'),

        );
        $vals = array_filter($input);
        if (!$vals) {
            $this->info('No arguments provided... Performing lazy install');
            $lazy_install = true;
        } else {
            $lazy_install = false;
        }
        if (!isset($input['make_install'])) {
            $input['make_install'] = true;
        }

        if (!$input['db_host']) {
            $input['db_host'] = getenv('DB_HOST');
        }
        if (!$input['db_user']) {
            $input['db_user'] = getenv('DB_USER');
        }
        if (!$input['db_pass']) {
            $input['db_pass'] = getenv('DB_PASS');
        }
        if (!$input['db_name']) {
            $input['db_name'] = getenv('DB_NAME');
        }

        if (!$input['db_driver']) {
            $input['db_driver'] = (getenv('DB_ENGINE') ?: 'sqlite');
            if (!$input['db_name']) {
                $input['db_name'] = (getenv('DB_NAME') ?: 'storage/database.sqlite');
            }
        }


        if (!$input['table_prefix']) {
            $input['table_prefix'] = (getenv('DB_PREFIX') ?: '');
        }
        if (!$input['table_prefix']) {
            $input['table_prefix'] = (getenv('TABKE_PREFIX') ?: '');
        }

        if ($lazy_install) {
            $input['default_template'] = (getenv('DEFAULT_TEMPLATE') ?: 'liteness');
            $input['with_default_content'] = true;
        }


        $this->info('Installing Microweber...');
        $this->installer->command_line_logger = $this;
        $result = $this->installer->index($input);
        $this->info($result);
    }

    protected function getArguments()
    {
        return [
            ['email', InputArgument::OPTIONAL, 'Admin account email'],
            ['username', InputArgument::OPTIONAL, 'Admin account username'],
            ['password', InputArgument::OPTIONAL, 'Admin account password'],
            ['db_host', InputArgument::OPTIONAL, 'Database host address'],
            ['db_name', InputArgument::OPTIONAL, 'Database schema name'],
            ['db_user', InputArgument::OPTIONAL, 'Database username'],
            ['db_pass', InputArgument::OPTIONAL, 'Database password'],
            ['db_driver', InputArgument::OPTIONAL, 'Database driver'],
            ['prefix', InputArgument::OPTIONAL, 'Table prefix'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['prefix', 'p', InputOption::VALUE_OPTIONAL, 'Database tables prefix'],
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Set default template name'],
            ['default-content', 'd', InputOption::VALUE_OPTIONAL, 'Install default content'],
            ['config_only', 'c', InputOption::VALUE_OPTIONAL, 'Prepare the install'],
        ];
    }
}
