<?php


function user_ip()
{
    $ipaddress = '127.0.0.1';

    if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ipaddress = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }  else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } else if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_REAL_IP'])) {
        $ipaddress = $_SERVER['HTTP_X_REAL_IP'];
    } else if (isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    }

    return $ipaddress;
}


if (!defined('MW_USER_IP')) {
    define('MW_USER_IP', user_ip());
}


function forgot_password_url()
{
    return app()->user_manager->forgot_password_url();
}

function register_url()
{
    return app()->user_manager->register_url();
}

function get_user_by_id($params = false)
{
    return app()->user_manager->get_by_id($params);
}

if (!function_exists('mw_csrf_token')) {
    function mw_csrf_token($form_name = false)
    {
        return app()->user_manager->csrf_token($form_name);
    }
}
function csrf_form($form_name = false)
{
    return app()->user_manager->csrf_form($form_name);
}

function logout_url()
{
    return app()->user_manager->logout_url();
}

function login_url()
{
    return app()->user_manager->login_url();
}

function profile_url()
{
    return app()->user_manager->profile_url();
}

function session_set($key, $val)
{
    return app()->user_manager->session_set($key, $val);
}

function session_append_array($key, $array)
{
    $oldArray = session_get($key);
    if (is_array($oldArray) && !empty($oldArray)) {
        $newArray = array_merge($oldArray, $array);
    } else {
        $newArray = $array;
    }

    return session_set($key, $newArray);
}

function session_get($name)
{
    return app()->user_manager->session_get($name);
}

function session_del($name)
{
    return app()->user_manager->session_del($name);
}

function session_end()
{
    return app()->user_manager->session_end();
}

function session_all()
{
    return app()->user_manager->session_all();
}

function api_login($api_key = false)
{
    return app()->user_manager->api_login($api_key);
}


function user_social_login($params)
{
    return app()->user_manager->social_login($params);
}


function logout()
{
    return app()->user_manager->logout();
}

function user_register($params)
{
    return app()->user_manager->register($params);
}


/**
 * Allows you to save users in the database.
 *
 * By default it have security rules.
 *
 * If you are admin you can save any user in the system;
 *
 * However if you are regular user you must post param id with the current user id;
 *
 * @param  $params
 * @param  $params ['id'] = $user_id; // REQUIRED , you must set the user id.
 *                 For security reasons, to make new user please use user_register() function that requires captcha
 *                 or write your own save_user wrapper function that sets  mw_var('force_save_user',true);
 *                 and pass its params to save_user();
 * @param  $params ['is_active'] = 1; //default is 'n'
 *
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
 * @return bool|int
 */
function save_user($params)
{
    return app()->user_manager->save($params);
}


function delete_user($data)
{
    return app()->user_manager->delete($data);
}


function social_login_process()
{
    return app()->user_manager->social_login_process();
}


function user_reset_password_from_link($params)
{
    return app()->user_manager->reset_password_from_link($params);
}


function user_send_forgot_password($params)
{
    return app()->user_manager->send_forgot_password($params);
}


function user_make_logged($params)
{
    return app()->user_manager->make_logged($params);
}


function user_login($params)
{
    return app()->user_manager->login($params);
}


function is_logged()
{

    $is = app()->user_manager->is_logged();
    if (defined('MW_API_CALL')) {
        app()->event_manager->trigger('mw.user.is_logged');
    }

    return $is;
}

function user_id()
{
    return app()->user_manager->id();
}

function has_access($function_name = '')
{
    return app()->user_manager->has_access($function_name);
}

function must_have_access($permission = '')
{
    if (!user_can_access($permission)) {
        $file = debug_backtrace()[0]['file'];
        mw_error('Permission denied! You dont have access to see this page. <br />File:' . $file);
    }
}

function only_admin_access()
{
    return app()->user_manager->admin_access();
}

function is_admin()
{
    if(app()->bound('user_manager')){
        return app()->user_manager->is_admin();
    }
}

function is_live_edit()
{
    if (!is_admin()) {
        return false;
    }


    $editModeParam = app()->url_manager->param('editmode');
    if ($editModeParam == 'n') {
        return false;
    }


    $editModeParam = app()->url_manager->param('editmode');
    if ($editModeParam == 'y') {
        return true;
    }

    $editModeParam2 = app()->url_manager->param('editmode',true);
    if ($editModeParam2 == 'y') {
        return true;
    }

    if(defined('IN_EDIT') and IN_EDIT){
        return true;
    }

    $editModeSession = app()->user_manager->session_get('editmode');

    if ($editModeSession == true and !defined('IN_EDIT')) {
        define('IN_EDIT', true);
        return true;
    }

    return $editModeSession;
}

/**
 * @function user_name
 * gets the user's FULL name
 *
 * @param        $user_id the id of the user. If false it will use the curent user (you)
 * @param string $mode full|first|last|username
 *                        'full' //prints full name (first +last)
 *                        'first' //prints first name
 *                        'last' //prints last name
 *                        'username' //prints username
 *
 * @return string
 */
function user_name($user_id = false, $mode = 'full')
{
    return app()->user_manager->name($user_id, $mode);
}


function user_email($user_id = false)
{
    return user_name($user_id, $mode = 'email');
}

function user_picture($user_id = false)
{
    return app()->user_manager->picture($user_id);
}

/**
 * @function get_users
 *
 * @param array|bool|string $params array|string;
 * @params $params['username'] string username for user
 * @params $params['email'] string email for user
 *
 *
 * @usage    get_users('email=my_email');
 *
 * @return array of users;
 */
function get_users($params = false)
{
    return app()->user_manager->get_all($params);
}

/**
 * get_user.
 *
 * get_user get the user info from the DB
 *
 * @category users
 *
 * @author   Microweber
 *
 * @link     http://microweber.com
 *
 * @param bool $id
 *
 *
 * @return array
 */
function get_user($id = false)
{
    return app()->user_manager->get($id);
}


function user_can_access($permission)
{

    $user = \Illuminate\Support\Facades\Auth::user();
    if (!$user) {
        return false;
    }
    if ($user->is_admin == 1) {
        return true;
    }

    return false;
   // return $user->can($permission);
}

function module_permissions($module)
{

    return \MicroweberPackages\Role\Repositories\Permission::generateModulePermissionsSlugs($module);
}

function user_can_destroy_module($module)
{
 //   $permissions = \MicroweberPackages\Role\Repositories\Permission::generateModulePermissionsSlugs($module);

    $user = \Illuminate\Support\Facades\Auth::user();
    if (!$user) {
        return false;
    }

    if ($user->is_admin == 1) {
        return true;
    }

   /* if ($user->can($permissions['destroy'])) {
        return true;
    }*/

    return false;
}

function user_can_view_module($module)
{

    //$permissions = \MicroweberPackages\Role\Repositories\Permission::generateModulePermissionsSlugs($module);

    $user = \Illuminate\Support\Facades\Auth::user();
    if (!$user) {
        return false;
    }

    if ($user->is_admin == 1) {
        return true;
    }

 /*   if ($user->can($permissions['index'])) {
        return true;
    }*/

    return false;

}



function detect_user_id_from_params($params){

    if (!empty($params)) {
        if (isset($params['username']) || isset($params['email'])) {

            if (isset($params['username']) && $params['username'] != false and filter_var($params['username'], FILTER_VALIDATE_EMAIL)) {
                $params['email'] = $params['username'];
            }

            $findUserId = false;
            $findByUsername = false;

            if (isset($params['username'])) {
                $findByUsername = \MicroweberPackages\User\Models\User::where('username', $params['username'])->first();
            }

            if ($findByUsername) {
                $findUserId = $findByUsername->id;
            } else {
                if (isset($params['email'])) {
                    $findByEmail = \MicroweberPackages\User\Models\User::where('email', $params['email'])->first();
                    if ($findByEmail) {
                        $findUserId = $findByEmail->id;
                    }
                }
            }

            if (!$findUserId) {
                return false;
            }

            return $findUserId;
        }
    }

    return false;
}
