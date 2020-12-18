<?php

namespace MicroweberPackages\User\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MicroweberPackages\User\Models\User;

class UserVerifyController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:10,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));
        if (!$user) {
            throw new AuthorizationException();
        }

        if ($user->hasVerifiedEmail()) {
            return redirect(site_url());
        }

        if (!hash_equals((string)$request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException();
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect(site_url());
    }

    public function showResendForm(Request $request)
    {


        $user = User::find($request->route('id'));

        if (!$user) {
            throw new AuthorizationException();
        }

        if ($user->hasVerifiedEmail()) {
            return redirect(site_url());
        }

        if (!hash_equals((string)$request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException();
        }


        return view('user::email.resend',[
            'id'=>$request->id,
            'hash'=>$request->hash,
        ]);

     }

    public function sendVerifyEmail(Request $request)
    {

        $user = User::find($request->route('id'));

        if (!$user) {
            throw new AuthorizationException();
        }

        if ($user->hasVerifiedEmail()) {
            return redirect(site_url());
        }

        if (!hash_equals((string)$request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException();
        }

        $user->sendEmailVerificationNotification();
        return redirect(site_url());
    }
}