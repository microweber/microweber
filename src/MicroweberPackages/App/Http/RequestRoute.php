<?php

namespace MicroweberPackages\App\Http;

use Illuminate\Http\Request;

class RequestRoute extends Request {

    /**
     * Creates a POST JSON Request based on a given URI and configuration.
     *
     * The information contained in the URI always take precedence
     * over the other information (server and parameters).
     *
     * @param string               $uri        The URI
     * @param string               $method     The HTTP method
     * @param array                $parameters The query (GET) or request (POST) parameters
     * @param array                $cookies    The request cookies ($_COOKIE)
     * @param array                $files      The request files ($_FILES)
     * @param array                $server     The server parameters ($_SERVER)
     * @param string|resource|null $content    The raw body data
     *
     * @return static
     */
    public static function postJson($route, $params)
    {
        $createRequest = self::create($route, 'POST', $params,[],[],$_SERVER);
        $createRequest->headers->set('accept', 'application/json');

        $response = app()->handle($createRequest);
        $responseBody = json_decode($response->getContent(), true);

        return self::formatFrontendResponse($responseBody);
    }

    public static function formatFrontendResponse($messages){

        $errors = [];

        if (isset($messages['errors'])) {
            $errors = $messages['errors'];
        }

        $errors['error'] = '';
        $errors = array_merge($errors, $messages);

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