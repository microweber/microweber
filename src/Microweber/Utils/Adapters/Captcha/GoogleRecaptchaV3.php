<?php

namespace Microweber\Utils\Adapters\Captcha;

class GoogleRecaptchaV3
{
    public function validate($key, $captcha_id = null, $unset_if_found = true)
    {
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = urlencode(get_option('recaptcha_v3_secret_key', 'captcha'));
        $recaptcha_response = urlencode($key);

        $response = mw()->http->url($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response)->get();
        $recaptcha = @json_decode($response);

        if ($recaptcha and isset($recaptcha->score) and $recaptcha->score >= 0.5) {
            return true;
        }

        return false;
    }

    public function render($params = array())
    {

    }
}
