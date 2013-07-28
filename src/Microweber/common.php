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




api_expose('content_link');


api_expose('pixum_img');


function page_link($id = false)
{
    return mw('content')->link($id);
}

function post_link($id = 0)
{
    return mw('content')->link($id);
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





/**
 * Defines all constants that are needed to parse the page layout
 *
 * It accepts array or $content that must have  $content['id'] set
 *
 * @example
 * <code>
 *  Define constants for some page
 *  $ref_page = mw('content')->get_by_id(1);
 *  define_constants($ref_page);
 *  print PAGE_ID;
 *  print POST_ID;
 *  print CATEGORY_ID;
 *  print MAIN_PAGE_ID;
 *  print DEFAULT_TEMPLATE_DIR;
 *  print DEFAULT_TEMPLATE_URL;
 * </code>
 *
 * @package Content
 * @subpackage Advanced
 * @const  PAGE_ID Defines the current page id
 * @const  POST_ID Defines the current post id
 * @const  CATEGORY_ID Defines the current category id if any
 * @const  ACTIVE_PAGE_ID Same as PAGE_ID
 * @const  CONTENT_ID current post or page id
 * @const  MAIN_PAGE_ID the parent page id
 * @const DEFAULT_TEMPLATE_DIR the directory of the site's default template
 * @const DEFAULT_TEMPLATE_URL the url of the site's default template
 */
function define_constants($content = false)
{

    return mw('content')->define_constants($content);
}


/**
 * Return the path to the layout file that will render the page
 *
 * It accepts array $page that must have  $page['id'] set
 *
 * @example
 * <code>
 *  //get the layout file for content
 *  $content = mw('content')->get_by_id($id=1);
 *  $render_file = get_layout_for_page($content);
 *  var_dump($render_file ); //print full path to the layout file ex. /home/user/public_html/userfiles/templates/default/index.php
 * </code>
 * @package Content
 * @subpackage Advanced
 */
function get_layout_for_page($page = array())
{
    return mw('content')->get_layout($page);

}


/**
 * Returns the homepage as array
 *
 * @category Content
 * @package Content
 */
function get_homepage()
{

    return mw('content')->homepage();
}






api_expose('reorder_content');
function reorder_content($params)
{


    return \mw('Microweber\ContentUtils')->reorder($params);



}









api_expose('save_edit');
function save_edit($post_data)
{
    return \mw('Microweber\ContentUtils')->save_edit($post_data);
}

api_expose('delete_content');

function delete_content($data)
{

    return \mw('Microweber\ContentUtils')->delete($data);

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


    return \mw('Microweber\ContentUtils')->save_content($data, $delete_the_cache);

}


//api_expose('save_content_field');

function save_content_field($data, $delete_the_cache = true)
{

    return \mw('Microweber\ContentUtils')->save_content_field($data, $delete_the_cache);

}

api_expose('get_content_field_draft');
function get_content_field_draft($data)
{
    return \mw('Microweber\ContentUtils')->edit_field_draft($data);
}

function get_content_field($data, $debug = false)
{


    return mw('content')->edit_field($data,$debug);


}




api_expose('content_set_published');


/**
 * Set content to be published
 *
 * Set is_active flag 'y'
 *
 * @param string|array|bool $params
 * @return string The url of the content
 * @package Content
 * @subpackage Advanced
 *
 * @uses save_content()
 * @see content_set_unpublished()
 * @example
 * <code>
 * //set published the content with id 5
 * content_set_published(5);
 *
 * //alternative way
 * content_set_published(array('id' => 5));
 * </code>
 *
 */
function content_set_published($params)
{

    return \mw('Microweber\ContentUtils')->set_published($params);

}

api_expose('content_set_unpublished');
/**
 * Set content to be unpublished
 *
 * Set is_active flag 'n'
 *
 * @param string|array|bool $params
 * @return string The url of the content
 * @package Content
 * @subpackage Advanced
 *
 * @uses save_content()
 * @see content_set_unpublished()
 * @example
 * <code>
 * //set published the content with id 5
 * content_set_unpublished(5);
 *
 * //alternative way
 * content_set_unpublished(array('id' => 5));
 * </code>
 *
 */
function content_set_unpublished($params)
{

    return \mw('Microweber\ContentUtils')->set_unpublished($params);

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
 * @desc  Get the template layouts info under the layouts subdir on your active template
 * @param $options
 * $options ['type'] - 'layout' is the default type if you dont define any. You can define your own types as post/form, etc in the layout.txt file
 * @return array
 * @author    Microweber Dev Team
 * @since Version 1.0
 */
function templates_list($options = false)
{
    return \mw('Microweber\ContentUtils')->templates_list($options);

}

function layout_link($options = false)
{
    return \Microweber\Layouts::get_link($options);
}


/**
 * Lists the layout files from a given directory
 *
 * You can use this function to get layouts from various folders in your web server.
 * It returns array of layouts with desctption, icon, etc
 *
 * This function caches the result in the 'templates' cache group
 *
 * @param bool|array|string $options
 * @return array|mixed
 *
 * @params $options['path'] if set i will look for layouts in this folder
 * @params $options['get_dynamic_layouts'] if set this function will scan for templates for the 'layout' module in all templates folders
 *
 *
 *
 *
 *
 */
function layouts_list($options = false)
{
    return \Microweber\Layouts::scan($options);
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

 


action_hook('mw_db_init_options', 'create_mw_shop_default_options');
function create_mw_shop_default_options()
{

    return mw('shop')->create_mw_shop_default_options();

}

action_hook('mw_admin_header_menu_start', 'mw_print_admin_menu_shop_btn');

function mw_print_admin_menu_shop_btn()
{
    $active = mw('url')->param('view');
    $cls = '';
    if ($active == 'shop') {
        $cls = ' class="active" ';
    }
    print '<li' . $cls . '><a href="' . admin_url() . 'view:shop">' . _e('Online Shop', true) . '</a></li>';
}

action_hook('mw_admin_dashboard_quick_link', 'mw_print_admin_dashboard_orders_btn');

function mw_print_admin_dashboard_orders_btn()
{
    $active = mw('url')->param('view');
    $cls = '';
    if ($active == 'shop') {
        $cls = ' class="active" ';
    }
    $notif_html = '';
    $notif_count = mw('Microweber\Notifications')->get('module=shop&rel=cart_orders&is_read=n&count=1');
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

function checkout_confirm_email_send($order_id, $to = false, $no_cache = false)
{

    return mw('shop')->checkout_confirm_email_send($order_id, $to, $no_cache);
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
    return mw('users')->social_login($params);
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


    return mw('users')->register($params);


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
    return mw('users')->save($params);
}

api_expose('system_log_reset');

function system_log_reset($data = false)
{
    return \Microweber\Log::reset();
}

api_expose('delete_log_entry');

function delete_log_entry($data)
{
    return \Microweber\Log::delete_entry($data);
}

function delete_log($params)
{
    return \Microweber\Log::delete($params);
}

function save_log($params)
{
    return \Microweber\Log::save($params);
}

function get_log_entry($id)
{

    return \Microweber\Log::get_entry_by_id($id);

}


function get_log($params)
{
    return \Microweber\Log::get($params);

}

api_expose('delete_user');

function delete_user($data)
{
    return mw('users')->save($data);
}


function hash_user_pass($pass)
{
    return mw('user')->hash_pass($pass);

}


api_expose('captcha');


function captcha()
{
    return \Microweber\Utils\Captcha::render();
}


function user_update_last_login_time()
{

  //  return mw('user')->update_last_login_time();

}

api_expose('social_login_process');
function social_login_process()
{
    return mw('users')->social_login_process();


}


api_expose('user_reset_password_from_link');
function user_reset_password_from_link($params)
{
    return mw('users')->reset_password_from_link($params);

}

api_expose('user_send_forgot_password');
function user_send_forgot_password($params)
{

    return mw('users')->send_forgot_password($params);


}

function user_login_set_failed_attempt()
{

    return mw('user')->login_set_failed_attempt();

}

api_expose('is_logged');

function is_logged()
{

    return mw('user')->is_logged();


}

function user_set_logged($user_id)
{

    return mw('user')->make_logged($user_id);

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


















function get_custom_field_by_id($field_id) {

    return mw('fields')->get_by_id($field_id);


}

function get_custom_fields($table, $id = 0, $return_full = false, $field_for = false, $debug = false, $field_type = false, $for_session = false) {
    return mw('fields')->get($table, $id, $return_full, $field_for, $debug , $field_type , $for_session );
}

/*document_ready('test_document_ready_api');

 function test_document_ready_api($layout) {

 //   $layout = modify_html($layout, $selector = '.editor_wrapper', 'append', 'ivan');
 //$layout = modify_html2($layout, $selector = '<div class="editor_wrapper">', '');
 return $layout;
 }*/

/**
 * make_custom_field
 *
 * @desc make_custom_field
 * @access      public
 * @category    forms
 * @author      Microweber
 * @link        http://microweber.com
 * @param string $field_type
 * @param string $field_id
 * @param array $settings
 */
api_expose('make_custom_field');
function custom_field_names_for_table($table) {
    return mw('fields')->names_for_table($table);
}

function make_default_custom_fields($rel, $rel_id, $fields_csv_str) {
    return mw('fields')->make_default($rel, $rel_id, $fields_csv_str);
}

function make_custom_field($field_id = 0, $field_type = 'text', $settings = false) {
    return mw('fields')->make_field($field_id, $field_type, $settings);
}

api_expose('save_custom_field');

function save_custom_field($data) {
    return mw('fields')->save($data);
}

api_expose('reorder_custom_fields');

function reorder_custom_fields($data) {
    return mw('fields')->reorder($data);
}

api_expose('remove_field');

function remove_field($id) {
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
function make_field($field_id = 0, $field_type = 'text', $settings = false) {
    return mw('fields')->make($field_id, $field_type, $settings );

}




/**
 * `
 *
 * Prints the selected categories as an <UL> tree, you might pass several
 * options for more flexibility
 *
 * @param
 *        	array
 *
 * @param
 *        	boolean
 *
 * @author Peter Ivanov
 *
 * @version 1.0
 *
 * @since Version 1.0
 *
 */
function content_helpers_getCaregoriesUlTree($parent, $link = false, $active_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $orderby = false, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter = 0, $max_level = false, $list_tag = false, $list_item_tag = false, $active_code_tag = false) {
    return \mw('category')->html_tree($parent, $link , $active_ids , $active_code, $remove_ids, $removed_ids_code, $ul_class_name , $include_first , $content_type , $li_class_name , $add_ids , $orderby , $only_with_content , $visible_on_frontend , $depth_level_counter , $max_level, $list_tag , $list_item_tag , $active_code_tag );

}



api_expose('save_category');

function save_category($data, $preserve_cache = false) {
    return \Microweber\CategoryUtils::save(   $data, $preserve_cache);


}

api_expose('delete_category');

function delete_category($data) {

    return \Microweber\CategoryUtils::delete($data);

}






api_expose('reorder_categories');

function reorder_categories($data) {

    return \Microweber\CategoryUtils::reorder($data);

}

function get_categories_for_content($content_id, $data_type = 'categories') {
    if (intval($content_id) == 0) {

        return false;
    }

    return \mw('category')->get_for_content($content_id, $data_type);
}

function category_link($id) {

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
function get_category_by_id($id = 0) {
    return \mw('category')->get_by_id($id);

}

function get_category_children($parent_id = 0, $type = false, $visible_on_frontend = false) {

    return \mw('category')->get_children($parent_id, $type , $visible_on_frontend);
}

function get_page_for_category($category_id) {
    return \mw('category')->mw('content')->get_page($category_id);


}







action_hook('mw_edit_page_admin_menus', 'mw_print_admin_menu_selector');

function mw_print_admin_menu_selector($params = false)
{
    //d($params);
    $add = '';
    if (isset($params['id'])) {
        $add = '&content_id=' . $params['id'];
    }
    print module('view=edit_page_menus&type=menu' . $add);
}

function get_menu_items($params = false)
{
    return mw('content')->get_menu_items($params);
}



function get_menu($params = false)
{

    return mw('content')->get_menu($params);

}

api_expose('add_new_menu');
function add_new_menu($data_to_save)
{
    return \mw('Microweber\ContentUtils')->menu_create($data_to_save);

}

api_expose('menu_delete');
function menu_delete($id = false)
{
    return \mw('Microweber\ContentUtils')->menu_delete($id);

}

api_expose('delete_menu_item');
function delete_menu_item($id)
{

    return \mw('Microweber\ContentUtils')->menu_item_delete($id);

}

function get_menu_item($id)
{

    return \mw('Microweber\ContentUtils')->menu_item_get($id);

}

api_expose('edit_menu_item');
function edit_menu_item($data_to_save)
{
    return \mw('Microweber\ContentUtils')->menu_item_save($data_to_save);



}

api_expose('reorder_menu_items');

function reorder_menu_items($data)
{

    return \mw('Microweber\ContentUtils')->menu_items_reorder($data);

}

function menu_tree($menu_id, $maxdepth = false)
{
    return mw('content')->menu_tree($menu_id, $maxdepth );


}

function is_in_menu($menu_id = false, $content_id = false)
{
    return \mw('Microweber\ContentUtils')->is_in_menu($menu_id,$content_id);

}

api_hook('save_content', 'add_content_to_menu');

function add_content_to_menu($content_id, $menu_id=false)
{
    return \mw('Microweber\ContentUtils')->add_content_to_menu($content_id,$menu_id);


}



api_expose('reorder_modules');

function reorder_modules($data)
{

    return \mw('Microweber\Modules')->reorder_modules($data);
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

function get_all_functions_files_for_modules($options = false)
{
    $args = func_get_args();
    $function_cache_id = '';

    $function_cache_id = serialize($options);

    $cache_id = $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

    $cache_group = 'modules/functions';

    $cache_content = mw('cache')->get($cache_id, $cache_group);

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

    $dir = mw('Microweber\Utils\Files')->rglob($glob_patern, 0, $dir_name);

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

        mw('cache')->save($configs, $function_cache_id, $cache_group, 'files');

        return $configs;
    } else {
        return false;
    }
}


function get_layouts_from_db($params = false)
{


    return \Microweber\Layouts::get($params);


}

function save_element_to_db($data_to_save)
{
    return \Microweber\Layouts::save($data_to_save);

}

function get_modules_from_db($params = false)
{

    return mw('module')->get($params);
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
    return \Microweber\Layouts::delete_all();
}

function delete_module_by_id($id)
{
    return \mw('Microweber\Modules')->delete_module($id);
}

function delete_modules_from_db()
{
    return \mw('Microweber\Modules')->delete_all();
}

function is_module_installed($module_name)
{

    return mw('module')->is_installed($module_name);
}

function module_ico_title($module_name, $link = true)
{
    return mw('module')->is_installed($module_name,$link);
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
    return \mw('Microweber\Modules')->uninstall($params);

}

action_hook('mw_db_init_modules', 're_init_modules_db');

function re_init_modules_db()
{

    return \mw('Microweber\Modules')->update_db();

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

    return \mw('Microweber\Modules')->install($params);

}

function save_module_to_db($data_to_save)
{
    return \mw('Microweber\Modules')->save($data_to_save);

}

function get_saved_modules_as_template($params)
{
    return \mw('Microweber\Modules')->get_saved_modules_as_template($params);
}

api_expose('delete_module_as_template');
function delete_module_as_template($data)
{

    return \mw('Microweber\Modules')->delete_module_as_template($data);



}

api_expose('save_module_as_template');
function save_module_as_template($data_to_save)
{

    return \mw('Microweber\Modules')->save_module_as_template($data_to_save);
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

    return \mw('Microweber\Modules')->scan_for_modules($options);
}

action_hook('mw_scan_for_modules', 'scan_for_modules');
function scan_for_modules($options = false)
{
    return \mw('Microweber\Modules')->scan_for_modules($options);

}

action_hook('mw_scan_for_modules', 'get_elements');

function get_elements($options = array())
{
    return \mw('Microweber\Modules')->get_layouts($options);


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
    return mw('Microweber\Forms')-> save_list($params);

}


api_expose('delete_forms_list');

function delete_forms_list($data)
{
    return mw('Microweber\Forms')-> delete_list($data);
}

api_expose('delete_form_entry');

function delete_form_entry($data)
{
    return mw('Microweber\Forms')-> delete_entry($data);

}

api_expose('forms_list_export_to_excel');
function forms_list_export_to_excel($params)
{


    return mw('Microweber\Forms')-> export_to_excel($params);


}


function get_form_entires($params)
{
    return mw('Microweber\Forms')-> get_entires($params);

}

function get_form_lists($params)
{
    return mw('Microweber\Forms')-> get_lists($params);
}

api_expose('post_form');
function post_form($params)
{
    return mw('Microweber\Forms')-> post($params);


}




action_hook('mw_admin_settings_menu', 'mw_print_admin_backup_settings_link');

function mw_print_admin_backup_settings_link() {

    if(is_module_installed('admin/backup')){

        $active = mw('url')->param('view');
        $cls = '';
        $mname = module_name_encode('admin/backup/small');
        if ($active == $mname ) {
            $cls = ' class="active" ';
        }
        $notif_html = '';
        $url = admin_url('view:modules/load_module:'.$mname);
        print "<li><a class=\"item-".$mname."\" href=\"#option_group=".$mname."\">Backup</a></li>";
        //print "<li><a class=\"item-".$mname."\" href=\"".$url."\">Backup</a></li>";
    }
    //$notif_count = mw('Microweber\Notifications')->get('module=comments&is_read=n&count=1');
    /*if ($notif_count > 0) {
        $notif_html = '<sup class="mw-notif-bubble">' . $notif_count . '</sup>';
    }*/
    //print '<li' . $cls . '><a href="' . admin_url() . 'view:comments"><span class="ico icomment">' . $notif_html . '</span><span>Comments</span></a></li>';
}







api_expose('mw_post_update');
function mw_post_update() {

    $a = is_admin();
    if ($a != false) {
        mw('cache')->delete('db');
        mw('cache')->delete('update/global');
        mw('cache')->delete('elements/global');

        mw('cache')->delete('templates');
        mw('cache')->delete('modules/global');
        scan_for_modules();
        get_elements();
        exec_action('mw_db_init_default');
        exec_action('mw_db_init_modules');
        exec_action('mw_db_init');

    }

}




api_expose('mw_apply_updates');

function mw_apply_updates($params) {
    only_admin_access();
    $params = parse_params($params);
    $update_api = new \Microweber\Update();
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
                $res[] = $update_api -> install_version($upd_params['mw_version']);

            }
            if (isset($upd_params['elements']) and is_array($upd_params['elements'])) {
                foreach ($param['elements'] as $item) {
                    $res[] = $update_api -> install_element($item);
                }
            }
            if (isset($upd_params['modules']) and is_array($upd_params['modules'])) {
                foreach ($param['modules'] as $item) {
                    $res[] = $update_api -> install_module($item);
                }
            }
            if (isset($upd_params['module_templates']) and is_array($upd_params['module_templates'])) {
                foreach ($upd_params['module_templates'] as $k => $item) {
                    if (is_array($item)) {
                        foreach ($item as $layout_file) {
                            $res[] = $update_api -> install_module_template($k, $layout_file);

                        }

                    } elseif (is_string($item)) {
                        $res[] = $update_api -> install_module_template($k, $item);
                    }
                }
            }

        }

        if (is_array($res)) {
            mw_post_update();
            mw('Microweber\Notifications')->delete_for_module('updates');

        }
    }
    return $res;

}

function mw_updates_count() {
    $count = 0;
    $upd_count = mw_check_for_update();
    if(isset($upd_count['count'])){
        return intval($upd_count['count']);
    } else {
        return false;
    }


}

$mw_avail_updates = false;
function mw_check_for_update() {
    global $mw_avail_updates;
    if ($mw_avail_updates == false) {

        $update_api = new \Microweber\Update();

        $iudates = $update_api -> check();
        $mw_avail_updates = $iudates;

    }
    return $mw_avail_updates;

}


function admin_url($add_string = false) {

    $admin_url = c('admin_url');

    return mw_site_url($admin_url) . '/' . $add_string;
}



//api_expose('mw_send_anonymous_server_data');
// function used do send us the language files
function mw_send_anonymous_server_data($params) {
    only_admin_access();
    $update_api = new \Microweber\Update();



    if ($params != false) {
        $params = parse_params($params);
    } else {

    }


    if(method_exists($update_api,'send_anonymous_server_data')){
        $iudates = $update_api -> send_anonymous_server_data($params);

        return $iudates;
    } else {
        $params['site_url'] = mw_site_url();
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


action_hook('rte_image_editor_image_search', 'mw_print_rte_image_editor_image_search');

function mw_print_rte_image_editor_image_search() {
    $active = mw('url')->param('view');
    $cls = '';
    if($active == 'shop'){
        $cls = ' class="active" ';
    }
    print '<module type="files/admin" />';
}
