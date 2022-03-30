<?php

namespace MicroweberPackages\App\Managers;

use Illuminate\Support\Facades\Config;
use MicroweberPackages\Package\MicroweberComposerClient;

if (defined('INI_SYSTEM_CHECK_DISABLED') == false) {
    define('INI_SYSTEM_CHECK_DISABLED', ini_get('disable_functions'));
}

class UpdateManager
{
    public $app;
    public $skip_cache = false;
    private $remote_api_url = 'https://update.microweberapi.com/';
    private $remote_url = 'https://update.microweberapi.com/';
    private $temp_dir = false;

    public function __construct($app = null)
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

    /**
     * @deprecated 1.1.3
     */
    public function browse()
    {

        return;
        $data = $this->collect_local_data();

        $result = $this->call('browse', $data);

        return $result;
    }

    /**
     * @deprecated 1.1.3
     */
    public function get_modules()
    {
        return;

        $data = $this->collect_local_data();
        $data['add_new'] = true;
        $result = $this->call('get_modules', $data);

        return $result;
    }

    public function http()
    {
        return new \MicroweberPackages\Utils\Http\Http();
    }

    public function collect_local_data()
    {

        $data = array();
        $data['php_version'] = phpversion();
        $data['mw_version'] = MW_VERSION;
        $data['mw_update_check_site'] = $this->app->url_manager->site();
        $data['update_channel'] = \Config::get('microweber.update_channel');
        $data['last_update'] = \Config::get('microweber.updated_at');

        $t = site_templates();
        $data['templates'] = $t;
        $t = $this->app->module_manager->get('ui=any&no_limit=true');
        $modules = array();
        $data['module_templates'] = array();
        if (is_array($t)) {
            foreach ($t as $value) {
                if (isset($value['module']) and isset($value['version'])) {
                    //  $mod = array('module' => $value['module'], 'version' => $value['version']);
                    // $modules[] = $mod;
                    $value['dir_name'] = $value['module'];
                    $modules[] = $value;

                    $module_templates = $this->app->module_manager->templates($value['module']);
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
        $data['modules'] = $modules;

        if ($this->skip_cache) {
            $t = $this->app->module_manager->scan_for_elements('skip_cache=1');
        } else {
            $t = $this->app->module_manager->scan_for_elements();
        }
        $elements = array();
        if (is_array($t)) {
            foreach ($t as $value) {
                if (isset($value['module']) and isset($value['version'])) {
                    $mod = array('module' => $value['module'], 'version' => $value['version']);
                    $elements[] = $mod;
                }
            }
        }
        $data['elements'] = $elements;

        $composer_json = normalize_path(base_path() . DS . 'composer.json', false);
        if (is_file($composer_json)) {
            $data['check_composer_json_md5'] = md5(@file_get_contents($composer_json));
        }
        if (is_dir(base_path() . DS . 'vendor')) {
            $data['check_vendor_writable'] = @is_writable(base_path() . DS . 'vendor');
        }

        return $data;
    }

    public function get_updates_notifications($params = false)
    {
        if (is_string($params)) {
            $params = parse_params($params);
        }
        if (!is_array($params)) {
            $params = array();
        }
        $params['rel_type'] = 'update_check';
        $params['rel_id'] = 'updates';

        $new_version_notifications = mw()->notifications_manager->get($params);
        return $new_version_notifications;
    }


    public function marketplace_link($params = false)
    {
        if (!isset($params['marketplace_provider_id']) and isset(mw()->ui->marketplace_provider_id) and mw()->ui->marketplace_provider_id) {
            $params['marketplace_provider_id'] = mw()->ui->marketplace_provider_id;
        } elseif ($this->app->make('config')->get('microweber.marketplace_provider_id')) {
            $params['marketplace_provider_id'] = $this->app->make('config')->get('microweber.marketplace_provider_id');
        }

        if (!isset($params['marketplace_access_code']) and isset(mw()->ui->marketplace_access_code)) {
            $params['marketplace_access_code'] = mw()->ui->marketplace_access_code;
        }

        $url_resp = $this->call('market_link', $params);

        if ($url_resp != false) {
            $url = json_decode($url_resp, 1);
            if (isset($url['url'])) {
                return $url['url'];
            }
        }
    }

    public function marketplace_admin_link($params = false)
    {
        if (is_string($params)) {
            $params = parse_params($params);
        }

        $params = http_build_query($params);

        return admin_url('view:admin__modules__market?' . $params);
    }

    /**
     * @deprecated 1.1.3
     */
    public function install_market_item($params)
    {
        return;

        if (defined('MW_API_CALL')) {
            $to_trash = true;
            $adm = $this->app->user_manager->is_admin();
            if ($adm == false) {
                return array('error' => 'You must be admin to install from Marketplace!');
            }
        }
        $this->log_msg('Preparing');

        $this->_set_time_limit();


        $dl_get = $this->call('get_market_dl_link', $params);
        if (isset($params['market_key']) and trim($params['market_key']) != '') {
            $lic = array();
            $lic['local_key'] = $params['market_key'];
            $lic['activate_on_save'] = 1;
            $this->save_license($lic);
        }


        $res = array();

        if ($dl_get != false and is_string($dl_get)) {
            $dl_get = json_decode($dl_get, true);
        }

        if ($dl_get != false and isset($dl_get['url'])) {
            if (is_cli()) {
                $res = $this->install_from_market($dl_get);
            } else {
                $this->set_updates_queue(array('market_item' => $dl_get));
                $res = array();
                $res['message'] = 'Preparing installation';
                $res['update_queue_set'] = true;
            }
        } else {
            $res = array();
            $res['error'] = 'Error with installation';
        }
        //   $this->post_update();

        return $res;
    }

    /**
     * @deprecated 1.1.3
     */
    public function apply_updates($params)
    {
        return;
        error_reporting(E_ERROR);
        $ret = array();

        $this->_set_time_limit();


        $to_be_unzipped = array();
        if (defined('MW_API_CALL')) {
            must_have_access();
        }

        $updates = $this->check();

        if (empty($updates)) {
            return false;
        }


        $step = false;
        if (isset($params['step'])) {
            $step = intval($params['step']);
        }

        $params = parse_params($params);

        $update_api = $this;
        $res = array();
        $upd_params = array();
        if (is_array($params)) {
            foreach ($params as $param_k => $param) {
                if ($param_k == 'mw_version' || $param_k == 'core_update') {
                    $ret[] = $update_api->install_version($param, $step);
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
                    } else if ($param_k == 'templates') {
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
                    } else if ($param_k == 'module_templates') {
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
                    } else if ($param_k == 'elements') {
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
                    } else {

                        // ...

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

    /**
     * @deprecated 1.1.3
     */
    public function set_updates_queue($params = false)
    {

        return;
        $params = parse_params($params);

        $a = $this->app->user_manager->is_admin();
        if ($a == false) {
            mw_error('Must be admin!');
        }
        $queue = $params;

        $c_id = $this->updates_queue_cache_id;
        $cache_group = $this->updates_queue_cache_group;

        $work = $params;
        $this->app->cache_manager->save($work, $c_id, $cache_group);


        return array('message' => 'Preparing');


//        $cache_content = $this->app->cache_manager->get($c_id, $cache_group);
//        if (($cache_content) != false) {
//            $work = $cache_content;
//        } else {
//            $work = $params;
//            $this->app->cache_manager->save($work, $c_id, $cache_group);
//        }
    }

    /**
     * @deprecated 1.1.3
     */
    public function apply_updates_queue($params = false)
    {
        return;
        $a = $this->app->user_manager->is_admin();
        if ($a == false) {
            mw_error('Must be admin!');
        }
        $this->_set_time_limit();
        $this->log_msg('Preparing');

        $c_id = $this->updates_queue_cache_id;
        $cache_group = $this->updates_queue_cache_group;
        $cache_content = $this->app->cache_manager->get($c_id, $cache_group);

        if ($params) {
            $params = parse_params($params);
        }

        $step = false;
        if (isset($params['step'])) {
            $step = intval($params['step']);
        }

        if (!empty($cache_content)) {
            $work = $cache_content;

            if (is_array($work) and !empty($work)) {
                foreach ($work as $k => $items) {
                    if ($k == 'market_item') {
                        $is_done = $this->install_from_market($items);
                        $this->post_update();

                        $this->app->cache_manager->save(false, $c_id, $cache_group);

                    } else {
                        if (is_array($items) and !empty($items)) {
                            foreach ($items as $ik => $item) {
                                $msg = '';
                                if ($k == 'mw_version') {
                                    $msg .= 'Installing Core Update...' . "\n";
                                } elseif ($k == 'modules') {
                                    $msg .= 'Installing module...' . "\n";
                                } elseif ($k == 'templates') {
                                    $msg .= 'Installing template...' . "\n";
                                } elseif ($k == 'module_templates') {
                                    $msg .= 'Installing module skin...' . "\n";
                                } else {
                                    $msg .= 'Installing...' . "\n";
                                }
                                $msg .= $item . "\n";
                                $this->log_msg($msg);

                                $queue = array($k => array(0 => $item));

                                if ($k == 'mw_version') {
                                    $is_done = $this->install_version($item, $step);
                                    if ($step and $step < $this->_install_steps_num) {
                                        return array('message' => $msg, 'try_again' => 'true');
                                    }
                                } else {
                                    $is_done = $this->apply_updates($queue);

                                }


                                $msg_log = $this->log_msg(true);
                                if (!empty($msg_log)) {
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
                                    $msg .= 'ERROR...' . "\n";
                                    $msg .= print_r($is_done, true);


                                }
                                unset($work[$k][$ik]);
                                $this->app->cache_manager->save($work, $c_id, $cache_group);
                                return array('message' => $msg);

                                return $msg;
                            }
                        } else {
                            unset($work[$k]);

                            ///  $this->composer_run();
                            if ($k == 'mw_version') {

                                $is_done = $this->install_version($items, $step);
                                if ($step and $step < $this->_install_steps_num) {
                                    return array('message' => 'Installing Core Update...', 'try_again' => 'true');
                                }

                            }

                            $this->app->cache_manager->save($work, $c_id, $cache_group);
                            //  $msg = "Installed all " . $k . "\n";
                            //  $msg = "Installed " . "\n";
                            $msg = 'done';
                            return array('message' => $msg);

                            return $msg;
                        }
                    }

                }
            } else {
                $this->app->cache_manager->save(false, $c_id, $cache_group);
            }
        } else {
            $this->app->cache_manager->save(false, $c_id, $cache_group);
        }
        return array('message' => 'done');

        return 'done';
    }

    /**
     * @deprecated 1.1.3
     */
    public function check($skip_cache = false)
    {

        return; // TODO

        $update_channel = Config::get('microweber.update_channel');
        if ('disabled' == $update_channel) {
            return;
        }

        $this->_set_time_limit();
        //   $skip_cache = true;
        $c_id = __FUNCTION__ . date('ymdh');
        if ($skip_cache == false) {
            $cache_content = $this->app->cache_manager->get($c_id, 'update');
            if (($cache_content) != false) {
                return $cache_content;
            }
        } else {
            $this->skip_cache = true;
            $this->app->cache_manager->delete('update');
        }


        $data = $this->collect_local_data();


//        $update_check_folder_checksum_cache_id = 'update_check_folder_checksum___' . MW_VERSION;
//        $cache_checksum = $this->app->cache_manager->get($update_check_folder_checksum_cache_id, 'update');
//
//        if (!$cache_checksum) {
//            $checksum = array();
//            try {
//                if (!is_link(MW_ROOTPATH . 'vendor')) {
//                    $filesystem = new \MicroweberPackages\Utils\Files();
//                    $checksum['vendor'] = $filesystem->md5_dir(MW_ROOTPATH . 'vendor');
//                    $checksum['src'] = $filesystem->md5_dir(MW_PATH);
//                    $checksum['config'] = $filesystem->md5_dir(config_path());
//                    //$checksum['userfiles/modules/microweber'] = $filesystem->md5_dir(mw_includes_path());
//                    $checksum['userfiles/modules'] = $filesystem->md5_dir(modules_path());
//
//                    $checksum = array_map(
//                        function ($str) {
//                            $str = str_replace(MW_ROOTPATH, '', $str);
//                            return str_replace('\\', '/', $str);
//                        },
//                        $checksum
//                    );
//
//
//                }
//            } catch (\Exception $e) {
//
//            }
//            $cache_checksum = $checksum;
//            $this->app->cache_manager->save($checksum, $update_check_folder_checksum_cache_id, 'update');
//        }
//
//        if ($cache_checksum) {
//            $data['checksum_folders'] = base64_encode(json_encode($cache_checksum));
//        }


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
            $notif['module'] = 'updates';
            $notif['rel_type'] = 'update_check';
            $notif['rel_id'] = 'updates';
            $notif['title'] = 'New updates are available';
            $notif['description'] = "There are $count new updates are available";
            $notif['notification_data'] = @json_encode($result);


            $this->app->notifications_manager->save($notif);
        }
        if (is_array($result)) {
            $result['count'] = $count;
        }

        if ($result != false) {
            $this->app->cache_manager->save($result, $c_id, 'update');
        } else {
            $this->app->cache_manager->save($result, false, 'update');
        }

        return $result;
    }


    private $_install_steps_num = 2;

    /**
     * @deprecated 1.1.3
     */
    public function install_version($new_version, $on_step = false)
    {

        return;
        if (defined('MW_API_CALL')) {
            must_have_access();
        }


        $params = array();
        $this->_set_time_limit();

        $params['mw_version'] = MW_VERSION;

        $params['core_update'] = $new_version;
        $params['mw_update_check_site'] = $this->app->url_manager->site();

        $result = $this->call('get_download_link', $params);

        if (isset($result['core_update'])) {
            $value = trim($result['core_update']);
            $fname = basename($value);
            $dir_c = mw_cache_path() . 'update/downloads' . DS;
            if (!is_dir($dir_c)) {
                mkdir_recursive($dir_c);
            }
            $dl_file = $dir_c . $fname;
            if (!$on_step or $on_step == 1) {

                if (!is_file($dl_file)) {
                    $this->log_msg('Downloading core update');

                    $get = $this->app->url_manager->download($value, $post_params = false, $save_to_file = $dl_file);
                }
            }
            if (!$on_step or $on_step > 1) {

                if (is_file($dl_file)) {
                    $unzip = new \MicroweberPackages\Utils\Unzip();
                    $target_dir = MW_ROOTPATH;
                    $this->log_msg('Preparing to unzip core update');
                    $result = $unzip->extract($dl_file, $target_dir, $preserve_filepath = true);
                    $this->log_msg('Core update unzipped');
                    $new_composer = $target_dir . 'composer.json.merge';
                    if (is_file($new_composer)) {
                        //     $this->composer_merge($new_composer);
                    }

                    $this->post_update();

                    return $result;
                }
            }


        }
    }

    public function post_update($version = false)
    {

        if (mw_is_installed()) {

            $bootstrap_cached_folder = normalize_path(base_path('bootstrap/cache/'),true);
            rmdir_recursive($bootstrap_cached_folder);


            $this->log_msg('Applying post update actions');
            $system_refresh = new \MicroweberPackages\Install\DbInstaller();
            $system_refresh->createSchema();
            //$system_refresh->run();

            $this->_set_time_limit();

            $option = array();
            $option['option_value'] = MW_VERSION;
            $option['option_key'] = 'app_version';
            $option['option_group'] = 'website';
            save_option($option);


            mw()->cache_manager->delete('db');
            mw()->cache_manager->delete('update');
            mw()->cache_manager->delete('elements');

            mw()->cache_manager->delete('templates');
            mw()->cache_manager->delete('modules');
            mw()->cache_manager->clear();
          //  scan_for_modules(['no_cache'=>true]);
         //   scan_for_elements(['no_cache'=>true,'reload_modules'=>true,'cleanup_db'=>true]);
            scan_for_modules(['no_cache'=>true,'reload_modules'=>true,'cleanup_db'=>true]);
            scan_for_elements(['no_cache'=>true,'reload_modules'=>true,'cleanup_db'=>true]);

            mw()->layouts_manager->scan();
            mw()->template->clear_cached_custom_css();
            mw()->template->clear_cached_apijs_assets();
            event_trigger('mw_db_init_default');
            event_trigger('mw_db_init_modules');
            event_trigger('mw_db_init');

            if ($version != false) {
                Config::set('microweber.version', $version);
                Config::set('microweber.updated_at', @date("Y-m-d H:i:s"));
                Config::save('microweber');
            }
        }
        return true;
    }

    /**
     * @deprecated 1.1.3
     */
    private function install_from_market($item)
    {
        return;

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
            $this->log_msg('Downloading from marketplace');

            //if (!is_file($download_target)){
            $dl = $this->http()->url($url)->download($download_target);
            //}
        } elseif (isset($item['download']) and isset($item['size'])) {
            $expected = intval($item['size']);

            $download_link = $item['download'];
            $this->log_msg('Downloading from marketplace');

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
                                    $this->log_msg('Downloading failed....');

                                    return array('size' => $fs, 'expected_size' => $expected, 'try_again' => 'true', 'warning' => 'Only ' . $fs . ' bytes downloaded of total ' . $expected);
                                }
                            }
                        }
                    }
                }
            }
        }


        if ($download_target != false and is_file($download_target)) {
            $this->log_msg('Download complete');

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
                $this->log_msg('Item type: ' . $item['item_type']);

                if (isset($item['install_path']) and $item['install_path'] != false) {
                    $this->log_msg('Item folder name: ' . $item['install_path']);

                    if ($item['item_type'] == 'module_template') {
                        $where_to_unzip = $where_to_unzip . DS . $item['install_path'] . DS . 'templates' . DS;
                    } else {
                        $where_to_unzip = $where_to_unzip . DS . $item['install_path'];
                    }
                }

                $where_to_unzip = str_replace('..', '', $where_to_unzip);
                $where_to_unzip = normalize_path($where_to_unzip, true);
                $this->log_msg('Unzipping in ' . $where_to_unzip);
                $unzip = new \MicroweberPackages\Utils\Unzip();
                $target_dir = $where_to_unzip;
                $result = $unzip->extract($download_target, $target_dir, $preserve_filepath = true);
                $result = array_unique($result);
                $new_composer = $target_dir . 'composer.json';

                if (is_file($new_composer)) {
                    // $this->composer_merge($new_composer);
                }


                $num_files = count($result);
                $this->log_msg('Files extracted ' . $num_files);
                return array('files' => $result, 'location' => $where_to_unzip, 'success' => "Item is installed. {$num_files} files extracted in {$where_to_unzip}");
            }
        }
    }

    /**
     * @deprecated 1.1.3
     */
    public function install_module($module_name)
    {

        return;
        $params = array();
        $params['module'] = $module_name;
        $params['add_new'] = $module_name;

        $result = $this->call('get_download_link', $params);
        if (isset($result['modules'])) {
            foreach ($result['modules'] as $mod_k => $value) {
                $fname = basename($value);
                $dir_c = mw_cache_path() . 'downloads' . DS;
                if (!is_dir($dir_c)) {
                    mkdir_recursive($dir_c);
                }
                $dl_file = $dir_c . $fname;
                if (!is_file($dl_file)) {
                    $this->log_msg('Downloading module' . $fname);

                    $get = $this->app->url_manager->download($value, $post_params = false, $save_to_file = $dl_file);
                }
                if (is_file($dl_file)) {
                    $unzip = new \MicroweberPackages\Utils\Unzip();
                    $this->log_msg('Unziping module' . $fname);

                    $target_dir = MW_ROOTPATH;
                    $result = $unzip->extract($dl_file, $target_dir, $preserve_filepath = true);
                }
            }
            $params = array();
            $params['skip_cache'] = true;

            $data = $this->app->module_manager->get($params);
            $this->app->cache_manager->delete('update');
            $this->app->cache_manager->delete('db');
            $this->app->cache_manager->delete('modules');

            $this->app->module_manager->scan_for_modules();
        }

        return $result;
    }

    /**
     * @deprecated 1.1.3
     */
    public function install_element($module_name)
    {
        return;

        $params = array();

        $params['element'] = $module_name;
        $result = $this->call('get_download_link', $params);
        if (isset($result['elements'])) {
            foreach ($result['elements'] as $mod_k => $value) {
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
                    $unzip = new \MicroweberPackages\Utils\Unzip();
                    $target_dir = MW_ROOTPATH;

                    // $result = $unzip -> extract($dl_file, $target_dir, $preserve_filepath = TRUE);
                    // skip_cache
                }
            }
            $params = array();
            $params['skip_cache'] = true;

            // $data = $this->app->module_manager->get($params);
        }

        return $result;
    }

    public function call($method = false, $post_params = false)
    {
        $cookie = mw_cache_path() . DIRECTORY_SEPARATOR . 'cookies' . DIRECTORY_SEPARATOR;
        if (!is_dir($cookie)) {
            mkdir_recursive($cookie);
        }
        $cookie_file = $cookie . 'cookie.txt';
        $requestUrl = $this->remote_url;


        if ($post_params != false and is_array($post_params)) {
            $post_params['site_url'] = $this->app->url_manager->site();
            $post_params['api_function'] = $method;
            $post_params['mw_version'] = MW_VERSION;
            $post_params['php_version'] = phpversion();

            $curl = new \MicroweberPackages\Utils\Http\Http($this->app);
            $curl->set_url($requestUrl);
            $curl->set_timeout(20);

            $post = array();
            $post['base64js'] = base64_encode(@json_encode($post_params));
            $curl_result = $curl->post($post);

        } else {
            $curl_result = false;
        }

        if ($curl_result == '' or $curl_result == false) {
            return false;
        }
        $result = false;
        //  return;
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

        $params['function_name'] = 'send_lang_form_to_microweber';

        $result = $this->call('send_anonymous_server_data', $params);

        return $result;
    }

    public function http_build_query_for_curl($arrays, &$new = array(), $prefix = null)
    {
        if (is_object($arrays)) {
            $arrays = get_object_vars($arrays);
        }

        foreach ($arrays as $key => $value) {
            $k = isset($prefix) ? $prefix . '[' . $key . ']' : $key;
            if (is_array($value) or is_object($value)) {
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
        $table = $this->app->module_manager->tables['system_licenses'];
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
                        if (!isset($license['rel_type']) or (isset($license['rel_type']) and $license['rel_type'] == $k)) {
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
            clearcache();
            return array('updates' => $lic_ids, 'success' => 'Licenses are checked');
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

        $table = $this->app->module_manager->tables['system_licenses'];
        if ($table == false) {
            return;
        }

        $params['table'] = $table;
        $r = $this->app->database_manager->get($params);

        return $r;
    }


    public function delete_license($params)
    {
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            return;
        }
        $table = $this->app->module_manager->tables['system_licenses'];
        if ($table == false) {
            return;
        }

        if (isset($params['id'])) {
            $this->app->database_manager->delete_by_id('system_licenses', intval($params['id']));
            return array('id' => 0, 'success' => _e('License was deleted', true));
        }
    }

    public function save_license($params)
    {
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            return;
        }
        $table = $this->app->module_manager->tables['system_licenses'];
        if ($table == false) {
            return;
        }

        if (!isset($params['rel_type']) and isset($params['rel'])) {
            $params['rel_type'] = $params['rel'];
        }
        if (!isset($params['rel_type']) and isset($params['local_key'])) {
            $prefix = explode('--', $params['local_key']);
            if (!isset($prefix[1])) {
                $prefix = explode('::', $params['local_key']);
            }
            if (isset($prefix[1])) {
                $params['rel_type'] = $prefix[0];
            }
        }

        if (/*!isset($params['id']) and*/
        isset($params['rel_type'])
        ) {
            $update = array();
            $update['rel_type'] = $params['rel_type'];
            $update['one'] = true;
            $update['table'] = $table;
            $update = $this->app->database_manager->get($update);
            if (isset($update['id'])) {
                $params['id'] = $update['id'];
            }
        } elseif (/*!isset($params['id']) and */
        isset($params['local_key'])
        ) {
            $update = array();
            $update['local_key'] = $params['local_key'];
            $update['one'] = true;
            $update['table'] = $table;
            $update = $this->app->database_manager->get($update);
            if (isset($update['id'])) {
                $params['id'] = $update['id'];
            }
        }

        $r = $this->app->database_manager->save($table, $params);


        if (isset($params['activate_on_save']) and $params['activate_on_save'] != false) {
            $validation = $this->validate_license('id=' . $r);
            $validation_result = $this->get_licenses('single=true&id=' . $r);

            $is_valid = false;
            if(isset($validation_result['status']) and $validation_result['status'] == 'active'){
                    $is_valid = true;
            }

            if (!$is_valid) {
                return array('id' => $r,'is_invalid'=>true, 'warning' => _e('License key saved is not valid', true));

            } else {
                return array('id' => $r, 'success' => 'License key saved','is_active'=>true,);

            }
        }

        return array('id' => $r, 'success' => 'License key saved');
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
            $unzip = new \MicroweberPackages\Utils\Unzip();
            $target_dir = MW_ROOTPATH;
            $result = $unzip->extract($dl_file, $target_dir, $preserve_filepath = true);

            return $result;
        }
    }

    public $log_messages = array();
    public $log_filename = 'install_item_log.txt';

    public function get_log_file_url()
    {
        $log_file_url = userfiles_url() . $this->log_filename;
        return $log_file_url;
    }

    public function clear_log()
    {
        $log_file = userfiles_path() . $this->log_filename;
        @file_put_contents($log_file, '');
    }

    public function log_msg($msg)
    {
        if ($msg === true) {
            return $this->log_messages;
        } else {
            $this->log_messages[] = $msg;
        }

        if (is_array($msg)) {
            return;
        }

        $log_file = userfiles_path() . $this->log_filename;
        if (!is_file($log_file)) {
            @touch($log_file);
        }
        if (is_file($log_file)) {
            $json = array('date' => date('H:i:s'), 'msg' => $msg);
            $msg_l = strtolower($msg);
            if ($msg_l == 'done' or $msg_l == 'preparing' or $msg === true) {
                @file_put_contents($log_file, $msg . "\n");
            } else {
                //  @file_put_contents($log_file, $msg . "\n");
                @file_put_contents($log_file, $msg . "\n", FILE_APPEND);
            }
        }
    }

    public function composer_search_packages($params = false)
    {
        $params = parse_params($params);
        $cache_id = false;
        if (isset($params['cache']) and $params['cache']) {
            $cache_id = 'composer_search_packages-' . md5(json_encode($params));
        }

        if ($cache_id) {
            $results = cache_get($cache_id, 'composer', 600);

            if ($results) {
                if ($results == 'noresults') {
                    return array();
                }
                return $results;
            }
        }


        $keyword = '';
        $search_params = array();
        if (isset($params['keyword'])) {
            $params['require_name'] = $keyword;
        }

        $results = $this->composer_update->searchPackages($params);

        if (!$results) {
            $results = 'noresults';
        }

        if ($results) {
            cache_save($results, $cache_id, 'composer', 60);
        }
        if ($results == 'noresults') {
            return array();
        }

        return $results;
    }


    public function composer_install_package_by_name($params)
    {
       /* try {
            return $this->composer_update->installPackageByName($params);
        }catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace()
            );
        }*/
        $mw = new MicroweberComposerClient();
        return $mw->requestInstall($params);
    }

    public function composer_merge($composer_patch_path)
    {
        $this->log_msg('Merging composer files');

        $this->composer_update->merge($composer_patch_path);
    }

    public function composer_get_required()
    {
        return $this->composer_update->getRequire();
    }


    /**
     * @deprecated 1.1.3
     */
    public function composer_save_package($params)
    {
        return;

    }

    /**
     * @deprecated 1.1.3
     */
    public function composer_replace_vendor_from_cache()
    {
        return;
    }


    /**
     * @deprecated 1.1.3
     */
    public function composer_run()
    {

        return;

    }


    private function _set_time_limit()
    {
        if (!ini_get('safe_mode')) {
            if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'ini_set')) {
                ini_set('set_time_limit', 600);
                ini_set('memory_limit', '2548M');
            }
            if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'set_time_limit')) {
                set_time_limit(600);
            }
        }
    }


    private function _getComposerPath()
    {
        return dirname(storage_path()) . '/';
    }

    private function _getTargetPath()
    {
        return dirname(storage_path()) . '/';
    }
}
