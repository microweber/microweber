<?php

namespace MicroweberPackages\App\Http;

use Illuminate\Http\Request;

class RequestRoute extends Request
{

    /**
     * Creates a POST JSON Request based on a given URI and configuration.
     *
     * The information contained in the URI always take precedence
     * over the other information (server and parameters).
     *
     * @param string $uri The URI
     * @param string $method The HTTP method
     * @param array $parameters The query (GET) or request (POST) parameters
     * @param array $cookies The request cookies ($_COOKIE)
     * @param array $files The request files ($_FILES)
     * @param array $server The server parameters ($_SERVER)
     * @param string|resource|null $content The raw body data
     *
     * @return static
     */
    public static function postJson($route, $params)
    {


        $requestFactory = function(array $query, array $request, array $attributes, array $cookies, array $files, array $server, $content) {
            $server['x-no-throttle'] = true;
            return new RequestRoute($query, $request, $attributes, $cookies, $files, $server, $content);
        };

        self::setFactory($requestFactory);
        $createRequest = self::create($route, 'POST', $params, [], [], $_SERVER);
        $createRequest->headers->set('accept', 'application/json');

        $response = app()->handle($createRequest);

        return self::formatFrontendResponse($response);
    }

    public static function formatFrontendResponse($response)
    {
        $messages = json_decode($response->getContent(), true);

        $errors = [];

        if (!isset($messages['success'])) {
            if ($response->status() == 200 || $response->status() == 201) {
                $errors['success'] = true;
            }
        }

        if ($response->status() == 400 || $response->status() == 403|| $response->status() == 422) {
            $errors['error'] = true;
            $errors['success'] = false;
        }

        if (isset($messages['errors']['captcha'])) {
            $errors['captcha_error'] = true;
            $errors['form_data_required'] = 'captcha';
            $errors['form_data_module'] = 'captcha';
            $errors['error'] = _e('Invalid captcha answer!', true);
        }

        if (isset($messages['errors']['terms'])) {
            $errors['error'] = _e('You must agree to terms and conditions', true);
            $errors['terms_error'] = true;
            $errors['form_data_required'] = 'terms';
            $errors['form_data_module'] = 'users/terms';
        }

        if (isset($messages['error'])) {
            $errors['error'] = $messages['error'];
        }

        if (!isset($errors['error']) && isset($messages['errors'])) {
            $errors['error'] = reset($messages['errors']);
        }

        $messages = array_merge($errors, $messages);

        return $messages;
    }

}