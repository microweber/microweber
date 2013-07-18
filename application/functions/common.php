<?php

/**
 * Get array of content items from the database
 *
 * It accepts string or array as parameters. You can pass any db field name as parameter to filter content by it.
 * All parameter are passed to the get() function
 *
 * You can get and filter content and also order the results by criteria
 *
 *
 *
 *
 * @function get_content
 * @package Content
 *
 *
 * @desc  Get array of content items from the content DB table
 *
 * @uses get() You can use all the options of get(), such as limit, order_by, count, etc...
 *
 * @param mixed|array|bool|string $params You can pass parameters as string or as array
 * @params
 *
 * *Some parameters you can use*
 *  You can use all defined database fields as parameters
 *
 * .[params-table]
 *|-----------------------------------------------------------------------------
 *| Field Name     | Description      | Values
 *|------------------------------------------------------------------------------
 *| id            | the id of the content |
 *| is_active        | flag published or unpublished content | "y" or "n"
 *| parent            | get content with parent  | any id or 0
 *| created_by        | get by author id  | any user id
 *| created_on        | the date of creation  |
 *| updated_on        | the date of last edit  |
 *| content_type    | the type of the content | "page" or "post", anything custom
 *| subtype            | subtype of the content | "static","dynamic","post","product", anything custom
 *| url                | the link to the content |
 *| title            | Title of the content |
 *| content            | The html content saved in the database |
 *| description     | Description used for the content list |
 *| position        | The order position |
 *| active_site_template        | Current template for the content |
 *| layout_file        | Current layout from the template directory |
 *| is_deleted        | flag for deleted content |  "n" or "y"
 *| is_home        | flag for homepage |  "n" or "y"
 *| is_shop        | flag for shop page |  "n" or "y"
 *
 *
 * @return array|bool|mixed Array of content or false if nothing is found
 * @example
 * #### Get with parameters as array
 * <code>
 *
 * $params = array();
 * $params['is_active'] = 'y'; //get only active content
 * $params['parent'] = 2; //get by parent id
 * $params['created_by'] = 1; //get by author id
 * $params['content_type'] = 'post'; //get by content type
 * $params['subtype'] = 'product'; //get by subtype
 * $params['title'] = 'my title'; //get by title
 *
 * $data = get_content($params);
 * var_dump($data);
 *
 * </code>
 *
 * @example
 * #### Get by params as string
 * <code>
 *  $data = get_content('is_active=y');
 *  var_dump($data);
 * </code>
 *
 * @example
 * #### Ordering and sorting
 * <code>
 *  //Order by position
 *  $data = get_content('content_type=post&is_active=y&order_by=position desc');
 *  var_dump($data);
 *
 *  //Order by date
 *  $data = get_content('content_type=post&is_active=y&order_by=updated_on desc');
 *  var_dump($data);
 *
 *  //Order by title
 *  $data = get_content('content_type=post&is_active=y&order_by=title asc');
 *  var_dump($data);
 *
 *  //Get content from last week
 *  $data = get_content('created_on=[mt]-1 week&is_active=y&order_by=title asc');
 *  var_dump($data);
 * </code>
 *
 */
function get_content($params = false)
{

    return \Content::get($params);
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
/**
 * Gets a link for given content id
 *
 * If you don't pass id parameter it will try to use the current page id
 *
 * @param int $id The $id The id of the content
 * @return string The url of the content
 * @package Content
 * @see post_link()
 * @see page_link()
 *
 *
 * @example
 * <code>
 * print content_link($id=1);
 * </code>
 *
 */
function content_link($id = 0)
{
    return \Content::link($id);
}


function page_link($id = false)
{
    return \Content::link($id);
}

function post_link($id = 0)
{
    return \Content::link($id);
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
 *       print content_link($item['id']);
 *      }
 * }
 * </code>
 *
 */
function get_posts($params = false)
{
    return \Content::get($params);
}





/**
 * Defines all constants that are needed to parse the page layout
 *
 * It accepts array or $content that must have  $content['id'] set
 *
 * @example
 * <code>
 *  Define constants for some page
 *  $ref_page = get_content_by_id(1);
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

    return \Content::define_constants($content);
}


/**
 * Return the path to the layout file that will render the page
 *
 * It accepts array $page that must have  $page['id'] set
 *
 * @example
 * <code>
 *  //get the layout file for content
 *  $content = get_content_by_id($id=1);
 *  $render_file = get_layout_for_page($content);
 *  var_dump($render_file ); //print full path to the layout file ex. /home/user/public_html/userfiles/templates/default/index.php
 * </code>
 * @package Content
 * @subpackage Advanced
 */
function get_layout_for_page($page = array())
{
    return \Content::get_layout($page);

}


/**
 * Returns the homepage as array
 *
 * @category Content
 * @package Content
 */
function get_homepage()
{

    return \Content::homepage();
}

/**
 * Get the content as array by url
 *
 * @param string $url the url of the content
 * @param bool $no_recursive if true, it will not try to search for parent content
 * @return bool|string
 * @package Content
 */
function get_content_by_url($url = '', $no_recursive = false)
{
    return get_page_by_url($url, $no_recursive);
}


function get_page_by_url($url = '', $no_recursive = false)
{
    return \Content::get_by_url($url,$no_recursive);
}

/**
 * Get single content item by id from the content_table
 *
 * @param int $id The id of the content item
 * @return array
 * @category Content
 * @function  get_content_by_id
 *
 * @example
 * <pre>
 * $content = get_content_by_id(1);
 * var_dump($content);
 * </pre>
 *
 */
function get_content_by_id($id)
{
    return \Content::get_by_id($id);
}


/**
 * Get single content item by id from the content_table
 *
 * @param int|string $id The id of the page or the url of a page
 * @return array The page row from the database
 * @category Content
 * @function  get_page
 *
 * @example
 * <pre>
 * Get by id
 * $page = get_page(1);
 * var_dump($page);
 * </pre>
 * @example
 * <pre>
 * Get by url
 *
 * $page = get_page('home');
 * var_dump($page);
 *</pre>
 */
function get_page($id = 0)
{
    return \Content::get_page($id);

}

api_expose('reorder_content');
function reorder_content($params)
{


    return \ContentUtils::reorder($params);



}


function paging_links($base_url = false, $pages_count, $paging_param = 'curent_page', $keyword_param = 'keyword')
{

    return \Content::paging_links($base_url = false, $pages_count, $paging_param, $keyword_param);

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
    return \Content::paging($params);



}



function get_custom_fields_for_content($content_id, $full = true, $field_type = false)
{


    return \Content::custom_fields($content_id, $full , $field_type );


}


function save_edit($post_data)
{
    return \ContentUtils::save_edit($post_data);
}

api_expose('delete_content');

function delete_content($data)
{

    return \ContentUtils::delete($data);

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


    return \ContentUtils::save_content($data, $delete_the_cache);

}


//api_expose('save_content_field');

function save_content_field($data, $delete_the_cache = true)
{

    return \ContentUtils::save_content_field($data, $delete_the_cache);

}

api_expose('get_content_field_draft');
function get_content_field_draft($data)
{
    return \ContentUtils::edit_field_draft($data);
}

function get_content_field($data, $debug = false)
{


    return \Content::edit_field($data,$debug);


}

/**
 * Print nested tree of pages
 *
 * @example
 * <pre>
 * // Example Usage:
 * $pt_opts = array();
 * $pt_opts['link'] = "{title}";
 * $pt_opts['list_tag'] = "ol";
 * $pt_opts['list_item_tag'] = "li";
 * pages_tree($pt_opts);
 * </pre>
 *
 * @example
 * <pre>
 * // Example Usage to make <select> with <option>:
 * $pt_opts = array();
 * $pt_opts['link'] = "{title}";
 * $pt_opts['list_tag'] = " ";
 * $pt_opts['list_item_tag'] = "option";
 * $pt_opts['active_ids'] = $data['parent'];
 * $pt_opts['active_code_tag'] = '   selected="selected"  ';
 * $pt_opts['ul_class'] = 'nav';
 * $pt_opts['li_class'] = 'nav-item';
 *  pages_tree($pt_opts);
 * </pre>
 * @example
 * <pre>
 * // Other options
 * $pt_opts['parent'] = "8";
 * $pt_opts['include_first'] =  true; //includes the parent in the tree
 * $pt_opts['id_prefix'] = 'my_id';
 * </pre>
 *
 *
 *
 * @package Content
 * @param int $parent
 * @param bool $link
 * @param bool $active_ids
 * @param bool $active_code
 * @param bool $remove_ids
 * @param bool $removed_ids_code
 * @param bool $ul_class_name
 * @param bool $include_first
 * @return sting Prints the pages tree
 */
function pages_tree($parent = 0, $link = false, $active_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false)
{
    return \Content::pages_tree($parent , $link , $active_ids , $active_code , $remove_ids, $removed_ids_code, $ul_class_name, $include_first );
}

function mw_create_default_content($what)
{

    return \ContentUtils::create_default_content($what);

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

    return \ContentUtils::set_published($params);

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

    return \ContentUtils::set_unpublished($params);

}

function get_content_parents($id = 0, $without_main_parrent = false, $data_type = 'category')
{
    return \Content::get_parents($id , $without_main_parrent, $data_type);

}


/**
 *  Get the first parent that has layout
 *
 * @category Content
 * @package Content
 * @subpackage Advanced
 * @uses get_content_parents()
 * @uses get_content_by_id()
 */
function get_content_inherited_parent($content_id)
{
    return \Content::get_inherited_parent($content_id);



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

if (!defined("MODULE_DB_SHOP")) {
    define('MODULE_DB_SHOP', MW_TABLE_PREFIX . 'cart');
}

if (!defined("MODULE_DB_SHOP_ORDERS")) {
    define('MODULE_DB_SHOP_ORDERS', MW_TABLE_PREFIX . 'cart_orders');
}

if (!defined("MODULE_DB_SHOP_SHIPPING_TO_COUNTRY")) {
    define('MODULE_DB_SHOP_SHIPPING_TO_COUNTRY', MW_TABLE_PREFIX . 'cart_shipping');
}


action_hook('mw_db_init_options', 'create_mw_shop_default_options');
function create_mw_shop_default_options()
{

    return \Shop::create_mw_shop_default_options();

}

action_hook('mw_admin_header_menu_start', 'mw_print_admin_menu_shop_btn');

function mw_print_admin_menu_shop_btn()
{
    $active = url_param('view');
    $cls = '';
    if ($active == 'shop') {
        $cls = ' class="active" ';
    }
    print '<li' . $cls . '><a href="' . admin_url() . 'view:shop">' . _e('Online Shop', true) . '</a></li>';
}

action_hook('mw_admin_dashboard_quick_link', 'mw_print_admin_dashboard_orders_btn');

function mw_print_admin_dashboard_orders_btn()
{
    $active = url_param('view');
    $cls = '';
    if ($active == 'shop') {
        $cls = ' class="active" ';
    }
    $notif_html = '';
    $notif_count = \mw\Notifications::get('module=shop&rel=cart_orders&is_read=n&count=1');
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


    return \Shop::update_order($params);
}

api_expose('delete_client');

function delete_client($data)
{

    return \Shop::delete_client($data);

}

api_expose('delete_order');

function delete_order($data)
{

    return \Shop::delete_order($data);

}

function get_orders($params = false)
{

    return \Shop::get_orders($params);

}

function cart_sum($return_amount = true)
{
    return \Shop::cart_sum($return_amount);
}


api_expose('checkout_ipn');

function checkout_ipn($data)
{
    return \Shop::checkout_ipn($data);
}

api_expose('checkout');

function checkout($data)
{
    return \Shop::checkout($data);
}

function checkout_confirm_email_send($order_id, $to = false, $no_cache = false)
{

    return \Shop::checkout_confirm_email_send($order_id, $to, $no_cache);
}

api_expose('checkout_confirm_email_test');
function checkout_confirm_email_test($params)
{


    return \Shop::checkout_confirm_email_test($params);
}

api_expose('update_cart');

function update_cart($data)
{
    return \Shop::update_cart($data);


}

api_expose('update_cart_item_qty');

function update_cart_item_qty($data)
{

    return \Shop::update_cart_item_qty($data);
}

api_expose('remove_cart_item');

function remove_cart_item($data)
{

    return \Shop::remove_cart_item($data);
}

function get_cart($params)
{

    return \Shop::get_cart($params);


}

function payment_options($option_key = false)
{
    return \Shop::payment_options($option_key);


}









if (!defined("MW_DB_TABLE_USERS")) {
    define('MW_DB_TABLE_USERS', MW_TABLE_PREFIX . 'users');
}
if (!defined("MW_DB_TABLE_LOG")) {
    define('MW_DB_TABLE_LOG', MW_TABLE_PREFIX . 'log');
}


function user_login($params)
{
    return \User::login($params);
}

function api_login($api_key = false)
{

    return \User::api_login($api_key);

}

api_expose('user_social_login');
function user_social_login($params)
{
    return \Users::social_login($params);
}


api_expose('logout');

function logout()
{

    return \User::logout();

}

//api_expose('user_register');
api_expose('user_register');

function user_register($params)
{


    return \Users::register($params);


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
    return \Users::save($params);
}

api_expose('system_log_reset');

function system_log_reset($data = false)
{
    return \mw\Log::reset();
}

api_expose('delete_log_entry');

function delete_log_entry($data)
{
    return \mw\Log::delete_entry($data);
}

function delete_log($params)
{
    return \mw\Log::delete($params);
}

function save_log($params)
{
    return \mw\Log::save($params);
}

function get_log_entry($id)
{

    return \mw\Log::get_entry_by_id($id);

}


function get_log($params)
{
    return \mw\Log::get($params);

}

api_expose('delete_user');

function delete_user($data)
{
    return \Users::save($data);
}


function hash_user_pass($pass)
{
    return \User::hash_pass($pass);

}


api_expose('captcha');


function captcha()
{
    return \mw\utils\Captcha::render();
}


function user_update_last_login_time()
{

    return \User::update_last_login_time();

}

api_expose('social_login_process');
function social_login_process()
{
    return \Users::social_login_process();


}


api_expose('user_reset_password_from_link');
function user_reset_password_from_link($params)
{
    return \Users::reset_password_from_link($params);

}

api_expose('user_send_forgot_password');
function user_send_forgot_password($params)
{

    return \Users::send_forgot_password($params);


}

function user_login_set_failed_attempt()
{

    return \User::login_set_failed_attempt();

}

api_expose('is_logged');

function is_logged()
{

    return \User::is_logged();


}

function user_set_logged($user_id)
{

    return \User::make_logged($user_id);

}

function user_id()
{

    return \User::id();
}

function has_access($function_name)
{

    return \User::has_access($function_name);
}

function admin_access()
{
    return \User::admin_access();

}

function only_admin_access()
{
    return \User::admin_access();

}

function is_admin()
{

    return \User::is_admin();
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
    return \User::name($user_id, $mode);
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
    return \User::get_all($params);
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
    return \User::get($id);

}

/**
 * Generic function to get the user by id.
 * Uses the getUsers function to get the data
 *
 * @param
 *            int id
 * @return array
 *
 */
function get_user_by_id($id)
{
    return \User::get_by_id($id);
}

function get_user_by_username($username)
{
    return \User::get_user_by_username($username);
}

/**
 * Function to get user printable name by given ID
 *
 * @param  $id
 * @param string $mode
 * @return string
 * @example
 * <code>
 * //get user name for user with id 10
 * nice_user_name(10, 'full');
 * </code>
 * @uses get_user_by_id()
 */
function nice_user_name($id, $mode = 'full')
{
    return \User::nice_name($id, $mode);

}

function users_count()
{
    return \Users::count();
}











function get_custom_field_by_id($field_id) {

    return \CustomFields::get_by_id($field_id);


}

function get_custom_fields($table, $id = 0, $return_full = false, $field_for = false, $debug = false, $field_type = false, $for_session = false) {
    return \CustomFields::get($table, $id, $return_full, $field_for, $debug , $field_type , $for_session );
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
    return \CustomFields::names_for_table($table);
}

function make_default_custom_fields($rel, $rel_id, $fields_csv_str) {
    return \CustomFields::make_default($rel, $rel_id, $fields_csv_str);
}

function make_custom_field($field_id = 0, $field_type = 'text', $settings = false) {
    return \CustomFields::make_field($field_id, $field_type, $settings);
}

api_expose('save_custom_field');

function save_custom_field($data) {
    return \CustomFields::save($data);
}

api_expose('reorder_custom_fields');

function reorder_custom_fields($data) {
    return \CustomFields::reorder($data);
}

api_expose('remove_field');

function remove_field($id) {
    return \CustomFields::delete($id);
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
    return \CustomFields::make($field_id, $field_type, $settings );

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
function category_tree($params = false) {

    return \Category::tree($params);
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
    return \Category::html_tree($parent, $link , $active_ids , $active_code, $remove_ids, $removed_ids_code, $ul_class_name , $include_first , $content_type , $li_class_name , $add_ids , $orderby , $only_with_content , $visible_on_frontend , $depth_level_counter , $max_level, $list_tag , $list_item_tag , $active_code_tag );

}



api_expose('save_category');

function save_category($data, $preserve_cache = false) {
    return \CategoryUtils::save(   $data, $preserve_cache);


}

api_expose('delete_category');

function delete_category($data) {

    return \CategoryUtils::delete($data);

}



function get_categories($params, $data_type = 'categories') {
    return \Category::get($params,$data_type);


}

function get_category_items($params, $data_type = 'categories') {

    return \Category::get_items($params,$data_type);
}

api_expose('reorder_categories');

function reorder_categories($data) {

    return \CategoryUtils::reorder($data);

}

function get_categories_for_content($content_id, $data_type = 'categories') {
    if (intval($content_id) == 0) {

        return false;
    }

    return \Category::get_for_content($content_id, $data_type);
}

function category_link($id) {

    if (intval($id) == 0) {

        return false;
    }

    return \Category::link($id);

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
    return \Category::get_by_id($id);

}

function get_category_children($parent_id = 0, $type = false, $visible_on_frontend = false) {

    return \Category::get_children($parent_id, $type , $visible_on_frontend);
}

function get_page_for_category($category_id) {
    return \Category::get_page($category_id);


}

function get_category_parents($id = 0, $without_main_parrent = false, $data_type = 'category') {

    return \Category::get_parents($id = 0, $without_main_parrent , $data_type);
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
    return \Content::get_menu_items($params);
}



function get_menu($params = false)
{

    return \Content::get_menu($params);

}

api_expose('add_new_menu');
function add_new_menu($data_to_save)
{
    return \ContentUtils::menu_create($data_to_save);

}

api_expose('menu_delete');
function menu_delete($id = false)
{
    return \ContentUtils::menu_delete($id);

}

api_expose('delete_menu_item');
function delete_menu_item($id)
{

    return \ContentUtils::menu_item_delete($id);

}

function get_menu_item($id)
{

    return \ContentUtils::menu_item_get($id);

}

api_expose('edit_menu_item');
function edit_menu_item($data_to_save)
{
    return \ContentUtils::menu_item_save($data_to_save);



}

api_expose('reorder_menu_items');

function reorder_menu_items($data)
{

    return \ContentUtils::menu_items_reorder($data);

}

function menu_tree($menu_id, $maxdepth = false)
{
    return \Content::menu_tree($menu_id, $maxdepth );


}

function is_in_menu($menu_id = false, $content_id = false)
{
    return \ContentUtils::is_in_menu($menu_id,$content_id);

}

api_hook('save_content', 'add_content_to_menu');

function add_content_to_menu($content_id, $menu_id=false)
{
    return \ContentUtils::add_content_to_menu($content_id,$menu_id);


}




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

