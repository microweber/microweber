<?php
namespace Microweber;


if (function_exists('api_expose')) {
    api_expose('Adapters/save_config');
}
use Pimple;

class Adapters
{

    public $app;
    /**
     * An instance of the cache adapter to use
     *
     * @var $container
     */
    public $container = array();
    public $config_items = array();
    public $adapters_dir = false;
    public $config_file = false;

    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = Application::getInstance();
            }
        }
        $adapters_dir = false;

        if (!isset($this->app->container)) {
            $container = new Pimple;
         } else {
            $container = $this->app->container;

        }
        $this->container = $container;

        $this->get_config();
        $this->map_adapters();

    }

    public function get_config($key = false)
    {
        $adapters_dir = false;
        if (empty($this->config_items)) {

            if ($this->app->config('adapters_dir') == false) {
                if (!defined('MW_ADAPTERS_DIR') and defined('MW_APP_PATH')) {
                    define('MW_ADAPTERS_DIR', MW_APP_PATH . 'Adapters' . DS);
                }
                if (defined('MW_ADAPTERS_DIR')) {
                    $adapters_dir = MW_ADAPTERS_DIR;
                } else {
                    $adapters_dir = __DIR__ . DS . 'Adapters' . DS;
                }

            } else {
                $adapters_dir = $this->app->config('adapters_dir');
            }
            if ($adapters_dir == false) {
                $adapters_dir = __DIR__ . DS . 'Adapters' . DS;

            }
            if ($adapters_dir != false) {
                $this->adapters_dir = $adapters_dir;
            }


            $adapters_config = false;

            if ($this->app->config('adapters_config_file') == false) {
                if (!isset($this->adapters_dir) or $this->adapters_dir != false) {
                    $this->config_file = $this->adapters_dir . 'config.json';
                }
            } else {
                $this->config_file = $this->app->config('adapters_config_file');
            }


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

    public function map_adapters()
    {
        ///get_config
        if (!empty($this->config_items)) {
            foreach ($this->config_items as $k => $v) {
                $app = $this->app;
                $this->container[$k] = function ($c) use ($v,$app) {
                    return new $v($app);
                };
            }
        }
    }




    public function save_config($params)
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
                return array('error' => "Config file is not writable");
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
                $conf_items = json_encode($conf_items);
                $save = file_put_contents($conf, $conf_items);
                if ($save) {
                    return array('success' => "Adapters configuration saved to $conf");
                }
            }
        }

    }

    public function get_adapters($provider)
    {
        $adapters_dir = false;
        if ($this->adapters_dir != false and is_string($this->adapters_dir)) {
            $adapters_dir = $this->adapters_dir;
        }

        if ($adapters_dir == false) {
            if (!defined('MW_ADAPTERS_DIR') and defined('MW_APP_PATH')) {
                define('MW_ADAPTERS_DIR', MW_APP_PATH . 'Adapters' . DS);
            }
            if (!defined('MW_ADAPTERS_DIR')) {
                return;
            }
            $adapters_dir = MW_ADAPTERS_DIR;
        }

        if ($adapters_dir == false) {
            return;
        }
        $adapters_dir = str_replace('..', '', $adapters_dir);
        $provider = str_replace('..', '', $provider);
        $provider = str_replace(' ', '_', $provider);

        $providers_dir = normalize_path($adapters_dir . DS . $provider, true);
        if (!is_dir($providers_dir)) {
            $providers_dir = normalize_path($adapters_dir . DS . ucfirst($provider), true);
            if (!is_dir($providers_dir)) {
                $providers_dir = normalize_path($adapters_dir . DS . strtolower($provider), true);
            }
        }
        if (!is_dir($providers_dir)) {
            return;
        }

        $files = glob("$providers_dir{*.php,*.PHP}", GLOB_BRACE);


        $providers = array();
        if (!empty($files)) {
            foreach ($files as $file) {

                if (stripos($file, '.php', 1)) {
                    $mtime = filemtime($file);
                    // Get time and date from filename
                    $date = date("F d Y", $mtime);
                    $time = date("H:i:s", $mtime);
                    // Remove the sql extension part in the filename
                    //	$filenameboth = str_replace('.sql', '', $file);
                    $bak = array();
                    $basename = basename($file);
                    $basename = str_ireplace('.php', '', $basename);
                    $bak['title'] = $basename;

                    $adapter = str_replace(dirname(MW_APP_PATH), '', $file);
                    $adapter = str_ireplace('.php', '', $adapter);
                    $adapter = str_ireplace('/', '\\', $adapter);

                    $bak['adapter'] = $adapter;
                    $title = str_ireplace('.php', '', $basename);
                    $title = str_ireplace('-', ' ', $title);
                    $title = str_ireplace('_', ' ', $title);

                    $bak['title'] = $title;

                    $bak['date'] = $date;
                    $bak['time'] = str_replace('_', ':', $time);

                    $bak['size'] = filesize($file);

                    $providers[] = $bak;
                }

            }
            return $providers;

        }


    }


}
