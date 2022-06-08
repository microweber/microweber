<?php

/**
 *  Microweber common functions.
 */
api_expose_admin('reorder_modules');

function reorder_modules($data)
{
    return mw()->module_manager->reorder_modules($data);
}


api_expose_admin('get_modules_list_json', function () {
    return mw()->module_manager->get('installed=1&ui=1');

});

api_expose_admin('get_layouts_list_json', function () {
    return mw()->module_manager->templates('layouts');


});
api_expose_admin('get_elements_list_json', function () {
    return mw()->module_manager->scan_for_elements();

});

api_expose_admin('get_modules_and_elements_json', function ($params) {

    $modules_options = array();
    $modules_options['skip_admin'] = true;
    $modules_options['ui'] = true;
    $elements_ready = array();
    $modules_by_categories = array();
    $mod_obj_str = 'modules';
    $template_config = mw()->template->get_config();
    $show_grouped_by_cats = false;
    $show_layouts_grouped_by_cats = false;
    $show_group_elements_by_category = false;
    $hide_dynamic_layouts = false;
    $disable_elements = false;

    if (isset($template_config['elements_mode']) and $template_config['elements_mode'] == 'disabled') {
        $disable_elements = true;
    }

    if (isset($params['hide-dynamic']) and $params['hide-dynamic']) {
        $hide_dynamic_layouts = true;
    }

    if (isset($params['group_modules_by_category']) and $params['group_modules_by_category']) {
        $show_grouped_by_cats = true;
    }

    if (isset($params['group_layouts_by_category']) and $params['group_layouts_by_category']) {
        $show_layouts_grouped_by_cats = true;
    }

    if (isset($template_config['group_layouts_by_category']) and $template_config['group_layouts_by_category']) {
        $show_layouts_grouped_by_cats = true;
    }

    if (isset($template_config['group_elements_by_category']) and $template_config['group_elements_by_category']) {
        $show_group_elements_by_category = true;
    }

    if (isset($template_config['use_dynamic_layouts_for_posts']) and $template_config['use_dynamic_layouts_for_posts']) {
        $hide_dynamic_layouts = false;
    }


    $ready = [];
    $ready['config'] = $template_config;


    $mod_obj_str = 'elements';
    $el_params = array();
    if (isset($params['layout_type'])) {
        $el_params['layout_type'] = $params['layout_type'];
    }

    $elements_ready = mw()->layouts_manager->get($el_params);

    if ($elements_ready == false) {
        // scan_for_modules($modules_options);
        $el_params['no_cache'] = true;
        mw()->module_manager->scan_for_elements($el_params);
        $elements_ready = mw()->layouts_manager->get($el_params);
    }

    if ($elements_ready == false) {
        $elements_ready = array();
    }

    $elements_from_template = mw()->layouts_manager->get_elements_from_current_site_template();
    if (!empty($elements_from_template)) {
        $elements_ready = array_merge($elements_from_template, $elements_ready);
    }

    if ($disable_elements) {
        $elements_ready = array();
    }


    if ($disable_elements) {
        $elements_ready = array();
    }


    if ($elements_ready) {


    }

    $ready_elements = [];

    if ($show_group_elements_by_category) {
        $ready_elements['hasCategories'] = 1;
    }

    $ready_elements['data'] = $elements_ready;

    $ready['elements'] = $ready_elements;


    // $dynamic_layouts = mw()->layouts_manager->get_all('no_cache=1&get_dynamic_layouts=1');
    $dynamic_layouts = false;
    $module_layouts_skins = false;
    $dynamic_layouts = mw()->layouts_manager->get_all('no_cache=1&get_dynamic_layouts=1');
    $module_layouts_skins = mw()->module_manager->templates('layouts');
    if ($hide_dynamic_layouts) {
        $dynamic_layouts = false;
        $module_layouts_skins = false;
    }
    if (!$module_layouts_skins) {
        $module_layouts_skins = [];
    }

    if ($dynamic_layouts) {
        $module_layouts_skins = array_merge($module_layouts_skins, $module_layouts_skins);
    }


    $modules = mw()->module_manager->get('installed=1&ui=1');
    $module_layouts = mw()->module_manager->get('installed=1&module=layouts');
    $hide_from_display_list = array('layouts', 'template_settings');
    $sortout_el = array();
    $sortout_mod = array();
    if (!empty($modules)) {
        foreach ($modules as $mod) {
            if (isset($mod['as_element']) and intval($mod['as_element']) == 1) {
                $sortout_el[] = $mod;
            } else {
                $sortout_mod[] = $mod;
            }
        }
        $modules = array_merge($sortout_el, $sortout_mod);
        if ($modules and !empty($module_layouts)) {
            $modules = array_merge($modules, $module_layouts);
        }
    }

    $modules_from_template = mw()->module_manager->get_modules_from_current_site_template();
    if (!empty($modules_from_template)) {
        if (!is_array($modules)) {
            $modules = array();
        }
        foreach ($modules as $module) {
            foreach ($modules_from_template as $k => $module_from_template) {
                if (isset($module['name']) and isset($module_from_template['name'])) {
                    if ($module['name'] == $module_from_template['name']) {
                        unset($modules_from_template[$k]);
                    }
                }
            }
        }
        $modules = array_merge($modules, $modules_from_template);
    }

    $modules_ready = [];
    if ($show_grouped_by_cats) {
        $modules_ready['hasCategories'] = 1;
    }
    $modules_ready['data'] = $modules;


    $ready['modules'] = $modules_ready;

    $layouts_ready = [];
    if ($show_layouts_grouped_by_cats) {
        $layouts_ready['hasCategories'] = 1;
    }
    $layouts_ready['data'] = $module_layouts_skins;

    $ready['layouts'] = $layouts_ready;
    return response()->json($ready);
    //  return $ready;

});

/*
 *
 * Modules functions API
 *
 * @package        modules
 * @since          Version 0.1
 */

// ------------------------------------------------------------------------
if (!defined('EMPTY_MOD_STR')) {
    define('EMPTY_MOD_STR', "<div class='mw-empty-module '>{module_title} {type}</div>");
}
/**
 * module_templates.
 *
 * Gets all templates for a module
 *
 * @category       modules api
 */
function module_templates($module_name, $template_name = false, $is_settings = false)
{
    return mw()->module_manager->templates($module_name, $template_name, $is_settings);
}

/**
 * @desc      Get the template layouts info under the layouts subdir on your active template
 *
 * @param $options
 * $options ['type'] - 'layout' is the default type if you dont define any. You can define your own types as post/form, etc in the layout.txt file
 *
 * @return array
 *
 * @author    Microweber Dev Team
 *
 * @since     Version 1.0
 */
function site_templates($options = false)
{
    return mw()->template->site_templates($options);
}

function layouts_list($options = false)
{
    return mw()->layouts_manager->scan($options);
}

function get_layouts_from_db($options = false)
{
    return mw()->layouts_manager->get($options);
}

function get_modules_from_db($options = false)
{
    return mw()->module_manager->get($options);
}

function get_modules($options = false)
{
    return mw()->module_manager->get($options);
}

api_expose_admin('save_module_as_template');
function save_module_as_template($data_to_save)
{
    return mw()->module_manager->save_module_as_template($data_to_save);
}

/**
 * Function modules list from the db or them the disk.
 *
 * @return mixed Array with modules or false
 *
 * @param array $params
 *
 *
 * Example:
 * $params = array();
 * $params['dir_name'] = '/path/'; //get modules in dir
 * $params['skip_save'] = true; //if true skips module install
 * $params['skip_cache'] = true; // skip_cache
 *
 * $params['cache_group'] = 'modules/global'; // allows custom cache group
 * $params['cleanup_db'] = true; //if true will reinstall all modules if skip_save is false
 * $params['is_elements'] = true;  //if true will list files from the MW_ELEMENTS_DIR
 *
 * $data = scan_for_modules($params);
 */
function scan_for_modules($options = false)
{
    return mw()->module_manager->scan_for_modules($options);
}

function scan_for_elements($options = array())
{
    return mw()->module_manager->scan_for_elements($options);
}

function have_license($module_name = false)
{
    return mw()->module_manager->license($module_name);
}

function load_module($module_name, $attrs = array())
{
    return mw()->module_manager->load($module_name, $attrs);
}

function element_display($element_filename, $attrs = array())
{
    return mw()->layouts_manager->element_display($element_filename, $attrs);
}

function module_css_class($module_name)
{
    mw()->module_manager->css_class($module_name);
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

api_expose_admin('save_form_list');
function save_form_list($params)
{
    return mw()->forms_manager->save_list($params);
}

function system_config_get($key = false)
{
    return mw()->config($key);
}

api_expose_admin('delete_forms_list');
function delete_forms_list($data)
{
    return mw()->forms_manager->delete_list($data);
}

api_expose_admin('delete_form_entry');
function delete_form_entry($data)
{
    return mw()->forms_manager->delete_entry($data);
}

api_expose_admin('forms_list_export_to_excel');
function forms_list_export_to_excel($params)
{
    return mw()->forms_manager->export_to_excel($params);
}

function get_form_entires($params)
{
    return mw()->forms_manager->get_entires($params);
}

function get_form_lists($params)
{
    return mw()->forms_manager->get_lists($params);
}

//event_bind('mw_admin_settings_menu', 'mw_print_admin_backup_settings_link');

function mw_print_admin_backup_settings_link()
{
    if (mw()->module_manager->is_installed('admin/backup')) {
        $active = mw()->url_manager->param('view');
        $cls = '';
        $mname = module_name_encode('admin/backup/small');
        if ($active == $mname) {
            $cls = ' class="active" ';
        }
        $notif_html = '';
        $url = admin_url('view:modules/load_module:' . $mname);
        echo '<li><a class="item-' . $mname . '" href="#option_group=' . $mname . '">Backup</a></li>';
        //print "<li><a class=\"item-".$mname."\" href=\"".$url."\">Backup</a></li>";
    }

    if (mw()->module_manager->is_installed('admin/import')) {
        $active = mw()->url_manager->param('view');
        $cls = '';
        $mname = module_name_encode('admin/import');
        if ($active == $mname) {
            $cls = ' class="active" ';
        }
        $notif_html = '';
        $url = admin_url('view:modules/load_module:' . $mname);
        echo '<li><a class="item-' . $mname . '" href="#option_group=' . $mname . '">Import</a></li>';
        //print "<li><a class=\"item-".$mname."\" href=\"".$url."\">Backup</a></li>";
    }
}

function mw_post_update()
{
    $a = is_admin();
    if ($a != false or is_cli()) {
        $update = mw()->update->post_update();

        if (isset($_GET['redirect_to'])) {
            return app()->url_manager->redirect($_GET['redirect_to']);
        }

        return $update;
    }
}

function mw_reload_modules()
{

    $bootstrap_cached_folder = base_path('bootstrap/cache/');
    rmdir_recursive($bootstrap_cached_folder);

    mw()->module_manager->scan(['reload_modules' => 1, 'scan' => 1]);

    if (isset($_GET['redirect_to'])) {
        return app()->url_manager->redirect($_GET['redirect_to']);
    }
}

/* DEPRECATED */
/* DEPRECATED */
/* DEPRECATED */

api_expose_admin('mw_install_market_item');

function mw_install_market_item($params)
{
    $a = is_admin();
    if ($a != false) {
        return mw('update')->install_market_item($params);
    }
}

api_expose_admin('mw_apply_updates');

function mw_apply_updates($params)
{
    $update_api = mw('update');

    return $update_api->apply_updates($params);
}

api_expose_admin('mw_apply_updates_queue');
function mw_apply_updates_queue($params)
{
    $update_api = mw('update');

    return $update_api->apply_updates_queue($params);
}

api_expose_admin('mw_set_updates_queue');
function mw_set_updates_queue($params)
{
    $update_api = mw('update');

    return $update_api->set_updates_queue($params);
}


api_expose_admin('mw_delete_license');
function mw_delete_license($params)
{
    $update_api = mw('update');

    return $update_api->delete_license($params);
}

api_expose_admin('mw_save_license');
function mw_save_license($params)
{
    $update_api = mw('update');

    return $update_api->save_license($params);
}

api_expose_admin('mw_validate_licenses');
function mw_validate_licenses($params)
{
    $update_api = mw('update');

    return $update_api->validate_license($params);
}

api_expose_admin('mw_consume_license');
function mw_consume_license($params)
{
    $update_api = mw('update');

    return $update_api->consume_license($params);
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

/* END OF DEPRECATED */
/* END OF DEPRECATED */
/* END OF DEPRECATED */
/* END OF DEPRECATED */


api_expose_admin('mw_send_anonymous_server_data');
// function used do send us the language files
function mw_send_anonymous_server_data($params)
{
    must_have_access();
    $update_api = mw('update');

    if ($params != false) {
        $params = parse_params($params);
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
 * @category Arrays
 *
 * @author   Jonas John
 *
 * @version  0.2
 *
 * @link     http://www.jonasjohn.de/snippets/php/trim-array.htm
 *
 * @param array $Input
 *                     Input array
 *
 * @return array|string
 */
function array_trim($Input)
{
    if (!is_array($Input)) {
        return trim($Input);
    }

    return array_map('array_trim', $Input);
}

if (!function_exists('strleft')) {
    function strleft($s1, $s2)
    {
        return substr($s1, 0, strpos($s1, $s2));
    }
}

$ex_fields_static = array();
$_mw_real_table_names = array();
$_mw_assoc_table_names = array();

/**
 * Guess the cache group from a table name or a string.
 *
 * @uses       guess_table_name()
 *
 * @param bool|string $for Your table name
 *
 * @return string The cache group
 *
 * @example
 * <code>
 * $cache_gr = guess_cache_group('content');
 * </code>
 */
function guess_cache_group($for = false)
{
    return $for;
}

function strip_tags_content($text, $tags = '', $invert = false)
{
    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
    $tags = array_unique($tags[1]);

    if (is_array($tags) and count($tags) > 0) {
        if ($invert == false) {
            return preg_replace('@<(?!(?:' . implode('|', $tags) . ')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
        } else {
            return preg_replace('@<(' . implode('|', $tags) . ')\b.*?>.*?</\1>@si', '', $text);
        }
    } elseif ($invert == false) {
        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    }

    return $text;
}

function html_cleanup($s, $tags = false)
{
    if (is_string($s) == true) {
        if ($tags != false) {
            $s = strip_tags_content($s, $tags, $invert = false);
        }

        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    } elseif (is_array($s) == true) {
        foreach ($s as $k => $v) {
            if (is_string($v) == true) {
                if ($tags != false) {
                    $v = strip_tags_content($v, $tags, $invert = false);
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

    $editmode_sess = mw()->user_manager->session_get('editmode');

    if ($editmode_sess == true) {
        return true;
    }
}

function notif($text, $class = 'success')
{
    if ($class === true) {
        $to_print = '<div><div class="mw-notification-text mw-open-module-settings">';
        $to_print = $to_print . ($text) . '</div></div>';
    } else {
        $to_print = '<div class="mw-notification mw-' . $class . ' "><div class="mw-notification-text mw-open-module-settings">';
        $to_print = $to_print . $text . '</div></div>';
    }

    return $to_print;
}

function lnotif($text, $class = 'success')
{
    $editmode_sess = mw()->user_manager->session_get('editmode');


    if (defined('MW_BACKEND') and MW_BACKEND != false) {
        return false;
    }
    if (defined('IN_EDIT') and IN_EDIT != false) {
        $editmode_sess = true;
    }
    // if ($editmode_sess == false) {
    if (defined('IN_EDITOR_TOOLS') and IN_EDITOR_TOOLS != false) {
        $editmode_sess = true;
    }

//    if(!$editmode_sess){
//    $editmode_sess = is_live_edit();
//        }
    //}

    if ($editmode_sess == true) {
        return notif($text, $class);
    }
}

function random_color()
{
    return '#' . sprintf('%02X%02X%02X', mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
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
                header('Content-Type:text/plain');
            }
            echo "<b>ERROR</b> [$errno] $errstr<br />\n";
            echo "  Fatal error on line $errline in file $errfile";
            echo ', PHP ' . PHP_VERSION . ' (' . PHP_OS . ")<br />\n";
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


if (!function_exists('powered_by_link')) {
    function powered_by_link()
    {
        return mw('ui')->powered_by_link();
    }
}

function get_all_functions_files_for_modules($options = false)
{
    if (mw_is_installed() == false) {
        return false;
    }

    $args = func_get_args();
    $function_cache_id = '';

    $function_cache_id = serialize($options);

    $cache_id = $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

    $cache_group = 'modules/global';

    $cache_content = mw()->cache_manager->get($cache_id, $cache_group);

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
        $dir_name = normalize_path(modules_path());
    }
    $installed = mw()->module_manager->get('ui=any&installed=1');
    $configs = false;
    if (is_array($installed) and !empty($installed)) {
        $configs = array();
        foreach ($installed as $module) {
            if (isset($module['module'])) {
                $file = normalize_path($dir_name . $module['module'] . DS . 'functions.php', false);
                if (is_file($file)) {
                    $configs[] = $file;
                }
            }
        }
    }

    mw()->cache_manager->save($configs, $function_cache_id, $cache_group);

    return $configs;
}

function countries_list($param = false)
{
    return mw()->forms_manager->countries_list($param);
}

function template_dir($param = false)
{
    return mw()->template->dir($param);
}

function template_url($param = false)
{
    return mw()->template->url($param);
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

function template_foot($script_src)
{
    return mw()->template->foot($script_src);
}

function template_headers_src()
{
    return mw()->template->head(true);
}

function template_stack_add($src, $group = 'default')
{
    return mw()->template->stack_add($src, $group);
}

function template_stack_display($group = 'default')
{
    return mw()->template->stack_display($group);
}


api_expose_admin('current_template_save_custom_css');
function current_template_save_custom_css($data)
{
    return mw()->layouts_manager->template_save_css($data);
}

api_expose_admin('layouts/template_remove_custom_css', function ($params) {
    return mw()->layouts_manager->template_remove_custom_css($params);

});


function mw_logo_svg()
{
    echo '<svg version="1.1" class="mwlogo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 9.961 1.248" enable-background="new 0 0 9.961 1.248" xml:space="preserve"><g><path fill="none" d="M7.706,0.509c-0.193,0-0.268,0.227-0.268,0.387c0,0.109,0.066,0.19,0.18,0.19c0.186,0,0.265-0.222,0.265-0.377 C7.882,0.597,7.833,0.509,7.706,0.509z" /><path fill="none" d="M4.211,0.509c-0.182,0-0.262,0.223-0.262,0.375C3.95,0.99,4.011,1.086,4.128,1.086 c0.184,0,0.265-0.227,0.265-0.379C4.393,0.587,4.341,0.509,4.211,0.509z" /><path fill="none" d="M6.667,0.51c-0.14,0-0.209,0.09-0.246,0.191h0.413C6.834,0.608,6.812,0.51,6.667,0.51z" /><path fill="none" d="M8.732,0.51c-0.14,0-0.209,0.09-0.246,0.191h0.413C8.899,0.608,8.877,0.51,8.732,0.51z" /><polygon class="mwpolycolor" points="1.219,0.004 0.74,0.904 0.737,0.904 0.641,0.004 0.263,0.004 0,1.249 0.26,1.249 0.446,0.259 0.45,0.259 0.569,1.249 0.779,1.249 1.305,0.259 1.309,0.259 1.082,1.249 1.338,1.249 1.601,0.004 	" /><polygon class="mwpolycolor" points="1.566,1.249 1.815,1.249 2.007,0.347 1.758,0.347 	" /><polygon class="mwpolycolor" points="1.832,0.004 1.786,0.208 2.035,0.208 2.081,0.004 	" /><path class="mwpolycolor" d="M2.556,0.509c0.108,0,0.166,0.043,0.166,0.15l0.247,0C2.964,0.42,2.785,0.323,2.564,0.323 c-0.324,0-0.509,0.256-0.509,0.563c0,0.263,0.149,0.387,0.4,0.387c0.234,0,0.391-0.129,0.466-0.354H2.674 C2.646,1.007,2.583,1.086,2.474,1.086c-0.125,0-0.171-0.087-0.171-0.195C2.303,0.736,2.368,0.509,2.556,0.509z" /><path class="mwpolycolor" d="M3.764,0.328c-0.026-0.003-0.05-0.005-0.077-0.005c-0.132,0-0.254,0.065-0.31,0.189L3.373,0.51L3.41,0.347 H3.175L2.987,1.248h0.247l0.083-0.399c0.033-0.153,0.102-0.296,0.292-0.296c0.035,0,0.07,0.009,0.105,0.016L3.764,0.328z" /><path class="mwpolycolor" d="M4.221,0.323c-0.321,0-0.518,0.256-0.519,0.561c0,0.258,0.163,0.388,0.414,0.388 c0.328,0,0.524-0.251,0.524-0.567C4.64,0.445,4.468,0.323,4.221,0.323z M4.128,1.086c-0.117,0-0.178-0.096-0.179-0.203 c0-0.152,0.081-0.375,0.262-0.375c0.129,0,0.182,0.078,0.182,0.198C4.393,0.86,4.313,1.086,4.128,1.086z" /><polygon class="mwpolycolor" points="5.911,0.347 5.622,0.977 5.618,0.977 5.59,0.347 5.339,0.347 5.062,0.981 5.059,0.981 5.024,0.347  4.771,0.347 4.871,1.249 5.13,1.249 5.409,0.616 5.413,0.616 5.444,1.249 5.702,1.249 6.173,0.347 	" /><path class="mwpolycolor" d="M6.654,0.323c-0.307,0-0.498,0.268-0.498,0.556c0,0.255,0.161,0.393,0.401,0.393 c0.26,0,0.389-0.107,0.467-0.31H6.777C6.741,1.02,6.692,1.086,6.593,1.086c-0.134,0-0.199-0.072-0.199-0.176 c0-0.014,0-0.029,0.002-0.052v0h0.664c0.009-0.044,0.014-0.093,0.014-0.141C7.073,0.455,6.904,0.323,6.654,0.323z M6.422,0.701 C6.458,0.6,6.528,0.51,6.667,0.51c0.145,0,0.167,0.099,0.167,0.191H6.422z" /><path class="mwpolycolor" d="M7.805,0.323c-0.119,0-0.19,0.036-0.267,0.12H7.535l0.092-0.439H7.38L7.119,1.248h0.229l0.03-0.141H7.38 c0.043,0.125,0.158,0.165,0.283,0.165c0.306,0,0.467-0.287,0.467-0.566C8.129,0.498,8.033,0.323,7.805,0.323z M7.617,1.086 c-0.113,0-0.18-0.081-0.18-0.19c0-0.161,0.075-0.387,0.268-0.387c0.127,0,0.176,0.087,0.176,0.2 C7.882,0.865,7.803,1.086,7.617,1.086z" /><path class="mwpolycolor" d="M8.719,0.323c-0.307,0-0.497,0.268-0.497,0.556c0,0.255,0.16,0.393,0.401,0.393 c0.259,0,0.388-0.107,0.467-0.31H8.842C8.806,1.02,8.757,1.086,8.658,1.086c-0.134,0-0.199-0.072-0.199-0.176 c0-0.014,0-0.029,0.002-0.052v0h0.665c0.008-0.044,0.014-0.093,0.014-0.141C9.139,0.455,8.97,0.323,8.719,0.323z M8.487,0.701 C8.523,0.6,8.593,0.51,8.732,0.51c0.145,0,0.167,0.099,0.167,0.191H8.487z" /><path class="mwpolycolor" d="M9.884,0.323c-0.132,0-0.254,0.065-0.31,0.189L9.57,0.51l0.037-0.163H9.373L9.185,1.248h0.247l0.083-0.399 c0.033-0.153,0.102-0.296,0.292-0.296c0.035,0,0.07,0.009,0.105,0.016l0.05-0.24C9.935,0.325,9.911,0.323,9.884,0.323z"/></g></svg>
';
}

function load_web_component_file($filename)
{
    $components_dir = mw_includes_path() . 'components' . DS;
    $load_file = false;
    $file = normalize_path($components_dir . $filename, false);
    if (is_file($file)) {
        $load_file = $file;
    }
    if ($load_file != false) {
        return file_get_contents($load_file);
    }
}

api_expose_admin('system_log_reset');

function system_log_reset($data = false)
{
    return mw()->log_manager->reset();
}

api_expose_admin('delete_log_entry');

function delete_log_entry($data)
{
    return mw()->log_manager->delete_entry($data);
}

api_expose('captcha');
/**
 * Returns PNG Image.
 */
function captcha($params = false)
{
    return mw()->captcha_manager->render($params);
}

///**
// * Returns captcha URL
// */
//function captcha_url($params=false)
//{
//	return Microweber\Utils\Captcha::url($params);
//}

function mw_error($e, $f = false, $l = false)
{
    $f = mw_includes_path() . 'error.php';

    $v = new \MicroweberPackages\View\View($f);
    $v->e = $e;
    $v->f = $f;
    $v->l = $l;
    die($v);
}

api_expose_admin('mw_composer_save_package');
function mw_composer_save_package($params)
{
    $update_api = mw('update');

    return $update_api->composer_save_package($params);
}

api_expose_admin('mw_composer_run_update');
function mw_composer_run_update($params)
{
    $update_api = mw('update');

    return $update_api->composer_run($params);
}


api_expose('mw_composer_install_package_by_name');
function mw_composer_install_package_by_name($params)
{
    if (!mw_is_installed()) {

    } else {
        must_have_access();
    }

    $update_api = mw('update');

    return $update_api->composer_install_package_by_name($params);
}

api_expose_admin('mw_composer_replace_vendor_from_cache');
function mw_composer_replace_vendor_from_cache($params)
{
    $update_api = mw('update');

    return $update_api->composer_replace_vendor_from_cache($params);
}




if (!function_exists('br2nl')) {
    function br2nl($string)
    {
        return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
    }
}


if (!function_exists('titlelize')) {
    function titlelize($str)
    {
        return mw()->format->titlelize($str);
    }
}


function load_layout_block($block_name)
{
    $block_name = str_replace('..', '', $block_name);
    $inc = false;
    $file = template_dir() . DS . 'modules/layouts/blocks/' . $block_name . '.php';
    $file2 = modules_path() . DS . 'layouts/blocks/' . $block_name . '.php';
    if (is_file($file)) {
        $inc = $file;
    } else if (is_file($file2)) {
        $inc = $file2;
    }
    if ($inc) {
        return include($inc);
    }
}


/**
 * Shows a section of the help file.
 *
 * @internal its used on the help in the admin
 */
function show_help($section = 'main')
{
    $lang = current_lang();

    $lang = str_replace('..', '', $lang);
    if (trim($lang) == '') {
        $lang = 'en';
    }

    $lang_file = mw_includes_path() . 'help' . DIRECTORY_SEPARATOR . $lang . '.php';
    $lang_file_en = mw_includes_path() . 'help' . DIRECTORY_SEPARATOR . $lang . '.php';
    $lang_file = normalize_path($lang_file, false);

    if (is_file($lang_file)) {
        include $lang_file;
    } elseif (is_file($lang_file_en)) {
        return $lang_file_en;
    }
}


if (!function_exists('mb_trim')) {
    function mb_trim($string, $charlist = null)
    {
        if (is_null($charlist)) {
            return trim($string);
        } else {
            $charlist = str_replace('/', '\/', preg_quote($charlist));
            return preg_replace("/(^[$charlist]+)|([$charlist]+$)/us", '', $string);
        }
    }
}


function __ewchar_to_utf8($matches)
{
    $ewchar = $matches[1];
    $binwchar = hexdec($ewchar);
    $wchar = chr(($binwchar >> 8) & 0xFF) . chr(($binwchar) & 0xFF);

    return iconv('unicodebig', 'utf-8', $wchar);
}

function special_unicode_to_utf8($str)
{
    return preg_replace_callback("/\\\u([[:xdigit:]]{4})/i", '__ewchar_to_utf8', $str);
}


function get_date_format()
{
    return mw()->format->get_date_format();

}

function date_system_format($db_date)
{
    return mw()->format->date_system_format($db_date);

}

function get_date_db_format($str_date)
{
    return mw()->format->get_date_db_format($str_date);

}


/**
 * Find Date in a String
 *
 * @author   Etienne Tremel
 * @license  http://creativecommons.org/licenses/by/3.0/ CC by 3.0
 * @link     http://www.etiennetremel.net
 * @version  0.2.0
 *
 * @param string  find_date( ' some text 01/01/2012 some text' ) or find_date( ' some text October 5th 86 some text' )
 * @return mixed  false if no date found else array: array( 'day' => 01, 'month' => 01, 'year' => 2012 )
 */
function find_date($string)
{
    return mw()->format->find_date($string);

}

/**
 * Encode arbitrary data into base-62
 * Note that because base-62 encodes slightly less than 6 bits per character (actually 5.95419631038688), there is some wastage
 * In order to make this practical, we chunk in groups of up to 8 input chars, which give up to 11 output chars
 * with a wastage of up to 4 bits per chunk, so while the output is not quite as space efficient as a
 * true multiprecision conversion, it's orders of magnitude faster
 * Note that the output of this function is not compatible with that of a multiprecision conversion, but it's a practical encoding implementation
 * The encoding overhead tends towards 37.5% with this chunk size; bigger chunk sizes can be slightly more space efficient, but may be slower
 * Base-64 doesn't suffer this problem because it fits into exactly 6 bits, so it generates the same results as a multiprecision conversion
 * Requires PHP 5.3.2 and gmp 4.2.0
 * @param string $data Binary data to encode
 * @return string Base-62 encoded text (not chunked or split)
 */
if (!function_exists('base62_encode')) {
    function base62_encode($data)
    {
        $outstring = '';
        $l = strlen($data);
        for ($i = 0; $i < $l; $i += 8) {
            $chunk = substr($data, $i, 8);
            $outlen = ceil((strlen($chunk) * 8) / 6); //8bit/char in, 6bits/char out, round up
            $x = bin2hex($chunk);  //gmp won't convert from binary, so go via hex
            $w = gmp_strval(gmp_init(ltrim($x, '0'), 16), 62); //gmp doesn't like leading 0s
            $pad = str_pad($w, $outlen, '0', STR_PAD_LEFT);
            $outstring .= $pad;
        }
        return $outstring;
    }
}

/**
 * Decode base-62 encoded text into binary
 * Note that because base-62 encodes slightly less than 6 bits per character (actually 5.95419631038688), there is some wastage
 * In order to make this practical, we chunk in groups of up to 11 input chars, which give up to 8 output chars
 * with a wastage of up to 4 bits per chunk, so while the output is not quite as space efficient as a
 * true multiprecision conversion, it's orders of magnitude faster
 * Note that the input of this function is not compatible with that of a multiprecision conversion, but it's a practical encoding implementation
 * The encoding overhead tends towards 37.5% with this chunk size; bigger chunk sizes can be slightly more space efficient, but may be slower
 * Base-64 doesn't suffer this problem because it fits into exactly 6 bits, so it generates the same results as a multiprecision conversion
 * Requires PHP 5.3.2 and gmp 4.2.0
 * @param string $data Base-62 encoded text (not chunked or split)
 * @return string Decoded binary data
 */
if (!function_exists('base62_decode')) {
    function base62_decode($data)
    {
        $outstring = '';
        $l = strlen($data);
        for ($i = 0; $i < $l; $i += 11) {
            $chunk = substr($data, $i, 11);
            $outlen = floor((strlen($chunk) * 6) / 8); //6bit/char in, 8bits/char out, round down
            $y = gmp_strval(gmp_init(ltrim($chunk, '0'), 62), 16); //gmp doesn't like leading 0s
            $pad = str_pad($y, $outlen * 2, '0', STR_PAD_LEFT); //double output length as as we're going via hex (4bits/char)
            $outstring .= pack('H*', $pad); //same as hex2bin
        }
        return $outstring;
    }
}

if (!function_exists('hashClosure')) {
    function hashClosure($f)
    {
        $rf = new \ReflectionFunction($f);
        $pseudounique = $rf->getFileName() . $rf->getEndLine();
        return crc32($pseudounique);
    }
}


if (!function_exists('mergeScreenshotParts')) {
    function mergeScreenshotParts($files, $outputFilename = 'full-screenshot.png')
    {

        $targetHeight = 0;

        $allImageSizes = [];
        foreach ($files as $file) {
            $imageSize = getimagesize($file);
            $allImageSizes[] = [
                'file' => $file,
                'width' => $imageSize[0],
                'height' => $imageSize[1],
            ];
            $targetHeight += $imageSize[1];
        }

        $targetWidth = $allImageSizes[0]['width'];
        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

        $i = 0;
        foreach ($allImageSizes as $imageSize) {

            $mergeFile = imagecreatefrompng($imageSize['file']);

            $destinationY = 0;
            if ($i > 0) {
                $destinationY = $imageSize['height'] * $i;
            }

            imagecopymerge($targetImage, $mergeFile, 0, $destinationY, 0, 0, $imageSize['width'], $imageSize['height'], 100);
            imagedestroy($mergeFile);
            $i++;
        }

        imagepng($targetImage, $outputFilename, 8);
    }
}
