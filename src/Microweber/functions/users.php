<?php


function forgot_password_url()
{

    return mw('user')->forgot_password_url();


}

function register_url()
{
    return mw('user')->register_url();


}

function login_url()
{

    return mw('user')->login_url();

}

function session_set($key, $val)
{


    return mw('user')->session_set($key, $val);
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


api_expose('delete_user');

function delete_user($data)
{
    return mw('user')->delete($data);
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

function user_picture($user_id = false)
{
    return mw('user')->picture($user_id);
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
function get_users($params = false)
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