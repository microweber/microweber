<?php


api_expose('multi_site/create');

class multi_site
{

    // singleton instance
    public $table;
    public $app;

    // private constructor function
    // to prevent external instantiation

    function __construct($app = false)
    {
        $this->table = get_table_prefix() . 'sites';
        if (!is_object($this->app)) {

            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }

        }

    }

    function create($params)
    {
        if (is_string($params)) {
            $params = parse_params($params);
        }
        $adm = $this->app->user_manager->is_admin();
        if (!$adm) {
            return array('error' => 'Not logged in as admin to use ' . __FUNCTION__);
        }
        $data = $params;
        $table = $this->table;

        if (!isset($data['domain'])) {
            return array('error' => 'domain parameter is needed');

        }
        $domain = $data['domain'];

        $domain_id = $this->get_domain_id($domain);
        if ($domain_id != false) {
            $data['id'] = $domain_id;
        } else {

        }


        $save = $this->app->database->save($table, $data);

        $deploy = $this->deploy($save);

        return $save;
    }

    function get_domain_id($domain)
    {
        $table = $this->table;
        $params = array();
        $params['table'] = $table;
        $params['domain'] = $domain;
        $get = $this->app->database->get($params);
        if (isset($get[0]) and isset($get[0]['id'])) {
            return $get[0]['id'];
        } else {
            return false;
        }
    }

    function deploy($domain_id)
    {
        $domain = $this->get_domain_by_id($domain_id);
        if (!isset($domain['domain'])) {
            return false;
        }


        $domain_name = trim(strtolower($domain['domain']));
        $domain_name = str_replace('..', '', $domain_name);
        $domain_name_no_dot = str_replace('.', '_', $domain_name);
        $domain_name_no_dot = str_replace('-', '_', $domain_name_no_dot);

        $cfg = $path_to_file = MW_CONFIG_FILE;

        $cfg_dir = dirname($cfg);

        $new_config = $cfg_dir . DS . 'config_' . $domain_name . '.php';
        $new_bootsrap_base = $cfg_dir . DS . 'bootstrap_' . $domain_name . '.php';

        $config_base = mw_includes_path() . 'install' . DIRECTORY_SEPARATOR . 'config.base.php';
        $bootsrap_base = mw_includes_path() . 'install' . DIRECTORY_SEPARATOR . 'bootstrap.base.php';

        $save_config = file_get_contents($config_base);

        if (is_file($path_to_file)) {
            include  ($path_to_file);
            if (isset($config)) {
                $config['DB_TYPE'] = $config['db']['type'];
                $config['DB_HOST'] = $config['db']['host'];
                $config['DB_USER'] = $config['db']['user'];
                $config['DB_PASS'] = $config['db']['pass'];
                $config['dbname'] = $config['db']['dbname'];
                $config['admin_username'] = $domain['username'];
                $config['admin_password'] = $domain['password'];
                $config['admin_email'] = $domain['email'];
                $config['IS_INSTALLED'] = 'no';
                $config['table_prefix'] = 'mw_' . $domain_name_no_dot;
                $config['autoinstall'] = 'yes';
                $config['with_default_content'] = 'yes';


                foreach ($config as $k => $v) {

                    if (is_string($v)) {
                        $save_config = str_ireplace('{' . $k . '}', $v, $save_config);
                    }
                }
                //file_put_contents('file.csv', implode(PHP_EOL, $data));
                // $new_str = $this->app->format->unvar_dump($config);
            }

        }
        if ($save_config != false and is_string($save_config)) {
            d($save_config);
            file_put_contents($new_config, $save_config);

        }
        if ($domain_name == 'userfiles') {
            return;
        }

        $custom_user_files_dir = MW_ROOTPATH . 'sites' . DIRECTORY_SEPARATOR . 'userfiles' . DIRECTORY_SEPARATOR;
        $custom_user_files_domain = MW_ROOTPATH . 'sites' . DIRECTORY_SEPARATOR . $domain_name . DIRECTORY_SEPARATOR;


        if (is_dir($custom_user_files_dir)) {
            if (!is_dir($custom_user_files_domain) and mkdir($custom_user_files_domain)) {
            }
            $file_class = mw('Microweber\Utils\Files');
            $files_copy = $file_class->copy_directory($custom_user_files_dir, $custom_user_files_domain);

            if (is_array($files_copy) and !empty($files_copy)) {
                if (is_file($bootsrap_base)) {
                    $custom_bootrap_file = $custom_bootrap_file_orig = file_get_contents($bootsrap_base);

                    foreach ($config as $k => $v) {
                        if (is_string($v)) {
                            $custom_bootrap_file = str_ireplace('{' . $k . '}', $v, $custom_bootrap_file);
                        }
                    }
                    foreach ($domain as $k => $v) {
                        if (is_string($v)) {
                            $custom_bootrap_file = str_ireplace('{' . $k . '}', $v, $custom_bootrap_file);
                        }
                    }
                    if($custom_bootrap_file != false){
                        if($custom_bootrap_file_orig != $custom_bootrap_file){
                            d($custom_user_files_domain);
                            file_put_contents($new_bootsrap_base, $custom_bootrap_file);

                        }
                    }
                }

            }


        }


    }

    function get_domain_by_id($domain_id)
    {
        $table = $this->table;
        $params = array();
        $params['table'] = $table;
        $params['id'] = $domain_id;
        $get = $this->app->database->get($params);
        if (isset($get[0]) and isset($get[0]['id'])) {
            return $get[0];
        } else {
            return false;
        }
    }
}
