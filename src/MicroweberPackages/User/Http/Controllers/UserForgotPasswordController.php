<?php

namespace MicroweberPackages\User\Http\Controllers;

use _HumbugBox58fd4d9e2a25\Exception;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserForgotPasswordController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('throttle:10,1');
    }

    public function showForgotForm()
    {
        return app()->parser->process(view('user::auth.forgot-password')); 
    }

    public function send(Request $request)
    {
        $rules = [];
        if (get_option('captcha_disabled', 'users') !== 'y') {
            $rules['captcha'] = 'required|min:1|captcha';
        }

        $rules['email'] = 'required|email';



        $request->validate($rules);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($request->expectsJson()) {
            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['message' => __($status)], 200);
            } else {
                return response()->json(['message' => __($status)], 422);
            }
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request)
    {
         $check = DB::table('password_resets')
            ->where('email', '=', $request->email)
            ->first();

        if (!$check) {
            return abort(response("Password reset link is expired", 401));
        }
        if ($check) {
            if (Carbon::parse($check->created_at) > Carbon::now()->subHours(1)) {
                return abort(response("Password reset link is expired", 401));
            }
        }

        return view('user::auth.reset-password', [
            'email' => $request->email,
            'token' => $request->token,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:1|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),

            function ($user, $password) use ($request) {

                $user->forceFill([
                    'password' => $password
                ])->save();

                $user->setRememberToken(Str::random(60));

                event(new PasswordReset($user));
            }
        );


        if ($request->expectsJson()) {
            if ($status === Password::PASSWORD_RESET) {
                return response()->json(['message' => __($status)], 200);
            } else {
                return response()->json(['message' => __($status)], 422);
            }
        }

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('user.login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}