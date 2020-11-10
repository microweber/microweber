<?php

namespace MicroweberPackages\User\Http\Controllers;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Option\Facades\Option;
use MicroweberPackages\User\Http\Requests\LoginRequest;
use MicroweberPackages\User\Models\User;

class UserLoginController extends Controller
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

    public function loginForm()
    {
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

            $message = [];
            if (Auth::user()->is_admin == 1) {
                $message['token'] = auth()->user()->createToken('authToken');
            }

            $message['user'] = auth()->user();
            $message['success'] = 'You are logged in';
            return response()->json($message, 200);
        }


        if (!isset($request['email']) and isset($request['username'])) {
            $user_id = detect_user_id_from_params($request);
            if($user_id){
                $email_user = User::where('id',$user_id)->first();
                if($email_user){
                    $request->merge(['email' => $email_user->email]);
                    $request->offsetUnset('username');
                }
            }
        }



        $login = Auth::attempt($this->loginFields($request->only('username', 'email', 'password')),$remember = true);

       // dd($request->all());

        if ($login) {

            $userData = auth()->user();

            $isVerfiedEmailRequired = Option::getValue('register_email_verify', 'users');
            if ($isVerfiedEmailRequired) {

                if (!$userData->is_verfied) {
                    $message = [];
                    $message['error'] = 'Please verify your email address. Please check your inbox for your account activation email';
                    return response()->json($message, 401);
                }
            }

            $isApprovalRequired = Option::getValue('registration_approval_required', 'users');
            if ($isApprovalRequired) {

                if (!$userData->is_active) {


                    $message = [];
                    $message['error'] = 'Your account is awaiting approval';
                    return response()->json($message, 401);
                }
            }


            if (Auth::user()->is_admin == 1) {
                $userData->token = auth()->user()->createToken('authToken');
            }


            $response['success'] = _e('You are logged in', 1);

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
            $response['data'] = auth()->user();
            return new \MicroweberPackages\User\Http\Resources\UserResource($response);


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

        if (isset($request['username']) && $request['username'] != false and filter_var($request['username'], FILTER_VALIDATE_EMAIL)) {
            $request['email'] = $request['username'];
            unset($request['username']);
        }

        return $request;
    }

    public function logout()
    {
        return Auth::logout();
    }

    private function _isApprovalRequired()
    {


        // return false;
// @todo

//        $register_email_verify = get_option('register_email_verify', 'users');
//        $registration_approval_required = get_option('registration_approval_required', 'users');
//
//
//        $found = false;
//        $inputs = $this->all();
//        if ($inputs and isset($inputs['username']) and $inputs['username']) {
//            $found = User::where('username', $inputs['username'])->first();
//        } else if ($inputs and isset($inputs['email']) and $inputs['email']) {
//            $found = User::where('email', $inputs['username'])->first();
//        }

        //  throw new AuthorizationException('This action is unauthorized.');


        //   $user =


//            if ($user_data['is_active'] == 0) {
//
//                $registration_approval_required = get_option('registration_approval_required', 'users');
//                $register_email_verify = get_option('register_email_verify', 'users');
//                if ($registration_approval_required == 'y') {
//                    return array('error' => 'Your account is awaiting approval');
//                } elseif ($user_data['is_verified'] != 1 && $register_email_verify == 'y') {
//                    return array('error' => 'Please verify your email address. Please check your inbox for your account activation email');
//                } else {
//                    return array('error' => 'Your account has been disabled');
//                }
//            }

    }
}