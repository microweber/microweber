<?php
/* SUBSCRIBER FUNCTIONS */

function newsletter_unsubscribe($params)
{
    // code...
}

api_expose('newsletter_subscribe');
function newsletter_subscribe($params)
{

    $mod_id = 'newsletter';

    if (isset($params['mod_id'])) {
        $mod_id = $params['mod_id'];
    }


    $adm = mw()->user_manager->is_admin();
    if (defined('MW_API_CALL')) {
        $validate_token = mw()->user_manager->csrf_validate($params);
        if (!$adm) {
            if ($validate_token == false) {
                return array(
                    'error' => 'Invalid token!'
                );
            }
        }
    }

    $redir = newsletter_get_redirect_link_after_subscribe($mod_id);




    $rules = [
        'email' => 'required|email|unique:newsletter_subscribers'
    ];

    $input = Input::only('name', 'email', 'list_id', 'terms', 'captcha');
    $messages = array(
        'unique' => 'This email is already subscribed!'
    );


    $list_id = Input::get('list_id');
    if (!$list_id) {
        $list_id = get_option('list_id', $mod_id);
    }


    $validator = Validator::make($input, $rules, $messages);
    if ($validator->fails()) {
        newsletter_set_cookie_for_subscribed_user();
        $resp = array();
        $resp['error'] = $validator->messages();
        if ($redir) {
            $resp['redirect'] = $redir;
        }
        return $resp;
    }

    $needs_terms = get_option('require_terms', $mod_id) == 'y';
    $enable_captcha = get_option('enable_captcha', $mod_id) == 'y';


    if ($needs_terms) {
        $user_id_or_email = mw()->user_manager->id();
        if (!$user_id_or_email) {
            if (isset($input ['email'])) {
                $user_id_or_email = $input ['email'];
            }
        }

        if (!$user_id_or_email) {
            return array(
                'error' => _e('You must provide email address', true)
            );
        } else {
            $terms_and_conditions_name = 'terms_newsletter';

            $check_term = mw()->user_manager->terms_check($terms_and_conditions_name, $user_id_or_email);
            if (!$check_term) {
                if (isset($input ['terms']) and $input ['terms']) {
                    mw()->user_manager->terms_accept($terms_and_conditions_name, $user_id_or_email);
                } else {
                    return array(
                        'error' => _e('You must agree to terms and conditions', true),
                        'form_data_required' => 'terms',
                        'form_data_module' => 'users/terms'
                    );
                }
            }
        }
    }

    if ($enable_captcha) {
        if (!isset($input ['captcha'])) {
            return array(
                'error' => _e('Invalid captcha answer!', true),
                'captcha_error' => true,
                'form_data_required' => 'captcha',
                'form_data_module' => 'captcha'
            );
        } else {
            $validate_captcha = mw()->captcha->validate($input ['captcha']);
            if (!$validate_captcha) {
                return array(
                    'error' => _e('Invalid captcha answer!', true),
                    'captcha_error' => true,
                    'form_data_required' => 'captcha',
                    'form_data_module' => 'captcha'
                );
            }
        }
    }
    newsletter_set_cookie_for_subscribed_user();


    $confirmation_code = str_random(30);

    newsletter_save_subscriber([
        'email' => Input::get('email'),
        'name' => Input::get('name'),
        'list_id' => $list_id,
        'confirmation_code' => $confirmation_code
    ]);
    $msg = 'Thanks for your subscription!';

    $resp = array(
        'success' => $msg
    );

    if ($redir) {
        $resp['redirect'] = $redir;
    }


    return $resp;
}

function newsletter_get_redirect_link_after_subscribe($mod_id = false)
{
    if (!$mod_id) {
        $mod_id = 'newsletter';
    }
    $redir = get_option('newsletter_redirect_after_submit', $mod_id);
    return $redir;
}

function newsletter_get_subscribers($params)
{
    if (is_string($params)) {
        $params = parse_params($params);
    }
    $params ['table'] = "newsletter_subscribers";
    return db_get($params);
}


api_expose_admin('newsletter_get_subscriber');
function newsletter_get_subscriber($subscriber_id)
{
    $data = ['id' => $subscriber_id, 'single' => true];
    $table = "newsletter_subscribers";

    return db_get($table, $data);
}

api_expose_admin('newsletter_save_subscriber');
function newsletter_save_subscriber($data)
{

    // mw()->user_manager->ses


    $table = "newsletter_subscribers";

    if (!isset($data ['is_subscribed']) and !isset($data ['id'])) {
        $data ['is_subscribed'] = 1;
    }

    if (!isset($data['subscribed_for'])) {
        // Default list

    } else {
        if (isset($data['id'])) {

            newsletter_delete_subscriber_lists($data['id']);

            foreach ($data['subscribed_for'] as $list_id) {
                newsletter_save_subscriber_list($data['id'], $list_id);
            }
        }
    }

    $save_id = db_save($table, $data);

    if (isset($data['list_id'])) {
        newsletter_save_subscriber_list($save_id, $data['list_id']);
    }

    return $save_id;
}

function newsletter_delete_subscriber_lists($subscriber_id)
{

    $table = "newsletter_subscribers_lists";

    // Delete subscriber from old lists
    db_delete($table, $subscriber_id, 'subscriber_id');
}

function newsletter_save_subscriber_list($subscriber_id, $list_id)
{

    $table = "newsletter_subscribers_lists";

    // Add subscriber to list
    $data = array();
    $data['subscriber_id'] = $subscriber_id;
    $data['list_id'] = $list_id;

    return db_save($table, $data);
}

function newsletter_get_subscriber_lists($subscriber_id)
{
    $data = ['subscriber_id' => $subscriber_id];
    $data ['table'] = "newsletter_subscribers_lists";
    return db_get($data);
}

function newsletter_get_subscribers_for_list($list_id)
{

    $subscribers = DB::table('newsletter_subscribers_lists')
        ->join('newsletter_subscribers', 'newsletter_subscribers.id', '=', 'newsletter_subscribers_lists.subscriber_id')
        ->select('newsletter_subscribers_lists.*', 'newsletter_subscribers.name', 'newsletter_subscribers.email', 'newsletter_subscribers.is_subscribed')
        ->where('newsletter_subscribers.is_subscribed', 1)
        ->get();

    $readySubscribers = array();

    foreach ($subscribers as $subscriber) {
        $readySubscribers[] = array(
            'id' => $subscriber->id,
            'subscriber_id' => $subscriber->subscriber_id,
            'list_id' => $subscriber->list_id,
            'name' => $subscriber->name,
            'email' => $subscriber->email,
        );
    }

    return $readySubscribers;
}

api_expose_admin('newsletter_delete_subscriber');
function newsletter_delete_subscriber($params)
{
    if (isset($params ['id'])) {
        $table = "newsletter_subscribers";
        $id = $params ['id'];
        return db_delete($table, $id);
    }
}


function newsletter_check_cookie_for_subscribed_user()
{
    $hash = user_ip();
    $hash = md5($hash);
    if (isset($_COOKIE['mw-newsletter_is_subscribed']) and $_COOKIE['mw-newsletter_is_subscribed'] == $hash) {
        return true;
    }
}

function newsletter_set_cookie_for_subscribed_user()
{
    $hash = user_ip();
    $hash = md5($hash);


    setcookie('mw-newsletter_is_subscribed', $hash, time() + 45000, '/');
}