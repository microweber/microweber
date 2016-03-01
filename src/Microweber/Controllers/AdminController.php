<?php

namespace Microweber\Controllers;

use Microweber\View;
use User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Auth;

class AdminController extends Controller
{
    /** @var \Microweber\Application */
    public $app;

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }

    public function index()
    {
        $is_installed = mw_is_installed();

        if (!$is_installed) {
            if (!$is_installed) {
                $installer = new InstallController();

                return $installer->index();
            }
        } elseif (defined('MW_VERSION')) {
            $config_version = Config::get('microweber.version');
            if ($config_version != MW_VERSION) {
                $this->app->update->post_update(MW_VERSION);
            }
        }

        if (!defined('MW_BACKEND')) {
            define('MW_BACKEND', true);
        }

        //create_mw_default_options();
        mw()->content_manager->define_constants();

        if (defined('TEMPLATE_DIR')) {
            $load_template_functions = TEMPLATE_DIR.'functions.php';
            if (is_file($load_template_functions)) {
                include_once $load_template_functions;
            }
        }

        event_trigger('mw.admin');
        event_trigger('mw_backend');
        $view = modules_path().'admin/';

        $hasNoAdmin = User::where('is_admin', 1)->limit(1)->count();

        if (!$hasNoAdmin) {
            $this->hasNoAdmin();
        }
        $hasNoAdmin = User::where('is_admin', 1)->limit(1)->count();

        $view .= (!$hasNoAdmin ? 'create' : 'index').'.php';

        $layout = new View($view);
        $layout = $layout->__toString();

        $layout = mw()->parser->process($layout);
        event_trigger('on_load');

        $layout = execute_document_ready($layout);

        event_trigger('mw.admin.header');

        $apijs_loaded = mw()->template->get_apijs_url();
        $apijs_settings_loaded = mw()->template->get_apijs_settings_url();

        $default_css = '<link rel="stylesheet" href="'.mw_includes_url().'default.css" type="text/css" />';
        if (!stristr($layout, $apijs_loaded)) {
            $rep = 0;
            $default_css = $default_css."\r\n".'<script src="'.$apijs_settings_loaded.'"></script>'."\r\n";
            $default_css = $default_css."\r\n".'<script src="'.$apijs_loaded.'"></script>'."\r\n";
            $layout = str_ireplace('<head>', '<head>'.$default_css, $layout, $rep);
        }

        $template_headers_src = mw()->template->admin_head(true);
        if ($template_headers_src != false and $template_headers_src != '') {
            $layout = str_ireplace('</head>', $template_headers_src.'</head>', $layout, $one);
        }

        return $layout;
    }

    private function hasNoAdmin()
    {
        if (!$this->checkServiceConfig()) {
            $this->registerMwClient();
        }
        if (mw()->url_manager->param('mw_install_create_user')) {
            $this->execCreateAdmin();
        }
    }

    private function checkServiceConfig()
    {
        $serviceConfig = Config::get('services.microweber');
        if ($serviceConfig) {
            if (trim(implode('', $serviceConfig))) {
                return true;
            }
        }

        return false;
    }

    private function registerMwClient()
    {
        $key = Config::get('app.key');

        $client = new \Guzzle\Service\Client('https://login.microweber.com/api/v1/client/');

        $domain = site_url();
        $domain = substr($domain, strpos($domain, '://') + 3);
        $domain = str_replace('/', '', $domain);

        $request = $client->createRequest('POST', "config/$domain");
        //dd($request, $request);
        $request->setPostField('token', $key);
        $response = $client->send($request);
        if (200 == $response->getStatusCode()) {
            $body = (string) $response->getBody();
            $body = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $body, MCRYPT_MODE_ECB);
            $body = trim($body);
            $body = (array) json_decode($body);

            Config::set('services.microweber', $body);
            Config::save(array('microweber', 'services'));

            save_option([
                'option_value' => 'y',
                'option_key' => 'enable_user_microweber_registration',
                'option_group' => 'users',
            ]);
        } else {
            // $reason = $response->getReasonPhrase();
            // dd(__FILE__, $reason, $response->getStatusCode());
        }
    }

    private function execCreateAdmin()
    {
        $input = Input::only(['admin_username', 'admin_email', 'admin_password']);
        $adminUser = new User();
        if (strlen(trim(implode('', $input)))) {
            $adminUser->username = $input['admin_username'];
            $adminUser->email = $input['admin_email'];
            $adminUser->password = $input['admin_password'];
            $adminUser->is_admin = 1;
            $adminUser->is_active = 1;
            $adminUser->save();
            Config::set('microweber.has_admin', 1);
            Config::save(array('microweber'));
            Auth::login($adminUser);
        }
    }
}
