<?php

namespace MicroweberPackages\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Auth;
use MicroweberPackages\App\Http\Middleware\AllowedIps;
use MicroweberPackages\Install\Http\Controllers\InstallController;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\View\StringBlade;
use MicroweberPackages\View\View as MicroweberView;
use Illuminate\Support\Facades\View as LaravelView;

class AdminController extends Controller
{

    public $middleware = [
        [
            'middleware' => 'admin',
            'options' => []
        ],
        [
            'middleware' => 'xss',
            'options' => []
        ], [
            'middleware' => AllowedIps::class,
            'options' => []
        ]
    ];

    /** @var \MicroweberPackages\App\LaravelApplication */

    public $app;
    private $render_content;

    public function __construct()
    {
        $this->app = mw();

        event_trigger('mw.init');
    }

    public function index(Request $request)
    {
        return $this->render();
    }

    public function view($layout, $params = [])
    {
        $this->render_content = LaravelView::make($layout, $params)->render();

        return $this->render();
    }




    public function render()
    {

        $is_installed = mw_is_installed();

        if (!$is_installed) {

            $installer = new InstallController();

            return $installer->index();

        } elseif (defined('MW_VERSION')) {
            $config_version = Config::get('microweber.version');
            if ($config_version != MW_VERSION) {
                $this->app->update->post_update(MW_VERSION);
            }
        }

        $force_https = \Config::get('microweber.force_https');

        if ($force_https and !is_cli()) {
            if (!is_https()) {
                $https = str_ireplace('http://', 'https://', url_current());
                return mw()->url_manager->redirect($https);
            }
        }


        if (!defined('MW_BACKEND')) {
            define('MW_BACKEND', true);
        }


        //create_mw_default_options();
        mw()->content_manager->define_constants();

        if (defined('TEMPLATE_DIR')) {
            app()->template_manager->boot_template();
        }

        event_trigger('mw.admin');
        event_trigger('mw_backend');
        $view = modules_path() . 'admin/';

        $hasNoAdmin = User::where('is_admin', 1)->limit(1)->count('id');

        if (!$hasNoAdmin) {
            $this->hasNoAdmin();
        }


        $hasNoAdmin = User::where('is_admin', 1)->limit(1)->count();

       $view .= (!$hasNoAdmin ? 'create' : 'index') . '.php';
     //  $view .= (!$hasNoAdmin ? 'create' : 'index_main') . '.php';
        $layout = new MicroweberView($view);

        if ($this->render_content) {
            $layout->assign('render_content', $this->render_content);
        }
        $layout = $layout->__toString();

        $layout = mw()->parser->process($layout);
        event_trigger('on_load');


        event_trigger('mw.admin.header');
   //   return view('admin::layouts.app', ['content' => $layout])->render();;
       return app(StringBlade::class)->render($layout, []);
    }

    private function hasNoAdmin()
    {
//        if (!$this->checkServiceConfig()) {
//            $this->registerMwClient();
//        }
        if (mw()->url_manager->param('mw_install_create_user')) {
            $this->execCreateAdmin();
        }
    }

    private function checkServiceConfig()
    {
        $serviceConfig = Config::get('services.microweber');
        if (!$serviceConfig) {
            return false;
        }
        if (trim(implode('', $serviceConfig))) {
            return true;
        }

        return false;
    }


    private function registerMwClient()
    {
        return;

        $key = Config::get('app.key');

        $client = new \Guzzle\Service\Client('https://login.microweber.com/api/v1/client/');

        $domain = site_url();
        $domain = substr($domain, strpos($domain, '://') + 3);
        $domain = str_replace('/', '', $domain);
        try {
            $request = $client->createRequest('POST', "config/$domain");
            //dd($request, $request);
            $request->setPostField('token', md5($key));
            $response = $client->send($request);
        } catch (\Exception $e) {
            return;
        }

        if (200 == $response->getStatusCode()) {
            $body = (string)$response->getBody();
            // $body = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $body, MCRYPT_MODE_ECB);
            $body = trim($body);
            $body = (array)json_decode($body);

            Config::set('services.microweber', $body);
            Config::save(array('microweber', 'services'));

            save_option([
                'option_value' => 'y',
                'option_key' => 'enable_user_microweber_registration',
                'option_group' => 'users',
            ]);
        }
    }

    private function execCreateAdmin()
    {
        $input = \Request::only(['admin_username', 'admin_email', 'admin_password']);
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
