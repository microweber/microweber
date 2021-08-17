<?php

namespace MicroweberPackages\User\Http\Controllers;

use App\Http\Resources\User\UserResource;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Event\Event;
use MicroweberPackages\Option\Facades\Option;
use MicroweberPackages\User\Http\Requests\LoginRequest;
use MicroweberPackages\User\Models\User;
use Illuminate\Support\Facades\Session;

class UserLoginController extends Controller
{
    public $middleware = [
        [
            'middleware' => 'xss',
            'options' => []
        ]
    ];

    public function __construct()
    {
        event_trigger('mw.init');
    }

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
     */
    public function login(LoginRequest $request)
    {

        $requestLang = $request->post('lang');
		$redirectParams = $request->only('http_redirect', 'redirect', 'where_to');

		if (!empty($requestLang)) {
            mw()->lang_helper->set_current_lang($requestLang);
            \Cookie::queue('lang', $requestLang, 86400 * 30);
        }

        if (Auth::check()) {

            // This will be used for whmcs login redirect
			if (isset($redirectParams['http_redirect'])) {
                if (Auth::user()->is_admin === 1 && (isset($redirectParams['where_to']) && $redirectParams['where_to'] == 'admin_content')) {
                    return redirect(admin_url());
                } else {
                    return redirect(site_url());
                }
            }

            $message = [];
            if (Auth::user()->is_admin == 1) {
                //"message": "SQLSTATE[HY000] [1045] Access denied for user 'forge'@'localhost' (using password: NO) (SQL: select exists(select * from `oauth_personal_access_clients`) as `exists`)",
              //  $message['token'] = auth()->user()->createToken('authToken');
            }

            $message['data'] = auth()->user();
            $message['success'] = 'You are logged in';
            return response()->json($message, 200);
        }

        if (!isset($request['email']) and isset($request['username'])) {
            $userId = detect_user_id_from_params($request);

            if($userId){
                $userFind = User::where('id',$userId)->first();
                if(!empty($userFind->email)){
                    $request->merge(['email' => $userFind->email]);
                    $request->offsetUnset('username');
                }
                if(!empty($userFind->username)){
                    $request->merge(['username' => $userFind->username]);
                    $request->offsetUnset('email');
                }
            }
        }

         Session::flash('old_sid', Session::getId());
        $loginData = $this->loginFields($request->only('username', 'email', 'password'));


        $login = Auth::attempt($loginData,$remember = true);
        if ($login) {

            $userData = auth()->user();

            if (Auth::user()->is_admin == 0) {
                $isVerfiedEmailRequired = get_option('register_email_verify', 'users');

                if ($isVerfiedEmailRequired) {

                    if (!$userData->hasVerifiedEmail()) {
                        $message = [];
                        $message['error'] = 'Please verify your email address. Please check your inbox for your account activation email';
                        Auth::logout();
                        return response()->json($message, 200);
                    }
                }

                $isApprovalRequired = get_option('registration_approval_required', 'users');
                 if ($isApprovalRequired) {

                    if (!$userData->is_active) {
                        $message = [];
                        $message['error'] = 'Your account is awaiting approval';
                        Auth::logout();
                        return response()->json($message, 200);
                    }
                }
            }

//            if (Auth::user()->is_admin == 1) {
//                //"message": "SQLSTATE[HY000] [1045] Access denied for user 'forge'@'localhost' (using password: NO) (SQL: select exists(select * from `oauth_personal_access_clients`) as `exists`)",
//
//                //   $userData->token = auth()->user()->createToken('authToken');
//            }


            $response['success'] = _e('You are logged in', 1);
            app()->user_manager->login_set_success_attempt($request);

            if (isset($redirectParams['where_to']) and $redirectParams['where_to']) {
                if (Auth::user()->is_admin == 1 && (isset($redirectParams['where_to']) && $redirectParams['where_to'] == 'admin_content')) {
                    $redirectParams['redirect'] = admin_url();
                } else {
                    $redirectParams['redirect'] = site_url();
                }
            }

            if (isset($redirectParams['http_redirect'])) {
                if (Auth::user()->is_admin == 1 && (isset($redirectParams['where_to']) && $redirectParams['where_to'] == 'admin_content')) {
                    return redirect(admin_url());
                } else {
                    return redirect(site_url());
                }
            }

            if (isset($redirectParams['redirect'])) {
                $response['redirect'] = $redirectParams['redirect'];
            }

            $response['data'] = auth()->user();


            return new  JsonResource($response);
        }

        app()->user_manager->login_set_failed_attempt($request);

        return response()->json(['error' =>_e( 'Wrong username or password.',true)], 401);
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
