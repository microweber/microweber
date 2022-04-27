<?php

namespace MicroweberPackages\App\Http\Controllers;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use MicroweberPackages\App\Http\Middleware\ApiAuth;
use MicroweberPackages\App\Http\Middleware\SameSiteRefererMiddleware;
use MicroweberPackages\App\Managers\Helpers\VerifyCsrfTokenHelper;
use MicroweberPackages\Helper\XSSClean;
use MicroweberPackages\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;


class ApiController  extends FrontendController
{

    public function api_html()
    {
        if (!defined('MW_API_HTML_OUTPUT')) {
            define('MW_API_HTML_OUTPUT', true);
        }
        return $this->api();
    }

    public function api($api_function = false, $params = false)
    {
        if (isset($_REQUEST['api_key']) and user_id() == 0) {
            api_login($_REQUEST['api_key']);
        }

        if (!defined('MW_API_CALL')) {
            define('MW_API_CALL', true);
        }

        $set_constants = true;
        if (!mw_is_installed()) {
            $set_constants = false;
        }

        $mod_class_api = false;
        $mod_class_api_called = false;
        $mod_class_api_class_exist = false;
        $caller_commander = false;
        if ($api_function == false) {
            $api_function_full = app()->url_manager->string();
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
             app()->template_manager->boot_template();
        }

        //$api_function_full = str_ireplace('api/', '', $api_function_full);

        $api_function_full = str_replace('..', '', $api_function_full);
        $api_function_full = str_replace('\\', '/', $api_function_full);
        $api_function_full = str_replace('//', '/', $api_function_full);

        $api_function_full = app()->database_manager->escape_string($api_function_full);
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
        $api_auth_exposed = ' ';
        if (mw()->user_manager->is_logged()) {
            $get_exposed = (api_expose_user(true));
            $api_exposed .= $get_exposed;
            $api_auth_exposed .= $get_exposed;
        }

        if (is_admin()) {
            $get_exposed = (api_expose_admin(true));
            $api_exposed .= $get_exposed;
            $api_auth_exposed .= $get_exposed;
        }


        $api_exposed = explode(' ', $api_exposed);
        $api_exposed = array_unique($api_exposed);
        $api_exposed = array_trim($api_exposed);

        $api_auth_exposed = explode(' ', $api_auth_exposed);
        $api_auth_exposed = array_unique($api_auth_exposed);
        $api_auth_exposed = array_trim($api_auth_exposed);

        $hooks = api_bind(true);
        if (mw()->user_manager->is_logged()) {
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
            $api_function = app()->url_manager->segment(1);
        }

        if (!defined('MW_API_RAW')) {
            if ($mod_class_api != false) {
                $url_segs = app()->url_manager->segment(-1);
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

        $api_skip_token_validation_items = ['clearcache','logout','user_login'];

        if (in_array($api_function, $api_auth_exposed)) {
            if(!in_array($api_function, $api_skip_token_validation_items)) {

                $request = request();
                $request->merge($_GET);
                $request->merge($_POST);
                $ref = $request->headers->get('referer');

                $same_site = app()->make(SameSiteRefererMiddleware::class);
                $is_same_site = $same_site->isSameSite($ref);

                if (!$is_same_site) {
                    $bearer_token = $request->bearerToken();
                    $is_bearer_token_valid = false;


                    if ($bearer_token) {
                        $validator = app()->make(ApiAuth::class);
                        $is_bearer_token_valid = $validator->validateBearerToken($bearer_token);
                    }
                    if (!$is_bearer_token_valid) {
                        $validator = app()->make(VerifyCsrfTokenHelper::class);
                        $is_token_valid = $validator->isValid($request);
                        if (!$is_token_valid) {
                            App::abort(403, 'Unauthorized action. The API function requires authentication.');
                        }
                    }
                }
            }
        }


        switch ($caller_commander) {
            case 'class_is_already_here':

                if ($params != false) {
                    $data = $params;
                } elseif (!$_POST and !$_REQUEST) {
                    $data = app()->url_manager->params(true);
                    if (empty($data)) {
                        $data = app()->url_manager->segment(2);
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
                            return $this->_api_response($res);
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

                    return $this->_api_response($res);
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
                        return $this->_api_response($res);
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
                                $data = app()->url_manager->params(true);
                                if (empty($data)) {
                                    $data = app()->url_manager->segment(2);
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

                                return $this->_api_response($res);
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

            return $this->module();
        }
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

                    //  $data = app()->url_manager->segment(2);
                    $data = app()->url_manager->params(true);
                    if (empty($data)) {
                        $data = app()->url_manager->segment(2);
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
                    $segs = app()->url_manager->segment();
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

                        $segs = app()->url_manager->segment();
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

                            if (is_array($this->app)) {
                                $class = new $mclass($this->app);
                            } else {
                                $class = new $mclass();
                            }

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

            App::abort(403, 'The api function is not defined in the allowed functions list');



          //  mw_error('The api function ' . $api_function . ' is not defined in the allowed functions list');
        }

        if (isset($res)) {
            return $this->_api_response($res);
        }

        return;

    }


    private function _api_response($res)
    {
        $status_code = 200;
        if ($res instanceof Response) {
            return $res;
        }

        if (defined('MW_API_RAW')) {
            return response($res);
        }

        if (!defined('MW_API_HTML_OUTPUT')) {
            if (is_bool($res) or is_int($res)) {
                return \Response::make(json_encode($res), $status_code);
            } elseif ($res instanceof RedirectResponse) {
                return $res;
            } elseif ($res instanceof Response) {
                return $res;
            }

            $response = \Response::make($res, $status_code);
            if (is_bool($res) or is_int($res) or is_array($res)) {
                $response->header('Content-Type', 'application/json');
            }

            return $response;
        } else {
            if (is_array($res)) {
                $res = json_encode($res);
            } else if (is_bool($res)) {
                $res = (bool)$res;
            }
            $response = \Response::make($res, $status_code);
            return $response;
        }
    }

    public function module()
    {
        if (!defined('MW_API_CALL')) {
            //      define('MW_API_CALL', true);
        }

        if (!defined('MW_NO_SESSION')) {
            $is_ajax = app()->url_manager->is_ajax();
            if (!mw()->user_manager->session_id() and $is_ajax == false and !defined('MW_SESS_STARTED')) {
                define('MW_SESS_STARTED', true);
                //session_start();
            }
            $editmode_sess = app()->user_manager->session_get('editmode');
            if ($editmode_sess == true and !defined('IN_EDIT')) {
                define('IN_EDIT', true);
            }
        }


        $request_data = array_merge($_GET, $_POST);

        // sanitize attributes
        if($request_data){
            $request_data_new = [];

            $xssClean = new XSSClean();

            foreach ($request_data as $k=>$v){
                if(is_string($v)) {
                    $v = str_replace('<', '-', $v);
                    $v = str_replace('>', '-', $v);
                }
                if(is_array($v)) {
                    $v = $xssClean->cleanArray($v);
                } else {
                    $v = $xssClean->clean($v);
                }

                if(is_string($k)){
                    $k = str_replace('<', '-', $k);
                    $k = str_replace('>', '-', $k);

                    $k = $xssClean->clean($k);
                    if($k){
                        $request_data_new[$k] = $v;
                    }
                } else {
                    $request_data_new[$k] = $v;
                }

            }
            $request_data = $request_data_new;

        }

        $page = false;

        $custom_display = false;
        if (isset($request_data['data-display']) and $request_data['data-display'] == 'custom') {
            $custom_display = true;
        }

        if (isset($request_data['data-module-name'])) {
            $request_data['module'] = $request_data['data-module-name'];
            $request_data['data-type'] = $request_data['data-module-name'];

            if (!isset($request_data['id'])) {
                $request_data['id'] = app()->url_manager->slug($request_data['data-module-name'] . '-' . date('YmdHis'));
            }
        }

        if (isset($request_data['data-type'])) {
            $request_data['module'] = $request_data['data-type'];
        }

        if (isset($request_data['display']) and $request_data['display'] == 'custom') {
            $custom_display = true;
        }
        if (isset($request_data['view']) and $request_data['view'] == 'admin') {
            $custom_display = false;
        }

        if ($custom_display == true) {
            $custom_display_id = false;
            if (isset($request_data['id'])) {
                $custom_display_id = $request_data['id'];
            }
            if (isset($request_data['data-id'])) {
                $custom_display_id = $request_data['data-id'];
            }
        }
        if (isset($request_data['from_url'])) {
            $from_url = $request_data['from_url'];
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $from_url = $_SERVER['HTTP_REFERER'];
            $from_url_p = @parse_url($from_url);
            if (is_array($from_url_p) and isset($from_url_p['query'])) {
                $from_url_p = parse_query($from_url_p['query']);
                if (is_array($from_url_p) and isset($from_url_p['from_url'])) {
                    $from_url = $from_url_p['from_url'];
                }
            }
        }

        if (isset($from_url) and $from_url != false) {
            if (stristr($from_url, 'editor_tools/wysiwyg') && !defined('IN_EDITOR_TOOLS')) {
                define('IN_EDITOR_TOOLS', true);
            }

            if (stristr($from_url, admin_url()) && !defined('MW_BACKEND')) {
                define('MW_BACKEND', true);
            }


            $url = $from_url;
            $from_url2 = str_replace('#', '/', $from_url);

            $content_id = app()->url_manager->param('content_id', false, $from_url2);

            if ($content_id == false) {
                $content_id = app()->url_manager->param('editpage', false, $from_url2);
            }
            if ($content_id == false) {
                $content_id = app()->url_manager->param('editpost', false, $from_url2);
            }
            if ($content_id == false) {
                $is_current = app()->url_manager->param('is-current', false, $from_url2);
                if ($is_current) {
                    $content_id = app()->url_manager->param('content-id', false, $from_url2);
                } else {
                    $content_id = app()->url_manager->param('mw-adm-content-id', false, $from_url2);
                }
            }

            if ($content_id == false) {
                $action_test = app()->url_manager->param('action', false, $from_url2);

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
                    if($page){
                        $url = $page['url'];
                    }
                }
            } else {
                if (trim($url) == '' or trim($url) == app()->url_manager->site()) {

                    //var_dump($from_url);
                    //$page = $this->app->content_manager->get_by_url($url);
                    $page = $this->app->content_manager->homepage();

                    if (!defined('IS_HOME')) {
                        define('IS_HOME', true);
                    }

                    if (isset($from_url2)) {
                        $mw_quick_edit = app()->url_manager->param('mw_quick_edit', false, $from_url2);

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
            $url = app()->url_manager->string();
        }

        if (!defined('IS_HOME')) {
            if (isset($page['is_home']) and $page['is_home'] == 'y') {
                define('IS_HOME', true);
            }
        }


        if (mw_is_installed()) {
            if ($page == false) {
                if (!isset($content_id)) {
                    return;
                }

                $this->app->content_manager->define_constants(array('id' => $content_id));
            } else {
                $this->app->content_manager->define_constants($page);
            }
        }

        if (defined('TEMPLATE_DIR')) {
            app()->template_manager->boot_template();
        }

        if ($custom_display == true) {
            $u2 = app()->url_manager->site();
            $u1 = str_replace($u2, '', $url);

            $this->render_this_url = $u1;
            $this->isolate_by_html_id = $custom_display_id;
            return $this->frontend();


        }


        $url_last = false;
        if (!isset($request_data['module'])) {
            $url = app()->url_manager->string(0);
            if ($url == __FUNCTION__) {
                $url = app()->url_manager->string(0);
            }

            /*
            $is_ajax = app()->url_manager->is_ajax();

            if ($is_ajax == true) {
            $url = app()->url_manager->string(true);
            }*/

            $url = $this->app->format->replace_once('module/', '', $url);
            $url = $this->app->format->replace_once('module_api/', '', $url);
            $url = $this->app->format->replace_once('m/', '', $url);
            if (is_module($url)) {
                $request_data['module'] = $url;
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

                            $request_data['module'] = $item;
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

        $module_info = app()->url_manager->param('module_info', true);


        if ($module_info and isset($request_data['module'])) {
            $request_data['module'] = str_replace('..', '', $request_data['module']);
            $try_config_file = modules_path() . '' . $request_data['module'] . '_config.php';
            $try_config_file = normalize_path($try_config_file, false);
            if (is_file($try_config_file)) {
                include $try_config_file;

                if (!isset($config) or !is_array($config)) {
                    return false;
                }

                if (!isset($config['icon']) or $config['icon'] == false) {
                    $config['icon'] = modules_path() . '' . $request_data['module'] . '.png';
                    $config['icon'] = app()->url_manager->link_to_file($config['icon']);
                }
                echo json_encode($config);

                return;
            }
        }


        $admin = app()->url_manager->param('admin', true);

        $mod_to_edit = app()->url_manager->param('module_to_edit', true);
        $embed = app()->url_manager->param('embed', true);

        $mod_iframe = false;
        if ($mod_to_edit != false) {
            $mod_to_edit = str_ireplace('_mw_slash_replace_', '/', $mod_to_edit);
            $mod_iframe = true;
        }

        //$data = $request_data;

        if (($_POST)) {
            $data = $_POST;
        } else {
            $url = app()->url_manager->segment();

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
            $data = $request_data;
        }
        if (!isset($data['module']) and isset($mod_from_url) and $mod_from_url != false) {
            $data['module'] = ($mod_from_url);
        }

        if (!isset($data['id']) and isset($request_data['id']) == true) {
            $data['id'] = $request_data['id'];
        }
        if (isset($data['ondrop'])) {
            if (!defined('MW_MODULE_ONDROP')) {
                define('MW_MODULE_ONDROP', true);
            }

            unset($data['ondrop']);
        }
        // d($data);


        $opts = array();
        if ($request_data) {
            $opts = $request_data;
        }


        if (isset($opts['class']) and is_string($opts['class']) and strstr($opts['class'], 'module-as-element')) {
            $opts['module_as_element'] = true;
            $opts['populate_module_ids_in_elements'] = true;
        }


        if ($mod_n == 'element-from-template' && isset($data['template'])) {
            $t = str_replace('..', '', $data['template']);
            $possible_layout = TEMPLATE_DIR . $t;
            $possible_layout = normalize_path($possible_layout, false);
            $opts['element_from_template'] = true;

            if (is_file($possible_layout)) {
                $l = new View($possible_layout);
                $layout = $l->__toString();
                $layout = $this->app->parser->process($layout, $opts);
                return response($layout);
            }
        }

        if ($mod_n == 'module-' && isset($data['template'])) {
            $t = str_replace('..', '', $data['template']);
            $possible_layout = templates_path() . $t;
            $possible_layout = normalize_path($possible_layout, false);
            if (is_file($possible_layout)) {
                $l = new View($possible_layout);
                $layout = $l->__toString();
                $layout = $this->app->parser->process($layout, $opts);
                return response($layout);


                //  echo $layout;

                // return;
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

                        //$v = app()->database_manager->escape_string($v);

                        $tags .= "{$k}=\"$v\" ";
                    }
                }
            }
        }

        if ($has_id == false) {
//            if (defined('MW_MODULE_ONDROP')) {
//                $mod_n = app()->url_manager->slug($mod_n) . '-' . date("YmdHis").unquid();
//                $tags .= "id=\"$mod_n\" ";
//            }
            //  $mod_n = app()->url_manager->slug($mod_n) . '-' . date("YmdHis");
            //  $tags .= "id=\"$mod_n\" ";
        }

        $tags = "<module {$tags} />";


        if (isset($request_data['live_edit'])) {
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
            if (strstr($get_arr_from_ref, app()->url_manager->site())) {
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
            $l = new View($p_index);
            $layout = $l->__toString();
            $res = str_replace('{content}', $res, $layout);
        }

        $aj = app()->url_manager->is_ajax();

        if ((isset($request_data['live_edit']) or isset($request_data['admin'])) and $aj == false) {
            $p_index = mw_includes_path() . DS . 'toolbar' . DS . 'editor_tools' . DS . 'module_settings' . DS . 'index.php';
            $p_index = normalize_path($p_index, false);
            $l = new View($p_index);
            $l->params = $data;
            $layout = $l->__toString();
            $res = str_replace('{content}', $res, $layout);
            $res = $this->app->parser->process($res, $options = false);
        }

        $res = mw()->template->process_stacks($res);

        $res = execute_document_ready($res);
        if (!defined('MW_NO_OUTPUT')) {
            $res = app()->url_manager->replace_site_url_back($res);
            return response($res);

            // echo $res;
        }

        if ($url_last == __FUNCTION__) {
            return;
        }
        if (function_exists($url_last)) {
            $this->api($url_last);
        } elseif (isset($url_prev) and function_exists($url_prev)) {
            $this->api($url_last);
        } elseif (class_exists($url_last, false)) {
            $this->api($url_last);
        } elseif (isset($url_prev) and class_exists($url_prev, false)) {
            $this->api($url_prev);
        }

        return;
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

        $tool = app()->url_manager->segment(1);

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

            if (trim($url) == '' or trim($url) == app()->url_manager->site()) {

                //$page = $this->app->content_manager->get_by_url($url);
                $page = $this->app->content_manager->homepage();
            } else {
                $page = $this->app->content_manager->get_by_url($url);
            }
        } else {
            $url = app()->url_manager->string();
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
            app()->template_manager->boot_template();
        }

        // $params = $_REQUEST;
        $params = array_merge($_GET, $_POST);
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

        $l = new View($p_index);
        $l->params = $params;
        $layout = $l->__toString();
        $apijs_loaded = false;
        if ($layout != false) {

            //$apijs_loaded = $this->app->template->get_apijs_url() . '?id=' . CONTENT_ID;
            //$apijs_loaded = $this->app->template->get_apijs_url();
            // $apijs_settings_loaded = $this->app->template->get_apijs_settings_url() . '?id=' . CONTENT_ID . '&category_id=' . CATEGORY_ID;
            //  $apijs_settings_loaded = $this->app->template->get_apijs_settings_url();
            $default_css_url = $this->app->template->get_default_system_ui_css_url();


            // $is_admin = app()->user_manager->is_admin();
            // $default_css = '<link rel="stylesheet" href="' . mw_includes_url() . 'default.css?v=' . MW_VERSION . '" type="text/css" />';
            $default_css = '<link rel="stylesheet" href="' . $default_css_url . '" type="text/css" />';


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
                $p = new View($p);
                $p->params = $params;
                $layout = $p->__toString();
                return response($layout);

            }
        } elseif (is_file($p)) {
            $p = new View($p);
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

        if (!$standalone_edit and $tool == 'wysiwyg') {
            if (isset($page['render_file'])) {
                if (!isset($page['layout_file'])) {
                    $page['layout_file'] = str_replace(template_dir(), '', $page['render_file']);
                }


                event_trigger('mw.front', $page);
                $l = new View($page['render_file']);
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

        /* if (!stristr($layout, $apijs_loaded)) {
             $rep = 0;

             $default_css = $default_css . "\r\n" . '<script src="' . $apijs_settings_loaded . '"></script>' . "\r\n";
             $default_css = $default_css . "\r\n" . '<script src="' . $apijs_loaded . '"></script>' . "\r\n";
             $layout = str_ireplace('<head>', '<head>' . $default_css, $layout, $rep);
         }*/

        $layout = str_ireplace('<head>', '<head>' . $default_css, $layout, $rep);


        $layout = $this->app->template->append_api_js_to_layout($layout);
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
        $layout = mw()->template->process_stacks($layout);


        $layout = $this->app->parser->process($layout, $options = false);

        $layout = mw()->template->add_csrf_token_meta_tags($layout);

        $layout = execute_document_ready($layout);

        $layout = str_replace('{head}', '', $layout);

        $layout = str_replace('{content}', '', $layout);
        return response($layout);


    }

}
