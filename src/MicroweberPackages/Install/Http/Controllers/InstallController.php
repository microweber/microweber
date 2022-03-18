<?php

namespace MicroweberPackages\Install\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Cache;
use MicroweberPackages\ComposerClient\Client;
use MicroweberPackages\Package\MicroweberComposerClient;
use MicroweberPackages\Package\MicroweberComposerPackage;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Translation\TranslationPackageInstallHelper;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\Utils\Http\Http;
use MicroweberPackages\Utils\Misc\License;
use MicroweberPackages\View\View;
use MicroweberPackages\Install;

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

    public function downloadTemplate()
    {
        $packageName = request()->get('download_template');

        $params = [];
        $params['require_name'] = $packageName;

        $runner = new MicroweberComposerClient();

        $license = new License();
        $getLicenses = $license->getLicenses();
        if (!empty($getLicenses)) {
            $runner->setLicenses($getLicenses);
        }

        $request = $runner->requestInstall($params);
        if (!isset($request['form_data_module_params'])) {
            return false;
        }

        $request = $runner->requestInstall($request['form_data_module_params']);

        dump($request);

    }

    public function installTemplateModalView()
    {
        $packageName = request()->get('install_template_modal');

        $packageManager = new Client();

        $license = new License();
        $getLicenses = $license->getLicenses();
        if (!empty($getLicenses)) {
            $packageManager->setLicenses($getLicenses);
        }

        $getPackage = $packageManager->getPackageByName($packageName);
        if (empty($getPackage)) {
            return;
        }

        $template = MicroweberComposerPackage::format($getPackage);

        return view('install::install_template_modal', ['template'=>$template]);
    }

   public function selectTemplateView()
    {
        $templates = $this->_getMarketTemplatesForInstallScreen();

        return view('install::select_template', ['templates'=>$templates]);
    }

    public function index($input = null)
    {
        if (!defined('MW_INSTALL_CONTROLLER')) {
            define('MW_INSTALL_CONTROLLER', true);
        }

        if (!is_array($input) || empty($input)) {
            $input = Request::all();
        }

        $is_installed = mw_is_installed();
        if ($is_installed) {
            return 'Microweber is already installed!';
        }

        if (isset($input['save_license'])) {
            $license = new License();
            $saveLicense = $license->saveLicense($input['license_key'], $input['license_rel_type']);
            if ($saveLicense) {
                return ['validated'=>true];
            }
            return ['validated'=>false];
        }

        if (isset($input['download_template'])) {
            return $this->downloadTemplate();
        }

        if (isset($input['install_template_modal'])) {
            return $this->installTemplateModalView();
        }

        if (isset($input['select_template'])) {
            return $this->selectTemplateView();
        }

        if (isset($input['get_templates_for_install_screen'])) {
            return $this->_get_templates_for_install_screen();
        }

        if (isset($input['get_market_templates_for_install_screen'])) {
            return $this->_getMarketTemplatesForInstallScreen();
        }

        if (isset($input['install_package_by_name'])) {
            return $this->_install_package_by_name($input['install_package_by_name']);
        }


        $allowed_configs = array('database', 'microweber');

        $env = $this->app->environment();

        $view = dirname(dirname(__DIR__)) . '/Resources/views/install.php';
        $view = normalize_path($view, false);

        $install_step = null;
        if (isset($input['install_step'])) {
            $install_step = trim($input['install_step']);

        }

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


            if (isset($input['db_driver'])) {
                $dbDriver = $input['db_driver'];
            } else {
                $dbDriver = 'mysql';
            }

            $errors = array();

            if ($dbDriver == 'mysql') {
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
            } else {
                if (is_null($input['db_user'])) {
                    $input['db_user'] = '';
                }
                if (is_null($input['db_user'])) {
                    $input['db_host'] = '';
                }
                if (is_null($input['db_name'])) {
                    $input['db_name'] = '';
                }
                if (is_null($input['db_host'])) {
                    $input['db_host'] = '';
                }
            }

            if (!empty($errors)) {
                return implode("\n", $errors);
            }

            Config::set('database.default', $dbDriver);
            if ($dbDriver == 'sqlite') {
                if (isset($input['db_name_sqlite'])) {
                    $input['db_name'] = $input['db_name_sqlite'];
                    $input['db_name'] = str_replace(':.', '.', $input['db_name']);
                }
                Config::set("database.connections.$dbDriver.database", $input['db_name']);
                if (isset($input['db_name']) and $input['db_name'] != ':memory:' and !file_exists($input['db_name'])) {
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
                Config::set('microweber.disable_model_cache', 0);
            }
            if (isset($input['clean_pre_configured'])) {
                Config::set('microweber.pre_configured', null);
                Config::set('microweber.pre_configured_input', null);
            }

            if (isset($input['admin_url'])) {
                Config::set('microweber.admin_url', $input['admin_url']);
            }
            if (!is_cli()) {
                Config::set('app.url', site_url());
            }
            Config::set('app.fallback_locale', 'en');

            if (isset($input['site_lang'])) {
                Config::set('app.locale', $input['site_lang']);
                Config::set('microweber.site_lang', $input['site_lang']);
            }

            if (Config::get('app.key') == 'YourSecretKey!!!') {
                if (!is_cli()) {
                    $_SERVER['argv'] = array();
                }
                $this->log('Generating key');
                /*if ($this->_can_i_use_artisan_key_generate_command()) {
                    Artisan::call('key:generate', [
                        '--force' => true,
                    ]);
                }*/
                $fallback_key = str_random(32);
                $fallback_key_str = 'base64:' . base64_encode($fallback_key);
                $allowed_configs[] = 'app';
                Config::set('app.key', $fallback_key_str);

            }

            $this->log('Saving config');
            Config::save($allowed_configs);
            Cache::flush();

            if ($config_only) {
                Config::set('microweber.pre_configured', 1);
                Config::set('microweber.pre_configured_input', $input);
            } else {

                try {
                    DB::connection()->getPdo();
                } catch (\Exception $e) {
                    $this->log("error");

                    //  return ("Error: Could not connect to the database.  Please check your configuration. Error: " . $e->getMessage() .' on ' . $e->getLine() . " – " . $e->getFile() );
                    return ("Error: Could not connect to the database.  Please check your configuration. " . $e->getMessage());
                }

                try {
                    DB::connection($dbDriver)->getDatabaseName();
                } catch (\Exception $e) {
                    $this->log('Error in database connection');
                    return 'Error: ' . $e->getMessage() . "\n";
                }

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

                if (!$install_step or $install_step == 1) {
                    $this->log('Setting up database');
                    $installer = new Install\DbInstaller();
                    $installer->logger = $this;
                    $installer->run();
                }

                if (!$install_step or $install_step == 2) {
                    $installer = new Install\WebserverInstaller();
                    $installer->run();
                }

                if (!$install_step or $install_step == 3) {
                    $this->log('Setting up modules');
                    $installer = new Install\ModulesInstaller();
                    $installer->logger = $this;
                    $installer->run();
                }

                if (!$install_step or $install_step == 4) {
                    $this->log('Setting up template');
                    $installer = new Install\TemplateInstaller();
                    if (isset($input['site_lang'])) {
                        $installer->setLanguage($input['site_lang']);
                    }
                    $installer->logger = $this;
                    $installer->run();
                }

                if (!$install_step or $install_step == 5) {
                    $this->log('Setting up default options');
                    $installer = new Install\DefaultOptionsInstaller();
                    if (isset($input['site_lang'])) {
                        $installer->setLanguage($input['site_lang']);
                    }
                    $installer->run();
                }

                if (!$install_step or $install_step == 6) {
                    $this->log('Setting up modules after template install');
                    $installer = new Install\ModulesInstaller();
                    $installer->logger = $this;
                    $installer->run();


                }

                if (!$install_step or $install_step == 7) {
                    if (isset($input['site_lang'])) {
                        if ($dbDriver == 'sqlite') {
                            \DB::connection('sqlite')->getPdo()->sqliteCreateFunction('md5', 'md5');
                        }

                        $selected_template = Config::get('microweber.install_default_template');
                        app()->content_manager->define_constants(['active_site_template' => $selected_template]);
                        if (defined('TEMPLATE_DIR')) {
                            app()->template_manager->boot_template();
                        }
                        $this->log('Running migrations after install for template' . $selected_template);
                        $installer = new Install\DbInstaller();
                        $installer->logger = $this;
                        $installer->createSchema();


//                         language is moved to json files and does not require install anymore
//                        $this->log('Importing the language package..');
//                        TranslationPackageInstallHelper::$logger = $this;
//                        TranslationPackageInstallHelper::installLanguage($input['site_lang']);
                    }


                }

                if ($install_step) {
                    if ($install_step != 'finalize') {
                        $install_step_return = array('install_step' => $install_step + 1);
                        if ($install_step == 7) {
                            if (isset($input['admin_email']) and isset($input['subscribe_for_update_notification'])) {
                                $this->reportInstall($input['admin_email'], $input['subscribe_for_update_notification']);
                            }
                            $install_step_return['finalize'] = true;
                            $install_step_return['install_step'] = 'finalize';
                        }

                        return $install_step_return;
                    }
                } elseif (!$install_step) {
                    if (isset($input['admin_email']) and isset($input['subscribe_for_update_notification'])) {
                        $this->reportInstall($input['admin_email'], $input['subscribe_for_update_notification']);
                    }
                }


                if (isset($input['admin_password']) && strlen($input['admin_password'])) {
                    if (isset($input['admin_email']) or isset($input['admin_username'])) {
                        if (!isset($input['admin_username'])) {
                            $input['admin_username'] = 'admin';
                        }
                        if (!isset($input['admin_email'])) {
                            $input['admin_email'] = 'noreply@localhost';
                        }


                        $check_if_has_admin = (new User())->where('is_admin', 1)->first();

                        if (!$check_if_has_admin) {
                            $this->log('Adding admin user');

                            $adminUser = new User();
                            $adminUser->username = $input['admin_username'];
                            $adminUser->email = $input['admin_email'];
                            $adminUser->password = $input['admin_password'];
                            $adminUser->is_admin = 1;
                            $adminUser->is_active = 1;
                            $adminUser->save();
                            $admin_user_id = $adminUser->id;
                        } else {
                            $admin_user_id = $check_if_has_admin->id;
                        }
                        Config::set('microweber.has_admin', 1);
                    }
                }

                $this->log('Saving ready config');

                Config::set('microweber.is_installed', 1);

            }


            Config::save($allowed_configs);

            if (Config::get('microweber.has_admin') and !is_cli() and isset($admin_user_id)) {
                mw()->user_manager->make_logged($admin_user_id, true);
            }

            event_trigger('mw.install.complete', $input);

            return 'done';
        }

        $layout = new View($view);

        $defaultDbEngine = Config::get('database.default');

        if (extension_loaded('pdo_sqlite')) {
            $defaultDbEngine = 'sqlite';
        }

        if (!$defaultDbEngine) {
            $defaultDbEngine = 'mysql';
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
            $domain = str_replace(':', '_', $domain);
            $domain = substr($domain, 0, 10);
        }

        if ((!isset($viewData['config']['prefix']) or !$viewData['config']['prefix']) and $domain) {
            $pre = $domain;
            if (is_numeric(substr($pre, 0, 1))) {
                $pre = 'p' . $pre;
            }
            $viewData['config']['prefix'] = $pre . '_';
        }
        if (extension_loaded('pdo_sqlite') and $domain) {
            $sqlite_path = normalize_path(storage_path() . DS . $domain . '.sqlite', false);
            $viewData['config']['db_name_sqlite'] = $sqlite_path;
        }
        if (Config::get('microweber.pre_configured')) {
            $viewData['pre_configured'] = Config::get('microweber.pre_configured');
            if (Config::get('microweber.pre_configured_input') and is_array(Config::get('microweber.pre_configured_input'))) {
                $viewData['config'] = array_merge($viewData['config'], Config::get('microweber.pre_configured_input'));
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

    private function reportInstall($email, $sendMail = false)
    {
        if (defined('MW_UNIT_TEST')) {
            return;
        }

        $um = new \MicroweberPackages\App\Managers\UpdateManager(app());
        $data = $um->collect_local_data();
        if ($sendMail) {
            $data['email'] = $email;
        }
        $postData = array();
        $postData['postdata'] = base64_encode(@json_encode($data));
        $http = new Http(app());

        try {
            $http->url('https://installreport.services.microweberapi.com')->set_timeout(10)->post($postData);
        } catch (\Exception $e) {
            //maybe internet connection problem
        }

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

    public function setLogInfo($text)
    {
        return $this->log($text);
    }

    private function _get_templates_for_install_screen()
    {
        //used in ajax
        $templates_opts = array('remove_hidden_from_install_screen' => true);
        $templates = site_templates($templates_opts);
        return $templates;
    }

    private function _getMarketTemplatesForInstallScreen()
    {
        $ready = array();
        $runner = new Client();
        $results = $runner->search();

        if ($results) {
            foreach ($results as $k => $result) {
                $latestVersion = end($result);
                if (!isset($latestVersion['type'])) {
                    continue;
                }
                if ($latestVersion['type'] !== 'microweber-template') {
                    continue;
                }/*
                if (isset($latestVersion['dist']['type']) && $latestVersion['dist']['type'] == 'zip') {
                    $ready[] = MicroweberComposerPackage::format($latestVersion);
                }*/
                $ready[] = MicroweberComposerPackage::format($latestVersion);
            }
        }

        return $ready;
    }

    private function _install_package_by_name($package_name)
    {
        $runner = new MicroweberComposerClient();
        $results = $runner->requestInstall(['require_name' => $package_name]);
        $runner->requestInstall($results['form_data_module_params']);

        return $results;
    }

    private function _can_i_use_artisan_key_generate_command()
    {
        $yes_i_can = true;
        if (!$this->_is_escapeshellarg_available()) {
            $yes_i_can = false;
        }

        if (!$this->_is_putenv_available()) {
            $yes_i_can = false;
        }


        if (!$this->_is_shell_exec_available()) {
            $yes_i_can = false;
        }


        if (!file_exists(base_path() . DIRECTORY_SEPARATOR . '.env')) {
            $yes_i_can = false;
        }
        $basedir = @ini_get('open_basedir');
        if ($basedir) {
            $yes_i_can = false;
        }

        if (!is_writable(base_path() . DIRECTORY_SEPARATOR . '.env')) {
            $yes_i_can = false;
        }

        return $yes_i_can;

    }

    private function _is_escapeshellarg_available()
    {

        return php_can_use_func('escapeshellarg');


    }

    private function _is_putenv_available()
    {


        return php_can_use_func('putenv');
    }


    private function _is_shell_exec_available()
    {


        $available = true;
        if (ini_get('safe_mode')) {
            $available = false;
        } else {
            $d = ini_get('disable_functions');
            $s = ini_get('suhosin.executor.func.blacklist');


            if ("$d$s") {
                $array = preg_split('/,\s*/', "$d,$s");
                if (in_array('shell_exec', $array)) {
                    $available = false;
                }
                if (in_array('exec', $array)) {
                    $available = false;
                }
            }
        }

        return $available;
    }
}
