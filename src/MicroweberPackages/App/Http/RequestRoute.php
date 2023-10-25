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
        $requestFactory = function (array $query, array $request, array $attributes, array $cookies, array $files, array $server, $content) use ($params) {
            if (!isset($params["x-no-throttle"])) {
                $server['x-no-throttle'] = true;
            } else {
                $server['x-no-throttle'] = $params["x-no-throttle"];
                unset($params["x-no-throttle"]);

            }
            return new RequestRoute($query, $request, $attributes, $cookies, $files, $server, $content);
        };

        self::setFactory($requestFactory);

        $createRequest = self::create($route, 'POST', $params, $_COOKIE, $_FILES, $_SERVER);
        $createRequest->headers->set('accept', 'application/json');

        $response = app()->handle($createRequest);
        return self::formatFrontendResponse($response);
    }

    public static function formatFrontendResponse($response)
    {

        $status = $response->status();

        if($status == 301 || $status == 302){
            return $response;
        }


        $messages = json_decode($response->getContent(), true);
        if (empty($messages)) {
            return $response->getContent();
        }

        $errors = [];
        if (!isset($messages['success'])) {
            if ($status == 200 || $status == 201) {
                $errors['success'] = true;
            }
        }

        if ($status == 400 || $status == 403 || $status == 422 || $status == 429) {
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

        if (isset($messages['errors'])) {
            //$errors['error'] = reset($messages['errors']);
            $allErrorsMsg = [];
            foreach ($messages['errors'] as $key => $val) {
                foreach ($val as $message) {
                    $allErrorsMsg[] = $message;
                }
            }
            $errors['message'] = implode("\n", $allErrorsMsg);

        }

        //$messages = array_merge($errors, $messages);
        if (!is_array($messages)) {
            $messages = [$messages];
        }
        $messages = array_merge($messages, $errors);

//        if (isset($messages['success']) and $messages['success'] == true and isset($messages['message'])) {
//             $messages['success'] = $messages['message'];
//        }


        if (isset($messages['error']) and $messages['error'] == true and isset($messages['success'])) {
            unset($messages['success']);
        }

        if (isset($messages['success']) and isset($messages['error']) and $messages['success'] == true) {
            unset($messages['error']);
        }

        return $messages;
    }

}
