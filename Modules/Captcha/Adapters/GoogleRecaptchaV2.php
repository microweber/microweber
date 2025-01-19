<?php

namespace Modules\Captcha\Adapters;

use Illuminate\Support\Facades\Request;

class GoogleRecaptchaV2
{
    public function validate($key = false, $captcha_id = null, $unset_if_found = true)
    {
       // $key = false;

        if (empty($key) && !empty(Request::post('g-recaptcha-response'))) {
            $key = Request::post('g-recaptcha-response');
        }
        if (empty($key) && !empty(Request::post('captcha'))) {
            $key = Request::post('captcha');
        }
        $secretKey = get_option('recaptcha_v2_secret_key', 'captcha');

        // post request to server
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) . '&response=' . urlencode($key);
        $response = app()->http->url($url)->get();

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
