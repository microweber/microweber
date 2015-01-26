<?php

namespace Microweber\Controllers;

use Microweber\View;
use Microweber\Install;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

use \Cache;
use \Event;
use \Session;

use Module;


class InstallController extends Controller
{


    public $app;

    public function __construct($app = null)
    {
        if (!is_object($this->app)) {

            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }


    }

    public function index($input = null)
    {
        if (!is_array($input) || empty($input)) {
            $input = Input::all();
        }
        $allowed_configs = array('database', 'microweber');
        $is_installed = mw_is_installed();
        if ($is_installed) {
            return 'Microweber is already installed!';
        }

        $view = MW_PATH . 'Views/install.php';

        $connection = Config::get('database.connections');

        if (isset($input['make_install'])) {
            if (!isset($input['db_pass'])) {
                $input['db_pass'] = '';
            }
            if (!isset($input['table_prefix'])) {
                $input['table_prefix'] = '';
            }

            $errors = array();
            if (!isset($input['db_host'])) {
                $errors[] = 'Parameter "db_host" is required';
            } else {
                $input['db_host'] = trim($input['db_host']);
            }
            if (!isset($input['db_name'])) {
                $errors[] = 'Parameter "db_name" is required';
            } else {
                $input['db_name'] = trim($input['db_name']);
            }
            if (!isset($input['db_user'])) {
                $errors[] = 'Parameter "db_user" is required';
            }
            if (!isset($input['admin_email'])) {
                $errors[] = 'Parameter "admin_email" is required';
            }
            if (!isset($input['admin_password'])) {
                $errors[] = 'Parameter "admin_password" is required';
            }
            if (!isset($input['admin_username'])) {
                $errors[] = 'Parameter "admin_username" is required';
            }

            if (!empty($errors)) {
                return implode("\n", $errors);
            }
            if (isset($input['db_driver'])) {
                $dbDriver = $input['db_driver'];
            } else {
                $dbDriver = 'mysql';
            }

            Config::set("database.default", $dbDriver);
            if ($dbDriver == 'sqlite') {
                Config::set("database.connections.$dbDriver.database", $input['db_name']);
                if (!file_exists($input['db_name'])) {
                    touch($input['db_name']);
                }
            }

            Config::set("database.connections.$dbDriver.host", $input['db_host']);
            Config::set("database.connections.$dbDriver.username", $input['db_user']);
            Config::set("database.connections.$dbDriver.password", $input['db_pass']);
            Config::set("database.connections.$dbDriver.database", $input['db_name']);
            Config::set("database.connections.$dbDriver.prefix", $input['table_prefix']);


            if (isset($input['default_template']) and $input['default_template'] != false) {
                Config::set('microweber.install_default_template', $input['default_template']);
            }
            if (isset($input['with_default_content']) and $input['with_default_content'] != false) {
                Config::set('microweber.install_default_template_content', 1);
            }

            if (Config::get('app.key') == 'YourSecretKey!!!') {
                if (!$this->app->runningInConsole()) {
                    $_SERVER['argv'] = array();
                }
                Artisan::call('key:generate');
            }

            Config::save($allowed_configs);
            Cache::flush();

            $install_finished = false;
            try {
                DB::connection($dbDriver)->getDatabaseName();
            } catch (\PDOException $e) {
                return ('Error: ' . $e->getMessage() . "\n");
            } catch (\Exception $e) {
                return ('Error: ' . $e->getMessage() . "\n");
            }

            if (function_exists('set_time_limit')) {
                @set_time_limit(0);
            }


            $installer = new Install\DbInstaller();
            $installer->run();

            $installer = new Install\WebserverInstaller();
            $installer->run();

            $installer = new Install\TemplateInstaller();
            $installer->run();

            $installer = new Install\DefaultOptionsInstaller();
            $installer->run();


            Config::set('microweber.is_installed', 1);

            if (isset($input['admin_password']) && strlen($input['admin_password'])) {
                $adminUser = new \User;
                $adminUser->username = $input['admin_username'];
                $adminUser->email = $input['admin_email'];
                $adminUser->password = $input['admin_password'];
                $adminUser->is_admin = 1;
                $adminUser->is_active = 1;
                $adminUser->save();
                Config::set('microweber.has_admin', 1);
            }


            Config::save($allowed_configs);
            return 'done';
        }

        $layout = new View($view);

        $defaultDbEngine = Config::get('database.default');
        if (extension_loaded('pdo_sqlite')) {
            $defaultDbEngine = 'sqlite';
        }

        $dbEngines = Config::get('database.connections');
        foreach ($dbEngines as $driver => $v) {
            if (!extension_loaded("pdo_$driver")) {
                unset($dbEngines[$driver]);
            }
        }
        $viewData = [
            'config' => $dbEngines[$defaultDbEngine],
            'dbDefaultEngine' => $defaultDbEngine,
            'dbEngines' => array_keys($dbEngines),
            'dbEngineNames' => [
                'mysql' => 'MySQL',
                'sqlite' => 'SQLite',
                'sqlsrv' => 'Microsoft SQL Server',
                'pgsql' => 'PostgreSQL'
            ]
        ];


        if (!$viewData['config']['prefix']) {
            $domain = $_SERVER['HTTP_HOST'];
            $domain = str_replace('.', '_', $domain);
            $viewData['config']['prefix'] = $domain . '_';
        }

        $layout->set($viewData);

        $is_installed = mw_is_installed();
        if ($is_installed) {
            App::abort(403, 'Unauthorized action. Microweber is already installed.');
        }
        $layout->assign('done', $is_installed);
        $layout = $layout->__toString();
        return $layout;
    }
}