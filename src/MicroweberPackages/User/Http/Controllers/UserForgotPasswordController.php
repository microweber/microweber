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
use MicroweberPackages\User\Models\User;
use Auth;


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


        if (!isset($request['email']) and isset($request['username'])) {
            $user_id = detect_user_id_from_params($request);
            if($user_id){
                $email_user = User::where('id',$user_id)->first();
                if($email_user){
                    $request->merge(['email' => $email_user->email]);
                }
            }
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

        $expiredText = "Password reset link is expired";

        $check = DB::table('password_resets')
            ->where('email', '=', $request->email)
            ->first();
        if (!$check) {
            return abort(response($expiredText, 401));
        }

        $abort = false;

        $createdAt = Carbon::parse($check->created_at);
        $now = Carbon::now();

        $diffInHours = $createdAt->diffInHours($now);


        if (!$check) {
            $abort = true;
        }

        if ($check) {
            if ($diffInHours > 1) {
                $abort = true;
            }
        }

        if($abort){
               DB::table('password_resets')
                ->where('email', '=', $request->email)
                ->delete();

            return abort(response($expiredText, 401));
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

                Auth::loginUsingId($user->id);
                $user->setRememberToken(Str::random(60));

                app()->auth->logoutOtherDevices($password);

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
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}