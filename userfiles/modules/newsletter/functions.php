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
        'email', 'terms', 'captcha'
    );
    $messages = array('unique' => 'This email is already subscribed!');

    $validator = Validator::make($input, $rules, $messages);
    if ($validator->fails()) {
        return array('error' => $validator->messages());
    }


    $needs_terms = get_option('require_terms', 'newsletter') == 'y';
    $enable_captcha = get_option('enable_captcha', 'newsletter') == 'y';


    if ($needs_terms) {
        $user_id_or_email = mw()->user_manager->id();
        if (!$user_id_or_email) {
            if (isset($input['email'])) {
                $user_id_or_email = $input['email'];
            }
        }

        if (!$user_id_or_email) {
            return array(
                'error' => _e('You must provide email address', true),
            );
        } else {
            $terms_and_conditions_name = 'terms_newsletter';

            $check_term = mw()->user_manager->terms_check($terms_and_conditions_name, $user_id_or_email);
            if (!$check_term) {
                if (isset($input['terms']) and $input['terms']) {
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
        if (!isset($input['captcha'])) {
            return array(
                'error' => _e('Invalid captcha answer!', true),
                'captcha_error' => true,
                'form_data_required' => 'captcha',
                'form_data_module' => 'captcha'
            );

        } else {
            $validate_captcha = mw()->captcha->validate($input['captcha']);
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


event_bind('website.privacy_settings', function () {
    print '<h2>Newsletter settings</h2><module type="newsletter/privacy_settings" />';
});
