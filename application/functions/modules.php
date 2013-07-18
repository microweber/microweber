<?php

if (!defined("MW_DB_TABLE_MODULES")) {
    define('MW_DB_TABLE_MODULES', MW_TABLE_PREFIX . 'modules');
}

if (!defined("MW_DB_TABLE_ELEMENTS")) {
    define('MW_DB_TABLE_ELEMENTS', MW_TABLE_PREFIX . 'elements');
}

if (!defined("MW_DB_TABLE_MODULE_TEMPLATES")) {
    define('MW_DB_TABLE_MODULE_TEMPLATES', MW_TABLE_PREFIX . 'module_templates');
}

api_expose('reorder_modules');

function reorder_modules($data)
{

    return \ModuleUtils::reorder_modules($data);
}


/**
 *
 * Modules functions API
 *
 * @package        modules
 * @since        Version 0.1
 */

// ------------------------------------------------------------------------
if (!defined('EMPTY_MOD_STR')) {
    define("EMPTY_MOD_STR", "<div class='mw-empty-module '>{module_title} {type}</div>");
}
/**
 * module_templates
 *
 * Gets all templates for a module
 *
 * @package        modules
 * @subpackage    functions
 * @category    modules api
 */

function module_templates($module_name, $template_name = false)
{
    return \Module::templates($module_name, $template_name);

}

function module_name_decode($module_name)
{
    $module_name = str_replace('__', '/', $module_name);
    return $module_name;
    //$module_name = str_replace('%20', '___', $module_name);

    //$module_name = str_replace('/', '___', $module_name);
}

function module_name_encode($module_name)
{
    $module_name = str_replace('/', '__', $module_name);
    $module_name = str_replace('\\', '__', $module_name);
    return $module_name;
    //$module_name = str_replace('%20', '___', $module_name);

    //$module_name = str_replace('/', '___', $module_name);
}


function module($params)
{

    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $options = $params2;
    }
    $tags = '';
    $em = EMPTY_MOD_STR;
    foreach ($params as $k => $v) {

        if ($k == 'type') {
            $module_name = $v;
        }

        if ($k == 'module') {
            $module_name = $v;
        }

        if ($k == 'data-type') {
            $module_name = $v;
        }

        if ($k != 'display') {

            if (is_array($v)) {
                $v1 = encode_var($v);
                $tags .= "{$k}=\"$v1\" ";
            } else {

                $em = str_ireplace("{" . $k . "}", $v, $em);

                $tags .= "{$k}=\"$v\" ";
            }
        }
    }

    //$tags = "<div class='module' {$tags} data-type='{$module_name}'  data-view='empty'>" . $em . "</div>";

    $res = \Module::load($module_name, $params);
    if (is_array($res)) {
        // $res['edit'] = $tags;
    }

    if (isset($params['wrap']) or isset($params['data-wrap'])) {
        $module_cl = module_css_class($module_name);
        $res = "<div class='module {$module_cl}' {$tags} data-type='{$module_name}'>" . $res . "</div>";
    }

    return $res;
}

function get_all_functions_files_for_modules($options = false)
{
    $args = func_get_args();
    $function_cache_id = '';

    $function_cache_id = serialize($options);

    $cache_id = $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

    $cache_group = 'modules/functions';

    $cache_content = cache_get_content($cache_id, $cache_group);

    if (($cache_content) != false) {

        return $cache_content;
    }

    //d($uninstall_lock);
    if (isset($options['glob'])) {
        $glob_patern = $options['glob'];
    } else {
        $glob_patern = '*functions.php';
    }

    if (isset($options['dir_name'])) {
        $dir_name = $options['dir_name'];
    } else {
        $dir_name = normalize_path(MODULES_DIR);
    }

    $disabled_files = array();

    $uninstall_lock = get_modules_from_db('ui=any&installed=[int]0');

    if (is_array($uninstall_lock) and !empty($uninstall_lock)) {
        foreach ($uninstall_lock as $value) {
            $value1 = normalize_path($dir_name . $value['module'] . DS . 'functions.php', false);
            $disabled_files[] = $value1;
        }
    }

    $dir = rglob($glob_patern, 0, $dir_name);

    if (!empty($dir)) {
        $configs = array();
        foreach ($dir as $key => $value) {

            if (is_string($value)) {
                $value = normalize_path($value, false);

                $found = false;
                foreach ($disabled_files as $disabled_file) {
                    //d($disabled_file);
                    if (strtolower($value) == strtolower($disabled_file)) {
                        $found = 1;
                    }
                }
                if ($found == false) {
                    $configs[] = $value;
                }
            }
            //d($value);
            //if ($disabled_files !== null and !in_array($value, $disabled_files,1)) {
            //
            //}
        }

        cache_save($configs, $function_cache_id, $cache_group, 'files');

        return $configs;
    } else {
        return false;
    }
}


function get_layouts_from_db($params = false)
{


    return \Layouts::get($params);


}

function save_element_to_db($data_to_save)
{
    return \Layouts::save($data_to_save);

}

function get_modules_from_db($params = false)
{

    return \Module::get($params);
}

api_expose('save_settings_el');

function save_settings_el($data_to_save)
{
    return save_element_to_db($data_to_save);
}

api_expose('save_settings_md');

function save_settings_md($data_to_save)
{
    return save_module_to_db($data_to_save);
}


function delete_elements_from_db()
{
    return \Layouts::delete_all();
}

function delete_module_by_id($id)
{
    return \ModuleUtils::delete_module($id);
}

function delete_modules_from_db()
{
    return \ModuleUtils::delete_all();
}

function is_module_installed($module_name)
{

    return \Module::is_installed($module_name);
}

function module_ico_title($module_name, $link = true)
{
    return \Module::is_installed($module_name,$link);
}

$_mw_modules_info_register = array();
function module_info($module_name)
{
    return \Module::info($module_name);

}

function is_module($module_name)
{
    return \Module::exists($module_name);
}



function module_dir($module_name)
{
    return \Module::dir($module_name);

}

function module_url($module_name)
{
    return \Module::url($module_name);

}

function locate_module($module_name, $custom_view = false, $no_fallback_to_view = false)
{

    return \Module::locate($module_name, $custom_view, $no_fallback_to_view);
}

api_expose('uninstall_module');

function uninstall_module($params)
{

    if (is_admin() == false) {
        return false;
    }
    return \ModuleUtils::uninstall($params);

}

action_hook('mw_db_init_modules', 're_init_modules_db');

function re_init_modules_db()
{

    return \ModuleUtils::update_db();

}

api_expose('install_module');

function install_module($params)
{


    if (defined('MW_FORCE_MOD_INSTALLED')) {

    } else {
        if (is_admin() == false) {
            return false;
        }
    }

    return \ModuleUtils::install($params);

}

function save_module_to_db($data_to_save)
{
    return \ModuleUtils::save($data_to_save);

}

function get_saved_modules_as_template($params)
{
    return \ModuleUtils::get_saved_modules_as_template($params);
}

api_expose('delete_module_as_template');
function delete_module_as_template($data)
{

    return \ModuleUtils::delete_module_as_template($data);



}

api_expose('save_module_as_template');
function save_module_as_template($data_to_save)
{

    return \ModuleUtils::save_module_as_template($data_to_save);
}

/**
 *
 * Function modules list from the db or them the disk
 * @return mixed Array with modules or false
 * @param array $params
 *

Example:
$params = array();
$params['dir_name'] = '/path/'; //get modules in dir
$params['skip_save'] = true; //if true skips module install
$params['skip_cache'] = true; // skip_cache

$params['cache_group'] = 'modules/global'; // allows custom cache group
$params['cleanup_db'] = true; //if true will reinstall all modules if skip_save is false
$params['is_elements'] = true;  //if true will list files from the ELEMENTS_DIR

$data = modules_list($params);

 */

function modules_list($options = false)
{

    return \ModuleUtils::scan_for_modules($options);
}

action_hook('mw_scan_for_modules', 'scan_for_modules');
function scan_for_modules($options = false)
{
    return \ModuleUtils::scan_for_modules($options);

}

action_hook('mw_scan_for_modules', 'get_elements');

function get_elements($options = array())
{
    return \ModuleUtils::get_layouts($options);


}


function load_module_lic($module_name = false)
{
    \Module::license($module_name);
}


function load_module($module_name, $attrs = array())
{

    return \Module::load($module_name, $attrs);

}


function module_css_class($module_name)
{
    \Module::css_class($module_name);
}

