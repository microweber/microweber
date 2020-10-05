<?php

namespace MicroweberPackages\User\Http\Controllers;

use Illuminate\Support\Facades\Request;

class AuthController
{
    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parsed = view('user::admin.auth.index');

        return app()->parser->process($parsed);;
    }

}