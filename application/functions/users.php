<?php

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

