<?php

namespace MicroweberPackages\App\Http;

use Illuminate\Http\Request;

class RequestRoute extends Request {

    public static function postJson($route, $params)
    {
        $createRequest = self::create($route, 'POST', $params,[],[],['HTTP_ACCEPT'=>'application/json']);
        $response = app()->handle($createRequest);
        $responseBody = json_decode($response->getContent(), true);

        return $responseBody;
    }

    public function formatFrontendResponse($messages){

        $errors = [];
        $errors['error'] = $messages->first();
        $errors = array_merge($errors, $messages->toArray());

        if (isset($errors['captcha'])) {
            $errors['captcha_error'] = true;
            $errors['form_data_required'] = 'captcha';
            $errors['form_data_module'] = 'captcha';
        }

        if (isset($errors['terms'])) {
            $errors['error'] = _e('You must agree to terms and conditions', true);
            $errors['terms_error'] = true;
            $errors['form_data_required'] = 'terms';
            $errors['form_data_module'] = 'users/terms';
        }

        return $errors;
    }

}