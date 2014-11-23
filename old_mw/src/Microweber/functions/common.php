<?php
/**
 *  Microweber common functions
 *
 *
 * @package     Microweber
 *
 */


/**
 *
 * Limits a string to a number of characters
 *
 * @param $str
 * @param int $n
 * @param string $end_char
 * @return string
 * @package Utils
 * @category Strings
 */
function character_limiter($str, $n = 500, $end_char = '&#8230;')
{
    if (strlen($str) < $n) {
        return $str;
    }
    $str = strip_tags($str);
    $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

    if (strlen($str) <= $n) {
        return $str;
    }

    $out = "";
    foreach (explode(' ', trim($str)) as $val) {
        $out .= $val . ' ';

        if (strlen($out) >= $n) {
            $out = trim($out);
            return (strlen($out) == strlen($str)) ? $out : $out . $end_char;
        }
    }
}


function api_url($str = '')
{
    $str = ltrim($str, '/');
    return site_url('api/' . $str);
}

function auto_link($text)
{
    return mw()->format->auto_link($text);
}

function prep_url($text)
{
    return mw()->format->prep_url($text);
}


function is_arr($var)
{
    return isarr($var);
}

function isarr($var)
{
    if (is_array($var) and !empty($var)) {
        return true;
    } else {
        return false;
    }
}

function is_ajax()

{
    return mw('url')->is_ajax();

}

function url_current($skip_ajax = false, $no_get = false)
{
    return mw('url')->current($skip_ajax, $no_get);
}

function url_segment($k = -1, $page_url = false)
{
    return mw('url')->segment($k, $page_url);

}

/**
 * Returns the curent url path, does not include the domain name
 *
 * @return string the url string
 */
function url_path($skip_ajax = false)
{
    return mw('url')->string($skip_ajax);
}

/**
 * Returns the curent url path, does not include the domain name
 *
 * @return string the url string
 */
function url_string($skip_ajax = false)
{
    return mw('url')->string($skip_ajax);
}

function url_title($text)
{
    return mw('url')->slug($text);
}

function url_param($param, $skip_ajax = false)
{
    return mw('url')->param($param, $skip_ajax);
}


api_expose('system_log_reset');

function system_log_reset($data = false)
{
    return mw('log')->reset();
}

api_expose('delete_log_entry');

function delete_log_entry($data)
{
    return mw('log')->delete_entry($data);
}


api_expose('captcha');


function captcha()
{
    return Microweber\Utils\Captcha::render();
}


/**
 *  Gets the data from the cache.
 *
 *  If data is not found it return false
 *
 *
 * @example
 * <code>
 *
 * $cache_id = 'my_cache_'.crc32($sql_query_string);
 * $cache_content = cache_get_content($cache_id, 'my_cache_group');
 *
 * </code>
 * @param string $cache_id id of the cache
 * @param string $cache_group (default is 'global') - this is the subfolder in the cache dir.
 *
 * @param bool $expiration_in_seconds You can pass custom cache object or leave false.
 * @return  mixed returns array of cached data or false
 * @package Cache
 *
 */

function cache_get($cache_id, $cache_group = 'global', $expiration_in_seconds = false)
{
    return mw()->cache->get($cache_id, $cache_group, $expiration_in_seconds);
}

/**
 * Stores your data in the cache.
 * It can store any value that can be serialized, such as strings, array, etc.
 *
 * @example
 * <code>
 * //store custom data in cache
 * $data = array('something' => 'some_value');
 * $cache_id = 'my_cache_id';
 * $cache_content = cache_save($data, $cache_id, 'my_cache_group');
 * </code>
 *
 * @param mixed $data_to_cache
 *            your data, anything that can be serialized
 * @param string $cache_id
 *            id of the cache, you must define it because you will use it later to
 *            retrieve the cached content.
 * @param string $cache_group
 *            (default is 'global') - this is the subfolder in the cache dir.
 *
 * @param bool $expiration_in_seconds
 * @return boolean
 * @package Cache
 */
function cache_save($data_to_cache, $cache_id, $cache_group = 'global')
{
    return mw()->cache->save($data_to_cache, $cache_id, $cache_group);


}


api_expose('clearcache');
/**
 * Clears all cache data
 * @example
 * <code>
 * //delete all cache
 *  clearcache();
 * </code>
 * @return boolean
 * @package Cache
 */
function clearcache()
{
    return mw()->cache->clear();

}


/**
 * Prints cache debug information
 *
 * @return array
 * @package Cache
 * @example
 * <code>
 * //get cache items info
 *  $cached_items = cache_debug();
 * print_r($cached_items);
 * </code>
 */
function cache_debug()
{
    return mw()->cache->debug();

}


/**
 * Deletes cache for given $cache_group recursively.
 *
 * @param string $cache_group
 *            (default is 'global') - this is the subfolder in the cache dir.
 * @param bool $expiration_in_seconds
 * @return boolean
 *
 * @package Cache
 * @example
 * <code>
 * //delete the cache for the content
 *  cache_clear("content");
 *
 * //delete the cache for the content with id 1
 *  cache_clear("content/1");
 *
 * //delete the cache for users
 *  cache_clear("users");
 *
 * //delete the cache for your custom table eg. my_table
 * cache_clear("my_table");
 * </code>
 *
 */
function cache_clear($cache_group = 'global', $cache_storage_type = false)
{

    return mw()->cache->delete($cache_group, $cache_storage_type);


}

//same as cache_clear
function cache_delete($cache_group = 'global', $cache_storage_type = false)
{

    return mw()->cache->delete($cache_group, $cache_storage_type);


}





function get_user_by_id($params = false)
{
    return mw()->user->get_by_id($params);
}

function get_menus($params = false)
{

    return mw()->content->get_menus($params);

}

function get_menu($params = false)
{

    return mw()->content->get_menu($params);

}

api_expose('add_new_menu');
function add_new_menu($data_to_save)
{
    return mw()->content->menu_create($data_to_save);

}

api_expose('menu_delete');
function menu_delete($id = false)
{
    return mw()->content->menu_delete($id);

}

api_expose('delete_menu_item');
function delete_menu_item($id)
{

    return mw()->content->menu_item_delete($id);

}

function get_menu_item($id)
{

    return mw()->content->menu_item_get($id);

}

api_expose('edit_menu_item');
function edit_menu_item($data_to_save)
{
    return mw()->content->menu_item_save($data_to_save);


}

api_expose('reorder_menu_items');
function reorder_menu_items($data)
{
    return mw()->content->menu_items_reorder($data);
}

function menu_tree($menu_id = false, $maxdepth = false)
{
    return mw()->content->menu_tree($menu_id, $maxdepth);
}

function is_in_menu($menu_id = false, $content_id = false)
{
    return mw()->content->is_in_menu($menu_id, $content_id);

}

api_hook('save_content_admin', 'add_content_to_menu');

function add_content_to_menu($content_id, $menu_id = false)
{

    return mw()->content->add_content_to_menu($content_id, $menu_id);


}


api_expose('reorder_modules');

function reorder_modules($data)
{

    return mw()->module->reorder_modules($data);
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

function module_templates($module_name, $template_name = false,$is_settings=false)
{
    return mw()->module->templates($module_name, $template_name, $is_settings);

}


/**
 * @desc  Get the template layouts info under the layouts subdir on your active template
 * @param $options
 * $options ['type'] - 'layout' is the default type if you dont define any. You can define your own types as post/form, etc in the layout.txt file
 * @return array
 * @author    Microweber Dev Team
 * @since Version 1.0
 */
function site_templates($options = false)
{

    return mw()->template->site_templates($options);
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
                $v1 = mw()->format->array_to_base64($v);
                $tags .= "{$k}=\"$v1\" ";
            } else {

                $em = str_ireplace("{" . $k . "}", $v, $em);

                $tags .= "{$k}=\"$v\" ";
            }
        }
    }

    //$tags = "<div class='module' {$tags} data-type='{$module_name}'  data-view='empty'>" . $em . "</div>";

    $res = mw()->module->load($module_name, $params);
    if (is_array($res)) {
        // $res['edit'] = $tags;
    }

    if (isset($params['wrap']) or isset($params['data-wrap'])) {
        $module_cl = module_css_class($module_name);
        $res = "<div class='module {$module_cl}' {$tags} data-type='{$module_name}'>" . $res . "</div>";
    }

    return $res;
}


function module_info($module_name)
{
    return mw()->module->info($module_name);

}

function is_module($module_name)
{
    return mw()->module->exists($module_name);
}


function module_url($module_name = false)
{
    return mw()->module->url($module_name);

}

function module_dir($module_name)
{
    return mw()->module->dir($module_name);

}


function locate_module($module_name, $custom_view = false, $no_fallback_to_view = false)
{

    return mw()->module->locate($module_name, $custom_view, $no_fallback_to_view);
}

api_expose('uninstall_module');

function uninstall_module($params)
{

    if (is_admin() == false) {
        return false;
    }
    return mw()->module->uninstall($params);

}

event_bind('mw_db_init_modules', 're_init_modules_db');

function re_init_modules_db()
{

    //return mw()->module->update_db();

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

    return mw()->module->install($params);

}

function save_module_to_db($data_to_save)
{
    return mw()->module->save($data_to_save);

}

function get_saved_modules_as_template($params)
{
    return mw()->module->get_saved_modules_as_template($params);
}

api_expose('delete_module_as_template');
function delete_module_as_template($data)
{

    return mw()->module->delete_module_as_template($data);


}


function layouts_list($options = false)
{

    return mw()->layouts->scan($options);
}

function get_layouts_from_db($options = false)
{

    return mw()->layouts->get($options);
}

function get_modules_from_db($options = false)
{

    return mw()->module->get($options);
}


function get_modules($options = false)
{

    return mw()->module->get($options);
}

api_expose('save_module_as_template');
function save_module_as_template($data_to_save)
{

    return mw()->module->save_module_as_template($data_to_save);
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
$params['is_elements'] = true;  //if true will list files from the MW_ELEMENTS_DIR

$data = scan_for_modules($params);

 */


event_bind('mw_scan_for_modules', 'scan_for_modules');
function scan_for_modules($options = false)
{
    return mw()->module->scan_for_modules($options);

}

event_bind('mw_scan_for_modules', 'get_elements');

function get_elements($options = array())
{
    return mw()->module->get_layouts($options);


}
function have_license($module_name = false)
{
    return  mw()->module->license($module_name);
}

function load_module_lic($module_name = false)
{
    return have_license($module_name);
}


function load_module($module_name, $attrs = array())
{

    return mw()->module->load($module_name, $attrs);

}


function module_css_class($module_name)
{
    mw()->module->css_class($module_name);
}


function template_var($key, $new_val = false)
{
    static $defined = array();
    $contstant = ($key);
    if ($new_val == false) {
        if (isset($defined[$contstant]) != false) {
            return $defined[$contstant];
        } else {
            return false;
        }
    } else {
        if (isset($defined[$contstant]) == false) {
            $defined[$contstant] = $new_val;
            return $new_val;
        }
    }
    return false;
}


/**
 *
 * You can use this function to store options in the database.
 *
 * @param $data array|string
 * Example usage:
 *
 * $option = array();
 * $option['option_value'] = 'my value';
 * $option['option_key'] = 'my_option';
 * $option['option_group'] = 'my_option_group';
 * save_option($option);
 *
 *
 *
 */
api_expose('save_option');
function save_option($data)
{

    return mw('option')->save($data);
}


api_expose('save_form_list');
function save_form_list($params)
{
    return mw('forms')->save_list($params);

}


function system_config_get($key = false)
{
    return mw()->config($key);

}

api_expose('delete_forms_list');

function delete_forms_list($data)
{
    return mw('Forms')->delete_list($data);
}

api_expose('delete_form_entry');

function delete_form_entry($data)
{
    return mw('Forms')->delete_entry($data);

}

api_expose('forms_list_export_to_excel');
function forms_list_export_to_excel($params)
{


    return mw('Forms')->export_to_excel($params);


}


function get_form_entires($params)
{
    return mw('Forms')->get_entires($params);

}

function get_form_lists($params)
{
    return mw('Forms')->get_lists($params);
}

api_expose('post_form');
function post_form($params)
{
    return mw('Forms')->post($params);


}


event_bind('mw_admin_settings_menu', 'mw_print_admin_backup_settings_link');

function mw_print_admin_backup_settings_link()
{

    if (mw()->module->is_installed('admin/backup')) {

        $active = mw('url')->param('view');
        $cls = '';
        $mname = module_name_encode('admin/backup/small');
        if ($active == $mname) {
            $cls = ' class="active" ';
        }
        $notif_html = '';
        $url = admin_url('view:modules/load_module:' . $mname);
        print "<li><a class=\"item-" . $mname . "\" href=\"#option_group=" . $mname . "\">Backup</a></li>";
        //print "<li><a class=\"item-".$mname."\" href=\"".$url."\">Backup</a></li>";
    }


    if (mw()->module->is_installed('admin/import')) {

        $active = mw('url')->param('view');
        $cls = '';
        $mname = module_name_encode('admin/import');
        if ($active == $mname) {
            $cls = ' class="active" ';
        }
        $notif_html = '';
        $url = admin_url('view:modules/load_module:' . $mname);
        print "<li><a class=\"item-" . $mname . "\" href=\"#option_group=" . $mname . "\">Import</a></li>";
        //print "<li><a class=\"item-".$mname."\" href=\"".$url."\">Backup</a></li>";
    }
}


api_expose('mw_post_update');
function mw_post_update()
{
    $a = is_admin();
    if ($a != false) {
        mw()->cache->delete('db');
        mw()->cache->delete('update/global');
        mw()->cache->delete('elements/global');

        mw()->cache->delete('templates');
        mw()->cache->delete('modules/global');

        scan_for_modules();
        get_elements();
        mw()->layouts->scan();
        event_trigger('mw_db_init_default');
        event_trigger('mw_db_init_modules');
        event_trigger('mw_db_init');
    }
}

api_expose('mw_install_market_item');

function mw_install_market_item($params)
{
    $a = is_admin();
    if ($a != false) {
        return  mw('update')->install_market_item($params);
    }
}
api_expose('mw_apply_updates');

function mw_apply_updates($params)
{

    $update_api = mw('update');
    return $update_api->apply_updates($params);

}
api_expose('mw_save_license');

function mw_save_license($params)
{

    $update_api = mw('update');
    return $update_api->save_license($params);

}

api_expose('mw_validate_licenses');

function mw_validate_licenses($params)
{

    $update_api = mw('update');
    return $update_api->validate_license($params);

}
function mw_updates_count()
{
    $count = 0;
    $upd_count = mw_check_for_update();
    if (isset($upd_count['count'])) {
        return intval($upd_count['count']);
    } else {
        return false;
    }


}

$mw_avail_updates = false;
function mw_check_for_update()
{


    global $mw_avail_updates;
    if ($mw_avail_updates == false) {

        $update_api = mw('update');
        $iudates = $update_api->check();
        $mw_avail_updates = $iudates;

    }
    return $mw_avail_updates;

}


function admin_url($add_string = false)
{

    $admin_url = c('admin_url');

    return site_url($admin_url) . '/' . $add_string;
}


//api_expose('mw_send_anonymous_server_data');
// function used do send us the language files
function mw_send_anonymous_server_data($params)
{
    only_admin_access();
    $update_api = mw('update');


    if ($params != false) {
        $params = parse_params($params);
    } else {

    }


    if (method_exists($update_api, 'send_anonymous_server_data')) {
        $iudates = $update_api->send_anonymous_server_data($params);

        return $iudates;
    } else {
        $params['site_url'] = site_url();
        $result = $update_api->call('send_anonymous_server_data', $params);
        return $result;
    }


}


/**
 * Trims an entire array recursively.
 *
 * @package Utils
 * @category Arrays
 * @author Jonas John
 * @version 0.2
 * @link http://www.jonasjohn.de/snippets/php/trim-array.htm
 * @param array $Input
 *            Input array
 * @return array|string
 */
function array_trim($Input)
{
    if (!is_array($Input))
        return trim($Input);
    return array_map('array_trim', $Input);
}


//event_bind('live_edit_toolbar_image_search', 'mw_print_live_edit_toolbar_image_search');
//
//function mw_print_live_edit_toolbar_image_search()
//{
//    $active = mw('url')->param('view');
//    $cls = '';
//    if ($active == 'shop') {
//        $cls = ' class="active" ';
//    }
//    print '<module type="files/admin" />';
//}


/**
 * Converts a path in the appropriate format for win or linux
 *
 * @param string $path
 *            The directory path.
 * @param boolean $slash_it
 *            If true, ads a slash at the end, false by default
 * @return string The formated string
 *
 * @package Utils
 * @category Files
 */
function normalize_path($path, $slash_it = true)
{

    $parser_mem_crc = 'normalize_path' . crc32($path . $slash_it);

    $ch = mw_var($parser_mem_crc);
    if ($ch != false) {

        $path = $ch;
        // print $path;
    } else {


        $path_original = $path;
        $s = DIRECTORY_SEPARATOR;
        $path = preg_replace('/[\/\\\]/', $s, $path);
        // $path = preg_replace ( '/' . $s . '$/', '', $path ) . $s;
        $path = str_replace($s . $s, $s, $path);
        if (strval($path) == '') {
            $path = $path_original;
        }
        if ($slash_it == false) {
            $path = rtrim($path, DIRECTORY_SEPARATOR);
        } else {
            $path .= DIRECTORY_SEPARATOR;
            $path = rtrim($path, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR);
        }
        if (strval(trim($path)) == '' or strval(trim($path)) == '/') {
            $path = $path_original;
        }
        if ($slash_it == false) {
        } else {
            $path = $path . DIRECTORY_SEPARATOR;
            $path = reduce_double_slashes($path);
            // $path = rtrim ( $path, DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR );
        }

        mw_var($parser_mem_crc, $path);

    }

    return $path;
}


/**
 * Removes double slashes from sting
 * @param $str
 * @return string
 */
function reduce_double_slashes($str)
{
    return preg_replace("#([^:])//+#", "\\1/", $str);
}


/**
 * Returns extension from a filename
 *
 * @param $LoSFileName Your filename
 * @return string  $filename extension
 * @package Utils
 * @category Files
 */
function get_file_extension($LoSFileName)
{
    $LoSFileExtensions = substr($LoSFileName, strrpos($LoSFileName, '.') + 1);
    return $LoSFileExtensions;
}

/**
 * Returns a filename without extension
 * @param $filename The filename
 * @return string  $filename without extension
 * @package Utils
 * @category Files
 */
function no_ext($filename)
{
    $filebroken = explode('.', $filename);
    array_pop($filebroken);

    return implode('.', $filebroken);

}

function url2dir($path)
{
    if (trim($path) == '') {
        return false;
    }

    $path = str_ireplace(site_url(), MW_ROOTPATH, $path);
    $path = str_replace('\\', '/', $path);
    $path = str_replace('//', '/', $path);

    return normalize_path($path, false);
}


function dir2url($path)
{
    $path = str_ireplace(MW_ROOTPATH, '', $path);
    $path = str_replace('\\', '/', $path);
    $path = str_replace('//', '/', $path);
    //var_dump($path);
    return site_url($path);
}


/**
 * Makes directory recursive, returns TRUE if exists or made and false on error
 *
 * @param string $pathname
 *            The directory path.
 * @return boolean
 *          returns TRUE if exists or made or FALSE on failure.
 *
 * @package Utils
 * @category Files
 */
function mkdir_recursive($pathname)
{
    if ($pathname == '') {
        return false;
    }
    is_dir(dirname($pathname)) || mkdir_recursive(dirname($pathname));
    return is_dir($pathname) || @mkdir($pathname);
}


function strleft($s1, $s2)
{
    return substr($s1, 0, strpos($s1, $s2));
}


if (defined('MW_IS_INSTALLED') and MW_IS_INSTALLED == true and function_exists('get_all_functions_files_for_modules')) {
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
    if (MW_IS_INSTALLED == true) {

        if (($cache_content_init) == false) {
            event_trigger('mw_db_init');
        }

        //event_trigger('mw_cron');
    }
}


/**
 *
 * Getting options from the database
 *
 * @param $key array|string - if array it will replace the db params
 * @param $option_group string - your option group
 * @param $return_full bool - if true it will return the whole db row as array rather then just the value
 * @param $module string - if set it will store option for module
 * Example usage:
 * get_option('my_key', 'my_group');
 *
 *
 *
 */
$_mw_global_options_mem = array();
function get_option($key, $option_group = false, $return_full = false, $orderby = false, $module = false)
{
    $update_api = mw('option');
    $iudates = $update_api->get($key, $option_group, $return_full, $orderby, $module);
    return $iudates;
}


function lipsum($number_of_characters = false)
{
    if ($number_of_characters == false) {
        $number_of_characters = 100;
    }

    $lipsum = array(
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quis justo et sapien varius gravida. Fusce porttitor consectetur risus ut tincidunt. Maecenas pellentesque nulla sodales enim consectetur commodo. Aliquam non dui leo, adipiscing posuere metus. Duis adipiscing auctor lorem ut pulvinar. Donec non magna massa, feugiat commodo felis. Donec ut nibh elit. Nulla pellentesque nulla diam, vitae consectetur neque.',
        'Etiam sed lorem augue. Vivamus varius tristique bibendum. Phasellus vitae tempor augue. Maecenas consequat commodo euismod. Aenean a lorem nec leo dignissim ultricies sed quis nisi. Fusce pellentesque tellus lectus, eu varius felis. Mauris lacinia facilisis metus, sed sollicitudin quam faucibus id.',
        'Donec ultrices cursus erat, non pulvinar lectus consectetur eu. Proin sodales risus a ante aliquet vel cursus justo viverra. Duis vel leo felis. Praesent hendrerit, sem vitae scelerisque blandit, enim neque pulvinar mi, vel lobortis elit dui vel dui. Donec ac sem sed neque consequat egestas. Curabitur pellentesque consequat ante, quis laoreet enim gravida eu. Donec varius, nisi non bibendum pellentesque, felis metus pretium ipsum, non vulputate eros magna ac sapien. Donec tincidunt porta tortor, et ornare enim facilisis vitae. Nulla facilisi. Cras ut nisi ac dolor lacinia tempus at sed eros. Integer vehicula arcu in augue adipiscing accumsan. Morbi placerat consectetur sapien sed gravida. Sed fringilla elit nisl, nec molestie felis. Nulla aliquet diam vitae diam iaculis porttitor.',
        'Integer eget tortor nulla, non dapibus erat. Sed ultrices consectetur quam at scelerisque. Nullam varius hendrerit nisl, ac cursus mi bibendum eu. Phasellus varius fermentum massa, sit amet ornare quam malesuada in. Quisque ac massa sem. Nulla eu erat metus, non tincidunt nibh. Nam consequat interdum nulla, at congue libero tincidunt eget. Sed cursus nulla eu felis faucibus porta. Nam sed lacus eros, nec pellentesque lorem. Sed dapibus, sapien mattis sollicitudin bibendum, libero augue dignissim felis, eget elementum felis nulla in velit. Donec varius, lectus non suscipit sollicitudin, urna est hendrerit nulla, vel vehicula arcu sem volutpat sapien. Ut nisi ipsum, accumsan vestibulum pulvinar eu, sodales id lacus. Nulla iaculis eros sit amet lectus tincidunt mattis. Ut eu nisl sit amet eros vestibulum imperdiet ut vel lorem. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.',
        'In hac habitasse platea dictumst. Aenean vehicula auctor eros non tincidunt. Donec tempor arcu ac diam sagittis mattis. Aenean eget augue nulla, non volutpat lorem. Praesent ut cursus magna. Mauris consequat suscipit nisi. Integer eu venenatis ligula. Maecenas leo risus, lacinia et auctor aliquet, aliquet in mi.',
        'Aliquam tincidunt dapibus augue, et vulputate dui aliquet et. Praesent pharetra mauris eu justo dignissim venenatis ornare nec nisl. Aliquam justo quam, varius eget congue vel, congue eget est. Ut nulla felis, luctus imperdiet molestie et, commodo vel nulla. Morbi at nulla dapibus enim bibendum aliquam non et ipsum. Phasellus sed cursus justo. Praesent sit amet metus lorem. Vivamus ut lorem dapibus turpis rhoncus pharetra. Donec in lacus sagittis nisl tempor sagittis quis a orci. Nam volutpat condimentum ante ac facilisis. Cras sem magna, vulputate id consequat rhoncus, suscipit non justo. In fringilla dignissim cursus.',
        'Nunc fringilla orci tellus, et euismod lorem. Ut quis turpis lacus, ac elementum lorem. Praesent fringilla, metus nec tincidunt consequat, sem sapien hendrerit nisi, nec feugiat libero risus a nisl. Duis arcu magna, ullamcorper et semper vitae, tincidunt nec libero. Etiam sed lacus ante. In imperdiet arcu eget elit commodo ut malesuada sem congue. Quisque porttitor porta sagittis. Nam porta elit sit amet mauris fermentum eu feugiat ipsum pretium. Maecenas sollicitudin aliquam eros, ut pretium nunc faucibus quis. Mauris id metus vitae libero viverra adipiscing quis ut nulla. Pellentesque posuere facilisis nibh, facilisis vehicula felis facilisis nec.',
        'Etiam pharetra libero nec erat pellentesque laoreet. Sed eu libero nec nisl vehicula convallis nec non orci. Aenean tristique varius nisl. Cras vel urna eget enim placerat vehicula quis sed velit. Quisque lacinia sagittis lectus eget sagittis. Pellentesque cursus suscipit massa vel ultricies. Quisque hendrerit lobortis elit interdum feugiat. Sed posuere volutpat erat vel lobortis. Vivamus laoreet mattis varius. Fusce tincidunt accumsan lorem, in viverra lectus dictum eu. Integer venenatis tristique dolor, ac porta lacus pellentesque pharetra. Suspendisse potenti. Ut dolor dolor, sollicitudin in auctor nec, facilisis non justo. Mauris cursus euismod gravida. In at orci in sapien laoreet euismod.',
        'Mauris purus urna, vulputate in malesuada ac, varius eget ante. Integer ultricies lacus vel magna dictum sit amet euismod enim dictum. Aliquam iaculis, ipsum at tempor bibendum, dolor tortor eleifend elit, sed fermentum magna nibh a ligula. Phasellus ipsum nisi, porta quis pellentesque sit amet, dignissim vel felis. Quisque condimentum molestie ligula, ac auctor turpis facilisis ac. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent molestie leo velit. Sed sit amet turpis massa. Donec in tortor quis metus cursus iaculis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In hac habitasse platea dictumst. Proin leo nisl, faucibus non sollicitudin et, commodo id diam. Aliquam adipiscing, lorem a fringilla blandit, felis dui tristique ligula, vitae eleifend orci diam eget quam. Aliquam vulputate gravida leo eget eleifend. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;',
        'Etiam et consectetur justo. Integer et ante dui, quis rutrum massa. Fusce nibh nisl, congue sit amet tempor vitae, ornare et nisi. Nulla mattis nisl ut ligula sagittis aliquam. Curabitur ac augue at velit facilisis venenatis quis sit amet erat. Donec lacus elit, auctor sed lobortis aliquet, accumsan nec mi. Quisque non est ante. Morbi vehicula pulvinar magna, quis luctus tortor varius et. Donec hendrerit nulla posuere odio lobortis interdum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec dapibus magna id ante sodales tempus. Maecenas at eleifend nulla.',
        'Sed eget gravida magna. Quisque vulputate diam nec libero faucibus vitae fringilla ligula lobortis. Aenean congue, dolor ut dapibus fermentum, justo lectus luctus sem, et vestibulum lectus orci non mauris. Vivamus interdum mauris at diam scelerisque porta mollis massa hendrerit. Donec condimentum lacinia bibendum. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam neque dolor, faucibus sed varius sit amet, vulputate vitae nunc.',
        'Etiam in lorem congue nunc sollicitudin rhoncus vel in metus. Integer luctus semper sem ut interdum. Sed mattis euismod diam, at porta mauris laoreet quis. Nam pellentesque enim id mi vestibulum gravida in vel libero. Nulla facilisi. Morbi fringilla mollis malesuada. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum sagittis consectetur auctor. Phasellus convallis turpis eget diam tristique feugiat. In consectetur quam faucibus purus suscipit euismod quis sed quam. Curabitur eget sodales dui. Quisque egestas diam quis sapien aliquet tincidunt.',
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam velit est, imperdiet ac posuere non, dictum et nunc. Duis iaculis lacus in libero lacinia ut consectetur nisi facilisis. Fusce aliquet nisl id eros dapibus viverra. Phasellus eget ultrices nisl. Nullam euismod tortor a metus hendrerit convallis. Donec dolor magna, fringilla in sollicitudin sit amet, tristique eget elit. Praesent adipiscing magna in ipsum vulputate non lacinia metus vestibulum. Aenean dictum suscipit mollis. Nullam tristique commodo dapibus. Fusce in tellus sapien, at vulputate justo. Nam ornare, lorem sit amet condimentum ultrices, ipsum velit tempor urna, tincidunt convallis sapien enim eget leo. Proin ligula tellus, ornare vitae scelerisque vitae, fringilla fermentum sem. Phasellus ornare, diam sed luctus condimentum, nisl felis ultricies tortor, ac tempor quam lacus sit amet lorem. Nunc egestas, nibh ornare dictum iaculis, diam nisl fermentum magna, malesuada vestibulum est mauris quis nisl. Ut vulputate pharetra laoreet.',
        'Donec mattis mauris et dolor commodo et pellentesque libero congue. Sed tristique bibendum augue sed auctor. Sed in ante enim. In sed lectus massa. Nulla imperdiet nisi at libero faucibus sagittis ac ac lacus. In dui purus, sollicitudin tempor euismod euismod, dapibus vehicula elit. Aliquam vulputate, ligula non dignissim gravida, odio elit ornare risus, a euismod est odio nec ipsum. In hac habitasse platea dictumst. Mauris posuere ultrices mattis. Etiam vitae leo vitae nunc porta egestas at vitae nibh. Sed pharetra, magna nec bibendum aliquam, dolor sapien consequat neque, sit amet euismod orci elit vitae enim. Sed erat metus, laoreet quis posuere id, congue id velit. Mauris ac velit vel ipsum dictum ornare eget vitae arcu. Donec interdum, neque at lacinia imperdiet, ante libero convallis quam, pellentesque faucibus quam dolor id est. Ut cursus facilisis scelerisque. Sed vitae ligula in purus malesuada porta.',
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vestibulum vestibulum metus. Integer ultrices ultricies pellentesque. Nulla gravida nisl a magna gravida ullamcorper. Vestibulum accumsan eros vel massa euismod in aliquam felis suscipit. Ut et purus enim, id congue ante. Mauris magna lectus, varius porta pellentesque quis, dignissim in est. Nulla facilisi. Nullam in malesuada mauris. Ut fermentum orci neque. Aliquam accumsan justo a lacus vestibulum fermentum. Donec molestie, quam id adipiscing viverra, massa velit aliquam enim, vitae dapibus turpis libero id augue. Quisque mi magna, mollis vel tincidunt nec, adipiscing sed metus. Maecenas tincidunt augue quis felis dapibus nec elementum justo fringilla. Sed eget massa at sapien tincidunt porta eu id sapien.'
    );
    $rand = rand(0, (sizeof($lipsum) - 1));

    return character_limiter($lipsum[$rand], $number_of_characters, '');
}


/**
 *
 *
 * Recursive glob()
 *
 * @access public
 * @package Utils
 * @category Files
 *
 * @uses is_array()
 * @param int|string $pattern
 * the pattern passed to glob()
 * @param int $flags
 * the flags passed to glob()
 * @param string $path
 * the path to scan
 * @return mixed
 * an array of files in the given path matching the pattern.
 */
function rglob($pattern = '*', $flags = 0, $path = '')
{
    if (!$path && ($dir = dirname($pattern)) != '.') {
        if ($dir == '\\' || $dir == '/')
            $dir = '';
        return rglob(basename($pattern), $flags, $dir . DS);
    }

    $path = normalize_path($path, 1);
    $paths = glob($path . '*', GLOB_ONLYDIR | GLOB_NOSORT);
    $files = glob($path . $pattern, $flags);


    if (is_array($paths)) {
        foreach ($paths as $p) {
            $temp = rglob($pattern, false, $p . DS);
            if (is_array($temp) and is_array($files) and !empty($files)) {
                $files = array_merge($files, $temp);
            } else if (is_array($temp) and !empty($temp)) {
                $files = $temp;
            }
        }
    }

    return $files;


}


/**
 * Returns the current microtime
 *
 * @return bool|string $date The current microtime
 *
 * @package Utils
 * @category Date
 * @link http://www.webdesign.org/web-programming/php/script-execution-time.8722.html#ixzz2QKEAC7PG
 */
function microtime_float()
{
    list ($msec, $sec) = explode(' ', microtime());
    $microtime = (float)$msec + (float)$sec;
    return $microtime;
}


/**
 * Returns a human readable filesize
 * @package Utils
 * @category Files
 * @author      wesman20 (php.net)
 * @author      Jonas John
 * @version     0.3
 * @link        http://www.jonasjohn.de/snippets/php/readable-filesize.htm
 */
function file_size_nice($size)
{
    // Adapted from: http://www.php.net/manual/en/function.filesize.php

    $mod = 1024;

    $units = explode(' ', 'B KB MB GB TB PB');
    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }

    return round($size, 2) . ' ' . $units[$i];
}


$ex_fields_static = array();
$_mw_real_table_names = array();
$_mw_assoc_table_names = array();


/**
 * Guess the cache group from a table name or a string
 *
 * @uses guess_table_name()
 * @param bool|string $for Your table name
 *
 *
 * @return string The cache group
 * @example
 * <code>
 * $cache_gr = guess_cache_group('content');
 * </code>
 *
 * @package Database
 * @subpackage Advanced
 */
function guess_cache_group($for = false)
{
    return guess_table_name($for, true);
}


function strip_tags_content($text, $tags = '', $invert = FALSE)
{

    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
    $tags = array_unique($tags[1]);

    if (is_array($tags) AND count($tags) > 0) {
        if ($invert == FALSE) {
            return preg_replace('@<(?!(?:' . implode('|', $tags) . ')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
        } else {
            return preg_replace('@<(' . implode('|', $tags) . ')\b.*?>.*?</\1>@si', '', $text);
        }
    } elseif ($invert == FALSE) {
        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    }
    return $text;
}

function html_cleanup($s, $tags = false)
{
    if (is_string($s) == true) {
        if ($tags != false) {
            $s = strip_tags_content($s, $tags, $invert = FALSE);
        }
        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

    } elseif (is_array($s) == true) {
        foreach ($s as $k => $v) {
            if (is_string($v) == true) {
                if ($tags != false) {
                    $v = strip_tags_content($v, $tags, $invert = FALSE);
                }
                $s[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
            } elseif (is_array($v) == true) {
                $s[$k] = html_cleanup($v, $tags);
            }
        }
    }
    return $s;
}
function in_live_edit()
{

    if (defined('IN_EDITOR_TOOLS') and IN_EDITOR_TOOLS != false) {
        return true;
     }


    $editmode_sess = mw()->user->session_get('editmode');

    if ($editmode_sess == true) {
     return true;
    }
}

function notif($sting, $class = 'success')
{
    return mw()->format->notif($sting, $class);
}

function lnotif($sting, $class = 'success')
{
    return mw()->format->lnotif($sting, $class);
}

function random_color()
{
    return "#" . sprintf("%02X%02X%02X", mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
}


function mw_error_handler($errno, $errstr, $errfile, $errline)
{


    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }

    switch ($errno) {
        case E_USER_ERROR:
            if (!headers_sent()) {
                header("Content-Type:text/plain");

            }
            echo "<b>ERROR</b> [$errno] $errstr<br />\n";
            echo "  Fatal error on line $errline in file $errfile";
            echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            print_r(debug_backtrace());
            echo "Aborting...<br />\n";
            exit(1);
            break;

        case E_USER_WARNING:
            echo "<b>WARNING</b> [$errno] $errstr<br />\n";
            break;

        case E_USER_NOTICE:
            // echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
            break;

        default:
            // echo "Unknown error type: [$errno] $errstr<br />\n";
            break;
    }

    /* Don't execute PHP internal error handler */
    return true;


}


if (!function_exists('params_stripslashes_array')) {

    function params_stripslashes_array($array)
    {
        return is_array($array) ? array_map('params_stripslashes_array', $array) : stripslashes($array);
    }
}

if (!function_exists('validator')) {

    function validator($data)
    {
        $validator = new \Microweber\Validator($data);
        return $validator;
    }
}

if (!function_exists('powered_by_link')) {
    function powered_by_link()
    {
        return mw('ui')->powered_by_link();

    }
}


function get_all_functions_files_for_modules($options = false)
{


    if (!defined("MW_IS_INSTALLED") or MW_IS_INSTALLED == false) {
        return false;
    }

    $args = func_get_args();
    $function_cache_id = '';

    $function_cache_id = serialize($options);

    $cache_id = $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

    $cache_group = 'modules/global';

    $cache_content = mw()->cache->get($cache_id, $cache_group);

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
    $installed = mw()->module->get('ui=any&installed=1');
    $configs = false;
    if (is_array($installed) and !empty($installed)) {
        $configs = array();
        foreach ($installed as $module) {
            if(isset($module['module'])){
            $file = normalize_path($dir_name . $module['module'] . DS . 'functions.php', false);
                if(is_file($file)){
                    $configs[] = $file;
                }
            }
        }
    }

    mw()->cache->save($configs, $function_cache_id, $cache_group);
    return $configs;

}


function template_dir()
{
    return mw()->template->dir();
}


function template_url()
{
    return mw()->template->url();

}


function template_name()
{

    return mw()->template->name();
}
function admin_head($script_src)
{
    return mw()->template->admin_head($script_src);
}

function template_head($script_src)
{
    return mw()->template->head($script_src);
}

function template_headers_src()
{
    return mw()->template->head(true);

}


api_expose('current_template_save_custom_css');
function current_template_save_custom_css($data)
{
    return mw()->layouts->template_save_css($data);

}

function mw_logo_svg()
{
    print '<svg version="1.1" class="mwlogo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 9.961 1.248" enable-background="new 0 0 9.961 1.248" xml:space="preserve"><g><path fill="none" d="M7.706,0.509c-0.193,0-0.268,0.227-0.268,0.387c0,0.109,0.066,0.19,0.18,0.19c0.186,0,0.265-0.222,0.265-0.377 C7.882,0.597,7.833,0.509,7.706,0.509z" /><path fill="none" d="M4.211,0.509c-0.182,0-0.262,0.223-0.262,0.375C3.95,0.99,4.011,1.086,4.128,1.086 c0.184,0,0.265-0.227,0.265-0.379C4.393,0.587,4.341,0.509,4.211,0.509z" /><path fill="none" d="M6.667,0.51c-0.14,0-0.209,0.09-0.246,0.191h0.413C6.834,0.608,6.812,0.51,6.667,0.51z" /><path fill="none" d="M8.732,0.51c-0.14,0-0.209,0.09-0.246,0.191h0.413C8.899,0.608,8.877,0.51,8.732,0.51z" /><polygon class="mwpolycolor" points="1.219,0.004 0.74,0.904 0.737,0.904 0.641,0.004 0.263,0.004 0,1.249 0.26,1.249 0.446,0.259 0.45,0.259 0.569,1.249 0.779,1.249 1.305,0.259 1.309,0.259 1.082,1.249 1.338,1.249 1.601,0.004 	" /><polygon class="mwpolycolor" points="1.566,1.249 1.815,1.249 2.007,0.347 1.758,0.347 	" /><polygon class="mwpolycolor" points="1.832,0.004 1.786,0.208 2.035,0.208 2.081,0.004 	" /><path class="mwpolycolor" d="M2.556,0.509c0.108,0,0.166,0.043,0.166,0.15l0.247,0C2.964,0.42,2.785,0.323,2.564,0.323 c-0.324,0-0.509,0.256-0.509,0.563c0,0.263,0.149,0.387,0.4,0.387c0.234,0,0.391-0.129,0.466-0.354H2.674 C2.646,1.007,2.583,1.086,2.474,1.086c-0.125,0-0.171-0.087-0.171-0.195C2.303,0.736,2.368,0.509,2.556,0.509z" /><path class="mwpolycolor" d="M3.764,0.328c-0.026-0.003-0.05-0.005-0.077-0.005c-0.132,0-0.254,0.065-0.31,0.189L3.373,0.51L3.41,0.347 H3.175L2.987,1.248h0.247l0.083-0.399c0.033-0.153,0.102-0.296,0.292-0.296c0.035,0,0.07,0.009,0.105,0.016L3.764,0.328z" /><path class="mwpolycolor" d="M4.221,0.323c-0.321,0-0.518,0.256-0.519,0.561c0,0.258,0.163,0.388,0.414,0.388 c0.328,0,0.524-0.251,0.524-0.567C4.64,0.445,4.468,0.323,4.221,0.323z M4.128,1.086c-0.117,0-0.178-0.096-0.179-0.203 c0-0.152,0.081-0.375,0.262-0.375c0.129,0,0.182,0.078,0.182,0.198C4.393,0.86,4.313,1.086,4.128,1.086z" /><polygon class="mwpolycolor" points="5.911,0.347 5.622,0.977 5.618,0.977 5.59,0.347 5.339,0.347 5.062,0.981 5.059,0.981 5.024,0.347  4.771,0.347 4.871,1.249 5.13,1.249 5.409,0.616 5.413,0.616 5.444,1.249 5.702,1.249 6.173,0.347 	" /><path class="mwpolycolor" d="M6.654,0.323c-0.307,0-0.498,0.268-0.498,0.556c0,0.255,0.161,0.393,0.401,0.393 c0.26,0,0.389-0.107,0.467-0.31H6.777C6.741,1.02,6.692,1.086,6.593,1.086c-0.134,0-0.199-0.072-0.199-0.176 c0-0.014,0-0.029,0.002-0.052v0h0.664c0.009-0.044,0.014-0.093,0.014-0.141C7.073,0.455,6.904,0.323,6.654,0.323z M6.422,0.701 C6.458,0.6,6.528,0.51,6.667,0.51c0.145,0,0.167,0.099,0.167,0.191H6.422z" /><path class="mwpolycolor" d="M7.805,0.323c-0.119,0-0.19,0.036-0.267,0.12H7.535l0.092-0.439H7.38L7.119,1.248h0.229l0.03-0.141H7.38 c0.043,0.125,0.158,0.165,0.283,0.165c0.306,0,0.467-0.287,0.467-0.566C8.129,0.498,8.033,0.323,7.805,0.323z M7.617,1.086 c-0.113,0-0.18-0.081-0.18-0.19c0-0.161,0.075-0.387,0.268-0.387c0.127,0,0.176,0.087,0.176,0.2 C7.882,0.865,7.803,1.086,7.617,1.086z" /><path class="mwpolycolor" d="M8.719,0.323c-0.307,0-0.497,0.268-0.497,0.556c0,0.255,0.16,0.393,0.401,0.393 c0.259,0,0.388-0.107,0.467-0.31H8.842C8.806,1.02,8.757,1.086,8.658,1.086c-0.134,0-0.199-0.072-0.199-0.176 c0-0.014,0-0.029,0.002-0.052v0h0.665c0.008-0.044,0.014-0.093,0.014-0.141C9.139,0.455,8.97,0.323,8.719,0.323z M8.487,0.701 C8.523,0.6,8.593,0.51,8.732,0.51c0.145,0,0.167,0.099,0.167,0.191H8.487z" /><path class="mwpolycolor" d="M9.884,0.323c-0.132,0-0.254,0.065-0.31,0.189L9.57,0.51l0.037-0.163H9.373L9.185,1.248h0.247l0.083-0.399 c0.033-0.153,0.102-0.296,0.292-0.296c0.035,0,0.07,0.009,0.105,0.016l0.05-0.24C9.935,0.325,9.911,0.323,9.884,0.323z"/></g></svg>
';
}


function load_web_component_file($filename)
{
    $components_dir = MW_WEB_COMPONENTS_DIR;
    $components_dir_shared = MW_WEB_COMPONENTS_SHARED_DIR;
    $load_file = false;
    $file = normalize_path($components_dir . $filename, false);
    if (is_file($file)) {
        $load_file = $file;
    } else {
        $file = normalize_path($components_dir_shared . $filename, false);
        if (is_file($file)) {
            $load_file = $file;
        }
    }
    if ($load_file != false) {
        return file_get_contents($load_file);
    }


}