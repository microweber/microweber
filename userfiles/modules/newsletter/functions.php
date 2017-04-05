<?php


/* SUBSCRIBER FUNCTIONS */


function newsletter_unsubscribe($params)
{
    //code...
}

api_expose('newsletter_subscribe');

function newsletter_subscribe($params)
{
    $adm = mw()->user_manager->is_admin();
    if (defined('MW_API_CALL')) {
        $validate_token = mw()->user_manager->csrf_validate($params);
        if (!$adm) {
            if ($validate_token == false) {
                return array('error' => 'Invalid token!');
            }
        }
    }
    $rules = [
        'email' => 'required|email|unique:newsletter_subscribers',
    ];

    $input = Input::only(
        'email'
    );
    $messages = array( 'unique' => 'This email is already subscribed!' );

    $validator = Validator::make($input, $rules,$messages);
    if ($validator->fails()) {
        return array('error' => $validator->messages());
    }

    $confirmation_code = str_random(30);

    newsletter_save_subscriber([
        'email' => Input::get('email'),
        'confirmation_code' => $confirmation_code
    ]);


    $msg = 'Thanks for your subscription!';

    return array('success' => $msg);


}


function newsletter_get_subscribers($params)
{
    if (is_string($params)) {
        $params = parse_params($params);
    }
    $params['table'] = "newsletter_subscribers";
    return db_get($params);
}

api_expose_admin('newsletter_save_subscriber');
function newsletter_save_subscriber($data)
{

    $table = "newsletter_subscribers";

    if (!isset($data['is_subscribed']) and !isset($data['id'])) {
        $data['is_subscribed'] = 1;
    }
    return db_save($table, $data);
}

api_expose_admin('newsletter_delete_subscriber');
function newsletter_delete_subscriber($params)
{
    if (isset($params['id'])) {
        $table = "newsletter_subscribers";
        $id = $params['id'];
        return db_delete($table, $id);
    }
}


/* EMAIL CAMPAIGN FUNCTIONS */

function newsletter_save_campaign($params)
{
    //code...
}


function newsletter_delete_campaign($params)
{
    //code...
}


function newsletter_send_campaign($params)
{
    //code...
}


