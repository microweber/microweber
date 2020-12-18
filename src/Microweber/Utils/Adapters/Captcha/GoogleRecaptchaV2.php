<?php

namespace Microweber\Utils\Adapters\Captcha;

class GoogleRecaptchaV2
{
    public function validate($key, $captcha_id = null, $unset_if_found = true)
    {


        $secretKey = get_option('recaptcha_v2_secret_key', 'captcha');
        $ip = $_SERVER['REMOTE_ADDR'];
        // post request to server
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) . '&response=' . urlencode($key);
        $response = mw()->http->url($url)->get();

        $responseKeys = @json_decode($response, true);

        // should return JSON with success as true
        if ($responseKeys and isset($responseKeys["success"]) and $responseKeys["success"]) {
            return true;
        }

        return false;
    }

    public function render($params = array())
    {

    }
}
