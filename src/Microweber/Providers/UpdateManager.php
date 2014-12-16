<?php
namespace Microweber\Providers;


use Microweber\Utils\Http;


if (defined("INI_SYSTEM_CHECK_DISABLED") == false) {
    define("INI_SYSTEM_CHECK_DISABLED", ini_get('disable_functions'));
}

class UpdateManager
{

    public $app;
    public $skip_cache = false;
    private $remote_api_url = 'http://api.microweber.com/service/update/';
    private $remote_url = 'http://api.microweber.com/service/update/';
    private $temp_dir = false;

    function __construct($app = null)
    {

        if (!is_object($this->app)) {

            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }

        }


        if (function_exists('mw_cache_path')) {
            $this->temp_dir = mw_cache_path() . 'updates_temp' . DIRECTORY_SEPARATOR;
        } else {
            $this->temp_dir = __DIR__ . DIRECTORY_SEPARATOR . 'cache/updates_temp' . DIRECTORY_SEPARATOR;
        }

        if (!is_dir($this->temp_dir)) {
            mkdir_recursive($this->temp_dir);
        }
    }

    function browse()
    {
        $data = $this->collect_local_data();


        $result = $this->call('browse', $data);
        return $result;
    }

    function get_modules()
    {
        $data = $this->collect_local_data();
        $data['add_new'] = true;
        $result = $this->call('get_modules', $data);
        return $result;
    }


    public function http()
    {
        return new \Microweber\Utils\Http();
    }

    private function collect_local_data()
    {
        $data = array();
        $data['mw_version'] = MW_VERSION;
        $data['mw_update_check_site'] = $this->app->url_manager->site();

        $t = mw()->template->site_templates();
        $data['templates'] = $t;
        //$t = $this->app->modules->scan_for_modules("skip_cache=1");
        $t = $this->app->modules->get("ui=any&no_limit=true");
        $data['modules'] = $t;
        $data['module_templates'] = array();
        if (is_array($t)) {
            foreach ($t as $value) {
                if (isset($value['module'])) {
                    $module_templates = $this->app->modules->templates($value['module']);
                    $mod_tpls = array();
                    if (is_array($module_templates)) {
                        foreach ($module_templates as $key1 => $value1) {

                            if (isset($value1['filename'])) {
                                $options = array();
                                if ($this->skip_cache) {
                                    $options['no_cache'] = 1;
                                }
                                $options['for_modules'] = 1;
                                $options['filename'] = $value1['filename'];
                                $module_templates_for_this = $this->app->layouts_manager->scan($options);
                                if (isset($module_templates_for_this[0]) and is_array($module_templates_for_this[0])) {
                                    $mod_tpls[$key1] = $module_templates_for_this[0];
                                }

                            }
                        }
                        if (!empty($mod_tpls)) {

                            $data['module_templates'][$value['module']] = $mod_tpls;
                        }
                    }
                }
            }
        }

        if ($this->skip_cache) {
            $t = $this->app->modules->scan_for_elements("skip_cache=1");
        } else {
            $t = $this->app->modules->scan_for_elements();
        }


        $data['elements'] = $t;


        return $data;
    }

    function get_templates()
    {
        $data = $this->collect_local_data();

        $result = $this->call('get_templates', $data);
        return $result;
    }

    function marketplace_link($params = false)
    {

        $url_resp = $this->call('market_link', $params);

        if ($url_resp != false) {
            $url = json_decode($url_resp, 1);
            if (isset($url['url'])) {
                return $url['url'];
            }
        }
    }

    function marketplace_admin_link($params = false)
    {
        $params = http_build_query($params);

        return admin_url('view:admin__modules__market?' . $params);
    }

    function install_market_item($params)
    {
        if (defined('MW_API_CALL')) {
            $to_trash = true;
            $adm = $this->app->user_manager->is_admin();
            if ($adm == false) {
                return array('error' => 'You must be admin to install from Marketplace!');
            }
        }
        if (!ini_get('safe_mode')) {
            if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'ini_set')) {
                ini_set("memory_limit", "160M");
                ini_set("set_time_limit", 0);
            }
            if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'set_time_limit')) {
                set_time_limit(0);
            }
        }

        $url = $this->remote_url . 'api/dl_file_api';

        $dl_get = $this->call('get_market_dl_link', $params);

        if ($dl_get != false and is_string($dl_get)) {
            $dl_get = json_decode($dl_get, true);
            if (isset($dl_get['url'])) {
                return $this->install_from_market($dl_get);
            }
        } else {
            if (isset($dl_get['url'])) {
                return $this->install_from_market($dl_get);
            }
        }


    }

    function apply_updates($params)
    {
        error_reporting(E_ERROR);
        $ret = array();
        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'ini_set')) {
            ini_set("set_time_limit", 0);
        }

        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'set_time_limit')) {
            set_time_limit(0);
        }

        $to_be_unzipped = array();
        $a = $this->app->user_manager->is_admin();
        if ($a == false) {
            mw_error('Must be admin!');
        }
        only_admin_access();


        $updates = $this->check();

        if (empty($updates)) {
            return false;
        }


        $params = parse_params($params);

        $update_api = $this;
        $res = array();
        $upd_params = array();
        if (is_array($params)) {
            foreach ($params as $param_k => $param) {
                if ($param_k == 'mw_version') {
                    $ret[] = $update_api->install_version($param);
                }

                if (is_array($param) and !empty($param)) {
                    if ($param_k == 'modules') {
                        if (!empty($updates) and isset($updates['modules']) and !empty($updates['modules'])) {
                            foreach ($param as $module) {
                                foreach ($updates['modules'] as $update) {
                                    if (isset($update['module']) or $update['item_type'] == 'module') {
                                        $module_market = $update['module'];
                                        $module = str_replace('\\', '/', $module);
                                        $module_market = str_replace('\\', '/', $module_market);
                                        if ($module == $module_market) {
                                            $ret[] = $this->install_from_market($update);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($param_k == 'templates') {
                        if (!empty($updates) and isset($updates['templates']) and !empty($updates['templates'])) {
                            foreach ($param as $module) {
                                foreach ($updates['templates'] as $update) {
                                    if (isset($update['dir_name']) or $update['item_type'] == 'template') {
                                        $module_market = $update['dir_name'];
                                        $module = str_replace('\\', '/', $module);
                                        $module_market = str_replace('\\', '/', $module_market);
                                        if ($module == $module_market) {
                                            $ret[] = $this->install_from_market($update);
                                        }
                                    }
                                }
                            }
                        }
                    }


                    if ($param_k == 'module_templates') {
                        if (!empty($updates) and isset($updates['module_templates']) and !empty($updates['module_templates'])) {
                            foreach ($param as $module) {
                                foreach ($updates['module_templates'] as $update) {
                                    if (isset($update['item_type']) and $update['item_type'] == 'module_template') {
                                        if (isset($update['module_skin_id']) and $update['module_skin_id'] == $module) {
                                            $ret[] = $this->install_from_market($update);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ($param_k == 'elements') {
                        if (!empty($updates) and isset($updates['elements']) and !empty($updates['elements'])) {
                            foreach ($param as $module) {

                                foreach ($updates['elements'] as $update) {
                                    if (isset($update['item_type']) and $update['item_type'] == 'element') {
                                        if (isset($update['element_id']) and $update['element_id'] == $module) {


                                            $ret[] = $this->install_from_market($update);
                                        }
                                    }
                                }
                            }
                        }
                    }


                }
            }

        }


        if (is_array($ret) and !empty($ret)) {
            $this->post_update();
            $this->app->notifications_manager->delete_for_module('updates');
        }
        return $ret;

    }


    private $updates_queue_cache_id = 'apply_updates_queue';
    private $updates_queue_cache_group = 'updates_queue';

    public function set_updates_queue($params = false)
    {
        $params = parse_params($params);

        $a = $this->app->user_manager->is_admin();
        if ($a == false) {
            mw_error('Must be admin!');
        }
        $queue = $params;

        $c_id = $this->updates_queue_cache_id;
        $cache_group = $this->updates_queue_cache_group;

        $cache_content = $this->app->cache_manager->get($c_id, $cache_group);
        if (($cache_content) != false) {
            $work = $cache_content;
        } else {
            $work = $params;
            $this->app->cache_manager->save($work, $c_id, $cache_group);
        }
    }

    public function apply_updates_queue()
    {
        $a = $this->app->user_manager->is_admin();
        if ($a == false) {
            mw_error('Must be admin!');
        }
        $c_id = $this->updates_queue_cache_id;
        $cache_group = $this->updates_queue_cache_group;
        $cache_content = $this->app->cache_manager->get($c_id, $cache_group);

        if (!empty($cache_content)) {
            $work = $cache_content;

            if (is_array($work) and !empty($work)) {
                foreach ($work as $k => $items) {
                    if (is_array($items) and !empty($items)) {
                        foreach ($items as $ik => $item) {
                            $msg = '';
                            if ($k == 'mw_version') {
                                $msg .= "Installing Core Update..." . "\n";
                            } elseif ($k == 'modules') {
                                $msg .= "Installing module..." . "\n";
                            } elseif ($k == 'templates') {
                                $msg .= "Installing template..." . "\n";
                            } elseif ($k == 'module_templates') {
                                $msg .= "Installing module skin..." . "\n";
                            } else {
                                $msg .= "Installing..." . "\n";
                            }
                            $msg .= $item . "\n";

                            $queue = array($k => array(0 => $item));
                            $is_done = $this->apply_updates($queue);

                            $msg_log = $this->_log_msg(true);
                            if(!empty($msg_log)){
                                $msg .= implode("\n", $msg_log) . "\n";
                            }


                            if (isset($is_done[0])) {
                                if (isset($is_done[0]['success'])) {
                                    $msg .= $is_done[0]['success'] . "\n";
                                } elseif (isset($is_done[0]['warning'])) {
                                    $msg .= $is_done[0]['warning'] . "\n";
                                } elseif (isset($is_done[0]['message'])) {
                                    $msg .= $is_done[0]['message'] . "\n";
                                }

                            } else {
                                $msg .= "ERROR..." . "\n";
                                $msg .= print_r($is_done, true);
                            }
                            unset($work[$k][$ik]);
                            $this->app->cache_manager->save($work, $c_id, $cache_group);
                            return $msg;

                        }
                    } else {
                        unset($work[$k]);
                        $this->app->cache_manager->save($work, $c_id, $cache_group);
                        $msg = "Installed all " . $k . "\n";
                        return $msg;
                    }
                }
            } else {
                $this->app->cache_manager->save(false, $c_id, $cache_group);
            }
        } else {
            $this->app->cache_manager->save(false, $c_id, $cache_group);
        }
        return 'done';
    }


    function merge_composer($composer_patch_path)
    {

        $this->_log_msg('Merging composer files');

        $runner = new \Microweber\Utils\ComposerUpdate();
        $runner->merge($composer_patch_path);

    }

    function run_composer()
    {
        $runner = new \Microweber\Utils\ComposerUpdate();
        $runner->run();
    }

    function check($skip_cache = false)
    {


        $a = $this->app->user_manager->is_admin();
        if ($a == false) {
            return false;
        }

        if (!ini_get('safe_mode')) {
            if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'ini_set')) {
                ini_set("memory_limit", "160M");
                ini_set("set_time_limit", 0);
            }
            if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'set_time_limit')) {
                set_time_limit(0);
            }
        }
        $c_id = __FUNCTION__ . date("ymdh");
        if ($skip_cache == false) {
            $cache_content = $this->app->cache_manager->get($c_id, 'update/global');
            if (($cache_content) != false) {
                return $cache_content;
            }
        } else {
            $this->skip_cache = true;
            $this->app->cache_manager->delete('update/global');
        }


        $data = $this->collect_local_data();


        $result = $this->call('check_for_update', $data);


        $count = 0;
        if (isset($result['modules'])) {
            $count = $count + sizeof($result['modules']);
        }
        if (isset($result['module_templates'])) {
            $count = $count + sizeof($result['module_templates']);
        }
        if (isset($result['templates'])) {
            $count = $count + sizeof($result['templates']);
        }
        if (isset($result['core_update'])) {
            $count = $count + 1;
        }
        if (isset($result['elements'])) {
            $count = $count + sizeof($result['elements']);
        }

        if ($count > 0) {
            $notif = array();
            $notif['replace'] = true;
            $notif['module'] = "updates";
            $notif['rel_type'] = "update_check";
            $notif['rel_id'] = 'updates';
            $notif['title'] = "New updates are available";
            $notif['description'] = "There are $count new updates are available";
            $this->app->notifications_manager->save($notif);
        }
        if (is_array($result)) {
            $result['count'] = $count;
        }
        if ($result != false) {
            $this->app->cache_manager->save($result, $c_id, 'update/global');
        }


        return $result;
    }

    function install_version($new_version)
    {
        only_admin_access();
        $params = array();
        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'ini_set')) {

            ini_set("set_time_limit", 0);
        }
        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'set_time_limit')) {
            set_time_limit(0);
        }

        $params['core_update'] = $new_version;
        $params['mw_update_check_site'] = $this->app->url_manager->site();

        $result = $this->call('get_download_link', $params);

        if (isset($result["core_update"])) {

            $value = trim($result["core_update"]);
            $fname = basename($value);
            $dir_c = mw_cache_path() . 'update/downloads' . DS;
            if (!is_dir($dir_c)) {
                mkdir_recursive($dir_c);
            }
            $dl_file = $dir_c . $fname;
            if (!is_file($dl_file)) {

                $this->_log_msg('Downloading core update');

                $get = $this->app->url_manager->download($value, $post_params = false, $save_to_file = $dl_file);
            }
            if (is_file($dl_file)) {
                $unzip = new \Microweber\Utils\Unzip();
                $target_dir = MW_ROOTPATH;
                $this->_log_msg('Preparing to unzip core update');
                $result = $unzip->extract($dl_file, $target_dir, $preserve_filepath = TRUE);
                $this->_log_msg('Core update unzipped');
                $new_composer = $target_dir.'composer.json.merge';
                if(is_file($new_composer)){
                    $this->merge_composer($new_composer);
                }

                $this->post_update();
                return $result;
                // skip_cache
            }

        }

    }

    function post_update()
    {
        $this->_log_msg('Applying post update actions');

        $system_refresh = new \Microweber\Install\DbInstaller;
        $system_refresh->install_db();
        //$system_refresh->run();

        if (!ini_get('safe_mode')) {
            if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'ini_set')) {

                ini_set("set_time_limit", 0);
            }
            if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'set_time_limit')) {
                set_time_limit(0);
            }
        }
        mw()->cache_manager->delete('db');
        mw()->cache_manager->delete('update/global');
        mw()->cache_manager->delete('elements/global');

        mw()->cache_manager->delete('templates');
        mw()->cache_manager->delete('modules/global');
        mw()->cache_manager->clear();
        scan_for_modules();
        scan_for_elements();
        mw()->layouts_manager->scan();
        event_trigger('mw_db_init_default');
        event_trigger('mw_db_init_modules');
        event_trigger('mw_db_init');


    }

    private function install_from_market($item)
    {
        if (isset($item['url']) and !isset($item['download'])) {
            $item['download'] = $item['url'];
        } elseif (isset($item['download_url']) and !isset($item['download'])) {
            $item['download'] = $item['download_url'];
        }

        $download_target = false;
        if (isset($item['download']) and !isset($item['size'])) {
            $url = $item['download'];

            $download_target = $this->temp_dir . md5($url) . basename($url);
            $download_target_extract_lock = $this->temp_dir . md5($url) . basename($url) . '.unzip_lock';
            $this->_log_msg('Downloading from marketplace');

            if (!is_file($download_target)) {
                $dl = $this->http()->url($url)->download($download_target);
            }
        } else if (isset($item['download']) and isset($item['size'])) {
            $expected = intval($item['size']);

            $download_link = $item['download'];

            $ext = get_file_extension($download_link);

            if ($ext != 'zip') {
                return;
            }

            if ($download_link != false and $expected > 0) {
                $text = $download_link;
                $regex = '/\b((?:[\w\d]+\:\/\/)?(?:[\w\-\d]+\.)+[\w\-\d]+(?:\/[\w\-\d]+)*(?:\/|\.[\w\-\d]+)?(?:\?[\w\-\d]+\=[\w\-\d]+\&?)?(?:\#[\w\-\d]*)?)\b/';
                preg_match_all($regex, $text, $matches, PREG_SET_ORDER);
                foreach ($matches as $match) {
                    if (isset($match[0])) {
                        $url = $download_link;

                        $download_target = $this->temp_dir . basename($download_link);
                        $download_target_extract_lock = $this->temp_dir . basename($download_link) . '.unzip_lock';


                        $expectd_item_size = $item['size'];
                        if (!is_file($download_target) or filesize($download_target) != $item['size']) {

                            $dl = $this->http()->url($url)->download($download_target);
                            if ($dl == false) {
                                if (is_file($download_target) and filesize($download_target) != $item['size']) {
                                    $fs = filesize($download_target);
                                    return array('size' => $fs, 'expected_size' => $expected, 'try_again' => "true", 'warning' => "Only " . $fs . ' bytes downloaded of total ' . $expected);
                                }
                            }
                        }
                    }
                }

            }


        }


        if ($download_target != false and is_file($download_target)) {
            $where_to_unzip = MW_ROOTPATH;
            if (isset($item['item_type'])) {
                if ($item['item_type'] == 'module') {
                    $where_to_unzip = modules_path();
                } elseif ($item['item_type'] == 'module_template') {
                    $where_to_unzip = modules_path();
                } elseif ($item['item_type'] == 'template') {
                    $where_to_unzip = templates_path();
                } elseif ($item['item_type'] == 'element') {
                    $where_to_unzip = elements_path();
                }

                if (isset($item['install_path']) and $item['install_path'] != false) {
                    if ($item['item_type'] == 'module_template') {
                        $where_to_unzip = $where_to_unzip . DS . $item['install_path'] . DS . 'templates' . DS;
                    } else {
                        $where_to_unzip = $where_to_unzip . DS . $item['install_path'];

                    }

                }


                $where_to_unzip = str_replace('..', '', $where_to_unzip);
                $where_to_unzip = normalize_path($where_to_unzip, true);
                $this->_log_msg('Unzipping in '.$where_to_unzip);
                $unzip = new \Microweber\Utils\Unzip();
                $target_dir = $where_to_unzip;
                $result = $unzip->extract($download_target, $target_dir, $preserve_filepath = TRUE);
                $num_files = count($result);
                return array('files' => $result, 'location' => $where_to_unzip, 'success' => "Item is installed. {$num_files} files extracted in {$where_to_unzip}");

            }

        }

    }

    function install_module($module_name)
    {

        $params = array();
        $params['module'] = $module_name;
        $params['add_new'] = $module_name;

        $result = $this->call('get_download_link', $params);

        if (isset($result["modules"])) {
            foreach ($result["modules"] as $mod_k => $value) {

                $fname = basename($value);
                $dir_c = mw_cache_path() . 'downloads' . DS;
                if (!is_dir($dir_c)) {
                    mkdir_recursive($dir_c);
                }
                $dl_file = $dir_c . $fname;
                if (!is_file($dl_file)) {
                    $this->_log_msg('Downloading module' . $fname);

                    $get = $this->app->url_manager->download($value, $post_params = false, $save_to_file = $dl_file);
                }
                if (is_file($dl_file)) {
                    $unzip = new \Microweber\Utils\Unzip();
                    $this->_log_msg('Unziping module' . $fname);

                    $target_dir = MW_ROOTPATH;
                    $result = $unzip->extract($dl_file, $target_dir, $preserve_filepath = TRUE);
                }
            }
            $params = array();
            $params['skip_cache'] = true;

            $data = $this->app->modules->get($params);
            $this->app->cache_manager->delete('update/global');
            $this->app->cache_manager->delete('db');
            $this->app->cache_manager->delete('modules');


            $this->app->modules->scan_for_modules();


        }
        return $result;

    }

    function install_element($module_name)
    {

        $params = array();

        $params['element'] = $module_name;
        $result = $this->call('get_download_link', $params);
        if (isset($result["elements"])) {
            foreach ($result["elements"] as $mod_k => $value) {

                $fname = basename($value);
                $dir_c = mw_cache_path() . 'downloads' . DS;
                if (!is_dir($dir_c)) {
                    mkdir_recursive($dir_c);
                }

                $dl_file = $dir_c . $fname;
                if (!is_file($dl_file)) {
                    $get = $this->app->url_manager->download($value, $post_params = false, $save_to_file = $dl_file);
                }
                if (is_file($dl_file)) {
                    $unzip = new \Microweber\Utils\Unzip();
                    $target_dir = MW_ROOTPATH;

                    // $result = $unzip -> extract($dl_file, $target_dir, $preserve_filepath = TRUE);
                    // skip_cache
                }
            }
            $params = array();
            $params['skip_cache'] = true;

            $data = $this->app->modules->get($params);
            //d($data);

        }
        return $result;

    }

    function call($method = false, $post_params = false)
    {
        $cookie = mw_cache_path() . DIRECTORY_SEPARATOR . 'cookies' . DIRECTORY_SEPARATOR;
        if (!is_dir($cookie)) {
            mkdir_recursive($cookie);
        }
        $cookie_file = $cookie . 'cookie.txt';
        $requestUrl = $this->remote_url;

        if ($method != false) {
            //  $requestUrl = $requestUrl . 'api/mw_check';
        }


        $post_params['site_url'] = $this->app->url_manager->site();
        $post_params['api_function'] = $method;
        //d($method);
        if ($post_params != false and is_array($post_params)) {
            $curl_result = $this->http()->url($requestUrl)->post($post_params);

        } else {
            $curl_result = false;
        }
        if ($curl_result == '' or $curl_result == false) {
            return false;
        }
        $result = false;

        // d($curl_result);

//        if (is_ajax()) {
//            print $curl_result;
//        }
        if ($curl_result != false) {
            $result = json_decode($curl_result, 1);
        }
        return $result;
    }

    public function send_anonymous_server_data($params = false)
    {

        if ($params != false and is_string($params)) {
            $params = parse_params($params);
        }

        if ($params == false) {
            $params = array();
        }

        $params['site_url'] = $this->app->url_manager->site();
        $params['function_name'] = 'send_lang_form_to_microweber';


        $result = $this->call('send_anonymous_server_data', $params);
        return $result;
    }

    function http_build_query_for_curl($arrays, &$new = array(), $prefix = null)
    {

        if (is_object($arrays)) {
            $arrays = get_object_vars($arrays);
        }

        foreach ($arrays as $key => $value) {
            $k = isset($prefix) ? $prefix . '[' . $key . ']' : $key;
            if (is_array($value) OR is_object($value)) {
                $this->http_build_query_for_curl($value, $new, $k);
            } else {
                $new[$k] = $value;
            }
        }
        return $new;
    }

    public function validate_license($params = false)
    {
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            return;
        }
        $table = $this->app->modules->tables['system_licenses'];
        if ($table == false) {
            return;
        }

        $lic_ids = array();
        $licenses = $this->get_licenses($params);
        if (!empty($licenses)) {
            $result = $this->call('validate_licenses', $licenses);
            if (!empty($result)) {
                foreach ($result as $k => $v) {
                    foreach ($licenses as $license) {
                        if (isset($license['rel_type']) and $license['rel_type'] == $k) {
                            if (is_array($v) and isset($v['status'])) {
                                $license['status'] = $v['status'];
                                foreach ($license as $license_k => $license_v) {
                                    if (isset($v[$license_k])) {
                                        $license[$license_k] = $v[$license_k];
                                    }
                                }
                                $lic_ids[] = $this->save_license($license);
                            }
                        }
                    }
                }
            }
        }

        if (!empty($lic_ids)) {
            return array('updates' => $lic_ids, 'success' => "Licenses are checked");
        }
    }

    public function get_licenses($params = false)
    {
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            return;
        }
        $params2 = array();

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }


        $table = $this->app->modules->tables['system_licenses'];
        if ($table == false) {
            return;
        }


        $params['table'] = $table;
        $r = $this->app->database->get($params);

        return $r;
    }

    public function save_license($params)
    {
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            return;
        }
        $table = $this->app->modules->tables['system_licenses'];
        if ($table == false) {
            return;
        }

        if (!isset($params['id']) and isset($params['rel_type'])) {
            $update = array();
            $update['rel_type'] = $params['rel_type'];
            $update['one'] = true;
            $update['table'] = $table;
            $update = $this->app->database->get($update);
            if (isset($update['id'])) {
                $params['id'] = $update['id'];
            }
        }

        $r = $this->app->database->save($table, $params);
        if (isset($params['activate_on_save']) and $params['activate_on_save'] != false) {
            $this->validate_license('id=' . $r);
        }

        return array('id' => $r, 'success' => "License key saved");
    }

    private function install_from_remote($url)
    {
        $fname = basename($url);
        $dir_c = mw_cache_path() . 'downloads' . DS;
        if (!is_dir($dir_c)) {
            mkdir_recursive($dir_c);
        }
        $dl_file = $dir_c . $fname;
        if (!is_file($dl_file)) {
            $get = $this->app->url_manager->download($url, $post_params = false, $save_to_file = $dl_file);
        }
        if (is_file($dl_file)) {
            $unzip = new \Microweber\Utils\Unzip();
            $target_dir = MW_ROOTPATH;
            $result = $unzip->extract($dl_file, $target_dir, $preserve_filepath = TRUE);
            return $result;
        }
    }

    public $log_messages = array();

    private function _log_msg($msg)
    {
        if ($msg === true) {
            return $this->log_messages;
        } else {
            $this->log_messages[] = $msg;
        }
    }

}
