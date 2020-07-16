<?php

namespace MicroweberPackages\Roles\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers, ValidatesRequests,Package;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function($request, $next){
            if($this->guard()->check()){
                return redirect($this->redirectPath());
            }
            return $next($request);
        })->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin::_auth.login')->with('username', $this->username());
    }

    public function loggedOut(Request $request)
    {
        return redirect($this->redirectPath());
    }

    protected function redirectTo()
    {
        return '/'.config('admin.router.prefix', 'admin');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function username()
    {
        return config('admin.auth.login.username', 'email');
    }
}
