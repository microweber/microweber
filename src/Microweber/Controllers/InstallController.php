<?php

namespace Microweber\Controllers;

use Microweber\View;
use Microweber\Install;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

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

    public function index()
    {

        $is_installed = mw_is_installed();
        if ($is_installed) {
            return 'Microweber is already installed!';
        }


        $view = MW_PATH . 'Views/install.php';

        $connection = Config::get('database.connections');
        $layout = new View($view);
        $is_installed = mw_is_installed();
        if ($is_installed) {
            App::abort(403, 'Unauthorized action. Microweber is already installed.');
        }
        $layout->assign('data', $connection);
        $layout->assign('done', $is_installed);
        $layout = $layout->__toString();
        $input = Input::all();

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
            $mysql = Config::get('database.connections.mysql');
            if (!empty($errors)) {

                //$msg = array('message',implode("\n",$errors),'error'=>$errors);
                return implode("\n", $errors);
            }
            Config::set('database.connections.mysql.host', $input['db_host']);
            Config::set('database.connections.mysql.username', $input['db_user']);
            Config::set('database.connections.mysql.password', $input['db_pass']);
            Config::set('database.connections.mysql.database', $input['db_name']);
            Config::set('database.connections.mysql.prefix', $input['table_prefix']);

            if (isset($input['default_template']) and $input['default_template'] !=false) {
                Config::set('microweber.install_default_template', $input['default_template']);
            }
            if (isset($input['with_default_content']) and $input['with_default_content'] !=false) {
                Config::set('microweber.install_default_template_content', 1);
            }

            Config::save();

            Cache::flush();

            $install_finished = false;
            $mysql = Config::get('database.connections.mysql');
            try {
                DB::connection()->getDatabaseName();
            } catch (\PDOException $e) {
                return ('Error: ' . $e->getMessage() . "\n");
            } catch (\Exception $e) {
                return ('Error: ' . $e->getMessage() . "\n");
            }

            $installer = new Install\DbInstaller();
            $installer->run();

            $installer = new Install\WebserverInstaller();
            $installer->run();

            $installer = new Install\TemplateInstaller();
            $installer->run();



            Config::set('microweber.is_installed', 1);

            $adminUser = new \User;
            $adminUser->username = $input['admin_username'];
            $adminUser->email = $input['admin_email'];
            $adminUser->password = $input['admin_password'];
            $adminUser->is_admin = 1;
            $adminUser->is_active = 1;
            $adminUser->save();

            Config::save();
            return 'done';
        }
        return $layout;


    }
}