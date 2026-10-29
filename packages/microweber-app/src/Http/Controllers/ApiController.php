<?php

namespace MicroweberPackages\App\Http\Controllers;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use MicroweberPackages\App\Http\Middleware\ApiAuth;
use MicroweberPackages\App\Http\Middleware\SameSiteRefererMiddleware;
use MicroweberPackages\App\Managers\Helpers\VerifyCsrfTokenHelper;
use MicroweberPackages\Security\XSSClean;
use MicroweberPackages\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use MicroweberPackages\Url\Facades\UrlManager;
use MicroweberPackages\Database\Facades\DatabaseManager;
use MicroweberPackages\Format\Facades\Format;


/**
 * Module renderer + retired legacy API dispatcher.
 *
 * The dynamic api_expose()/api_bind() dispatch this controller used to power
 * (via the greedy `api/{all}` catch-all → {@see api()}) has been RETIRED: it
 * resolved a class/function + method straight from the request URL and invoked
 * it, and its `class_is_already_here` branch did so without consulting the
 * $api_exposed allow list, letting an unauthenticated request call an arbitrary
 * public method on any already-loaded class (CWE-306 — e.g.
 * UserManager::make_logged → administrator-session takeover). Every real
 * endpoint is now an explicit route + controller + FormRequest (see the
 * App/Module/User routes/api.php); the api_expose()/api_bind() registry is
 * empty. {@see api()} now only delegates to the module renderer and otherwise
 * returns 403. Do NOT route new endpoints here — define a real route +
 * controller instead. The still-live surface is the module renderer
 * ({@see module()}, served by its own `/module/*` routes too).
 */
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

        if (!defined('MW_API_CALL')) {
            define('MW_API_CALL', true);
        }

        $set_constants = true;
        if (!mw_is_installed()) {
            $set_constants = false;
        }

        if ($api_function == false) {
            $api_function_full = UrlManager::string();
            $api_function_full = Format::replace_once('api_html', '', $api_function_full);
            $api_function_full = Format::replace_once('api/api', 'api', $api_function_full);
            $api_function_full = Format::replace_once('api', '', $api_function_full);
            $api_function_full = trim($api_function_full, '/');
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

        if ($api_function == false) {
            $api_function = UrlManager::segment(1);
        }

        if (!defined('MW_API_FUNCTION_CALL')) {
            define('MW_API_FUNCTION_CALL', $api_function);
        }

        // SECURITY / DEPRECATION — the legacy dynamic dispatch has been retired.
        //
        // This catch-all once resolved a class/function + method straight from
        // the request URL and invoked it (the api_expose()/api_bind() registry
        // served through `api/{all}`). The `class_is_already_here` branch called
        // the method WITHOUT consulting the $api_exposed allow list, so an
        // unauthenticated POST could invoke an arbitrary public method on any
        // already-loaded class — e.g. UserManager::make_logged(['id' => 1]) ->
        // Auth::loginUsingId(1), a full administrator-session takeover from one
        // anonymous request (CWE-306, Missing Authentication for Critical
        // Function). CSRF was no barrier: the token is readable by any visitor.
        //
        // Every real endpoint is now an explicit route + controller + FormRequest
        // (see App/Module/User routes/api.php); the api_expose()/api_bind()
        // registry is empty. The only thing still served here is the module
        // renderer, which keeps its own `/module/*` routes too.
        $is_module_call = ($api_function === 'module');
        if (!$is_module_call and is_string($api_function_full)) {
            $normalized_full = ltrim(str_replace('\\', '/', $api_function_full), '/');
            if ($normalized_full === 'module' or strpos($normalized_full, 'module/') === 0) {
                $is_module_call = true;
            }
        }
        if ($is_module_call) {
            return $this->module();
        }

        return response('The api function is not defined in the allowed functions list', 403);
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
            $is_ajax = UrlManager::is_ajax();
            if (!app()->user_manager->session_id() and $is_ajax == false and !defined('MW_SESS_STARTED')) {
                define('MW_SESS_STARTED', true);
                //session_start();
            }
            $editmode_sess = app()->user_manager->session_get('editmode');
            if ($editmode_sess == true and !defined('IN_EDIT')) {
                define('IN_EDIT', true);
            }
        }




        $requestUri = '';
        if(is_ajax()){


        $httpReferer = request()->header('referer');
        if ($httpReferer) {
            $requestUri = $httpReferer;
              //  $requestUri = str_replace(site_url(), '', $requestUri);


            $request = new \Illuminate\Http\Request();
            $request->server->replace(request()->server->all());
            $request->server->set('REQUEST_URI', $requestUri);

                URL::setRequest($request);
                $currentUrl = URL::current();


            }
        }

         $request_data = request()->all();

        // sanitize attributes
        if($request_data){
            $request_data_new = [];

            $xssClean = new XSSClean();

            foreach ($request_data as $k=>$v){
                if(is_string($v)) {
                    $v = str_replace('<', '-', $v);
                    $v = str_replace('>', '-', $v);
                    $v = str_replace('"', '-', $v);
                    $v = str_replace("'", '-', $v);
                }
                if(is_array($v)) {
                    $v = $xssClean->cleanArray($v);
                } else {
                    $v = $xssClean->clean($v);
                }

                if(is_string($k)){
                    $k = str_replace('<', '-', $k);
                    $k = str_replace('>', '-', $k);
                    $k = str_replace('"', '-', $k);
                    $k = str_replace("'", '-', $k);

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
                $request_data['id'] = UrlManager::slug($request_data['data-module-name'] . '-' . date('YmdHis'));
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
        } else {
            $from_url = request()->header('referer');
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

            $content_id = UrlManager::param('content_id', false, $from_url2);

            if ($content_id == false) {
                $content_id = UrlManager::param('editpage', false, $from_url2);
            }
            if ($content_id == false) {
                $content_id = UrlManager::param('editpost', false, $from_url2);
            }
            if ($content_id == false) {
                $is_current = UrlManager::param('is-current', false, $from_url2);
                if ($is_current) {
                    $content_id = UrlManager::param('content-id', false, $from_url2);
                } else {
                    $content_id = UrlManager::param('mw-adm-content-id', false, $from_url2);
                }
            }

            if ($content_id == false) {
                $action_test = UrlManager::param('action', false, $from_url2);

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
                if (trim($url) == '' or trim($url) == UrlManager::site()) {

                    //var_dump($from_url);
                    //$page = $this->app->content_manager->get_by_url($url);
                    $page = $this->app->content_manager->homepage();

                    if (!defined('IS_HOME')) {
                        define('IS_HOME', true);
                    }

                    if (isset($from_url2)) {
                        $mw_quick_edit = UrlManager::param('mw_quick_edit', false, $from_url2);

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
            $url = UrlManager::string();
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
            $u2 = UrlManager::site();
            $u1 = str_replace($u2, '', $url);

            $this->render_this_url = $u1;
            $this->isolate_by_html_id = $custom_display_id;
            return $this->frontend();


        }


        $url_last = false;
        if (!isset($request_data['module'])) {
            $url = UrlManager::string(0);
            if ($url == __FUNCTION__) {
                $url = UrlManager::string(0);
            }

            /*
            $is_ajax = UrlManager::is_ajax();

            if ($is_ajax == true) {
            $url = UrlManager::string(true);
            }*/

            $url = Format::replace_once('module/', '', $url);
            $url = Format::replace_once('module_api/', '', $url);
            $url = Format::replace_once('m/', '', $url);
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

        $module_info = UrlManager::param('module_info', true);


        if ($module_info and isset($request_data['module'])) {
            $request_data['module'] = sanitize_path($request_data['module']);
            $try_config_file = modules_path() . '' . $request_data['module'] . '_config.php';
            $try_config_file = normalize_path($try_config_file, false);
            if (is_file($try_config_file)) {
                include $try_config_file;

                if (!isset($config) or !is_array($config)) {
                    return false;
                }

                if (!isset($config['icon']) or $config['icon'] == false) {
                    $config['icon'] = modules_path() . '' . $request_data['module'] . '.png';
                    $config['icon'] = UrlManager::link_to_file($config['icon']);
                }
                echo json_encode($config);

                return;
            }
        }


        $admin = UrlManager::param('admin', true);

        $mod_to_edit = UrlManager::param('module_to_edit', true);
        $embed = UrlManager::param('embed', true);

        $mod_iframe = false;
        if ($mod_to_edit != false) {
            $mod_to_edit = str_ireplace('_mw_slash_replace_', '/', $mod_to_edit);
            $mod_iframe = true;
        }

        $data = $request_data;

        if ((request()->isMethod('post'))) {
            $data = request()->all();
        } else {
            $url = UrlManager::segment();

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



        $opts = array();
        if ($request_data) {
            $opts = $request_data;
        }

        $as_element = false;
        if (isset($opts['class']) and is_string($opts['class']) and strstr($opts['class'], 'module-as-element')) {
            $as_element = true;
        }


        if (isset($opts['as_element']) and $opts['as_element']) {
            $as_element = true;
        }
        if ($as_element) {
            $opts['module_as_element'] = true;
            $opts['populate_module_ids_in_elements'] = true;
            $opts['no_wrap'] = true;
        }


        if ($mod_n == 'element-from-template' && isset($data['template'])) {
            $t = sanitize_path($data['template']);
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
            $t = sanitize_path($data['template']);
            $possible_layout = templates_dir() . $t;
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
            $data = xss_clean($data);
            foreach ($data as $k => $v) {
                $k = $this->app->module_manager->format_attr($k);

                if ($k != 'ondrop') {
                    if ($k == 'id') {
                        $has_id = true;
                    }

                    if (is_array($v)) {
                        $v1 = Format::array_to_base64($v);
                        $tags .= "{$k}=\"$v1\" ";
                    } else {
                        $v = $this->app->module_manager->format_attr($v);

                        $tags .= "{$k}=\"$v\" ";
                    }
                }
            }
        }

        if ($has_id == false) {
//            if (defined('MW_MODULE_ONDROP')) {
//                $mod_n = UrlManager::slug($mod_n) . '-' . date("YmdHis").unquid();
//                $tags .= "id=\"$mod_n\" ";
//            }
            //  $mod_n = UrlManager::slug($mod_n) . '-' . date("YmdHis");
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

        $httpReferer = request()->header('referer');
        if ($httpReferer) {
            $get_arr_from_ref = $httpReferer;
            if (strstr($get_arr_from_ref, UrlManager::site())) {
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

       /* if ($embed != false) {
            $p_index = mw_includes_path() . 'api/index.php';
            $p_index = normalize_path($p_index, false);
            $l = new View($p_index);
            $layout = $l->__toString();
            $res = str_replace('{content}', $res, $layout);
        }*/

        $aj = UrlManager::is_ajax();

    /*    if ((isset($request_data['live_edit']) or isset($request_data['admin'])) and $aj == false) {
            $p_index = mw_includes_path() . DS . 'toolbar' . DS . 'editor_tools' . DS . 'module_settings' . DS . 'index.php';
            $p_index = normalize_path($p_index, false);
            $l = new View($p_index);
            $l->params = $data;
            $layout = $l->__toString();
            $res = str_replace('{content}', $res, $layout);
            $res = $this->app->parser->process($res, $options = false);
        }*/

        $res = app()->template_manager->process_stacks($res);

        $res = execute_document_ready($res);
        if (!defined('MW_NO_OUTPUT')) {
            $res = UrlManager::replace_site_url_back($res);
            return response($res);

            // echo $res;
        }

        if ($url_last == __FUNCTION__) {
            return;
        }
//        if (function_exists($url_last)) {
//            $this->api($url_last);
//        } elseif (isset($url_prev) and function_exists($url_prev)) {
//            $this->api($url_last);
//        } elseif (class_exists($url_last, false)) {
//            $this->api($url_last);
//        } elseif (isset($url_prev) and class_exists($url_prev, false)) {
//            $this->api($url_prev);
//        }

        return;
    }


}
