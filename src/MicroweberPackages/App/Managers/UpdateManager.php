<?php

namespace MicroweberPackages\App\Managers;

use Illuminate\Support\Facades\Config;
use MicroweberPackages\App\Models\SystemLicenses;
use MicroweberPackages\ComposerClient\Client;
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

    public function post_update($version = false)
    {
        if (mw_is_installed()) {

            $bootstrap_cached_folder = normalize_path(base_path('bootstrap/cache/'),true);
            rmdir_recursive($bootstrap_cached_folder);

            // Booting the template to register the migrations
            if (!defined('TEMPLATE_DIR')) {
                $the_active_site_template = $this->app->option_manager->get('current_template', 'template');
                if(!$the_active_site_template){
                    $the_active_site_template = Config::get('microweber.install_default_template');
                }
                if ($the_active_site_template) {
                    app()->content_manager->define_constants(['active_site_template' => $the_active_site_template]);
                }
            }
            if (defined('TEMPLATE_DIR')) {
                app()->template_manager->boot_template();
            }


            try {
                $this->log_msg('Applying post update actions');

                $system_refresh = new \MicroweberPackages\Install\DbInstaller();
                $system_refresh->createSchema();
            } catch (\Exception $e) {
                $this->log_msg('Error on DB migrations' . $e->getMessage());

             }



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

    public function consume_license($params = false)
    {
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            return ['status'=>'Not allowed action.', 'active'=> false];
        }
        $table = $this->app->module_manager->tables['system_licenses'];
        if ($table == false) {
            return ['status'=>'Not allowed action.', 'active'=> false];
        }

        $findLicense = SystemLicenses::where('id', $params['id'])->first();
        if ($findLicense == null) {
            return ['status'=>'License not found', 'active'=> false];
        }

        $composerClient = new Client();
        $consumeLicense = $composerClient->consumeLicense($findLicense->local_key);

        return $consumeLicense;
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

        if (!isset($params['local_key'])) {
            return;
        }

        $licenseLocalKey = trim($params['local_key']);

        $composerClient = new Client();
        $consumeLicense = $composerClient->consumeLicense($licenseLocalKey);
        if ($consumeLicense['valid']) { 

            $findSystemLicense = SystemLicenses::where('local_key', $licenseLocalKey)->first();
            if ($findSystemLicense !== null) {
                return array('is_invalid'=>true, 'warning' => _e('License key already exist', true));
            }

            if (!isset($consumeLicense['servers']) || empty($consumeLicense['servers'])) {
                return array('is_invalid'=>true, 'warning' => _e('License key is invalid', true));
            }

            $licenseServers = end($consumeLicense['servers']);
            $licenseDetails = $licenseServers['details'];

            $newSystemLicense = new SystemLicenses();
            $newSystemLicense->local_key = $licenseLocalKey;

            if (isset($licenseDetails['md5hash'])) {
                $newSystemLicense->local_key_hash = $licenseDetails['md5hash'];
            }

            if (isset($licenseDetails['registeredname'])) {
                $newSystemLicense->registered_name = $licenseDetails['registeredname'];
            }

         /*   if (isset($licenseDetails['registeredname'])) {
                $newSystemLicense->company_name = $licenseDetails['registeredname'];
            }*/

            if (isset($licenseDetails['validdomain'])) {
                $newSystemLicense->domains = $licenseDetails['validdomain'];
            }

            /*
            if (isset($licenseDetails['validip'])) {
                $newSystemLicense->ips = $licenseDetails['validip'];
            }*/

            if (isset($licenseDetails['status'])) {
                $newSystemLicense->status = $licenseDetails['status'];
            }

            if (!isset($licenseDetails['productid'])) {
                $newSystemLicense->product_id = $licenseDetails['productid'];
            }

            if (isset($licenseDetails['serviceid'])) {
                $newSystemLicense->service_id = $licenseDetails['serviceid'];
            }

            if (isset($licenseDetails['billingcycle'])) {
                $newSystemLicense->billing_cycle = $licenseDetails['billingcycle'];
            }

            if (isset($licenseDetails['regdate'])) {
                $newSystemLicense->reg_on = $licenseDetails['regdate'];
            }

            if (isset($licenseDetails['nextduedate'])) {
                $newSystemLicense->due_on = $licenseDetails['nextduedate'];
            }

            $newSystemLicense->save();

            return array('id' => $newSystemLicense->id, 'success' => 'License key saved', 'is_active'=>true);
        }

        return array('is_invalid'=>true, 'warning' => _e('License key is not valid', true));
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
