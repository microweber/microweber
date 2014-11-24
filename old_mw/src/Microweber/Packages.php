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
    api_expose('Packages/prepare_patch');
}

class Packages
{

    public $app;
    public $config_file;
    public $temp_dir;
    public $config_items = array();
    public $config_items_patch = array();
    private $remote_api_url = 'http://patch.microweber.com/';

    function __construct($app = null)
    {

        if (!is_object($this->app)) {

            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }

        }
        if (!isset($this->app->config) or $this->app->config('composer_file') == false) {
            if (defined('MW_ROOTPATH')) {
                $this->config_file = MW_ROOTPATH . 'composer.json';
            }
        } else {
            $this->config_file = $this->app->config('composer_file');
        }


        if (defined('MW_CACHE_DIR')) {
            $this->temp_dir = MW_CACHE_DIR . 'packages_temp' . DIRECTORY_SEPARATOR;
        } else {
            $this->temp_dir = __DIR__ . DIRECTORY_SEPARATOR . 'packages_temp' . DIRECTORY_SEPARATOR;
        }

        if (!is_dir($this->temp_dir)) {
            mkdir($this->temp_dir);
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

            if (trim($require_version) != 'delete') {
                $conf_items['require'][$require_name] = $require_version;
            } else {
                if (isset($conf_items['require'][$require_name])) {
                    unset($conf_items['require'][$require_name]);
                }
            }
            /*foreach ($params as $key => $value) {

                $conf_items[$key] = $value;
            }*/

            if (defined('JSON_UNESCAPED_SLASHES')) {
                $conf_items = json_encode($conf_items, JSON_UNESCAPED_SLASHES);

            } else {
                $conf_items = str_replace('\/', '/', json_encode($conf_items));

            }


            $save = file_put_contents($patch_file, $conf_items);
            if ($save) {
                return array('success' => "composer.patch is saved");
            }


        }

    }

    function prepare_patch()
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


            $http = $this->app->http->set_url($requestUrl);

            $http->set_timeout(20);
//            $curl = new \Microweber\Utils\Curl();
//            $curl->setUrl($requestUrl);
//             $curl->timeout = 20;
            $post_params = array();
            $post_params['site_url'] = $this->app->url->site();
            $post_params['composer_json'] = $composer_file_content;
            $post_params['composer_patch'] = $patch_file_content;
            //   $post_params['debug'] = $patch_file_content;

            $curl_result = $http->post($post_params);
            if ($curl_result === false) {
                $curl_result = $http->post($post_params);
            }


            //d($post_params);
            // d($curl_result);
            if ($curl_result != false) {
                $curl_result = json_decode($curl_result, true);
                if ($curl_result != false and is_array($curl_result) and !empty($curl_result)) {
                    $item = $curl_result;
                    //  foreach ($curl_result as $item) {
                    if (isset($item['download']) and $item['download'] != false) {
                        $link = json_encode($item);
                        //$item['download'];
                        file_put_contents($this->temp_dir . 'download.json', $link);
                        return array('message' => "Patch is ready for download from " . $item['download']);
                    } else if (isset($item['error']) and $item['error'] != false) {
                        return array('message' => $item['error']);
                    } else if (isset($item['message']) and $item['message'] != false) {
                        return array('message' => $item['message']);
                    }

                    //}

                }
                return $curl_result;
            }
        }
    }

    public function get_patch_require()
    {
        return $this->get_patch_config('require');
    }

    public function get_patch_config($key = false)
    {

        if (empty($this->config_items_patch)) {
            $conf = $this->get_patch_file_location();
            if ($conf != false and is_file($conf)) {
                $existing = file_get_contents($conf);
                $conf_items = array();
                if ($existing != false) {
                    $conf_items = json_decode($existing, true);
                }
                $this->config_items_patch = $conf_items;
            }
        }

        if ($key == false) {
            return $this->config_items_patch;
        } else {
            if (isset($this->config_items_patch[$key])) {
                return $this->config_items_patch[$key];
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
        $download = $this->temp_dir . 'download.json';
        if (!is_file($download)) {
            return array('error' => 'file not found at ' . $download);
        }
        $download_links = file_get_contents($download);
        if ($download_links == false) {
            return false;
        }
        $download_links = json_decode($download_links, true);
        if (is_array($download_links)) {
             $item = $download_links;
            if (isset($item['download']) and isset($item['size'])) {
                $expected = intval($item['size']);

                $download_link = $item['download'];
                if ($download_link != false and $expected > 0) {
                    $text = $download_link;
                    $regex = '/\b((?:[\w\d]+\:\/\/)?(?:[\w\-\d]+\.)+[\w\-\d]+(?:\/[\w\-\d]+)*(?:\/|\.[\w\-\d]+)?(?:\?[\w\-\d]+\=[\w\-\d]+\&?)?(?:\#[\w\-\d]*)?)\b/';
                    preg_match_all($regex, $text, $matches, PREG_SET_ORDER);
                    foreach ($matches as $match) {
                        if (isset($match[0])) {
                            $url = $match[0];
                            $download_target = $this->temp_dir . basename($url);
                            $download_target_extract_lock = $this->temp_dir . basename($url) . '.unzip_lock';
                            $expectd_item_size = $item['size'];

                            if (!is_file($download_target) or filesize($download_target) != $item['size']) {

                                $dl = $this->app->http->url($url)->download($download_target);
                                if ($dl == false) {
                                    if (is_file($download_target) and filesize($download_target) != $item['size']) {
                                        $fs = filesize($download_target);

                                        return array('size' => $fs, 'expected_size' => $expected, 'try_again' => "true", 'warning' => "Only " . $fs . ' bytes downloaded of total ' . $expected);
                                    }
                                }
                                // d($dl);
                            } else if (!is_file($download_target_extract_lock) and is_file($download_target) or filesize($download_target) == $item['size']) {
                                @touch($download_target_extract_lock);
                                $unzip = new \Microweber\Utils\Unzip();
                                $target_dir = MW_ROOTPATH;
                                $result = $unzip->extract($download_target, $target_dir, $preserve_filepath = TRUE);

                                return array('result' => $result, 'success' => "Patch is completed");

                            }
                            // your link generator
                        }
                    }

                }


            }


        }


    }

    function download_patch_file($file)
    {

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
                $conf_items = json_encode($conf_items, JSON_UNESCAPED_SLASHES);
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