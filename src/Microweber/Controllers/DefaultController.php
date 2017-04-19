<?php

namespace Microweber\Controllers;

use Microweber\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Cache;
use Module;

class DefaultController extends Controller
{
    /** @var \Microweber\Application */
    public $app;

    public $return_data = false;
    public $content_data = false;
    public $page_url = false;
    public $create_new_page = false;
    public $render_this_url = false;
    public $isolate_by_html_id = false;
    public $functions = array();
    public $page = array();
    public $params = array();
    public $vars = array();

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
            $installer = new InstallController($this->app);

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

        return $this->frontend();
    }

    public function rss()
    {
        if (mw_is_installed() == true) {
            event_trigger('mw_cron');
        }

        header('Content-Type: application/rss+xml; charset=UTF-8');

        $cont = get_content('is_active=1&is_deleted=0&limit=2500&orderby=updated_at desc');

        $site_title = $this->app->option_manager->get('website_title', 'website');
        $site_desc = $this->app->option_manager->get('website_description', 'website');
        $rssfeed = '<?xml version="1.0" encoding="UTF-8"?>';
        $rssfeed .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
        $rssfeed .= '<channel>' . "\n";
        $rssfeed .= '<atom:link href="' . site_url('rss') . '" rel="self" type="application/rss+xml" />' . "\n";
        $rssfeed .= '<title>' . $site_title . '</title>' . "\n";
        $rssfeed .= '<link>' . site_url() . '</link>' . "\n";
        $rssfeed .= '<description>' . $site_desc . '</description>' . "\n";
        foreach ($cont as $row) {
            if (!isset($row['description']) or $row['description'] == '') {
                $row['description'] = $row['content'];
            }
            $row['description'] = character_limiter(strip_tags(($row['description'])), 500);
            $rssfeed .= '<item>' . "\n";
            $rssfeed .= '<title>' . $row['title'] . '</title>' . "\n";
            $rssfeed .= '<description><![CDATA[' . $row['description'] . '  ]]></description>' . "\n";
            $rssfeed .= '<link>' . content_link($row['id']) . '</link>' . "\n";
            $rssfeed .= '<pubDate>' . date('D, d M Y H:i:s O', strtotime($row['created_at'])) . '</pubDate>' . "\n";
            $rssfeed .= '<guid>' . content_link($row['id']) . '</guid>' . "\n";
            $rssfeed .= '</item>' . "\n";
        }
        $rssfeed .= '</channel>' . "\n";
        $rssfeed .= '</rss>';

        event_trigger('mw_robot_url_hit');

        echo $rssfeed;
     }

    public function api_html()
    {
        if (!defined('MW_API_HTML_OUTPUT')) {
            define('MW_API_HTML_OUTPUT', true);
        }
        $this->api();
    }

    public function api($api_function = false, $params = false)
    {
        if (isset($_REQUEST['api_key']) and user_id() == 0) {
            api_login($_REQUEST['api_key']);
        }

        if (!defined('MW_API_CALL')) {
            define('MW_API_CALL', true);
        }

        // dd($_SERVER);
        //   dd($_SERVER);


        $set_constants = true;
        $mod_class_api = false;
        $mod_class_api_called = false;
        $mod_class_api_class_exist = false;
        $caller_commander = false;
        if ($api_function == false) {
            $api_function_full = $this->app->url_manager->string();
            $api_function_full = $this->app->format->replace_once('api_html', '', $api_function_full);
            $api_function_full = $this->app->format->replace_once('api/api', 'api', $api_function_full);

            $api_function_full = $this->app->format->replace_once('api', '', $api_function_full);
            $api_function_full = trim($api_function_full, '/');

            //$api_function_full = substr($api_function_full, 4);
        } else {
            $api_function_full = $api_function;
        }
        if (isset($api_function_full) and $api_function_full != '') {
            if (ltrim($api_function_full, '/') == 'module') {
                $set_constants = false;
            }
        }
        if ($set_constants == true) {
            $this->app->content_manager->define_constants();
        }

        if (defined('TEMPLATE_DIR')) {
            $load_template_functions = TEMPLATE_DIR . 'functions.php';
            if (is_file($load_template_functions)) {
                include_once $load_template_functions;
            }
        }

        //$api_function_full = str_ireplace('api/', '', $api_function_full);

        $api_function_full = str_replace('..', '', $api_function_full);
        $api_function_full = str_replace('\\', '/', $api_function_full);
        $api_function_full = str_replace('//', '/', $api_function_full);

        $api_function_full = $this->app->database_manager->escape_string($api_function_full);
        if (is_string($api_function_full)) {
            $mod_api_class = explode('/', $api_function_full);
        } else {
            $mod_api_class = $api_function_full;
        }
        $try_class_func = array_pop($mod_api_class);

        // $try_class_func2 = array_pop($mod_api_class);
        $mod_api_class_copy = $mod_api_class;
        $try_class_func2 = array_pop($mod_api_class_copy);
        $mod_api_class2 = implode(DS, $mod_api_class_copy);

        $mod_api_class = implode(DS, $mod_api_class);
        $mod_api_class_clean = ltrim($mod_api_class, '/');
        $mod_api_class_clean = ltrim($mod_api_class_clean, '\\');
        $mod_api_class_clean_uc1 = ucfirst($mod_api_class_clean);

        $mod_api_class1 = normalize_path(modules_path() . $mod_api_class, false) . '.php';
        $mod_api_class_native = normalize_path(mw_includes_path() . $mod_api_class, false) . '.php';
        $mod_api_class_native_system = normalize_path(dirname(MW_PATH) . DS . $mod_api_class, false) . '.php';
        $mod_api_class_native_global_ns = normalize_path(mw_includes_path() . 'classes' . DS . $mod_api_class2, false) . '.php';
        $mod_api_class1_uc1 = normalize_path(modules_path() . $mod_api_class_clean_uc1, false) . '.php';
        $mod_api_class_native_uc1 = normalize_path(mw_includes_path() . $mod_api_class_clean_uc1, false) . '.php';
        $mod_api_class_native_global_ns_uc1 = normalize_path(mw_includes_path() . 'classes' . DS . $mod_api_class_clean_uc1, false) . '.php';

        $mod_api_class2 = normalize_path(modules_path() . DS . $mod_api_class_clean . DS . $mod_api_class_clean, false) . '.php';
        $mod_api_class2_uc1 = normalize_path(modules_path() . DS . $mod_api_class_clean . DS . $mod_api_class_clean, false) . '.php';

        $try_class = '\\' . str_replace('/', '\\', $mod_api_class);

        if (class_exists($try_class, false)) {
            $caller_commander = 'class_is_already_here';
            $mod_class_api_class_exist = true;
        } else {
            if (is_file($mod_api_class1)) {
                $mod_class_api = true;
                include_once $mod_api_class1;
            } elseif (is_file($mod_api_class_native_system)) {
                $mod_class_api = true;
                include_once $mod_api_class_native_system;
            } elseif (is_file($mod_api_class1_uc1)) {
                $mod_class_api = true;
                include_once $mod_api_class1_uc1;
            } elseif (is_file($mod_api_class_native_global_ns_uc1)) {
                $try_class = str_replace('/', '\\', $mod_api_class2);
                $mod_class_api = true;

                include_once $mod_api_class_native_global_ns_uc1;
            } elseif (is_file($mod_api_class_native_global_ns)) {
                $try_class = str_replace('/', '\\', $mod_api_class2);
                $mod_class_api = true;
                include_once $mod_api_class_native_global_ns;
            } elseif (is_file($mod_api_class_native_uc1)) {
                $mod_class_api = true;
                include_once $mod_api_class_native_uc1;
            } elseif (is_file($mod_api_class_native)) {
                $mod_class_api = true;
                include_once $mod_api_class_native;
            } elseif (is_file($mod_api_class2)) {
                $mod_class_api = true;
                include_once $mod_api_class2;
            } elseif (is_file($mod_api_class2_uc1)) {
                $mod_class_api = true;
                include_once $mod_api_class2_uc1;
            }
        }

        $api_exposed = '';

        // user functions
        $api_exposed .= 'user_login user_logout social_login_process';

        // content functions

        $api_exposed .= 'set_language ';
        $api_exposed .= (api_expose(true));

        if (is_logged()) {
            $api_exposed .= (api_expose_user(true));
        }

        if (is_admin()) {
            $api_exposed .= (api_expose_admin(true));
        }


        $api_exposed = explode(' ', $api_exposed);
        $api_exposed = array_unique($api_exposed);
        $api_exposed = array_trim($api_exposed);

        $hooks = api_bind(true);
        if (is_logged()) {
            $hooks_admin = api_bind_user(true);
            if (is_array($hooks_admin)) {
                $hooks = array_merge($hooks, $hooks_admin);
            }
        }

        if (is_admin()) {
            $hooks_admin = api_bind_admin(true);
            if (is_array($hooks_admin)) {
                $hooks = array_merge($hooks, $hooks_admin);
            }
        }

        if ($api_function == false) {
            $api_function = $this->app->url_manager->segment(1);
        }

        if (!defined('MW_API_RAW')) {
            if ($mod_class_api != false) {
                $url_segs = $this->app->url_manager->segment(-1);
            }
        } else {
            if (is_array($api_function)) {
                $url_segs = $api_function;
            } else {
                $url_segs = explode('/', $api_function);
            }
        }
        if (!defined('MW_API_FUNCTION_CALL')) {
            define('MW_API_FUNCTION_CALL', $api_function);
        }

        switch ($caller_commander) {
            case 'class_is_already_here':

                if ($params != false) {
                    $data = $params;
                } elseif (!$_POST and !$_REQUEST) {
                    $data = $this->app->url_manager->params(true);
                    if (empty($data)) {
                        $data = $this->app->url_manager->segment(2);
                    }
                } else {

                    //$data = $_REQUEST;
                    $data = array_merge($_GET, $_POST);
                }

                static $loaded_classes = array();

                //$try_class_n = src_
                if (isset($loaded_classes[$try_class]) == false) {
                    $res = new $try_class($data);
                    $loaded_classes[$try_class] = $res;
                } else {
                    $res = $loaded_classes[$try_class];
                }

                if (isset($hooks[$api_function_full]) and !empty($hooks[$api_function_full])) {
                    foreach ($hooks[$api_function_full] as $hook) {
                        if (is_array($hook)) {
                            $hook = array_pop($hook);
                        }
                        if (is_callable($hook)) {
                            $res = call_user_func($hook, $data);
                            if (defined('MW_API_RAW')) {
                                $mod_class_api_called = true;
                            }
                            return $this->_api_responce($res);
                        }
                    }
                }


                if (method_exists($res, $try_class_func) or method_exists($res, $try_class_func2)) {
                    if (method_exists($res, $try_class_func2)) {
                        $try_class_func = $try_class_func2;
                    }

                    $res = $res->$try_class_func($data);

                    if (defined('MW_API_RAW')) {
                        $mod_class_api_called = true;
                    }

                    return $this->_api_responce($res);
                }
                break;

            default:
                $res = false;
                if (isset($hooks[$api_function_full])) {
                    $data = array_merge($_GET, $_POST);

                    $call = $hooks[$api_function_full];

                    if (!empty($call)) {
                        foreach ($call as $call_item) {
                            $res = call_user_func($call_item, $data);
                        }
                    }
                    if ($res != false) {
                        return $this->_api_responce($res);
                    }
                }

                if ($mod_class_api == true and $mod_api_class != false) {
                    $mod_api_class = str_replace('..', '', $mod_api_class);

                    $try_class = str_replace('/', '\\', $mod_api_class);
                    $try_class_full = str_replace('/', '\\', $api_function_full);

                    $try_class_full2 = str_replace('\\', '/', $api_function_full);
                    $mod_api_class_test = explode('/', $try_class_full2);
                    $try_class_func_test = array_pop($mod_api_class_test);
                    $mod_api_class_test_full = implode('/', $mod_api_class_test);
                    $mod_api_err = false;
                    if (!defined('MW_API_RAW')) {
                        if (!in_array($try_class_full, $api_exposed, true) and !in_array($try_class_full2, $api_exposed, true) and !in_array($mod_api_class_test_full, $api_exposed, true)) {
                            $mod_api_err = true;

                            foreach ($api_exposed as $api_exposed_value) {
                                if ($mod_api_err == true) {
                                    if ($api_exposed_value == $try_class_full) {
                                        $mod_api_err = false;
                                    } elseif (strtolower('\\' . $api_exposed_value) == strtolower($try_class_full)) {
                                        $mod_api_err = false;
                                    } elseif ($api_exposed_value == $try_class_full2) {
                                        $mod_api_err = false;
                                    } else {
                                        $convert_slashes = str_replace('\\', '/', $try_class_full);

                                        if ($convert_slashes == $api_exposed_value) {
                                            $mod_api_err = false;
                                        }
                                    }
                                }
                            }
                        } else {
                            $mod_api_err = false;
                        }
                    }

                    if ($mod_class_api and $mod_api_err == false) {
                        if (!class_exists($try_class, false)) {
                            $remove = $url_segs;
                            $last_seg = array_pop($remove);
                            $last_prev_seg = array_pop($remove);
                            $last_prev_seg2 = array_pop($remove);

                            if (class_exists($last_prev_seg, false)) {
                                $try_class = $last_prev_seg;
                            } elseif (class_exists($last_prev_seg2, false)) {
                                $try_class = $last_prev_seg2;
                            }
                        }

                        if (!class_exists($try_class, false)) {
                            $try_class_mw = ltrim($try_class, '/');
                            $try_class_mw = ltrim($try_class_mw, '\\');
                            $try_class = $try_class_mw;
                        }

                        if (class_exists($try_class, false)) {
                            if ($params != false) {
                                $data = $params;
                            } elseif (!$_POST and !$_REQUEST) {
                                $data = $this->app->url_manager->params(true);
                                if (empty($data)) {
                                    $data = $this->app->url_manager->segment(2);
                                }
                            } else {
                                $data = array_merge($_GET, $_POST);
                            }

                            $res = new $try_class($data);

                            if (method_exists($res, $try_class_func) or method_exists($res, $try_class_func2)) {
                                if (method_exists($res, $try_class_func2)) {
                                    $try_class_func = $try_class_func2;
                                }

                                $res = $res->$try_class_func($data);

                                $mod_class_api_called = true;

                                return $this->_api_responce($res);
                            }
                        } else {
                            mw_error('The api class ' . $try_class . '  does not exist');
                        }
                    }
                }

                break;
        }

        if ($api_function) {
        } else {
            $api_function = 'index';
        }

        if ($api_function == 'module' and $mod_class_api_called == false) {

           return  $this->module();
        } else {
            $err = false;
            if (!in_array($api_function, $api_exposed, true)) {
                $err = true;
            }

            if ($err == true) {
                foreach ($api_exposed as $api_exposed_item) {
                    if ($api_exposed_item == $api_function) {
                        $err = false;
                    }
                }
            }

            if (isset($api_function_full)) {
                foreach ($api_exposed as $api_exposed_item) {
                    if (is_string($api_exposed_item) and is_string($api_function_full)) {
                        $api_function_full = str_replace('\\', '/', $api_function_full);
                        $api_function_full = ltrim($api_function_full, '/');

                        if (strtolower($api_exposed_item) == strtolower($api_function_full)) {
                            $err = false;
                        }
                    }
                }
            }

            if ($err == false) {
                if ($mod_class_api_called == false) {
                    if (!$_POST and !$_REQUEST) {

                        //  $data = $this->app->url_manager->segment(2);
                        $data = $this->app->url_manager->params(true);
                        if (empty($data)) {
                            $data = $this->app->url_manager->segment(2);
                        }
                    } else {

                        //$data = $_REQUEST;
                        $data = array_merge($_GET, $_POST);
                    }

                    $api_function_full_2 = explode('/', $api_function_full);
                    unset($api_function_full_2[count($api_function_full_2) - 1]);
                    $api_function_full_2 = implode('/', $api_function_full_2);

                    if (function_exists($api_function)) {
                        $res = $api_function($data);
                    } elseif (class_exists($api_function, false)) {

                        //
                        $segs = $this->app->url_manager->segment();
                        $mmethod = array_pop($segs);

                        $class = new $api_function($this->app);

                        if (method_exists($class, $mmethod)) {
                            $res = $class->$mmethod($data);
                        }
                    } else {
                        $api_function_full_2 = str_replace(array('..', '/'), array('', '\\'), $api_function_full_2);
                        $api_function_full_2 = __NAMESPACE__ . '\\' . $api_function_full_2;

                        if (class_exists($api_function_full_2, false)) {

                            //

                            $segs = $this->app->url_manager->segment();
                            $mmethod = array_pop($segs);

                            $class = new $api_function_full_2($this->app);

                            if (method_exists($class, $mmethod)) {
                                $res = $class->$mmethod($data);
                            }
                        } elseif (isset($api_function_full)) {
                            $api_function_full = str_replace('\\', '/', $api_function_full);

                            $api_function_full1 = explode('/', $api_function_full);
                            $mmethod = array_pop($api_function_full1);
                            $mclass = array_pop($api_function_full1);

                            if (class_exists($mclass, false)) {
                                $class = new $mclass($this->app);

                                if (method_exists($class, $mmethod)) {
                                    $res = $class->$mmethod($data);
                                }
                            }
                        }
                    }
                }

                if (isset($res) and isset($hooks[$api_function]) and is_array($hooks[$api_function]) and !empty($hooks[$api_function])) {
                    foreach ($hooks[$api_function] as $hook_key => $hook_value) {
                        if ($hook_value != false and $hook_value != null) {
                            $hook_value($res);
                        }
                    }
                } else {

                    //error('The api function ' . $api_function . ' does not exist', __FILE__, __LINE__);
                }

                // print $api_function;
            } else {
                $api_function = mw()->format->clean_html($api_function);
                $api_function = mw()->format->clean_xss($api_function);
                mw_error('The api function ' . $api_function . ' is not defined in the allowed functions list');
            }

            if (isset($res)) {
                return $this->_api_responce($res);
            }

            return;
        }
    }

    public function module()
    {
        if (!defined('MW_API_CALL')) {

            //      define('MW_API_CALL', true);
        }

        if (!defined('MW_NO_SESSION')) {
            $is_ajax = $this->app->url_manager->is_ajax();
            if (!mw()->user_manager->session_id() and $is_ajax == false) {
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
                $_REQUEST['id'] = $this->app->url_manager->slug($_REQUEST['data-module-name'] . '-' . date('YmdHis'));
            }
        }

        if (isset($_REQUEST['data-type'])) {
            $_REQUEST['module'] = $_REQUEST['data-type'];
        }

        if (isset($_REQUEST['display']) and $_REQUEST['display'] == 'custom') {
            $custom_display = true;
        }
        if (isset($_REQUEST['view']) and $_REQUEST['view'] == 'admin') {
            $custom_display = false;
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
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $from_url = $_SERVER['HTTP_REFERER'];
        }

        if (isset($from_url) and $from_url != false) {
            if (stristr($from_url, 'editor_tools/wysiwyg')) {
                if (!defined('IN_EDITOR_TOOLS')) {
                    define('IN_EDITOR_TOOLS', true);
                }
            }

            $url = $from_url;
            $from_url2 = str_replace('#', '/', $from_url);

            $content_id = $this->app->url_manager->param('content_id', false, $from_url2);

            if ($content_id == false) {
                $content_id = $this->app->url_manager->param('editpage', false, $from_url2);
            }
            if ($content_id == false) {
                $content_id = $this->app->url_manager->param('editpost', false, $from_url2);
            }
            if ($content_id == false) {
                $is_current = $this->app->url_manager->param('is-current', false, $from_url2);
                if ($is_current) {
                    $content_id = $this->app->url_manager->param('content-id', false, $from_url2);
                } else {
                    $content_id = $this->app->url_manager->param('mw-adm-content-id', false, $from_url2);
                }
            }

            if ($content_id == false) {
                $action_test = $this->app->url_manager->param('action', false, $from_url2);

                if ($action_test != false) {
                    $action_test = str_ireplace('editpage:', '', $action_test);
                    $action_test = str_ireplace('editpost:', '', $action_test);
                    $action_test = str_ireplace('edit:', '', $action_test);
                    $action_test = str_ireplace('showposts:', '', $action_test);

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
                if (trim($url) == '' or trim($url) == $this->app->url_manager->site()) {

                    //var_dump($from_url);
                    //$page = $this->app->content_manager->get_by_url($url);
                    $page = $this->app->content_manager->homepage();

                    if (!defined('IS_HOME')) {
                        define('IS_HOME', true);
                    }

                    if (isset($from_url2)) {
                        $mw_quick_edit = $this->app->url_manager->param('mw_quick_edit', false, $from_url2);

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
            $url = $this->app->url_manager->string();
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
                include_once $load_template_functions;
            }
        }

        if ($custom_display == true) {
            $u2 = $this->app->url_manager->site();
            $u1 = str_replace($u2, '', $url);

            $this->render_this_url = $u1;
            $this->isolate_by_html_id = $custom_display_id;
            return $this->frontend();


        }
        $url_last = false;
        if (!isset($_REQUEST['module'])) {
            $url = $this->app->url_manager->string(0);
            if ($url == __FUNCTION__) {
                $url = $this->app->url_manager->string(0);
            }

            /*
            $is_ajax = $this->app->url_manager->is_ajax();

            if ($is_ajax == true) {
            $url = $this->app->url_manager->string(true);
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
                    ++$i;
                }
            }
        }

        $module_info = $this->app->url_manager->param('module_info', true);

        if ($module_info) {
            if ($_REQUEST['module']) {
                $_REQUEST['module'] = str_replace('..', '', $_REQUEST['module']);
                $try_config_file = modules_path() . '' . $_REQUEST['module'] . '_config.php';
                $try_config_file = normalize_path($try_config_file, false);
                if (is_file($try_config_file)) {
                    include $try_config_file;

                    if (!isset($config) or !is_array($config)) {
                        return false;
                    }

                    if (!isset($config['icon']) or $config['icon'] == false) {
                        $config['icon'] = modules_path() . '' . $_REQUEST['module'] . '.png';
                        $config['icon'] = $this->app->url_manager->link_to_file($config['icon']);
                    }
                    echo json_encode($config);

                    return;
                }
            }
        }

        $admin = $this->app->url_manager->param('admin', true);

        $mod_to_edit = $this->app->url_manager->param('module_to_edit', true);
        $embed = $this->app->url_manager->param('embed', true);

        $mod_iframe = false;
        if ($mod_to_edit != false) {
            $mod_to_edit = str_ireplace('_mw_slash_replace_', '/', $mod_to_edit);
            $mod_iframe = true;
        }

        //$data = $_REQUEST;

        if (($_POST)) {
            $data = $_POST;
        } else {
            $url = $this->app->url_manager->segment();

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
                    return response($layout);


                  //  echo $layout;

                  // return;
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

            //  $mod_n = $this->app->url_manager->slug($mod_n) . '-' . date("YmdHis");
            //  $tags .= "id=\"$mod_n\" ";
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
            event_trigger('mw.front');
        }

        if (isset($_SERVER['HTTP_REFERER']) and $_SERVER['HTTP_REFERER'] != false) {
            $get_arr_from_ref = $_SERVER['HTTP_REFERER'];
            if (strstr($get_arr_from_ref, $this->app->url_manager->site())) {
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

        $aj = $this->app->url_manager->is_ajax();

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
            $res = $this->app->url_manager->replace_site_url_back($res);
            return response($res);

           // echo $res;
        }

        if ($url_last != __FUNCTION__) {
            if (function_exists($url_last)) {
                //
                $this->api($url_last);
            } elseif (isset($url_prev) and function_exists($url_prev)) {
                $this->api($url_last);
            } elseif (class_exists($url_last, false)) {
                $this->api($url_last);
            } elseif (isset($url_prev) and class_exists($url_prev, false)) {
                $this->api($url_prev);
            }
        }

        return;
    }

    public function frontend()
    {
        if (isset($_GET['debug'])) {
            if ($this->app->make('config')->get('app.debug')) {
                DB::enableQueryLog();
            }
        }

        event_trigger('mw.controller.index');

        if ($this->render_this_url == false and $this->app->url_manager->is_ajax() == false) {
            $page_url = $this->app->url_manager->string();
        } elseif ($this->render_this_url == false and $this->app->url_manager->is_ajax() == true) {
            $page_url = $this->app->url_manager->string(1);
        } else {
            $page_url = $this->render_this_url;
            $this->render_this_url = false;
        }
        if ($this->page_url != false) {
            $page_url = $this->page_url;
        }

        if (strtolower($page_url) == 'index.php') {
            $page_url = '';
        }

        if ($this->create_new_page == true and $this->page_url != false) {
            $page_url = $this->page_url;
        }


        $favicon_image = get_option('favicon_image', 'website');
        if ($favicon_image) {
            mw()->template->head('<link rel="shortcut icon" href="' . $favicon_image . '" />');
        }


        $page = false;

        if ($page == false and !empty($this->page)) {
            $page = $this->page;
        }

        $page_url = rtrim($page_url, '/');
        $is_admin = $this->app->user_manager->is_admin();
        $page_url_orig = $page_url;
        $simply_a_file = false;
        $show_404_to_non_admin = false;

        // if this is a file path it will load it
        if (isset($_REQUEST['view'])) {
            $is_custom_view = $_REQUEST['view'];
        } else {
            $is_custom_view = $this->app->url_manager->param('view');
            if ($is_custom_view and $is_custom_view != false) {
                $is_custom_view = str_replace('..', '', $is_custom_view);
                $page_url = $this->app->url_manager->param_unset('view', $page_url);
            }
        }

        $is_editmode = $this->app->url_manager->param('editmode');

        $is_no_editmode = $this->app->url_manager->param('no_editmode');
        $is_quick_edit = $this->app->url_manager->param('mw_quick_edit');

        if ($is_quick_edit != false) {
            $page_url = $this->app->url_manager->param_unset('mw_quick_edit', $page_url);
        }
        $is_preview_template = $this->app->url_manager->param('preview_template');
        if (!$is_preview_template) {
            $is_preview_template = false;
            if ($this->return_data == false) {
                if (!defined('MW_FRONTEND')) {
                    define('MW_FRONTEND', true);
                }
            }

            if (mw()->user_manager->session_id() and $is_editmode and $is_no_editmode == false) {
                if ($is_editmode == 'n') {
                    $is_editmode = false;
                    $page_url = $this->app->url_manager->param_unset('editmode', $page_url);
                    $this->app->user_manager->session_set('back_to_editmode', true);
                    $this->app->user_manager->session_set('editmode', false);

                    return $this->app->url_manager->redirect($this->app->url_manager->site_url($page_url));
                } else {
                    $editmode_sess = $this->app->user_manager->session_get('editmode');
                    $page_url = $this->app->url_manager->param_unset('editmode', $page_url);

                    if ($is_admin == true) {
                        if ($editmode_sess == false) {
                            $this->app->user_manager->session_set('editmode', true);
                            $this->app->user_manager->session_set('back_to_editmode', false);
                            $is_editmode = false;
                        }

                        return $this->app->url_manager->redirect($this->app->url_manager->site_url($page_url));
                    } else {
                        $is_editmode = false;
                    }
                }
            }

            if (mw()->user_manager->session_id() and !$is_no_editmode) {
                $is_editmode = $this->app->user_manager->session_get('editmode');
            } else {
                $is_editmode = false;
                $page_url = $this->app->url_manager->param_unset('no_editmode', $page_url);
            }
        } else {
            $is_editmode = false;
            $page_url = $this->app->url_manager->param_unset('preview_template', $page_url);
        }
        if ($is_quick_edit == true) {
            $is_editmode = true;
        }
        $preview_module = false;
        $preview_module_template = false;
        $preview_module_id = false;
        $template_relative_layout_file_from_url = false;
        $is_preview_module = $this->app->url_manager->param('preview_module');

        if ($is_preview_module != false) {
            if ($this->app->user_manager->is_admin()) {
                $is_preview_module = module_name_decode($is_preview_module);
                if (is_module($is_preview_module)) {
                    $is_preview_module_skin = $this->app->url_manager->param('preview_module_template');
                    $preview_module_id = $this->app->url_manager->param('preview_module_id');
                    $preview_module = $is_preview_module;
                    if ($is_preview_module_skin != false) {
                        $preview_module_template = module_name_decode($is_preview_module_skin);
                        $is_editmode = false;
                    }
                }
            }
        }

        $is_layout_file = $this->app->url_manager->param('preview_layout');
        if (!$is_layout_file) {
            $is_layout_file = false;
        } else {
            $page_url = $this->app->url_manager->param_unset('preview_layout', $page_url);
        }

        if (isset($_REQUEST['content_id']) and intval($_REQUEST['content_id']) != 0) {
            $page = $this->app->content_manager->get_by_id($_REQUEST['content_id']);
        }

        if ($is_quick_edit or $is_preview_template == true or isset($_REQUEST['isolate_content_field']) or $this->create_new_page == true) {
            if (isset($_REQUEST['content_id']) and intval($_REQUEST['content_id']) != 0) {
                $page = $this->app->content_manager->get_by_id($_REQUEST['content_id']);
            } else {
                $page['id'] = 0;
                $page['content_type'] = 'page';
                if (isset($_REQUEST['content_type'])) {
                    $page['content_type'] = $this->app->database_manager->escape_string($_REQUEST['content_type']);
                }

                if (isset($_REQUEST['subtype'])) {
                    $page['subtype'] = $this->app->database_manager->escape_string($_REQUEST['subtype']);
                }
                template_var('new_content_type', $page['content_type']);
                $page['parent'] = '0';

                if (isset($_REQUEST['parent_id']) and $_REQUEST['parent_id'] != 0) {
                    $page['parent'] = intval($_REQUEST['parent_id']);
                }

                //$page['url'] = $this->app->url_manager->string();
                if (isset($is_preview_template) and $is_preview_template != false) {
                    $page['active_site_template'] = $is_preview_template;
                } else {
                }
                if (isset($is_layout_file) and $is_layout_file != false) {
                    $page['layout_file'] = $is_layout_file;
                }

                if (isset($_REQUEST['inherit_template_from']) and $_REQUEST['inherit_template_from'] != 0) {
                    $page['parent'] = intval($_REQUEST['inherit_template_from']);
                    $inherit_from = $this->app->content_manager->get_by_id($_REQUEST['inherit_template_from']);

                    //$page['parent'] =  $inherit_from ;
                    if (isset($inherit_from['layout_file']) and $inherit_from['layout_file'] == 'inherit') {
                        $inherit_from_id = $this->app->content_manager->get_inherited_parent($inherit_from['id']);
                        $inherit_from = $this->app->content_manager->get_by_id($inherit_from_id);
                    }

                    if (is_array($inherit_from) and isset($inherit_from['active_site_template'])) {
                        $page['active_site_template'] = $inherit_from['active_site_template'];
                        $is_layout_file = $page['layout_file'] = $inherit_from['layout_file'];
                    }
                }
                if (isset($_REQUEST['content_type']) and $_REQUEST['content_type'] != false) {
                    $page['content_type'] = $_REQUEST['content_type'];
                }

                if ($this->content_data != false) {
                    $page = $this->content_data;
                }
                template_var('new_page', $page);
            }
        }
        $output_cache_timeout = false;
        if (isset($is_preview_template) and $is_preview_template != false) {
            if (!defined('MW_NO_SESSION')) {
                define('MW_NO_SESSION', true);
            }
        }

        if (isset($_REQUEST['recart']) and $_REQUEST['recart'] != false) {
            event_trigger('recover_shopping_cart', $_REQUEST['recart']);
        }

        if ($output_cache_timeout != false) {
            $output_cache_id = __FUNCTION__ . crc32($_SERVER['REQUEST_URI']);
            $output_cache_group = 'content/preview';
            $output_cache_content = $this->app->cache_manager->get($output_cache_id, $output_cache_group, $output_cache_timeout);
            if ($output_cache_content != false) {
                echo $output_cache_content;

                return;
            }
        }

        $the_active_site_template = $this->app->option_manager->get('current_template', 'template');

        $date_format = $this->app->option_manager->get('date_format', 'website');
        if ($date_format == false) {
            $date_format = 'Y-m-d H:i:s';
        }

        if ($page == false or $this->create_new_page == true) {
            if (trim($page_url) == '' and $preview_module == false) {
                $page = $this->app->content_manager->homepage();
            } else {
                $found_mod = false;
                $page = $this->app->content_manager->get_by_url($page_url);
                $page_exact = $this->app->content_manager->get_by_url($page_url, true);
                $page_url_segment_1 = $this->app->url_manager->segment(0, $page_url);
                if ($preview_module != false) {
                    $page_url = $preview_module;
                }
                if ($the_active_site_template == false or $the_active_site_template == '') {
                    $the_active_site_template = 'default';
                }

                if ($page_exact == false and $found_mod == false and $this->app->modules->is_installed($page_url) and $page_url != 'settings' and $page_url != 'admin') {
                    $found_mod = true;
                    $page['id'] = 0;
                    $page['content_type'] = 'page';
                    $page['parent'] = '0';
                    $page['url'] = $this->app->url_manager->string();
                    $page['active_site_template'] = $the_active_site_template;
                    template_var('no_edit', 1);

                    $mod_params = '';
                    if ($preview_module_template != false) {
                        $mod_params = $mod_params . " template='{$preview_module_template}' ";
                    }
                    if ($preview_module_id != false) {
                        $mod_params = $mod_params . " id='{$preview_module_id}' ";
                    }
                    $found_mod = $page_url;
                    $page['content'] = '<microweber module="' . $page_url . '" ' . $mod_params . '  />';

                    //  $page['simply_a_file'] = 'clean.php';
                    $page['layout_file'] = 'clean.php';
                    template_var('content', $page['content']);

                    template_var('new_page', $page);
                }

                if ($found_mod == false) {
                    if (empty($page)) {
                        $the_new_page_file = false;
                        $page_url_segment_1 = $this->app->url_manager->segment(0, $page_url);
                        $td = templates_path() . $page_url_segment_1;
                        $td_base = $td;

                        $page_url_segment_2 = $this->app->url_manager->segment(1, $page_url);
                        $directly_to_file = false;
                        $page_url_segment_3 = $this->app->url_manager->segment(-1, $page_url);

                        $page_url_segment_1 = $the_active_site_template = $this->app->option_manager->get('current_template', 'template');
                        $td_base = templates_path() . $the_active_site_template . DS;

                        $page_url_segment_3_str = implode(DS, $page_url_segment_3);

                        if ($page_url_segment_3_str != '') {
                            $page_url_segment_3_str = rtrim($page_url_segment_3_str, DS);
                            $page_url_segment_3_str = rtrim($page_url_segment_3_str, '\\');
                            $page_url_segment_3_str_copy = $page_url_segment_3_str;

                            $is_ext = get_file_extension($page_url_segment_3_str);
                            if ($is_ext == false or $is_ext != 'php') {
                                $page_url_segment_3_str = $page_url_segment_3_str . '.php';
                            }

                            $td_f = $td_base . DS . $page_url_segment_3_str;
                            $td_fd = $td_base . DS . $page_url_segment_3_str_copy;
                            $td_fd2 = $td_base . DS . $page_url_segment_3[0];

                            if (is_file($td_f)) {
                                $the_new_page_file = $page_url_segment_3_str;
                                $simply_a_file = $directly_to_file = $td_f;
                            } else {
                                if (is_dir($td_fd)) {
                                    $td_fd_index = $td_fd . DS . 'index.php';
                                    if (is_file($td_fd_index)) {
                                        $the_new_page_file = $td_fd_index;
                                        $simply_a_file = $directly_to_file = $td_fd_index;
                                    }
                                } else {
                                    $is_ext = get_file_extension($td_fd);
                                    if ($is_ext == false or $is_ext != 'php') {
                                        $td_fd = $td_fd . '.php';
                                    }
                                    $is_ext = get_file_extension($td_fd2);
                                    if ($is_ext == false or $is_ext != 'php') {
                                        $td_fd2 = $td_fd2 . '.php';
                                    }
                                    if (is_file($td_fd)) {
                                        $the_new_page_file = $td_fd;
                                        $simply_a_file = $directly_to_file = $td_fd;
                                    } elseif (is_file($td_fd2)) {
                                        $the_new_page_file = $td_fd2;
                                        $simply_a_file = $directly_to_file = $td_fd2;
                                    } else {
                                        $td_basedef = templates_path() . 'default' . DS . $page_url_segment_3_str;
                                        if (is_file($td_basedef)) {
                                            $the_new_page_file = $td_basedef;
                                            $simply_a_file = $directly_to_file = $td_basedef;
                                        }
                                    }
                                }
                            }
                        }

                        $fname1 = 'index.php';
                        $fname2 = $page_url_segment_2 . '.php';
                        $fname3 = $page_url_segment_2;

                        $tf1 = $td . DS . $fname1;
                        $tf2 = $td . DS . $fname2;
                        $tf3 = $td . DS . $fname3;

                        if ($directly_to_file == false and is_dir($td)) {
                            if (is_file($tf1)) {
                                $simply_a_file = $tf1;
                                $the_new_page_file = $fname1;
                            }

                            if (is_file($tf2)) {
                                $simply_a_file = $tf2;
                                $the_new_page_file = $fname2;
                            }
                            if (is_file($tf3)) {
                                $simply_a_file = $tf3;
                                $the_new_page_file = $fname3;
                            }

                            if (($simply_a_file) != false) {
                                $simply_a_file = str_replace('..', '', $simply_a_file);
                                $simply_a_file = normalize_path($simply_a_file, false);
                            }
                        }

                        if ($simply_a_file == false) {

                            //$page = $this->app->content_manager->homepage();
                            $page = false;
                            if (!is_array($page)) {
                                $page = array();

                                $page['id'] = 0;
                                $page['content_type'] = 'page';
                                $page['parent'] = '0';
                                $page['url'] = $this->app->url_manager->string();

                                //  $page['active_site_template'] = $page_url_segment_1;
                                $page['simply_a_file'] = 'clean.php';
                                $page['layout_file'] = 'clean.php';
                                $show_404_to_non_admin = true;
                            }

                            if (is_array($page_url_segment_3)) {
                                foreach ($page_url_segment_3 as $mvalue) {
                                    if ($found_mod == false and $this->app->modules->is_installed($mvalue)) {
                                        $found_mod = true;
                                        $page['id'] = 0;
                                        $page['content_type'] = 'page';
                                        $page['parent'] = '0';
                                        $page['url'] = $this->app->url_manager->string();
                                        $page['active_site_template'] = $page_url_segment_1;
                                        $page['content'] = '<module type="' . $mvalue . '" />';
                                        $page['simply_a_file'] = 'clean.php';
                                        $page['layout_file'] = 'clean.php';
                                        template_var('content', $page['content']);

                                        template_var('new_page', $page);
                                        $show_404_to_non_admin = false;
                                    }
                                }
                            }
                        } else {
                            if (!is_array($page)) {
                                $page = array();
                            }

                            $page['id'] = 0;

                            if (isset($page_data) and isset($page_data['id'])) {

                                //  $page['id'] = $page_data['id'];
                            }

                            $page['content_type'] = 'page';
                            $page['parent'] = '0';
                            $page['url'] = $this->app->url_manager->string();

                            $page['active_site_template'] = $page_url_segment_1;

                            $page['layout_file'] = $the_new_page_file;
                            $page['simply_a_file'] = $simply_a_file;

                            template_var('new_page', $page);
                            template_var('simply_a_file', $simply_a_file);
                        }
                    }
                }
            }
        }

        if ($page['id'] != 0) {

            // if(!isset($page['layout_file']) or $page['layout_file'] == false){
            $page = $this->app->content_manager->get_by_id($page['id']);

            // }

            if ($page['content_type'] == 'post' and isset($page['parent'])) {
                $content = $page;
                $page = $this->app->content_manager->get_by_id($page['parent']);
            } else {
                $content = $page;
            }
        } else {
            $content = $page;
        }

        if (isset($content['created_at']) and trim($content['created_at']) != '') {
            $content['created_at'] = date($date_format, strtotime($content['created_at']));
        }

        if (isset($content['updated_at']) and trim($content['updated_at']) != '') {
            $content['updated_at'] = date($date_format, strtotime($content['updated_at']));
        }

        if ($is_preview_template != false) {
            $is_preview_template = str_replace('____', DS, $is_preview_template);
            $is_preview_template = str_replace('..', '', $is_preview_template);

            $content['active_site_template'] = $is_preview_template;
        }

        if ($is_layout_file != false and $is_admin == true) {
            $is_layout_file = str_replace('____', DS, $is_layout_file);
            if ($is_layout_file == 'inherit') {
                if (isset($_REQUEST['inherit_template_from']) and intval($_REQUEST['inherit_template_from']) != 0) {
                    $inherit_layout_from_this_page = $this->app->content_manager->get_by_id($_REQUEST['inherit_template_from']);

                    if (isset($inherit_layout_from_this_page['layout_file']) and $inherit_layout_from_this_page['layout_file'] != 'inherit') {
                        $is_layout_file = $inherit_layout_from_this_page['layout_file'];
                    }

                    if (isset($inherit_layout_from_this_page['layout_file']) and $inherit_layout_from_this_page['layout_file'] != 'inherit') {
                        $is_layout_file = $inherit_layout_from_this_page['layout_file'];
                    }
                }
            }
            $content['layout_file'] = $is_layout_file;
        }
        if ($is_custom_view and $is_custom_view != false) {
            $content['custom_view'] = $is_custom_view;
        }

        if (isset($content['is_active']) and ($content['is_active'] == 'n' or $content['is_active'] == 0)) {
            if ($this->app->user_manager->is_admin() == false) {
                $page_non_active = array();
                $page_non_active['id'] = 0;
                $page_non_active['content_type'] = 'page';
                $page_non_active['parent'] = '0';
                $page_non_active['url'] = $this->app->url_manager->string();
                $page_non_active['content'] = 'This page is not published!';
                $page_non_active['simply_a_file'] = 'clean.php';
                $page_non_active['layout_file'] = 'clean.php';
                $page_non_active['page_non_active'] = true;
                template_var('content', $page_non_active['content']);
                $content = $page_non_active;
            }
        } elseif (isset($content['is_deleted']) and $content['is_deleted'] == 1) {
            if ($this->app->user_manager->is_admin() == false) {
                $page_non_active = array();
                $page_non_active['id'] = 0;
                $page_non_active['content_type'] = 'page';
                $page_non_active['parent'] = '0';
                $page_non_active['url'] = $this->app->url_manager->string();
                $page_non_active['content'] = 'This page is deleted!';
                $page_non_active['simply_a_file'] = 'clean.php';
                $page_non_active['layout_file'] = 'clean.php';
                $page_non_active['page_is_deleted'] = true;
                template_var('content', $page_non_active['content']);
                $content = $page_non_active;
            }
        }

        if (isset($content['require_login']) and $content['require_login'] == 1) {
            if ($this->app->user_manager->id() == 0) {
                $page_non_active = array();
                $page_non_active['id'] = 0;
                $page_non_active['content_type'] = 'page';
                $page_non_active['parent'] = '0';
                $page_non_active['url'] = $this->app->url_manager->string();
                $page_non_active['content'] = ' <module type="users/login" class="user-require-login-on-view" /> ';
                $page_non_active['simply_a_file'] = 'clean.php';
                $page_non_active['layout_file'] = 'clean.php';
                $page_non_active['page_require_login'] = true;

                template_var('content', $page_non_active['content']);
                $content = $page_non_active;
            }
        }
        if (!defined('IS_HOME')) {
            if (isset($content['is_home']) and $content['is_home'] == 1) {
                define('IS_HOME', true);
            }
        }


        $this->app->content_manager->define_constants($content);

        event_trigger('mw.front', $content);
        event_trigger('mw_frontend', $content);

        $render_file = $this->app->template->get_layout($content);

        $content['render_file'] = $render_file;

        if (defined('TEMPLATE_DIR')) {
            $load_template_functions = TEMPLATE_DIR . 'functions.php';

            if (is_file($load_template_functions)) {
                include_once $load_template_functions;
            }
        }

        if ($this->return_data != false) {
            return $content;
        }

        if (isset($content['original_link']) and $content['original_link'] != '') {
            $content['original_link'] = str_ireplace('{site_url}', $this->app->url_manager->site(), $content['original_link']);
            $redirect = $this->app->format->prep_url($content['original_link']);
            if ($redirect != '') {
                return $this->app->url_manager->redirect($redirect);
            }
        }

        if (!isset($page['title'])) {
            $page['title'] = 'New page';
        }
        if (!isset($content['title'])) {
            $content['title'] = 'New content';
        }
        $category = false;
        if (defined('CATEGORY_ID')) {
            $category = $this->app->category_manager->get_by_id(CATEGORY_ID);
        }

        if ($render_file) {
            $render_params = array();
            if ($show_404_to_non_admin) {
                if (!is_admin()) {
                    $load_template_404 = template_dir() . '404.php';
                    if (is_file($load_template_404)) {
                        $render_file = $load_template_404;
                    }
                }
            }

            $render_params['render_file'] = $render_file;
            $render_params['page_id'] = PAGE_ID;
            $render_params['content_id'] = CONTENT_ID;
            $render_params['post_id'] = POST_ID;
            $render_params['category_id'] = CATEGORY_ID;
            $render_params['content'] = $content;
            $render_params['category'] = $category;
            $render_params['page'] = $page;
            $render_params['meta_tags'] = true;

            $l = $this->app->template->render($render_params);
            if (is_object($l)) {
                return $l;
            }

            // used for preview from the admin wysiwyg
            if (isset($_REQUEST['isolate_content_field'])) {
                require_once MW_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';
                $pq = \phpQuery::newDocument($l);

                $isolated_head = pq('head')->eq(0)->html();

                $found_field = false;
                if (isset($_REQUEST['isolate_content_field'])) {
                    foreach ($pq['[field=content]'] as $elem) {
                        $isolated_el = $l = pq($elem)->htmlOuter();
                    }
                }

                $is_admin = $this->app->user_manager->is_admin();
                if ($is_admin == true and isset($isolated_el) != false) {
                    $tb = mw_includes_path() . DS . 'toolbar' . DS . 'editor_tools' . DS . 'wysiwyg' . DS . 'index.php';

                    //$layout_toolbar = file_get_contents($filename);
                    $layout_toolbar = new \Microweber\View($tb);
                    $layout_toolbar = $layout_toolbar->__toString();
                    if ($layout_toolbar != '') {
                        if (strstr($layout_toolbar, '{head}')) {
                            if ($isolated_head != false) {
                                $layout_toolbar = str_replace('{head}', $isolated_head, $layout_toolbar);
                            }
                        }

                        if (strpos($layout_toolbar, '{content}')) {
                            $l = str_replace('{content}', $l, $layout_toolbar);
                        }

                        //$layout_toolbar = mw()->parser->process($layout_toolbar, $options = array('no_apc' => 1));
                    }
                }
            }
            $modify_content = event_trigger('on_load', $content);

            if ($is_editmode == true and !defined('IN_EDIT')) {
                define('IN_EDIT', true);
            }

            if (isset($is_quick_edit) and $is_quick_edit == true and !defined('QUICK_EDIT')) {
                define('QUICK_EDIT', true);
            }

            $l = $this->app->parser->process($l, $options = false);

            if ($preview_module_id != false) {
                $_REQUEST['embed_id'] = $preview_module_id;
            }
            if (isset($_REQUEST['embed_id'])) {
                $find_embed_id = trim($_REQUEST['embed_id']);
                $l = $this->app->parser->get_by_id($find_embed_id, $l);
            }

            $apijs_loaded = $this->app->template->get_apijs_url();

            //$apijs_loaded = $this->app->template->get_apijs_url() . '?id=' . CONTENT_ID;

            $is_admin = $this->app->user_manager->is_admin();
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
                    $l = str_ireplace('</head>', $template_headers_append . '</head>', $l, $one);
                }
            }

            $template_headers_src = $this->app->template->head(true);
            $template_footer_src = $this->app->template->foot(true);

            $template_headers_src_callback = $this->app->template->head_callback($page);
            if (is_array($template_headers_src_callback) and !empty($template_headers_src_callback)) {
                foreach ($template_headers_src_callback as $template_headers_src_callback_str) {
                    if (is_string($template_headers_src_callback_str)) {
                        $template_headers_src = $template_headers_src . "\n" . $template_headers_src_callback_str;
                    }
                }
            }
            if (isset($page['created_by'])) {
                $author = $this->app->user_manager->get_by_id($page['created_by']);
                if (is_array($author) and isset($author['profile_url']) and $author['profile_url'] != false) {
                    $template_headers_src = $template_headers_src . "\n" . '<link rel="author" href="' . trim($author['profile_url']) . '" />' . "\n";
                }
            }

            if ($template_headers_src != false and is_string($template_headers_src)) {
                $l = str_ireplace('</head>', $template_headers_src . '</head>', $l, $one);
            }
            if ($template_footer_src != false and is_string($template_footer_src)) {
                $l = str_ireplace('</body>', $template_footer_src . '</body>', $l, $one);
            }

            $l = str_ireplace('<head>', '<head>' . $default_css, $l);
            if (!stristr($l, $apijs_loaded)) {
                //$apijs_settings_loaded = $this->app->template->get_apijs_settings_url() . '?id=' . CONTENT_ID . '&category_id=' . CATEGORY_ID;;
                $apijs_settings_loaded = $this->app->template->get_apijs_settings_url();
                $apijs_settings_script = "\r\n" . '<script src="' . $apijs_settings_loaded . '"></script>' . "\r\n";
                $apijs_settings_script .= '<script src="' . $apijs_loaded . '"></script>' . "\r\n";
                $l = str_ireplace('<head>', '<head>' . $apijs_settings_script, $l);
            }

            if (isset($content['active_site_template']) and $content['active_site_template'] == 'default' and $the_active_site_template != 'default' and $the_active_site_template != 'mw_default') {
                $content['active_site_template'] = $the_active_site_template;
            }
            if (isset($content['active_site_template']) and trim($content['active_site_template']) != '' and $content['active_site_template'] != 'default') {
                if (!defined('CONTENT_TEMPLATE')) {
                    define('CONTENT_TEMPLATE', $content['active_site_template']);
                }

                $custom_live_edit = TEMPLATES_DIR . DS . $content['active_site_template'] . DS . 'live_edit.css';
                $live_edit_css_folder = userfiles_path() . 'css' . DS . $content['active_site_template'] . DS;
                $live_edit_url_folder = userfiles_url() . 'css/' . $content['active_site_template'] . '/';
                $custom_live_edit = $live_edit_css_folder . DS . 'live_edit.css';
            } else {
                if (!defined('CONTENT_TEMPLATE')) {
                    define('CONTENT_TEMPLATE', $the_active_site_template);
                }

                //                if ($the_active_site_template == 'mw_default') {
                //                    $the_active_site_template = 'default';
                //                }
                $custom_live_edit = TEMPLATE_DIR . DS . 'live_edit.css';

                $live_edit_css_folder = userfiles_path() . 'css' . DS . $the_active_site_template . DS;
                $live_edit_url_folder = userfiles_url() . 'css/' . $the_active_site_template . '/';
                $custom_live_edit = $live_edit_css_folder . 'live_edit.css';
            }
            $custom_live_edit = normalize_path($custom_live_edit, false);

            if (is_file($custom_live_edit)) {
                $custom_live_editmtime = filemtime($custom_live_edit);
                $liv_ed_css = '<link rel="stylesheet" href="' . $live_edit_url_folder . 'live_edit.css?version=' . $custom_live_editmtime . '" id="mw-template-settings" type="text/css" />';
                $l = str_ireplace('</head>', $liv_ed_css . '</head>', $l);
            }

            $liv_ed_css = $this->app->template->get_custom_css_url();
            if ($liv_ed_css != false) {
                $liv_ed_css = '<link rel="stylesheet" href="' . $liv_ed_css . '" id="mw-custom-user-css" type="text/css" />';
                $l = str_ireplace('</head>', $liv_ed_css . '</head>', $l);
            }
            $website_head_tags = $this->app->option_manager->get('website_head', 'website');
            $rep_count = 1;
            if ($website_head_tags != false) {
                $l = str_ireplace('</head>', $website_head_tags . '</head>', $l, $rep_count);
            }

            if (defined('MW_VERSION')) {
                $generator_tag = "\n" . '<meta name="generator" content="Microweber" />' . "\n";
                $l = str_ireplace('</head>', $generator_tag . '</head>', $l, $rep_count);
            }

            if ($is_editmode == true and $this->isolate_by_html_id == false and !isset($_REQUEST['isolate_content_field'])) {
                if ($is_admin == true) {
                    $tb = mw_includes_path() . DS . 'toolbar' . DS . 'toolbar.php';

                    $layout_toolbar = new \Microweber\View($tb);
                    $is_editmode_basic = false;
                    $user_data = $this->app->user_manager->get();
                    if (isset($user_data['basic_mode']) and trim($user_data['basic_mode'] == 'y')) {
                        $is_editmode_basic = true;
                    }

                    if (isset($is_editmode_basic) and $is_editmode_basic == true) {
                        $layout_toolbar->assign('basic_mode', true);
                    } else {
                        $layout_toolbar->assign('basic_mode', false);
                    }
                    event_trigger('mw.live_edit');
                    $layout_toolbar = $layout_toolbar->__toString();
                    if ($layout_toolbar != '') {
                        $layout_toolbar = $this->app->parser->process($layout_toolbar, $options = array('no_apc' => 1));

                        $c = 1;
                        $l = str_ireplace('</body>', $layout_toolbar . '</body>', $l, $c);
                    }

                    $custom_live_edit = TEMPLATES_DIR . DS . TEMPLATE_NAME . DS . 'live_edit.php';
                    $custom_live_edit = normalize_path($custom_live_edit, false);
                    if (is_file($custom_live_edit)) {
                        $layout_live_edit = new \Microweber\View($custom_live_edit);
                        $layout_live_edit = $layout_live_edit->__toString();
                        if ($layout_live_edit != '') {
                            $l = str_ireplace('</body>', $layout_live_edit . '</body>', $l, $c);
                        }
                    }
                }
            } elseif ($is_editmode == false and $is_admin == true and mw()->user_manager->session_id() and !(mw()->user_manager->session_all() == false) and mw()->user_manager->session_get('back_to_editmode')) {
                if (!isset($_REQUEST['isolate_content_field']) and !isset($_REQUEST['content_id'])) {
                    $back_to_editmode = $this->app->user_manager->session_get('back_to_editmode');
                    if ($back_to_editmode == true) {
                        $tb = mw_includes_path() . DS . 'toolbar' . DS . 'toolbar_back.php';

                        $layout_toolbar = new \Microweber\View($tb);

                        $layout_toolbar = $layout_toolbar->__toString();

                        if ($layout_toolbar != '') {
                            $layout_toolbar = $this->app->parser->process($layout_toolbar, $options = array('no_apc' => 1));
                            $c = 1;
                            $l = str_ireplace('</body>', $layout_toolbar . '</body>', $l, $c);
                        }
                    }
                }
            }

            $l = str_replace('{TEMPLATE_URL}', TEMPLATE_URL, $l);
            $l = str_replace('{THIS_TEMPLATE_URL}', THIS_TEMPLATE_URL, $l);
            $l = str_replace('{DEFAULT_TEMPLATE_URL}', DEFAULT_TEMPLATE_URL, $l);

            $l = str_replace('%7BTEMPLATE_URL%7D', TEMPLATE_URL, $l);
            $l = str_replace('%7BTHIS_TEMPLATE_URL%7D', THIS_TEMPLATE_URL, $l);
            $l = str_replace('%7BDEFAULT_TEMPLATE_URL%7D', DEFAULT_TEMPLATE_URL, $l);


            if ($page != false and empty($this->page)) {
                $this->page = $page;
            }
            $l = execute_document_ready($l);

            event_trigger('frontend');

            $is_embed = $this->app->url_manager->param('embed');

            if ($is_embed != false) {
                $this->isolate_by_html_id = $is_embed;
            }

            if ($this->isolate_by_html_id != false) {
                $id_sel = $this->isolate_by_html_id;
                $this->isolate_by_html_id = false;
                require_once MW_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';
                $pq = \phpQuery::newDocument($l);
                foreach ($pq['#' . $id_sel] as $elem) {
                    $l = pq($elem)->htmlOuter();
                }
            }
            if (mw()->user_manager->session_id() and !(mw()->user_manager->session_all() == false) and $is_editmode) {
                $this->app->user_manager->session_set('last_content_id', CONTENT_ID);
            }

            if ($output_cache_timeout != false) {
                $this->app->cache_manager->save($l, $output_cache_id, $output_cache_group);
            }

            if (isset($_REQUEST['debug'])) {
                if ($this->app->make('config')->get('app.debug')) {
                    $is_admin = $this->app->user_manager->is_admin();
                    if ($is_admin == true) {
                        include mw_includes_path() . 'debug.php';
                    }
                }
            }


            if ($show_404_to_non_admin) {
                $response = \Response::make($l);
                $response->setStatusCode(404);
                return $response;
            }

            return $l;
        } else {
            echo 'Error! Page is not found? Please login in the admin and make a page.';

            $this->app->cache_manager->clear();

            return;
        }
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

    public function sitemapxml()
    {
        $sm_file = mw_cache_path() . 'sitemap.xml';

        $skip = false;
        if (is_file($sm_file)) {
            $filelastmodified = filemtime($sm_file);

            if (($filelastmodified - time()) > 3 * 3600) {
                $skip = 1;
            }
        }

        if ($skip == false) {
            $map = new \Microweber\Utils\Sitemap($sm_file);
            $map->file = mw_cache_path() . 'sitemap.xml';

            $cont = get_content('is_active=1&is_deleted=0&limit=2500&fields=id,updated_at&orderby=updated_at desc');

            if (!empty($cont)) {
                foreach ($cont as $item) {
                    $map->addPage($this->app->content_manager->link($item['id']), 'daily', 1, $item['updated_at']);
                }
            }
            $map = $map->create();
        }
        $map = $sm_file;
        $fp = fopen($map, 'r');

        // send the right headers
        header('Content-Type: text/xml');
        header('Content-Length: ' . filesize($map));

        // dump the file and stop the script
        fpassthru($fp);

        event_trigger('mw_robot_url_hit');

        exit;
    }

    private function _api_responce($res)
    {

        if (defined('MW_API_RAW')) {
            return $res;
        }
        if (!defined('MW_API_HTML_OUTPUT')) {
            if (is_bool($res) or is_int($res)) {
                return json_encode($res);
            } elseif ($res instanceof RedirectResponse) {
                return $res;
            } elseif ($res instanceof Response) {
                return $res;
            }
            $response = \Response::make($res);
            if (is_bool($res) or is_int($res) or is_array($res)) {
                $response->header('Content-Type', 'application/json');
            }

            return $response;
        } else {
            if (is_array($res)) {
                $res = json_encode($res);
            }
            echo $res;

            return;
        }
    }

    public function apijs_settings()
    {
        if (!defined('MW_NO_SESSION')) {
            define('MW_NO_SESSION', 1);
        }
        $lastModified = time() - 120;
        $etagFile = md5(serialize($_REQUEST));
        $ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);

        //get the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
        $etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

        //set last-modified header
        // header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModified) . " GMT");
        // header('Cache-Control: public');
        // header("Etag: $etagFile");

        if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModified || $etagHeader == $etagFile) {
            // header("HTTP/1.1 304 Not Modified");
            // return;
            // exit;
        }

        $ref_page = false;

        if (isset($_REQUEST['id'])) {
            $ref_page = $this->app->content_manager->get_by_id($_REQUEST['id']);
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $ref_page = $_SERVER['HTTP_REFERER'];
            if ($ref_page != '') {
                $ref_page = $this->app->content_manager->get_by_url($ref_page);
                $page_id = $ref_page['id'];
            }
        }

        $cat_url = false;
        if (isset($_REQUEST['category_id'])) {
            $cat_url = intval($_REQUEST['category_id']);
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $cat_url = mw()->category_manager->get_category_id_from_url($_SERVER['HTTP_REFERER']);
            $cat_url = intval($cat_url);
        }

        if ($cat_url != false) {
            if (!defined('CATEGORY_ID')) {
                define('CATEGORY_ID', intval($cat_url));
            }
        }

        // header("Content-type: text/javascript");

        $file = mw_includes_path() . 'api' . DS . 'api_settings.js';

        $this->app->content_manager->define_constants($ref_page);
        $l = new \Microweber\View($file);

        $l = $l->__toString();

        $compile_assets = \Config::get('microweber.compile_assets');
        if ($compile_assets and defined('MW_VERSION')) {
            $userfiles_dir = userfiles_path();
            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS . 'apijs');
            $userfiles_cache_filename = $userfiles_cache_dir . 'api_settings.' . md5(site_url() . template_dir()) . '.' . MW_VERSION . '.js';
            if (!is_file($userfiles_cache_filename)) {
                if (!is_dir($userfiles_cache_dir)) {
                    mkdir_recursive($userfiles_cache_dir);
                }
                if (is_dir($userfiles_cache_dir)) {
                    @file_put_contents($userfiles_cache_filename, $l);
                }
            } else {
                $fmd5 = md5_file($userfiles_cache_filename);
                $fmd = md5($l);
                if ($fmd5 != $fmd) {
                    @file_put_contents($userfiles_cache_filename, $l);
                }
            }
        }

        $response = \Response::make($l);

        $response->header('Content-Type', 'application/javascript');

        return $response;

        // return;
    }

    public function apijs()
    {
        if (!defined('MW_NO_SESSION')) {
            define('MW_NO_SESSION', 1);
        }

        $ref_page = false;
        if (isset($_REQUEST['id'])) {
            $ref_page = $this->app->content_manager->get_by_id($_REQUEST['id']);
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $ref_page = $_SERVER['HTTP_REFERER'];
            if ($ref_page != '') {
                $ref_page = $this->app->content_manager->get_by_url($ref_page);
                $page_id = $ref_page['id'];
            }
        }
        if (isset($_SERVER['HTTP_REFERER'])) {
            $cat_url = mw()->category_manager->get_category_id_from_url($_SERVER['HTTP_REFERER']);
            if ($cat_url != false) {
                if (!defined('CATEGORY_ID')) {
                    define('CATEGORY_ID', intval($cat_url));
                }
            }
        }
        $file = mw_includes_path() . 'api' . DS . 'api.js';

        $last_modified_time = $lastModified = filemtime($file);

        $ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
        $etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);
        if (defined('MW_VERSION')) {
            $etag = md5(filemtime($file) . MW_VERSION);
        } else {
            $etag = filemtime($file);
        }

        $this->app->content_manager->define_constants($ref_page);
        $l = new \Microweber\View($file);

        $l = $l->__toString();
        $l = str_replace('{SITE_URL}', $this->app->url_manager->site(), $l);
        $l = str_replace('{MW_SITE_URL}', $this->app->url_manager->site(), $l);
        $l = str_replace('%7BSITE_URL%7D', $this->app->url_manager->site(), $l);

        $response = \Response::make($l);
        $response->header('Content-Type', 'application/javascript');

        $compile_assets = \Config::get('microweber.compile_assets');
        if ($compile_assets and defined('MW_VERSION')) {
            $userfiles_dir = userfiles_path();
            $hash = md5(site_url());
            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS . 'apijs');
            $userfiles_cache_filename = $userfiles_cache_dir . 'api.' . $hash . '.' . MW_VERSION . '.js';
            if (!is_file($userfiles_cache_filename)) {
                if (!is_dir($userfiles_cache_dir)) {
                    mkdir_recursive($userfiles_cache_dir);
                }
                if (is_dir($userfiles_cache_dir)) {
                    @file_put_contents($userfiles_cache_filename, $l);
                }
            } else {
                $fmd5 = md5_file($userfiles_cache_filename);
                $fmd = md5($l);
                if ($fmd5 != $fmd) {
                    @file_put_contents($userfiles_cache_filename, $l);
                }
            }
        }

        if (!$this->app->make('config')->get('app.debug')) {
            // enable caching if in not in debug mode
            $response->header('Etag', $etag);
            $response->header('Last-Modified', gmdate('D, d M Y H:i:s', $last_modified_time) . ' GMT');
            $response->setTtl(30);
        }

        return $response;
    }

    public function editor_tools()
    {
        if (!defined('IN_ADMIN') and is_admin()) {
            define('IN_ADMIN', true);
        }
        if (!defined('IN_EDITOR_TOOLS')) {
            define('IN_EDITOR_TOOLS', true);
        }

        if (mw_is_installed() == true) {

            //event_trigger('mw_db_init');
            //  event_trigger('mw_cron');
        }

        $tool = $this->app->url_manager->segment(1);

        if ($tool) {
        } else {
            $tool = 'index';
        }

        $page = false;
        if (isset($_REQUEST['content_id'])) {
            if (intval($_REQUEST['content_id']) == 0) {
                $this->create_new_page = true;

                $custom_content_data_req = $_REQUEST;
                $custom_content_data = array();
                if (isset($custom_content_data_req['content_type'])) {
                    //    $custom_content_data['content_type'] = $custom_content_data_req['content_type'];
                }
                if (isset($custom_content_data_req['content_type'])) {
                    $custom_content_data['content_type'] = $custom_content_data_req['content_type'];
                }
                if (isset($custom_content_data_req['subtype'])) {
                    $custom_content_data['subtype'] = $custom_content_data_req['subtype'];
                }
                if (isset($custom_content_data_req['parent_page']) and is_numeric($custom_content_data_req['parent_page'])) {
                    $custom_content_data['parent'] = intval($custom_content_data_req['parent_page']);
                }
                if (isset($custom_content_data_req['preview_layout'])) {
                    //  $custom_content_data['preview_layout'] =($custom_content_data_req['preview_layout']);
                }
                if (!empty($custom_content_data)) {
                    $custom_content_data['id'] = 0;
                    $this->content_data = $custom_content_data;
                }

                $this->return_data = 1;
                $page = $this->frontend();
            } else {
                $page = $this->app->content_manager->get_by_id($_REQUEST['content_id']);
            }
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
            $url = explode('?', $url);
            $url = $url[0];

            if (trim($url) == '' or trim($url) == $this->app->url_manager->site()) {

                //$page = $this->app->content_manager->get_by_url($url);
                $page = $this->app->content_manager->homepage();
            } else {
                $page = $this->app->content_manager->get_by_url($url);
            }
        } else {
            $url = $this->app->url_manager->string();
        }

        if (!isset($page['active_site_template'])) {
            $page['active_site_template'] = 'default';
        }

        if (isset($_GET['preview_template'])) {
            $page['active_site_template'] = $_GET['preview_template'];
        }
        if (isset($_GET['content_type'])) {
            $page['content_type'] = $_GET['content_type'];
        }
        if (isset($_GET['preview_layout']) and $_GET['preview_layout'] != 'inherit') {
            $page['layout_file'] = $_GET['preview_layout'];
        }

        $this->app->content_manager->define_constants($page);

        $page['render_file'] = $this->app->template->get_layout($page);

        if (defined('TEMPLATE_DIR')) {
            $load_template_functions = TEMPLATE_DIR . 'functions.php';

            if (is_file($load_template_functions)) {
                include_once $load_template_functions;
            }
        }

        $params = $_REQUEST;
        $tool = str_replace('..', '', $tool);

        $p_index = mw_includes_path() . 'toolbar/editor_tools/index.php';
        $p_index = normalize_path($p_index, false);

        $standalone_edit = true;
        $p = mw_includes_path() . 'toolbar/editor_tools/' . $tool . '/index.php';
        $standalone_edit = false;
        if ($tool == 'plupload') {
            $standalone_edit = true;
        }
        if ($tool == 'plupload') {
            $standalone_edit = true;
        }
        if ($tool == 'imageeditor') {
            $standalone_edit = true;
        }

        if ($tool == 'rte_image_editor') {
            $standalone_edit = true;
        }
        if ($tool == 'editor_toolbar') {
            $standalone_edit = true;
        }

        if ($tool == 'wysiwyg') {
            $standalone_edit = false;
            $ed_file_from_template = TEMPLATE_DIR . 'editor.php';

            if (is_file($ed_file_from_template)) {
                $p_index = $ed_file_from_template;
            }

            if (isset($page['content_type']) and $page['content_type'] != 'post' and $page['content_type'] != 'page' and $page['content_type'] != 'product') {
                if (isset($page['subtype']) and ($page['subtype'] != 'post' and $page['subtype'] != 'product')) {
                    $standalone_edit = true;
                }
            } elseif (isset($page['content_type']) and $page['content_type'] == 'post') {
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
        $default_css = '';
        $apijs_settings_loaded = '';
        $apijs_loaded = '';

        $p = normalize_path($p, false);

        $l = new \Microweber\View($p_index);
        $l->params = $params;
        $layout = $l->__toString();
        $apijs_loaded = false;
        if ($layout != false) {

            //$apijs_loaded = $this->app->template->get_apijs_url() . '?id=' . CONTENT_ID;
            $apijs_loaded = $this->app->template->get_apijs_url();
            // $apijs_settings_loaded = $this->app->template->get_apijs_settings_url() . '?id=' . CONTENT_ID . '&category_id=' . CATEGORY_ID;
            $apijs_settings_loaded = $this->app->template->get_apijs_settings_url();

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

        if (isset($_REQUEST['plain'])) {
            if (is_file($p)) {
                $p = new \Microweber\View($p);
                $p->params = $params;
                $layout = $p->__toString();
                return response($layout);

             }
        } elseif (is_file($p)) {
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

        //    $page['render_file'] = $render_file;
        if (!$standalone_edit) {
            if (isset($page['render_file'])) {
                event_trigger('mw.front', $page);
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
//
//
//                $render_params = array();
//                $render_params['render_file'] = $p;
//                $render_params['page_id'] = PAGE_ID;
//                $render_params['content_id'] = CONTENT_ID;
//                $render_params['post_id'] = POST_ID;
//                $render_params['category_id'] = CATEGORY_ID;
//                $render_params['page'] = $page;
//                $render_params['params'] = $params;
//                $render_params['application'] = $this->app;

                //  $l = $this->app->template->render($render_params);
                if (is_object($l)) {
                    return $l;
                }

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
                            $err = $err . ' (' . $page['active_site_template'] . ' template)';
                        }

                        return $err;
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

        $layout = mw()->template->process_meta($layout);

        $layout = $this->app->parser->process($layout, $options = false);

        $layout = execute_document_ready($layout);

        $layout = str_replace('{head}', '', $layout);

        $layout = str_replace('{content}', '', $layout);
        return response($layout);


    }

    public function robotstxt()
    {
        header('Content-Type: text/plain');
        $robots = get_option('robots_txt', 'website');

        if ($robots == false) {
            $robots = "User-agent: *\nAllow: /" . "\n";
            $robots .= 'Disallow: /cache/' . "\n";
            $robots .= 'Disallow: /storage/' . "\n";
            $robots .= 'Disallow: /database/' . "\n";
            $robots .= 'Disallow: /vendor/' . "\n";
            $robots .= 'Disallow: /src/' . "\n";
            $robots .= 'Disallow: /userfiles/modules/' . "\n";
            $robots .= 'Disallow: /userfiles/templates/' . "\n";
        }
        event_trigger('mw_robot_url_hit');
        echo $robots;
        exit;
    }

    public function show_404()
    {
        header('HTTP/1.0 404 Not Found');
        $v = new \Microweber\View(MW_ADMIN_VIEWS_DIR . '404.php');
        echo $v;
    }

    public function __get($name)
    {
        if (isset($this->vars[$name])) {
            return $this->vars[$name];
        }
    }

    public function __set($name, $data)
    {
        if (is_callable($data)) {
            $this->functions[$name] = $data;
        } else {
            $this->vars[$name] = $data;
        }
    }

    public function __call($method, $args)
    {
        if (isset($this->functions[$method])) {
            call_user_func_array($this->functions[$method], $args);
        } else {
            // error out
        }
    }
}
