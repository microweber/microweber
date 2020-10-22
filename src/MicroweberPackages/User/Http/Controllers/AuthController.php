<?php

namespace MicroweberPackages\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\User\Http\Requests\LoginRequest;
use MicroweberPackages\User\User;

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
        $login = user_login($request->all());

        if (isset($login['success'])) {
            $success['token'] = auth()->user()->createToken('authToken');

            $success['user'] = auth()->user();
            return response()->json(['success' => $success])->setStatusCode(Response::HTTP_ACCEPTED);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * register api
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), $this->_rules($request->all()));
        $validator->validate();

        return User::create($request->all());
    }

    private function _rules($inputs)
    {
        $rules = [];

        $validateConfirmPassword = false;
        $validateEmail = false;
        $validateUsername = false;

        if (!isset($inputs['username']) || !isset($inputs['email'])) {
            $validateUsername = true;
        }

        if (isset($inputs['email']) && !isset($inputs['username'])) {
            $validateUsername = false;
            $validateEmail = true;
        }

        if (isset($inputs['email']) && isset($inputs['username'])) {
            $validateUsername = true;
            $validateEmail = true;
        }

        if ($validateEmail) {
            $rules['email'] = 'required|string|max:255|unique:users';
        }

        if (isset($inputs['confirm_password'])) {
            $validateConfirmPassword = true;
        }

        if ($validateUsername) {
            $rules['username'] = 'required|string|max:255|unique:users';
        }

        if ($validateConfirmPassword) {
            $rules['confirm_password'] = 'required|min:1|same:password';
        }

        $captcha_disabled = get_option('captcha_disabled', 'users') == 'y';
        if (!$captcha_disabled) {
            $rules['captcha'] = 'required|min:1|captcha';
        }

        $rules['password'] = 'required|min:1';

        return $rules;
    }
}