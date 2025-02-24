<?php

if (!function_exists('str_random')) {
    /**
     * If the given value is not an array, wrap it in one.
     *
     * @param mixed $value
     * @return array
     */
    function str_random($length = 16)
    {
        return \Illuminate\Support\Str::random($length);
    }
}
function mw_admin_prefix_url()
{
    return config('microweber.admin_url', 'admin');
}


function mw_admin_prefix_url_legacy()
{
    return config('microweber.admin_url_legacy', mw_admin_prefix_url() . '/legacy');
}

function mw_is_multisite(): bool
{
    if (defined('MW_IS_MULTISITE') and MW_IS_MULTISITE) {
        return true;
    }
    return false;
}

//
///**
// *  Microweber other functions.
// */

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
    return app()->template_manager->site_templates($options);
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
 * @return mixed Array with modules or false
 *
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
        $url = admin_url('module/view?type=' . $mname);
        echo '<li><a class="item-' . $mname . '" href="?group=' . $mname . '">Backup</a></li>';
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
        $url = admin_url('module/view?type=' . $mname);
        echo '<li><a class="item-' . $mname . '" href="?group=' . $mname . '">Restore</a></li>';
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
    mw_post_update();
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


if (!function_exists('strleft')) {
    function strleft($s1, $s2)
    {
        return substr($s1, 0, strpos($s1, $s2));
    }
}

$ex_fields_static = array();
$_mw_real_table_names = array();
$_mw_assoc_table_names = array();


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
        $link = mw('ui')->powered_by_link();
        if (trim(empty($link))) {
            return "Your Company";
        }
        return $link;
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


function template_dir($param = false)
{
    return app()->template_manager->dir($param);
}

function template_url($param = false)
{
    return app()->template_manager->url($param);
}

function template_name()
{
    return app()->template_manager->folder_name();
}

//
//api_expose_admin('current_template_save_custom_css');
//function current_template_save_custom_css($data)
//{
//    return mw()->layouts_manager->template_save_css($data);
//}
//
//api_expose_admin('layouts/template_remove_custom_css', function ($params) {
//    return mw()->layouts_manager->template_remove_custom_css($params);
//
//});


function mw_logo_svg()
{
    echo ' <svg fill="currentColor"version="1.1" class="mwlogo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 9.961 1.248" enable-background="new 0 0 9.961 1.248" xml:space="preserve"><g><path fill="none" d="M7.706,0.509c-0.193,0-0.268,0.227-0.268,0.387c0,0.109,0.066,0.19,0.18,0.19c0.186,0,0.265-0.222,0.265-0.377 C7.882,0.597,7.833,0.509,7.706,0.509z" /><path fill="none" d="M4.211,0.509c-0.182,0-0.262,0.223-0.262,0.375C3.95,0.99,4.011,1.086,4.128,1.086 c0.184,0,0.265-0.227,0.265-0.379C4.393,0.587,4.341,0.509,4.211,0.509z" /><path fill="none" d="M6.667,0.51c-0.14,0-0.209,0.09-0.246,0.191h0.413C6.834,0.608,6.812,0.51,6.667,0.51z" /><path fill="none" d="M8.732,0.51c-0.14,0-0.209,0.09-0.246,0.191h0.413C8.899,0.608,8.877,0.51,8.732,0.51z" /><polygon class="mwpolycolor" points="1.219,0.004 0.74,0.904 0.737,0.904 0.641,0.004 0.263,0.004 0,1.249 0.26,1.249 0.446,0.259 0.45,0.259 0.569,1.249 0.779,1.249 1.305,0.259 1.309,0.259 1.082,1.249 1.338,1.249 1.601,0.004 	" /><polygon class="mwpolycolor" points="1.566,1.249 1.815,1.249 2.007,0.347 1.758,0.347 	" /><polygon class="mwpolycolor" points="1.832,0.004 1.786,0.208 2.035,0.208 2.081,0.004 	" /><path class="mwpolycolor" d="M2.556,0.509c0.108,0,0.166,0.043,0.166,0.15l0.247,0C2.964,0.42,2.785,0.323,2.564,0.323 c-0.324,0-0.509,0.256-0.509,0.563c0,0.263,0.149,0.387,0.4,0.387c0.234,0,0.391-0.129,0.466-0.354H2.674 C2.646,1.007,2.583,1.086,2.474,1.086c-0.125,0-0.171-0.087-0.171-0.195C2.303,0.736,2.368,0.509,2.556,0.509z" /><path class="mwpolycolor" d="M3.764,0.328c-0.026-0.003-0.05-0.005-0.077-0.005c-0.132,0-0.254,0.065-0.31,0.189L3.373,0.51L3.41,0.347 H3.175L2.987,1.248h0.247l0.083-0.399c0.033-0.153,0.102-0.296,0.292-0.296c0.035,0,0.07,0.009,0.105,0.016L3.764,0.328z" /><path class="mwpolycolor" d="M4.221,0.323c-0.321,0-0.518,0.256-0.519,0.561c0,0.258,0.163,0.388,0.414,0.388 c0.328,0,0.524-0.251,0.524-0.567C4.64,0.445,4.468,0.323,4.221,0.323z M4.128,1.086c-0.117,0-0.178-0.096-0.179-0.203 c0-0.152,0.081-0.375,0.262-0.375c0.129,0,0.182,0.078,0.182,0.198C4.393,0.86,4.313,1.086,4.128,1.086z" /><polygon class="mwpolycolor" points="5.911,0.347 5.622,0.977 5.618,0.977 5.59,0.347 5.339,0.347 5.062,0.981 5.059,0.981 5.024,0.347  4.771,0.347 4.871,1.249 5.13,1.249 5.409,0.616 5.413,0.616 5.444,1.249 5.702,1.249 6.173,0.347 	" /><path class="mwpolycolor" d="M6.654,0.323c-0.307,0-0.498,0.268-0.498,0.556c0,0.255,0.161,0.393,0.401,0.393 c0.26,0,0.389-0.107,0.467-0.31H6.777C6.741,1.02,6.692,1.086,6.593,1.086c-0.134,0-0.199-0.072-0.199-0.176 c0-0.014,0-0.029,0.002-0.052v0h0.664c0.009-0.044,0.014-0.093,0.014-0.141C7.073,0.455,6.904,0.323,6.654,0.323z M6.422,0.701 C6.458,0.6,6.528,0.51,6.667,0.51c0.145,0,0.167,0.099,0.167,0.191H6.422z" /><path class="mwpolycolor" d="M7.805,0.323c-0.119,0-0.19,0.036-0.267,0.12H7.535l0.092-0.439H7.38L7.119,1.248h0.229l0.03-0.141H7.38 c0.043,0.125,0.158,0.165,0.283,0.165c0.306,0,0.467-0.287,0.467-0.566C8.129,0.498,8.033,0.323,7.805,0.323z M7.617,1.086 c-0.113,0-0.18-0.081-0.18-0.19c0-0.161,0.075-0.387,0.268-0.387c0.127,0,0.176,0.087,0.176,0.2 C7.882,0.865,7.803,1.086,7.617,1.086z" /><path class="mwpolycolor" d="M8.719,0.323c-0.307,0-0.497,0.268-0.497,0.556c0,0.255,0.16,0.393,0.401,0.393 c0.259,0,0.388-0.107,0.467-0.31H8.842C8.806,1.02,8.757,1.086,8.658,1.086c-0.134,0-0.199-0.072-0.199-0.176 c0-0.014,0-0.029,0.002-0.052v0h0.665c0.008-0.044,0.014-0.093,0.014-0.141C9.139,0.455,8.97,0.323,8.719,0.323z M8.487,0.701 C8.523,0.6,8.593,0.51,8.732,0.51c0.145,0,0.167,0.099,0.167,0.191H8.487z" /><path class="mwpolycolor" d="M9.884,0.323c-0.132,0-0.254,0.065-0.31,0.189L9.57,0.51l0.037-0.163H9.373L9.185,1.248h0.247l0.083-0.399 c0.033-0.153,0.102-0.296,0.292-0.296c0.035,0,0.07,0.009,0.105,0.016l0.05-0.24C9.935,0.325,9.911,0.323,9.884,0.323z"/></g></svg>
';
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


/**
 * Shows a section of the help file.
 *
 * @internal its used on the help in the admin
 */
function show_help($section = 'main')
{
    $lang = current_lang();

    $lang = sanitize_path($lang);
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


function get_date_format()
{
    return mw()->format->get_date_format();
}

function get_date_format_raw()
{
    return get_option('date_format', 'website');
}

function date_system_format($db_date)
{
    return mw()->format->date_system_format($db_date);

}

function get_date_db_format($str_date)
{
    return mw()->format->get_date_db_format($str_date);

}

if (!function_exists('get_class_protected_property_value')) {
    function get_class_protected_property_value($obj, $prop)
    {
        $refl = new ReflectionClass($obj);
        $reflBar = $refl->getProperty($prop);
        return $reflBar->getValue($obj);
    }
}

function mw_save_framework_config_file($params)
{
    $saveOnlyKeys = [
        'developer_mode',
        'force_https',
        'update_channel',
        'compile_assets',
    ];

    if (empty($params) or !is_admin()) {
        return;
    }

    $save_configs = array();
    foreach ($params as $k => $item) {
        if ($k != 'microweber') {
            continue;
        }
        if (is_array($item) and !empty($item)) {
            foreach ($item as $config_k => $config) {
                if (is_string($config_k)) {
                    if (is_numeric($config)) {
                        $config = intval($config);
                    }

                    if (in_array($config_k, $saveOnlyKeys)) {
                        \Illuminate\Support\Facades\Config::set($k . '.' . $config_k, $config);
                        $save_configs[] = $k;
                    }
                }
            }
        }
    }
    if (!empty($save_configs)) {
        \Illuminate\Support\Facades\Config::save($save_configs);
        return array('success' => 'Config is changed!');
    }
}


if (!function_exists('format_bytes')) {
    function format_bytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
