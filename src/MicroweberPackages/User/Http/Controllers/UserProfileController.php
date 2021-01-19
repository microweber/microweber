<?php

namespace MicroweberPackages\User\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MicroweberPackages\User\Models\User;

class UserProfileController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:10,1')->only('verify', 'resend');
    }

    public function update(Request $request) {

        dd($request);

    }
}
