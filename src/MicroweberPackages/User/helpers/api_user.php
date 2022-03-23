<?php

api_expose('user_social_login');

api_expose('user_register');

api_expose('social_login_process');

api_expose('user_reset_password_from_link');


api_expose_admin('user_make_logged');

api_expose('user_login');

api_expose('is_logged');

api_expose('user_send_forgot_password');


api_expose_admin('users/register_email_send_test', function () {

    try {
        mw()->option_manager->override('users', 'register_email_enabled', true);
        $send =  mw()->user_manager->register_email_send();
        if ($send) {
            $user = Auth::user();

            return 'Email is send successfully to <b>'.$user->email.'</b>.';
        }
    } catch (Exception $e) {
        echo "Error Message: <br />" . $e->getMessage();
    }

});

api_expose('users/register_email_send', function ($params = false) {
    $uid = null;
    if (isset($params['user_id']) and is_admin()) {
        $uid = intval($params['user_id']);
    }
    return mw()->user_manager->register_email_send($uid);
});

api_expose_admin('users/forgot_password_email_send_test', function () {

     try {
         $user = Auth::user();
         mw()->option_manager->override('users', 'forgot_pass_email_enabled', true);
         $send = mw()->user_manager->send_forgot_password([
             'email'=>$user->email
         ]);
         if ($send) {
             return 'Email is send successfully to <b>'.$user->email.'</b>.';
         }
    } catch (Exception $e) {
        echo "Error Message: <br />" . $e->getMessage();
    }

});

api_expose_admin('users/search_authors', function ($params = false) {

    $return = array();

    $kw = false;
    if (isset($params['kw'])) {
        $kw = $params['kw'];
    }


    $all_users_search = array();
    $all_users_search['limit'] = 100;
    $all_users_search['fields'] = 'id,username,first_name,last_name,email,is_admin';
    if ($kw) {
        $all_users_search['keyword'] = $kw;
        $all_users_search['search_in_fields'] = 'id,username,first_name,last_name,email';
    }

    $all_users = get_users($all_users_search);
    if ($all_users) {
        foreach ($all_users as $user) {
            if (isset($user['id'])) {
                $user['display_name'] = user_name($user['id']);
                $user['picture'] = user_picture($user['id']);
                $return[] = $user;
            }
        }
    }
    return $return;
});


api_expose('users/verify_email_link', function ($params) {
    if (isset($params['key'])) {

        try {
            $decoded = mw()->format->decrypt($params['key']);
            if ($decoded) {
                $decoded = intval($decoded);
                $adminUser = \User::findOrFail($decoded);
                $adminUser->is_verified = 1;
                $adminUser->save();
                mw()->cache_manager->delete('users');
                mw()->cache_manager->delete('users/' . $decoded);
                $params['user_id'] = $decoded;
                mw()->event_manager->trigger('mw.user.verify_email_link', $params);

                return mw()->url_manager->redirect(site_url());
            }

        } catch (Exception $e) {
            echo 'Exception: ', $e->getMessage(), "\n";
        }
    }

});

