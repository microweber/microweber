<?php

namespace MicroweberPackages\Install\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\Install\Http\Controllers\InstallController;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class InstallCommand extends Command
{
    protected $name = 'microweber:install';
    protected $description = 'Installs Microweber (duh)';
    protected $installer;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'microweber:install
                                    {--db_host=}
                                    {--db_name=}
                                    {--db_user=}
                                    {--db_password=}
                                    {--db_driver=}
                                    {--db_prefix=}
                                    {--email=}
                                    {--username=}
                                    {--password=}
                                    {--default-content=}
                                    {--template=}
                                    {--config_only=}
                                    {--language=}';

    public function __construct(InstallController $installer)
    {
        $this->installer = $installer;
        parent::__construct();
    }

    public function handle()
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
            'db_host' => $this->option('db_host'),
            'db_name' => $this->option('db_name'),
            'db_user' => $this->option('db_user'),
            'db_password' => $this->option('db_password'),
            'db_driver' => $this->option('db_driver'),
            'db_prefix' => $this->option('db_prefix'),
            'admin_email' => $this->option('email'),
            'admin_username' => $this->option('username'),
            'admin_password' => $this->option('password'),
            'with_default_content' => $this->option('default-content'),
            'default_template' => $this->option('template'),
            'config_only' => $this->option('config_only'),
            'site_lang' => $this->option('language'),
        );

        $vals = array_filter($input);
        if (!$vals) {
            $this->info('No options provided... Performing lazy install');
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
        if (!$input['db_password']) {
            $input['db_password'] = getenv('DB_PASS');
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

        if (!$input['db_prefix']) {
            $input['db_prefix'] = (getenv('DB_PREFIX') ?: '');
        }
        if (!$input['db_prefix']) {
            $input['db_prefix'] = (getenv('TABKE_PREFIX') ?: '');
        }

        if ($lazy_install) {
            $input['default_template'] = (getenv('DEFAULT_TEMPLATE') ?: 'liteness');
            $input['with_default_content'] = true;
        }
        $input['subscribe_for_update_notification'] = true;


        $templateFound = false;
        if (is_file(templates_path() . $input['default_template'] .DS. 'config.php')) {
            $templateFound = $input['default_template'];
        }

        if (!$templateFound) {
            // Search in composer json
            $availTemplates = scandir(templates_path());
            foreach ($availTemplates as $templateFolderName) {
                $templateComposerFile = templates_path() . $templateFolderName . DS.'composer.json';
                if (is_file($templateComposerFile)) {
                    $templateComposerContent = file_get_contents($templateComposerFile);
                    $templateComposerContent = json_decode($templateComposerContent, true);
                    if (isset($templateComposerContent['name'])) {
                        if ($input['default_template'] == $templateComposerContent['name']) {
                            $templateFound = $templateFolderName;
                        }
                    }
                }
            }
        }

        $input['default_template'] = $templateFound;

        $this->info('Installing Microweber...');
        $this->installer->command_line_logger = $this;
        $result = $this->installer->index($input);

        if (strpos($result, 'Could not connect to the database.') !== false) {
            throw new \Exception($result);
        }

        $this->info($result);
    }

}
