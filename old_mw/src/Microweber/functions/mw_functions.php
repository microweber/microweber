<?php
if (!defined('MW_APP_PATH')) {
    return;
}

include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'api.php');
include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'db.php');
include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'users.php');

include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'common.php');
include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'content.php');

include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'categories.php');
include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'shop.php');

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

                        if (isset($autoinstall['table_prefix'])) {
                            $prefix = trim($autoinstall['table_prefix']);
                            if ($prefix != '') {
                                $last_char = substr($prefix, -1);
                                if ($last_char != '_') {
                                    $prefix = $prefix . '_';
                                    $autoinstall['table_prefix'] = $prefix;
                                }
                            }
                        }

                        define('MW_TABLE_PREFIX', (trim($autoinstall['table_prefix'])));
                    }
                    if (!defined('MW_TABLE_PREFIX') and isset($_REQUEST['table_prefix'])) {

                        if (isset($_REQUEST['table_prefix'])) {
                            $prefix = trim($_REQUEST['table_prefix']);
                            if ($prefix != '') {
                                $last_char = substr($prefix, -1);
                                if ($last_char != '_') {
                                    $prefix = $prefix . '_';
                                    $_REQUEST['table_prefix'] = $prefix;
                                }
                            }
                        }
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


/**
 *  Loading common function files
 */
include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'media.php');
include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'language.php');
include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'updates.php');
/**
 *  Loading modules function files
 */

function mw_load_all_modules_functions()
{
    static $is_loaded = false;

    if ($is_loaded == false and function_exists('get_all_functions_files_for_modules')) {
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
    $is_loaded = true;

}


 event_bind('app_init', 'mw_load_all_modules_functions');
//event_trigger('mw_init');
//event_trigger('app_init',false);
  // mw_load_all_modules_functions();







