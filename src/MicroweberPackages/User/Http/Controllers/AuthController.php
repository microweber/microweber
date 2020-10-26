<?php

namespace MicroweberPackages\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\User\Http\Requests\LoginRequest;
use MicroweberPackages\User\Models\User;

class AuthController extends Controller
{
    public $middleware = [
        [
            'middleware' => 'xss',
            'options' => []
        ]
    ];


    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (Auth::check() && Auth::user()->is_admin == 1) {
            return redirect(admin_url());
        }

        $parsed = view('user::admin.auth.index');

        return app()->parser->process($parsed);
    }

    /**
     * login api
     *
     * @param \MicroweberPackages\User\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        if (Auth::check()) {

            $success = [];
            if (Auth::user()->is_admin == 1) {
                $success['token'] = auth()->user()->createToken('authToken');
            }

            $success['user'] = auth()->user();
            $success['success'] = 'You are logged in';

            return response()->json($success, 200);
        }

        $login = Auth::attempt($this->loginFields($request->only('username', 'email', 'password')));
        if ($login) {

            $response = [];
            if (Auth::user()->is_admin == 1) {
                $response['token'] = auth()->user()->createToken('authToken');
            }

            $response['user'] = auth()->user();
            $response['success']= _e('You are logged in', 1);

            $redirectParams = $request->only('redirect', 'where_to');
            if (isset($redirectParams['where_to']) and $redirectParams['where_to']) {
                if (Auth::user()->is_admin == 1 && $redirectParams['where_to'] == 'admin_content') {
                    $redirectParams['redirect'] = admin_url();
                } else {
                    $redirectParams['redirect'] = site_url();
                }
            }

            if (isset($redirectParams['redirect'])) {
                $response['redirect'] = $redirectParams['redirect'];
            }

            return response()->json($response)->setStatusCode(Response::HTTP_ACCEPTED);
        }

        return response()->json(['error' => 'Unauthorised request'], 401);
    }

    public function loginFields($request)
    {
        if (!isset($request['username']) and isset($request['username_encoded']) and $request['username_encoded']) {
            $decodedUsername = @base64_decode($request['username_encoded']);
            if (!empty($decodedUsername)) {
                $request['username'] = $decodedUsername;
            } else {
                $request['username'] = @base62_decode($request['username_encoded']);
            }
        }

        if (!isset($request['email']) and isset($request['email_encoded']) and $request['email_encoded']) {
            $decodedEmail = @base64_decode($request['email_encoded']);
            if (!empty($decodedEmail)) {
                $request['email'] = $decodedEmail;
            } else {
                $request['email'] = @base62_decode($request['email_encoded']);
            }
        }

        if (!isset($params['password']) and isset($request['password_encoded']) and $request['password_encoded']) {
            $decodedPassword = @base64_decode($request['password_encoded']);
            if (!empty($decodedPassword)) {
                $request['password'] = $decodedPassword;
            } else {
                $request['password'] = @base62_decode($request['password_encoded']);
            }
        }

        if ($request['username'] != false and filter_var($request['username'], FILTER_VALIDATE_EMAIL)) {
            $request['email'] = $request['username'];
            unset($request['username']);
        }

        return $request;
    }

    public function logout()
    {
        return Auth::logout();
    }
}