<?php

include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'api.php');
include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'common.php');
include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'media.php');
include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'language.php');
include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'updates.php');

if (!defined('MW_IS_INSTALLED')) {
    $autoinstall_cli = getopt("i:");

    /*
    You can install MW from the command line if you have predefined your db access in the config
    just run php -e full_path_to/index.php -i=yes
    */
    if (isset($autoinstall_cli) and $autoinstall_cli != false and !empty($autoinstall_cli)) {

        $autoinstall_cli_val = array_pop($autoinstall_cli);
        if (trim(strtolower($autoinstall_cli_val)) == 'yes') {
            $cli_autoinstall = true;
        }
    }

    if (isset($cli_autoinstall) and $cli_autoinstall != false) {
        $cfg = MW_CONFIG_FILE;
        if (is_file($cfg) and is_readable($cfg)) {
            require ($cfg);
            if (is_array($config) and isset($config['db']) and is_array($config['db'])) {
                $autoinstall = $config;
                if (!isset($config['is_installed'])) {
                    $autoinstall['is_installed'] = 'no';
                }
                if (isset($config['table_prefix'])) {
                    $autoinstall['table_prefix'] = $config['table_prefix'];
                    if (!defined('MW_TABLE_PREFIX') and isset($autoinstall['table_prefix']) and !isset($_REQUEST['table_prefix'])) {
                        define('MW_TABLE_PREFIX', (trim($autoinstall['table_prefix'])));
                    }
                    if (!defined('MW_TABLE_PREFIX') and isset($_REQUEST['table_prefix'])) {
                        define('MW_TABLE_PREFIX', (trim($_REQUEST['table_prefix'])));
                    }
                } else {
                    $autoinstall['table_prefix'] = '';
                }
                if (isset($autoinstall['db']['dbname'])) {
                    $autoinstall['dbname'] = $autoinstall['db']['dbname'];
                }


                if (isset($autoinstall['db']['host'])) {
                    $autoinstall['db_host'] = $autoinstall['db']['host'];
                }
                if (isset($autoinstall['db']['user'])) {
                    $autoinstall['db_user'] = $autoinstall['db']['user'];
                }
                if (isset($autoinstall['db']['pass'])) {
                    $autoinstall['db_pass'] = $autoinstall['db']['pass'];
                }

                if (!defined('MW_INSTALL_FROM_CONFIG')) {
                    define('MW_INSTALL_FROM_CONFIG', true);
                    mw_var('mw_autoinstall', $autoinstall);
                }
            }
        }

    }


    if (!defined('MW_TABLE_PREFIX') and !isset($_REQUEST['autoinstall'])) {
    } else if (!defined('MW_TABLE_PREFIX') and isset($_REQUEST['table_prefix'])) {
        define('MW_TABLE_PREFIX', trim($_REQUEST['table_prefix']));
    } else if (!defined('MW_TABLE_PREFIX')) {
        $pre = mw()->config('table_prefix');
        define('MW_TABLE_PREFIX', $pre);
    }
}


$c_id = 'mw_init_all';
if (defined('MW_IS_INSTALLED') and MW_IS_INSTALLED == true) {
    $curent_time_zone = mw('option')->get('time_zone', 'website');
    if ($curent_time_zone != false and $curent_time_zone != '') {
        $default_time_zone = date_default_timezone_get();
        if ($default_time_zone != $curent_time_zone) {
            date_default_timezone_set($curent_time_zone);
        }
    }
}




if (defined('MW_IS_INSTALLED') and MW_IS_INSTALLED == true) {
    if ($cache_content_init != 'yes') {
        event_trigger('mw_db_init_modules');
    }
}

function get_all_functions_files_for_modules($options = false)
{


    $args = func_get_args();
    $function_cache_id = '';

    $function_cache_id = serialize($options);

    $cache_id = $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

    $cache_group = 'modules/global';

    $cache_content = mw('cache')->get($cache_id, $cache_group);

    if (($cache_content) != false) {

        return $cache_content;
    }

    if (isset($options['glob'])) {
        $glob_patern = $options['glob'];
    } else {
        $glob_patern = '*functions.php';
    }

    if (isset($options['dir_name'])) {
        $dir_name = $options['dir_name'];
    } else {
        $dir_name = normalize_path(MW_MODULES_DIR);
    }

    $disabled_files = array();

    $uninstall_lock = mw('module')->get('ui=any&installed=[int]0');

    if (is_array($uninstall_lock) and !empty($uninstall_lock)) {
        foreach ($uninstall_lock as $value) {
            $value1 = normalize_path($dir_name . $value['module'] . DS . 'functions.php', false);
            $disabled_files[] = $value1;
        }
    }

    $dir = mw('Utils\Files')->rglob($glob_patern, 0, $dir_name);

    if (!empty($dir)) {
        $configs = array();
        foreach ($dir as $key => $value) {

            if (is_string($value)) {
                $value = normalize_path($value, false);

                $found = false;
                foreach ($disabled_files as $disabled_file) {
                    if (strtolower($value) == strtolower($disabled_file)) {
                        $found = 1;
                    }
                }
                if ($found == false) {
                    $configs[] = $value;
                }
            }

        }

        mw('cache')->save($configs, $function_cache_id, $cache_group, 'files');

        return $configs;
    } else {
        return false;
    }
}


if (function_exists('get_all_functions_files_for_modules')) {
    $module_functions = get_all_functions_files_for_modules();
    if ($module_functions != false) {
        if (is_array($module_functions)) {
            foreach ($module_functions as $item) {
                if (is_file($item)) {
                    include_once ($item);
                }
            }
        }
    }
}





