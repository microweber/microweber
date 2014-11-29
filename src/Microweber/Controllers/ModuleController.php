<?php


namespace Microweber\Controllers;

use Microweber\View;
use Microweber\Utils\DbInstaller;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

use \Cache;
use \Session;


use Module;


class ModuleController extends Controller
{


    public $return_data = false;
    public $page_url = false;
    public $create_new_page = false;
    public $render_this_url = false;
    public $isolate_by_html_id = false;
    public $functions = array();
    public $page = array();
    public $params = array();
    public $vars = array();
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
        if (!$is_installed) {
            App::abort(403, 'Unauthorized action. Microweber MUST be installed to use modules.');
        }


        if (!defined('MW_API_CALL')) {
            //  	define('MW_API_CALL', true);
        }

        if (!defined("MW_NO_SESSION")) {
            $is_ajax = $this->app->url->is_ajax();
            if (!isset($_SESSION) and $is_ajax == false) {

                if (!defined('MW_SESS_STARTED')) {
                    define('MW_SESS_STARTED', true);
                    // //session_start();
                }

            }
            $editmode_sess = $this->app->user_manager->session_get('editmode');
            if ($editmode_sess == true and !defined('IN_EDIT')) {
                define('IN_EDIT', true);
            }
        }


        $page = false;

        $custom_display = false;
        if (isset($_REQUEST['data-display']) and $_REQUEST['data-display'] == 'custom') {
            $custom_display = true;
        }

        if (isset($_REQUEST['data-module-name'])) {
            $_REQUEST['module'] = $_REQUEST['data-module-name'];
            $_REQUEST['data-type'] = $_REQUEST['data-module-name'];

            if (!isset($_REQUEST['id'])) {
                $_REQUEST['id'] = $this->app->url->slug($_REQUEST['data-module-name'] . '-' . date("YmdHis"));
            }

        }

        if (isset($_REQUEST['data-type'])) {
            $_REQUEST['module'] = $_REQUEST['data-type'];
        }

        if (isset($_REQUEST['display']) and $_REQUEST['display'] == 'custom') {
            $custom_display = true;
        }
        if (isset($_REQUEST['view']) and $_REQUEST['view'] == 'admin') {
            $custom_display = FALSE;
        }

        if ($custom_display == true) {
            $custom_display_id = false;
            if (isset($_REQUEST['id'])) {
                $custom_display_id = $_REQUEST['id'];
            }
            if (isset($_REQUEST['data-id'])) {
                $custom_display_id = $_REQUEST['data-id'];
            }
        }
        if (isset($_REQUEST['from_url'])) {
            $from_url = $_REQUEST['from_url'];
        } else if (isset($_SERVER["HTTP_REFERER"])) {
            $from_url = $_SERVER["HTTP_REFERER"];
        }


        if (isset($from_url) and $from_url != false) {

            if (stristr($from_url, 'editor_tools/wysiwyg')) {

                if (!defined('IN_EDITOR_TOOLS')) {
                    define('IN_EDITOR_TOOLS', true);
                }
            }


            $url = $from_url;
            $from_url2 = str_replace('#', '/', $from_url);

            $content_id = $this->app->url->param('content_id', false, $from_url2);
            if ($content_id == false) {
                $content_id = $this->app->url->param('editpage', false, $from_url2);
            }
            if ($content_id == false) {
                $content_id = $this->app->url->param('editpost', false, $from_url2);
            }
            if ($content_id == false) {
                $content_id = $this->app->url->param('content-id', false, $from_url2);
            }
            if ($content_id == false) {
                $action_test = $this->app->url->param('action', false, $from_url2);

                if ($action_test != false) {
                    $action_test = str_ireplace('editpage:', '', $action_test);
                    $action_test = str_ireplace('editpost:', '', $action_test);
                    $action_test = str_ireplace('edit:', '', $action_test);

                    $action_test = intval($action_test);
                    if ($action_test != 0) {
                        $content_id = $action_test;
                        $this->app->content_manager->define_constants(array('id' => $content_id));
                    }

                }

            }


            if (strpos($url, '#')) {
                $url = substr($url, 0, strpos($url, '#'));
            }
            //$url = $_SERVER["HTTP_REFERER"];
            $url = explode('?', $url);
            $url = $url[0];

            if ($content_id != false) {
                $page = array();
                $page['id'] = $content_id;
                if ($content_id) {
                    $page = $this->app->content_manager->get_by_id($content_id);
                    $url = $page['url'];

                }
            } else {
                if (trim($url) == '' or trim($url) == $this->app->url->site()) {
                    //var_dump($from_url);
                    //$page = $this->app->content_manager->get_by_url($url);
                    $page = $this->app->content_manager->homepage();

                    if (!defined('IS_HOME')) {
                        define('IS_HOME', true);
                    }

                    if (isset($from_url2)) {
                        $mw_quick_edit = $this->app->url->param('mw_quick_edit', false, $from_url2);

                        if ($mw_quick_edit) {
                            $page = false;
                        }
                    }

                } else {
                    if (!stristr($url, admin_url())) {
                        $page = $this->app->content_manager->get_by_url($url);
                    } else {
                        $page = false;
                        if (!defined('PAGE_ID')) {
                            define('PAGE_ID', false);
                        }
                        if (!defined('POST_ID')) {
                            define('POST_ID', false);
                        }
                        if (!defined('CONTENT_ID')) {
                            define('CONTENT_ID', false);
                        }
                    }


                }
            }
        } else {
            $url = $this->app->url->string();
        }


        if (!defined('IS_HOME')) {

            if (isset($page['is_home']) and $page['is_home'] == 'y') {
                define('IS_HOME', true);
            }
        }


        if ($page == false) {
            if (!isset($content_id)) {
                return;
            }


            $this->app->content_manager->define_constants(array('id' => $content_id));
        } else {
            $this->app->content_manager->define_constants($page);
        }
        if (defined('TEMPLATE_DIR')) {
            $load_template_functions = TEMPLATE_DIR . 'functions.php';
            if (is_file($load_template_functions)) {
                include_once($load_template_functions);
            }
        }

        if ($custom_display == true) {

            $u2 = $this->app->url->site();
            $u1 = str_replace($u2, '', $url);

            $this->render_this_url = $u1;
            $this->isolate_by_html_id = $custom_display_id;
            $this->index();
            exit();
        }
        $url_last = false;
        if (!isset($_REQUEST['module'])) {
            $url = $this->app->url->string(0);
            if ($url == __FUNCTION__) {
                $url = $this->app->url->string(0);
            }
            /*
             $is_ajax = $this->app->url->is_ajax();

             if ($is_ajax == true) {
             $url = $this->app->url->string(true);
             }*/

            $url = $this->app->format->replace_once('module/', '', $url);
            $url = $this->app->format->replace_once('module_api/', '', $url);
            $url = $this->app->format->replace_once('m/', '', $url);

            if (is_module($url)) {
                $_REQUEST['module'] = $url;
                $mod_from_url = $url;

            } else {
                $url1 = $url_temp = explode('/', $url);
                $url_last = array_pop($url_temp);

                $try_intil_found = false;
                $temp1 = array();
                foreach ($url_temp as $item) {

                    $temp1[] = implode('/', $url_temp);
                    $url_laset = array_pop($url_temp);

                }

                $i = 0;
                foreach ($temp1 as $item) {
                    if ($try_intil_found == false) {

                        if (is_module($item)) {

                            $url_tempx = explode('/', $url);

                            $_REQUEST['module'] = $item;
                            $url_prev = $url_last;
                            $url_last = array_pop($url_tempx);
                            $url_prev = array_pop($url_tempx);

                            // d($url_prev);
                            $mod_from_url = $item;
                            $try_intil_found = true;
                        }

                    }
                    $i++;
                }

            }
        }

        $module_info = $this->app->url->param('module_info', true);

        if ($module_info) {
            if ($_REQUEST['module']) {
                $_REQUEST['module'] = str_replace('..', '', $_REQUEST['module']);
                $try_config_file = modules_path() . '' . $_REQUEST['module'] . '_config.php';
                $try_config_file = normalize_path($try_config_file, false);
                if (is_file($try_config_file)) {
                    include($try_config_file);

                    if (!isset($config) or !is_array($config)) {
                        return false;
                    }


                    if (!isset($config['icon']) or $config['icon'] == false) {
                        $config['icon'] = modules_path() . '' . $_REQUEST['module'] . '.png';
                        $config['icon'] = $this->app->url->link_to_file($config['icon']);
                    }
                    print json_encode($config);
                    exit();
                }
            }
        }

        $admin = $this->app->url->param('admin', true);

        $mod_to_edit = $this->app->url->param('module_to_edit', true);
        $embed = $this->app->url->param('embed', true);

        $mod_iframe = false;
        if ($mod_to_edit != false) {
            $mod_to_edit = str_ireplace('_mw_slash_replace_', '/', $mod_to_edit);
            $mod_iframe = true;
        }
        //$data = $_REQUEST;

        if (($_POST)) {
            $data = $_POST;
        } else {
            $url = $this->app->url->segment();

            if (!empty($url)) {
                foreach ($url as $k => $v) {
                    $kv = explode(':', $v);
                    if (isset($kv[0]) and isset($kv[1])) {
                        $data[$kv[0]] = $kv[1];
                    }
                }
            }
        }


        $tags = false;
        $mod_n = false;

        if (isset($data['type']) != false) {
            if (trim($data['type']) != '') {
                $mod_n = $data['data-type'] = $data['type'];
            }
        }

        if (isset($data['data-module-name'])) {
            $mod_n = $data['data-type'] = $data['data-module-name'];
            unset($data['data-module-name']);
        }

        if (isset($data['data-type']) != false) {
            $mod_n = $data['data-type'];
        }
        if (isset($data['data-module']) != false) {
            if (trim($data['data-module']) != '') {
                $mod_n = $data['module'] = $data['data-module'];
            }
        }

        if (isset($data['module'])) {
            $mod_n = $data['data-type'] = $data['module'];
            unset($data['module']);
        }

        if (isset($data['type'])) {
            $mod_n = $data['data-type'] = $data['type'];
            unset($data['type']);
        }
        if (isset($data['data-type']) != false) {
            $data['data-type'] = rtrim($data['data-type'], '/');
            $data['data-type'] = rtrim($data['data-type'], '\\');
            $data['data-type'] = str_replace('__', '/', $data['data-type']);
        }
        if (!isset($data)) {
            $data = $_REQUEST;
        }
        if (!isset($data['module']) and isset($mod_from_url) and $mod_from_url != false) {
            $data['module'] = ($mod_from_url);
        }

        if (!isset($data['id']) and isset($_REQUEST['id']) == true) {
            $data['id'] = $_REQUEST['id'];
        }
        if (isset($data['ondrop'])) {

            if (!defined('MW_MODULE_ONDROP')) {
                define('MW_MODULE_ONDROP', true);
            }

            unset($data['ondrop']);
        }
        if ($mod_n == 'layout') {

            if (isset($data['template'])) {
                $t = str_replace('..', '', $data['template']);
                $possible_layout = templates_path() . $t;
                $possible_layout = normalize_path($possible_layout, false);
                if (is_file($possible_layout)) {
                    $l = new \Microweber\View($possible_layout);
                    $layout = $l->__toString();
                    $layout = $this->app->parser->process($layout, $options = false);
                    print $layout;
                    return;
                }
            }
        }

        $has_id = false;
        if (isset($data) and is_array($data)) {
            foreach ($data as $k => $v) {
                if ($k != 'ondrop') {
                    if ($k == 'id') {
                        $has_id = true;
                    }

                    if (is_array($v)) {
                        $v1 = $this->app->format->array_to_base64($v);
                        $tags .= "{$k}=\"$v1\" ";
                    } else {

                        $v = $this->app->format->clean_html($v);


                        //$v = $this->app->database_manager->escape_string($v);

                        $tags .= "{$k}=\"$v\" ";
                    }
                }
            }
        }
        if ($has_id == false) {

            //	$mod_n = $this->app->url->slug($mod_n) . '-' . date("YmdHis");
            //	$tags .= "id=\"$mod_n\" ";
        }


        $tags = "<module {$tags} />";

        $opts = array();
        if ($_REQUEST) {
            $opts = $_REQUEST;
        }
        if (isset($_REQUEST['live_edit'])) {
            event_trigger('mw.live_edit');
        }
        $opts['admin'] = $admin;
        if ($admin == 'admin') {
            event_trigger('mw_backend');
            event_trigger('mw.admin');
        } else {
            event_trigger('mw_frontend');

        }

        //

        if (isset($_SERVER['HTTP_REFERER']) and $_SERVER['HTTP_REFERER'] != false) {
            $get_arr_from_ref = $_SERVER['HTTP_REFERER'];
            if (strstr($get_arr_from_ref, $this->app->url->site())) {
                $get_arr_from_ref_arr = parse_url($get_arr_from_ref);
                if (isset($get_arr_from_ref_arr['query']) and $get_arr_from_ref_arr['query'] != '') {
                    $restore_get = parse_str($get_arr_from_ref_arr['query'], $get_array);
                    if (is_array($get_array)) {

                        mw_var('mw_restore_get', $get_array);
                    }
                    //

                }
            }
        }

        $res = $this->app->parser->process($tags, $opts);
        $res = preg_replace('~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $res);

        if ($embed != false) {
            $p_index = mw_includes_path() . 'api/index.php';
            $p_index = normalize_path($p_index, false);
            $l = new \Microweber\View($p_index);
            $layout = $l->__toString();
            $res = str_replace('{content}', $res, $layout);
        }

        $aj = $this->app->url->is_ajax();

        if (isset($_REQUEST['live_edit']) and $aj == false) {


            $p_index = mw_includes_path() . DS . 'toolbar' . DS . 'editor_tools' . DS . 'module_settings' . DS . 'index.php';
            $p_index = normalize_path($p_index, false);
            $l = new \Microweber\View($p_index);
            $l->params = $data;
            $layout = $l->__toString();
            $res = str_replace('{content}', $res, $layout);
            $res = $this->app->parser->process($res, $options = false);


        }

        $res = execute_document_ready($res);
        if (!defined('MW_NO_OUTPUT')) {
            $res = $this->app->url->replace_site_url_back($res);
            print $res;
        }

        if ($url_last != __FUNCTION__) {
            if (function_exists($url_last)) {
                //
                $this->api($url_last);
            } else if (isset($url_prev) and function_exists($url_prev)) {
                $this->api($url_last);
            } elseif (class_exists($url_last, false)) {
                $this->api($url_last);
            } elseif (isset($url_prev) and class_exists($url_prev, false)) {
                $this->api($url_prev);
            }
        }
        exit();
    }


    public function m()
    {

        if (!defined('MW_API_CALL')) {
            define('MW_API_CALL', true);
        }

        if (!defined('MW_NO_OUTPUT')) {
            define('MW_NO_OUTPUT', true);
        }
        return $this->module();
    }


    public function plupload()
    {
        $this->app->content_manager->define_constants();
        $f = MW_PATH . 'functions' . DIRECTORY_SEPARATOR . 'plupload.php';
        require($f);
        exit();
    }

    public function editor_tools()
    {
        if (!defined('IN_ADMIN')) {
            define('IN_ADMIN', true);
        }
        if (!defined('IN_EDITOR_TOOLS')) {
            define('IN_EDITOR_TOOLS', true);

        }


        if (mw_is_installed() == true) {
            //event_trigger('mw_db_init');
            //  event_trigger('mw_cron');
        }

        $tool = $this->app->url->segment(1);

        if ($tool) {

        } else {
            $tool = 'index';
        }
        $page = false;
        if (isset($_REQUEST["content_id"])) {

            if (intval($_REQUEST["content_id"]) == 0) {
                $this->create_new_page = true;
                $this->return_data = 1;
                $page = $this->index();
                // $page = array();

                // $page['id'] = 0;
            } else {
                $page = $this->app->content_manager->get_by_id($_REQUEST["content_id"]);

            }


        } elseif (isset($_SERVER["HTTP_REFERER"])) {
            $url = $_SERVER["HTTP_REFERER"];
            $url = explode('?', $url);
            $url = $url[0];

            if (trim($url) == '' or trim($url) == $this->app->url->site()) {
                //$page = $this->app->content_manager->get_by_url($url);
                $page = $this->app->content_manager->homepage();
            } else {

                $page = $this->app->content_manager->get_by_url($url);
            }
        } else {
            $url = $this->app->url->string();
        }

        if (isset($_GET['preview_template'])) {
            $page['active_site_template'] = $_GET['preview_template'];
        }
        if (isset($_GET['preview_layout']) and $_GET['preview_layout'] != 'inherit') {
            $page['layout_file'] = $_GET['preview_layout'];
        }


        $this->app->content_manager->define_constants($page);
        $page['render_file'] = $this->app->template->get_layout($page);

//        if (!isset($page['render_file'])) {
//
//        }
        //d($page);
        if (defined('TEMPLATE_DIR')) {
            $load_template_functions = TEMPLATE_DIR . 'functions.php';
            if (is_file($load_template_functions)) {
                include_once($load_template_functions);
            }
        }


        $params = $_REQUEST;
        $tool = str_replace('..', '', $tool);

        $p_index = mw_includes_path() . 'toolbar/editor_tools/index.php';
        $p_index = normalize_path($p_index, false);

        $p = mw_includes_path() . 'toolbar/editor_tools/' . $tool . '/index.php';
        $standalone_edit = false;
        if ($tool == 'wysiwyg') {
            $ed_file_from_template = TEMPLATE_DIR . 'editor.php';


            if (is_file($ed_file_from_template)) {
                $p_index = $ed_file_from_template;
            }

            if (isset($page['content_type']) and $page['content_type'] != 'post' and $page['content_type'] != 'page' and $page['content_type'] != 'product') {
                if (isset($page['subtype']) and ($page['subtype'] != 'post' and $page['subtype'] != 'product')) {
                    $standalone_edit = true;


                }
            } else if (isset($page['content_type']) and $page['content_type'] == 'post') {
                if (isset($page['subtype']) and ($page['subtype'] != 'post' and $page['subtype'] != 'product')) {
                    $standalone_edit = true;


                }
            }

            if ($standalone_edit) {
                if (!isset($page['content'])) {
                    $page['content'] = '<div class="element"></div>';
                }
                $page['content'] = '<div class="edit" field="content" rel="content" contenteditable="true">' . $page['content'] . '</div>';
                $page['render_file'] = false;

            }


            //
            //  $page['content'] = '<div class="edit" field="content" rel="content" contenteditable="true">' . $page['content'] . '</div>';
        }


        $p = normalize_path($p, false);

        $l = new \Microweber\View($p_index);
        $l->params = $params;
        $layout = $l->__toString();


        if ($layout != false) {
            //$apijs_loaded = $this->app->url->site('apijs') . '?id=' . CONTENT_ID;
            $apijs_loaded = $this->app->url->site('apijs');
            $apijs_settings_loaded = $this->app->url->site('apijs_settings') . '?id=' . CONTENT_ID;

            // $is_admin = $this->app->user_manager->is_admin();
            $default_css = '<link rel="stylesheet" href="' . mw_includes_url() . 'default.css" type="text/css" />';
            $headers = event_trigger('site_header', TEMPLATE_NAME);
            $template_headers_append = '';
            $one = 1;
            if (is_array($headers)) {
                foreach ($headers as $modify) {
                    if ($modify != false and is_string($modify) and $modify != '') {
                        $template_headers_append = $template_headers_append . $modify;
                    }
                }
                if ($template_headers_append != false and $template_headers_append != '') {
                    $layout = str_ireplace('</head>', $template_headers_append . '</head>', $l, $one);
                }
            }
            if (function_exists('template_headers_src')) {
                $template_headers_src = template_headers_src();
                if ($template_headers_src != false and $template_headers_src != '') {
                    $layout = str_ireplace('</head>', $template_headers_src . '</head>', $l, $one);
                }
            }


            if (isset($page['active_site_template'])) {
                if ($page['active_site_template'] == '') {
                    $page['active_site_template'] = 'default';
                }

                if ($page['active_site_template'] == 'default') {
                    $active_site_template = $this->app->option_manager->get('current_template', 'template');
                } else {
                    $active_site_template = $page['active_site_template'];
                    if ($active_site_template == 'mw_default') {
                        $active_site_template = 'default';
                    }
                }

                $live_edit_css_folder = userfiles_path() . 'css' . DS . $active_site_template . DS;
                $custom_live_edit = $live_edit_css_folder . DS . 'live_edit.css';
                if (is_file($custom_live_edit)) {
                    $live_edit_url_folder = userfiles_url() . 'css/' . $active_site_template . '/';
                    $custom_live_editmtime = filemtime($custom_live_edit);
                    $liv_ed_css = '<link rel="stylesheet" href="' . $live_edit_url_folder . 'live_edit.css?version=' . $custom_live_editmtime . '" id="mw-template-settings" type="text/css" />';
                    $layout = str_ireplace('</head>', $liv_ed_css . '</head>', $l);
                }
            }


        }


        // var_dump($l);

        if (isset($_REQUEST['plain'])) {
            if (is_file($p)) {
                $p = new \Microweber\View($p);
                $p->params = $params;
                $layout = $p->__toString();
                print $layout;
                exit();

            }
        } else if (is_file($p)) {
            $p = new \Microweber\View($p);
            $p->params = $params;
            $layout_tool = $p->__toString();
            $layout = str_replace('{content}', $layout_tool, $layout);

        } else {
            $layout = str_replace('{content}', 'Not found!', $layout);
        }
        $category = false;
        if (defined('CATEGORY_ID')) {
            $category = $this->app->category_manager->get_by_id(CATEGORY_ID);
        }


        // $render_file = $this->app->template->get_layout($page);
//d($page['render_file']);
        //    $page['render_file'] = $render_file;
        if (!$standalone_edit) {
            if (isset($page['render_file'])) {

                $l = new \Microweber\View($page['render_file']);
                $l->page_id = PAGE_ID;
                $l->content_id = CONTENT_ID;
                $l->post_id = POST_ID;
                $l->category_id = CATEGORY_ID;
                $l->content = $page;
                $l->category = $category;
                $l->params = $params;
                $l->page = $page;
                $l->application = $this->app;
                $l = $l->__toString();

                $l = $this->app->parser->process($l, $options = false);
//                if(isset($page['content']) and $page['content'] != false){
//
//                if($page['content'] == ''){
//                    unset($page['content']);
//                }
//                } else {
//                    $page['content'] = $l;
//                }


                $editable = $this->app->parser->isolate_content_field($l, true);

                if ($editable != false) {
                    $page['content'] = $editable;
                } else {
                    if ($tool == 'wysiwyg') {
                        $err = 'no editable content region found';
                        if (isset($page['layout_file'])) {
                            $file = $page['layout_file'];
                            $file = str_replace('__', '/', $page['layout_file']);
                            $err = $err . ' in file ' . $file;
                        }
                        if (isset($page['active_site_template'])) {
                            $err = $err . ' (' . $page['active_site_template'] . ')';
                        }
                        print $err;
                        return false;
                    }
                }
            }


        }

        if (!stristr($layout, $apijs_loaded)) {
            $rep = 0;


            $default_css = $default_css . "\r\n" . '<script src="' . $apijs_settings_loaded . '"></script>' . "\r\n";
            $default_css = $default_css . "\r\n" . '<script src="' . $apijs_loaded . '"></script>' . "\r\n";
            $layout = str_ireplace('<head>', '<head>' . $default_css, $layout, $rep);
        }
        if (isset($page['content'])) {
            if ($standalone_edit) {
                if (!isset($render_file)) {
                    if (stristr($page['content'], 'field="content"') or stristr($page['content'], 'field=\'content\'')) {
                        $page['content'] = '<div class="edit" field="content" rel="content" contenteditable="true">' . $page['content'] . '</div>';
                    }
                }
            }

            $layout = str_replace('{content}', $page['content'], $layout);

        }

        $layout = $this->app->parser->process($layout, $options = false);

        $layout = execute_document_ready($layout);

        $layout = str_replace('{head}', '', $layout);

        $layout = str_replace('{content}', '', $layout);

        print $layout;
        exit();
        //
        //header("HTTP/1.0 404 Not Found");
        //$v = new \Microweber\View(MW_ADMIN_VIEWS_DIR . '404.php');
        //echo $v;
    }

    public function robotstxt()
    {

        header("Content-Type: text/plain");
        $robots = get_option('robots_txt', 'website');

        if ($robots == false) {
            $robots = "User-agent: *\nAllow: /" . "\n";
            $robots .= "Disallow: /cache/" . "\n";
            $robots .= "Disallow: /userfiles/modules/" . "\n";
            $robots .= "Disallow: /userfiles/templates/" . "\n";
        }
        event_trigger('mw_robot_url_hit');
        print $robots;
        exit;
    }


    function __get($name)
    {
        if (isset($this->vars[$name]))
            return $this->vars[$name];
    }

    function __set($name, $data)
    {
        if (is_callable($data))
            $this->functions[$name] = $data;
        else
            $this->vars[$name] = $data;
    }

    function __call($method, $args)
    {
        if (isset($this->functions[$method])) {
            call_user_func_array($this->functions[$method], $args);
        } else {
            // error out
        }
    }

    function __destruct()
    {

    }
}
