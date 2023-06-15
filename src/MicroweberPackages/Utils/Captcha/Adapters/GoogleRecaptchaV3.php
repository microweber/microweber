<?php

namespace MicroweberPackages\Utils\Captcha\Adapters;

use Illuminate\Support\Facades\Request;

class GoogleRecaptchaV3
{
    public function validate($key, $captcha_id = null, $unset_if_found = true)
    {
        if (empty($key) && !empty(Request::post('g-recaptcha-response'))) {
            $key = Request::post('g-recaptcha-response');
        }
        if (empty($key) && !empty(Request::post('captcha'))) {
            $key = Request::post('captcha');
        }

        $default_score = 0.5;

        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = urlencode(get_option('recaptcha_v3_secret_key', 'captcha'));
        $recaptcha_response = urlencode($key);

        $recaptcha_v3_score = get_option('recaptcha_v3_score', 'captcha');
        if (!empty($recaptcha_v3_score)) {
            $default_score = floatval($recaptcha_v3_score);
        }

        if ($default_score > 1) {
            $default_score = 1;
        }

        $response = mw()->http->url($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response.'&score='.$default_score.'&remoteip='.user_ip())->get();
        $recaptcha = @json_decode($response,true);

        if ($recaptcha and isset($recaptcha["success"]) and $recaptcha["success"]) {
            return true;
        }

 

        return false;
    }

    public function render($params = array())
    {

    }
}
