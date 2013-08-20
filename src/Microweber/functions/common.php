<?php

function get_content($params = false)
{

    return mw('content')->get($params);
}

api_expose('get_content_admin');

function get_content_admin($params)
{
    if (is_admin() == false) {
        return false;
    }

    return get_content($params);
}


/**
 * paging
 *
 * paging
 *
 * @access public
 * @category posts
 * @author Microweber
 * @link
 *
 * @param $params['num'] = 5; //the numer of pages
 * @internal param $display =
 *            'default' //sets the default paging display with <ul> and </li>
 *            tags. If $display = false, the function will return the paging
 *            array which is the same as $posts_pages_links in every template
 *
 * @return string - html string with ul/li
 */
function paging($params)
{
    return mw('content')->paging($params);

}
api_expose('content_link');


function content_link($id = false)
{

    return mw('content')->link($id);
}


function content_parents($id = 0, $without_main_parrent = false)
{
    return mw('content')->get_parents($id, $without_main_parrent);
}

function page_link($id = false)
{
    return mw('content')->link($id);
}

function post_link($id = false)
{
    return mw('content')->link($id);
}

function pages_tree($params = false)
{

    return mw('content')->pages_tree($params);
}



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


/**
 * Return array of posts specified by $params
 *
 * This function makes query in the database and returns data from the content table
 *
 * @param string|array|bool $params
 * @return string The url of the content
 * @package Content
 *
 * @uses get_content()
 * @example
 * <code>
 * //get array of posts
 * $content = get_posts('parent=5');
 *
 * if (!empty($content)) {
 *      foreach($content as $item){
 *       print $item['id'];
 *       print $item['title'];
 *       print mw('content')->link($item['id']);
 *      }
 * }
 * </code>
 *
 */
function get_posts($params = false)
{
    return mw('content')->get($params);
}




function api_url($str = '')
{
    $str = ltrim($str, '/');
    return site_url('api/' . $str);
}







/**
 * category_tree
 *
 * @desc prints category_tree of UL and LI
 * @access      public
 * @category    categories
 * @author      Microweber
 * @param $params = array();
 * @param  $params['parent'] = false; //parent id
 * @param  $params['link'] = false; // the link on for the <a href
 * @param  $params['active_ids'] = array(); //ids of active categories
 * @param  $params['active_code'] = false; //inserts this code for the active ids's
 * @param  $params['remove_ids'] = array(); //remove those caregory ids
 * @param  $params['ul_class_name'] = false; //class name for the ul
 * @param  $params['include_first'] = false; //if true it will include the main parent category
 * @param  $params['content_type'] = false; //if this is set it will include only categories from desired type
 * @param  $params['add_ids'] = array(); //if you send array of ids it will add them to the category
 * @param  $params['orderby'] = array(); //you can order by such array $params['orderby'] = array('created_on','asc');
 * @param  $params['content_type'] = false; //if this is set it will include only categories from desired type
 * @param  $params['list_tag'] = 'select';
 * @param  $params['list_item_tag'] = "option";
 *
 *
 */
function category_tree($params = false)
{

    return mw('category')->tree($params);
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
function url_segment($k = -1, $page_url = false) {
    return mw('url')->segment($k, $page_url);

}

/**
 * Returns the curent url path, does not include the domain name
 *
 * @return string the url string
 */
function url_path($skip_ajax = false) {
    return mw('url')->string($skip_ajax);
}

/**
 * Returns the curent url path, does not include the domain name
 *
 * @return string the url string
 */
function url_string($skip_ajax = false) {
    return mw('url')->string($skip_ajax);
}

function url_param($param, $skip_ajax = false)
{
    return mw('url')->param($param, $skip_ajax);
}


api_expose('save_edit');
function save_edit($post_data)
{
    return \mw('content')->save_edit($post_data);
}


/**
 * Function to save content into the content_table
 *
 * @param
 *            array
 *
 * @param
 *            boolean
 *
 * @return string | the id saved
 *
 *
 * @version 1.0
 *
 * @since Version 1.0
 *
 */
api_expose('save_content');

function save_content($data, $delete_the_cache = true)
{


    return \mw('content')->save_content($data, $delete_the_cache);

}


//api_expose('save_content_field');

function save_content_field($data, $delete_the_cache = true)
{

    return \mw('content')->save_content_field($data, $delete_the_cache);

}

api_expose('get_content_field_draft');
function get_content_field_draft($data)
{
    return \mw('content')->edit_field_draft($data);
}

function get_content_field($data, $debug = false)
{


    return mw('content')->edit_field($data, $debug);


}



function template_header($script_src)
{
    return mw('content')->template_header($script_src);
}

function template_headers_src()
{
    return mw('content')->template_header(true);

}







/**
 *
 * Shop module api
 *
 * @package        modules
 * @subpackage        shop
 * @since        Version 0.1
 */

// ------------------------------------------------------------------------


event_bind('mw_db_init_options', 'create_mw_shop_default_options');
function create_mw_shop_default_options()
{

    return mw('shop')->create_mw_shop_default_options();

}

event_bind('mw_admin_header_menu_start', 'mw_print_admin_menu_shop_btn');

function mw_print_admin_menu_shop_btn()
{
    $active = mw('url')->param('view');
    $cls = '';
    if ($active == 'shop') {
        $cls = ' class="active" ';
    }
    print '<li' . $cls . '><a href="' . admin_url() . 'view:shop">' . _e('Online Shop', true) . '</a></li>';
}

event_bind('mw_admin_dashboard_quick_link', 'mw_print_admin_dashboard_orders_btn');

function mw_print_admin_dashboard_orders_btn()
{
    $active = mw('url')->param('view');
    $cls = '';
    if ($active == 'shop') {
        $cls = ' class="active" ';
    }
    $notif_html = '';
    $notif_count = mw('Notifications')->get('module=shop&rel=cart_orders&is_read=n&count=1');
    if ($notif_count > 0) {
        $notif_html = '<sup class="mw-notif-bubble">' . $notif_count . '</sup>';
    }

    $ord_pending = get_orders('count=1&order_status=[null]&is_completed=y');
    $neword = '';
    if ($ord_pending > 0) {
        $neword = '<span class="icounter">' . $ord_pending . ' new</span>';
    }
    print '<li' . $cls . '><a href="' . admin_url() . 'view:shop/action:orders"><span class="ico iorder">' . $notif_html . '</span>' . $neword . '<span>View Orders</span></a></li>';
}

api_expose('update_order');
/**
 * update_order
 *
 * updates order by parameters
 *
 * @package        modules
 * @subpackage    shop
 * @subpackage    shop\orders
 * @category    shop module api
 */
function update_order($params = false)
{


    return mw('shop')->update_order($params);
}

api_expose('delete_client');

function delete_client($data)
{

    return mw('shop')->delete_client($data);

}

api_expose('delete_order');

function delete_order($data)
{

    return mw('shop')->delete_order($data);

}

function get_orders($params = false)
{

    return mw('shop')->get_orders($params);

}

function cart_sum($return_amount = true)
{
    return mw('shop')->cart_sum($return_amount);
}


api_expose('checkout_ipn');

function checkout_ipn($data)
{
    return mw('shop')->checkout_ipn($data);
}

api_expose('checkout');

function checkout($data)
{
    return mw('shop')->checkout($data);
}



api_expose('checkout_confirm_email_test');
function checkout_confirm_email_test($params)
{


    return mw('shop')->checkout_confirm_email_test($params);
}

api_expose('update_cart');

function update_cart($data)
{
    return mw('shop')->update_cart($data);


}

api_expose('update_cart_item_qty');

function update_cart_item_qty($data)
{

    return mw('shop')->update_cart_item_qty($data);
}

api_expose('remove_cart_item');

function remove_cart_item($data)
{

    return mw('shop')->remove_cart_item($data);
}

function get_cart($params)
{

    return mw('shop')->get_cart($params);


}

function payment_options($option_key = false)
{
    return mw('shop')->payment_options($option_key);


}

function session_set($name, $val)
{


    return mw('user')->session_set($name, $val);
}

function session_get($name)
{
    return mw('user')->session_get($name);

}

function session_del($name)
{
    return mw('user')->session_del($name);
}

function session_end()
{


    return mw('user')->session_end();

}

function currency_format($amount, $curr = false)
{

    return mw('shop')->currency_format($amount, $curr);


}
api_expose('user_login');
function user_login($params)
{
    return mw('user')->login($params);
}

function api_login($api_key = false)
{

    return mw('user')->api_login($api_key);

}

api_expose('user_social_login');
function user_social_login($params)
{
    return mw('user')->social_login($params);
}


api_expose('logout');

function logout()
{

    return mw('user')->logout();

}

//api_expose('user_register');
api_expose('user_register');

function user_register($params)
{


    return mw('user')->register($params);


}

api_expose('save_user');

/**
 * Allows you to save users in the database
 *
 * By default it have security rules.
 *
 * If you are admin you can save any user in the system;
 *
 * However if you are regular user you must post param id with the current user id;
 *
 * @param $params
 * @param  $params['id'] = $user_id; // REQUIRED , you must set the user id.
 * For security reasons, to make new user please use user_register() function that requires captcha
 * or write your own save_user wrapper function that sets  mw_var('force_save_user',true);
 * and pass its params to save_user();
 *
 *
 * @param  $params['is_active'] = 'y'; //default is 'n'
 * @usage
 *
 * $upd = array();
 * $upd['id'] = 1;
 * $upd['email'] = $params['new_email'];
 * $upd['password'] = $params['passwordhash'];
 * mw_var('force_save_user', false|true); // if true you want to make new user or foce save ... skips id check and is admin check
 * mw_var('save_user_no_pass_hash', false|true); //if true skips pass hash function and saves password it as is in the request, please hash the password before that or ensure its hashed
 * $s = save_user($upd);
 *
 *
 *
 *
 *
 * @return bool|int
 */
function save_user($params)
{
    return mw('user')->save($params);
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


api_expose('delete_user');

function delete_user($data)
{
    return mw('user')->save($data);
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


    return mw('cache')->get($cache_id, $cache_group, $expiration_in_seconds);


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
    return mw('cache')->save($data_to_cache, $cache_id, $cache_group);


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
    return mw('cache')->clear();

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
    return mw('cache')->debug();

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

    return mw('cache')->delete($cache_group, $cache_storage_type);


}


api_expose('social_login_process');
function social_login_process()
{
    return mw('user')->social_login_process();


}


api_expose('user_reset_password_from_link');
function user_reset_password_from_link($params)
{
    return mw('user')->reset_password_from_link($params);

}

api_expose('user_send_forgot_password');
function user_send_forgot_password($params)
{

    return mw('user')->send_forgot_password($params);


}


api_expose('is_logged');

function is_logged()
{

    if (defined('USER_ID')) {
        // print USER_ID;
        return USER_ID;
    } else {

        $user_session = $_SESSION;
        if ($user_session == FALSE) {


            return false;
        } else {
            if (isset($user_session['user_session'])) {
                $user_session = $user_session['user_session'];
            }

        }
        $res = false;
        if (isset($user_session['user_id'])) {
            $res = $user_session['user_id'];
        }

        if ($res != false) {
            // $res = $sess->get ( 'user_id' );
            define("USER_ID", $res);
        }

        return $res;
    }


}


function user_id()
{

    return mw('user')->id();
}

function has_access($function_name)
{

    return mw('user')->has_access($function_name);
}

function admin_access()
{
    return mw('user')->admin_access();

}

function only_admin_access()
{
    return mw('user')->admin_access();

}

function is_admin()
{

    return mw('user')->is_admin();
}

/**
 * @function user_name
 * gets the user's FULL name
 *
 * @param $user_id  the id of the user. If false it will use the curent user (you)
 * @param string $mode full|first|last|username
 *  'full' //prints full name (first +last)
 *  'first' //prints first name
 *  'last' //prints last name
 *  'username' //prints username
 * @return string
 */
function user_name($user_id = false, $mode = 'full')
{
    return mw('user')->name($user_id, $mode);
}

/**
 * @function get_users
 *
 * @param $params array|string;
 * @params $params['username'] string username for user
 * @params $params['email'] string email for user
 * @params $params['password'] string password for user
 *
 *
 * @usage get_users('email=my_email');
 *
 *
 * @return array of users;
 */
function get_users($params)
{
    return mw('user')->get_all($params);
}

/**
 * get_user
 *
 * get_user get the user info from the DB
 *
 * @access public
 * @category users
 * @author Microweber
 * @link http://microweber.com
 * @param $id =
 *            the id of the user;
 * @return array
 */
function get_user($id = false)
{
    return mw('user')->get($id);

}


function make_default_custom_fields($rel, $rel_id, $fields_csv_str)
{
    return mw('fields')->make_default($rel, $rel_id, $fields_csv_str);
}

api_expose('make_custom_field');
function make_custom_field($field_id = 0, $field_type = 'text', $settings = false)
{
    return mw('fields')->make_field($field_id, $field_type, $settings);
}

api_expose('save_custom_field');

function save_custom_field($data)
{


    return mw('fields')->save($data);
}

api_expose('reorder_custom_fields');

function reorder_custom_fields($data)
{
    return mw('fields')->reorder($data);
}

api_expose('remove_field');

function remove_field($id)
{
    return mw('fields')->delete($id);
}

/**
 * make_field
 *
 * @desc make_field
 * @access      public
 * @category    forms
 * @author      Microweber
 * @link        http://microweber.com
 * @param string $field_type
 * @param string $field_id
 * @param array $settings
 */
function make_field($field_id = 0, $field_type = 'text', $settings = false)
{
    return mw('fields')->make($field_id, $field_type, $settings);

}


api_expose('save_category');

function save_category($data, $preserve_cache = false)
{
    return \mw('category')->save($data, $preserve_cache);


}

function get_categories($data)
{

    return \mw('category')->get($data);

}


api_expose('delete_category');

function delete_category($data)
{

    return \mw('category')->delete($data);

}


api_expose('reorder_categories');

function reorder_categories($data)
{

    return \mw('category')->reorder($data);

}

function get_categories_for_content($content_id, $data_type = 'categories')
{
    if (intval($content_id) == 0) {

        return false;
    }

    return \mw('category')->get_for_content($content_id, $data_type);
}

function category_link($id)
{

    if (intval($id) == 0) {

        return false;
    }

    return \mw('category')->link($id);

}

/**
 * @desc Get a single row from the categories_table by given ID and returns it as one dimensional array
 * @param int
 * @return array
 * @author      Peter Ivanov
 * @version 1.0
 * @since Version 1.0
 */
function get_category_by_id($id = 0)
{
    return \mw('category')->get_by_id($id);

}

function get_category_children($parent_id = 0, $type = false, $visible_on_frontend = false)
{

    return \mw('category')->get_children($parent_id, $type, $visible_on_frontend);
}

function get_page_for_category($category_id)
{
    return \mw('category')->get_page($category_id);


}


event_bind('mw_edit_page_admin_menus', 'mw_print_admin_menu_selector');

function mw_print_admin_menu_selector($params = false)
{
    //d($params);
    $add = '';
    if (isset($params['id'])) {
        $add = '&content_id=' . $params['id'];
    }
    print module('view=edit_page_menus&type=menu' . $add);
}

function get_content_by_id($params = false)
{
    return mw('content')->get_by_id($params);
}

function get_user_by_id($params = false)
{
    return mw('user')->get_by_id($params);
}


function get_menu($params = false)
{

    return mw('content')->get_menu($params);

}

api_expose('add_new_menu');
function add_new_menu($data_to_save)
{
    return \mw('content')->menu_create($data_to_save);

}

api_expose('menu_delete');
function menu_delete($id = false)
{
    return \mw('content')->menu_delete($id);

}

api_expose('delete_menu_item');
function delete_menu_item($id)
{
 
    return \mw('content')->menu_item_delete($id);

}

function get_menu_item($id)
{

    return \mw('content')->menu_item_get($id);

}

api_expose('edit_menu_item');
function edit_menu_item($data_to_save)
{
    return \mw('content')->menu_item_save($data_to_save);


}

api_expose('reorder_menu_items');

function reorder_menu_items($data)
{

    return \mw('content')->menu_items_reorder($data);

}

function menu_tree($menu_id, $maxdepth = false)
{
    return mw('content')->menu_tree($menu_id, $maxdepth);


}

function is_in_menu($menu_id = false, $content_id = false)
{
    return \mw('content')->is_in_menu($menu_id, $content_id);

}

api_hook('save_content', 'add_content_to_menu');

function add_content_to_menu($content_id, $menu_id = false)
{
    return \mw('content')->add_content_to_menu($content_id, $menu_id);


}


api_expose('reorder_modules');

function reorder_modules($data)
{

    return \mw('module')->reorder_modules($data);
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
    return mw('module')->templates($module_name, $template_name);

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

    return mw('content')->site_templates($options);
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
                $v1 = mw('format')->array_to_base64($v);
                $tags .= "{$k}=\"$v1\" ";
            } else {

                $em = str_ireplace("{" . $k . "}", $v, $em);

                $tags .= "{$k}=\"$v\" ";
            }
        }
    }

    //$tags = "<div class='module' {$tags} data-type='{$module_name}'  data-view='empty'>" . $em . "</div>";

    $res = mw('module')->load($module_name, $params);
    if (is_array($res)) {
        // $res['edit'] = $tags;
    }

    if (isset($params['wrap']) or isset($params['data-wrap'])) {
        $module_cl = module_css_class($module_name);
        $res = "<div class='module {$module_cl}' {$tags} data-type='{$module_name}'>" . $res . "</div>";
    }

    return $res;
}













$_mw_modules_info_register = array();
function module_info($module_name)
{
    return mw('module')->info($module_name);

}

function is_module($module_name)
{
    return mw('module')->exists($module_name);
}


function module_dir($module_name)
{
    return mw('module')->dir($module_name);

}

function module_url($module_name)
{
    return mw('module')->url($module_name);

}

function locate_module($module_name, $custom_view = false, $no_fallback_to_view = false)
{

    return mw('module')->locate($module_name, $custom_view, $no_fallback_to_view);
}

api_expose('uninstall_module');

function uninstall_module($params)
{

    if (is_admin() == false) {
        return false;
    }
    return \mw('module')->uninstall($params);

}

event_bind('mw_db_init_modules', 're_init_modules_db');

function re_init_modules_db()
{

    //return mw('module')->update_db();

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

    return \mw('module')->install($params);

}

function save_module_to_db($data_to_save)
{
    return \mw('module')->save($data_to_save);

}

function get_saved_modules_as_template($params)
{
    return \mw('module')->get_saved_modules_as_template($params);
}

api_expose('delete_module_as_template');
function delete_module_as_template($data)
{

    return \mw('module')->delete_module_as_template($data);


}

api_expose('save_module_as_template');
function save_module_as_template($data_to_save)
{

    return \mw('module')->save_module_as_template($data_to_save);
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

$data = modules_list($params);

 */

function modules_list($options = false)
{

    return \mw('module')->scan_for_modules($options);
}

event_bind('mw_scan_for_modules', 'scan_for_modules');
function scan_for_modules($options = false)
{
    return \mw('module')->scan_for_modules($options);

}

event_bind('mw_scan_for_modules', 'get_elements');

function get_elements($options = array())
{
    return \mw('module')->get_layouts($options);


}


function load_module_lic($module_name = false)
{
    mw('module')->license($module_name);
}


function load_module($module_name, $attrs = array())
{

    return mw('module')->load($module_name, $attrs);

}


function module_css_class($module_name)
{
    mw('module')->css_class($module_name);
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
    return mw('Forms')->save_list($params);

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

    if (mw('module')->is_installed('admin/backup')) {

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
    //$notif_count = mw('Notifications')->get('module=comments&is_read=n&count=1');
    /*if ($notif_count > 0) {
        $notif_html = '<sup class="mw-notif-bubble">' . $notif_count . '</sup>';
    }*/
    //print '<li' . $cls . '><a href="' . admin_url() . 'view:comments"><span class="ico icomment">' . $notif_html . '</span><span>Comments</span></a></li>';
}


api_expose('mw_post_update');
function mw_post_update()
{

    $a = is_admin();
    if ($a != false) {
        mw('cache')->delete('db');
        mw('cache')->delete('update/global');
        mw('cache')->delete('elements/global');

        mw('cache')->delete('templates');
        mw('cache')->delete('modules/global');
        scan_for_modules();
        get_elements();
        event_trigger('mw_db_init_default');
        event_trigger('mw_db_init_modules');
        event_trigger('mw_db_init');

    }

}


api_expose('mw_apply_updates');

function mw_apply_updates($params)
{
    only_admin_access();
    $params = parse_params($params);
    $update_api = mw('update');
    $res = array();
    $upd_params = array();
    if (is_array($params)) {
        foreach ($params as $param_k => $param) {
            if ($param_k == 'mw_version') {
                $upd_params['mw_version'] = $param_k;
            }

            if ($param_k == 'elements') {
                $upd_params['elements'] = $param;
            }

            if ($param_k == 'modules') {
                $upd_params['modules'] = $param;
            }
            if ($param_k == 'module_templates') {
                $upd_params['module_templates'] = $param;
            }

            if (isset($upd_params['mw_version'])) {
                $res[] = $update_api->install_version($upd_params['mw_version']);

            }
            if (isset($upd_params['elements']) and is_array($upd_params['elements'])) {
                foreach ($param['elements'] as $item) {
                    $res[] = $update_api->install_element($item);
                }
            }
            if (isset($upd_params['modules']) and is_array($upd_params['modules'])) {
                foreach ($param['modules'] as $item) {
                    $res[] = $update_api->install_module($item);
                }
            }
            if (isset($upd_params['module_templates']) and is_array($upd_params['module_templates'])) {
                foreach ($upd_params['module_templates'] as $k => $item) {
                    if (is_array($item)) {
                        foreach ($item as $layout_file) {
                            $res[] = $update_api->install_module_template($k, $layout_file);

                        }

                    } elseif (is_string($item)) {
                        $res[] = $update_api->install_module_template($k, $item);
                    }
                }
            }

        }

        if (is_array($res)) {
            mw_post_update();
            mw('Notifications')->delete_for_module('updates');

        }
    }
    return $res;

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


event_bind('rte_image_editor_image_search', 'mw_print_rte_image_editor_image_search');

function mw_print_rte_image_editor_image_search()
{
    $active = mw('url')->param('view');
    $cls = '';
    if ($active == 'shop') {
        $cls = ' class="active" ';
    }
    print '<module type="files/admin" />';
}


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


function db_escape_string($value)
{
    global $mw_escaped_strings;
    if (isset($mw_escaped_strings[$value])) {
        return $mw_escaped_strings[$value];
    }

    $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
    $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
    $new = str_replace($search, $replace, $value);
    $mw_escaped_strings[$value] = $new;
    return $new;
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
            //mw('cache')->save('true', $c_id, 'db');

            // $installed = array();
//            $installed['option_group'] = ('mw_system');
//            $installed['option_key'] = ('is_installed');
//            $installed['option_value'] = 'yes';
//            mw('option')->save_static($installed);

        }

        //event_trigger('mw_cron');
    }
}


/**
 *
 * Settings module api
 *
 * @package     modules
 * @subpackage      settings
 * @since       Version 0.1
 */

// ------------------------------------------------------------------------
event_bind('mw_admin_header_menu', 'mw_print_admin_menu_settings_btn');

function mw_print_admin_menu_settings_btn()
{
    $active = mw('url')->param('view');
    $cls = '';
    if ($active == 'settings') {
        $cls = ' class="active" ';
    }
    print '<li' . $cls . '><a href="' . admin_url() . 'view:settings">' . _e("Settings", true) . '</a></li>';
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

            if (is_array($temp) and is_array($files)) {
                $files = array_merge($files, $temp);
            } else if (is_array($temp)) {
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
$mw_escaped_strings = array();
function save($table, $data)
{
    return mw('db')->save($table, $data);

}

function get($params)
{
    return mw('db')->get($params);

}

function db_build_table($table_name, $fields_to_add, $column_for_not_drop = array())
{

    return mw('db')->build_table($table_name, $fields_to_add, $column_for_not_drop);

}

function db_get_table_name($assoc_name)
{

    $assoc_name = str_ireplace('table_', MW_TABLE_PREFIX, $assoc_name);
    return $assoc_name;
}


$_mw_db_get_real_table_names = array();
function db_get_real_table_name($assoc_name)
{
    global $_mw_db_get_real_table_names;

    if (isset($_mw_db_get_real_table_names[$assoc_name])) {

        return $_mw_db_get_real_table_names[$assoc_name];
    }


    $assoc_name_new = str_ireplace('table_', MW_TABLE_PREFIX, $assoc_name);
    if (defined('MW_TABLE_PREFIX') and MW_TABLE_PREFIX != '' and stristr($assoc_name_new, MW_TABLE_PREFIX) == false) {
        $assoc_name_new = MW_TABLE_PREFIX . $assoc_name_new;
    }
    $_mw_db_get_real_table_names[$assoc_name] = $assoc_name_new;
    return $assoc_name_new;
}


/**
 * Get Relative table name from a string
 *
 * @package Database
 * @subpackage Advanced
 * @param string $for string Your table name
 *
 * @param bool $guess_cache_group If true, returns the cache group instead of the table name
 *
 * @return bool|string
 * @example
 * <code>
 * $table = guess_table_name('content');
 * </code>
 */
function guess_table_name($for, $guess_cache_group = false)
{

    if (stristr($for, 'table_') == false) {
        switch ($for) {
            case 'user' :
            case 'users' :
                $rel = 'users';
                break;

            case 'media' :
            case 'picture' :
            case 'video' :
            case 'file' :
                $rel = 'media';
                break;

            case 'comment' :
            case 'comments' :
                $rel = 'comments';
                break;

            case 'module' :
            case 'modules' :
            case 'modules' :
            case 'modul' :
                $rel = 'modules';
                break;

            case 'category' :
            case 'categories' :
            case 'cat' :
            case 'categories' :
            case 'tag' :
            case 'tags' :
                $rel = 'categories';
                break;

            case 'category_items' :
            case 'cat_items' :
            case 'tag_items' :
            case 'tags_items' :
                $rel = 'categories_items';
                break;

            case 'post' :
            case 'page' :
            case 'content' :

            default :
                $rel = $for;
                break;
        }
        $for = $rel;
    }
    if (defined('MW_TABLE_PREFIX') and MW_TABLE_PREFIX != '' and stristr($for, MW_TABLE_PREFIX) == false) {
        //$for = MW_TABLE_PREFIX.$for;
    } else {

    }
    if ($guess_cache_group != false) {

        $for = str_replace('table_', '', $for);
        $for = str_replace(MW_TABLE_PREFIX, '', $for);
    }

    return $for;
}

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