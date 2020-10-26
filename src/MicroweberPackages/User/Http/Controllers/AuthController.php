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
              'middleware'=>'xss',
              'options'=>[]
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

        $login = Auth::attempt([
            $this->loginFields($request->all())
        ]);
        if ($login) {






            $success = [];
            if (Auth::user()->is_admin == 1) {
                $success['token'] = auth()->user()->createToken('authToken');
            }
            $success['user'] = auth()->user();
            return response()->json(['success' => $success])->setStatusCode(Response::HTTP_ACCEPTED);
        }

        return response()->json(['error' => 'Unauthorised'], 401);
    }

    public function loginFields($params)
    {
        $returnFields = [];

        if (!isset($params['username']) and isset($params['username_encoded']) and $params['username_encoded']) {
            $decoded_username = @base64_decode($params['username_encoded']);
            if (!empty($decoded_username)) {
                $returnFields['username'] = $decoded_username;
            } else {
                $returnFields['username'] = @base62_decode($params['username_encoded']);
            }
        }

        if (!isset($params['email']) and isset($params['email_encoded']) and $params['email_encoded']) {
            $decoded_email = @base64_decode($params['email_encoded']);
            if (!empty($decoded_email)) {
                $returnFields['email'] = $decoded_email;
            } else {
                $returnFields['email'] = @base62_decode($params['email_encoded']);
            }
        }

        if (!isset($params['password']) and isset($params['password_encoded']) and $params['password_encoded']) {
            $decoded_password = @base64_decode($params['password_encoded']);
            if (!empty($decoded_password)) {
                $returnFields['password'] = $decoded_password;
            } else {
                $returnFields['password'] = @base62_decode($params['password_encoded']);
            }
        }

        if ($params['username'] != false and filter_var($params['username'], FILTER_VALIDATE_EMAIL)) {
            $returnFields['email'] = $params['username'];
            unset($returnFields['username']);
        }

        return $returnFields;
    }

    public function logout()
    {
        return Auth::logout();
    }
}