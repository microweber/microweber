<?php

namespace Microweber\Utils\Adapters\Captcha;

class GoogleRecaptchaV3
{
    public function validate($key, $captcha_id = null, $unset_if_found = true)
    {
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

        $response = mw()->http->url($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response)->get();
        $recaptcha = @json_decode($response);

        if ($recaptcha and isset($recaptcha->score) and $recaptcha->score >= $default_score) {
            return true;
        }

        return false;
    }

    public function render($params = array())
    {

    }
}
