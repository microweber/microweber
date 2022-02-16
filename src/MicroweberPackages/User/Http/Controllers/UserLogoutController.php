<?php

namespace MicroweberPackages\User\Http\Controllers;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserLogoutController extends Controller
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
        return view('user::logout.index');
    }

    public function loginForm()
    {
//        $parsed = view('user::admin.auth.index');
//
//        return app()->parser->process($parsed);
    }

}
