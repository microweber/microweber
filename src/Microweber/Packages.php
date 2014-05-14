<?php
namespace Microweber;


if (defined("INI_SYSTEM_CHECK_DISABLED") == false) {
    define("INI_SYSTEM_CHECK_DISABLED", ini_get('disable_functions'));
}
if (function_exists('api_expose')) {
    api_expose('Packages/save_patch');
}
if (function_exists('api_expose')) {
    api_expose('Packages/apply_patch');
}

class Packages
{

    public $app;
    public $config_file;
    public $config_items = array();
    private $remote_api_url = 'http://api.microweber.com/service/deploy/';

    function __construct($app = null)
    {

        if (!is_object($this->app)) {

            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = Application::getInstance();
            }

        }
        if (!isset($this->app->config) or $this->app->config('composer_file') == false) {
            if (defined('MW_ROOTPATH')) {
                $this->config_file = MW_ROOTPATH . 'composer.json';
            }
        } else {
            $this->config_file = $this->app->config('composer_file');
        }


    }

    function save_patch($params)
    {
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }

        if (!isset($params['require_name']) and !isset($params['require_version'])) {
            return array('error' => "require_name and require_version parameters are missing");
        }
        $require_name = $params['require_name'];
        $require_version = $params['require_version'];

        if (!$require_name or !$require_version) {
            return array('error' => "require_name and require_version parameters must not be blank");

        }
        $patch_file = $this->get_patch_file_location();
        if ($patch_file != false and is_file($patch_file)) {
            $existing = @file_get_contents($patch_file);


            $conf_items = array();
            if ($existing != false) {
                $conf_items = json_decode($existing, true);
            }
            if (!isset($conf_items['require'])) {
                $conf_items['require'] = array();
            }
            $conf_items['require'][$require_name] = $require_version;

            /*foreach ($params as $key => $value) {

                $conf_items[$key] = $value;
            }*/
            $conf_items = json_encode($conf_items,JSON_UNESCAPED_SLASHES);
            $save = file_put_contents($patch_file, $conf_items);
            if ($save) {
                return array('success' => "composer.patch is saved");
            }


        }

    }

    function get_patch_file_location()
    {
        $conf = $this->config_file;
        $patch_file = false;
        if ($conf != false) {
            $patch_file = str_ireplace('.json', '.patch', $conf);
        }

        if ($patch_file != false) {
            if (!is_file($patch_file)) {
                @touch($patch_file);
            }
        }

        return $patch_file;
    }

    function apply_patch()
    {

        if (defined('MW_API_CALL')) {
            $is_admin = $this->app->user->is_admin();
            if ($is_admin == false) {
                return false;
            }
        }


        $patch_file = $this->get_patch_file_location();
        $composer_file = $this->config_file;
        $requestUrl = $this->remote_api_url;
        $download = false;

        if ($patch_file != false) {
            $patch_file_content = @file_get_contents($patch_file);
            $composer_file_content = @file_get_contents($composer_file);

            $patch_file_items = json_decode($patch_file_content, true);
            if (isset($patch_file_items['require'])) {
                $download = true;
            }

        }

        if ($download == true) {
            $curl = new \Microweber\Utils\Curl();
            $curl->setUrl($requestUrl);
             $curl->timeout = 20;
            $post_params = array();
            $post_params['site_url'] = $this->app->url->site();
            $post_params['composer_json'] =  $composer_file_content;
            $post_params['composer_patch'] =$patch_file_content;
d($post_params);
            $curl_result = $curl->post($post_params);

            d($curl_result);
        }

    }

    function get_composer_file_location()
    {
        $conf = $this->config_file;
        $patch_file = false;
        if ($conf != false) {
            $patch_file = str_ireplace('.json', '.patch', $conf);
        }

        if ($patch_file != false) {
            if (!is_file($patch_file)) {
                @touch($patch_file);
            }
        }

        return $patch_file;
    }

    function save_config($params)
    {

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }

        if (defined('MW_API_CALL')) {
            $is_admin = $this->app->user->is_admin();
            if ($is_admin == false) {
                return false;
            }
        }


        $conf = $this->config_file;
        if ($conf != false) {
            if (is_file($conf) and !is_writable($conf)) {
                return array('error' => "composer.json is not writable");
            } else {
                touch($conf);
            }
            if (is_file($conf)) {
                $existing = file_get_contents($conf);
                $conf_items = array();
                if ($existing != false) {
                    $conf_items = json_decode($existing, true);
                }
                foreach ($params as $key => $value) {

                    $conf_items[$key] = $value;
                }
                $conf_items = json_encode($conf_items,JSON_UNESCAPED_SLASHES);
                $save = file_put_contents($conf, $conf_items);
                if ($save) {
                    return array('success' => "composer.json is saved");
                }
            }
        }


    }

    public function get_required()
    {
        return $this->get_config('require');
    }

    public function get_config($key = false)
    {
        $adapters_dir = false;
        if (empty($this->config_items)) {
            $conf = $this->config_file;
            if ($conf != false and is_file($conf)) {
                $existing = file_get_contents($conf);
                $conf_items = array();
                if ($existing != false) {
                    $conf_items = json_decode($existing, true);
                }
                $this->config_items = $conf_items;
            }
        }

        if ($key == false) {
            return $this->config_items;
        } else {
            if (isset($this->config_items[$key])) {
                return $this->config_items[$key];
            }
        }


    }


}