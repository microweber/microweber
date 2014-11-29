<?php


namespace Microweber\Controllers;

use Microweber\View;
use Microweber\Utils\Installer;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

use Module;
use \Session;

class AdminController extends Controller
{

 

    public function index()
    {
        $is_installed = mw_is_installed();

        if (!$is_installed) {
            App::abort(403, 'Unauthorized action. Microweber is not installed.');
        }


        /////////////////////////////////

        if (!defined('MW_BACKEND')) {
            define('MW_BACKEND', true);
        }

        //create_mw_default_options();
        mw()->content_manager->define_constants();
        //   mw()->ui();


        if (defined('TEMPLATE_DIR')) {
            $load_template_functions = TEMPLATE_DIR . 'functions.php';
            if (is_file($load_template_functions)) {
                include_once($load_template_functions);
            }
        }


        event_trigger('mw.admin');

        event_trigger('mw_backend');


        $view = modules_path() . 'admin/index.php';

        $layout = new View($view);

        $layout = $layout->__toString();


        $layout = mw()->parser->process($layout);
        event_trigger('on_load');

        //$layout = mw()->parser->process($l, $options = false);
        // $layout = mw()->parser->process($l, $options = false);
        $layout = execute_document_ready($layout);

        event_trigger('mw.admin.header');

        $apijs_loaded = mw()->url->site('apijs');
        $apijs_settings_loaded = mw()->url->site('apijs_settings') . '?id=' . CONTENT_ID;

        // $is_admin = mw()->user->is_admin();
        $default_css = '<link rel="stylesheet" href="' . mw_includes_url() . 'default.css" type="text/css" />';
        if (!stristr($layout, $apijs_loaded)) {
            $rep = 0;


            $default_css = $default_css . "\r\n" . '<script src="' . $apijs_settings_loaded . '"></script>' . "\r\n";
            $default_css = $default_css . "\r\n" . '<script src="' . $apijs_loaded . '"></script>' . "\r\n";
            $layout = str_ireplace('<head>', '<head>' . $default_css, $layout, $rep);
        }


        $template_headers_src = mw()->template->admin_head(true);
        if ($template_headers_src != false and $template_headers_src != '') {
            $layout = str_ireplace('</head>', $template_headers_src . '</head>', $layout, $one);
        }
        return $layout;

        if (isset($_REQUEST['debug'])) {
            mw()->content_manager->debug_info();
            $is_admin = mw()->user->is_admin();
            if ($is_admin == true) {

            }
        }
        exit();


        ////////////////


        $view = modules_path() . 'admin/index.php';

        $layout = new View($view);
        $layout = $layout->__toString();


        $layout = mw()->parser->process($layout);


        return $layout;

    }


}
