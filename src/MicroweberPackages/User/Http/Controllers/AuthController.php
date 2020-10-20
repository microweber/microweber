<?php

namespace MicroweberPackages\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use MicroweberPackages\User\Http\Requests\LoginRequest;
use MicroweberPackages\User\Http\Requests\RegisterRequest;
use MicroweberPackages\User\User;

class AuthController extends Controller
{
  /*  public $middleware = [
        [
            'middleware'=>'throttle:130,1',
            'options'=>[]
        ]
    ];*/

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
     * @param \MicroweberPackages\User\Http\Requests\RegisterRequest $request

     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {



        echo 1;
        die();

        $registred = User::create($request->all());


        return $registred;
    }
}