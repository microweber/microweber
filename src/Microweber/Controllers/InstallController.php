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
use Cache;

class InstallController extends Controller
{
    public $app;
    public $command_line_logger;

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
        $env = $this->app->environment();

        $view = MW_PATH . 'Views/install.php';

        $connection = Config::get('database.connections');

        $this->log('Preparing to install');
        if (isset($input['make_install'])) {
            $config_only = false;
            if (isset($input['config_only']) and $input['config_only']) {
                $config_only = true;
            }
            if (!isset($input['db_pass'])) {
                $input['db_pass'] = '';
            }
            if (!isset($input['table_prefix'])) {
                $input['table_prefix'] = '';
            }

            if (is_numeric(substr($input['table_prefix'], 0, 1))
            ) {
                $input['table_prefix'] = 'p' . $input['table_prefix'];
            }

            $input['table_prefix'] = str_replace(':', '', $input['table_prefix']);


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
            } else {
                $input['db_user'] = trim($input['db_user']);
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

            Config::set('database.default', $dbDriver);
            if ($dbDriver == 'sqlite') {
                if (isset($input['db_name_sqlite'])) {
                    $input['db_name'] = $input['db_name_sqlite'];
                }
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

            if (defined('MW_VERSION')) {
                Config::set('microweber.version', MW_VERSION);
            }

            if (isset($input['default_template']) and $input['default_template'] != false) {
                Config::set('microweber.install_default_template', $input['default_template']);
            }
            if (isset($input['with_default_content']) and $input['with_default_content'] != false) {
                Config::set('microweber.install_default_template_content', 1);
            }

            if (!isset($input['developer_mode'])) {
                Config::set('microweber.compile_assets', 1);
            }
            if (isset($input['clean_pre_configured'])) {
                Config::set('microweber.pre_configured',null);
                Config::set('microweber.pre_configured_input',null);
            }


            if (Config::get('app.key') == 'YourSecretKey!!!') {
                if (!is_cli()) {
                    $_SERVER['argv'] = array();
                }
                $this->log('Generating key');
                if (!$this->_can_i_use_artisan_key_generate_command()) {
                    $fallback_key = str_random(32);
                    $fallback_key_str = 'base64:' . base64_encode($fallback_key);
                    Config::set('app.key', $fallback_key_str);
                    $allowed_configs[] = 'app';
                } else {
                    Artisan::call('key:generate', [
                        '--force' => true,
                    ]);
                }
            }

            $this->log('Saving config');
            Config::save($allowed_configs);
            Cache::flush();


            if($config_only) {
                Config::set('microweber.pre_configured', 1);
                Config::set('microweber.pre_configured_input', $input);
            } else {


            $install_finished = false;
            try {
                DB::connection($dbDriver)->getDatabaseName();
            } catch (\PDOException $e) {
                return 'Error: ' . $e->getMessage() . "\n";
            } catch (\Exception $e) {
                return 'Error: ' . $e->getMessage() . "\n";
            }

            if (function_exists('set_time_limit')) {
                @set_time_limit(0);
            }
            $this->log('Setting up database');
            $installer = new Install\DbInstaller();
            $installer->logger = $this;
            $installer->run();

            $installer = new Install\WebserverInstaller();
            $installer->run();

            $this->log('Setting up template');
            $installer = new Install\TemplateInstaller();
            $installer->run();

            $this->log('Setting up default options');
            $installer = new Install\DefaultOptionsInstaller();
            $installer->run();

            if (isset($input['admin_password']) && strlen($input['admin_password'])) {
                $this->log('Adding admin user');

                $adminUser = new \User();
                $adminUser->username = $input['admin_username'];
                $adminUser->email = $input['admin_email'];
                $adminUser->password = $input['admin_password'];
                $adminUser->is_admin = 1;
                $adminUser->is_active = 1;
                $adminUser->save();
                $admin_user_id = $adminUser->id;
                Config::set('microweber.has_admin', 1);
            }

            $this->log('Saving ready config');

            Config::set('microweber.is_installed', 1);

            }


            Config::save($allowed_configs);
            $this->log('done');

            if(Config::get('microweber.has_admin') and !is_cli() and isset($admin_user_id)){
                mw()->user_manager->make_logged($admin_user_id);
            }

            return 'done';
        }

        $layout = new View($view);

        $defaultDbEngine = Config::get('database.default');

        if (!$defaultDbEngine) {
            $defaultDbEngine = 'mysql';
        }
        if (extension_loaded('pdo_sqlite')) {
            // $defaultDbEngine = 'sqlite';
        }

        $dbEngines = Config::get('database.connections');

        if (!$dbEngines) {
            $dbEngines = json_decode('{"sqlite":{"driver":"sqlite","database":"","prefix":""},"mysql":{"driver":"mysql","host":"localhost","database":"forge","username":"forge","password":"","charset":"utf8","collation":"utf8_unicode_ci","prefix":"","strict":false},"pgsql":{"driver":"pgsql","host":"localhost","database":"forge","username":"forge","password":"","charset":"utf8","prefix":"","schema":"public"},"sqlsrv":{"driver":"sqlsrv","host":"localhost","database":"database","username":"root","password":"","prefix":""}}', true);
        }
        foreach ($dbEngines as $driver => $v) {
            if (!extension_loaded("pdo_$driver") and isset($dbEngines[$driver])) {
                unset($dbEngines[$driver]);
            }
        }
        if (!isset($dbEngines[$defaultDbEngine])) {
            $dbEngines[$defaultDbEngine] = false;
        }

        $config = array();
        if (isset($dbEngines[$defaultDbEngine]) and is_array($dbEngines[$defaultDbEngine])) {
            $config = $dbEngines[$defaultDbEngine];
        }
        $viewData = [
            'config' => $config,
            'dbDefaultEngine' => $defaultDbEngine,
            'dbEngines' => array_keys($dbEngines),
            'dbEngineNames' => [
                'mysql' => 'MySQL',
                'sqlite' => 'SQLite',
                'sqlsrv' => 'Microsoft SQL Server',
                'pgsql' => 'PostgreSQL',
            ],
        ];

        $domain = false;
        if (isset($_SERVER['HTTP_HOST'])) {
            $domain = $_SERVER['HTTP_HOST'];
            $domain = str_replace('www.', '', $domain);
            $domain = str_replace('.', '_', $domain);
            $domain = str_replace('-', '_', $domain);
            $domain = substr($domain, 0, 10);
        }

        if ((!isset($viewData['config']['prefix']) or !$viewData['config']['prefix']) and $domain) {
            $viewData['config']['prefix'] = $domain . '_';
        }
        if (extension_loaded('pdo_sqlite') and $domain) {
            $sqlite_path = normalize_path(storage_path() . DS . $domain . '.sqlite', false);
            $viewData['config']['db_name_sqlite'] = $sqlite_path;
        }
        if(Config::get('microweber.pre_configured')){
        $viewData['pre_configured'] = Config::get('microweber.pre_configured');
            if(Config::get('microweber.pre_configured_input') and is_array(Config::get('microweber.pre_configured_input'))){
            $viewData['config'] = array_merge( $viewData['config'],Config::get('microweber.pre_configured_input'));
            }
        }
        $layout->set($viewData);

        $is_installed = mw_is_installed();
        if ($is_installed) {
            App::abort(403, 'Unauthorized action. Microweber is already installed.');
        }
        $layout->assign('done', $is_installed);
        $layout = $layout->__toString();
        Cache::flush();
        return $layout;
    }

    public function log($text)
    {
        $log_file = userfiles_path() . 'install_log.txt';
        if (!is_file($log_file)) {
            @touch($log_file);
        }

        if ($this->command_line_logger and is_object($this->command_line_logger) and method_exists($this->command_line_logger, 'info')) {
            $this->command_line_logger->info($text);
        }
        if (is_file($log_file)) {
            $json = array('date' => date('H:i:s'), 'msg' => $text);

            if ($text == 'done' or $text == 'Preparing to install') {
                @file_put_contents($log_file, $text . "\n");
            } else {
                @file_put_contents($log_file, $text . "\n", FILE_APPEND);
            }
        }
    }


    private function _can_i_use_artisan_key_generate_command()
    {
        $yes_i_can = true;
        if (!$this->_is_escapeshellarg_available()) {
            $yes_i_can = false;
        }
        if (!file_exists(base_path() . DIRECTORY_SEPARATOR . '.env')) {
            $yes_i_can = false;
        }

        if (!is_writable(base_path() . DIRECTORY_SEPARATOR . '.env')) {
            $yes_i_can = false;
        }

        return $yes_i_can;

    }

    private function _is_escapeshellarg_available()
    {
        static $available;

        if (!isset($available)) {
            $available = true;
            if (ini_get('safe_mode')) {
                $available = false;
            } else {
                $d = ini_get('disable_functions');
                $s = ini_get('suhosin.executor.func.blacklist');
                if ("$d$s") {
                    $array = preg_split('/,\s*/', "$d,$s");
                    if (in_array('escapeshellarg', $array)) {
                        $available = false;
                    }
                }
            }
        }

        return $available;
    }
}
