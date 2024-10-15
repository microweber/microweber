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
    private $hasNoAdmin;

    public function __construct()
    {
        $this->app = mw();

        event_trigger('mw.init');
        $is_installed = mw_is_installed();

        if ($is_installed) {
            $this->hasNoAdmin = User::where('is_admin', 1)->limit(1)->count('id') == 0;
        }
    }

    public function index(Request $request)
    {

        return $this->render();
    }

    public function dashboard(Request $request)
    {
        if ($this->hasNoAdmin) {
            return $this->index($request);
        }

        return view('admin::admin.dashboard');
    }

    public function view($layout, $params = [])
    {
        $this->render_content = LaravelView::make($layout, $params)->render();

        return $this->render();
    }

//    public function webAppManifest()
//    {
//        $website_name = get_option('website_name', 'website');
//        $hostname = site_hostname();
//        if (!$website_name) {
//            $website_name = 'Microweber';
//        }
//        $favicon_image = get_favicon_image();
//        if (!$favicon_image) {
//            $favicon_image = site_url('favicon.ico');
//        }
//
//
//        $maskable_icon = get_option('maskable_icon', 'website');
//        if (!$maskable_icon) {
//            $maskable_icon = asset('vendor/microweber-packages/frontend-assets/img/logo-mobile.svg');
//        }
//        $manifest_app_icon = get_option('manifest_app_icon', 'website');
//        if (!$manifest_app_icon) {
//            $manifest_app_icon = modules_url() . 'microweber/api/libs/mw-ui/assets/img/logo-144x144.png';
//        }
//
//        $manifest = [
//            "name" => "$website_name on $hostname",
//            "short_name" => $website_name,
//            "description" => $website_name . " Admin",
//            "start_url" => admin_url(),
//            "scope" => site_url(),
//            "background_color" => "#2196f3",
//            "theme_color" => "#2196f3",
//            "icons" => [
//                [
//                    "src" => $manifest_app_icon,
//                    "sizes" => "144x144",
//                    "type" => "image/png",
//                    "purpose" => "any"
//                ],
//
//                [
//                    "src" => $maskable_icon,
//                    "purpose" => "maskable"
//                ], [
//                    "src" => $favicon_image,
//                    "purpose" => "any"
//                ],
//            ],
//            "display" => "standalone"
//        ];
//
//        $manifestJson = json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
//
//        return response($manifestJson)->header('Content-Type', 'application/manifest+json');
//    }


    public function render()
    {

        $is_installed = mw_is_installed();

        if (!$is_installed) {

            $installer = new InstallController();

            return $installer->index();

        } elseif (defined('MW_VERSION')) {
            $this->app->update->perform_post_update_if_needed();
        }

        $force_https = Config::get('microweber.force_https');

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
        app()->content_manager->define_constants();

        if (defined('TEMPLATE_DIR')) {
            app()->template_manager->boot_template();
        }

        $view = modules_path() . 'admin/';

        $hasNoAdmin = User::where('is_admin', 1)->limit(1)->count('id');

        if (!$hasNoAdmin) {
            $this->hasNoAdmin();
        }


        $hasNoAdmin = User::where('is_admin', 1)->limit(1)->count();

        //   $view .= (!$hasNoAdmin ? 'create' : 'index') . '.php';
        $view .= (!$hasNoAdmin ? 'create_main' : 'index_main') . '.php';
        $layout = new MicroweberView($view);

        if ($this->render_content) {
            $layout->assign('render_content', $this->render_content);
        }
        $layout = $layout->__toString();

        //   $layout = app(StringBlade::class)->render($layout, []);

        $layout = mw()->parser->process($layout);
        event_trigger('on_load');


        //event_trigger('mw.admin.header');

        //  return $layout;
        return view('admin::layouts.legacy', ['content' => $layout])->render();;
        //   return app(StringBlade::class)->render($layout, []);
    }

    private function hasNoAdmin()
    {

        if (mw()->url_manager->param('mw_install_create_user')) {
            $this->execCreateAdmin();
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
         //   Config::set('microweber.has_admin', 1);
           // Config::save(array('microweber'));
            Auth::login($adminUser);
        }
    }
}
