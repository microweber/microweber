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
        $login = user_login($request->all());

        if (isset($login['success'])) {
            $success['token'] = auth()->user()->createToken('authToken');

            $success['user'] = auth()->user();
            return response()->json(['success' => $success])->setStatusCode(Response::HTTP_ACCEPTED);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}